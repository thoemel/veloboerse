<?php
include APPPATH . 'views/header.php';

echo '
	' . heading('Administration', 1) . '
	' . heading('Aktuelle Börse', 2) . '
<div class="row">
	<p>
		Hier ein paar nette Links für die Auswertung der Börse:
	</p>
	<ul>
		<li>' . anchor('auswertung/cashMgmt', 'Cash Management') . '</li>
		<li>' . anchor('auswertung/statistik', 'Statistik') . '</li>
		<li>' . anchor('auswertung/afrika', 'Velos für Afrika') . '</li>
		<li>' . anchor('admin/ezag', 'EZAG') . '</li>
	</ul>
</div>
	' . heading('Nächste Börse einrichten', 2) . '
<div class="row">
	<p>
		' . $boerseContent . '
	</p>
</div>

	' . heading('Vergangene Börsen', 2) . '
<div class="row">
	<p>
		' . anchor('admin/vergangeneBoersen', 'Zu den Downloads') . '
	</p>
</div>';

include APPPATH . 'views/footer.php';