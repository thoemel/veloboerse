<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Velo für Privat erfassen</h1>
';

echo form_open_multipart('annahme/speichern_private', 
		array('id' => 'erfassungsformular', 
				'role' => 'form', 
				'class' => 'form-horizontal'));
echo form_hidden('altesBild', $myVelo->img);
echo form_hidden('id', $myVelo->id);

echo '
	<h3>Quittung Nummer: ' . $myVelo->id . '</h3>

	<div class="form-group">
		<label for="preis_input" class="col-lg-2 control-label">Preis</label>
		<div class="col-lg-10">
			' . form_input(array('id' => 'preis_input', 'name' => 'preis', 'value' => '', 'class' => 'focusPlease form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<div class="checkbox">
				<label>
					' . form_checkbox('kein_ausweis', '1', $myVelo->kein_ausweis == 'yes') . '
					Kein Ausweis
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<div class="checkbox">
				<label>
					' . form_checkbox('keine_provision', 'yes', false) . '
					Keine Provision (von Helfer verkauft)
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<div class="checkbox">
				<label>
					' . form_checkbox('velafrika', '1', false) . '
					Bei Nichtverkauf an Velafrika gespendet
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Bemerkungen</label>
		<div class="col-sm-10">
			<textarea name="bemerkungen" class="form-control" rows="3">' 
			. $myVelo->bemerkungen 
			. '</textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Erfassen</button>
		</div>
	</div>

</form>
</div>';

include APPPATH . 'views/footer.php';