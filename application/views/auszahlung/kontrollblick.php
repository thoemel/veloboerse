<?php
include APPPATH . 'views/header.php';


echo '

	<h1>' . $h1 . '</h1>';
if ($keinAusweis) {
    echo '
	<div class="row">
		<p class="alert alert-warning">Verkäufer muss noch Ausweis zeigen!</p>
	</div>';
}

echo form_open('auszahlung/speichern_private', array('class' => 'form-horizontal', 'role' => 'form'));


foreach ($meineVelos as $diesesVelo) {

    if ('yes' == $diesesVelo['verkauft']
        && 'yes' == $diesesVelo['angenommen']
        && 'no' == $diesesVelo['ausbezahlt']
        && 0 == $diesesVelo['gestohlen']) {
        echo form_hidden('id[]', $diesesVelo['id']);
    }

    echo '
    ' . $diesesVelo['divAround'] . '
    	<h2>' . $diesesVelo['typ'] . ' | ' . $diesesVelo['marke'] . ' | ' . $diesesVelo['farbe'] . '</h2>';
    if (!empty($diesesVelo['img'])) {
        echo img(['src'=>'uploads/'.$diesesVelo['img'], 'height'=>'100']);
    }
    echo '
    	<div class="row">
    		<div class="col-sm-2">
    			Quittung Nr.
    		</div>
    		<div class="badge col-sm-1">
    			' . $diesesVelo['id'] . '
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-sm-2">
    			Status:
    		</div>
    		<div>
    			' . $diesesVelo['status'] . '
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-sm-2">
    			Preis:
    		</div>
    		<div class="col-sm-4">
    			Fr. ' . $diesesVelo['preis'] . ' (Provision: Fr. ' . Velo::getProvision($diesesVelo['preis']) . ')
    		</div>
    	</div>';


    if (!empty($diesesVelo['bemerkungen'])) {
    	echo '
    	<div class="row">
    		<div class="col-sm-2">Bemerkungen: </div>
    		<div class="col-sm-9 alert alert-warning">' . $diesesVelo['bemerkungen'] . '</div>
    	</div>';
    }


    if ('no' == $diesesVelo['verkauft']) {
    	echo '<p class="clearfix">Keine Auszahlung, weil das Velo nicht verkauft wurde.</p>';
    }
    if ('yes' == $diesesVelo['ausbezahlt']) {
        echo '<p class="clearfix">Keine Auszahlung, weil die Auszahlung schon erfolgte.</p>';
    }
    if ('no' == $diesesVelo['angenommen']) {
        echo '<p class="clearfix">Keine Auszahlung, weil nicht angenommen.</p>';
    }
    if (1 == $diesesVelo['gestohlen']) {
    	echo '<p class="clearfix">Keine Auszahlung, weil das Velo als gestohlen gemeldet wurde.</p>';
    }


    echo '
    </div>';
} // End foreach $meineVelos


echo '
    <div class="row">
    	<div class="checkbox">
    		<label>
    			' . form_checkbox('no_provision', 'yes', false, 'id="no_provision"') . '
    			Provisionserlass HelferIn --> Provisionserlass: Fr. <span id="maxProvision">' . $auszahlung_maxProvision . '</span>
    		</label>
    	</div>
    </div>';
echo '
    <p>Velos verkauft für Fr. <span class="verkaufssumme">' . $verkaufssumme . '</span></p>
    <p>Provision insgesamt Fr. <span class="provision_total">' . $provision_total . '</span></p>
	<p class="verybig">Auszahlen: Fr. <span class="auszahlungsbetrag">' . $auszahlung_betrag . '</span></p>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Bestätigen</button>
		</div>
	</div>';

echo form_close();

echo '
<div>
	<p><br><br><br><br><br><br>
		' . anchor('auszahlung/formular_private/',
				'Nicht auszahlen, nächste Quittung scannen.') . '</p>
</div>';

include APPPATH . 'views/footer.php';
