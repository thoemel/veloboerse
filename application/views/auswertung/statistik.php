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
		<th scope="col">Einstellgebühr</th>
		<th scope="col">Anteil am Umsatz</th>
		<th scope="col">Verkauft / angeboten</th>
	</tr>
</thead>
</tbody>';
foreach ($veloStatistik as $type => $values) {
    if ('haendler' == $type) {
        $einstellgebuehr = $totalEinstellgebuehrHaendler;
    } else {
        $einstellgebuehr = '-';
    }
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
		<td>' . $einstellgebuehr . '</td>
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


echo '
' . heading('Nach Provisionsstufe', 2) . '
<table class="table table-striped table-bordered table-condensed">
<thead>
	<tr>
		<th scope="col" rowspan="2">Provisions-Obergrenze</th>
		<th scope="col" colspan="3">Händler</th>
		<th scope="col" colspan="3">Private</th>
	</tr>
	<tr>
		<th scope="col">Anzahl verkauft</th>
		<th scope="col">Anzahl nicht verkauft</th>
		<th scope="col">% verkauft</th>
		<th scope="col">Anzahl verkauft</th>
		<th scope="col">Anzahl nicht verkauft</th>
		<th scope="col">% verkauft</th>
	</tr>
</thead>
</tbody>';
foreach ($modalSplit[0] as $provision => $verkauftUndNicht) {
    if ($modalSplit[1][$provision]['verkauft'] + $modalSplit[1][$provision]['nicht_verkauft'] != 0) {
        $anteilHaendler = round(($modalSplit[1][$provision]['verkauft'] / ($modalSplit[1][$provision]['nicht_verkauft'] + $modalSplit[1][$provision]['verkauft'])) * 100, 2);
    } else {
        $anteilHaendler = '-';
    }
    if ($verkauftUndNicht['verkauft'] + $verkauftUndNicht['nicht_verkauft'] != 0) {
        $anteilPrivate = round(($verkauftUndNicht['verkauft'] / ($verkauftUndNicht['nicht_verkauft'] + $verkauftUndNicht['verkauft'])) * 100, 2);
    } else {
        $anteilPrivate = '-';
    }
	echo '
	<tr>
		<td>' . $provision . '</td>
		<td>' . $modalSplit[1][$provision]['verkauft'] . '</td>
		<td>' . $modalSplit[1][$provision]['nicht_verkauft'] . '</td>
		<td>' . $anteilHaendler . '</td>
		<td>' . $verkauftUndNicht['verkauft'] . '</td>
		<td>' . $verkauftUndNicht['nicht_verkauft'] . '</td>
		<td>' . $anteilPrivate . '</td>
	</tr>';
}
echo '
</tbody>
</table>';

include APPPATH . 'views/footer.php';
