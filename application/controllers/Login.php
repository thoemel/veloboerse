<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Set the session var 'user_ressort' to what the user wants to do
	 *
	 * @param	string	$role	Choosen work
	 * @return	void
	 */
	public function dispatch($role = 'viewer')
	{
		// Require user to be logged in.
		$this->requireLoggedIn();

		switch ($role) {
			case 'privatannahme':
				$this->session->set_userdata('user_ressort', 'privatannahme');
				redirect('annahme/einstieg_private');
				break;
			case 'privatauszahlung':
				$this->session->set_userdata('user_ressort', 'privatauszahlung');
				redirect('auszahlung/formular_private');
				break;
			case 'kasse':
				$this->session->set_userdata('user_ressort', 'kasse');
				redirect('kasse/index');
				break;
			case 'abholung':
				$this->session->set_userdata('user_ressort', 'abholung');
				redirect('abholung/index');
				break;
			case 'haendlerabholung':
				$this->session->set_userdata('user_ressort', 'haendlerabholung');
				redirect('abholung/index');
				break;
			case 'haendleradmin':
				$this->session->set_userdata('user_ressort', 'haendleradmin');
				redirect('haendleradmin/index');
				break;
			case 'veloformular':
				$this->session->set_userdata('user_ressort', 'veloformular');
				redirect('velos/einstieg');
				break;
			case 'polizei':
				$this->session->set_userdata('user_ressort', 'polizei');
				redirect('polizei/index');
				break;
			case 'auswertung':
				$this->session->set_userdata('user_ressort', 'auswertung');
				redirect('auswertung/index');
				break;
			case 'admin':
			    $this->session->set_userdata('user_ressort', 'admin');
			    redirect('admin/index');
			    break;
			case 'benutzeradmin':
			        $this->session->set_userdata('user_ressort', 'admin');
			        redirect('benutzeradmin/liste');
			        break;
			default:
				redirect();
		}
		return;
	}


	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->form();
	}


	/**
	 * Show the login form
	 */
	public function form()
	{
	    $this->setup_login_form();

		$this->load->view('login/formular', $this->data);
	}


	public function logMeIn()
	{
		// Method should not be directly accessible
		if( $this->uri->uri_string() == 'login/logMeIn') {
		    show_404();
		}

		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ) {
		    // Do the login
		    if (!$this->require_min_level(1)) {
		        $this->session->set_flashdata('error', 'Login fehlgeschlagen');
		        redirect('login/form');
		        return;
		    }
		}


		if ($this->verify_min_level(8)) {
		    // success
		    redirect('login/showChoices');
		} elseif ($this->verify_min_level(1)) {
		    // success, Private
		    redirect('verkaeufer/index');
		} else {
		    // failure
		    $this->form();
		}
		return;
	}


	public function logout()
	{
		$this->authentication->logout();

		$redirect_protocol = USE_SSL ? 'https' : NULL;

		redirect( site_url( LOGIN_PAGE . '?' . AUTH_LOGOUT_PARAM . '=1', $redirect_protocol ) );
	}

	/**
	 * User recovery form
	 */
	public function recover()
	{

	    // If IP or posted email is on hold, display message
	    if( $on_hold = $this->authentication->current_hold_status( TRUE ) ) {
	        $this->addData('accountDisabled', TRUE);
	    } else {
	        if( $this->tokens->match && $this->input->post('email') ) {
	            // If the form post looks good
	            if( $user_data = $this->M_user->get_recovery_data( $this->input->post('email') ) )  {
	                // Check if user is banned
	                if( $user_data->banned == '1' ) {
	                    // Log an error if banned
	                    $this->authentication->log_error( $this->input->post('email', TRUE ) );

	                    // Show special message for banned user
	                    $this->addData('accountBanned', TRUE);
	                } else {
	                    /**
	                     * Use the authentication libraries salt generator for a random string
	                     * that will be hashed and stored as the password recovery key.
	                     * Method is called 4 times for a 88 character string, and then
	                     * trimmed to 72 characters
	                     */
	                    $recovery_code = substr( $this->authentication->random_salt()
	                        . $this->authentication->random_salt()
	                        . $this->authentication->random_salt()
	                        . $this->authentication->random_salt(), 0, 72 );

	                    // Update user record with recovery code and time
	                    $this->M_user->update_user_raw_data(
	                        $user_data->user_id,
	                        [
	                            'passwd_recovery_code' => $this->authentication->hash_passwd($recovery_code),
	                            'passwd_recovery_date' => date('Y-m-d H:i:s')
	                        ]
	                        );

	                    // Set the link protocol
	                    $link_protocol = USE_SSL ? 'https' : 'http';

	                    // Set URI of link
	                    $link_uri = 'login/recovery_verification/' . $user_data->user_id . '/' . $recovery_code;
	                    $link = site_url( $link_uri, $link_protocol );
	                    $this->addData('recovery_link', anchor(
	                        site_url( $link_uri, $link_protocol ),
	                        site_url( $link_uri, $link_protocol ),
	                        'target ="_blank"'
	                        ));
	                }
	            } else {
	                // There was no match, log an error, and display a message
	                // Log the error
	                $this->authentication->log_error( $this->input->post('email', TRUE ) );

	                $this->addData('noMatch', TRUE);
	            }
	        }
	        $this->config->load('email', 'forgot_pw');
	        $mailConfig = config_item('forgot_pw');
	        $mailConfig['smtp_host'] = config_item('smtp_host');
	        $mailConfig['smtp_adress'] = config_item('smtp_adress');
	        $mailConfig['smtp_name'] = config_item('smtp_name');
	        $mailConfig['smtp_user'] = config_item('smtp_user');
	        $mailConfig['smtp_pass'] = config_item('smtp_pass');
	        $mailConfig['smtp_port'] = config_item('smtp_port');
	        $this->load->library('email');
	        $this->email->initialize($mailConfig);

	        $this->email->from(config_item('smtp_adress'), config_item('smtp_name'));
	        $this->email->to($this->input->post('email', TRUE ));

	        $this->email->subject('Passwort Velobörse');
	        $format = config_item('pw_vergessen_text');
	        $msg = sprintf($format, str_replace('p//', 'p://', $link));
	        $this->email->message($msg);

	        $success = $this->email->send(FALSE);
	    }

	    if (TRUE === $success) {
	        $this->addData('recovery_success_message', 'Aktivierungs-Link wurde geschickt');
	    } else {
	        $this->addData('recovery_success_message', 'Aktivierungs-Link konnte nicht versendet werden.');
	        log_message('error', $this->email->print_debugger());
	    }

	    $this->email->clear();

	    $this->load->view('login/recovery_sent', $this->data);
	    return;
	}


	public function recovery_request()
	{
	    $this->load->view('login/recovery_address_form', $this->data);
	    return;
	}

	/**
	 * Verification of a user by email for recovery
	 *
	 * @param  int     the user ID
	 * @param  string  the passwd recovery code
	 */
	public function recovery_verification( $user_id = '', $recovery_code = '' )
	{
	    /// If IP is on hold, display message
	    if( $on_hold = $this->authentication->current_hold_status( TRUE ) ) {
	        $this->addData('accountDisabled', TRUE);
	    } else {

	        if(
	            /**
	             * Make sure that $user_id is a number and less
	             * than or equal to 10 characters long
	             */
	            is_numeric( $user_id ) && strlen( $user_id ) <= 10 &&

	            /**
	             * Make sure that $recovery code is exactly 72 characters long
	             */
	            strlen( $recovery_code ) == 72 &&

	            /**
	             * Try to get a hashed password recovery
	             * code and user salt for the user.
	             */
	            $recovery_data = $this->M_user->get_recovery_verification_data( $user_id ) )
	        {
	            /**
	             * Check that the recovery code from the
	             * email matches the hashed recovery code.
	             */
	            if( $recovery_data->passwd_recovery_code == $this->authentication->check_passwd( $recovery_data->passwd_recovery_code, $recovery_code ) )
	            {
	                $this->addData('user_id', $user_id);
	                $this->addData('username', $recovery_data->username);
	                $this->addData('recovery_code', $recovery_data->passwd_recovery_code);
	                $this->addData('unhashed_recovery_code', $recovery_code);
	            } else {
	                // Link is bad so show message
	                $this->addData('recovery_error', TRUE);

	                // Log an error
	                $this->authentication->log_error('');
	            }
	        } else {
	            // Link is bad so show message
	            $this->addData('recovery_error', TRUE);

	            // Log an error
	            $this->authentication->log_error('');
	        }

	        /**
	         * If form submission is attempting to change password
	         */
	        if( $this->tokens->match) {
	            $this->M_user->recovery_password_change();
	            $this->session->set_flashdata('success', 'Passwort gespeichert.');
	            redirect('login/form');
	            return;
	        }
	    }

	    $this->load->view('login/recovery_form', $this->data);
	    return;
	} // End of function recovery_verification()


	/**
	 * Private sollen sich registrieren können, um ihr Velo anzubieten.
	 */
	public function registrationForm()
	{
	    $myUser = new M_user();
	    if (!empty($this->session->flashdata('myUser'))) {
	        $tmp = $this->session->flashdata('myUser');
	        $myUser->email = $tmp->email;
	        $myUser->username = $tmp->username;
	        $myUser->vorname = $tmp->vorname;
	        $myUser->nachname = $tmp->nachname;
	        $myUser->strasse = $tmp->strasse;
	        $myUser->plz = $tmp->plz;
	        $myUser->ort = $tmp->ort;
	        $myUser->telefon = $tmp->telefon;
	        $myUser->iban = $tmp->iban;

	    }
	    $this->addData('myUser', $myUser);
	    $this->addData('formAction', 'Benutzeradmin/registerUser');
	    $this->load->view('login/createUser', $this->data);
	}


	public function showChoices()
	{
		// Require user to be logged in.
		$this->requireLoggedIn();

		// Admins sehen auch Auswertungs-Link
		$this->addData('showAuswertung', $this->requireRole('admin'));

		$this->load->view('login/auswahl', $this->data);
		return;
	}
}
