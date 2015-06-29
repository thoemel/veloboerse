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

echo '
	<p>Ihre Angaben werden überprüft und die entsprechende Anzahl Quittungsnummern zugewiesen.</p>
		
	<p>Hier noch mehr PROSA einfügen!!!</p>';

include APPPATH . 'views/footer.php';