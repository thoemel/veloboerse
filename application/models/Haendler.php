<?php
class Haendler extends CI_Model {
	public $adresse = '	';
	public $bankverbindung = '';
	public $busse = 0;
	public $code = '';
	public $email = '';
	public $firma = '';
	public $iban = '';
	public $id = 0;
	public $kommentar = '';
	public $person = '';
	public $provisionFactor = 0.15;
	public $standgebuehr = 0;
	public $uptodate = 0;
	public $telefon = '';
	public $anzahlVelos = 0;

	/**
	 * Entspricht einem Eintrag des Enum-Felds der Tabelle haendler.status
	 * Mögliche Werte:
	 * 		offen		- Noch keine Status-Änderung bisher
	 * 		angenommen	- Alle Velos gingen durch die Annahme
	 * 		abgeholt	- Alle nicht-verkauften Velos haben das Gelände wieder verlassen
	 * 		ausbezahlt	- Die Auszahlung ist abgeschlossen und unterschrieben.
	 *
	 * @var String
	 */
	private $status = 'offen';


	/**
	 * Konstruktor.
	 */
	public function __construct()
	{
		// Do nothing
		parent::__construct();
	}


	/**
	 * Setzt den Status aller Händler auf "angenommen".
	 * @return bool
	 */
	public static function alleAngenommen()
	{
		$CI =& get_instance();
		$sql = 'UPDATE haendler SET status = "angenommen"';

		return $CI->db->query($sql);
	}


	/**
	 * Setzt alle Händler zurück für neue Börse
	 */
	public static function alleZuruecksetzen()
	{
		$CI =& get_instance();
		$sql = 'UPDATE haendler SET
				status = "angenommen",
				code = UUID(),
				kommentar = "",
				status = "offen",
				standgebuehr = 0,
				busse = 0,
				uptodate = 0,
				anzahlVelos = 0';

		return $CI->db->query($sql);
	}


	/**
	 * Prüft, wie viele Velos des Händlers noch in der Halle sind.
	 * Diese Funktion wird z.B. bei der Händlerabholung verwendet.
	 * @return	int	Anzahl Velos in Halle
	 */
	public function anzahlNochDrinnen()
	{
		$ret = 0;

		/*
		 * Als draussen gelten folgende Velos:
		 * - verkauft
		 * - abgeholt
		 * - gestohlen
		 * - storniert
		 */
		foreach (Velo::getAll($this->id)->result() as $thisVelo) {
			if ('no' == $thisVelo->verkauft
				&& 'no' == $thisVelo->abgeholt
				&& 0 == $thisVelo->gestohlen
				&& 0 == $thisVelo->storniert)
			{
				$ret++;
			}
		}

		return $ret;
	} // End of function anzahlNochDrinnen


	/**
	 * Händler löschen
	 *
	 * @return true, falls alles erfolgreich.
	 */
	public function delete()
	{
		$ret = true;

		// Zuerst alle Velos des Händlers löschen
		$this->db->query('DELETE FROM velos WHERE haendler_id = ?', $this->id);

		// Jetzt den DB-Eintrag aus der Händler-Tabelle löschen
		$this->db->query('DELETE FROM haendler WHERE id = ?', $this->id);

		return $ret;
	}


	/**
	 * Codes für Händler neu setzen mit UUID().
	 * @param	int		$haendler_id	Nur für diesen Händler, falls nicht 0.
	 * @return	boolean	True, wenn Query erfolgreich.
	 */
	public static function direktLinksDeaktivieren($haendler_id)
	{
		$CI =& get_instance();
		$sql = 'UPDATE haendler SET code = UUID()';

		if ($haendler_id > 0) {
			if (!self::istRegistriert($haendler_id)) {
				return false;
			}
			$sql .= ' WHERE id = ?';
		}
		return $CI->db->query($sql, array($haendler_id));
	}


	/**
	 * Sucht in der DB nach dem Händler mit der entsprechenden ID.
	 *
	 * @throws	Exception, falls kein Händler gefunden.
	 * @param	int			$id
	 * @return	stdClass	Klasse mit allen DB-Feldern als Attributen
	 */
	public function find($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('haendler', 1);
		if ($query->num_rows() !== 1) {
			throw new Exception('Kein Händler mit dieser ID gefunden');
			exit;
		}

		// Public Felder
		$this->adresse = $query->row()->adresse;
		$this->bankverbindung = $query->row()->bankverbindung;
		$this->busse = $query->row()->busse;
		$this->code = $query->row()->code;
		$this->email = $query->row()->email;
		$this->telefon = $query->row()->telefon;
		$this->firma = $query->row()->firma;
		$this->iban = $query->row()->iban;
		$this->id = $query->row()->id;
		$this->kommentar = $query->row()->kommentar;
		$this->person = $query->row()->person;
		$this->provisionFactor = $query->row()->provision / 100;
		$this->standgebuehr = $query->row()->standgebuehr;
		$this->uptodate = $query->row()->uptodate;
		$this->anzahlVelos = $query->row()->anzahlVelos;
		$this->setStatus($query->row()->status);


		return $query->row();
	}


