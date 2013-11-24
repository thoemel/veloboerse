<?php
include APPPATH . 'views/header.php';

// TODO striped klasse im bootstrap?
echo '
<div class="haendleradmin">
	<h1>Händleradmin: Velos des Händlers ' . $haendler->firma . '</h1>
	
	<p>'.anchor('haendleradmin', 'zur Händlerauswahl').'</p>
	
	<table class="striped">
		<tr>
			<th>Quittung Nr.</th>
			<th>Preis</th>
			<th>Verkauft</th>
		</tr>';

foreach ($veloQuery->result() as $velo) {
	echo '
		<tr>
			<td>' . $velo->id . '</td>
			<td>' . $velo->preis . '</td>
			<td>' . lang($velo->verkauft) . '</td>
		</tr>';
}
	
// TODO Fette Klasse im bootstrap?
echo '
		<tr>
			<td class="bold">Total</td>
			<td>' . $haendler->sumAlleVerkauften() . '</td>
			<td></td>
		</tr>
	</table>

	<p>'.anchor('haendleradmin', 'zur Händlerauswahl').'</p>
</div>';


include APPPATH . 'views/footer.php';