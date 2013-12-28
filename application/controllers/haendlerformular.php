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
		$this->load->view('haendlerformular/formular', $this->data);
		return ;
	}
	
	
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
