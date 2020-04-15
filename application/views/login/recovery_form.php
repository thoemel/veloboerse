<?php
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Neues Passwort</h1>
    <p>
        Gib im Formular zwei mal dein neues Passwort ein.<br>
    </p>
</div>';

echo form_open('login/recovery_verification/' . $user_id . '/' . $unhashed_recovery_code, array('id' => 'recoveryPw'));
echo form_hidden('user_id', $user_id);
echo form_hidden('recovery_code', $recovery_code);

echo '
    <div class="row">
	<div class="form-group">
		<label for="password" class="col-lg-2 control-label">Passwort</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('password') . '
            ' . form_password(array('id' => 'password', 'name' => 'passwd', 'value' => '', 'class' => 'form-control')) . '
		</div>
	</div>
    </div>
    <div class="row">
	<div class="form-group">
		<label for="password_confirm" class="col-lg-2 control-label">Passwort bestätigen</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('password') . '
            ' . form_password(array('id' => 'password_confirm', 'name' => 'passwd_confirm', 'value' => '', 'class' => 'form-control')) . '
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