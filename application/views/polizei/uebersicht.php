<?php
include APPPATH . 'views/header.php';

echo '
	' . heading('Polizei', 1) . '
<div class="row">
	<p>
		Hier ein paar nette Links für die Zusammenarbeit mit der Polizei:
	</p>
	<ul>
		<li>' . anchor('polizei/gestohlene', 'Gestohlene Velos') . '</li>
		<li>' . anchor('polizei/rahmennummern', 'Neu hinzugekommene Rahmennummern') . ' Vorsicht: Es werden die Velos exportiert, die seit dem letzten Export angenommen wurden.
            Vergiss nicht, die Datei direkt zu speichern und der Polizei zu geben. Wenn du zweimal hintereinander klickst, ist die Liste leer.</li>
        <li>' . anchor('polizei/rahmennummern/alle', 'Alle Rahmennummern') . '</li>
	</ul>
</div>';


include APPPATH . 'views/footer.php';
