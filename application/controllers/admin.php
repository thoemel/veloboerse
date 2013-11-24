<?php
/**
 * Administrative tasks for IDB Kantone project
 *
 * @author web@meteotest.ch
 */
class Admin extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('M_user');
	}

	/**
	 * Deletes an existing user
	 * @param int $id
	 */
	public function deleteUser($id)
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('user_role'), array('superadmin'))) {
			$this->show_403();
			return ;
		}
		
		if ($this->simpleloginsecure->delete($id)) {
			$this->session->set_flashdata('success', "Benutzer gelöscht");
		} else {
			$this->session->set_flashdata('error', "Benutzer konte nicht gelöscht werden.");
		}

		redirect('admin');
	}


	/**
	 * Edits credentials of an existing user
	 */
	public function editUser()
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('user_role'), array('superadmin'))) {
			$this->show_403();
			return ;
		}
		
		if ($this->form_validation->run('editUser') === false) {
			// Not registered because of wrong input
			$formValues = array();
			$formValues['email'] = set_value('email');
			$formValues['role'] = set_value('role');
			$this->addData('formValues', $formValues);
			$this->userForm($this->input->post('id'));
			return;
		}
		
		$myUser = new M_user();
		$myUser->fetch($this->input->post('id'));
		$myUser->email = $this->input->post('email');
		$myUser->role = $this->input->post('role');
		
		if($myUser->save()) {
			$this->session->set_flashdata('success', 'Benutzer speichern erfolgreich.');
		} else {
			// Not registered because of technical reason
			$this->session->set_flashdata('error', 'Benutzer speichern fehlgeschlagen.');
		}
			
		redirect('admin');

	} // End of function editUser()


	public function index()
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('user_role'), array('superadmin'))) {
			$this->show_403();
			return ;
		}
		
		// Get List of users
		$this->addData('registeredUsers', $this->M_user->all());


		$this->load->view('admin/index', $this->data);
		
		return;
	}


	/**
	 * Creates a user in the database
	 *
	 */
	public function registerUser()
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('user_role'), array('superadmin'))) {
			$this->show_403();
			return ;
		}
		
		if ($this->form_validation->run('createUser') === false) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('admin');
		}

		$myUser = new M_user();
		$myUser->email		= $this->input->post('email');
		$myUser->password	= $this->input->post('pw');
		$myUser->role		= $this->input->post('role');
		if ($myUser->save()) {
			$this->session->set_flashdata('success', 'Benutzer erstellen erfolgreich.');
		} else {
			$this->session->set_flashdata('error', 'Benutzer erstellen fehlgeschlagen.');
		}

		redirect('admin');
	}
	
	
	/**
	 * With this method a superadmin user can see the application as if he were 
	 * another user.
	 * 
	 * @param	int	$user_id
	 * @return	void
	 */
	public function switchToUser($user_id)
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('user_role'), array('superadmin'))) {
			$this->show_403();
			return ;
		}
		
		$newUser = new M_user();
		if (!$newUser->fetch($user_id)) {
			throw new Exception('No user found with this id');
			return;
		}
		$this->session->set_userdata('user_id', $newUser->id);
		$this->session->set_userdata('user_role', $newUser->type);
		$this->session->set_userdata('user_email', $newUser->email);
		$this->session->set_userdata(array('logged_in' => true));
		
		$this->session->set_flashdata('success', 'Eingeloggt als ' . $newUser->email);
		redirect('');
		return;
	}


	/**
	 * Shows the edit form for a user.
	 * Used for create and edit
	 * 
	 * @param	String	$userId	
	 */
	public function userForm($userId = '')
	{
		// User must be superadmin
		if (!in_array($this->session->userdata('user_role'), array('superadmin'))) {
			$this->show_403();
			return ;
		}
		
		$userId = intval($userId);
		
		// Get Types
		$roles = $this->M_user->roles();
		$this->addData('roles', $roles);
		
		// If form_validation failed formValues are already populated
		if (!isset($this->data['formValues'])) {
			$formValues = array();
			$user = new M_user();
			if (0 < $userId) {
				$user->fetch($userId);
			}
			$formValues['id'] = $user->id;
			$formValues['email'] = $user->email;
			$formValues['pw'] = '';
			$formValues['role'] = $user->role;
			$this->addData('formValues', $formValues);
		} 
		
		$formAction = $userId ? 'admin/editUser' : 'admin/registerUser';
		$this->addData('formAction', $formAction);
			
		$this->load->view('admin/user_form', $this->data);
		
		return;
	}
}
