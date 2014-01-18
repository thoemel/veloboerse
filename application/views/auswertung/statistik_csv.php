<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=veloboerse_statistik_" . date('Ymd') . ".xls");

echo 'Statistik ' . date('d.m.Y') . "\n";
echo 'Verkaufte Velos' . "\n";
echo "Händler;Private;Total\n";
echo $verkaufteVelos['haendler']['anzahl'] . " (" . round($verkaufteVelos['haendler']['anzahlAnteil'] * 100) . "%);"
		. $verkaufteVelos['private']['anzahl'] . " (" . round($verkaufteVelos['private']['anzahlAnteil'] * 100) . "%);"
		. $verkaufteVelos['total']['anzahl'] . "\n";

echo "\n";
echo "Velostatistik\n";
echo ";Velos auf Platz;Anzahl verkauft;Durchschnittlicher Preis;Eingenommene Provision;Summe keine Provision;Summe Helfer kauft;Anzahl bar;Anzahl Karte;Anteil am Umsatz;Verkauft / angeboten\n";

foreach ($veloStatistik as $type => $values) {
	echo ucfirst($type) . ";" 
	. $values['anzahl'] . ";" 
	. $values['sumVerkauft'] . ";" 
	. round($values['schnittPreis']) . ";" 
	. round($values['sumProvision'], 2) . ";" 
	. $values['sumKeineProvision'] . ";" 
	. $values['sumHelferKauft'] . ";" 
	. $values['zahlungsart']['bar'] . ";" 
	. $values['zahlungsart']['karte'] . ";" 
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
	. ";" . $arrHaendler['kommentar'] 
	. "\n";
}
