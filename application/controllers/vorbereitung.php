<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vorbereitung extends MY_Controller {

	
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
		$this->load->view('vorbereitung/neue_boerse', $this->data);
	}
	
	
}
