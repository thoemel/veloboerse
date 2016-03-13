<?php 
include APPPATH . 'views/header.php';

echo '

' . $divAround . '
	<h1>' . $h1 . '</h1>

	<div class="row">
		<div class="col-sm-2">
			Quittung Nr. 
		</div>
		<div class="badge col-sm-1">
			' . $velo->id . '
		</div>
	</div>
	<div class="row">
		<div class="col-sm-2">
			Preis: 
		</div>
		<div class="col-sm-2">
			<span id="preis">' . $velo->preis . '</span> 
			Fr.
		</div>
	</div>';

if ('yes' == $velo->kein_ausweis) {
	echo '
	<div class="row">
		<p class="alert alert-warning">Verkäufer muss noch Ausweis zeigen!</p>
	</div>';
}
if (!empty($velo->bemerkungen)) {
	echo '
	<div class="row">
		<div class="col-sm-2">Bemerkungen: </div>
		<div class="col-sm-10 alert alert-warning">' . $velo->bemerkungen . '</div>
	</div>';
}
echo form_open('auszahlung/speichern_private', 
		array('class' => 'form-horizontal', 'role' => 'form'));
echo form_hidden('id', $velo->id);

echo '
	<div class="checkbox">
		<label>
			' . form_checkbox('no_provision', 'yes', false, 'id="no_provision"') . '
			Keine Provision (von HelferIn verkauft)
		</label>
	</div>
	<div class="checkbox">
		<label>
			' . form_checkbox('auszahlung_summieren', 'yes', false, 'id="auszahlung_summieren"') . '
			Weiteres Velo vom gleichen Verkäufer dazunehmen
		</label>
	</div>
	<div id="velo_bemerkungen" class="hidden form-group">
		<label class="col-sm-2 control-label">Bitte Namen ins Bemerkungsfeld!</label>
		<div class="col-sm-10">
			<textarea rows="3" class="form-control" name="bemerkungen">' . $velo->bemerkungen . '</textarea>
		</div>
	</div>';

if ('no' == $velo->ausbezahlt && 'yes' == $velo->verkauft && 0 == $velo->gestohlen) {
	echo '
	<p class="verybig">Auszahlen: Fr. <span class="auszahlungsbetrag">' . $auszahlung_betrag . '</span></p>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Bestätigen</button>
		</div>
	</div>';
}
if ('no' == $velo->verkauft) {
	echo '<p class="clearfix alert alert-error">Keine Auszahlung, weil das Velo nicht verkauft wurde.</p>';
}
if ('yes' == $velo->ausbezahlt) {
	echo '<p class="clearfix alert alert-error">Keine Auszahlung, weil die Auszahlung schon erfolgte.</p>';
}
if (1 == $velo->gestohlen) {
	echo '<p class="clearfix alert alert-error">Keine Auszahlung, weil das Velo als gestohlen gemeldet wurde.</p>';
}

echo form_close();

echo '
</div>';

if ($this->session->userdata('summierte_auszahlung')) {
	echo '
	<div class="row">
		<p class="alert alert-info">
			Weitere Velos dieses Verkäufers:
			<p id="summierte_auszahlung">';
	$total = $auszahlung_betrag;
	foreach ($this->session->userdata('summierte_auszahlung') as $summierte) {
		$total += $summierte['auszuzahlen'];
		echo '
				<span class="col-sm-2">'.$summierte['quittungsNr'].':</span> 
				'.$summierte['auszuzahlen'].'<br>';
	}
	echo '
				<span class="col-sm-2">'.$velo->id.': </span>
				<span class="auszahlungsbetrag">'.$auszahlung_betrag.'</span><br>
				
				<span class="col-sm-2">Total: </span>
				<span class="badge" id="auszahlung_total">'.$total.'</span>
			</p>
		</p>
	</div>';
	
}

echo '
<div>
	<p><br><br><br><br><br><br>
		' . anchor('auszahlung/formular_private/', 
				'Nicht auszahlen, nächste Quittung scannen.') . '</p>
</div>';

include APPPATH . 'views/footer.php';
