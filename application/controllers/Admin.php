<?php
/**
 * Administrative Aufgaben
 *
 * @author thoemel@thoemel.ch
 */
class Admin extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		// Logged-in user must be superadmin.
		$this->requireRole('superadmin');

		$this->load->model('M_user');
	}
	
	
	/**
	 * Schliesst eine Börse ab.
	 */
	public function boerseAbschliessen($id)
	{
		$boerse = new Boerse();
		try {
			$boerse->find($id);
		} catch (Exception $e) {
			$this->session->set_flashdata('error', $e->getMessage());
			redirect('admin/index');
			return;
		}
		$boerse->status = 'geschlossen';
		if (!$boerse->save()) {
			$this->session->set_flashdata('error', 'Börse konnte nicht abgeschlossen werden.');
			redirect('admin/index');
		} else {
			$this->load->dbutil();
			$this->load->helper('file');
			
			// Statistik-CSV generieren und ablegen
			$this->load->model('statistik');
			$data['verkaufteVelos'] = Statistik::verkaufteVelos();
			$data['veloStatistik'] = Statistik::velos();
			$data['haendlerStatistik'] = Statistik::haendler();
			$data['modalSplit'] = Statistik::modalsplit();
			$data['noHeaders'] = true;
			$statistik = $this->load->view('auswertung/statistik_csv', $data, true);
			$statsFileName = 'backups/bkup_boerse_statistik_' . date('Ymd', strtotime($boerse->datum)) . '.csv';
			write_file($statsFileName, utf8_decode($statistik));
			
			// CSV der DB-Tabelle "haendler" generieren und ablegen
			$query = $this->db->get("haendler");
			$haendlerCsv = $this->dbutil->csv_from_result($query, ';');
			$haendlerFileName = 'backups/bkup_boerse_haendler_' . date('Ymd', strtotime($boerse->datum)) . '.csv';
			write_file($haendlerFileName, utf8_decode($haendlerCsv));
			
			// CSV der DB-Tabelle "velos" generieren und ablegen
			$query = $this->db->get("velos");
			$velosCsv = $this->dbutil->csv_from_result($query, ';');
			$velosFileName = 'backups/bkup_boerse_velos_' . date('Ymd', strtotime($boerse->datum)) . '.csv';
			write_file($velosFileName, utf8_decode($velosCsv));
			
			// Datenbank backup als gzip ablegen
			$backup = $this->dbutil->backup();
			$dbFileName = 'backups/bkup_boerse_db_' . date('Ymd', strtotime($boerse->datum)) . '.sql.gz';
			write_file($dbFileName, $backup);
			
			$this->index();
		} 
		
		return;
	} // End of boerseAbschliessen()
	
	
	/**
	 * Erstelle ein ZIP mit allen Backup-Dateien zu dieser Börse und sende es
	 * an den Browser.
	 * @param int $id
	 * @return void
	 */
	public function boerseDownload($id)
	{
		$this->load->helper('download');
		$boerse = new Boerse();
		try {
			$boerse->find($id);
		} catch (Exception $e) {
			log_message('error', 'Admin::downloadBoerse(): ' . $e->getMessage());
			$this->session->set_flashdata('error', $e->getMessage());
			redirect('admin/index');
			return;
		}
		$datumsTeil = date('Ymd', strtotime($boerse->datum));

		// Create a ZIP and send it to the browser
		$zip = new ZipArchive();
		$filename = sys_get_temp_dir() . "/boerse_" . $datumsTeil . ".zip";
			
		if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
			log_message('error', 'Admin::downloadBoerse(): kann ' . $filename . ' nicht erstellen');
			redirect('admin/index');
		}

		$zip->addFile("backups/bkup_boerse_statistik_".$datumsTeil.".csv");
		$zip->addFile("backups/bkup_boerse_haendler_".$datumsTeil.".csv");
		$zip->addFile("backups/bkup_boerse_velos_".$datumsTeil.".csv");
		$zip->addFile("backups/bkup_boerse_db_".$datumsTeil.".sql.gz");
		$zip->close();
		force_download($filename, NULL, true);
		unlink($filename);
		
		return;
	}
	
	
	/**
	 * Speichert Börsendaten aus einem Formular
	 */
	public function boerseSpeichern()
	{
		// Form validation
		if ($this->form_validation->run('boerseSpeichern') === false) {
			$this->session->set_flashdata('error', 'Unmögliches Datum');
			redirect('admin/index');
			return;
		}
		
		$boerse = new Boerse();
		try {
			$boerse->find($this->input->post('id'));
		} catch(Exception $e) {
			// Neue Börse --> DB bereit machen
			Boerse::eroeffne();
		}
		
		$boerse->datum = $this->input->post('boerseDatum');
		if ($boerse->save()) {
			$this->session->set_flashdata('success', 'Börse gespeichert.');
		} else {
			$this->session->set_flashdata('error', 'Börse konnte nicht gespeichert werden.');
		}
		
		
		redirect('admin/index');
		return;
	}

	/**
	 * Deletes an existing user
	 * @param int $id
	 */
	public function deleteUser($id)
	{
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
		
		if (!empty($this->input->post('pw'))) {
			$this->simpleloginsecure->edit_password($this->input->post('email'), $this->input->post('pw'));
		}
		
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
		// Get List of users
		$this->addData('registeredUsers', $this->M_user->all());
		
		// Letzte Börse abschliessen oder neue eröffnen
		$letzteOffene = Boerse::letzteOffene();
		if (!is_null($letzteOffene)) {
			$this->data['letzteBoerse'] = $letzteOffene;
			$boerseContent = $this->load->view('boerse/abschluss', $this->data, true);
		} else {
			$naechsteOffene = Boerse::naechsteOffene();
			if (is_null($naechsteOffene)) {
				$naechsteOffene = new Boerse();
			}
			$this->data['naechsteBoerse'] = $naechsteOffene;
			$boerseContent = $this->load->view('boerse/formular', $this->data, true);
			
			if (date('Y-m-d') == $naechsteOffene->datum) {
				$boerseContent = $this->load->view('boerse/heuteistboerse', $this->data, true);
			}
		}
		$this->data['boerseContent'] = $boerseContent;

		$this->load->view('admin/index', $this->data);
		
		return;
	}


	/**
	 * Creates a user in the database
	 *
	 */
	public function registerUser()
	{
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
	
	
	
	public function vergangeneBoersen()
	{
		$alleBoersen = Boerse::all('geschlossen');
		$this->data['alleBoersen'] = $alleBoersen;
		$this->load->view('admin/vergangeneBoersen', $this->data);
	}
}
