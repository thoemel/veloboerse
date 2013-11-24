<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auswertung extends MY_Controller {

	
	public function __construct()
	{
		parent::__construct();

		// All methods require user to be logged in.
		$this->requireLoggedIn();
	}
	
	
	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		show_error('kein index in der auswertung');
	}
	
	
	/**
	 * Wie viel Geld haben wir vor Ort?
	 */
	public function cashMgmt()
	{
		$velos = Velo::getAll();
		$cash = 0;
		foreach ($velos->result_array() as $thisVelo) {
			if ($thisVelo['zahlungsart'] == 'bar') {
				$cash += $thisVelo['preis'];
			}
		}
		$this->addData('cash', $cash);
		
		$this->load->view('auswertung/cashMgmt', $this->data);
		return;
	}
	
	
}
