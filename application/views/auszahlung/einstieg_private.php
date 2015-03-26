<?php 
include APPPATH . 'views/header.php';

if ($this->session->flashdata('gespeichertesVelo')) {
	echo '
	<div class="alert alert-info">
		<h3>Gespeicherte Angaben</h3>
		<dl>
			<dt>Quittung Nr.</dt><dd>' . $velo->id . '</dd>
			<dt>Preis</dt><dd>' . $velo->preis. '</dd>
			';
if ('yes' == $velo->keine_provision) {
	echo '<dt>Keine Provision</dt><dd>' . $keineProvision . '</dd>';
	echo '<dt>Auszahlung</dt><dd>' . $velo->preis . '</dd>';
} else {
	echo '<dt>Provision</dt><dd>' . velo::getProvision($velo->preis) . '</dd>';
	echo '<dt>Auszahlung</dt><dd>' . ($velo->preis - velo::getProvision($velo->preis)) . '</dd>';
}
echo		'
		</dl>
	</div>';
}

echo '

<div>
	<h1>Auszahlung Private</h1>
';

echo '
	<p>Gib im Formular die Quittungs-Nummer ein, damit Du die Auszahlung abwickeln kannst.</p>
	<h2>Kleine Statistik</h2>
	<table class="table table-striped table-bordered table-condensed">
	<tbody>
		<tr>
			<th scope="row">Velos auf Platz</th>
			<td>' . $newStatistics['countPrivateAufPlatz'] . '</td>
		</tr>
		<tr>
			<th scope="row">Vermuteter Bargeldbedarf</th>
			<td>' . number_format(round($newStatistics['probAuszahlung']), 0, '.', '\'') . '</td>
		</tr>
		<tr>
			<th scope="row">Maximaler Bargeldbedarf</th>
			<td>' . number_format($newStatistics['maxAuszahlung'], 0, '.', '\'') . '</td>
		</tr>
	</tbody>
	</table>
</div>';

include APPPATH . 'views/footer.php';