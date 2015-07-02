<?php
class MY_Controller extends CI_Controller {

	/**
	 * Array zum weiterleiten von Variablen an den View
	 *
	 * @var array
	 */
	protected $data = array();


	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set('Europe/Zurich');
		
		//Transactions für die Entwicklung ausschalten.
		$this->db->trans_off();

		// Profiling infos für die Entwicklung einschalten
		if ($_SERVER['SERVER_NAME'] == 'dev.provelobern.ch') {
// 	           $this->output->enable_profiler(TRUE);
		}


		// Statistik
		$this->load->model('Statistik');
		Statistik::registriere();
		
		// Ressort Navi
		$this->ressortNavi();
		
		$this->searchFormHandling();
		
		// Standardmässig hat der Body Tag keine Klasse. Kann auch z. B. ' class="alert alert-error"' sein.
		if ($this->session->userdata('user_ressort')) {
			$this->addData('bodyClass', ' class="'.$this->session->userdata('user_ressort').'"');
		} else {
			$this->addData('bodyClass', '');
		}
		if ('login/showChoices' == uri_string()) {
			// Session Überschreiben für Ressortwahl
			$this->addData('bodyClass', '');
		}

	} // End of function __construct

	
	/**
	 * Fügt dem $data array ein neues Element hinzu. Diese Methode dient nur der
	 * bequemeren Eingabe in den Kontrollern.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	protected function addData($key, $value)
	{
		$this->data[$key] = $value;
	}

	
	/**
	 * Fügt einem Element des $data array weiteren Text hinzu. 
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	protected function appendData($key, $value)
	{
		$oldData = '';
		if (!empty($this->data[$key])) {
			$oldData = $this->data[$key] . '<br>';
		}
		$this->data[$key] = $oldData . $value;
	}
	
	
	/**
	 * Check if user is logged in. Set flashdata and redirect if not
	 */
	protected function requireLoggedIn()
	{
		if (!$this->session->userdata('logged_in')) {
			$this->session->set_flashdata('error', 'Benutzer ist nicht eingeloggt.');
			Redirect();
		}
		
	}
	
	
	/**
	 * Gibt ein Array mit den Ressorts, für die der User Berechtigt ist.
	 * Schreibt dieses Array auch in $this->data, damit es in den Controllern
	 * und Views verfügbar ist.
	 * 
	 * @return array($href => $name)
	 */
	public function ressortNavi() {
		// Default navi for not logged-in user
		$arrOut = array('login/form'	=> 'Login');
		
		// Normal users
		if ($this->session->userdata('logged_in')) {
			$arrOut = array(
				'login/dispatch/privatannahme'	=> 'Annahme Private',
				'login/dispatch/privatauszahlung'	=> 'Auszahlung Private',
				'login/dispatch/kasse'				=> 'Kasse',
				'login/dispatch/abholung'			=> 'Abholung Private',
				'login/dispatch/haendlerabholung'	=> 'Abholung Händler',
				'login/dispatch/haendleradmin'		=> 'Händleradmin',
				'login/dispatch/veloformular'		=> 'Formular Velo',
				'login/dispatch/polizei'			=> 'Polizei',
			);
		}
		
		// Superadmins
		if ('superadmin' == $this->session->userdata('user_role')) {
			$arrOut['login/dispatch/auswertung'] = 'Auswertung';
		}
		
		$this->data['ressortNavi'] = $arrOut;
		
		return $arrOut;
	}
	
	
	private function searchFormHandling()
	{
		/*
		 * Action und Button-Text für das Formular oben rechts entsprechend 
		 * des Ressorts
		 */
		$this->addData('formSubmitText', 'suchen');
		switch ($this->session->userdata('user_ressort')) {
			case 'privatannahme':
				$this->addData('formAction', 'annahme/formular_private');
				$this->addData('formSubmitText', 'erfassen');
				break;
			case 'privatauszahlung':
				$this->addData('formAction', 'auszahlung/kontrollblick');
				break;
			case 'kasse':
				$this->addData('formAction', 'kasse/kontrollblick');
				break;
			case 'abholung':
				$this->addData('formAction', 'abholung/kontrollblick');
				break;
			case 'haendlerabholung':
				$this->addData('formAction', 'abholung/abholen');
				break;
			case 'veloformular':
				$this->addData('formAction', 'velos/formular');
				break;
			default:
				$this->addData('formAction', 'velos/suche');
				
		}
		
		/*
		 * Auf gewissen Seiten das Suchformular ausblenden, weil es zu Problemen
		 * führen kann. Wenn es da ist und den Fokus hat, kann man nicht mit 
		 * Enter das Formular abschicken, sondern setzt einfach eine leere
		 * Suche ab. Um das zu verhindern, zeigen wir das Formular gar nicht an.
		 */
		switch (uri_string()) {
			case '/auszahlung/kontrollblick':
			case '/kasse/kontrollblick':
			case '/abholung/kontrollblick':
				$this->addData('showSearchForm', false);
				break;
			default:
				$this->addData('showSearchForm', true);
		}
	}
	

	/**
	 * Displays a 403 Forbidden page
	 */
	protected function show_403()
	{
		$this->output->set_status_header(403, 'Forbidden');
		$this->load->view('v_403', $this->data);
		return;
	}


}
