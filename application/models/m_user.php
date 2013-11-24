<?php
/**
 * Model M_user
 * The Users of the application
 * This model is used for user administration.
 * @see library Simplelogin for CRUD actions and authentification.
 *
 * TODO auf boerse anpassen
 * @author
 */
class M_user extends CI_Model {
	/**
	 * Class variables
	 *
	 */
	public $id;
	public $email;
	public $role;
	

	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 * Get an array of registered users
	 * 
	 * @return	array of objects with db field names (except pw) as keys
	 */
	public function all()
	{
		$arrOut = array();

		$this->db->select(array('user_id', 'user_email', 'user_role', 'user_date', 'user_modified', 'user_last_login'));
		$this->db->order_by('user_email', 'asc');
		$query = $this->db->get('users');
		if ($query->num_rows() == 0) {
			return $arrOut;
		}

		foreach ($query->result() as $row) {
			$thisUser = new M_user();
			$thisUser->id		= $row->user_id;
			$thisUser->email	= $row->user_email;
			$thisUser->role		= $row->user_role;
			$arrOut[] = $thisUser;
		}
		return $arrOut;
	}
	
	
	/**
	 * Get the data from database and populate the class attributes
	 * 
	 * @param	int		$id
	 * @return	boolean	True if a user with this id could be found
	 */
	public function fetch($id)
	{
		$this->db->where('user_id', $id);
		$query = $this->db->get('users', 1);
		if (1 !== $query->num_rows()) {
			return false;
		}
		$row = $query->row();
		$this->id = $row->user_id;
		$this->email = $row->user_email;
		$this->role = $row->user_role;
		
		return true;
	}
	
	
	/**
	 * Fetches data from db.
	 * @uses	$this->fetch()
	 * @param 	String	$email
	 * @return boolean	True if data was found in db
	 */
	public function fetch4email($email)
	{
		$q = $this->db->query('SELECT user_id FROM users WHERE user_email = ?', array($email));
		if (0 == $q->num_rows()) {
			return false;
		} else {
			return $this->fetch($q->row()->id);
		}
	}
	
	
	/**
	 * Return all types a user can have. Used for dropdowns
	 * @return array	key: DB enum case; value: lang text
	 */
	public static function roles()
	{
		return array(
				'provelo'		=> 'Pro Velo',
				'superadmin'	=> 'Superadmin',
				'haendler'		=> 'Händler');
	}


	/**
	 * Save class attributes to database
	 * To create a user the SimpleLoginSecure library is used.
	 *
	 * @see SimpleLoginSecure
	 * @return	boolean							True on success
	 */
	public function save()
	{
		// New user
		if (0 == $this->id) {
			// Check if another usre has this email address
			$this->db->where('user_email', $this->email);
			$query = $this->db->get('users', 1);
			if ($query->num_rows() == 1) {
				log_message('error', 'Benutzer mit dieser Mail Adresse existiert schon.');
				return false;
			}
			
			if (!$this->simpleloginsecure->create($this->email, $this->password, false)) {
				log_message('error', 'Benutzer erstellen fehlgeschlagen.');
				return false;
			}
			$this->db->where('user_email', $this->email);
			$query = $this->db->get('users', 1);
			$this->id = $query->row()->id;
		}


		// Both new and existing
		$this->db->where('user_id', $this->id);
		$this->db->set('user_email', $this->email);
		$this->db->set('user_role', $this->role);

		return $this->db->update('users');
	}
	
	
}
