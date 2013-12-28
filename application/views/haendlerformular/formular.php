<?php
include APPPATH . 'views/header.php';

if ($this->session->userdata('logged_in')) {
	echo '
	<ol class="breadcrumb">
		<li>' . anchor('login/showChoices', 'Ressorts') . '</li>
		<li>' . anchor('haendleradmin', 'Händleradmin') . '</li>
		<li>' . anchor('haendleradmin/quittungen/' . $haendler->id, 'Quittungen zuweisen') . '</li>
		<li class="active">Händlerformular</li>
	</ol>';
}
echo heading('Velo Liste für HändlerInnen', 1) . '
	' . heading('Name: ' . $haendler->person, 2) . '
	
	<div class="alert alert-danger">
		Nicht vergessen, das Formular am Schluss (oder zwischendurch) zu speichern!
	</div>
	
	<div class="row">
		<div class="col-md-1">Nr.</div>
		<div class="col-md-1">Preis</div>
		<div class="col-md-2">Typ</div>
		<div class="col-md-2">Farbe</div>
		<div class="col-md-2">Marke</div>
		<div class="col-md-2">Rahmen-Nr.</div>
		<div class="col-md-2">Vignetten-Nr. 2011</div>
	</div>
			
	<form class="form-inline" role="form" action="' . site_url('haendlerformular/speichern') . '" method="post">';

foreach ($veloquery->result() as $velo) {
	echo '
	<div class="row">
		<div class="form-group col-md-1">
			<input readonly value="' . $velo->id . '" name="id[]" type="text" class="form-control input-sm">
		</div>
		<div class="form-group col-md-1">
			<input value="' . $velo->preis . '" name="preis[]" type="text" class="form-control input-sm">
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
		</div>
		<div class="form-group col-md-2">
			<input value="' . $velo->vignettennummer . '" name="vignettennummer[]" type="text" class="form-control input-sm">
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