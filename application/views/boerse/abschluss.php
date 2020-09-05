<?php
echo '
<div class="row">
	<p>
		Die letzte Börse ist noch nicht abgeschlossen.
		Ein Klick auf unten stehenden Button macht ein Backup von der letzten
		Börse und erlaubt dir, eine neue zu erfassen.
        Ausserdem kannst du den EZAG für die Auszahlung der Privaten hier herunterladen.
	</p>
	<p>
		' . anchor('admin/boerseAbschliessen/'.$letzteBoerse->id,
				'Börse von ' . $letzteBoerse->datum . ' abschliessen',
				array('class'=> 'btn btn-primary', 'role' => 'button')) . '
	</p>
</div>';