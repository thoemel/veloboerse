<?php
include APPPATH . 'views/header.php';

echo '
<div class="haendleradmin">
	<h1>Händleradmin: Velos eines Händlers importieren</h1>
	<p>Lade eine Datei hoch, um sie zu importieren.</p>';

echo form_open_multipart('haendleradmin/import') . '
	<input type="file" name="userfile" size="20" />
	' . form_submit('abschicken', 'hochladen') . '
	' . form_close();

echo '
</div>';


include APPPATH . 'views/footer.php';