<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Haendlerformular extends MY_Controller {

	/**
	 * Haendler Model
	 * @var Haendler
	 */
	public $haendler = NULL;
	
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('haendler');
		$this->haendler = new Haendler();
	}
	
	
	/**
	 * CodeIgniters _remap Methode wird bei jedem Aufruf ausgeführt.
	 * @param String	$method	Zweites URL-Segment
	 * @param array		$params	Allfällige weitere URL-Segmente
	 * @return void
	 */
	public function _remap($method, $params = array())
	{
		// Falls URL auf existierende Methode zeigt, führe diese aus und fertig.
		if (method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $params);
		}
		
		// Falls URL keine Methode enthält, suche einen Händler mit entsprechendem Code
		if (0 < $haendler_id = $this->haendler->idFuerCode($method)) {
			$this->haendler->find($haendler_id);
			$this->session->set_userdata('haendler_id', $haendler_id);
			$this->index();
			return ;
		}
		
		// Falls weder Methode noch Händlercode gefunden, ist nix.
		show_404();
		return ;
	}
	
	
	/**
	 * Weist eine Händler die persönlichen Angaben zu.
	 * Übernimmt die Formulareingaben aus haendlerformular/haendlerconfig.
	 * Das darf weniger, als wenn über die Haendleradmin aufgerufen (login).
	 */
	public function haendlerconfigSpeichern()
	{
		// Formulareingaben prüfen
		$haendler_id = $this->input->post('haendler_id');
		if (!Haendler::istRegistriert($haendler_id)
		|| $haendler_id != $this->session->userdata('haendler_id')) {
			$this->session->set_flashdata('error', 'Falsche Händler-Nummer ' . intval($haendler_id) . '. Versuchs vielleicht mit neu einloggen.');
			redirect('haendleradmin/index');
		}
	
		// Daten aus Formular lesen
		// TODO Formularwerte prüfen
		$firma = strval($this->input->post('input_Firma'));
		$person = strval($this->input->post('input_Person'));
		$adresse = strval($this->input->post('input_Adresse'));
		$email = strval($this->input->post('input_Email'));
		$telefon = strval($this->input->post('input_Telefon'));
		$bankverb = strval($this->input->post('input_Bankverb'));
		$iban = strval($this->input->post('input_Iban'));
		$kommentar = strval($this->input->post('input_Kommentar'));
		$anzahlVelos = strval($this->input->post('input_velos'));
		
		// Neue Instanz von Haendler
		$myHandler  = new Haendler();
		$myHandler->find($haendler_id);
		
		// Überschreiben der Datenbank- mit den Formular-Werten
		$myHandler->firma = $firma;
		$myHandler->person = $person;
		$myHandler->adresse = $adresse;
		$myHandler->email = $email;
		$myHandler->telefon = $telefon;
		$myHandler->bankverbindung = $bankverb;
		$myHandler->iban = $iban;
		$myHandler->kommentar = $kommentar;
		$myHandler->uptodate = 1;
		$myHandler->anzahlVelos = $anzahlVelos;
		$myHandler->save();
		
	
		if (!empty($errorsForFlash)) {
			$this->session->set_flashdata('error', implode('<br>', $errorsForFlash));
		} else {
			$this->session->set_flashdata('success', 'Händler-Angaben wurden gespeichert (' . $firma . ').');
		}
	
		redirect('haendlerformular/index');
	} // End of function quittungenSpeichern
	
	
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		if ($this->haendler->id > 0) {
			// Händler wurde in _remap instanziiert
		} elseif ($this->session->userdata('haendler_id')) {
			$this->haendler->find($this->session->userdata('haendler_id'));
		} else {
			show_error('Sorry, Sie sind nicht berechtigt.');
		}
		
		$myVelos = Velo::getAll($this->haendler->id);
		
		if (0 == $this->haendler->uptodate) 
		{
			$this->data['haendler'] = $this->haendler;
			$this->load->view('haendlerformular/haendlerconfig' , $this->data);
			//return ;
		}
		elseif (1 == $this->haendler->uptodate AND 0 >= $myVelos->num_rows())
		{
			$this->data['haendler'] = $this->haendler;
			$this->load->view('haendlerformular/inBearbeitung' , $this->data);
		}
		elseif (1 == $this->haendler->uptodate AND 0 < $myVelos->num_rows())
		{
			if (isset($this->session->userdata['user_role']) 
				&& in_array($this->session->userdata['user_role'], array('superadmin','provelo'))) {
				$this->data['useTabindex'] = true;
			} else {
				$this->data['useTabindex'] = false;
			}
			$this->data['haendler'] = $this->haendler;
			$this->data['veloquery'] = Velo::getAll($this->haendler->id);
			$this->data['querformat'] = true;
			$this->load->view('haendlerformular/formular', $this->data);
		}
		return ;
	} // End of function index()
	
	
	/**
	 * Stellt das Händlerformular in einem PDF zum Ausdrucken zusammen.
	 * @param UUID $haendler_code	UUID des Händlers
	 */
	public function pdf($haendler_code)
	{
		if ($this->haendler->id > 0) {
			// Händler wurde in _remap instanziiert
		} elseif ($this->session->userdata('haendler_id')) {
			$this->haendler->find($this->session->userdata('haendler_id'));
		} else {
			show_error('Sorry, Sie sind nicht berechtigt.');
		}
		
		$veloquery = Velo::getAll($this->haendler->id);
		
		
		$this->load->library('pv_tcpdf');
		$pdf = new Pv_tcpdf('L');
		$pdf->SetMargins(PDF_MARGIN_LEFT, 20, 20);
		$pdf->AddPage();
		
		// Logo
		$pdf->Image(
				FCPATH . '/img/logo.png',
				$pdf->GetX(),
				$pdf->GetY(),
				0,
				10.0,
				'png',
				'',
				'M',
				true,
				300,
				'R');
		
		// Horizontale Linie
		$pdf->Ln();
		$pdf->SetLineWidth(0.2);
		$pdf->SetDrawColor(0,0,0);
		$pdf->Cell(0,1,'','B',1);
		$pdf->Ln(6);

		// Titel
		$title = 'Händler Nr. ' . $this->haendler->id . ': ' 
				. $this->haendler->firma . ' | ' 
				. $this->haendler->person;
		$pdf->SetFont('', 'B', 16);
		$pdf->SetTextColor(0,0,0);
		$pdf->Write(0, $title, '', false, 'C', true);
		$pdf->Ln(3);
		
		
		/*
		 * Tabelle
		 */
		$pdf->SetFont('', 'B', 12);
		// Header
		$thead = array('Quittung-Nr.', 'Preis', 'Typ', 'Farbe', 'Marke', 'Rahmen-Nr.');
		$colgroup = array (
				30,
				30,
				50,
				50,
				50,
				50
		);
		
		// Inhalte
		$tbody = array();
		foreach ($veloquery->result() as $velo) {
			$tbody[] = array(
				$velo->id,
				$velo->preis,
				$velo->typ,
				$velo->farbe,
				$velo->marke,
				$velo->rahmennummer
			);
		}
		$pdf->ColoredTable($thead, $tbody, $colgroup);

		$filename = 'Quittungsformular';
		$pdf->Output($filename, 'I');
		
		return ;
	} // End of function pdf()
	
	
	/**
	 * Speichert die Velos, die ein Händler eingegeben hat
	 */
	public function speichern()
	{
		$ret = true;
		$this->load->model('velo');
		$ids = $this->input->post('id');
		$preise = $this->input->post('preis');
		$farben = $this->input->post('farbe');
		$marken = $this->input->post('marke');
		$typen = $this->input->post('typ');
		$rahmennummern = $this->input->post('rahmennummer');
		$vignettennummern = $this->input->post('vignettennummer');
		$stornierte = NULL === $this->input->post('storniert') ? array() : $this->input->post('storniert');
		$countVelos = count($ids);
		for ($i = 0; $i < $countVelos; $i++) {
			$myVelo = new Velo();
			try {
				$myVelo->find($ids[$i]);
			} catch (Exception $e) {
				log_message('error', $e->getMessage());
				show_error('Ungültige Quittungsnummer.');
			}
			$myVelo->preis = $preise[$i];
			$myVelo->typ = $typen[$i];
			$myVelo->farbe = $farben[$i];
			$myVelo->marke = $marken[$i];
			$myVelo->rahmennummer = $rahmennummern[$i];
			$myVelo->vignettennummer = $vignettennummern[$i];
			// Checkboxen werden nur übermittelt, falls gecheckt. Darum ist der Value
			// auf die velo->id gesetzt.
			
			$myVelo->storniert = in_array($myVelo->id, $stornierte);
			$ret = $ret && $myVelo->save();
		}
		
		if ($ret) {
			$this->session->set_flashdata('success', 'Velos wurden gespeichert.');
		} else {
			$this->session->set_flashdata('error', 'Beim Speichern ist etwas fehlgeschlagen. Bitte kontrollieren Sie die Angaben zu Ihren Velos noch einmal!');
		}
		
		if (1 == $this->session->userdata('logged_in')) {
			redirect('haendleradmin/index');
		} else {
			redirect('haendlerformular/index');
		}
	}
	
	
}
