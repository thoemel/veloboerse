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


	/**
	 * Exportiert eine Liste mit angenommenen Velos für die Kontrolle durch die Polizei.
	 * Normalerweise nur die, die nicht schon einmal epxortiert wurden. So kann die Polizei mehrmals
	 * vorbei kommen und nach neu angenommenen fragen. Falls als Parameter "alle" mitgegeben wird,
	 * gibt es einen Export aller angenommenen Velos.
	 * Das Script bricht den Request ab, indem es eine CSV-Datei an den Browser zurück schickt.
	 *
	 * @param string $alle Falls 'alle', wird alles aus der Tabelle 'rahmennummern' exportiert.
	 * @return void
	 */
	public function rahmennummern($alle = '') {
	    $this->output->enable_profiler(FALSE);
	    $msg = "id,rahmennummer,preis,typ,farbe,marke,verkaeufer_id\n";

        if ('alle' !== $alle) {
            $velos = Velo::polizei_rahmennummern();
        } else {
            $velos = Velo::polizei_alle_rahmennummern();
        }


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
