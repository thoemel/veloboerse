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

		// Logged-in user must be admin.

		$this->requireRole('admin');

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

		    // Velobilder
		    $bkupPath = FCPATH . 'backups/';
		    $imgPath = FCPATH . 'uploads/';
		    try
		    {
		        $archiveName = $bkupPath . 'bkup_boerse_bilder_' . date('Ymd', strtotime($boerse->datum)) . '.tar';
		        $a = new PharData($archiveName);

		        foreach (glob($imgPath.'*.{gif,jpg,jpeg,png,GIF,JPG,JPEG,PNG}',GLOB_BRACE) as $filename) {
		            $a->addFile($filename);
		        }

		        // COMPRESS archive.tar FILE. COMPRESSED FILE WILL BE archive.tar.gz
		        $a->compress(Phar::GZ);

		        // NOTE THAT BOTH FILES WILL EXISTS. SO IF YOU WANT YOU CAN UNLINK archive.tar
		        unlink($archiveName);
		    }
		    catch (Exception $e)
		    {
		        echo "Exception : " . $e;
		        die();
		    }
		    foreach (glob($imgPath.'*.{gif,jpg,jpeg,png,GIF,JPG,JPEG,PNG}',GLOB_BRACE) as $filename) {
		        unlink($filename);
		    }

		    // Quittungen
		    $quittungenPath = FCPATH . 'quittungen/';
		    try
		    {
		        $archiveName = $bkupPath . 'bkup_boerse_quittungen_' . date('Ymd', strtotime($boerse->datum)) . '.tar';
		        $a = new PharData($archiveName);

		        foreach (glob($quittungenPath.'*.pdf',GLOB_BRACE) as $filename) {
		            $a->addFile($filename);
		        }

		        // COMPRESS archive.tar FILE. COMPRESSED FILE WILL BE archive.tar.gz
		        $a->compress(Phar::GZ);

		        // NOTE THAT BOTH FILES WILL EXISTS. SO IF YOU WANT YOU CAN UNLINK archive.tar
		        unlink($archiveName);
		    }
		    catch (Exception $e)
		    {
		        echo "Exception : " . $e;
		        die();
		    }
		    foreach (glob($quittungenPath.'*.pdf',GLOB_BRACE) as $filename) {
		        unlink($filename);
		    }


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

			// Haendler  zurücksetzen
			$this->load->model('haendler');
			Haendler::alleZuruecksetzen();


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
		$zip->addFile("backups/bkup_boerse_bilder_".$datumsTeil.".tar.gz");
		$zip->addFile("backups/bkup_boerse_quittungen_".$datumsTeil.".tar.gz");
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
	 * @deprecated
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
	 * @deprecated
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



	public function ezag()
	{
	    $this->output->enable_profiler(FALSE);
	    // Hat es eine offene Börse in der Vergangenheit?
	    if (Boerse::aktuelle() === NULL) {
	        $this->session->set_flashdata('error', 'Es hat keine offene Börse in der Vergangenheit. Zur Zeit kann kein EZAG erstellt werden.');
	        redirect('admin');
	        return;
	    }


	    // Velos, die verkauft und nicht ausbezahlt wurden
        $velos = Velo::ezag();

        // Provision abziehen und grand Total berechnen
        $grandTotal = 0;
        $falscheIban = [];
        foreach ($velos as &$row) {
            if ('yes' == $row->keine_provision) {
                $row->auszuzahlen = $row->preis;
            } else {
                $row->auszuzahlen = $row->preis - velo::getProvision($row->preis);
            }
            $grandTotal += $row->auszuzahlen;

            // Check if IBAN is syntactically correct
            $tmp = strtoupper(substr($row->iban, 0, 2));
            $row->iban = $tmp . substr($row->iban, 2);
            $ibanOk = preg_match('/[A-Z]{2,2}[0-9]{2,2}[a-zA-Z0-9]{1,30}/', str_replace(' ', '', $row->iban));
            if (0 == $ibanOk) {
                $userWithWrongIban = new M_user();
                $userWithWrongIban->fetch($row->verkaeufer_id);
                if (!array_key_exists($row->verkaeufer_id, $falscheIban)) {
                    $falscheIban[$row->verkaeufer_id] = [
                        'user_id' => $userWithWrongIban->id,
                        'vorname' => $userWithWrongIban->vorname,
                        'nachname' => $userWithWrongIban->nachname,
                        'strasse' => $userWithWrongIban->strasse,
                        'plz' => $userWithWrongIban->plz,
                        'ort' => $userWithWrongIban->ort,
                        'iban' => $userWithWrongIban->iban,
                        'email' => $userWithWrongIban->email
                    ];
                }
            }
        }



	    // XML zusammenstellen
        $this->addData('velos', $velos);
        $this->addData('grandTotal', $grandTotal);
        $ezag = $this->load->view('admin/ezag', $this->data, true);

        // XML validieren
        libxml_use_internal_errors(TRUE);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($ezag);
        $valid = $dom->schemaValidate(FCPATH.'application/models/pain.001.001.03.ch.02.xsd');
        if (!$valid) {
            $errors = libxml_get_errors();
//             var_dump($errors);
//             var_dump($falscheIban);
//             die('hier');
            $this->addData('xml_errors', $errors);
            $this->addData('falscheIban', $falscheIban);
            $this->load->view('admin/ezag_fehler', $this->data);
        } else {
            // Datei als Download
            $this->load->helper('download');
            force_download('ezag_veloboerse'.Boerse::aktuelle()->datum.'.xml', $ezag);
        }
        libxml_use_internal_errors(FALSE);

	    return;
	} // End of function ezag()


	public function index()
	{
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
	 * @deprecated
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
	 * Shows the edit form for a user.
	 * Used for create and edit
	 *
	 * @param	String	$userId
	 * @deprecated
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
