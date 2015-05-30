<?php
include APPPATH . 'views/header.php';

echo '
	' . heading('ToDos während der Börse', 2) . '
<div class="row">
	<p>
		Das ist zu tun während der Börse
	</p>
	<ul>
		<li>Direktlinks der Händler deaktivieren<br>
			<i>Das geht im Ressort Händleradmin -> Direktlinks</i>
		</li>
		<li>Händlerstatus auf "angenommen" setzen.<br>
			<i>update `haendler` SET status = "angenommen";</i></li>
		<li></li>
	</ul>
</div>';

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
	</ul>
</div>';



include APPPATH . 'views/footer.php';
