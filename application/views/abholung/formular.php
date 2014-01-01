<?php 
include APPPATH . 'views/header.php';

if (!empty($abgeholt)) {
	if (isset($haendler)) {
		echo '
		<div class="alert alert-info">
			<h2>Händler Nr. ' . $haendler->id . '</h2>
			<p class="verybig">Noch 
				<span class="badge verybig">' . $verbleibend . '</span> Velos</p>
		</div>';
	}
	echo '
	<div class="alert alert-info">
		<h3>Gespeicherte Angaben</h3>
		<dl>
			<dt>Quittung Nr.</dt><dd>' . $velo->id . '</dd>
			<dt>Preis</dt><dd>' . $velo->preis . '</dd>
			<dt>Abgeholt</dt><dd>' . $abgeholt . '</dd>
		</dl>
	</div>';
}

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