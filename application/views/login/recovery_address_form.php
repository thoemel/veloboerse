<?php
include APPPATH . 'views/header.php';

echo '
<div>
<h1>Passwort vergessen</h1>
</div>';

echo form_open('login/recover', array('id' => 'recoveryMailForm'));

echo '
    <div class="row">
	<div class="form-group">
		<label for="email_input" class="col-lg-2 control-label">E-Mail</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('email') . '
			' . form_input(array('id' => 'email_input', 'name' => 'email', 'value' => '', 'class' => 'focusPlease form-control')) . '
		</div>
	</div>
    </div>
    <div class="row">
	<div class="form-group col-sm-offset-2 col-sm-10">
        ' . form_submit('submit', 'Aktivierungs-Link anfordern', 'class="btn"') . '
	</div>
	</div>
';

echo form_close();

include APPPATH . 'views/footer.php';