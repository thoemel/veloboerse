<?php
include APPPATH . 'views/header.php';

echo '
<div class="haendleradmin">

	<ol class="breadcrumb hidden-print">
		<li>' . anchor('login/showChoices', 'Ressorts') . '</li>
		<li>' . anchor('haendleradmin', 'Händleradmin') . '</li>
		<li class="active">Direktlinks</li>
	</ol>
	
	<h1>Händleradmin: Direktlinks</h1>
				
	<div>
		<p>' . anchor('haendleradmin/direktLinksDeaktivieren', 'Alle erneuern.',
				array('title'=>'Ersetzt die bestehenden Direktlinks durch neue.')) . '</p>
	</div>
	
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th scope="column">Nr.</th>
				<th scope="column">Firma | Person</th>
				<th scope="column">Link</th>
				<th scope="column">erneuern</th>
			</tr>
		</thead>
		<tbody>';

foreach ($liste->result() as $haendler) {
	echo '
			<tr>
				<th scope="row">' . $haendler->id . '</th>
				<td>' . $haendler->firma . ' | ' . $haendler->person . '</td>
				<td>' . base_url() . 'index.php/haendlerformular/' . $haendler->code . '</td>
				<td>' . anchor('haendleradmin/direktLinksDeaktivieren/' . $haendler->id,
							'erneuern', 
							array('title'=>'Ersetzt den bestehenden Direktlink dieses Händlers durch einen neuen.')) . '</td>
			</tr>';
}	

echo '
		</tbody>
	</table>
</div>';


include APPPATH . 'views/footer.php';