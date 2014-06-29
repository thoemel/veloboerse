<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Verkauf bestätigen</h1>

	<div class="row">
		<div class="col-sm-2">
			Quittung Nr. 
		</div>
		<div class="badge col-sm-2">
			' . $velo->id . '
		</div>
	</div>
	<div class="verybig row">
		<div class="col-sm-2">
			Preis: 
		</div>
		<div id="preis" class="col-sm-4">
			' . $velo->preis . ' 
			Fr.
		</div>
	</div>';

if (!empty($velo->bemerkungen)) {
	echo '
	<div class="row">
		<div class="col-sm-2">Bemerkungen: </div>
		<div class="col-sm-10 alert alert-warning">' . $velo->bemerkungen . '</div>
	</div>';
}

echo '<form action="'.site_url('kasse/verkaufe').'"
			method="post"
			role="form">';
// echo form_open('kasse/verkaufe', array('class'=>"form-horizontal"));
echo form_hidden('id', $velo->id);
echo form_hidden('provision', Velo::getProvision($velo->preis));
echo form_hidden('angeschriebener_preis', $velo->preis);

echo '
	<div class="checkbox">
		<label>
			' . form_checkbox('helfer_kauft', 'yes', false, 'id="helfer_kauft" class="form-control"') . '
			Von HelferIn gekauft
		</label>
	</div>
	<div class="form-group">
		<label class="radio-inline">
			' . form_radio('zahlungsart', 'bar', false) . '
			Bar
		</label>
		<label class="radio-inline">
			' . form_radio('zahlungsart', 'debit', true) . '
			Post- oder EC-Karte
		</label>
		<label class="radio-inline">
			' . form_radio('zahlungsart', 'kredit', false) . '
			Kreditkarte
		</label>
	</div>';
if (0 == $velo->gestohlen) {
	echo '
		<div class="form-group">
				<button type="submit" class="btn btn-default">Bestätigen</button>
		</div>';
}
if (1 == $velo->gestohlen) {
	echo '
		<div class="alert alert-error">
			Das Velo wurde als gestohlen gemeldet.
		</div>';
}

echo form_close();


echo '
</div>
			
<div>
	<p>' . anchor('velos/formular/' . $velo->id, 'Ausnahmen bearbeiten') . '</p>
	<p>' . anchor('login/dispatch/abholung', 'Zur Abholung (nicht-verkaufte Velos zurückgeben)') . '</p>
</div>';

include APPPATH . 'views/footer.php';
