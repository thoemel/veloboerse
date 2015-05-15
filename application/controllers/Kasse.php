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
		
		// Prüfung, ob Velo überhaupt registriert ist.
		if (!Velo::istRegistriert($quittungNr)) {
			$this->session->set_flashdata('error', 'Keine gültige Quittungsnummer');
			redirect('kasse/index');
			return;
		}
		
		$myVelo = new Velo();
		$myVelo->find($quittungNr);
		
		// Velo darf nicht mehr verkauft werden, wenn schon abgeholt
		if ('yes' == $myVelo->abgeholt) {
			$this->session->set_flashdata('error', 'Hilfe! <br>Hol den Thoemel! <br>Das Velo ist als "abgeholt" registriert - das muss dringend geklärt werden!');
			redirect('kasse/index');
			return;
		}
		
		// Velo nicht ein zweites Mal verkaufen
		if ('yes' == $myVelo->verkauft) {
			$this->session->set_flashdata('error', 'Hilfe! <br>Hol den Thoemel! <br>Das Velo ist als "verkauft" registriert - das muss dringend geklärt werden!');
			redirect('kasse/index');
			return;
		}
		$this->addData('velo', $myVelo);
		$this->addData('hideNavi', true);
		
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
			$this->data['velo'] = $myVelo;
			$vonHelferGekauft = ('yes' == $myVelo->helfer_kauft) ? 'Ja' : 'Nein';
			$this->addData('vonHelferGekauft', $vonHelferGekauft);
		} else {
			$this->data['error'] = 'Verkauf nicht geklappt.';
		}
		
		
		$this->load->view('kasse/formular', $this->data);
	} // End of function verkaufe()
	
	
	/**
	 * Falls jemand den Verkauf nicht abschliesst und schon eine neue Quittung scannt,
	 * kommt er hierher (Fokus ist auf einem andern Formular.
	 * Wir leiten ihn weiter zur Kontrollblick-Methode, allerdings mit einer Warnmeldung.
	 */
	public function verklickt()
	{
		$this->addData('quittungNr', $this->input->post('id'));
		$this->load->view('kasse/verklickt', $this->data);
		return;
	} // End of function verklickt()
	
	
} // End of class Kasse
