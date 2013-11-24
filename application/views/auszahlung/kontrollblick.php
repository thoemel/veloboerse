<?php 
include APPPATH . 'views/header.php';

echo '

' . $divAround . '
	<h1>Auszahlung bestätigen</h1>

	<dl>
		<dt>Quittung Nr.</dt>
		<dd>' . $velo->id . '</dd>
		<dt>Preis</dt>
		<dd id="preis">' . $velo->preis . '</dd>
	</dl>';

if ('yes' == $velo->kein_ausweis) {
	echo '<p class="clearfix alert-error">Verkäufer muss noch Ausweis zeigen!</p>';
}
echo form_open('auszahlung/speichern_private', 
		array('class' => 'form-horizontal', 'role' => 'form'));
echo form_hidden('id', $velo->id);

echo '
	<div class="checkbox">
		<label>
			' . form_checkbox('no_provision', 'yes', false, 'id="no_provision"') . '
			Keine Provision (von HelferIn verkauft)
		</label>
	</div>';

if ('no' == $velo->ausbezahlt && 'yes' == $velo->verkauft) {
	echo '
	<p class="verybig">Auszahlen: Fr. <span id="auszahlung_betrag">' . $auszahlung_betrag . '</span></p>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default focusPlease">Bestätigen</button>
		</div>
	</div>';
}
if ('no' == $velo->verkauft) {
	echo '<p class="clearfix alert alert-error">Keine Auszahlung, weil das Velo nicht verkauft wurde.</p>';
}
if ('yes' == $velo->ausbezahlt) {
	echo '<p class="clearfix alert alert-error">Keine Auszahlung, weil die Auszahlung schon erfolgte.</p>';
}

echo form_close();

echo '
</div>';

include APPPATH . 'views/footer.php';
