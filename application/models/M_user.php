<?php
/**
 * Model M_user
 * The Users of the application
 * This model is used for user administration.
 * @see library Simplelogin for CRUD actions and authentification.
 *
 * @author
 */
class M_user extends MY_Model {
	/**
	 * Class variables
	 *
	 */
	public $id = 0;
	public $email = '';
	public $username = NULL;
	public $auth_level = 0;
	public $banned = '0';
	public $passwd_recovery_code = '';
	public $passwd_recovery_date = NULL;
	private $password = '';
	public $passwd_modified_at = NULL;
	public $last_login = NULL;
	public $created_at = NULL;
	public $modified_at = NULL;

	public $vorname = '';
	public $nachname = '';
	public $adresse = '';
	public $iban = '';


	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 * Array aller Benutzer
	 * Vorläufig noch ohne die Werte aus der Tabelle "private".
	 *
	 * @return	array of objects with db field names (except pw) as keys
	 */
	public function all()
	{
		$arrOut = array();

		$this->db->select(array('user_id', 'username', 'email', 'auth_level', 'banned', 'passwd_recovery_code', 'passwd_recovery_date', 'passwd_modified_at', 'last_login', 'created_at', 'modified_at'));
		$this->db->order_by('email', 'asc');
		$query = $this->db->get(config_item('user_table'));
		if ($query->num_rows() == 0) {
			return $arrOut;
		}

		foreach ($query->result() as $row) {
			$thisUser = new M_user();
			$thisUser->id		= $row->user_id;
			$thisUser->email	= $row->email;
			$thisUser->username = $row->username;
			$thisUser->auth_level = $row->auth_level;
			$thisUser->banned = $row->banned;
			$thisUser->created_at = $row->created_at;
			$thisUser->last_login = $row->last_login;
			$thisUser->modified_at = $row->modified_at;
			$thisUser->passwd_modified_at = $row->passwd_modified_at;
			$thisUser->passwd_recovery_code = $row->passwd_recovery_code;
			$thisUser->passwd_recovery_date = $row->passwd_recovery_date;

			$arrOut[] = $thisUser;
		}
		return $arrOut;
	}


	/**
	 * Change a user's password
	 *
	 * @param  string  the new password
	 * @param  string  the new password confirmed
	 * @param  string  the user ID
	 * @param  string  the password recovery code
	 */
	protected function _change_password( $password, $password2, $user_id, $recovery_code )
	{
	    // User ID check
	    if( isset( $user_id ) && $user_id !== FALSE )
	    {
	        $query = $this->db->select( 'user_id' )
	        ->from( $this->db_table('user_table') )
	        ->where( 'user_id', $user_id )
	        ->where( 'passwd_recovery_code', $recovery_code )
	        ->get();

	        // If above query indicates a match, change the password
	        if( $query->num_rows() == 1 )
	        {
	            $user_data = $query->row();

	            $this->db->where( 'user_id', $user_data->user_id )
	            ->update(
	                $this->db_table('user_table'),
	                [
	                    'passwd' => $this->authentication->hash_passwd( $password ),
	                    'passwd_recovery_code' => NULL,
	                    'passwd_recovery_date' => NULL
	                ]
	                );
	        }
	    }
	}


	/**
	 * Delete a user from all used tables
	 * @return bool    True if successfully deleted
	 */
	public function delete()
	{
	    $ret = true;
	    // Delete from joined table "private"
	    $this->db->where('user_id', $this->id);
	    $this->db->delete('private');

	    // Maybe in the future we have to deal with acl records - not yet.

	    // Delete from users table
	    $this->db->where('user_id', $this->id);
	    $ret = $this->db->delete('users');
	    if (!$ret) {
	        log_message('error', 'Could not delete user #' . $this->id . ' from users table.');
	    }

	    return $ret;
	}



