<?php
class Boerse extends CI_Model {
	public $datum = NULL;
	public $status = 'offen';
	public $id = 0;
		
	
	/**
	 * Konstruktor.
	 */
	public function __construct()
	{
		// Do nothing
		parent::__construct();
	}
	
	
	/**
	 * Boerse löschen
	 * 
	 * @return true, falls alles erfolgreich.
	 */
	public function delete()
	{
		return $this->db->query('DELETE FROM boersen WHERE id = ?', $this->id);;
	}
	
	
	/**
	 * Bereinigt die Datenbank, damit eine neue Börse beginnen kann.
	 * 
	 * @return boolean
	 */
	public static function eroeffne()
	{
		$CI =& get_instance();
		
		$ret = $CI->db->query('TRUNCATE velos');
		$ret = $ret && $CI->db->query('TRUNCATE statistik');
		return $ret;
	}
	
	
	/**
	 * Sucht in der DB nach der Börse mit der entsprechenden ID.
	 * 
	 * @throws	Exception, falls kein Eintrag gefunden.
	 * @param	int			$id
	 * @return	stdClass	Klasse mit allen DB-Feldern als Attributen
	 */
	public function find($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('boersen', 1);
		if ($query->num_rows() !== 1) {
			throw new Exception('Keine Börse mit dieser ID gefunden');
			exit;
		}

		// Public Felder
		$this->id = $id;
		$this->datum = $query->row()->datum;
		$this->status = $query->row()->status;
		
		return $query->row();
	}
	
	
	/**
	 * Gibt eine Börsen-Instanz zurück, falls eine Börse in der Zukunft liegt 
	 * und offen ist. Falls nicht NULL.
	 * 
	 * @return NULL|Boerse
	 */
	public static function naechsteOffene()
	{
		$CI =& get_instance();
		
		$CI->db->select('id');
		$CI->db->where('datum >= ', date('Y-m-d'));
		$CI->db->where('status', 'offen');
		$CI->db->order_by('datum', 'asc');
		$query = $CI->db->get('boersen', 1);
		
		if (0 == $query->num_rows()) {
			return NULL;
		}
		
		$boerse = new Boerse();
		$boerse->find($query->row()->id);
		return $boerse;
	}
	
	
	/**
	 * Gibt eine Börsen-Instanz zurück, falls eine Börse in der Vergangenheit liegt 
	 * und offen ist. Falls nicht NULL.
	 * 
	 * @return NULL|Boerse
	 */
	public static function letzteOffene()
	{
		$CI =& get_instance();
		
		$CI->db->select('id');
		$CI->db->where('status', 'offen');
		$CI->db->where('datum < ', date('Y-m-d'));
		$CI->db->order_by('datum', 'desc');
		$query = $CI->db->get('boersen', 1);
		
		if (0 == $query->num_rows()) {
			return NULL;
		}
		
		$boerse = new Boerse();
		$boerse->find($query->row()->id);
		return $boerse;
	}
	
	
	/**
	 * Schreibt die Werte der aktuellen Instanz in die DB
	 * @return boolean
	 */
	public function save()
	{
		// Public Felder
		$this->db->set('datum', $this->datum);
		$this->db->set('status', $this->status);
		
		// Insert oder Update
		if (1 > $this->id) {
			$success = $this->db->insert('boersen');
			$this->id = $this->db->insert_id();
		} else {
			$this->db->where('id', $this->id);
			$success = $this->db->update('boersen');
		}
		
		return $success;
	} // End of function save()
	
	
}
