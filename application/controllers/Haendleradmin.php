<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Haendleradmin extends MY_Controller {

	
	public function __construct()
	{
		parent::__construct();

		// All methods require user to be logged in.
		$this->requireLoggedIn();
		
		$this->load->model('haendler');
	}
	
	
	/**
	 * Abrechnungs-Tabelle für einen Händler 
	 * @param int	$haendler_id
	 */
	public function abrechnung($haendler_id)
	{
		// Input validierung
		$haendler_id = intval($haendler_id);
		if (empty($haendler_id)) {
			$this->session->set_flashdata('error', 'Zuerst Händler auswählen.');
			redirect('haendleradmin');
		}
		if (!Haendler::istRegistriert($haendler_id)) {
			$this->session->set_flashdata('error', 'Kein Händler mit dieser ID im System.');
			redirect('haendleradmin');
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
			$thisVelo['preis']	= $velo->preis;
			$thisVelo['bemerkungen']	= $velo->bemerkungen;
			if ('yes' == $velo->verkauft) {
				$thisVelo['verkauft']	= 'x';
				$thisVelo['unverkauft']	= '&nbsp;';
				$countVerkauft++;
			} else {
				$thisVelo['verkauft'] = '&nbsp;';
				$thisVelo['unverkauft']	= 'x';
				if (0 == $velo->gestohlen && 0 == $velo->storniert) {
					$countNichtVerkauft++;
				}
			}
			$thisVelo['abgeholt'] = ('yes' == $velo->abgeholt) ? 'x' : '&nbsp;';
			
			// Bemerkungen aus Zusatzfeldern
			$bem = '';
			if (1 == $velo->gestohlen) {
				$bem .= '<span class="badge">gestohlen</span>';
			}
			if (1 == $velo->storniert) {
				$bem .= '<span class="badge">storniert</span>';
			}
			if (1 == $velo->problemfall) {
				$bem .= '<button type="button" class="btn btn-warning hidden-print" title="Problemfall!">!</button>';
			}
			if (!empty($velo->bemerkungen)) {
				$bem .= '<button type="button" class="btn btn-info hidden-print" title="' . $velo->bemerkungen . '">i</button>';
			}
			$thisVelo['bem'] = $bem;
			
			$arrVelos[] = $thisVelo;
		}

		$preisVerkaufte = $haendler->sumAlleVerkauften();
		$provisionAbsolut = $preisVerkaufte
							* $haendler->provisionFactor;
		$einstellbebuehr =	$countNichtVerkauft * 10;
		$auszahlungBetrag = $haendler->sumAlleVerkauften()
							* (1 - $haendler->provisionFactor)
							- $einstellbebuehr
							- $haendler->busse
							- $haendler->standgebuehr;
		$iban = str_replace(' ', '', $haendler->iban);
		$iban = substr($iban, 0, 4) . ' ' . substr($iban, 4, 1) . 'XXX XXXX XXXX ' . substr($iban, -5, 4) . ' ' . substr($iban, -1);
		
		
		$this->addData('haendler', $haendler);
		$this->addData('preisVerkaufte', (number_format($preisVerkaufte, 2)));
		$this->addData('provisionAbsolut', (number_format($provisionAbsolut, 2)));
		$this->addData('einstellgebuehr', (number_format($einstellbebuehr, 2)));
		$this->addData('auszahlungBetrag', (number_format($auszahlungBetrag, 2)));
		$this->addData('arrVelos', $arrVelos);
		$this->addData('countNichtVerkauft', $countNichtVerkauft);
		$this->addData('countVerkauft', $countVerkauft);
		$this->addData('iban', $iban);
		
	
		$this->load->view('haendleradmin/abrechnung', $this->data);
		
		return;
	} // End of function abrechnung()
	
	
	/**
	 * Nach Auszahlung Status des Händlers auf "ausbezahlt" setzen
	 */
	public function abschluss($haendler_id)
	{
		$this->load->model('haendler');
		$this->load->model('velo');
		
		$success = true;
		
		$haendler_id = (int)$haendler_id;
		if (!Haendler::istRegistriert($haendler_id)) {
			$this->session->set_flashdata('error', 'Kein Händler mit dieser ID bekannt.');
			redirect('haendleradmin');
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
		
		redirect('haendleradmin');
		
		return $success;
	} // End of function abschluss()
	
	
	/**
	 * Liste mit allen Direktlinks für die Händler.
	 */
	public function direktlinks()
	{
		$this->data['liste'] = Haendler::getAll();
		$this->load->view('haendleradmin/direktlinks', $this->data);
	}
	
	
	/**
	 * Ersetzt die bestehenden Direktlinks durch neue.
	 * @param	int		$haendler_id	Nur für diesen Händler, falls id gegeben.
	 * @return	void
	 */
	public function direktLinksDeaktivieren($haendler_id = 0)
	{
		$success = Haendler::direktLinksDeaktivieren((int)$haendler_id);
		if (false == $success) {
			$this->session->set_flashdata('error', 'Direktlink(s) deaktivieren ist fehlgeschlagen.');
		} else {
			if (0 < $haendler_id) {
				$this->session->set_flashdata('success', 'Dieser Händler hat nun einen neuen Direktlink.');
			} else {
				$this->session->set_flashdata('success', 'Direktlinks deaktivieren war erfolgreich. Alle Händler haben nun neue Direktlinks.');
			}
		}
		
		redirect('haendleradmin/direktlinks');
		return;
	}
	
	
	/**
	 * Google-docs File empfangen und Import starten.
	 * Ich erwarte, dass ein csv à la Excel kommt. Also Semikolon als Delimiter
	 * und doppelten Anführungszeichen als Text-Wrapper.
	 * @param	int	$haendler_id
	 * @return	void
	 */
	public function import($haendler_id = NULL)
	{
		$success = false;
		
		if (!empty($haendler_id)) {
			$this->session->set_userdata('haendler_id', $haendler_id);
		} else {
			$haendler_id = $this->session->userdata('haendler_id');
		}
		
		
		if (!empty($_FILES)) {
			$success = $this->upload();
		}
		
		if ($success) {
			redirect('haendleradmin/index');
		} else {
			$this->load->view('haendleradmin/upload', $this->data);
		}
		
		return;
	}
	
	
	/**
	 * Importiere die Velos eines Händlers aus Google-Docs
	 * @param array $arrUpload	Aus der upload Library
	 * @return boolean			True falls erfolgreich
	 */
	private function importCSV($arrUpload)
	{
		$ret = true;
		
		$handle = fopen($arrUpload['full_path'], 'r');
		
		while (($arrLine = fgetcsv($handle, 0, "\t", '"')) !== false) {
			if (!is_numeric($arrLine[1])) {
// 				if ('Händler Nr.' == $arrLine[1]) {
// 					$haendler_id = $arrLine[2];
// 					if ($haendler_id != $this->session->userdata('haendler_id')) {
// 						$this->appendData('error', 'Händlernummer im File entspricht nicht jener der Browser Session.');
// 						return false;
// 					}
// 				}
				continue;
			}
			$myVelo = new Velo();
			$myVelo->id = $arrLine[1];
			$myVelo->preis = $arrLine[7];
			$myVelo->haendler_id = $this->session->userdata('haendler_id');
			$ret = $ret & $myVelo->save();
		}
		
		fclose($handle);
		
		return $ret;
	}
	
	
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->data['liste'] = Haendler::getAll();
		$this->load->view('haendleradmin/auswahl', $this->data);
	}
	
	
	/**
	 * Liste aller Velos eines Händlers
	 * @param string $haendler_id
	 */
	public function velos($haendler_id = '')
	{
		// Input validierung
		if (!empty($haendler_id)) {
			if (!Haendler::istRegistriert($haendler_id)) {
				$this->session->set_flashdata('error', 'Kein Händler mit dieser ID im System.');
				redirect('haendleradmin/index');
			}
			$this->session->set_userdata('haendler_id', intval($haendler_id));
		} else {
			$this->session->set_flashdata('error', 'Zuerst Händler auswählen.');
			redirect('haendleradmin/index');
		}
		
		
		$haendler = new Haendler();
		$haendler->find($haendler_id);
		$this->addData('haendler', $haendler);
		
		$veloQuery = Velo::getAll($haendler_id);
		$this->addData('veloQuery', $veloQuery);
		
		$this->load->view('haendleradmin/liste', $this->data);
	}
	
	
	public function loeschen($haendler_id)
	{
		if (!Haendler::istRegistriert($haendler_id)) {
			$this->session->set_flashdata('error', 'Falsche Händler-Nummer ' . intval($haendler_id) . '. Versuchs vielleicht mit neu einloggen.');
			redirect('haendleradmin/index');
		}
		
		$haendler = new Haendler();
		$haendler->find($haendler_id);
		
		if ($haendler->delete()) {
			$this->session->set_flashdata('success', 'Haendler wurde gelöscht.');
		} else {
			$this->session->set_flashdata('error', 'Haendler wurde nicht gelöscht.');
		}
		
		redirect('haendleradmin/index');
		return;
	}
	
	
	/**
	 * Quittungen für einen Händler anzeigen / bearbeiten
	 * @param string $haendler_id
	 */
	public function quittungen($haendler_id = '')
	{
		// Input validierung
		if (!empty($haendler_id)) {
			$this->session->set_userdata('haendler_id', intval($haendler_id));
		}
		
		if (!$this->session->userdata('haendler_id')) {
			$this->session->set_flashdata('error', 'Zuerst Händler auswählen.');
			redirect('haendleradmin/index');
		}
		
		$haendler = new Haendler();
		$haendler->find($haendler_id);
		$this->data['haendler'] = $haendler;
		$anzeigename = $haendler->firma;
		if (empty($anzeigename)) {
			$anzeigename = $haendler->person;
		}
		$this->data['anzeigename'] = $anzeigename;
		
		$myIds = array();
		$this->load->model('velo');
		$myVelos = Velo::getAll($haendler_id);
		if (0 < $myVelos->num_rows()) {
			foreach ($myVelos->result() as $row) {
				$myIds[] = $row->id;
			}
			sort($myIds);
		}
		$this->data['ids'] = $myIds;
		
		$this->load->view('haendleradmin/quittungen', $this->data);
		return;
	} // End of function quittungen()
	
	
	/**
	 * Weist eine Händler Quittungen zu.
	 * Übernimmt die Formulareingaben aus haendleradmin/quittungen.
	 */
	public function quittungenSpeichern()
	{
		// Formulareingaben prüfen
		$haendler_id = $this->input->post('haendler_id');
		if (!Haendler::istRegistriert($haendler_id)
			|| $haendler_id != $this->session->userdata('haendler_id')) {
			$this->session->set_flashdata('error', 'Falsche Händler-Nummer. Versuchs vielleicht mit neu einloggen.');
			redirect('haendleradmin/index');
		}
		
		$from = intval($this->input->post('range_from'));
		$to = intval($this->input->post('range_to'));
		if (!$from || !$to) {
			$this->session->set_flashdata('error', 'Unmögliche Eingabe.');
			redirect('haendleradmin/quittungen/' . $haendler_id);
		}
		
		// Velos registrieren
		$this->load->model('velo');
		$errorsForFlash = array();
		for ($id = $from; $id <= $to; $id++) {
			if (velo::istRegistriert($id)) {
				$errorsForFlash[] = 'Quittung ' . $id . ' war schon vergeben.';
				continue;
			}
			$myVelo = new Velo();
			$myVelo->id = $id;
			$myVelo->haendler_id = $haendler_id;
			$myVelo->save();
		}
		
		if (!empty($errorsForFlash)) {
			$this->session->set_flashdata('error', implode('<br>', $errorsForFlash));
		} else {
			$this->session->set_flashdata('success', 'Quittungen wurden gespeichert.');
		}
		
		redirect('haendleradmin/quittungen/' . $haendler_id);
	} // End of function quittungenSpeichern
	
	
	/**
	 * Google-docs File empfangen und Import starten.
	 * Ich erwarte, dass ein csv à la Excel kommt. Also Semikolon als Delimiter
	 * und doppelten Anführungszeichen als Text-Wrapper.
	 * @return	boolean	$success
	 */
	private function upload()
	{
		$success = true;
		
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv|CSV|text|TEXT';
		$config['max_size']	= '4096';
		$config['overwrite'] = true;
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload()) {
			$this->appendData('error', $this->upload->display_errors());
			$success = false;
		} else {
			$this->addData('upload_data', $this->upload->data());
			
			$success = $this->importCsv($this->upload->data());
			if (false === $success) {
				// TODO exakter
				$this->appendData('error', 'CSV Import fehlgeschlagen.');
			}
		}
		
		return $success;
	}
	
	/**
	 * Daten eines Händlers anzeigen / bearbeiten
	 * @param string $haendler_id
	 */
	public function haendlerconfig($haendler_id = '')
	{
		// Input validierung
		if (!empty($haendler_id)) {
			$this->session->set_userdata('haendler_id', intval($haendler_id));
		}
	
		if (!$this->session->userdata('haendler_id')) {
			$this->session->set_flashdata('error', 'Zuerst Händler auswählen.');
			redirect('haendleradmin/index');
		}
	
		$haendler = new Haendler();
		if ('' != $haendler_id) {
			$haendler->find($haendler_id);
		}
		
		$this->data['haendler'] = $haendler;
		$anzeigename = $haendler->firma;
		if (empty($anzeigename)) {
			$anzeigename = $haendler->person;
		}
		$this->data['anzeigename'] = $anzeigename;
	
/*		$myIds = array();
		$this->load->model('velo');
		$myVelos = Velo::getAll($haendler_id);
		if (0 < $myVelos->num_rows()) {
			foreach ($myVelos->result() as $row) {
				$myIds[] = $row->id;
			}
			sort($myIds);
		}
		$this->data['ids'] = $myIds;
	*/
		$this->load->view('haendleradmin/haendlerconfig', $this->data);
		return;
	} // End of function haendlerconfig()
	
	/**
	 * Weist eine Händler die persönlichen Angaben zu.
	 * Übernimmt die Formulareingaben aus haendleradmin/haendlerconfig.
	 */
	public function haendlerconfigSpeichern()
	{
		// Formulareingaben prüfen
		$haendler_id = $this->input->post('haendler_id');
		if (0 != $haendler_id) {
			if (!Haendler::istRegistriert($haendler_id)
			|| $haendler_id != $this->session->userdata('haendler_id')) {
				$this->session->set_flashdata('error', 'Falsche Händler-Nummer ' . intval($haendler_id) . '. Versuchs vielleicht mit neu einloggen.');
				redirect('haendleradmin/index');
			}
		}
	
		// Daten aus Formular lesen
		$firma = strval($this->input->post('input_Firma'));
		$person = strval($this->input->post('input_Person'));
		$adresse = strval($this->input->post('input_Adresse'));
		$email = strval($this->input->post('input_Email'));
		$telefon = strval($this->input->post('input_Telefon'));
		$bankverb = strval($this->input->post('input_Bankverb'));
		$iban = strval($this->input->post('input_Iban'));
		$kommentar = strval($this->input->post('input_Kommentar'));
		$busse = strval($this->input->post('input_busse'));
		$uptodate = strval($this->input->post('input_uptodate'));
		$anzahlVelos = strval($this->input->post('input_velos'));
		$standgebuehr = strval($this->input->post('input_standgebuehr'));
		
		// Neue Instanz von Haendler
		$myHandler  = new Haendler();
		if (0 != $haendler_id) {
			$myHandler->find($haendler_id);
		}
		
		// Überschreiben der Datenbank- mit den Formular-Werten
		// mToDo: Werte prüfen, z.B. auf nicht leer o.ä.??
		$myHandler->firma = $firma;
		$myHandler->person = $person;
		$myHandler->adresse = $adresse;
		$myHandler->email = $email;
		$myHandler->telefon = $telefon;
		$myHandler->bankverbindung = $bankverb;
		$myHandler->iban = $iban;
		$myHandler->kommentar = $kommentar;
		$myHandler->busse = $busse;
		$myHandler->uptodate = $uptodate;
		$myHandler->anzahlVelos = $anzahlVelos;
		$myHandler->standgebuehr = $standgebuehr;
		$myHandler->save();
		
	
		if (!empty($errorsForFlash)) {
			$this->session->set_flashdata('error', implode('<br>', $errorsForFlash));
		} else {
			$this->session->set_flashdata('success', 'Händler-Angaben wurden gespeichert (' . $firma . ').');
		}
	
		redirect('haendleradmin/index');
	} // End of function quittungenSpeichern
	
	
	/**
	 * Excel file fuer Versand Einladung Haendler
	 */
	public function versandExcel()
	{
		$content = array();
		$header = array('id','code','firma','adresse','person','email','telefon','anzahlVelos','uptodate');
		array_push($content, $header);
		foreach (Haendler::getAll()->result_array() as $row) {
			$line = array();
			foreach ($header as $column) {
				$myVal = $row[$column];
				if ('code' == $column) {
					$myVal = site_url('haendlerformular') . '/' . $myVal;
				}
				$line[] = '"' . $myVal . '"';
			}
			$content[] = $line;
		}
		$this->data['content'] = $content;
		
		$this->data['filename'] = "haendlerversand_export_" . date('Ymd') . ".xls";
		
		$this->load->view('csv', $this->data);
	}
}
