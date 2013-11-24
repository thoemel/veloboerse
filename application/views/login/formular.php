<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Login</h1>
';

echo form_open('login/logMeIn', array('id' => 'loginformular'));

echo '<label for="username">Benutzername</label>';
echo form_input(array('id' => 'username', 'name' => 'username', 'value' => '', 'class' => 'focusPlease'));

echo '<label for="password">Passwort</label>';
echo form_password(array('id' => 'password', 'name' => 'password', 'value' => ''));

echo form_submit('login-button', 'Einloggen', 'class="btn"');
echo form_close();

echo '
</div>';

include APPPATH . 'views/footer.php';