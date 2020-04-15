<?php
// TODO Filter Table einführen
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'views/header.php';

echo '
<div>
    <h1>Benutzerverwaltung</h1>
</div>

<div>
    <h2>Neuen Benutzer anlegen</h2>
    ' . anchor('benutzeradmin/createUserForm', 'Neu') . '
</div>
<div>
    <h2>Registrierte Benutzer</h2>
</div>

<div>
    <table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th>Benutzername</th>
            <th>Vorname</th>
            <th>Nachname</th>
            <th>E-Mail</th>
            <th>Wohnadresse</th>
            <th>Rolle</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>';
foreach ($allUsers as $u) {
    echo '
        <tr>
            <td>' . $u->username . '</td>
            <td>tbd</td>
            <td>tbd</td>
            <td>' . $u->email . '</td>
            <td>tbd</td>
            <td>' . $levels_and_roles[$u->auth_level] . '</td>
            <td>';
    echo anchor('admin/switchToUser/' . $u->id,
                    '&nbsp;',
					array('title' => 'als '.$u->email.' einloggen', 'class' => 'glyphicon glyphicon-share-alt'));
    echo '&nbsp;&nbsp;';
    echo anchor('benutzeradmin/userForm/' . $u->id,
                '&nbsp;',
    			array('title' => 'editieren', 'class' => 'glyphicon glyphicon-edit'));
    echo '&nbsp;&nbsp;';
    echo anchor('benutzeradmin/deleteUser/' . $u->id,
                '&nbsp;',
                array('title' => 'löschen', 'class' => 'glyphicon glyphicon-trash', 'onclick' => 'return window.confirm(\'Willst Du den Benuztzer wirklich löschen?\');'));
    echo '
            </td>
        </tr>';
}
echo '
    </tbody>
    </table>
</div>';



include APPPATH . 'views/footer.php';