	/**
	 * Get the data from database and populate the class attributes
	 *
	 * @param	int		$id
	 * @return	boolean	True if a user with this id could be found
	 */
	public function fetch($id)
	{
		$this->db->where('users.user_id', $id);
		$this->db->join('private', 'users.user_id = private.user_id', 'left');
		$query = $this->db->get('users', 1);
		if (1 !== $query->num_rows()) {
			return false;
		}
		$row = $query->row();
		$this->id		= $row->user_id;
		$this->email	= $row->email;
		$this->username = $row->username;
		$this->auth_level = $row->auth_level;
		$this->banned = $row->banned;
		$this->created_at = $row->created_at;
		$this->last_login = $row->last_login;
		$this->modified_at = $row->modified_at;
		$this->passwd_modified_at = $row->passwd_modified_at;
		$this->passwd_recovery_code = $row->passwd_recovery_code;
		$this->passwd_recovery_date = $row->passwd_recovery_date;
		$this->vorname = $row->vorname;
		$this->nachname = $row->nachname;
		$this->adresse = $row->adresse;
		$this->iban = $row->iban;

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
	    $q = $this->db->query('SELECT user_id FROM users WHERE email = ?', array($email));
	    if (0 == $q->num_rows()) {
	        return false;
	    } else {
	        return $this->fetch($q->row()->id);
	    }
	}


	/**
	 * Fetches data from db.
	 * @uses	$this->fetch()
	 * @param 	String	$email
	 * @return boolean	True if data was found in db
	 */
	public function fetch4username($username)
	{
	    $q = $this->db->query('SELECT user_id FROM users WHERE username = ?', array($username));
	    if (0 == $q->num_rows()) {
	        return false;
	    } else {
	        return $this->fetch($q->row()->id);
	    }
	}


	/**
	 * Get data for a recovery
	 *
	 * @param   string  the email address
	 * @return  mixed   either query data or FALSE
	 */
	public function get_recovery_data( $email )
	{
	    $query = $this->db->select( 'u.user_id, u.email, u.banned' )
	    ->from( $this->db_table('user_table') . ' u' )
	    ->where( 'LOWER( u.email ) =', strtolower( $email ) )
	    ->limit(1)
	    ->get();

	    if( $query->num_rows() == 1 )
	        return $query->row();

	        return FALSE;
	}


	/**
	 * Get the user name, user salt, and hashed recovery code,
	 * but only if the recovery code hasn't expired.
	 *
	 * @param  int  the user ID
	 */
	public function get_recovery_verification_data( $user_id )
	{
	    $recovery_code_expiration = date('Y-m-d H:i:s', time() - config_item('recovery_code_expiration') );

	    $query = $this->db->select( 'username, passwd_recovery_code' )
	    ->from( $this->db_table('user_table') )
	    ->where( 'user_id', $user_id )
	    ->where( 'passwd_recovery_date >', $recovery_code_expiration )
	    ->limit(1)
	    ->get();

	    if ( $query->num_rows() == 1 )
	        return $query->row();

	        return FALSE;
	}



	/**
	 * Get an unused ID for user creation
	 *
	 * @return  int between 1200 and 4294967295
	 */
	private function get_unused_id()
	{
	    // Create a random user id between 1200 and 4294967295
	    $random_unique_int = 2147483648 + mt_rand( -2147482448, 2147483647 );

	    // Make sure the random user_id isn't already in use
	    $query = $this->db->where( 'user_id', $random_unique_int )
	    ->get_where( $this->db_table('user_table') );

	    if( $query->num_rows() > 0 )
	    {
	        $query->free_result();

	        // If the random user_id is already in use, try again
	        return $this->get_unused_id();
	    }

	    return $random_unique_int;
	}


	/**
	 * Validation and processing for password change during account recovery
	 */
	public function recovery_password_change()
	{
	    $this->load->library('form_validation');

	    // Load form validation rules
	    $this->load->model('validation_callables');
	    $this->form_validation->set_rules([
	        [
	            'field' => 'passwd',
	            'label' => 'Neues Passwort',
	            'rules' => [
	                'trim',
	                'required',
	                'matches[passwd_confirm]',
	                [
	                    '_check_password_strength',
	                    [$this->validation_callables, 'check_password_strength']
	                ]
	            ]
	        ],
	        [
	            'field' => 'passwd_confirm',
	            'label' => 'Passwort Bestätigung',
	            'rules' => 'trim|required'
	        ],
	        [
	            'field' => 'recovery_code'
	        ],
	        [
	            'field' => 'user_id'
	        ]
	    ]);

	    if( $this->form_validation->run() !== FALSE )
	    {
	        $this->load->vars( ['validation_passed' => 1] );

	        $this->_change_password(
	            $this->input->post('passwd'),
	            $this->input->post('passwd_confirm'),
	            set_value('user_id'),
	            set_value('recovery_code')
	            );
	    }
	    else
	    {
	        $this->load->vars( ['validation_errors' => validation_errors()] );
	    }
	}


