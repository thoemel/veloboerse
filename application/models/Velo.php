<?php
class Velo extends CI_Model {

	public $abgeholt = 'no';
	public $afrika = FALSE;
	public $ausbezahlt = 'no';
	public $bemerkungen = '';
	public $farbe = '';
	public $gestohlen = FALSE;
	public $haendler_id	= 0;
	public $helfer_kauft = 'no';
	public $id = 0;
	public $img = '';
	public $kein_ausweis = 'no';
	public $keine_provision = 'no';
	public $marke = '';
	public $preis = 0;
	public $problemfall = FALSE;
	public $rahmennummer = '';
	public $storniert = FALSE;
	public $typ = '';
	public $verkauft = 'no';
	public $vignettennummer = '';
	public $zahlungsart = NULL;
	
	/**
	 * Konstruktor.
	 */
	public function __construct()
	{
		// Do nothing
		parent::__construct();
	}
	
	
	/**
	 * Sucht in der DB nach dem Velo mit der entsprechenden ID.
	 * 
	 * @throws	Exception, falls kein Velo gefunden.
	 * @param int $id
	 * @return	stdClass	Klasse mit allen DB-Feldern als Attributen
	 */
	public function find($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('velos', 1);
		if ($query->num_rows() !== 1) {
			throw new Exception('Kein Velo mit dieser ID gefunden');
			exit;
		}

		$this->abgeholt = $query->row()->abgeholt;
		$this->afrika = $query->row()->afrika;
		$this->ausbezahlt = $query->row()->ausbezahlt;
		$this->bemerkungen = $query->row()->bemerkungen;
		$this->farbe = $query->row()->farbe;
		$this->gestohlen = $query->row()->gestohlen;
		$this->haendler_id = $query->row()->haendler_id;
		$this->helfer_kauft = $query->row()->helfer_kauft;
		$this->id = $id;
		$this->img = $query->row()->img;
		$this->kein_ausweis = $query->row()->kein_ausweis;
		$this->keine_provision = $query->row()->keine_provision;
		$this->marke = $query->row()->marke;
		$this->preis = $query->row()->preis;
		$this->problemfall = $query->row()->problemfall;
		$this->rahmennummer = $query->row()->rahmennummer;
		$this->storniert = $query->row()->storniert;
		$this->typ = $query->row()->typ;
		$this->verkauft = $query->row()->verkauft;
		$this->vignettennummer = $query->row()->vignettennummer;
		$this->zahlungsart = $query->row()->zahlungsart;
				
		return $query->row();
	}
	
	
	/**
	 * Liefere alle Velos aus der DB
	 * @param	int	$haendler_id	[optional]
	 * @return Query Objekt
	 */
	public static function getAll($haendler_id = NULL)
	{
		$CI =& get_instance();
		
		if ($haendler_id) {
			$CI->db->where('haendler_id', $haendler_id);
		}

		$CI->db->order_by('id', 'asc');
		$query = $CI->db->get('velos');
		
		return $query;
	} // End of function find()
	
	
	/**
	 * Liefere alle Velos aus der DB
	 * @param	int	$haendler_id	[optional]
	 * @return Query Objekt
	 */
	public static function gestohlene()
	{
		$CI =& get_instance();
		
		$CI->db->where('gestohlen', 1);
		$CI->db->order_by('id', 'asc');
		$query = $CI->db->get('velos');
		
		return $query;
	} // End of function find()
	
	
	
	/**
	 * Gibt die Provision für ein Velo. Benutzt die Provisions Tabelle aus der DB.
	 * 
	 * @throws Exception	Wenn keine Provision gefunden wurde.
	 * @param	$preis		Der Verkaufspreis des Velos
	 * @return	int			Die Provision
	 */
	public static function getProvision($preis)
	{
		$CI =& get_instance();
		
		$CI->db->where('preis >=', $preis);
		$CI->db->order_by('preis', 'asc');
		$query = $CI->db->get('provision', 1);
		if (1 == $query->num_rows()) {
			$provision = $query->row()->provision;
		} else {
			// ueber 3000 immer 10%
			$provision = $preis / 10;
		}
		return $provision;
	}
	
	
	/**
	 * Prüft, ob eine Quttung im System registriert ist.
	 * @param int	$quittungNr
	 * @return	boolean
	 */
	public static function istRegistriert($quittungNr)
	{
		$CI =& get_instance();
		
		$CI->db->where('id', $quittungNr);
		$query = $CI->db->get('velos', 1);
		return ( 1 == $query->num_rows() );
	}
	
	
	/**
	 * Liefert die ganze Provisionsliste als Array.
	 * @return array($obergrenze => $provision)	Sortiert nach aufsteigender Obergrenze
	 */
	public static function provisionsliste()
	{
		$arrOut = array();
		$CI =& get_instance();
		
		$CI->db->order_by('preis', 'asc');
		$query = $CI->db->get('provision');
		foreach ($query->result() as $row) {
			$arrOut[$row->preis] = (int)($row->provision);
		}
		
		return $arrOut;
	}
	
	
	/**
	 * Schreibt die Werte der aktuellen Instanz in die DB
	 * @return boolean
	 */
	public function save()
	{
		$this->db->set('abgeholt', $this->abgeholt);
		$this->db->set('afrika', $this->afrika);
		$this->db->set('ausbezahlt', $this->ausbezahlt);
		$this->db->set('bemerkungen', $this->bemerkungen);
		$this->db->set('farbe', $this->farbe);
		$this->db->set('gestohlen', $this->gestohlen);
		$this->db->set('haendler_id', $this->haendler_id);
		$this->db->set('helfer_kauft', $this->helfer_kauft);
		$this->db->set('img', $this->img);
		$this->db->set('kein_ausweis', $this->kein_ausweis);
		$this->db->set('keine_provision', $this->keine_provision);
		$this->db->set('marke', $this->marke);
		$this->db->set('preis', $this->preis);
		$this->db->set('problemfall', $this->problemfall);
		$this->db->set('rahmennummer', $this->rahmennummer);
		$this->db->set('storniert', $this->storniert);
		$this->db->set('typ', $this->typ);
		$this->db->set('verkauft', $this->verkauft);
		$this->db->set('vignettennummer', $this->vignettennummer);
		$this->db->set('zahlungsart', $this->zahlungsart);
		
		if (!self::istRegistriert($this->id)) {
			$this->db->set('id', $this->id);
			$success = $this->db->insert('velos');
		} else {
			$this->db->where('id', $this->id);
			$success = $this->db->update('velos');
		}
		
		// Check haendler Status
		if ($this->haendler_id > 0) {
			$haendler = new Haendler();
			$haendler->find($this->haendler_id);
			if (0 == $haendler->anzahlNochDrinnen() && $haendler->getStatus() == 'angenommen') {
				// Das war das letzte Velo dieses Händlers, das die Halle verliess (durch Kasse oder Abholung)
				$haendler->setStatus('abgeholt');
				$success = $success && $haendler->save();
			}
			if (0 < $haendler->anzahlNochDrinnen() && $haendler->getStatus() == 'abgeholt') {
				// Ggf. wenn ein Velo nachträglich übers Allerwelts-Formular bearbeitet wurde
				$haendler->setStatus('angenommen');
				$success = $success && $haendler->save();
			}
		}
		
		return $success;
	} // End of function save()
	
	
}