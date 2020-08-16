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
		$this->addData('velos', Velo::getRandomly(10));

		$this->load->view('start', $this->data);
	}


	/**
	 * Gibt ein CSV mit allen Velos. Gedacht für den Notfall.
	 */
	public function veloDump()
	{
	    $this->output->enable_profiler(FALSE);
	    $this->load->dbutil();
	    $query = $this->db->query(
	        "SELECT id, preis, verkauft, abgeholt, ausbezahlt, bemerkungen,
                keine_provision as helfy_verkauft, helfer_kauft as helfy_kauft,
                haendler_id as haendly_id, verkaeufer_id as verkaeufy_id
            FROM velos
            ORDER BY id asc"
	        );
	    $this->output
    	    ->set_content_type('text/csv')
    	    ->set_output($this->dbutil->csv_from_result($query, ';', "\r\n", '"'));
// 	    echo $this->dbutil->csv_from_result($query, ';', "\r\n", '"');
	    return;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */