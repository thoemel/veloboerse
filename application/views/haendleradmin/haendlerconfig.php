<?php
include APPPATH . 'views/header.php';

/* echo form_open_multipart('',
		array('id' => 'erfassungsformular', 'role' => 'form', 'class' => 'form-horizontal', 'action' => "' . site_url('haendleradmin/quittungenSpeichern') . '"));
*/



echo '
<div class="haendleradmin">
	<ol class="breadcrumb hidden-print">
		<li>' . anchor('login/showChoices', 'Ressorts') . '</li>
		<li>' . anchor('haendleradmin', 'Händleradmin') . '</li>
		<li class="active">Händler Daten anpassen</li>
	</ol>

	<h1>Händler Daten anpassen</h1>
	<h2>Händler: ' . $anzeigename . '</h2>
</div>';

echo '
	<form class="form-horizontal" role="form" action="' . site_url('haendleradmin/haendlerconfigSpeichern') . '" method="post">	
	<input type="hidden" name="haendler_id" value="' . $haendler->id . '">
	<div class="form-group">
		<label for="firma_input" class="col-sm-2 control-label">Firma</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'firma_input', 'name' => 'input_Firma', 'value' => $haendler->firma, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="person_input" class="col-sm-2 control-label">Person</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'person_input', 'name' => 'input_Person', 'value' => $haendler->person, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Adresse</label>
		<div class="col-sm-10">
			<textarea name="input_Adresse" class="form-control" rows="3">' 
			. $haendler->adresse
			. '</textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="email_input" class="col-sm-2 control-label">E-Mail</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'email_input', 'name' => 'input_Email', 'value' => $haendler->email, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="telefon_input" class="col-sm-2 control-label">Telefon</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'telefon_input', 'name' => 'input_Telefon', 'value' => $haendler->telefon, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="bankverb_input" class="col-sm-2 control-label">Bankverbindung</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'bankverb_input', 'name' => 'input_Bankverb', 'value' => $haendler->bankverbindung, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="iban_input" class="col-sm-2 control-label">IBAN</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'iban_input', 'name' => 'input_Iban', 'value' => $haendler->iban, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="busse_input" class="col-sm-2 control-label">Busse</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'busse_input', 'name' => 'input_busse', 'value' => $haendler->busse, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="velos_input" class="col-sm-2 control-label">Anzahl Vélos</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'velos_input', 'name' => 'input_velos', 'value' => $haendler->anzahlVelos, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="standgebuehr_input" class="col-sm-2 control-label">Standgebühr</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'standgebuehr_input', 'name' => 'input_standgebuehr', 'value' => $haendler->standgebuehr, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Kommentar</label>
		<div class="col-sm-10">
			<textarea name="input_Kommentar" class="form-control" rows="3">' 
			. $haendler->kommentar
			. '</textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="uptodate_input" class="col-sm-2 control-label">Aktualisiert?</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'uptodate_input', 'name' => 'input_uptodate', 'value' => $haendler->uptodate, 'class' => 'form-control')) . '
		</div>
		<span id="uptodateExpl" class="help-block">
			Falls ein Händler zu früh gespeichert hat und noch Angaben ändern will, hier wieder von 1 auf 0 setzen.
		</span>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">Speichern</button>
		</div>
	</div>
</form>
</div>';

include APPPATH . 'views/footer.php';