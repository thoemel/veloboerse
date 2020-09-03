<?php
include APPPATH . 'views/header.php';
?>

<h1>
	Exportierte EZAG Datei ist fehlerhaft.
</h1>

<p>
	Bei den folgenden Benutzern ist eine fehlerhafte IBAN eingetragen. Bitte bereinige dies in der Benutzeradmin.
</p>

<ul>
<?php
foreach ($falscheIban as $user_id => $user_array) {
    echo '<li>' .
    anchor('benutzeradmin/userForm/'.$user_id, $user_array['vorname'] . ' ' . $user_array['nachname']) . '
    (' . $user_array['email'] . ')
    </li>';
}

?>
</ul>

<p>
	Falls das nicht weiterhilft, schicke den folgenden Code an Thoemel.
</p>

<pre>
<?php var_dump($xml_errors);?>
</pre>

<?php
include APPPATH . 'views/footer.php';