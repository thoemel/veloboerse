<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Händervelo erfassen</h1>
';

echo form_open_multipart('annahme/speichern', 
		array('id' => 'erfassungsformular', 'role' => 'form', 'class' => 'form-horizontal'));
echo form_hidden('id', $myVelo->id);
echo form_hidden('kein_ausweis', $myVelo->kein_ausweis);

echo '<h3>Quittung Nummer: ' . $myVelo->id . '</h3>
		
	<div class="form-group">
		<label for="haendler_id" class="col-lg-2 control-label">Händler</label>
		<div class="col-lg-10">
			' . $haendlerDropdown . '
		</div>
	</div>
					
	<div class="form-group">
		<label for="preis_input" class="col-lg-2 control-label">Preis</label>
		<div class="col-lg-10">
			' . form_input(array('id' => 'preis_input', 'name' => 'preis', 'value' => '', 'class' => 'form-control')) . '
		</div>
	</div>
					
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Erfassen</button>
	</div>';


echo form_close();

echo '
</div>';

include APPPATH . 'views/footer.php';