<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Verklickt</h1>
	
	<div class="alert alert-error">
		Du hast das vorherige Velo nicht abgeschlossen! 
	</div>
	<div class="row">
		<div class="col-sm-4">
			Quittung Nr. des nicht abgeschlossenen Velos:
		</div>
		<div class="badge col-sm-2">
			' . $quittungNr . '
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			Du musst die Quittungsnummer noch einmal oben rechts eingeben und den 
			Verkauf abschliessen! 
		</div>
	</div>
	
</div>
			
<div>
	<p><br><br><br><br><br><br>
		' . anchor('kasse/', 'Verkauf abbrechen') . '</p>
</div>';

include APPPATH . 'views/footer.php';
