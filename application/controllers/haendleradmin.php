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
}
