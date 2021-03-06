<?php
if (!isset($noHeaders) || false === $noHeaders) {
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=veloboerse_statistik_" . date('Ymd') . ".csv");
}

$ichwill = 'debuggen';
echo 'Statistik ' . date('d.m.Y') . "\n";
echo 'Verkaufte Velos' . "\n";
echo "Händler;Private;Total\n";
echo $verkaufteVelos['haendler']['anzahl'] . " (" . round($verkaufteVelos['haendler']['anzahlAnteil'] * 100) . "%);"
		. $verkaufteVelos['private']['anzahl'] . " (" . round($verkaufteVelos['private']['anzahlAnteil'] * 100) . "%);"
		. $verkaufteVelos['total']['anzahl'] . "\n";

echo "\n";
echo "Velostatistik\n";
echo ";Velos auf Platz;Anzahl verkauft;Durchschnittlicher Preis;Eingenommene Provision;Summe keine Provision;Summe Helfer kauft;Anzahl bar;Anzahl Debit;Einstellgebühr;Anteil am Umsatz;Verkauft / angeboten\n";

foreach ($veloStatistik as $type => $values) {
    if ('haendler' == $type) {
        $einstellgebuehr = $totalEinstellgebuehrHaendler;
    } else {
        $einstellgebuehr = '-';
    }
	echo ucfirst($type) . ";"
	. $values['velosAufPlatz'] . ";"
	. $values['sumVerkauft'] . ";"
	. round($values['schnittPreis']) . ";"
	. round($values['sumProvision'], 2) . ";"
	. $values['sumKeineProvision'] . ";"
	. $values['sumHelferKauft'] . ";"
	. $values['zahlungsart']['bar'] . ";"
	. $values['zahlungsart']['debit'] . ";"
	    . $einstellgebuehr . ";"
	. round($values['anteilVerkauftGruppeVonVerkauftTotal'], 2) . ";"
	. round($values['anteilVerkauftGruppeVonAnzahlGruppe'], 2) . "\n";
}

echo "\n";
echo "Händlerstatistik\n";
echo "Nr.;Firma;Person;Velos auf Platz;Velos verkauft;% Velos verkauft;Velos zurück;Summe VP;Provision %;Provision;Einstellgebühr;Standgebühr;Effektive Auszahlung;Bemerkungen\n";

foreach ($haendlerStatistik as $arrHaendler) {
	echo $arrHaendler['id'] . ";"
	. $arrHaendler['firma']
	. ";" . $arrHaendler['person']
	. ";" . $arrHaendler['velosAufPlatz']
	. ";" . $arrHaendler['velosVerkauft']
	. ";" . round((100 * $arrHaendler['anteilVerkauft']), 2)
	. ";" . $arrHaendler['velosZurück']
	. ";" . $arrHaendler['summePreisVerkaufte']
	. ";" . $arrHaendler['provision']
	. ";" . round($arrHaendler['summeProvision'], 2)
	. ";" . $arrHaendler['einstellgebuehr']
	. ";" . $arrHaendler['standgebuehr']
	. ";" . $arrHaendler['betragAusbezahlt']
	. ";\"" . $arrHaendler['kommentar'] . "\""
	. "\n";
}

echo "\n";
echo "Modalsplit nach Provisionsstufe\n";
echo "Provisions-Obergrenze;Händler;;Private\n";
echo ";verkauft;nicht verkauft;verkauft;nicht verkauft\n";
foreach ($modalSplit[0] as $provision => $verkauftUndNicht) {
	echo $provision . ";"
		. $modalSplit[1][$provision]['verkauft'] . ";"
		. $modalSplit[1][$provision]['nicht_verkauft'] . ";"
		. $verkauftUndNicht['verkauft'] . ";"
		. $verkauftUndNicht['nicht_verkauft'] . ";\n";
}

