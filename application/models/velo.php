<?php
class Velo extends CI_Model {

	public $abgeholt = 'no';
	public $ausbezahlt = 'no';
	public $farbe = '';
	public $haendler_id	= NULL;
	public $helfer_kauft = 'no';
	public $id = 0;
	public $img = '';
	public $kein_ausweis = 'no';
	public $keine_provision = 'no';
	public $marke = '';
	public $preis = 0;
	public $rahmennummer = '';
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
		$this->ausbezahlt = $query->row()->ausbezahlt;
		$this->id = $id;
		$this->img = $query->row()->img;
		$this->preis = $query->row()->preis;
		$this->verkauft = $query->row()->verkauft;
		$this->zahlungsart = $query->row()->zahlungsart;
		$this->kein_ausweis = $query->row()->kein_ausweis;
		$this->keine_provision = $query->row()->keine_provision;
		$this->helfer_kauft = $query->row()->helfer_kauft;
		$this->haendler_id = $query->row()->haendler_id;
		$this->farbe = $query->row()->farbe;
		$this->marke = $query->row()->marke;
		$this->rahmennummer = $query->row()->rahmennummer;
		$this->typ = $query->row()->typ;
		$this->vignettennummer = $query->row()->vignettennummer;
				
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
	 * Gibt die Provision für ein Velo.
	 * 
	 * @uses	$this->preis	Der Verkaufspreis des Velos
	 * @throws Exception		Wenn keine Provision gefunden wurde.
	 * @return	int				Die Provision
	 */
	public function getProvision()
	{
		$this->db->where('preis >=', $this->preis);
		$this->db->order_by('preis', 'asc');
		$query = $this->db->get('provision', 1);
		if (1 == $query->num_rows()) {
			$provision = $query->row()->provision;
		} else {
			$provision = $this->preis / 10;
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
	 * Schreibt die Werte der aktuellen Instanz in die DB
	 * @return boolean
	 */
	public function save()
	{
		$this->db->set('abgeholt', $this->abgeholt);
		$this->db->set('ausbezahlt', $this->ausbezahlt);
		$this->db->set('farbe', $this->farbe);
		$this->db->set('haendler_id', $this->haendler_id);
		$this->db->set('helfer_kauft', $this->helfer_kauft);
		$this->db->set('img', $this->img);
		$this->db->set('kein_ausweis', $this->kein_ausweis);
		$this->db->set('keine_provision', $this->keine_provision);
		$this->db->set('marke', $this->marke);
		$this->db->set('preis', $this->preis);
		$this->db->set('rahmennummer', $this->rahmennummer);
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
		
		return $success;
	} // End of function save()
	
	
}