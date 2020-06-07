<?php
/**
 * Was die privaten VerkäuferInnen so machen
 *
 * @author thoemel@thoemel.ch
 */
class Verkaeufer extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        // User must be logged in.
        $this->requireLoggedIn();
    }


    /**
     * Ändert die Daten eines Benutzers.
     */
    public function editUser()
    {
        $this->requireRole('Verkäufer privat');

        if ($this->form_validation->run('editVerkaeufer') === false) {
            // Not registered because of wrong input
            $this->appendData('error', validation_errors());
            $this->userForm();
            return;
        }

        /*
         * Testen, ob ein anderer User diese E-Mail hat.
         */
        $testUser = new M_user();
        $testUser->fetch4email($this->input->post('email'));
        if ($testUser->id != $this->auth_user_id) {
            $this->appendData('error', 'Diese E-Mail ist schon vergeben.');
            $this->userForm();
            return;
        }

        /*
         * Testen, ob ein anderer User diesen Benutzernamen hat (falls username geändert).
         */
        if ($this->auth_username != $this->input->post('username')) {
            $testUser = new M_user();
            $testUser->fetch4username($this->input->post('username'));
            if ($testUser->id !== $this->auth_user_id) {
                $this->appendData('error', 'Dieser Benutzername ist schon vergeben.');
                $this->userForm();
                return;
            }
        }

        $myUser = new M_user();
        $myUser->fetch($this->auth_user_id);
        $myUser->email = $this->input->post('email');
        $myUser->username = $this->input->post('username');
        $myUser->vorname = $this->input->post('vorname');
        $myUser->nachname = $this->input->post('nachname');
        $myUser->adresse = $this->input->post('adresse');
        $myUser->iban = $this->input->post('iban');
        if (!empty($this->input->post('password'))) {
            $myUser->set_password($this->input->post('password'));
            $myUser->passwd_modified_at = date('Y-m-d H:i:s');
        }
        $myUser->modified_at = date('Y-m-d H:i:s');


        if($myUser->save()) {
            $this->session->set_flashdata('success', 'Die Angaben wurden gespeichert.');
        } else {
            // Not registered because of technical reason
            $this->session->set_flashdata('error', 'Speichern fehlgeschlagen.');
        }

        redirect('verkaeufer/index');
    } // End of function editUser()


    public function index()
    {
        $meineVelos = Velo::fuerVerkaeufer($this->auth_user_id);
        $this->addData('meineVelos', $meineVelos);
        $this->load->view('verkaeufer/index', $this->data);
        return;
    }


    /**
     * Verkäufer will ein Velo doch nicht verkaufen...
     * @param int $id
     */
    public function stornieren($id) {
        $myVelo = new Velo();
        try {
            $myVelo->find($id);
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Kein Velo mit dieser ID registriert.');
            redirect('verkaeufer/index');
            return;
        }

        // Nur Velos, die noch nicht durch die Annahme sind, dürfen vom Verkäufer storniert werden
        if ('yes' == $myVelo->angenommen) {
            $this->session->set_flashdata('error', 'Velos, die schon im Verkauf sind, dürfen nicht mehr verändert werden.');
            redirect('verkaeufer/index');
            return;
        }

        // Nur eigene Velos
        if ($myVelo->verkaeufer_id !== $this->auth_user_id) {
            $this->session->set_flashdata('error', 'Sieht so aus, als gehöre das Velo gar nicht dir.');
            redirect('verkaeufer/index');
            return;
        }

        $myVelo->storniert = 1;
        if (true === $myVelo->save()) {
            $this->session->set_flashdata('success', 'Dein Angebot wurde storniert.');
        } else {
            $this->session->set_flashdata('error', 'Da ist etwas schief gelaufen. Melde dich bitte bei Pro Velo.');
        }
        redirect('verkaeufer/index');
        return;
    } // End of function stornieren()


    /**
     * Zeige ein Formular um einen User zu erstellen oder editieren.
     *
     * @param int $user_id
     */
    public function userForm()
    {
        $this->addData('formAction', 'verkaeufer/editUser');

        $this->M_user->fetch($this->auth_user_id);
        $this->addData('myUser', $this->M_user);


        $this->load->view('login/createUser', $this->data);
    }


    /**
     * Zeige das Erfassungs- bzw. Bearbeitungs-Formular an.
     * TODO Überlegen: Sollen Helfer "keine Provision" wählen können?
     *
     * @param number $id
     */
    public function veloformular($id = 0)
    {
        $id = intval($id);
        $myVelo = new Velo();
        if ($id > 0) {
            try {
                $myVelo->find($id);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                $this->addData('error', 'Es ist kein Velo mit dieser Quittungsnummer registriert.');
                $this->index();
                return ;
            }
        }

        $this->addData('myVelo', $myVelo);
        $this->addData('provisionsliste', Velo::provisionsliste());
        $this->load->view('verkaeufer/veloformular', $this->data);
    }


    /**
     * Verkäufer hat das Formular ausgefüllt (neu oder bearbeitet).
     */
    public function speichereVelo()
    {
        // Require user to be logged in.
        $this->requireLoggedIn();

        // form validation
        // TODO form validation einrichten
        if ($this->form_validation->run('veloErfassenVerkaeufer') === false) {
            $myError = validation_errors();
            $this->session->set_flashdata('error', validation_errors());
            redirect('verkaeufer/veloformular/'.$this->input->post('id'));
            return;
        }

        // Preis in ganzen Zehnern
        $preis = round($this->input->post('preis')/10) * 10;

        $myVelo = new Velo();
        try {
            $myVelo->find($this->input->post('id'));
        } catch (Exception $e) {
            $myVelo->id = 0;
        }
        $myVelo->verkaeufer_id = $this->auth_user_id;
        $myVelo->angenommen = 'no';
        $myVelo->afrika				= $this->input->post('velafrika');
        $myVelo->farbe				= $this->input->post('farbe');
        $myVelo->marke				= $this->input->post('marke');
        $myVelo->preis				= $preis;
        $myVelo->rahmennummer		= $this->input->post('rahmennummer');
        $myVelo->typ				= $this->input->post('typ');

        // Foto
        if (0 < $_FILES['userfile']['size']) {
            $config['upload_path']          = './uploads/';
            $config['allowed_types']        = 'gif|jpg|png';

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('userfile'))
            {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('verkaeufer/veloformular/'.$this->input->post('id'));
                return;
            } else {
                $data = array('upload_data' => $this->upload->data());
                $myVelo->img = $this->upload->data('file_name');
            }
        }

        // TODO Foto Rahmennummer

        $myVelo->save();

        $meineVelos = Velo::fuerVerkaeufer($this->auth_user_id);
        $this->addData('meineVelos', $meineVelos);
        $this->load->view('verkaeufer/index', $this->data);

        return;
    } // End of function erfasse
}