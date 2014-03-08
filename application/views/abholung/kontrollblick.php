<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Abholung bestätigen</h1>

	<dl>
		<dt>Quittung Nr.</dt>
		<dd>' . $velo->id . '</dd>
		<dt>Preis</dt>
		<dd>' . $velo->preis . '</dd>
	</dl>';


echo form_open('abholung/abholen');
echo form_hidden('id', $velo->id);

if ('yes' == $velo->verkauft) {
	echo '<p class="verybig alert-error">Das Velo wurde schon verkauft!</p>';
} elseif ('yes' == $velo->abgeholt) {
	echo '<p class="verybig alert-error">Das Velo wurde schon als abgeholt registriert!</p>';
} elseif (1 == $velo->gestohlen) {
	echo '<p class="verybig alert-error">Das Velo wurde als gestohlen gemeldet!</p>';
} else {
	echo '<p class="clearfix">' . form_submit('abholung_bestaetigen', 'Bestätigen', 'class="btn"') . '</p>';
}

echo form_close();


echo '
</div>
		
			
<div>
	<p>' . anchor('velos/formular/' . $velo->id, 'Ausnahmen bearbeiten') . '</p>
	<p>' . anchor('login/dispatch/kasse', 'Zur Kasse (Velos verkaufen)') . '</p>
</div>';

include APPPATH . 'views/footer.php';
