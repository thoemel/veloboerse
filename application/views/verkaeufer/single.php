<?php
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Detailangaben zum Velo</h1>
';

echo '<div>PDF: ';
echo anchor('verkaeufer/pdf/' . $myVelo->id, 'PDF');
echo '</div>';

if (!empty($myVelo->img)) {
    echo img(['src'=>'uploads/'.$myVelo->img]);
}

echo '
<dl>';

foreach (['preis','typ','marke','farbe','rahmennummer'] as $key) {
    $value = $myVelo->$key;
	if (empty($value)) {
		$value = '-';
	}
	echo '
		<dt>' . $key . '</dt>
		<dd>' . $value . '</dd>';
}

echo '
	</dl>

</div>

';

include APPPATH . 'views/footer.php';