	/**
	 * Liefere alle Händler aus der DB
	 * @return CI_DB_result Objekt
	 */
	public static function getAll()
	{
		$CI =& get_instance();

		$CI->db->order_by('id', 'asc');
		$query = $CI->db->get('haendler');

		return $query;
	} // End of function getAll()



	/**
	 * Gibt den Status des Händlers zurück. Normaler getter des Attributs status.
	 *
	 * @return	String
	 */
	public function getStatus()
	{
		return $this->status;
	}


	/**
	 * Sucht in der db nach einem Händler mit dem entsprechenden Code
	 * und gibt dessen ID zurück, falls gefunden.
	 *
	 * @param	String	$code	UUID für DB-Feld 'code'
	 * @return	int				ID des Händlers. 0 falls nicht gefunden
	 */
	public function idFuerCode($code)
	{
		$this->db->where('code', $code);
		$query = $this->db->get('haendler', 1);
		if (0 == $query->num_rows()) {
			return 0;
		}
		return $query->row()->id;
	}


	/**
	 * Prüft, ob ein Händler im System registriert ist.
	 * @param int	$id
	 * @return	boolean
	 */
	public static function istRegistriert($id)
	{
		$CI =& get_instance();

		$CI->db->where('id', $id);
		$query = $CI->db->get('haendler', 1);
		return ( 1 == $query->num_rows() );
	}


	/**
	 * Schreibt die Werte der aktuellen Instanz in die DB
	 * @return boolean
	 */
	public function save()
	{
		// Public Felder
		$this->db->set('firma', $this->firma);
		$this->db->set('adresse', $this->adresse);
		$this->db->set('code', $this->code);
		$this->db->set('person', $this->person);
		$this->db->set('email', $this->email);
		$this->db->set('telefon', $this->telefon);
		$this->db->set('bankverbindung', $this->bankverbindung);
		$this->db->set('iban', $this->iban);
		$this->db->set('kommentar', $this->kommentar);
		$this->db->set('provision', ($this->provisionFactor * 100));
		$this->db->set('standgebuehr', $this->standgebuehr);
		$this->db->set('busse', $this->busse);
		$this->db->set('uptodate', $this->uptodate);
		$this->db->set('anzahlVelos', $this->anzahlVelos);

		// Private Felder
		$this->db->set('status', $this->getStatus());

		if (!self::istRegistriert($this->id)) {
			$this->db->set('code', uniqid()); // Keine UUID, das kommt weiter unten
			$success = $this->db->insert('haendler');
			$this->id = $this->db->insert_id();
			self::direktLinksDeaktivieren($this->id); // Das macht eine richtige UUID
		} else {
			$this->db->where('id', $this->id);
			$success = $this->db->update('haendler');
		}

		return $success;
	} // End of function save()


	/**
	 * Set status Attribut auf einen der Enum-Werte des DB- Feldes haendler.status
	 * @param	String	$strIn
	 * @return	void
	 */
	public function setStatus($strIn)
	{
		if (!in_array($strIn, array('offen','angenommen','abgeholt','ausbezahlt'))) {
			$this->status = 'offen';
		}
		$this->status = $strIn;
		return;
	}


	/**
	 * Gibt ein Array mit allen möglichen Status zurück.
	 * Keys und Values sind identisch. Das ist bequem für Formular-Views.
	 * Die Keys entsprechen den Enum-Werten aus der DB.
	 *
	 * @return array
	 */
	public static function statusArray() {
		return array('offen'=>'offen','angenommen'=>'angenommen','abgeholt'=>'abgeholt','ausbezahlt'=>'ausbezahlt');
	}


	/**
	 * Liefert den Verkaufpreis aller verkauften Velos dieses Händlers
	 * @return	int
	 */
	public function sumAlleVerkauften()
	{
		$this->db->select_sum('preis');
		$this->db->where('haendler_id', $this->id);
		$this->db->where('verkauft', 'yes');
		$query = $this->db->get('velos');
		return $query->row()->preis;
	}


	/**
	 * Gibt ein Array mit Informationen zu den Velos dieses Händlers
	 *
	 * @return array	Keys: total, verkauft, abgeholt, gestohlen, storniert, problemfall
	 */
	public function velosInfo()
	{
		$arrOut = array(
			'abgeholt'		=> 0,
			'gestohlen'		=> 0,
			'problemfall'	=> 0,
			'storniert'		=> 0,
			'total' 		 =>0,
			'verkauft'		=> 0,
		);
		$this->db->where('haendler_id', $this->id);
		$query = $this->db->get('velos');
		foreach ($query->result() as $row) {
			$arrOut['total'] += 1;
			if ('yes' == $row->abgeholt) {
				$arrOut['abgeholt'] += 1;
			}
			if ('yes' == $row->gestohlen) {
				$arrOut['gestohlen'] += 1;
			}
			if ('yes' == $row->problemfall) {
				$arrOut['problemfall'] += 1;
			}
			if ('yes' == $row->storniert) {
				$arrOut['storniert'] += 1;
			}
			if ('yes' == $row->verkauft) {
				$arrOut['verkauft'] += 1;
			}
		}
		return $arrOut;
	}


}
