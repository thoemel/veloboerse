<?php
include APPPATH . 'views/header.php';

if ($this->session->userdata('logged_in')) {
	echo '
	<ol class="breadcrumb hidden-print">
		<li>' . anchor('login/showChoices', 'Ressorts') . '</li>
		<li>' . anchor('haendleradmin', 'Händleradmin') . '</li>
		<li>' . anchor('haendleradmin/quittungen/' . $haendler->id, 'Quittungen zuweisen') . '</li>
		<li class="active">In Bearbeitung</li>
	</ol>';
}
echo heading('In Bearbeitung', 1) . '
' . heading('Name: ' . $haendler->person, 2);
echo heading('--------------------', 3);
echo heading('Ihre Angaben werden von uns überprüft und die gewünschte Anzahl Quittungsnummern zugewiesen.', 4);
echo heading('Sobald die Quittungsnummern zugewiesen sind, gelangen Sie hier zur Seite, wo Sie ihre Vélos eintragen können.', 4);


include APPPATH . 'views/footer.php';