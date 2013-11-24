<?php 
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Detailangaben zum Velo</h1>

<dl>';

foreach ($myVelo as $key => $value) {
	if ('img' == $key && empty($value)) {
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