	/**
	 * Return all types a user can have. Used for dropdowns
	 * @return array	key: auth_level; value: lang text
	 */
	public static function roles()
	{
	    return config_item('levels_and_roles');
	}


	/**
	 * Save class attributes to database
	 * To create a user and for changing the password
	 * the SimpleLoginSecure library is used.
	 *
	 * @see SimpleLoginSecure
	 * @return	boolean							True on success
	 */
	public function save()
	{
	    $this->db->set('auth_level', $this->auth_level);
	    $this->db->set('banned', $this->banned);
	    $this->db->set('created_at', $this->created_at);
	    $this->db->set('email', $this->email);
	    $this->db->set('last_login', $this->last_login);
	    $this->db->set('modified_at', $this->modified_at);
	    $this->db->set('passwd_modified_at', $this->passwd_modified_at);
	    $this->db->set('passwd_recovery_code', $this->passwd_recovery_code);
	    $this->db->set('passwd_recovery_date', $this->passwd_recovery_date);
	    if (!empty($this->password)) {
	        $this->db->set('passwd', $this->password);
	    }
	    if (!empty($this->username)) {
	        $this->db->set('username', $this->username);
	    }

		// New user
		if (0 == $this->id) {
			// Check if another user has this email address
			$this->db->where('email', $this->email);
			if (!empty($this->username)) {
			    $this->db->or_where('username', $this->username);
			}
			$query = $this->db->get('users', 1);
			if ($query->num_rows() == 1) {
				log_message('error', 'Benutzer mit dieser Mail Adresse existiert schon.');
				return false;
			}
			// Check if another user has this username
		    if (!empty($this->username)) {
		        $this->db->where('username', $this->username);

		        $query = $this->db->get('users', 1);
		        if ($query->num_rows() == 1) {
		            log_message('error', 'Benutzer mit diesem Benutzernamen existiert schon.');
		            return false;
		        }
		    }

		    $this->id = $this->get_unused_id();
		    $this->db->set('user_id', $this->id);

		    $this->db->insert(db_table('user_table'));

		} // End if new user
		else {
		    $this->db->where('user_id', $this->id);
		    $this->db->update(db_table('user_table'));
		}

		// Both new and existing
		if( $this->db->affected_rows() !== 1 ) {
		    log_message('error', 'Benutzer speichern fehlgeschlagen.');
		    return false;
		}

		// Personal informations to table "private"
		$this->db->where('user_id', $this->id);
		$query = $this->db->get('private');

		$this->db->set('vorname', $this->vorname);
		$this->db->set('nachname', $this->nachname);
		$this->db->set('adresse', $this->adresse);
		$this->db->set('iban', $this->iban);
		if (1 !== $query->num_rows()) {
		    $this->db->set('user_id', $this->id);
		    $ret = $this->db->insert('private');
		} else {
		    $this->db->where('user_id', $this->id);
		    $ret = $this->db->update('private');
		}
		if (!$ret) {
		    log_message('error', 'Benutzer speichern in Tabelle "private" fehlgeschlagen.');
		}



        return TRUE;
	} // End of function save()


	/**
	 * Set hashed password
	 * @param String $pwd  The unhashed password
	 * @return void
	 */
	public function set_password($pwd) {
	    $this->password = $this->authentication->hash_passwd( $pwd );
	}


	/**
	 * Update a user record with data not from POST
	 *
	 * @param  int     the user ID to update
	 * @param  array   the data to update in the user table
	 * @return bool
	 */
	public function update_user_raw_data( $the_user, $user_data = [] )
	{
	    return $this->db->where('user_id', $the_user)->update( $this->db_table('user_table'), $user_data );
	}

} // End of class M_user