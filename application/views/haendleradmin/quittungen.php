<?php
include APPPATH . 'views/header.php';

echo '
<div class="haendleradmin">
	<ol class="breadcrumb hidden-print">
		<li>' . anchor('login/showChoices', 'Ressorts') . '</li>
		<li>' . anchor('haendleradmin', 'Händleradmin') . '</li>
		<li class="active">Quittungen zuweisen</li>
	</ol>
		
	<h1>Quittungen zuweisen</h1>
	<h2>Händler: ' . $anzeigename . '</h2>
			
	<h3>Zugewiesene Quittungsnummern</h3>
	<ul>';

foreach ($ids as $id) {
	echo '<li>' . $id . '</li>';
}

echo '
	</ul>
	<p>Insgesamt <span class="badge">' . count($ids) . '</span> Quittungen</p>

	<h3>Neu zuweisen</h3>
	<form class="form-inline" role="form" action="' . site_url('haendleradmin/quittungenSpeichern') . '" method="post">
	<input type="hidden" name="haendler_id" value="' . $haendler->id . '">
	<div class="row">
		<div class="form-group col-md-1">
			<input placeholder="von" value="" name="range_from" type="text" class="form-control">
		</div>
		<div class="form-group col-md-1">
			<input placeholder="bis" value="" name="range_to" type="text" class="form-control">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-default">Speichern</button>
		</div>
	</div>
	</form>
	
	<h3>Link für Händlerformular</h3>
	<p>
		Diesen Link kannst Du den Händlern mailen. Sie können damit ohne Login ihre Velos eintragen:<br>
		' . site_url('haendlerformular/'.$haendler->code) . '<br>
		oder ' . anchor('haendlerformular/'.$haendler->code, 'hier klicken') . ', um selbst zu schauen.
	</p>
	
</div>';


include APPPATH . 'views/footer.php';