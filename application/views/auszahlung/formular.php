<?php 
include APPPATH . 'views/header.php';

if (isset($success) && true === $success) {
	echo '
	<div class="alert-success">
		Auszahlung wurde registriert :-)
	</div>
	';
}

echo '
<div>
	<h1>Auszahlung Private</h1>

' . form_open('auszahlung/kontrollblick', array('id' => 'auszahlungformular')) . '

' . form_label('Quittungs-Nummer', 'auszahlung_quittung_input') . '
' . form_input('quittungNr', '', 'id="auszahlung_quittung_input" class="focusPlease"') . '

' . form_submit('auszahlen', 'Kontrollieren', 'class="btn"') . '
		
' . form_close() . '
		
</div>';

include APPPATH . 'views/footer.php';