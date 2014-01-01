<?php 
include APPPATH . 'views/header.php';

if (!empty($success) && 'verkaufe' == $this->uri->segment(2)) {
	echo '
	<div class="alert alert-info">
		<h3>Gespeicherte Angaben</h3>
		<dl>
			<dt>Quittung Nr.</dt><dd>' . $velo->id . '</dd>
			<dt>Preis</dt><dd>' . $velo->preis . '</dd>
			<dt>HelferIn kauft</dt><dd>' . $vonHelferGekauft . '</dd>
			<dt>Zahlungsart</dt><dd>' . ucfirst($velo->zahlungsart) . '</dd>
		</dl>
	</div>';
}

echo '
<div>
	<h1>Kasse: Velo verkaufen</h1>

	<p>Gib im Formular die Quittungs-Nummer ein, um das Velo zu verkaufen.</p>
				
</div>
		
<div>
	<p>' . anchor('login/dispatch/abholung', 'Zur Abholung (nicht-verkaufte Velos zurückgeben)') . '</p>
</div>';

include APPPATH . 'views/footer.php';