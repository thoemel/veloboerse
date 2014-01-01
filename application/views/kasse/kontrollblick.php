<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Verkauf bestätigen</h1>

	<dl>
		<dt>Quittung Nr.</dt>
		<dd>' . $velo->id . '</dd>
		<dt>Preis</dt>
		<dd id="preis">' . $velo->preis . '</dd>
	</dl>';

echo '<form action="'.site_url('kasse/verkaufe').'"
			method="post"
			role="form">';
// echo form_open('kasse/verkaufe', array('class'=>"form-horizontal"));
echo form_hidden('id', $velo->id);
echo form_hidden('provision', $velo->getProvision());
echo form_hidden('angeschriebener_preis', $velo->preis);

echo '
	<div class="checkbox">
		<label>
			' . form_checkbox('helfer_kauft', 'yes', false, 'id="helfer_kauft" class="form-control focusPlease"') . '
			Von HelferIn gekauft
		</label>
	</div>
	<div class="form-group">
		<label class="radio-inline">
			' . form_radio('zahlungsart', 'bar', false) . '
			Bar
		</label>
		<label class="radio-inline">
			' . form_radio('zahlungsart', 'karte', true) . '
			Karte
		</label>
	</div>
	<div class="form-group">
			<button type="submit" class="btn btn-default">Bestätigen</button>
	</div>';

echo form_close();


echo '
</div>
			
<div>
	<p>' . anchor('velos/formular/' . $velo->id, 'Ausnahmen bearbeiten') . '</p>
	<p>' . anchor('login/dispatch/abholung', 'Zur Abholung (nicht-verkaufte Velos zurückgeben)') . '</p>
</div>';

include APPPATH . 'views/footer.php';
