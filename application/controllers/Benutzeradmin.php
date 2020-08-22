<?php
/**
 * Administrative Aufgaben
 *
 * @author thoemel@thoemel.ch
 */
class Benutzeradmin extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Zeige ein Formular, um einen neuen Benutzer einzurichten.
     * @uses $this->userForm
     */
    public function createUserForm()
    {
        $this->userForm(0);
        return;
    }


    /**
     * Lösche einen Benutzer
     * @param int $id
     */
    public function deleteUser($id)
    {
        // Logged-in user must be admin.
        $this->requireRole('admin');

        $myUser = new M_user();
        $myUser->id = $id;
        $myUser->delete();
        redirect('benutzeradmin/liste');
        return;
    }


    /**
     * Ändert die Daten eines Benutzers.
     */
    public function editUser()
    {
        // Logged-in user must be admin.
        $this->requireRole('admin');

        if ($this->form_validation->run('editUser') === false) {
            // Not registered because of wrong input
            $formValues = array();
            $formValues['email'] = set_value('email');
            $formValues['username'] = set_value('username');
            $formValues['vorname'] = set_value('vorname');
            $formValues['nachname'] = set_value('nachname');
            $formValues['strasse'] = set_value('strasse');
            $formValues['plz'] = set_value('plz');
            $formValues['ort'] = set_value('ort');
            $formValues['iban'] = set_value('iban');
            $this->addData('formValues', $formValues);
            redirect('benutzeradmin/userForm/' . $this->input->post('user_id'));
            return;
        }

        /*
         * Testen, ob ein anderer User diese E-Mail hat.
         */
        $testUser = new M_user();
        $testUser->fetch4email($this->input->post('email'));
        if ($testUser->id !== $this->input->post('user_id')) {
            $formValues['email'] = set_value('Diese E-Mail ist schon vergeben.');
            $this->addData('formValues', $formValues);
            $this->userForm($this->input->post('id'));
            return;
        }

        /*
         * Testen, ob ein anderer User diesen Benutzernamen hat.
         */
        $testUser = new M_user();
        $testUser->fetch4username($this->input->post('username'));
        if ($testUser->id !== $this->input->post('user_id')) {
            $formValues['username'] = set_value('Dieser Benutzername ist schon vergeben.');
            $this->addData('formValues', $formValues);
            $this->userForm($this->input->post('id'));
            return;
        }

        $myUser = new M_user();
        if (FALSE === $myUser->fetch($this->input->post('user_id'))) {
            redirect();
            return;
        }
        $myUser->email = $this->input->post('email');
        $myUser->username = $this->input->post('username');
        $myUser->vorname = $this->input->post('vorname');
        $myUser->nachname = $this->input->post('nachname');
        $myUser->strasse = $this->input->post('strasse');
        $myUser->plz = $this->input->post('plz');
        $myUser->ort = $this->input->post('ort');
        $myUser->iban = $this->input->post('iban');
        $myUser->auth_level = $this->input->post('rolle');
        if (!empty($this->input->post('password'))) {
            $myUser->set_password($this->input->post('password'));
            $myUser->passwd_modified_at = date('Y-m-d H:i:s');
        }
        $myUser->modified_at = date('Y-m-d H:i:s');


        if($myUser->save()) {
            $this->session->set_flashdata('success', 'Benutzer speichern erfolgreich.');
        } else {
            // Not registered because of technical reason
            $this->session->set_flashdata('error', 'Benutzer speichern fehlgeschlagen.');
        }

        redirect('benutzeradmin/liste');
    } // End of function editUser()


    /**
     * Zeigt eine Liste aller registrierter Benutzer.
     */
    public function liste() {
        // Logged-in user must be admin.
        $this->requireRole('admin');

        $this->addData('allUsers', $this->M_user->all());
        $this->addData('levels_and_roles', config_item('levels_and_roles'));
        $this->load->view('benutzeradmin/liste', $this->data);
    }


    /**
     * Creates a user in the database
     *
     * @return void
     *
     */
    public function registerUser()
    {
        if (is_role('admin')) {
            $back_uri = 'benutzeradmin/createUserForm';
            $redirect_uri = 'benutzeradmin/liste';
        } else {
            $back_uri = 'login/registrationForm';
            $redirect_uri = 'login/form';
        }

        if ($this->form_validation->run('createUser') === false) {
            // Not registered because of wrong input
            $this->session->set_flashdata('error', validation_errors());
            redirect($back_uri);
        }

        $myUser = new M_user();
        $myUser->email = $this->input->post('email');
        $myUser->username = $this->input->post('username');
        $myUser->vorname = $this->input->post('vorname');
        $myUser->nachname = $this->input->post('nachname');
        $myUser->strasse = $this->input->post('strasse');
        $myUser->plz = $this->input->post('plz');
        $myUser->ort = $this->input->post('ort');
        $myUser->iban = $this->input->post('iban');
        if (!empty($this->input->post('rolle'))) {
            $myUser->auth_level = $this->input->post('rolle');
        } else {
            $myUser->auth_level = 1; // Private
        }
        if (!empty($this->input->post('password'))) {
            $myUser->set_password($this->input->post('password'));
            $myUser->passwd_modified_at = date('Y-m-d H:i:s');
        }
        $myUser->created_at = date('Y-m-d H:i:s');


        if($myUser->save()) {
            $this->session->set_flashdata('success', 'Benutzer speichern erfolgreich.');
            redirect($redirect_uri);
        } else {
            // Not registered because of technical reason
            $this->session->set_flashdata('error', 'Benutzer speichern fehlgeschlagen. Bitte wenden Sie sich an die Pro Velo');
            redirect($back_uri);
        }

        return;
    }


    /**
     * Zeige ein Formular um einen User zu erstellen oder editieren.
     *
     * @param int $user_id
     */
    public function userForm($user_id)
    {
        // Logged-in user must be admin.
        $this->requireRole('admin');

        $formAction = $user_id > 0 ? 'Benutzeradmin/editUser' : 'Benutzeradmin/registerUser';
        $this->addData('formAction', $formAction);

        $this->M_user->fetch($user_id);
        $this->addData('myUser', $this->M_user);

        $rolesDrop = form_dropdown('rolle', config_item('levels_and_roles'), $this->M_user->auth_level, array('id'=>'role_drop'));
        $this->addData('rolesDrop', $rolesDrop);

        $this->load->view('benutzeradmin/userForm', $this->data);
    }


} // End of class Benutzeradmin