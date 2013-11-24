<?php 
include APPPATH . 'views/header.php';

echo '
<div>
	<h1>Kasse: Velo verkaufen</h1>

	<p>Gib im Formular die Quittungs-Nummer ein, um das Velo zu verkaufen.</p>
				
</div>
		
<div>
	<p>' . anchor('login/dispatch/abholung', 'Zur Abholung (nicht-verkaufte Velos zurückgeben)') . '</p>
</div>';

include APPPATH . 'views/footer.php';