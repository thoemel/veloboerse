<?php
include APPPATH . 'views/header.php';

echo '
	' . heading('Vergangene Börsen', 1) . '
<div>
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th scope="column">Datum</th>
				<th scope="column">Statistik</th>
				<th scope="column">Händler</th>
				<th scope="column">Velos</th>
				<th scope="column">DB-Dump</th>
				<th scope="column">Zip</th>
			</tr>
		</thead>
		<tbody>';

foreach ($alleBoersen as $row) {
	$datumsTeil = date('Ymd', strtotime($row->datum));
	echo '
			<tr>
				<th scope="row">' . $row->datum . '</th>
				<td><a href="'.base_url().'backups/bkup_boerse_statistik_'.$datumsTeil.'.csv"
						title="Download Statistik CSV"><span class="glyphicon glyphicon-download"></span></a></td>
				<td><a href="'.base_url().'backups/bkup_boerse_haendler_'.$datumsTeil.'.csv"
						title="Download Händler CSV"><span class="glyphicon glyphicon-download"></span></a></td>
				<td><a href="'.base_url().'backups/bkup_boerse_velos_'.$datumsTeil.'.csv"
						title="Download Velos CSV"><span class="glyphicon glyphicon-download"></span></a></td>
				<td><a href="'.base_url().'backups/bkup_boerse_db_'.$datumsTeil.'.sql.gz"
						title="Download Datenbank Dump"><span class="glyphicon glyphicon-download"></span></a></td>
				<td>' . anchor('admin/boerseDownload/'.$row->id, 
							'<span class="glyphicon glyphicon-download"></span>',
							array("title" => "Alle Dateien als ZIP")) . '</td>
			</tr>';
}
echo '
		</tbody>
	</table>
</div>';

include APPPATH . 'views/footer.php';