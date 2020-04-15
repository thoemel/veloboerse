<?php
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Aktivierungs-Link wurde geschickt</h1>
    <p>Das heisst, nicht ganz. Mail verschicken ist noch nicht implementiert. Bis dahin gilt dieser Link:<br>
        ' . $recovery_link . '
    </p>
</div>';


include APPPATH . 'views/footer.php';