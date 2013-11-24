<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Liste aller Angebote an der diesjährigen Velobörse</h1>

	<p>Hier ist der Start</p>
	
	<ul>
';

foreach ($liste->result() as $row) {
	echo '
		<li>id: ' . $row->id . ', Preis: ' . $row->preis . ' ' . anchor('velos/formular/' . $row->id, 'bearbeiten') . '</li>';
}

echo '
	</ul>

</div>

';

include APPPATH . 'views/footer.php';