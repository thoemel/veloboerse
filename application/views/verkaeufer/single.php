<?php
echo '

<div>
	<h1>Detailangaben zum Velo</h1>
';

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