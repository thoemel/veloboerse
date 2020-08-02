<?php
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Detailangaben zum Velo</h1>';

if (!empty($myVelo->img)) {
    $imgAttrs = ['src'=>'uploads/'.$myVelo->img, 'style'=>'width:100%;max-width:150px', 'class'=>'img-responsive'];
    $myImg = img($imgAttrs);
    unset($imgAttrs['style']);
    echo '

	<div class="row">
		<div class="col-sm-2">
            <a data-toggle="modal" data-target="#Velobild'.$myVelo->id.'">'.$myImg.'</a>
            <div id="Velobild'.$myVelo->id.'" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            '.img($imgAttrs).'
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>';
}

echo '
    <dl>';

foreach ($myVelo as $key => $value) {
	$value = str_replace(array('yes','no'), array('ja','nein'), $value);
	if (in_array($key, array('gestohlen','problemfall','storniert','afrika'))) {
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