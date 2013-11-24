<?php
include APPPATH . 'views/header.php';

echo '
<div class="haendleradmin">
	<h1>Händleradmin: Händler wählen</h1>
		
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th scope="column">Firma</th>
				<th scope="column">Person</th>
				<th scope="column">Velos</th>
				<th scope="column">Import</th>
				<th scope="column">Quittungen</th>
			</tr>
		</thead>
		<tbody>';

foreach ($liste->result() as $haendler) {
	echo '
			<tr>
				<th scope="row">' . $haendler->firma . '</th>
				<td>' . $haendler->person . '</td>
				<td>' . anchor('haendleradmin/velos/' . $haendler->id, 'Velos'). '</td>
				<td>' . anchor('haendleradmin/import/' . $haendler->id, 'CSV Import') . '</td>
				<td>' . anchor('haendleradmin/quittungen/' . $haendler->id, 'Quittungen zuweisen', 'inactive="true"') . '</td>
			</tr>';
}	

echo '
		</tbody>
	</table>
</div>';


include APPPATH . 'views/footer.php';