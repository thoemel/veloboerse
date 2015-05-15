<?php 
include APPPATH . 'views/header.php';

if (!empty($success) && 'speichern_private' == $this->uri->segment(2)) {
	echo '
	<div class="alert alert-info">
		<h3>Gespeicherte Angaben</h3>
		<dl>
			<dt>Quittung Nr.</dt><dd>' . $velo->id . '</dd>
			<dt>Preis</dt><dd>' . $velo->preis . '</dd>
			<dt>Provision</dt><dd>' . $velo->getProvision($velo->preis) . '</dd>
			<dt>Ausweis gezeigt</dt><dd>' . $ausweisGezeigt . '</dd>
			<dt>Velafrika</dt><dd>' . $velafrika . '</dd>';
	if ('yes' == $velo->keine_provision) {
		echo '
			<dt>Helfer verkauft</dt><dd>Ja <span class="badge">Name muss in Bemerkungen rein!</span></dd>';
	}
	if (!empty($velo->bemerkungen)) {
		echo '
			<dt>Bemerkungen</dt><dd>' . $velo->bemerkungen . '</dd>';
	}
	echo '
		</dl>
	</div>';
}

echo '

<div>
	<h1>Velo für Privat erfassen</h1>

	<p>Gib im Formular die Quittungs-Nummer ein, damit Du Details zum Velo erfassen kannst.</p>';


echo '
</div>';

include APPPATH . 'views/footer.php';