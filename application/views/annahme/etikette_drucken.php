<?php
include APPPATH . 'views/header.php';

echo form_open('annahme/pdf/'.$myVelo->id);

echo '
<div>
	<h1>Etikette drucken</h1>

    <div class="row">
    	<div class="form-group">
    		<div class="btn btn-warning col-sm-4 col-lg-2">
    			' . anchor('annahme/pdf/'.$myVelo->id, 'Etikette') . '
    		</div>
    	</div>
    </div>
    <div class="row">
        <br>
    	<div class="form-group">
    		<div class="btn btn-warning col-sm-4 col-lg-2">
    			' . anchor('verkaeufer/pdf/'.$myVelo->id, 'Quittung A4') . '
    		</div>
    	</div>
    </div>
    <div class="row">
        <br>
    	<div class="form-group">
    		<div class="btn btn-info col-sm-4 col-lg-2">
    			' . anchor('annahme/einstieg_private', 'Nächstes Velo annehmen') . '
    		</div>
    	</div>
    </div>
</div>';


include APPPATH . 'views/footer.php';