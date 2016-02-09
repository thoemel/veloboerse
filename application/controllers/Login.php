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
		$this->load->view('login/formular', $this->data);
	}
	
	
	public function logMeIn()
	{
		if($this->simpleloginsecure->login($this->input->post('username'), $this->input->post('password'))) {
			// success
			redirect('login/showChoices');
		} else {
			// failure
			$this->session->set_flashdata('error', 'Login fehlgeschlagen');
			redirect('login/form');
		}
		return;
	}
	
	
	public function logout()
	{
		$this->simpleloginsecure->logout();
		redirect();
	}
	
	
	/**
	 * @deprecated
	 * @see admin.php
	 */
	public function save()
	{
		$this->form_validation->set_rules('username', 'E-Mail', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Passwort', 'trim|required|min_length[8]');
		
		if (false === $this->form_validation->run()) {
			$this->form();
			return;
		}
		
		$this->simpleloginsecure->create($this->input->post('username'), $this->input->post('password'));
		redirect('auswahl');
	}
	
	
	public function showChoices()
	{
		// Require user to be logged in.
		$this->requireLoggedIn();
		
		$this->load->view('login/auswahl', $this->data);
		return;
	}
}
