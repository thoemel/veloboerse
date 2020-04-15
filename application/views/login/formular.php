<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'views/header.php';


if( ! isset( $optional_login ) )
{
    echo '

<div>
	<h1>Login</h1>
';

}

if( ! isset( $on_hold_message ) ) {
    if( isset( $login_error_mesg ) ) {
        echo '
			<div class="alert alert-error">
				<p>
					Login fehlgeschlagen. Ungültige E-Mail Adresse oder Passwort.<br>'
					. 'Sie haben noch '
					. $this->authentication->login_errors_count . ' von ' . config_item('max_allowed_attempts') . ' Versuchen.<br>
				</p>
			</div>
		';
    }

    if( $this->input->get(AUTH_LOGOUT_PARAM) ) {
        echo '
			<div style="border:1px solid green">
				<p>
					Sie sind ausgeloggt.
				</p>
			</div>
		';
    }

    echo form_open('login', array('id' => 'loginformular'));

    echo '
    <div class="row">
	   <div class="form-group">
            <label for="username" class="col-lg-2 control-label">Benutzername oder E-Mail</label>
            ' . form_input(array('id' => 'username', 'name' => 'login_string', 'value' => '', 'class' => 'focusPlease')) . '
        </div>
    </div>
    <div class="row">
	    <div class="form-group">
            <label for="password" class="col-lg-2 control-label">Passwort</label>
            ' . form_password(array('id' => 'password', 'name' => 'login_pass', 'value' => '')) . '
        </div>
    </div>';

    if( config_item('allow_remember_me') ) {
        echo '
			<div class="row">
	        <div class="form-group">
            <span class="alert alert-info">Dieses Feature funktioniert noch nicht</span>
			<label for="remember_me" class="col-lg-2 control-label">Eingeloggt bleiben</label>
			<input type="checkbox" id="remember_me" name="remember_me" value="yes" />
            </div>
            </div>';
	}

	$link_protocol = USE_SSL ? 'https' : NULL;
	$vergessenHref = site_url('login/recovery_request', $link_protocol);
	echo '
    <div class="row">
	    <div class="form-group">
			<a href="' . $vergessenHref . '" class="col-lg-2 col-sm-offset-2">Passwort vergessen</a>
		</div>
    </div>';

    echo form_submit('submit', 'Einloggen', 'class="btn"');
    echo form_close();


	} else {
		// EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
		echo '
			<div class="alert alert-error">
				<p>
					Zu viele Login-Versuche
				</p>
				<p>
					Die Website erlaubt höchstens ' . config_item('max_allowed_attempts') . ' Versuche.
				<p>
				<p>
					Ihr Konto wurde für ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' Minuten gesperrt.
				</p>
				<p>
					Nach dieser Zeit können Sie mit dem Passwort-vergessen-Link wieder Zugang beantragen.
				</p>
			</div>
		';
	}

include APPPATH . 'views/footer.php';