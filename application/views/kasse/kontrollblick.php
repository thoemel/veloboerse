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
		<div class="col-sm-10 alert alert-info">' . $velo->bemerkungen . '</div>
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
	<div class="form-group">
		<label class="checkbox-inline">
			' . form_checkbox('helfer_kauft', 'yes', false, 'id="helfer_kauft"') . '
			Von HelferIn gekauft
		</label>
	</div>
	<div class="form-group">
		<label class="radio-inline">
			' . form_radio('zahlungsart', 'bar', false) . '
			Bar
		</label>
		<label class="radio-inline">
			' . form_radio('zahlungsart', 'debit', false) . '
			Post- oder EC-Karte
		</label>
		<label class="radio-inline">
			' . form_radio('zahlungsart', 'kredit', false) . '
			Kreditkarte
		</label>
	</div>
	<div id="velo_bemerkungen" class="hidden form-group">
		<label class="col-sm-2 control-label">Bitte Namen ins Bemerkungsfeld!</label>
		<div class="col-sm-10">
			<textarea rows="3" class="form-control" name="bemerkungen">' . $velo->bemerkungen . '</textarea>
		</div>
	</div>';
if (0 == $velo->gestohlen) {
	echo '
		<div class="form-group">
				<button type="submit" id="bestaetigen_kasse" class="btn btn-default">
					Bestätigen
		</button>
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
	<p><br><br><br><br><br><br>
		' . anchor('kasse/', 'Verkauf abbrechen') . '</p>
</div>';

echo '
<div class="verklickt_form">
	<form action="'.site_url('kasse/verklickt').'" method="post" role="form">';
echo form_hidden('id', $velo->id);
echo form_submit('verklickt_submit', 'verklickt_submit', 'class="focusPlease"');
echo form_close();
echo '
</div>';

include APPPATH . 'views/footer.php';
