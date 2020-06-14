<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'views/header.php';

echo validation_errors();

echo '
<div>
    <h1>Benutzer registrieren</h1>
</div>';

echo form_open($formAction, array('id' => 'registrierFormular'));

echo '
    <div class="row">
	<div class="form-group">
		<label for="email_input" class="col-lg-2 control-label">E-Mail</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('email') . '
			' . form_input(array('id' => 'email_input', 'name' => 'email', 'value' => $myUser->email, 'class' => 'focusPlease form-control')) . '
		</div>
	</div>
    </div>
    <div class="row">
	<div class="form-group">
		<label for="username_input" class="col-lg-2 control-label">Benutzername</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('username') . '
			' . form_input(array('id' => 'username_input', 'name' => 'username', 'value' => $myUser->username, 'class' => 'form-control')) . '
		</div>
	</div>

	</div>
    <div class="row">
	<div class="form-group">
		<label for="password" class="col-lg-2 control-label">Passwort</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('password') . '
            ' . form_password(array('id' => 'password', 'name' => 'password', 'value' => '', 'class' => 'form-control')) . '
		</div>
	</div>
    </div>
    <div class="row">
	<div class="form-group">
		<label for="vorname_input" class="col-lg-2 control-label">Vorname</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('vorname') . '
			' . form_input(array('id' => 'vorname_input', 'name' => 'vorname', 'value' => $myUser->vorname, 'class' => 'form-control')) . '
		</div>
	</div>
	</div>
    <div class="row">
	<div class="form-group">
		<label for="nachname_input" class="col-lg-2 control-label">Nachname</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('nachname') . '
			' . form_input(array('id' => 'nachname_input', 'name' => 'nachname', 'value' => $myUser->nachname, 'class' => 'form-control')) . '
		</div>
	</div>
	</div>
    <div class="row">
    <div class="form-group">
		<label class="col-sm-2 control-label">Adresse</label>
		<div class="col-sm-10">
            ' . form_error('adresse') . '
			<textarea name="adresse" class="form-control" rows="3">'
                . $myUser->adresse
                . '</textarea>
		</div>
	</div>
	</div>
    <div class="row">
	<div class="form-group">
		<label for="iban_input" class="col-lg-2 control-label">IBAN</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('iban') . '
			' . form_input(array('id' => 'iban_input', 'name' => 'iban', 'value' => $myUser->iban, 'class' => 'form-control')) . '
		</div>
	</div>
	</div>
    <div class="row">
	<div class="form-group col-sm-offset-2 col-sm-10">
        ' . form_submit('submit', 'Speichern', 'class="btn"') . '
	</div>
	</div>
';


echo form_close();

include APPPATH . 'views/footer.php';