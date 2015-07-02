<?php
include APPPATH . 'views/header.php';

echo '
	<div class="alert alert-info hidden-print">
		Bitte alle Angaben überprüfen und gegebenenfalls korrigieren.<br>
		Nicht vergessen, die gewünschte Anzahl Vélos im entsprechenden Feld einzutragen!<br>
		Bitte erst am Schluss speichern, da die Angaben danach nicht mehr anpassbar sind!
	</div>
	<form class="form-horizontal" role="form" action="' . site_url('haendlerformular/haendlerconfigSpeichern') . '" method="post">
	<input type="hidden" name="haendler_id" value="' . $haendler->id . '">
	<div class="form-group">
		<label for="typ_input" class="col-sm-2 control-label">Firma</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'typ_input', 'name' => 'input_Firma', 'value' => $haendler->firma, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="typ_input" class="col-sm-2 control-label">Person</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'typ_input', 'name' => 'input_Person', 'value' => $haendler->person, 'class' => 'form-control')) . '
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
		<label for="typ_input" class="col-sm-2 control-label">E-Mail</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'typ_input', 'name' => 'input_Email', 'value' => $haendler->email, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="typ_input" class="col-sm-2 control-label">Telefon</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'typ_input', 'name' => 'input_Telefon', 'value' => $haendler->telefon, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="typ_input" class="col-sm-2 control-label">Bankverbindung</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'typ_input', 'name' => 'input_Bankverb', 'value' => $haendler->bankverbindung, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="typ_input" class="col-sm-2 control-label">IBAN</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'typ_input', 'name' => 'input_Iban', 'value' => $haendler->iban, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="typ_input" class="col-sm-2 control-label">Anzahl Vélos</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'typ_input', 'name' => 'input_velos', 'value' => $haendler->anzahlVelos, 'class' => 'form-control')) . '
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
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">Speichern</button>
		</div>
	</div>
	</form>
	</div>';
			
include APPPATH . 'views/footer.php';