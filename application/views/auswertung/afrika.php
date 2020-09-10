<?php
include APPPATH . 'views/header.php';

echo '
' . heading('Velos für Afrika', 1) . '

<h2>Diese Velos wurden verkauft, aber nicht ausbezahlt.</h2>

<table class="table table-striped table-bordered table-condensed">
<thead>
	<tr>
		<th scope="col">Quittung</th>
		<th scope="col">Preis</th>
		<th scope="col">Provision</th>
		<th scope="col">Auszuzahlender Betrag</th>
		<th scope="col">Als für Afrika registriert?</th>
	</tr>
</thead>
<tbody>';
foreach ($veloQuery['verkauft_nicht_ausbezahlt'] as $row) {
	$velafrika = $row->afrika == 1 ? 'Ja' : 'Nein';
	echo '
	<tr>
		<td>' . $row->id . '</td>
		<td>' . $row->preis . '</td>
		<td>' . Velo::getProvision($row->preis) . '</td>
		<td>' . ($row->preis - Velo::getProvision($row->preis)) . '</td>
		<td>' . $velafrika . '</td>
	</tr>';
}
echo '
	</tr>
</tbody>
</table>';
echo '
<h2>Alle für Afrika registrierten Velos</h2>

<table class="table table-striped table-bordered table-condensed">
<thead>
	<tr>
		<th scope="col">Quittung</th>
		<th scope="col">Preis</th>
		<th scope="col">Provision</th>
		<th scope="col">Auszuzahlender Betrag</th>
		<th scope="col">Als für Afrika registriert</th>
		<th scope="col">Verkauft</th>
		<th scope="col">Ausbezahlt</th>
	</tr>
</thead>
<tbody>';
foreach ($veloQuery['afrika_registriert'] as $row) {
	$velafrika = $row->afrika == 1 ? 'Ja' : 'Nein';
	$verkauft = $row->verkauft == 'yes' ? 'Ja' : 'Nein';
	$ausbezahlt = $row->ausbezahlt == 'yes' ? 'Ja' : 'Nein';
	echo '
	<tr>
		<td>' . $row->id . '</td>
		<td>' . $row->preis . '</td>
		<td>' . Velo::getProvision($row->preis) . '</td>
		<td>' . ($row->preis - Velo::getProvision($row->preis)) . '</td>
		<td>' . $velafrika . '</td>
		<td>' . $verkauft . '</td>
		<td>' . $ausbezahlt . '</td>
	</tr>';
}
echo '
	</tr>
</tbody>
</table>';

include APPPATH . 'views/footer.php';
