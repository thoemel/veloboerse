<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Klasse für die Auszahlung von Privatvelos.
 * Die Händlerauszahlung erfolgt über die Händleradmin
 * @author thoemel
 */
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
		$ichwill = 'debuggen';
		if ($this->session->flashdata('gespeichertesVelo')) {
			$myVelo = new Velo();
			try {
				$myVelo->find($this->session->flashdata('gespeichertesVelo'));
			} catch (Exception $e) {
				log_message('error', 'Auszahlung::formular_private() - Velo nicht gefunden');
				$this->session->set_flashdata('error', 'Fehler. Das angeblich ausbezahlte Velo konnte nicht gefunden werden.');
				redirect('auszahlung/formular_private');
				return ;
			}
			$this->addData('velo', $myVelo);
			$keineProvision = ('yes' == $myVelo->keine_provision) ? 'Ja' : 'Nein';
			$this->addData('keineProvision', $keineProvision);
		}
		
		$this->addData('newStatistics', Statistik::cashMgmt());
		
		$this->load->view('auszahlung/einstieg_private', $this->data);
	}
	
	
	/**
	 * Per Default auf formular_index umleiten.
	 */
	public function index()
	{
		$this->formular_private();
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
			if (empty($quittungNr)) {
				$this->session->set_flashdata('error', 'Keine Quittungs-Nummer eingegeben. Bitte kontrolliere, ob das letzte Velo korrekt abgehandelt wurde!');
			}
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
		
		// Heading
		$this->addData('divAround', '<div>');
		$this->addData('h1', 'Auszahlung bestätigen');
		if ('yes' == $myVelo->ausbezahlt) {
			$this->addData('divAround', '<div class="alert-error">');
			$this->addData('h1', 'Schon ausbezahlt');
		} 
		if ('no' == $myVelo->verkauft) {
			$this->addData('divAround', '<div class="alert-warning">');
			$this->addData('h1', 'Noch nicht verkauft');
		}
		if (1 == $myVelo->gestohlen) {
			$this->addData('divAround', '<div class="alert-error">');
			$this->addData('h1', 'Gestohlen');
		}
		
		$this->addData('velo', $myVelo);
		$this->addData('auszahlung_betrag', ($myVelo->preis - Velo::getProvision($myVelo->preis)));
		$this->addData('hideNavi', true);
		
		$this->load->view('auszahlung/kontrollblick', $this->data);
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
		
		if (false !== $this->input->post('bemerkungen')) {
			$myVelo->bemerkungen = $this->input->post('bemerkungen');
		}
	
		$myVelo->ausbezahlt = 'yes';
		$myVelo->keine_provision = $noProvision;
	
		$success = $myVelo->save();
		if (!$success) {
			log_message('error', 'Auszahlung speichern fuer Quittung Nr. '.$myVelo->id.' ging schief.');
			$this->session->set_flashdata('error', 'Auszahlung speichern ging schief.');
		} else {
			$this->session->set_flashdata('success', 'Auszahlung wurde gespeichert.');
			$this->session->set_flashdata('gespeichertesVelo', $myVelo->id);
		}
		
		$ichwill = 'debuggen';
		if ('yes' === $this->input->post('auszahlung_summieren')) {
			if (false !== $this->session->userdata('summierte_auszahlung')) {
				$diese_auszahlung = $myVelo->preis;
				if ($myVelo->keine_provision == 'no') {
					$diese_auszahlung -= Velo::getProvision($myVelo->preis);
				}
				$summierte_auszahlung = $this->session->userdata('summierte_auszahlung');
				$summierte_auszahlung[] = array(
						'quittungsNr'=>$myVelo->id,
						'auszuzahlen'=>$diese_auszahlung
				);
			}
			$this->session->set_userdata('summierte_auszahlung', $summierte_auszahlung);
		} else {
			if (false !== $this->session->userdata('summierte_auszahlung')) {
				$this->session->unset_userdata('summierte_auszahlung');
			}
		}
	
		redirect('auszahlung/formular_private');
		return;
	} // End of function speichern_private
	
	
} // End of class Auszahlung

// EOF
