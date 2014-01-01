<?php 
include APPPATH . 'views/header.php';

if ($this->session->flashdata('gespeichertesVelo')) {
	echo '
	<div class="alert alert-info">
		<h3>Gespeicherte Angaben</h3>
		<dl>
			<dt>Quittung Nr.</dt><dd>' . $velo->id . '</dd>
			<dt>Keine Provision</dt><dd>' . $keineProvision . '</dd>
		</dl>
	</div>';
}

echo '

<div>
	<h1>Auszahlung Private</h1>
';

echo '
	<p>Gib im Formular die Quittungs-Nummer ein, damit Du die Auszahlung abwickeln kannst.</p>';

echo '
</div>';

include APPPATH . 'views/footer.php';