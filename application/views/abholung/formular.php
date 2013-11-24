<?php 
include APPPATH . 'views/header.php';

echo '
<div>
	<h1>Nicht verkauftes Velo wird abgeholt</h1>

	<p>Gib im Formular die Quittungs-Nummer ein, um die Abholung zu registrieren.</p>
		
</div>';

if ('haendlerabholung' != $this->session->userdata('user_ressort')) {
	echo '
	<div>
		<p>' . anchor('login/dispatch/kasse', 'Zur Kasse (Velos verkaufen)') . '</p>
	</div>';
}

include APPPATH . 'views/footer.php';