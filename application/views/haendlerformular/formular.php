<?php
include APPPATH . 'views/header.php';

if ($this->session->userdata('logged_in')) {
	echo '
	<ol class="breadcrumb hidden-print">
		<li>' . anchor('login/showChoices', 'Ressorts') . '</li>
		<li>' . anchor('haendleradmin', 'Händleradmin') . '</li>
		<li>' . anchor('haendleradmin/quittungen/' . $haendler->id, 'Quittungen zuweisen') . '</li>
		<li class="active">Händlerformular</li>
	</ol>';
}
echo heading('Velo Liste für HändlerInnen', 1) . '
	' . heading('Händler Nr. ' . $haendler->id . ', ' . $haendler->person, 2) . '
	<div>' . anchor('haendlerformular/pdf/' . $haendler->code, 'Druckversion', 'class="btn btn-info"') . '</div>
	<div>&nbsp;</div>
	<div class="alert alert-danger hidden-print">
		Nicht vergessen, das Formular am Schluss (oder zwischendurch) zu speichern!
	</div>

	<div class="row">
		<div class="col-md-1">Nr.</div>
		<div class="col-md-1">Stornieren</div>
		<div class="col-md-1">Preis</div>
		<div class="col-md-2">Typ</div>
		<div class="col-md-2">Farbe</div>
		<div class="col-md-2">Marke</div>
		<div class="col-md-2">Rahmen-Nr.</div>';
if ($mayDelete) {
	echo '
		<div class="col-md-1">Del.</div>';
}
echo '
	</div>

	<form class="form-inline" role="form" action="' . site_url('haendlerformular/speichern') . '" method="post">';

$tabindex = 0;
foreach ($veloquery->result() as $velo) {
	$tabindex++;
	$tabindexAttr = $useTabindex === true ? ' tabindex="'.$tabindex.'"' : '';
	$focusClass = $tabindex === 1 ? ' focusPlease' : '';
	echo '
	<div class="row">
		<div class="form-group col-md-1">
			<input readonly value="' . $velo->id . '" name="id[]" type="text" class="form-control input-sm">
		</div>
		<div class="form-group col-md-1">
			' . form_checkbox('storniert[]', $velo->id, (true == $velo->storniert)) . '
		</div>
		<div class="form-group col-md-1">
			<input value="' . $velo->preis . '" name="preis[]" type="text" class="form-control input-sm'.$focusClass.'"'.$tabindexAttr.'>
		</div>
		<div class="form-group col-md-2">
			<input value="' . $velo->typ . '" name="typ[]" type="text" class="form-control input-sm">
		</div>
		<div class="form-group col-md-2">
			<input value="' . $velo->farbe . '" name="farbe[]" type="text" class="form-control input-sm">
		</div>
		<div class="form-group col-md-2">
			<input value="' . $velo->marke . '" name="marke[]" type="text" class="form-control input-sm">
		</div>
		<div class="form-group col-md-2">
			<input value="' . $velo->rahmennummer . '" name="rahmennummer[]" type="text" class="form-control input-sm">
		</div>';
	if ($mayDelete) {
		echo '
		<div class="form-group col-md-1">
			' . form_checkbox('zuLoeschen[]', $velo->id, false) . '
		</div>';
	}
	echo '
		</div>';
} // End foreach Velo


if ($mayDelete) {
    // Velos Status auf angenommen stellen
    echo '
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="form-group">
            <p>Hier ankreuzen, falls alle Velos angenommen sind:</p>
			<div class="col-md-1">
                ' . form_checkbox('velosAnnehmen', 1, false) . '
            </div>
            <label for="preis_input" class="col-lg-4 control-label">Angenommen</label>
		</div>
	</div>';
}

echo '
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="form-group">
				<button type="submit" class="btn btn-danger">Speichern</button>
		</div>
	</div>
	</form>';

include APPPATH . 'views/footer.php';