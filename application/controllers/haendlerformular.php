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
		
		$this->data['haendler'] = $this->haendler;
		$this->data['veloquery'] = Velo::getAll($this->haendler->id);
		$this->data['querformat'] = true;
		$this->load->view('haendlerformular/formular', $this->data);
		return ;
	}
	
	
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
			$ret = $ret && $myVelo->save();
		}
		
		if ($ret) {
			$this->session->set_flashdata('success', 'Velos wurden gespeichert.');
			redirect('haendlerformular/index');
		} else {
			$this->session->set_flashdata('error', 'Beim Speichern ist etwas fehlgeschlagen. Bitte kontrollieren Sie die Angaben zu Ihren Velos noch einmal!');
		}
		
		redirect('haendlerformular/index');
	}
	
	
}
