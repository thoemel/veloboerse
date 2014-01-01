<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Arbeitsbereich wählen</h1>
';

echo '<div class="bottom20 privatannahme">
		'.anchor('login/dispatch/privatannahme', 'Annahme Private').'
	</div>';
echo '<div class="bottom20 privatauszahlung">
		'.anchor('login/dispatch/privatauszahlung', 'Auszahlung Private').'
	</div>';
echo '<div class="bottom20 kasse">
		'.anchor('login/dispatch/kasse', 'Kasse').'
	</div>';
echo '<div class="bottom20 abholung">
		'.anchor('login/dispatch/abholung', 'Abholung Private').'
	</div>';
echo '<div class="bottom20 haendlerannahme">
		'.anchor('login/dispatch/haendlerannahme', 'Annahme Händler').'
	</div>';
echo '<div class="bottom20 haendlerabholung">
		'.anchor('login/dispatch/haendlerabholung', 'Abholung Händler').'
	</div>';
echo '<div class="bottom20 haendlerauszahlung">
		'.anchor('login/dispatch/haendlerauszahlung', 'Auszahlung Händler').'
	</div>';
echo '<div class="bottom20 haendleradmin">
		'.anchor('login/dispatch/haendleradmin', 'Händleradmin').'
	</div>';
echo '<div class="bottom20 veloformular">
		'.anchor('login/dispatch/veloformular', 'Formular Velo').'
	</div>';

echo '
</div>';

include APPPATH . 'views/footer.php';