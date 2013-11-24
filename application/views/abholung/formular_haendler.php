<?php 
include APPPATH . 'views/header.php';

echo '
<div>
	<h1>Händlervelo auschecken</h1>

	<p>Im Formular unten die Händlernummer eingeben und Enter drücken.<br>
		Oder Barcode einlesen und fertig.</p>
		
	' . form_open('abholung/abholen') . '
	' . form_input('id', '', 'class="focusPlease"') . '
	<button type="submit" class="btn">bestätigen</button>
	' . form_close() . '
</div>';
		

include APPPATH . 'views/footer.php';