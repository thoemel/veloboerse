<?php
echo '<div>
	<p>Gib hier das Datum für die nächste Börse ein.</p>';

echo form_open('admin/boerseSpeichern');
if (0 < $naechsteBoerse->id) {
	echo form_hidden('id', $naechsteBoerse->id);
}
echo '
<div class="input-group date col-sm-2 col-md-2 col-lg-2">
	<input type="text" class="form-control datepicker col-sm-4 col-md-4 col-lg-4" name="boerseDatum" value="' . $naechsteBoerse->datum . '">
	<div class="input-group-addon">
	<span class="glyphicon glyphicon-th"></span>
	</div>
</div>';
echo form_submit('submit', 'speichern');
echo form_close();

echo '</div>';