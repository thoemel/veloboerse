<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kasse extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();

		// All methods require user to be logged in.
		$this->requireLoggedIn();
	}
	
	
	/**
	 * Zeigt gewisse für die Kasse relevante Details an, damit die kontrollieren können, ob das 
	 * Velo mit der Quittung übereinstimmt. 
	 * Namentlich Preis und Foto werden angezeigt.
	 */
	public function kontrollblick()
	{
		$quittungNr = $this->input->post('id');
		if (!Velo::istRegistriert($quittungNr)) {
			$this->session->set_flashdata('error', 'Keine gültige Quittungsnummer');
			redirect('kasse/index');
			return;
		}
		
		$myVelo = new Velo();
		$myVelo->find($quittungNr);
		
		if ('yes' == $myVelo->verkauft) {
			$this->addData('error', 'Das Velo wurde bereits verkauft. Wir können es nicht noch ein zweites Mal verkaufen.');
		}
		$this->addData('velo', $myVelo);
		
		$this->load->view('kasse/kontrollblick', $this->data);
	}
	
	
	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->load->view('kasse/formular', $this->data);
	}
	
	
	/**
	 * Schliesst den Verkauf ab.
	 * @uses	POST vars
	 */
	public function verkaufe()
	{
		$myVelo = new Velo();
		$quittungNr = $this->input->post('id');
		try {
			$myVelo->find($quittungNr);
		} catch (Exception $e) {
			$this->session->set_flashdata('error', 'Das Velo ist nicht registriert. Verkauf fehlgeschlagen.');
			redirect();
		}
		
		if ('yes' == $myVelo->verkauft) {
			$this->session->set_flashdata('error', 'Das Velo wurde schon früher verkauft. Verkauf fehlgeschlagen.');
			redirect();
		}

		$myVelo->verkauft = 'yes';
		$myVelo->zahlungsart = $this->input->post('zahlungsart');
		$myVelo->helfer_kauft = ('yes' == $this->input->post('helfer_kauft')) ? 'yes' : 'no';
		
		if ($myVelo->save()) {
			$this->data['success'] = 'Verkauf wurde registriert :-)';
		} else {
			$this->data['error'] = 'Verkauf nicht geklappt.';
		}
		
		
		$this->load->view('kasse/formular', $this->data);
	} // End of function verkaufe()
	
	
} // End of class Kasse
