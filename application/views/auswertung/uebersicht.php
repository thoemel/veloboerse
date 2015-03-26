<?php
include APPPATH . 'views/header.php';

echo '
	' . heading('Auswertung', 1) . '
<div class="row">
	<p>
		Hier ein paar nette Links für die Auswertung der Börse:
	</p>
	<ul>
		<li>' . anchor('auswertung/cashMgmt', 'Cash Management') . '</li>
		<li>' . anchor('auswertung/statistik', 'Statistik') . '</li>
		<li>' . anchor('auswertung/afrika', 'Velos für Afrika') . '</li>
	</ul>
</div>';
echo '
	' . heading('Nächste Börse einrichten', 1) . '
<div class="row">
	<p>
		' . anchor('vorbereitung/index', 'Vorgehen') . '
	</p>
</div>';


include APPPATH . 'views/footer.php';
