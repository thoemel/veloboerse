<?php 
include APPPATH . 'views/header.php';

echo '

<div class="jumbotron">
	<h1>Velobörse Bern, willkommen</h1>

	<p>Gib im Formularfeld Deine Quittungs-Nummer ein zum Sehen, ob es verkauft ist oder nicht.</p>
	' . form_open('velos/suche', array('role'=>"form")) . '
	<div class="row">
		<div class="form-group col-sm-2">
			' . form_input(array('name'=>'id','class'=>'form-control focusPlease','placeholder'=>"Quittungs-Nr.")) . '
		</div>
		<button type="submit" class="btn btn-success">' . $formSubmitText . '</button>
	</div>
	' . form_close();

if (!empty($naechsteBoerse)) {
	echo '
	<p>
		<div class="verybig">Anzahl Velos auf Platz: ' . $anzahl . '</div>
	</p>';
}

echo '
</div>

';

include APPPATH . 'views/footer.php';