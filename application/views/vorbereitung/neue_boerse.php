<?php
include APPPATH . 'views/header.php';

echo '
	' . heading('Nächste Börse einrichten', 1) . '
<div class="row">
	<p>
		Das ist zu tun vor der Börse
	</p>
	<ul>
		<li>DB haendler, velos und statistik truncaten. <br>
			<i>haendler ist nur solange nötig, wie wir noch mit den GoogleDocs arbeiten.</i>
		</li>
		<li></li>
		<li></li>
		<li>Nachdem alle Händler ihre Velos eingetragen haben die Direktlinks
			disablen. <i>update haendler set code = uuid();</i></li>
	</ul>
</div>';


include APPPATH . 'views/footer.php';
