<?php
include APPPATH . 'views/header.php';

echo '
<div class="haendleradmin">
	<h1>Händleradmin</h1>
	<div class="bottom20 Direktlinks">
		' . anchor('haendleradmin/direktlinks', 'Direktlinks') . '
	</div>
	<div class="bottom20 Händler hinzufügen">
	' . anchor('haendleradmin/haendlerconfig/', 'Händler hinzufügen') . '
	</div>
	
	<h2>Händler wählen</h2>
	
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th scope="column">Nr.</th>
				<th scope="column">Bereit?</th>
				<th scope="column">Firma</th>
				<th scope="column">Person</th>
				<th scope="column">Status</th>
				<th scope="column">Quittungen</th>
				<th scope="column">Velos</th>
				<th scope="column">Abrechnung</th>
				<th scope="column">Bearbeiten</th>
			</tr>
		</thead>
		<tbody>';

foreach ($liste->result() as $haendler) {
	echo '
			<tr>
				<th scope="row">' . $haendler->id . '</th>';
	if (0 == $haendler->uptodate) {
		echo '<td>NEIN</td>';
	}
	else {
		echo '<td>JA-> ' . $haendler->uptodate . ' </td>';
	}
				
	echo '
				<td>' . $haendler->firma . '</td>
				<td>' . $haendler->person . '</td>
				<td>' . $haendler->status . '</td>
				<td>' . anchor('haendleradmin/quittungen/' . $haendler->id, 'zuweisen', 'inactive="true"') . '</td>
				<td>' . anchor('haendlerformular/' . $haendler->code, 'kontrollieren', 'inactive="true"') . '</td>
				<td>' . anchor('haendleradmin/abrechnung/' . $haendler->id, 'Abrechnung'). '</td>
				<td>' . anchor('haendleradmin/haendlerconfig/' . $haendler->id, '&nbsp;', array('title' => 'bearbeiten', 'class' => 'glyphicon glyphicon-edit'))
                      . anchor('haendleradmin/loeschen/' . $haendler->id, '&nbsp;', array('title' => 'löschen', 'class' => 'glyphicon glyphicon-trash', 'onclick' => 'return window.confirm(\'Willst Du den Händler wirklich löschen?\');'))
				. '</td>		
			</tr>';
}	

echo '
		</tbody>
	</table>
</div>';


include APPPATH . 'views/footer.php';