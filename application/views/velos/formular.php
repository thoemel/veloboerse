<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Velo bearbeiten</h1>
';

echo form_open_multipart('velos/erfasse', 
		array('id' => 'erfassungsformular', 'role' => 'form', 'class' => 'form-horizontal'));

echo heading('Quittung Nummer <span class="badge">' . $myVelo->id . '</span>', 3);
echo form_hidden('id',$myVelo->id);

echo '
	<div class="form-group">
		<label for="preis_input" class="col-sm-2 control-label">Preis</label>
		<div class="col-sm-2 col-md-2 col-lg-1">
			' . form_input(array('id' => 'preis_input', 'name' => 'preis', 'value' => $myVelo->preis, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Verkauft</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('verkauft', 'yes', ('yes' == $myVelo->verkauft)) . ' ja</label>
			<label class="radio-inline">' . form_radio('verkauft', 'no', ('no' == $myVelo->verkauft)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Abgeholt</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('abgeholt', 'yes', ('yes' == $myVelo->abgeholt)) . ' ja</label>
			<label class="radio-inline">' . form_radio('abgeholt', 'no', ('no' == $myVelo->abgeholt)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Zahlungsart</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('zahlungsart', 'bar', ('bar' == $myVelo->zahlungsart)) . ' bar</label>
			<label class="radio-inline">' . form_radio('zahlungsart', 'karte', ('karte' == $myVelo->zahlungsart)) . ' Karte</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Ausbezahlt</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('ausbezahlt', 'yes', ('yes' == $myVelo->ausbezahlt)) . ' ja</label>
			<label class="radio-inline">' . form_radio('ausbezahlt', 'no', ('no' == $myVelo->ausbezahlt)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Kein Ausweis</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('kein_ausweis', 'yes', ('yes' == $myVelo->kein_ausweis)) . ' ja</label>
			<label class="radio-inline">' . form_radio('kein_ausweis', 'no', ('no' == $myVelo->kein_ausweis)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Keine Provision (von Helfer verkauft)</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('keine_provision', 'yes', ('yes' == $myVelo->keine_provision)) . ' ja</label>
			<label class="radio-inline">' . form_radio('keine_provision', 'no', ('no' == $myVelo->keine_provision)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Von Helfer gekauft</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('helfer_kauft', 'yes', ('yes' == $myVelo->helfer_kauft)) . ' ja</label>
			<label class="radio-inline">' . form_radio('helfer_kauft', 'no', ('no' == $myVelo->helfer_kauft)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label for="haendler_id" class="col-sm-2 control-label">Händler</label>
		<div class="col-sm-6 col-md-4 col-lg-4">
			' . $haendlerDropdown . '
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