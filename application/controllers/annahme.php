<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Annahme extends MY_Controller {

	
	public function __construct()
	{
		parent::__construct();
		
		// All methods require user to be logged in.
		$this->requireLoggedIn();
	}
	
	
	/**
	 * Der Einstieg zur Privatannahme. Hier wird nur ein Default-Text angezeigt und 
	 * die Action fürs Formular im Header bestimmt.
	 */
	public function einstieg_private()
	{
		$this->load->view('annahme/einstieg_private', $this->data);
		return;
	}
	
	
	/**
	 * Zeigt das Erfassungsformular an.
	 * 
	 * @param int	$id		Quittungsnummer. Falls eine angegeben, wird das Formular vorausgefüllt.
	 */
	public function formular_private($id = '') 
	{
		$id = intval($id);
		if ($this->input->post('id')) {
			$id = intval($this->input->post('id'));
		}
		if (0 == $id) {
			$this->session->set_flashdata('error', 'Quittungs-Nummer fehlt.');
			redirect('annahme/einstieg_private');
			return;
		}
		if (10000 > $id) {
			$this->session->set_flashdata('error', 'Quittungsnummern < 10000 sind Händlerquittungen. Annahme fehlgeschlagen.');
			redirect('annahme/einstieg_private');
			return;
		}
		if (Velo::istRegistriert($id)) {
			$this->session->set_flashdata('error', 'Quittungs-Nummer schon registriert.');
			redirect('annahme/einstieg_private');
			return;
		}
		
		$myVelo = new Velo();
		$myVelo->id = $id;
		$this->data['myVelo'] = $myVelo;
		$this->load->view('annahme/formular_private', $this->data);
	} // End of function formular_private
	
	
	/**
	 * Index Page for this controller.
	 * //TODO Brauchts die listen-Ansicht hier überhaupt?
	 */
	public function index()
	{
		$this->load->view('velos/liste', $this->data);
	}
	
	
	/**
	 * Liste aller Velos
	 * //TODO Brauchts die listen-Ansicht hier überhaupt?
	 */
	public function listing()
	{
		$this->data['liste'] = Velo::getAll();
		$this->load->view('velos/liste', $this->data);
	}
	
	
	/**
	 * Formulardaten entgegennehmen und verarbeiten
	 * Nur Private
	 */
	public function speichern_private()
	{
		// TODO: form validation
	
		$myVelo = new Velo();
		if (Velo::istRegistriert($this->input->post('id'))) {
			$this->session->set_flashdata('error', 'Diese Quittungs-Nummer ist schon registriert.');
			redirect('annahme/einstieg_private');
		}
		$myVelo->id = $this->input->post('id');
		$myVelo->preis = $this->input->post('preis');
		$myVelo->kein_ausweis = $this->input->post('kein_ausweis');
	
		// Bild Upload
		// 		$config['upload_path'] = './img/velos/';
		// 		$config['allowed_types'] = 'gif|jpg|png';
		// 		$config['max_size']	= '1024';
		// 		$config['max_width']  = '1024';
		// 		$config['max_height']  = '768';
	
		// 		$this->load->library('upload', $config);
	
		// 		if ( ! $this->upload->do_upload('img')) {
		// 			$this->data['error'] = 'Bild konnte nicht gespeichert werden.';
		// 			log_message('error', $this->upload->display_errors());
		// 		} else {
		// 			$upload_data = $this->upload->data();
		// 			$myVelo->img = $upload_data['file_name'];
		// 		}
	
		$success = $myVelo->save();
		if (!$success) {
			$this->session->set_flashdata('error', 'Velo Annahme ging schief.');
			$this->data['myBike'] = $myVelo;
			$this->load->view('velos/single', $this->data);
		} else {
			$this->addData('success', 'Annahme ok.');
			$this->addData('preis', $myVelo->preis);
			$ausweisGezeigt = ('yes' == $myVelo->kein_ausweis) ? 'Nein' : 'Ja';
			$this->addData('ausweisGezeigt', $ausweisGezeigt);
			$this->load->view('annahme/einstieg_private', $this->data);
		}
	
		return;
		} // End of function speichern_private
	
	
}
