<?php 
include APPPATH . 'views/header.php';

echo '

' . $divAround . '
	<h1>Auszahlung bestätigen</h1>

	<div class="row">
		<div class="col-sm-2">
			Quittung Nr. 
		</div>
		<div class="badge col-sm-1">
			' . $velo->id . '
		</div>
	</div>
	<div class="row">
		<div class="col-sm-2">
			Preis: 
		</div>
		<div class="col-sm-2">
			<span id="preis">' . $velo->preis . '</span> 
			Fr.
		</div>
	</div>';

if ('yes' == $velo->kein_ausweis) {
	echo '
	<div class="row">
		<p class="alert alert-warning">Verkäufer muss noch Ausweis zeigen!</p>
	</div>';
}
if (!empty($velo->bemerkungen)) {
	echo '
	<div class="row">
		<div class="col-sm-2">Bemerkungen: </div>
		<div class="col-sm-10 alert alert-warning">' . $velo->bemerkungen . '</div>
	</div>';
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

if ('no' == $velo->ausbezahlt && 'yes' == $velo->verkauft && 0 == $velo->gestohlen) {
	echo '
	<p class="verybig">Auszahlen: Fr. <span id="auszahlung_betrag">' . $auszahlung_betrag . '</span></p>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Bestätigen</button>
		</div>
	</div>';
}
if ('no' == $velo->verkauft) {
	echo '<p class="clearfix alert alert-error">Keine Auszahlung, weil das Velo nicht verkauft wurde.</p>';
}
if ('yes' == $velo->ausbezahlt) {
	echo '<p class="clearfix alert alert-error">Keine Auszahlung, weil die Auszahlung schon erfolgte.</p>';
}
if (1 == $velo->gestohlen) {
	echo '<p class="clearfix alert alert-error">Keine Auszahlung, weil das Velo als gestohlen gemeldet wurde.</p>';
}

echo form_close();

echo '
</div>';

include APPPATH . 'views/footer.php';
