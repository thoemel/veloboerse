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


	public function rahmennummern() {
	    $this->output->enable_profiler(FALSE);

	    $velos = Velo::polizei_rahmennummern();

	    $msg = "id,rahmennummer,preis,typ,farbe,marke,verkaeufer_id\n";
	    foreach ($velos as $row) {
	        $msg .= '"'.$row->id.'";"'
	            .str_replace('"', '``', $row->rahmennummer).'";"'
                .str_replace('"', '``', $row->preis).'";"'
                .str_replace('"', '``', $row->typ).'";"'
                    .str_replace('"', '``', $row->farbe).'";"'
                .str_replace('"', '``', $row->marke).'";"'
                .str_replace('"', '``', $row->verkaeufer_id).'"'."\n";
	    }

	    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	    header("Content-Disposition: attachment; filename=veloboerse_rahmennummern_" . date('Ymd_His') . ".csv");
	    echo $msg;
	    return;
	}
}
