<?php
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Arbeitsbereich wählen</h1>

	<div class="bottom20 privatannahme">
		'.anchor('login/dispatch/privatannahme', 'Annahme Private').'
	</div>
	<div class="bottom20 privatauszahlung">
		'.anchor('login/dispatch/privatauszahlung', 'Auszahlung Private').'
	</div>
	<div class="bottom20 kasse">
		'.anchor('login/dispatch/kasse', 'Kasse').'
	</div>
	<div class="bottom20 abholung">
		'.anchor('login/dispatch/abholung', 'Abholung Private').'
	</div>
	<div class="bottom20 haendlerabholung">
		'.anchor('login/dispatch/haendlerabholung', 'Abholung Händler').'
	</div>
	<div class="bottom20 haendleradmin">
		'.anchor('login/dispatch/haendleradmin', 'Händleradmin').'
	</div>
	<div class="bottom20 veloformular">
		'.anchor('login/dispatch/veloformular', 'Formular Velo').'
	</div>
	<div class="bottom20 polizei">
		'.anchor('login/dispatch/polizei', 'Polizei').'
	</div>';

// Nur Admins
if ($showAuswertung) {
	echo '
	<div class="bottom20">
		'.anchor('login/dispatch/auswertung', 'Auswertung').'
	</div>
	<div class="bottom20">
		'.anchor('login/dispatch/admin', 'Börsen-Admin').'
	</div>
	<div class="bottom20">
		'.anchor('login/dispatch/benutzeradmin', 'Benutzeradmin').'
	</div>';
}

echo '
</div>';

include APPPATH . 'views/footer.php';