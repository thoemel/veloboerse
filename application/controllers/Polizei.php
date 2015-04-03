<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Polizei extends MY_Controller {

	
	public function __construct()
	{
		parent::__construct();

		// All methods require user to be logged in.
		$this->requireLoggedIn();
	}
	
	
	/**
	 * Zusammenzug der verkauften aber nicht ausbezahlten Velos.
	 * In der Annahme, dass die alle für Afrika gespendet werden.
	 */
	public function gestohlene()
	{
		$this->data['gestohlene'] = Velo::gestohlene();
		$this->load->view('polizei/gestohlene', $this->data);
	}
	
	
	/**
	 * Liste mit Links für die Polizei.
	 */
	public function index()
	{
		$this->load->view('polizei/uebersicht', $this->data);
	}
	
}
