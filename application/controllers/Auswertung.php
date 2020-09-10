<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auswertung extends MY_Controller {


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
	public function afrika()
	{
		$this->data['veloQuery'] = Statistik::afrika();
		$this->load->view('auswertung/afrika', $this->data);
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
		// Die Übersicht steht neuerdings unter dem Navipunkt "Administration".
		redirect('admin/index');
	}


	/**
	 * Wie viel Geld haben wir vor Ort?
	 */
	public function cashMgmt()
	{
		$velos = Velo::getAll();
		$cash = 0;
		$benoetigtesCash = 0;
		$worstCaseCash = 0;
		foreach ($velos->result_array() as $thisVelo) {
			if ($thisVelo['zahlungsart'] == 'bar') {
				$cash += $thisVelo['preis'];
			}
			if (!$thisVelo['haendler_id']
				&& 'yes' == $thisVelo['verkauft'])
			{
				$benoetigtesCash += ($thisVelo['preis'] - Velo::getProvision($thisVelo['preis']));
			}
			if (!$thisVelo['haendler_id']
				&& 'no' == $thisVelo['ausbezahlt']
				&& !in_array($thisVelo['zahlungsart'], array('debit','kredit')))
			{
				$worstCaseCash += ($thisVelo['preis'] - Velo::getProvision($thisVelo['preis']));
			}
// 			if (condition) {
// 				// Hochrechnung gemäss aktuellem Modalsplit cash/karte;
// 			}
		}
		$this->addData('cash', $cash);
		$this->addData('benoetigtesCash', $benoetigtesCash);
		$this->addData('worstCaseCash', $worstCaseCash);
		$this->addData('newStatistics', Statistik::cashMgmt());

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

		$this->addData('modalSplit', Statistik::modalsplit());


		// Summe Einstellgebühr Händler
		$totalEinstellgebuehr = 0;
		foreach ($this->data['haendlerStatistik'] as $myHaendler) {
		    $totalEinstellgebuehr += $myHaendler['einstellgebuehr'];
		}
		$this->addData('totalEinstellgebuehrHaendler', $totalEinstellgebuehr);

		switch ($format) {
			case 'csv':
			    $this->output->enable_profiler(FALSE);
				$this->load->view('auswertung/statistik_csv', $this->data);
				break;
			default:
				$this->load->view('auswertung/statistik', $this->data);
		}

		return ;
	}


}
