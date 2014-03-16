<?php
include APPPATH . 'views/header.php';

echo '
' . heading('Velos für Afrika', 1) . '

<p>Diese Velos wurden verkauft, aber nicht ausbezahlt.</p>

<table class="table table-striped table-bordered table-condensed">
<thead>
	<tr>
		<th scope="col">Quittung</th>
		<th scope="col">Preis</th>
		<th scope="col">Provision</th>
		<th scope="col">Auszuzahlender Betrag</th>
	</tr>
</thead>
<tbody>';
foreach ($veloQuery as $row) {
	echo '
	<tr>
		<td>' . $row->id . '</td>
		<td>' . $row->preis . '</td>
		<td>' . Velo::getProvision($row->preis) . '</td>
		<td>' . ($row->preis - Velo::getProvision($row->preis)) . '</td>
	</tr>';
}
echo '
	</tr>
</tbody>
</table>';

include APPPATH . 'views/footer.php';
