<?php
include APPPATH . 'views/header.php';

echo '
' . heading('Statistik ' . date('d.m.Y'), 1) . '
<div class="row">
	' . anchor('auswertung/statistik/csv', 'Download diese Auswertung als .CSV', array('class'=>'btn btn-info')) . '
	' . anchor('auswertung/csv/haendler', 'Download Händler Tabelle .CSV', array('class'=>'btn btn-info')) . '
	' . anchor('auswertung/csv/velos', 'Download Velos Tabelle .CSV', array('class'=>'btn btn-info')) . '
</div>
			
' . heading('Verkaufte Velos', 2) . '
<div class="row">
	<div class="col-sm-2">Händler</div>
	<div class="col-sm-2">Private</div>
	<div class="col-sm-2">Total</div>
</div>
<div class="row">
	<div class="col-sm-2">
		' . $verkaufteVelos['haendler']['anzahl'] . ' 
		(' . round($verkaufteVelos['haendler']['anzahlAnteil'] * 100) . '%)
	</div>
	<div class="col-sm-2">
		' . $verkaufteVelos['private']['anzahl'] . ' 
		(' . round($verkaufteVelos['private']['anzahlAnteil'] * 100) . '%)
	</div>
	<div class="col-sm-2">
		' . $verkaufteVelos['total']['anzahl'] . '
	</div>
</div>
			';

echo '
' . heading('Velostatistik', 2) . '
<table class="table table-striped table-bordered table-condensed">
<thead>
	<tr>
		<th scope="col"></th>
		<th scope="col">Velos auf Platz</th>
		<th scope="col">Anzahl verkauft</th>
		<th scope="col">Durchschnittlicher Preis</th>
		<th scope="col">Eingenommene Provision</th>
		<th scope="col">Summe keine Provision</th>
		<th scope="col">Summe Helfer kauft</th>
		<th scope="col">Anzahl bar</th>
		<th scope="col">Anzahl Debit</th>
		<th scope="col">Anzahl Kredit</th>
		<th scope="col">Anteil am Umsatz</th>
		<th scope="col">Verkauft / angeboten</th>
	</tr>
</thead>
</tbody>';
foreach ($veloStatistik as $type => $values) {
	echo '
	<tr>
		<th scope="row">' . ucfirst($type) . '</th>
		<td>' . $values['velosAufPlatz'] . '</td>
		<td>' . $values['sumVerkauft'] . '</td>
		<td>' . round($values['schnittPreis']) . '</td>
		<td>' . round($values['sumProvision'], 2) . '</td>
		<td>' . $values['sumKeineProvision'] . '</td>
		<td>' . $values['sumHelferKauft'] . '</td>
		<td>' . $values['zahlungsart']['bar'] . '</td>
		<td>' . $values['zahlungsart']['debit'] . '</td>
		<td>' . $values['zahlungsart']['kredit'] . '</td>
		<td>' . round($values['anteilVerkauftGruppeVonVerkauftTotal'], 2) . '</td>
		<td>' . round($values['anteilVerkauftGruppeVonAnzahlGruppe'], 2) . '</td>
	</tr>';
}
echo '
	</tr>
</tbody>
</table>';

echo '
' . heading('Händlerstatistik', 2) . '
<table class="table table-striped table-bordered table-condensed">
<thead>
	<tr>
		<th scope="col">Nr.</th>
		<th scope="col">Firma</th>
		<th scope="col">Person</th>
		<th scope="col">Velos auf Platz</th>
		<th scope="col">Velos verkauft</th>
		<th scope="col">% Velos verkauft</th>
		<th scope="col">Velos zurück</th>
		<th scope="col">Summe VP</th>
		<th scope="col">Provision %</th>
		<th scope="col">Provision</th>
		<th scope="col">Einstellgebühr</th>
		<th scope="col">Standgebühr</th>
		<th scope="col">Effektive Auszahlung</th>
		<th scope="col">Bemerkungen</th>
	</tr>
</thead>
</tbody>';
foreach ($haendlerStatistik as $arrHaendler) {
	echo '
	<tr>
		<td>' . $arrHaendler['id'] . '</td>
		<td>' . $arrHaendler['firma'] . '</td>
		<td>' . $arrHaendler['person'] . '</td>
		<td>' . $arrHaendler['velosAufPlatz'] . '</td>
		<td>' . $arrHaendler['velosVerkauft'] . '</td>
		<td>' . round((100 * $arrHaendler['anteilVerkauft']), 2) . '</td>
		<td>' . $arrHaendler['velosZurück'] . '</td>
		<td>' . $arrHaendler['summePreisVerkaufte'] . '</td>
		<td>' . $arrHaendler['provision'] . '</td>
		<td>' . round($arrHaendler['summeProvision'], 2) . '</td>
		<td>' . $arrHaendler['einstellgebuehr'] . '</td>
		<td>' . $arrHaendler['standgebuehr'] . '</td>
		<td>' . $arrHaendler['betragAusbezahlt'] . '</td>
		<td>' . $arrHaendler['kommentar'] . '</td>
	</tr>';
}
echo '
</tbody>
</table>';

include APPPATH . 'views/footer.php';
