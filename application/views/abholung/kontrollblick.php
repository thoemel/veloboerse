<?php
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Abholung bestätigen</h1>
';
if (!empty($velo->img)) {
    $imgAttrs = ['src'=>'uploads/'.$velo->img, 'style'=>'width:100%;max-width:150px', 'class'=>'img-responsive'];
    $myImg = img($imgAttrs);
    unset($imgAttrs['style']);
    echo '

	<div class="row">
		<div class="col-sm-2">
            <a data-toggle="modal" data-target="#Velobild'.$velo->id.'">'.$myImg.'</a>
            <div id="Velobild'.$velo->id.'" class="modal fade" role="dialog">
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
	<dl>
		<dt>Quittung Nr.</dt>
		<dd>' . $velo->id . '</dd>
		<dt>Preis</dt>
		<dd>' . $velo->preis . '</dd>';
if ('' != $velo->bemerkungen) {
		echo '<dt>Bemerkung</dt>
		<dd>' . $velo->bemerkungen . '</dd>';
}
echo '
	</dl>';

if (isset($verkaeuferInfo)) {
    echo '
    <div class="row">
        ' .$verkaeuferInfo . '
    </div>';
}


echo form_open('abholung/abholen');
echo form_hidden('id', $velo->id);

if ('yes' == $velo->verkauft) {
	echo '<p class="verybig alert-error">Das Velo wurde schon verkauft!</p>';
} elseif ('yes' == $velo->abgeholt) {
	echo '<p class="verybig alert-error">Das Velo wurde schon als abgeholt registriert!</p>';
} elseif (1 == $velo->gestohlen) {
	echo '<p class="verybig alert-error">Das Velo wurde als gestohlen gemeldet!</p>';
} else {
	echo '<p class="clearfix">' . form_submit('abholung_bestaetigen', 'Bestätigen', 'class="btn"') . '</p>';
}

echo form_close();


echo '
</div>


<div>
	<p>' . anchor('velos/formular/' . $velo->id, 'Ausnahmen bearbeiten') . '</p>
	<p>' . anchor('login/dispatch/kasse', 'Zur Kasse (Velos verkaufen)') . '</p>
</div>';

include APPPATH . 'views/footer.php';
