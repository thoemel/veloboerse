<?php
include APPPATH . 'views/header.php';

echo '

<div>
	<h1>Velo zum Verkauf anbieten</h1>

    <div class="row">
	<div class="form-group">
		<div class="col-lg-12">Mit <span class="glyphicon glyphicon-asterisk"></span> markierte Felder sind Pflicht.<br><br></div>
	</div>
';

echo form_open_multipart('verkaeufer/speichereVelo',
    array('id' => 'erfassungsformular', 'role' => 'form', 'class' => 'form-horizontal'));
echo form_hidden('id',$myVelo->id);

if ($myVelo->id > 0) {
    echo heading('Quittung Nummer <span class="badge">' . $myVelo->id . '</span>', 3);
}

echo '
	</div>

    <div class="row">
    	<div class="form-group">
            <label for="preis_input" class="col-lg-2 control-label"><span class="glyphicon glyphicon-asterisk"></span> Preis</label>
    		<div class="col-sm-2 col-md-2 col-lg-1">
    			' . form_input(array('id' => 'preis_input', 'name' => 'preis', 'value' => $myVelo->preis, 'class' => 'focusPlease form-control')) . '
            </div>
    		<div class="col-sm-7 col-lg-7">
                <span class="glyphicon glyphicon-info-sign"></span>
    			Der Preis wird beim Speichern auf Zehner gerundet.
            </div>
    	</div>
    </div>
    <div class="row">
    	<div class="form-group">
            <div class="col-lg-2 control-label">
                Provision
            </div>
    		<div class="col-sm-2 col-md-2 col-lg-1">
    			<span class="provision">0</span>
            </div>
    	</div>
    </div>
    <div class="row">
    	<div class="form-group">
            <div class="col-lg-2 control-label">
                Auszahlung
            </div>
    		<div class="col-sm-2 col-md-2 col-lg-1">
    			<span class="auszahlungsbetrag">0</span>
            </div>
    	</div>
    </div>

    <div class="row">
    <div class="form-group">
		<label class="col-sm-2 control-label">Typ</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('typ', 'Damenvelo', ('Damenvelo' == $myVelo->typ)) . ' Damenvelo</label>
			<label class="radio-inline">' . form_radio('typ', 'Herrenvelo', ('Herrenvelo' == $myVelo->typ)) . ' Herrenvelo</label>
			<label class="radio-inline">' . form_radio('typ', 'Mountainbike', ('Mountainbike' == $myVelo->typ)) . ' Mountainbike</label>
			<label class="radio-inline">' . form_radio('typ', 'Citybike', ('Citybike' == $myVelo->typ)) . ' Citybike</label>
			<label class="radio-inline">' . form_radio('typ', 'Renner', ('Renner' == $myVelo->typ)) . ' Renner</label>
			<label class="radio-inline">' . form_radio('typ', 'Bahnhofvelo', ('Bahnhofvelo' == $myVelo->typ)) . ' Bahnhofvelo</label>
			<label class="radio-inline">' . form_radio('typ', 'E-Bike', ('E-Bike' == $myVelo->typ)) . ' E-Bike</label>
			<label class="radio-inline">' . form_radio('typ', 'Kindervelo', ('Kindervelo' == $myVelo->typ)) . ' Kindervelo</label>
			<label class="radio-inline">' . form_radio('typ', 'Zubehör', ('Zubehör' == $myVelo->typ)) . ' Zubehör</label>
			<label class="radio-inline">' . form_radio('typ', 'Anderes', ('Anderes' == $myVelo->typ)) . ' Anderes</label>
		</div>
	</div>
    </div>

    <div class="row">
	<div class="form-group">
		<label for="farbe_input" class="col-sm-2 control-label">Farbe</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'farbe_input', 'name' => 'farbe', 'value' => $myVelo->farbe, 'class' => 'form-control')) . '
		</div>
	</div>
    </div>

    <div class="row">
	<div class="form-group">
		<label for="marke_input" class="col-sm-2 control-label">Marke</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'marke_input', 'name' => 'marke', 'value' => $myVelo->marke, 'class' => 'form-control')) . '
		</div>
	</div>
    </div>
    <div class="row">
	<div class="form-group">
		<label for="rahmennummer_input" class="col-sm-2 control-label">Rahmennummer</label>
		<div class="col-sm-4">
			' . form_input(array('id' => 'rahmennummer_input', 'name' => 'rahmennummer', 'value' => $myVelo->rahmennummer, 'class' => 'form-control')) . '
		</div>
	</div>
    </div>
    <div class="row">
	<div class="form-group">
		<label class="col-sm-2 control-label">Velafrika</label>
		<div class="col-sm-10">
			<label class="radio-inline">' . form_radio('velafrika', 1, (true == $myVelo->afrika)) . ' ja</label>
			<label class="radio-inline">' . form_radio('velafrika', 0, (false == $myVelo->afrika)) . ' nein</label>
            <br>Wenn "ja", wird das Velo an Velafrika verschenkt, falls es nicht verkauft wird.
		</div>
	</div>
    </div>

    <div class="row">
	<div class="form-group">
		<label for="foto_input" class="col-sm-2 control-label">Foto</label>
		<div class="col-sm-10">
			<input id="foto_input" type="file" name="userfile" />
		</div>
	</div>
    </div>

    <div class="row">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">Speichern</button>
		</div>
	</div>

    </div>
</form>
</div>';

			    // JSON object with provisionsliste
			    echo '
<script type="text/javascript">
	var provisionsliste = ' . json_encode($provisionsliste) . ';
</script>';

include APPPATH . 'views/footer.php';