<?php
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Velo bearbeiten</h1>
';
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
echo form_open_multipart('velos/erfasse',
		array('id' => 'erfassungsformular', 'role' => 'form', 'class' => 'form-horizontal'));

echo heading('Quittung Nummer <span class="badge">' . $myVelo->id . '</span>', 3);
echo form_hidden('id',$myVelo->id);

echo '
	<div class="form-group">
		<label for="haendler_id" class="col-sm-2 control-label">Händler</label>
		<div class="col-sm-6 col-md-4 col-lg-4">
			' . $haendlerDropdown . '
		</div>';
if (isset($haendler)) {
	echo '
		<div class="col-sm-4 col-md-3 col-lg-3">
			' . anchor('haendlerformular/'.$haendler->code, '-> zum Händlerformular') . '
		</div>';
}
echo '
	</div>';

if (isset($verkaeuferInfo)) {
    echo $verkaeuferInfo;
}

echo '
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-2">Provision: <span class="provision">0</span></div>
		<div class="col-lg-2">Auszahlung: <span class="auszahlungsbetrag">0</span></div>
	</div>
		<div class="form-group">
		<label for="preis_input" class="col-lg-2 control-label">Preis</label>
		<div class="col-sm-2 col-md-2 col-lg-1">
			' . form_input(array('id' => 'preis_input', 'name' => 'preis', 'value' => $myVelo->preis, 'class' => 'focusPlease form-control')) . '
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">Velafrika</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('velafrika', 1, (true == $myVelo->afrika)) . ' ja</label>
			<label class="radio-inline">' . form_radio('velafrika', 0, (false == $myVelo->afrika)) . ' nein</label>
		</div>
	</div>

	<div class="form-group">
		<label for="typ_input" class="col-sm-2 control-label">Typ</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'typ_input', 'name' => 'typ', 'value' => $myVelo->typ, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="farbe_input" class="col-sm-2 control-label">Farbe</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'farbe_input', 'name' => 'farbe', 'value' => $myVelo->farbe, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="marke_input" class="col-sm-2 control-label">Marke</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'marke_input', 'name' => 'marke', 'value' => $myVelo->marke, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="rahmennummer_input" class="col-sm-2 control-label">Rahmennummer</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'rahmennummer_input', 'name' => 'rahmennummer', 'value' => $myVelo->rahmennummer, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label for="vignettennummer_input" class="col-sm-2 control-label">Vignettennummer</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'vignettennummer_input', 'name' => 'vignettennummer', 'value' => $myVelo->vignettennummer, 'class' => 'form-control')) . '
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Verkauft</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('verkauft', 'yes', ('yes' == $myVelo->verkauft)) . ' ja</label>
			<label class="radio-inline">' . form_radio('verkauft', 'no', ('no' == $myVelo->verkauft)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Abgeholt</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('abgeholt', 'yes', ('yes' == $myVelo->abgeholt)) . ' ja</label>
			<label class="radio-inline">' . form_radio('abgeholt', 'no', ('no' == $myVelo->abgeholt)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Zahlungsart</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('zahlungsart', 'bar', ('bar' == $myVelo->zahlungsart)) . ' bar</label>
			<label class="radio-inline">' . form_radio('zahlungsart', 'debit', ('debit' == $myVelo->zahlungsart)) . ' Karte</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Angenommen</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('angenommen', 'yes', ('yes' == $myVelo->angenommen)) . ' ja</label>
			<label class="radio-inline">' . form_radio('angenommen', 'no', ('no' == $myVelo->angenommen)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Ausbezahlt</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('ausbezahlt', 'yes', ('yes' == $myVelo->ausbezahlt)) . ' ja</label>
			<label class="radio-inline">' . form_radio('ausbezahlt', 'no', ('no' == $myVelo->ausbezahlt)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Kein Ausweis</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('kein_ausweis', 'yes', ('yes' == $myVelo->kein_ausweis)) . ' ja</label>
			<label class="radio-inline">' . form_radio('kein_ausweis', 'no', ('no' == $myVelo->kein_ausweis)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Keine Provision (von Helfer verkauft)</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('keine_provision', 'yes', ('yes' == $myVelo->keine_provision)) . ' ja</label>
			<label class="radio-inline">' . form_radio('keine_provision', 'no', ('no' == $myVelo->keine_provision)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Von Helfer gekauft</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('helfer_kauft', 'yes', ('yes' == $myVelo->helfer_kauft)) . ' ja</label>
			<label class="radio-inline">' . form_radio('helfer_kauft', 'no', ('no' == $myVelo->helfer_kauft)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Gestohlen</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('gestohlen', 1, (true == $myVelo->gestohlen)) . ' ja</label>
			<label class="radio-inline">' . form_radio('gestohlen', 0, (false == $myVelo->gestohlen)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Problemfall</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('problemfall', 1, (true == $myVelo->problemfall)) . ' ja</label>
			<label class="radio-inline">' . form_radio('problemfall', 0, (false == $myVelo->problemfall)) . ' nein</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Storniert</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('storniert', 1, (true == $myVelo->storniert)) . ' ja</label>
			<label class="radio-inline">' . form_radio('storniert', 0, (false == $myVelo->storniert)) . ' nein</label>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">Bemerkungen</label>
		<div class="col-sm-10">
			<textarea name="bemerkungen" class="form-control" rows="3">'
			. $myVelo->bemerkungen
			. '</textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">Speichern</button>
		</div>
	</div>
</form>


    <div class="row">
    	<div class="form-group">
    		<div class="btn btn-warning col-sm-offset-8 col-sm-4 col-lg-offset-10 col-lg-2">
    			' . anchor('annahme/pdf/'.$myVelo->id, 'Etikette') . '
    		</div>
    	</div>
    </div>
    <div class="row">
        <br>
    	<div class="form-group">
    		<div class="btn btn-warning col-sm-offset-8 col-sm-4 col-lg-offset-10 col-lg-2">
    			' . anchor('verkaeufer/pdf/'.$myVelo->id, 'Quittung A4') . '
    		</div>
    	</div>
    </div>
</div>';

// JSON object with provisionsliste
echo '
<script type="text/javascript">
	var provisionsliste = ' . json_encode($provisionsliste) . ';
</script>';

include APPPATH . 'views/footer.php';