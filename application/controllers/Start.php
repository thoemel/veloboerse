<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Start extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->load->model('statistik');
		$this->load->model('boerse');
		$boerse = Boerse::naechsteOffene();
		$this->addData('naechsteBoerse', $boerse);
		$this->addData('anzahl', Statistik::anzahlVelosAufPlatz());

		$this->load->view('start', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */