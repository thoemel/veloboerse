<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auswertung extends MY_Controller {

	
	public function __construct()
	{
		parent::__construct();

		// All methods require user to be logged in.
		$this->requireLoggedIn();
	}
	
	
	/**
	 * Velos-Tabelle Export als CSV
	 * @param String	$tabelle	Name der DB-Tabelle
	 */
	public function csv($tabelle = 'velos')
	{
		// Input validation
		switch ($tabelle) {
			case 'haendler':
				break;
			case 'velos':
			default:
				$tabelle = 'velos';
		}
		$this->data['tabelle'] = $tabelle;
		
		$this->load->dbutil();
		$query = $this->db->query("SELECT * FROM " . $tabelle);
		$this->data['content'] = $this->dbutil->csv_from_result($query, ';');
		
		$this->load->view('auswertung/csv', $this->data);
		return ;
	}
	
	
	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->load->view('auswertung/uebersicht', $this->data);
	}
	
	
	/**
	 * Wie viel Geld haben wir vor Ort?
	 */
	public function cashMgmt()
	{
		$velos = Velo::getAll();
		$cash = 0;
		$benoetigtesCash = 0;
		foreach ($velos->result_array() as $thisVelo) {
			if ($thisVelo['zahlungsart'] == 'bar') {
				$cash += $thisVelo['preis'];
			}
			if (!$thisVelo['haendler_id'] 
				&& 'yes' == $thisVelo['verkauft'])
			{
				$benoetigtesCash += ($thisVelo['preis'] - Velo::getProvision($thisVelo['preis']));
			}
		}
		$this->addData('cash', $cash);
		$this->addData('benoetigtesCash', $benoetigtesCash);
		
		$this->load->view('auswertung/cashMgmt', $this->data);
		return;
	}
	
	
	/**
	 * Zeigt eine statistische Auswertung.
	 * @param string $format	'csv', falls ein Export im CSV-Format gewünscht.
	 */
	public function statistik($format = 'html')
	{
		$this->load->model('statistik');
		
		$this->addData('verkaufteVelos', Statistik::verkaufteVelos());

		$this->addData('veloStatistik', Statistik::velos());
		
		$this->addData('haendlerStatistik', Statistik::haendler());
		
		switch ($format) {
			case 'csv':
				$this->load->view('auswertung/statistik_csv', $this->data);
				break;
			default:
				$this->load->view('auswertung/statistik', $this->data);
		}
		
		return ;
	}
	
	
}
