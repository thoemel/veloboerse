<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auszahlung extends MY_Controller {

	
	public function __construct()
	{
		parent::__construct();

		// All methods require user to be logged in.
		$this->requireLoggedIn();
	}
	
	
	/**
	 * Zeigt eine Einstiegsseite mit Introtext
	 *
	 */
	public function formular_private()
	{
		$this->load->view('auszahlung/einstieg_private', $this->data);
	}
	
	
	/**
	 * Zeigt die Liste der Händler.
	 */
	public function einstieg_haendler()
	{
		$this->addData('haendlerQuery', Haendler::getAll());
		$this->load->view('auszahlung/einstieg_haendler', $this->data);
		return;
	}


	/**
	 * Zeigt gewisse für die Auszahlung relevante Details an, damit wir 
	 * kontrollieren können, ob das Velo mit der Quittung übereinstimmt. 
	 * Namentlich Preis und (falls irgendwann implementiert) Foto werden angezeigt.
	 * Zudem wird die Provision und der auszuzahlende Betrag berechnet und 
	 * angezeigt.
	 */
	public function kontrollblick()
	{
		// Input validation
		$quittungNr = $this->input->post('id');
		if (10000 > $quittungNr) {
			$this->session->set_flashdata('error', 'Das ist ein Händlervelo. Auszahlung erfolgt nicht hier.');
			redirect('auszahlung/formular_private');
			return;
		}
		
		$myVelo = new Velo();
		try {
			$myVelo->find($quittungNr);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			$this->session->set_flashdata('error', 'Velo ist nicht registriert. Auszahlung nicht möglich.');
			redirect('auszahlung/formular_private');
			return;
		}
		
		if ('yes' == $myVelo->ausbezahlt || 'no' == $myVelo->verkauft) {
			$this->addData('divAround', '<div class="alert-error">');
		} else {
			$this->addData('divAround', '<div>');
		}
		$this->addData('velo', $myVelo);
		$this->addData('auszahlung_betrag', ($myVelo->preis - $myVelo->getProvision()));
		
		$this->load->view('auszahlung/kontrollblick', $this->data);
	}
	
	
	/**
	 * Nach Auszahlung Status des Händlers auf "ausbezahlt"
	 */
	public function speichern_haendler($haendler_id)
	{
		$this->load->model('haendler');
		$this->load->model('velo');
		
		$success = true;
		
		$haendler_id = (int)$haendler_id;
		if (!Haendler::istRegistriert($haendler_id)) {
			$this->session->set_flashdata('error', 'Kein Händler mit dieser ID bekannt.');
			redirect('auszahlung/einstieg_haendler');
			return;
		}
		
		// Alle Velos des Händlers als ausbezahlt registrieren
		foreach (Velo::getAll($haendler_id)->result() as $elem) {
			$thisVelo = new Velo();
			$thisVelo->find($elem->id);
			$thisVelo->ausbezahlt = 'yes';
			$success = $success && $thisVelo->save();
		}
		
		// Händler Status auf "ausbezahlt" setzen
		$myHaendler = new Haendler();
		$myHaendler->find($haendler_id);
		$myHaendler->setStatus('ausbezahlt');
		if ($myHaendler->save()) {
			$this->session->set_flashdata('success', 'Händler abgeschlossen');
		} else {
			$success = false;
			log_message('error', 'Haendler Save fehlgeschlagen beim Abschluss');
			$this->session->set_flashdata('error', 'Händler Abschluss fehlgeschlagen');
		}
		
		// TODO Was, wenn Success false ist?
		
		redirect('auszahlung/einstieg_haendler');
		
		return $success;
	}
	
	
	/**
	 * Auszahlung für Private registrieren
	 * @uses	post data
	 */
	public function speichern_private()
	{
		$quittungNr = (int)($this->input->post('id'));
		$noProvision = ($this->input->post('no_provision') == 'yes') ? 'yes' : 'no';
	
		$myVelo = new Velo();
		try {
			$myVelo->find($quittungNr);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			$this->session->set_flashdata('error', 'Velo ist nicht registriert. Auszahlung nicht möglich.');
			redirect('auszahlung/formular_private');
			return;
		}
	
		if ('yes' == $myVelo->ausbezahlt) {
			$this->session->set_flashdata('error', 'Auszahlung ist bereits erfolgt. Kein zweites Mal möglich.');
			redirect('auszahlung/formular_private');
			return;
		}
	
		$myVelo->ausbezahlt = 'yes';
		$myVelo->keine_provision = $noProvision;
	
		$success = $myVelo->save();
		if (!$success) {
			log_message('error', 'Auszahlung speichern fuer Quittung Nr. '.$myVelo->id.' ging schief.');
			$this->session->set_flashdata('error', 'Auszahlung speichern ging schief.');
		} else {
			$this->session->set_flashdata('success', 'Auszahlung wurde gespeichert.');
		}
	
		redirect('auszahlung/formular_private');
		return;
	} // End of function speichern_private
	
	
	/**
	 * Liste aller Velos eines Händlers 
	 * @param int	$haendler_id
	 */
	public function velos($haendler_id)
	{
		// Input validierung
		$haendler_id = intval($haendler_id);
		if (empty($haendler_id)) {
			$this->session->set_flashdata('error', 'Zuerst Händler auswählen.');
			redirect('auszahlung/einstieg_haendler');
		}
		if (!Haendler::istRegistriert($haendler_id)) {
			$this->session->set_flashdata('error', 'Kein Händler mit dieser ID im System.');
			redirect('auszahlung/einstieg_haendler');
		}
		
	
		$haendler = new Haendler();
		$haendler->find($haendler_id);
		
	
		$veloQuery = Velo::getAll($haendler_id);
		$arrVelos = array();
		$countVerkauft = 0;
		$countNichtVerkauft = 0;
		foreach ($veloQuery->result() as $velo) {
			$thisVelo = array();
			$thisVelo['id']		= $velo->id;
			$thisVelo['preis']		= $velo->preis;
			if ('yes' == $velo->verkauft) {
				$thisVelo['verkauft']	= 'x';
				$thisVelo['unverkauft']	= '&nbsp;';
				$countVerkauft++;
			} else {
				$thisVelo['verkauft'] = '&nbsp;';
				$thisVelo['unverkauft']	= 'x';
				$countNichtVerkauft++;
			}
			$thisVelo['abgeholt'] = ('yes' == $velo->abgeholt) ? 'x' : '&nbsp;';
			
			$arrVelos[] = $thisVelo;
		}

		$preisVerkaufte = $haendler->sumAlleVerkauften();
		$provisionAbsolut = $preisVerkaufte
							* $haendler->provisionFactor;
		$einstellbebuehr =	$countNichtVerkauft * 10;
		$auszahlungBetrag = $haendler->sumAlleVerkauften()
							* (1 - $haendler->provisionFactor)
							- $einstellbebuehr;
		$iban = str_replace(' ', '', $haendler->iban);
		$iban = substr($iban, 0, 4) . ' ' . substr($iban, 4, 1) . 'XXX XXXX XXXX ' . substr($iban, 17, 4) . ' ' . substr($iban, -1);
		
		
		$this->addData('haendler', $haendler);
		$this->addData('preisVerkaufte', (number_format($preisVerkaufte, 2)));
		$this->addData('provisionAbsolut', (number_format($provisionAbsolut, 2)));
		$this->addData('einstellgebuehr', (number_format($einstellbebuehr, 2)));
		$this->addData('auszahlungBetrag', (number_format($auszahlungBetrag, 2)));$this->addData('arrVelos', $arrVelos);
		$this->addData('countNichtVerkauft', $countNichtVerkauft);
		$this->addData('countVerkauft', $countVerkauft);
		$this->addData('iban', $iban);
		
	
		$this->load->view('auszahlung/velos_haendler', $this->data);
		
		return;
	} // End of function velos()
	
	
} // End of class Auszahlung

// EOF
