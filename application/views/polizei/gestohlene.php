<?php
include APPPATH . 'views/header.php';

echo '
' . heading('Als gestohlen gemeldete Velos', 1) . '

<p>Diese Velos wurden von der Polizei als gestohlen deklariert.</p>

<table class="table table-striped table-bordered table-condensed">
<thead>
	<tr>
		<th scope="col">Quittung</th>
		<th scope="col">Händlernummer</th>
		<th scope="col">Preis</th>
	</tr>
</thead>
<tbody>';
foreach ($gestohlene->result() as $row) {
	echo '
	<tr>
		<td>' . $row->id . '</td>
		<td>' . $row->haendler_id . '</td>
		<td>' . $row->preis . '</td>
	</tr>';
}
echo '
	</tr>
</tbody>
</table>';

include APPPATH . 'views/footer.php';
