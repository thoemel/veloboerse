<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Kein Treffer</h1>

	<p>
		Es wurde kein Velo mit der gesuchten Quittungs-Nummer registriert.
	</p>
	<p>
		Gib im Formularfeld Deine Quittungs-Nummer ein zum Sehen, ob es verkauft ist oder nicht.
	</p>
	' . form_open('velos/suche', array('role'=>"form")) . '
	<div class="row">
		<div class="form-group col-sm-2">
			' . form_input(array('name'=>'id','class'=>'form-control focusPlease','placeholder'=>"Quittungs-Nr.")) . '
		</div>
		<button type="submit" class="btn btn-success">' . $formSubmitText . '</button>
	</div>
	' . form_close() . '
</div>';

include APPPATH . 'views/footer.php';