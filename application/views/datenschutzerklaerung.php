<?php
include APPPATH . 'views/header.php';

// Set the link protocol
$link_protocol = is_https() ? 'https:' : 'http:';

echo '
<h1>Datenschutzerklärung</h1>
<h2>Wer wir sind</h2>
<p>
Wir sind ' . config_item('veranstalter') . '
<br>
Die Adresse unserer Website ist: ' . site_url('', $link_protocol) . '.
</p>

<h2>Cookies</h2>
<p>
Wir verwenden Cookies, um registrierten Nutzern zu ermöglichen, sich einzuloggen und Velos anzubieten.
</p><p>
Wenn du dich anmeldest, werden wir einige Cookies einrichten, um deine Anmeldeinformationen zu speichern.
Anmelde-Cookies verfallen nach zwei Stunden. Mit der Abmeldung aus deinem Konto werden die Anmelde-Cookies gelöscht.
</p><p>
Wenn du eine Zurücksetzung des Passworts beantragst, wird deine IP-Adresse in der E-Mail zur Zurücksetzung enthalten sein.
</p>

<h2>Wie lange wir deine Daten speichern</h2>
<p>
Für Benutzer, die sich auf unserer Website registrieren, speichern wir zusätzlich die persönlichen Informationen,
die sie in Registrationsformular angeben. Alle Benutzer können jederzeit ihre persönlichen Informationen einsehen,
verändern oder löschen (der Benutzername und die E-Mail Adresse kann nicht verändert werden).
<br>Administratoren der Website können diese Informationen ebenfalls einsehen und verändern.
</p><p>
Von dir angemeldete Velos werden nach der Börse aus dem System gelöscht. Falls du dein Velo nicht verkaufen konntest und es
an der nächsten Börse wieder versuchen möchtest, wirst du es neu erfassen müssen.
</p>

<h2>Welche Rechte du an deinen Daten hast</h2>
<p>
Wenn du ein Konto auf dieser Website besitzt oder Kommentare geschrieben hast, kannst du einen Export deiner
personenbezogenen Daten bei uns anfordern, inklusive aller Daten, die du uns mitgeteilt hast.
Darüber hinaus kannst du die Löschung aller personenbezogenen Daten, die wir von dir gespeichert haben, anfordern.
Dies umfasst nicht die Daten, die wir aufgrund administrativer, rechtlicher oder sicherheitsrelevanter Notwendigkeiten
aufbewahren müssen.
</p>

<h2>Wohin wir deine Daten senden</h2>
<p>
Wir geben deine Daten niemandem weiter.
</p>
';


include APPPATH . 'views/footer.php';