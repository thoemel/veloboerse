<?php
/**
 * Erledigt das registrieren von Benutzerzugriffen 
 * und die Statistik über die vergangene Börse.
 */
class Statistik extends CI_Model {
	public function __construct($id = 0) {
		parent::__construct ();
	}


	/**
	 * Statistische Angaben für Velos für Afrika
	 * Liefert alle Velos, die verkauft aber nicht ausbezahlt worden sind.
	 * 
	 * @return CI query Objekt
	 */
	public static function afrika()
	{
		$CI = & get_instance ();
		$arrOut = array();
		
		$sql = 'SELECT *
				FROM velos 
				WHERE verkauft = "yes" 
				AND ausbezahlt = "no" 
				AND (haendler_id = 0) 
				ORDER BY id asc';
		$query = $CI->db->query($sql);
		
		$sql = 'SELECT *
				FROM velos
				WHERE afrika = 1
				ORDER BY id asc';
		$q2 = $CI->db->query($sql);
		return array('verkauft_nicht_ausbezahlt' => $query->result(),
					'afrika_registriert' => $q2->result());
	} // End of function afrika()
	
	
	public static function anzahlVelosAufPlatz()
	{
		$CI = & get_instance ();
		$sql = 'SELECT count(id) as anzahl
				FROM velos
				WHERE verkauft = "no"
				AND abgeholt = "no"
				AND gestohlen = 0
				AND storniert = 0';
		$query = $CI->db->query($sql);
		return $query->row()->anzahl;
	}
	
	
	/**
	 * Statistische Zahlen für die Abschätzung, wie viel Bargeld wir auf Platz brauchen.
	 * 
	 * @return array	Keys: maxAuszahlung, probAuszahlung, einnahmenBisher, einnahmenPrognoseAbJetzt, statAnteilVerkauftePrivat, statAnteilVerkaufteHaendler, statAnteilVerkaufteTotal, countPrivateAufPlatz
	 */
	public static function cashMgmt()
	{
		$CI = & get_instance ();
		
		// Annahmen: statistischer Anteil verkauft/angeboten Privat (2014h)
		$statAnteilVerkauftePrivat = 0.56;
		$statAnteilVerkaufteHaendler = 0.49;
		$statAnteilVerkaufteTotal = 0.53;
		
		// Maximaler auszuzahlender Betrag Privatvelos
		$maxAuszahlung = 0;
		$sql = 'SELECT	preis
				FROM	velos
				WHERE	haendler_id = 0
				AND		ausbezahlt = "no"
				AND		abgeholt = "no"
				AND		verkauft = "no"';
		$query = $CI->db->query($sql);
		$countPrivateAufPlatz = $query->num_rows();
		foreach ($query->result() as $row) {
			$maxAuszahlung += $row->preis;
			$maxAuszahlung -= Velo::getProvision($row->preis);
		}
		
		// Heute eingenommenes Bargeld
		$sql = 'SELECT	SUM(preis) as einnahmen
				FROM	velos
				WHERE	verkauft = "yes"
				AND		zahlungsart = "bar"';
		$query = $CI->db->query($sql);
		$einnahmenBisher = $query->row()->einnahmen;
		
		// Schon ausbezahlt
		$ausbezahlt = 0;
		$sql = 'SELECT	preis
				FROM	velos
				WHERE	haendler_id = 0
				AND		ausbezahlt = "yes"';
		$query = $CI->db->query($sql);
		foreach ($query->result() as $row) {
			$ausbezahlt += $row->preis;
			$ausbezahlt -= Velo::getProvision($row->preis);
		}
		
		/*
		 * Erwartete Bargeld-Einnahmen ab jetzt
		 */
		// Anteil Barzahlung
		$statAnteilBarHeute = 1;
		$sql = 'SELECT zahlungsart, count( id ) as anz
				FROM `velos`
				WHERE verkauft = "yes"
				AND zahlungsart != ""
				GROUP BY zahlungsart';
		$query = $CI->db->query($sql);
		$sumVerkaufte = 0;
		$sumBar = 0;
		foreach ($query->result() as $row) {
			$sumVerkaufte += $row->anz;
			if ($row->zahlungsart == 'bar') {
				$sumBar = $row->anz;
			}
		}
		if (0 != $sumVerkaufte) {
			$statAnteilBarHeute = $sumBar / $sumVerkaufte;
		}
		
		// Geschätzte Anzahl Verkäufe (-> Preis)
		$sql = 'SELECT sum(preis) as preisAufPlatz
				FROM `velos`
				WHERE verkauft = "no"
				AND abgeholt = "no"';
		$query = $CI->db->query($sql);
		$preisAufPlatz = $query->row()->preisAufPlatz;
		$prognoseVerkaufHeute = $preisAufPlatz * $statAnteilVerkaufteTotal;
		$prognoseEinnahmenHeute = $prognoseVerkaufHeute * $statAnteilBarHeute;
		
		$arrOut = array(
				'maxAuszahlung'					=> $maxAuszahlung, 
				'probAuszahlung'				=> ($maxAuszahlung * $statAnteilVerkauftePrivat),
				'einnahmenBisher'				=> $einnahmenBisher, 
				'ausbezahlt'					=> $ausbezahlt,
				'einnahmenPrognoseAbJetzt'		=> $prognoseEinnahmenHeute,
				'statAnteilVerkauftePrivat'		=> $statAnteilVerkauftePrivat,
				'statAnteilVerkaufteHaendler'	=> $statAnteilVerkaufteHaendler,
				'statAnteilVerkaufteTotal'		=> $statAnteilVerkaufteTotal,
				'statAnteilBarHeute'			=> $statAnteilBarHeute,
				'countPrivateAufPlatz'			=> $countPrivateAufPlatz,
		);
		return $arrOut;
	}
	
	
	/**
	 * Statistische Angaben pro Händler
	 */
	public static function haendler()
	{
		$CI = & get_instance ();
		$arrOut = array();
		
		foreach (Haendler::getAll()->result_array() as $haendlerRow) {
			$myHaendler = $haendlerRow;
			$CI->db->where('haendler_id', $haendlerRow['id']);
			$query = $CI->db->get('velos');
			if (0 == $query->num_rows()) {
				continue;
			}

			$myHaendler['velosAufPlatz'] = 0;
			$myHaendler['velosVerkauft'] = 0;
			$myHaendler['anteilVerkauft'] = 0;
			$myHaendler['velosZurück'] = 0;
			$myHaendler['summePreisVerkaufte'] = 0;
			$myHaendler['summeProvision'] = 0;
			$myHaendler['einstellgebuehr'] = 0;
			$myHaendler['betragAusbezahlt'] = 0;
			
			$myHaendler['betragAusbezahlt'] -= $myHaendler['busse'];
			
			foreach ($query->result() as $velo) {
				if (0== $velo->storniert) {
					$myHaendler['velosAufPlatz'] ++;
				}
				if ('yes' == $velo->verkauft) {
					$myHaendler['velosVerkauft'] ++;
					$myHaendler['summePreisVerkaufte'] += $velo->preis;
					$myHaendler['summeProvision'] += $velo->preis * $myHaendler['provision'] / 100;
					$myHaendler['betragAusbezahlt'] += $velo->preis * (100 - $myHaendler['provision']) / 100;
				}
				if ('no' == $velo->verkauft && 0 == $velo->storniert && 0 == $velo->gestohlen) {
					$myHaendler['einstellgebuehr'] += 10;
				}
			}
			$myHaendler['anteilVerkauft'] = $myHaendler['velosVerkauft'] / $myHaendler['velosAufPlatz'];
			$myHaendler['velosZurück'] = $myHaendler['velosAufPlatz'] - $myHaendler['velosVerkauft'];
			$myHaendler['betragAusbezahlt'] = $myHaendler['betragAusbezahlt'] - $myHaendler['einstellgebuehr'] - $myHaendler['standgebuehr'];
			
			$arrOut[] = $myHaendler;
		}
		
		return $arrOut;
	}
	
	
	/**
	 * Verkaufte Velos nach 
	 * 		Händler/Private
	 * 			Provisionsstufe
	 * 				Verkauft/nicht
	 * @return array(0|1=>array(obergrenze=>array(verkauft=>#,nicht_verkauft=>#)))
	 */
	public static function modalsplit()
	{
		$CI = & get_instance ();
		$arrOut = array(0=>array(),1=>array());

		$CI->db->order_by('preis', 'asc');
		$provisionsQuery = $CI->db->get('provision');
		$arrProvision = array();
		if ($provisionsQuery->num_rows() == 0) {
			return $arrOut;
		}
		foreach ($provisionsQuery->result() as $row) {
			$arrProvision[] = $row->preis;
			$arrOut[0][$row->preis] = array('verkauft'=>0,'nicht_verkauft'=>0);
			$arrOut[1][$row->preis] = array('verkauft'=>0,'nicht_verkauft'=>0);
		}
		$arrOut[0]['mehr'] = array('verkauft'=>0,'nicht_verkauft'=>0);
		$arrOut[1]['mehr'] = array('verkauft'=>0,'nicht_verkauft'=>0);

// 		$sql = 'SELECT (haendler_id > 0) as haendlervelo,
// 					(ceil(preis/100)*100) as obergrenze,
// 					verkauft, count(verkauft) as anzahl
// 				FROM velos
// 				GROUP BY (haendler_id > 0), ceil(preis/100)*100, verkauft
// 				ORDER BY haendlervelo asc, obergrenze asc, verkauft asc
// 				LIMIT 0, 100;';
		$sql = 'SELECT (haendler_id > 0) as haendlervelo, preis, verkauft
				FROM velos
				WHERE storniert = 0
				AND gestohlen = 0';
		$query = $CI->db->query($sql);
		if ($query->num_rows() == 0) {
			return $arrOut;
		}
		foreach ($query->result() as $row) {
			$verkauftOderNicht = 'yes' == $row->verkauft ? 'verkauft' : 'nicht_verkauft';
			if ($row->preis > end($arrProvision)) {
				$arrOut[$row->haendlervelo]['mehr'][$verkauftOderNicht]++;
				continue;
			}
			foreach ($arrProvision as $myP) {
				if ($row->preis <= $myP) {
					$arrOut[$row->haendlervelo][$myP][$verkauftOderNicht]++;
					break;
				}
			}
		}
		
		return $arrOut;
	}
	
	
	/**
	 * Erledigt das registrieren von Benutzerzugriffen.
	 * Ursprünglicher Zweck ist, dass bei der Fehlersuche nach Errormails die
	 * Geschichte des Besuchs rekonstruiert werden kann.
	 */
	public static function registriere() {
		$CI = & get_instance ();
		
		$CI->db->set ( 'zeitpunkt', date ( 'Y-m-d H:i:s' ) );
		$CI->db->set ( 'url', current_url () );
		if (! empty ( $_POST )) {
			$CI->db->set ( 'post_json', json_encode ( $_POST ) );
		}
		$user_id = NULL === $CI->session->userdata ( 'user_id' ) ? 0 : $CI->session->userdata ( 'user_id' );
		$CI->db->set ( 'user_id', $user_id );
		$user_agent = NULL === $CI->session->userdata ( 'user_agent' ) ? '' : $CI->session->userdata ( 'user_agent' );
		$CI->db->set ( 'user_agent', $user_agent );
		$CI->db->insert ( 'statistik' );
		
		return;
	}
	
	
	/**
	 * Statistische Angaben über die velos Tabelle
	 * @return array
	 */
	public static function velos()
	{
		$arrOut = array('haendler' => NULL, 'private' => NULL);
		$arrFields = array(
			'velosAufPlatz' => NULL,
			'schnittPreis' => NULL,
			'sumPreis' => NULL,
			'sumProvision' => NULL,
			'sumGestohlen' => NULL,
			'sumStorniert' => NULL,
			'zahlungsart' => array('bar' => NULL, 'kredit' => NULL, 'debit' => NULL),
			'sumVerkauft' => NULL,
			'sumKeineProvision' => NULL,
			'sumHelferKauft' => NULL,
			'anteilVerkauftGruppeVonVerkauftTotal' => NULL,
			'anteilVerkauftGruppeVonAnzahlGruppe' => NULL,
		);
		$arrOut['haendler'] = $arrFields;
		$arrOut['private'] = $arrFields;
		$arrOut['total'] = $arrFields;
		
		// Array mit Haendler-Infos array($id => $row)
		$arrHaendler = array();
		$haendlerQuery = Haendler::getAll();
		foreach ($haendlerQuery->result() as $row) {
			$arrHaendler[$row->id] = $row;
		}
		
		
		$veloQuery = Velo::getAll();
		foreach ($veloQuery->result() as $velo) {
			if ($velo->haendler_id > 0) {
				$key = 'haendler';
				$provision = $velo->preis * $arrHaendler[$velo->haendler_id]->provision / 100;
			} else {
				$key = 'private';
				$provision = Velo::getProvision($velo->preis);
			}
			
			/*
			 * Stornierte Velos nicht beachten
			 */
			if ($velo->storniert) {
				$arrOut[$key]['sumStorniert'] ++;
				continue;
			}
			
			/*
			 * Gestohlene Velos nicht beachten
			 */
			if ($velo->gestohlen) {
				$arrOut[$key]['sumGestohlen'] ++;
				continue;
			}
			
			/*
			 * Velos auf Platz
			 */
			$arrOut[$key]['velosAufPlatz'] ++;
			
			/*
			 * Anzal verkaufte Velos
			 */
			if ('yes' == $velo->verkauft) {
				$arrOut[$key]['sumVerkauft'] ++;
			}
			
			/*
			 * Eingenommene Provision: 
			 * Nur von Velos, die wirklich verkauft wurden
			 * und bei welchen kein Helfer vom Provisionserlass
			 * profitiert hat.
			 */
			if (	'no' == $velo->helfer_kauft
				&&	'no' == $velo->keine_provision
				&&	'yes' == $velo->verkauft) {
				$arrOut[$key]['sumProvision'] += $provision;
			}
			
			/*
			 * Provisions-Proviteure zählen
			 */
			if ('yes' == $velo->keine_provision) {
				$arrOut[$key]['sumKeineProvision'] += $provision;
			}
			if ('yes' == $velo->helfer_kauft) {
				$arrOut[$key]['sumHelferKauft'] += $provision;
			}
			
			/*
			 * Zahlungsart
			 */
			if ($velo->zahlungsart) {
				$arrOut[$key]['zahlungsart'][$velo->zahlungsart] ++;
			}
			
			/*
			 * Für Aggregatsberechnungen
			 */
			$arrOut[$key]['sumPreis'] += $velo->preis;
		} // End foreach velo


		/*
		 * Durchschnittlicher Preis und Anteil verkauft
		*/
		foreach (array('haendler', 'private') as $key) {
			if ($arrOut[$key]['velosAufPlatz']) {
				$arrOut[$key]['schnittPreis'] = $arrOut[$key]['sumPreis'] / $arrOut[$key]['velosAufPlatz'];
				$arrOut[$key]['anteilVerkauftGruppeVonAnzahlGruppe'] = $arrOut[$key]['sumVerkauft'] / $arrOut[$key]['velosAufPlatz'];
			}
			if (0 < ($arrOut['haendler']['velosAufPlatz'] + $arrOut['private']['velosAufPlatz'])) {
				$arrOut[$key]['anteilVerkauftGruppeVonVerkauftTotal'] = $arrOut[$key]['sumVerkauft'] / ($arrOut['haendler']['sumVerkauft'] + $arrOut['private']['sumVerkauft']);
			}
		}
		
		
		/*
		 * Total
		 */
		$arrOut['total']['velosAufPlatz'] = $arrOut['haendler']['velosAufPlatz'] + $arrOut['private']['velosAufPlatz'];
		$arrOut['total']['schnittPreis'] = ($arrOut['haendler']['schnittPreis'] + $arrOut['private']['schnittPreis']) / 2;
		$arrOut['total']['sumPreis'] = $arrOut['haendler']['sumPreis'] + $arrOut['private']['sumPreis'];
		$arrOut['total']['sumProvision'] = $arrOut['haendler']['sumProvision'] + $arrOut['private']['sumProvision'];
		$arrOut['total']['sumGestohlen'] = $arrOut['haendler']['sumGestohlen'] + $arrOut['haendler']['sumGestohlen'];
		$arrOut['total']['sumStorniert'] = $arrOut['haendler']['sumStorniert'] + $arrOut['haendler']['sumStorniert'];
		$arrOut['total']['zahlungsart'] = array ( 
				'bar' => $arrOut['haendler']['zahlungsart']['bar'] + $arrOut['private']['zahlungsart']['bar'],
				'kredit' => $arrOut['haendler']['zahlungsart']['kredit'] + $arrOut['private']['zahlungsart']['kredit'],
				'debit' => $arrOut['haendler']['zahlungsart']['debit'] + $arrOut['private']['zahlungsart']['debit'],); 
		$arrOut['total']['sumVerkauft'] = $arrOut['haendler']['sumVerkauft'] + $arrOut['private']['sumVerkauft'];
		$arrOut['total']['sumKeineProvision'] = $arrOut['haendler']['sumKeineProvision'] + $arrOut['private']['sumKeineProvision'];
		$arrOut['total']['sumHelferKauft'] = $arrOut['haendler']['sumHelferKauft'] + $arrOut['private']['sumHelferKauft'];
		$arrOut['total']['anteilVerkauftGruppeVonVerkauftTotal'] = $arrOut['haendler']['anteilVerkauftGruppeVonVerkauftTotal'] + $arrOut['private']['anteilVerkauftGruppeVonVerkauftTotal'];
		$arrOut['total']['anteilVerkauftGruppeVonAnzahlGruppe'] = ($arrOut['haendler']['anteilVerkauftGruppeVonAnzahlGruppe'] + $arrOut['private']['anteilVerkauftGruppeVonAnzahlGruppe']) / 2;
		
		return $arrOut;
	} // End of function velos
	
	
	/**
	 * Statistik über die verkauften Velos
	 * 
	 * @return array('haendler' => array('anzahl', 'preis', 'anzahlAnteil', 'preisAnteil'), 
	 * 				'private' => array('anzahl', 'preis', 'anzahlAnteil', 'preisAnteil'), 
	 * 				'total' => array('anzahl', 'preis'))
	 */
	public static  function verkaufteVelos() {
		$CI = & get_instance ();
		$arrOut = array (
				'haendler' => array (
						'anzahl' => NULL,
						'preis' => NULL,
						'anzahlAnteil' => NULL,
						'preisAnteil'	=> NULL 
				),
				'private' => array (
						'anzahl' => NULL,
						'preis' => NULL,
						'anzahlAnteil' => NULL,
						'preisAnteil'	=> NULL 
				),
				'total' => array (
						'anzahl' => NULL,
						'preis' => NULL,
				),
		);
		
		$sql = 'SELECT count(preis) as anzahl, sum(preis) as preis, 
    			(haendler_id > 0) as istHaendler 
				FROM `velos` 
				WHERE verkauft = ? 
				group by istHaendler';
		$query = $CI->db->query ( $sql, array('yes') );
		
		foreach ($query->result() as $row) {
			if (0 == $row->istHaendler) {
				$arrOut['private']['anzahl'] = $row->anzahl;
				$arrOut['private']['preis'] = $row->preis;
			} else {
				$arrOut['haendler']['anzahl'] = $row->anzahl;
				$arrOut['haendler']['preis'] = $row->preis;
			}
			$arrOut['total']['anzahl'] += $row->anzahl;
			$arrOut['total']['preis'] += $row->preis;
		}

		if (0 != $arrOut['haendler']['anzahl']) {
			$arrOut['haendler']['anzahlAnteil'] = $arrOut['haendler']['anzahl'] / $arrOut['total']['anzahl'];
		}
		if (0 != $arrOut['haendler']['preis']) {
			$arrOut['haendler']['preisAnteil'] = $arrOut['haendler']['preis'] / $arrOut['total']['preis'];
		}
		if (0 != $arrOut['private']['anzahl']) {
			$arrOut['private']['anzahlAnteil'] = $arrOut['private']['anzahl'] / $arrOut['total']['anzahl'];
		}
		if (0 != $arrOut['private']['preis']) {
			$arrOut['private']['preisAnteil'] = $arrOut['private']['preis'] / $arrOut['total']['preis'];
		}
		
		return $arrOut;
	}
}
