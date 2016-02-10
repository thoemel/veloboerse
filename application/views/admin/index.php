<?php
include APPPATH . 'views/header.php';

echo '
	' . heading('Administration', 1) . '
	' . heading('Aktuelle Börse', 2) . '
<div class="row">
	<p>
		Hier ein paar nette Links für die Auswertung der Börse:
	</p>
	<ul>
		<li>' . anchor('auswertung/cashMgmt', 'Cash Management') . '</li>
		<li>' . anchor('auswertung/statistik', 'Statistik') . '</li>
		<li>' . anchor('auswertung/afrika', 'Velos für Afrika') . '</li>
	</ul>
</div>
	' . heading('Nächste Börse einrichten', 2) . '
<div class="row">
	<p>
		' . $boerseContent . '
	</p>
</div>

	' . heading('Vergangene Börsen', 2) . '
<div class="row">
	<p>
		' . anchor('admin/vergangeneBoersen', 'Zu den Downloads') . '
	</p>
</div>
				
' . heading('Benutzer Administration', 2) . '
		
<div id="registeredUsers" class="side">';
echo heading('Registrierte Benutzer', 3);

echo '<div>';
echo anchor('admin/userForm', '&nbsp;', array('title' => 'Neuen Benutzer anlegen', 'class' => 'icon-plus')) . '&nbsp;';
echo anchor('admin/userForm', 'Neuen Benutzer anlegen');
echo '</div>';

echo '</div>
		
	<div class="main">';

if (count($registeredUsers) > 0) {
	echo '<ul>';
    foreach ($registeredUsers as $user) {
        echo '<li>';
        echo anchor('admin/switchToUser/' . $user->id, 
                    '&nbsp;',
					array('title' => 'als '.$user->email.' einloggen', 'class' => 'glyphicon glyphicon-share-alt'));
        echo '&nbsp;&nbsp;';
        echo anchor('admin/userForm/' . $user->id, 
                    '&nbsp;',
        			array('title' => 'editieren', 'class' => 'glyphicon glyphicon-edit'));
        echo '&nbsp;&nbsp;';
        echo anchor('admin/deleteUser/' . $user->id, 
                    '&nbsp;',
                    array('title' => 'löschen', 'class' => 'glyphicon glyphicon-trash', 'onclick' => 'return window.confirm(\'Willst Du den Benuztzer wirklich löschen?\');'));
        echo '&nbsp;&nbsp;';
        echo $user->email . ' (' . $user->role . ')';
        echo '</li>';
    }
    echo '</ul>';
}

echo '<div>';
echo anchor('admin/userForm', '&nbsp;', array('title' => 'Neuen Benutzer anlegen', 'class' => 'icon-plus')) . '&nbsp;';
echo anchor('admin/userForm', 'Neuen Benutzer anlegen');
echo '</div>';

echo '</div>';

include APPPATH . 'views/footer.php';