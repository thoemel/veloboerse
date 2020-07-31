<?php
echo '

<div class="row">
<div class="alert alert-info col-lg-6">
	<p>
        Willst du noch etwas ändern?
        Dann musst du über diesen ' . anchor('verkaeufer/veloformular/'.$myVelo->id, 'Link') . ' gehen.
    </p>
</div>
</div>';

echo form_open('annahme/registriere');
echo form_hidden('id', $myVelo->id);
echo '
<div class="row">
	<div class="form-group">
		<label class="col-sm-2 control-label">Ausweis OK?</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('ausweisOK', 'yes', false) . ' ja</label>
			<label class="radio-inline">' . form_radio('ausweisOK', 'no', false) . ' nein</label>
		</div>
	</div>
</div>

<div class="row">
	<div class="form-group">
		<label class="col-sm-2 control-label">Rahmennummer OK?</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('rahmennummerOK', 'yes', false) . ' ja</label>
			<label class="radio-inline">' . form_radio('rahmennummerOK', 'no', false) . ' nein</label>
		</div>
	</div>
</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">Weiter zur Druckansicht</button>
		</div>
	</div>
</form>
</div>
';

echo form_close();