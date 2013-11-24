<?php
include APPPATH . 'views/header.php';

echo '<div id="userForm">';
echo heading('Benutzer', 2);

echo form_open($formAction);
if ($formValues['id']) {
	echo form_hidden('id', $formValues['id']);
}
echo "<label>E-mail</label>";
echo form_error('email');
echo form_input('email', $formValues['email']) . "<br>";
echo "<label>Passwort</label>";
echo form_error('pw');
echo form_password('pw', $formValues['pw']) . "<br>";
echo "<label>Rolle</label>";
echo form_error('role');
echo form_dropdown('role', $roles, $formValues['role']) . "<br>";
echo form_submit('submit', 'speichern');
echo form_close();

echo '</div>';

include APPPATH . 'views/footer.php';