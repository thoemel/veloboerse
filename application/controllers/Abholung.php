<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Abholung extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		// All Methods require login
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
			$this->session->set_flashdata('error', 'Keine gültige Quittungsnummer (' . (int) $quittungNr . ')');
			redirect('abholung/index');
			return;
		}
		
		$myVelo = new Velo();
		$myVelo->find($quittungNr);
				
		if ('yes' == $myVelo->verkauft) {
			$this->addData('error', 'Das Velo wurde bereits verkauft. Es kann nicht sein, dass es jetzt abgeholt wird.');
		}
		$this->addData('velo', $myVelo);
		
		$this->load->view('abholung/kontrollblick', $this->data);
		return;
	}
	
	
	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->load->view('abholung/formular', $this->data);
	}
	
	
	/**
	 * Schliesst den Verkauf ab.
	 * @uses	POST vars
	 */
	public function abholen()
	{
		$myVelo = new Velo();
		$quittungNr = $this->input->post('id');
		try {
			$myVelo->find($quittungNr);
		} catch (Exception $e) {
			$this->session->set_flashdata('error', 'Das Velo ist nicht registriert. Abholung fehlgeschlagen.');
			redirect();
		}
		
		/*
		 * Mögliche Fehler abfangen
		 */
		if ('yes' == $myVelo->verkauft) {
			$this->addData('error', 'Das Velo wurde schon früher verkauft. Abholung nicht freigegeben.');
			$this->addData('bodyClass', ' class="alert alert-error"');
			return $this->index();
		}
		if ('haendlerabholung' == $this->session->userdata('user_ressort') && !$myVelo->haendler_id) {
			// Ein privates Velo wird durch die Händlerabholung geschleust
			$this->addData('error', 'Das ist kein Händlervelo. Abholung nicht freigegeben.');
			$this->addData('bodyClass', ' class="alert alert-error"');
			return $this->index();
		}
		if ('abholung' == $this->session->userdata('user_ressort') && 0 < $myVelo->haendler_id) {
			// Ein Händlervelo wird durch die Privatabholung geschleust
			$this->addData('error', 'Das ist kein Händlervelo. Abholung nicht freigegeben.');
			$this->addData('bodyClass', ' class="alert alert-error"');
			return $this->index();
		}

		/*
		 * Abholung registrieren
		 */
		$myVelo->abgeholt = 'yes';
		if ($myVelo->save()) {
			$this->data['success'] = 'Abholung wurde registriert :-)';
		} else {
			$this->data['error'] = 'Abholung nicht geklappt.';
		}
		
		/*
		 * Bei Händlern prüfen, ob alle Velos verkauft oder rausgebracht worden sind.
		 * Gegebenenfalls Status setzen.
		 */
		if ($myVelo->haendler_id) {
			$myHandler  = new Haendler();
			$myHandler->find($myVelo->haendler_id);
			$this->addData('haendler', $myHandler);
			$verbleibend = $myHandler->anzahlNochDrinnen();
			$this->addData('verbleibend', $verbleibend);
			if ('abgeholt' == $myHandler->getStatus()) {
				$this->addData('success', '<br><br>Alle Velos dieses Händlers sind entweder verkauft oder abgeholt.');
				$this->addData('bodyClass', ' class="alert alert-success"');
			}
		}
		
		$this->addData('velo', $myVelo);
		$abgeholt = ('yes' == $myVelo->abgeholt) ? 'Ja' : 'Nein';
		$this->addData('abgeholt', $abgeholt);
		
		$this->load->view('abholung/formular', $this->data);
	} // End of function abholen()
	
	
} // End of class Abholung
