<?php
include APPPATH . 'views/header.php';

echo '
<div>
	<h1>Auszahlung Händler: Händler wählen</h1>
		
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th>Nr.</th>
			<th>Firma</th>
			<th>Kontaktperson</th>
			<th>Status</th>
			<th>Link</th>
		</tr>';

foreach ($haendlerQuery->result() as $haendler) {
	echo '
		<tr>
			<td>' . $haendler->id . '</td>
			<td>' . $haendler->firma . '</td>
			<td>' . $haendler->person . '</td>
			<td>' . $haendler->status . '</td>
			<td>' . anchor('auszahlung/velos/'.$haendler->id, 'Tabelle') . '</td>
		</tr>';
}	

echo '
	</table>
</div>';


include APPPATH . 'views/footer.php';