<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Detailangaben zum Velo</h1>

<dl>';

foreach ($myVelo as $key => $value) {
	$value = str_replace(array('yes','no'), array('ja','nein'), $value);
	if (in_array($key, array('gestohlen','problemfall','storniert'))) {
		$value = str_replace(array('1','0'), array('ja','nein'), $value);
	}
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