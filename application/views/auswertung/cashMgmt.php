<?php
include APPPATH . 'views/header.php';

echo heading('Cash Management', 1);

echo heading('Prognosen', 2) . '
		<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th scope="col">Was</th>
				<th scope="col">Wieviel</th>
				<th scope="col">Bemerkungen</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th scope="row">Einnahmen bar bisher</th>
				<td>' . $newStatistics['einnahmenBisher'] . '</td>
				<td>Preis aller Velos, die bisher verkauft und bar bezahlt wurden.</td>
			</tr>
			<tr>
				<th scope="row">Ausbezahlt bisher</th>
				<td>' . $newStatistics['ausbezahlt'] . '</td>
				<td></td>
			</tr>
			<tr>
				<th scope="row">Berechneter Kassenstand</th>
				<td>' . ($newStatistics['einnahmenBisher'] - $newStatistics['ausbezahlt']) . '</td>
				<td>Ganz einfach Einnahmen bar bisher minus ausbezahlter Betrag bisher.</td>
			</tr>
			<tr>
				<th scope="row">Maximale Auszahlung ab jetzt</th>
				<td>' . $newStatistics['maxAuszahlung'] . '</td>
				<td>Preis aller Privatvelos, die noch nicht ausbezahlt oder abgeholt wurden,<br>
					abzüglich der Provision.</td>
			</tr>
			<tr>
				<th scope="row">Einnahmen bar ab jetzt</th>
				<td>' . round($newStatistics['einnahmenPrognoseAbJetzt']) . '</td>
				<td>Preis aller Velos auf Platz, <br>
					verrechnet mit Anteil Verkaufte/Angebotene<br>
					und Anteil AnzahlBarzahlung/AnzahlVerkauft<br>
					(Datengrundlage siehe unten)</td>
			</tr>
		</tbody>
		</table>';

echo heading('Annahmen', 2) . '
		<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th scope="col">Was</th>
				<th scope="col">Wieviel</th>
				<th scope="col">Bemerkungen</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th scope="row">Statistischer Anteil Verkaufte/Angebotene Total</th>
				<td>' . $newStatistics['statAnteilVerkaufteTotal'] . '</td>
				<td>(Herbst 2014)</td>
			</tr>
			<tr>
				<th scope="row">Statistischer Anteil Barzahlung</th>
				<td>' . $newStatistics['statAnteilBarHeute'] . '</td>
				<td>Berechnet aus Daten von heute.<br>
					Zum Vergleich: Im Herbst 2014 war er 0.506, Frühling 2015 0.452.</td>
			</tr>
		</tbody>
		</table>';

include APPPATH . 'views/footer.php';