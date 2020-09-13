<?php
include APPPATH . 'views/header.php';

echo '
<div>
	<ol class="breadcrumb hidden-print">
		<li>' . anchor('login/showChoices', 'Ressorts') . '</li>
		<li>' . anchor('haendleradmin', 'Händleradmin') . '</li>
		<li class="active">Abrechnung ' . $haendler->firma . '</li>
	</ol>

	<h1>Abrechnung</h1>
		<h2>Händler Nr. ' . $haendler->id . '</h2>
		<p>' . $haendler->firma . '<br>
			' . $haendler->person . '<br>
			' . nl2br($haendler->adresse) . '<br>
		</p>


	<table class="table table-striped table-bordered table-condensed">
		<thead>
		<tr>
			<th scope="col" rowspan="2">Quittungs-Nummer</th>
			<th scope="col" rowspan="2">Preis in CHF</th>
			<th scope="col" colspan="2">verkauft</th>
			<th scope="col" rowspan="2">abgeholt</th>
		</tr>
		<tr>
			<th>Ja</th>
			<th>Nein</th>
		</tr>
		</thead>

		<tfoot>
		<tr>
			<th scope="row">Total</th>
			<td></td>
			<td>' . $countVerkauft . '</td>
			<td>' . $countNichtVerkauft . '</td>
			<td>&nbsp;</td>
		</tr>
		</tfoot>';


foreach ($arrVelos as $velo) {
	echo '
		<tr>
			<td>' . $velo['id'] . ' ' . $velo['bem'] . '</td>
			<td>' . number_format($velo['preis'], 2) . '</td>
			<td>' . $velo['verkauft'] . '</td>
			<td>' . $velo['unverkauft'] . '</td>
			<td>' . $velo['abgeholt'] . '</td>
		</tr>';
}

echo '
	</table>

	<dl>
		<dt>Total Preis verkaufte Velos</dt>
		<dd>Fr. ' . $preisVerkaufte . '</dd>
		<dt>Provision</dt>
		<dd>Fr. ' . $provisionAbsolut . '</dd>
		<dt>Einstellgebühr</dt>
		<dd>Fr. ' . $einstellgebuehr . '</dd>';
if ($haendler->busse) {
	echo '
		<dt>Busse</dt>
		<dd>Fr. ' . number_format($haendler->busse, 2) . '</dd>';
}
echo '
		<dt>Standgebühr</dt>
		<dd>Fr. ' . number_format($haendler->standgebuehr, 2) . '</dd>
	</dl>';

if (in_array($haendler->getStatus(), array('abgeholt', 'ausbezahlt'))) {
	echo	'
	<h2>Überweisung Betrag: Fr. ' . $auszahlungBetrag . '</h2>
		' . form_open('haendleradmin/abschluss/'.$haendler->id) . '
		<p class="hidden-print">
		<button type="button" onClick="window.print()">drucken</button>
		<button type="submit">abschliessen</button>
	</p>
		' . form_close() . '

	<p class="hidden-print">
			'.anchor('auszahlung/einstieg_haendler', 'zur Händlerauswahl').'
	</p>';
} else {
	echo '
	<h2>Noch nicht alle Velos abgeholt. Abschluss noch nicht möglich.</h2>';
}


echo '
		<p>
			Ich akzeptiere diese Abrechnung und bin damit einverstanden,
			dass mir der Betrag von ' . $auszahlungBetrag . '
			auf folgendes Konto überwiesen wird:<br>
			' . $haendler->bankverbindung . '<br>
			IBAN: ' . $iban . '
		</p>
		<p>
			' . config_item('ort') . ', den ' . date('d.m.Y') . '<br><br><br>
			<hr><br>
			' . $haendler->person . '
		</p>
	</div>';


include APPPATH . 'views/footer.php';