<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Ist mein Velo verkauft?</h1>';
// $myVelo = new Velo() ;   // dem Eclipse sagen, dass myVelo ein Velo-Objekt ist
if ($myVelo->verkauft == 'yes') {
	echo '
		<p>Ja, es wurde für ' . $myVelo->preis . ' Franken verkauft.</p>
		<p>Ab 12 Uhr kann das Geld (abzüglich Provision von ' . Velo::getProvision($myVelo->preis) . ' Franken, d.h. am Schluss ' . ($myVelo->preis - Velo::getProvision($myVelo->preis)) . ' Franken) an der Velobörse abgeholt werden.</p>
		<p>Herzlichen Dank, Ihre Pro Velo Bern</p>';
} else {
	echo '
		<p>Leider wurde das Velo noch nicht verkauft.</p>
		<p>' . anchor('velos/suche/' . $myVelo->id, 'Nochmals versuchen', array('class'=>'btn btn-success ')) . '</p>';
}

echo '
</div>

';

include APPPATH . 'views/footer.php';