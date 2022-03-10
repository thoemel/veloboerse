<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'views/header.php';

echo validation_errors();

echo '
<div>
    <h1>Benutzer registrieren</h1>
</div>';

echo form_open($formAction, array('id' => 'registrierFormular'));

echo '
    <div class="row">
	<div class="form-group">
		<div class="col-lg-12">Mit <span class="glyphicon glyphicon-asterisk"></span> markierte Felder sind Pflicht.<br><br></div>
	</div>

	<div class="form-group">
		<label for="email_input" class="col-lg-2 control-label"><span class="glyphicon glyphicon-asterisk"></span> E-Mail</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('email') . '
			' . form_input(array('id' => 'email_input', 'name' => 'email', 'value' => $myUser->email, 'class' => 'focusPlease form-control')) . '
		</div>
	</div>
    </div>
    <div class="row">
	<div class="form-group">
		<label for="username_input" class="col-lg-2 control-label">Benutzername
            <span class="badge">
                <span class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#unameModal"></span>
            </span>
        </label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('username') . '
			' . form_input(array('id' => 'username_input', 'name' => 'username', 'value' => $myUser->username, 'class' => 'form-control', 'maxlength' => 12)) . '
		</div>
        <div class="col-sm-1 col-md-1 col-lg-1">
          <!-- Modal -->
          <div class="modal fade" id="unameModal" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Benutzername-Anforderungen</h4>
                </div>
                <div class="modal-body">
                  <p>
                  <ul>
                  	<li>Es ist kein Benutzername nötig.
                  	<li>Beim Login kannst du den Benutzernamen oder die E-Mail Adresse verwenden.
                    <li>Wenn schon jemand anderes den gleichen Benutzernamen hat, musst du einen andern suchen.
                    <li>Er darf höchstens 12 Zeichen lang sein.
                  </ul>
                  </p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                </div>
              </div>

            </div>
          </div>
        </div>
	</div>

	</div>
    <div class="row">
	<div class="form-group">
		<label for="password" class="col-lg-2 control-label">
            <span class="glyphicon glyphicon-asterisk"></span>Passwort
            <span class="badge">
                <span class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#myModal"></span>
            </span>
        </label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('password') . '
            ' . form_password(array('id' => 'password', 'name' => 'password', 'value' => '', 'class' => 'form-control')) . '
		</div>
        <div class="col-sm-1 col-md-1 col-lg-1">
          <!-- Modal -->
          <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Passwort-Anforderungen</h4>
                </div>
                <div class="modal-body">
                  <p>
                  <ul>
                  	<li>Mindestens 8 Zeichen
                    <li>Mindestens 1 Grosbuchstabe
                    <li>Mindestens 1 Kleinbuchstabe
                    <li>Mindestens 1 Zahl
                  </ul>
                  </p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                </div>
              </div>

            </div>
          </div>
        </div>
	</div>
    </div>
    <div class="row">
	<div class="form-group">
		<label for="vorname_input" class="col-lg-2 control-label"><span class="glyphicon glyphicon-asterisk"></span> Vorname</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('vorname') . '
			' . form_input(array('id' => 'vorname_input', 'name' => 'vorname', 'value' => $myUser->vorname, 'class' => 'form-control')) . '
		</div>
	</div>
	</div>
    <div class="row">
	<div class="form-group">
		<label for="nachname_input" class="col-lg-2 control-label"><span class="glyphicon glyphicon-asterisk"></span> Nachname</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('nachname') . '
			' . form_input(array('id' => 'nachname_input', 'name' => 'nachname', 'value' => $myUser->nachname, 'class' => 'form-control')) . '
		</div>
	</div>
	</div>
    <div class="row">
	<div class="form-group">
		<label for="nachname_input" class="col-lg-2 control-label"><span class="glyphicon glyphicon-asterisk"></span> Strasse, Nr.</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('strasse') . '
			' . form_input(array('id' => 'strasse_input', 'name' => 'strasse', 'value' => $myUser->strasse, 'class' => 'form-control')) . '
		</div>
	</div>
	</div>
    <div class="row">
	<div class="form-group">
		<label for="plz_input" class="col-lg-2 control-label"><span class="glyphicon glyphicon-asterisk"></span> PLZ</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('plz') . '
			' . form_input(array('id' => 'plz_input', 'name' => 'plz', 'value' => $myUser->plz, 'class' => 'form-control')) . '
		</div>
	</div>
	</div>
    <div class="row">
	<div class="form-group">
		<label for="ort_input" class="col-lg-2 control-label"><span class="glyphicon glyphicon-asterisk"></span> Ort</label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('ort') . '
			' . form_input(array('id' => 'ort_input', 'name' => 'ort', 'value' => $myUser->ort, 'class' => 'form-control')) . '
		</div>
	</div>
	</div>
    <div class="row">
	<div class="form-group">
		<label for="ort_input" class="col-lg-2 control-label">
            Telefon
            <span class="badge">
                <span class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#telefonModal"></span>
            </span>
        </label>
		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('telefon') . '
			' . form_input(array('id' => 'telefon_input', 'name' => 'telefon', 'value' => $myUser->telefon, 'class' => 'form-control')) . '
		</div>
        <div class="col-sm-1 col-md-1 col-lg-1">
          <!-- Modal -->
          <div class="modal fade" id="telefonModal" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Telefonnummer</h4>
                </div>
                <div class="modal-body">
                  <p>
                    Die Telefonnummer ist nur für die Börsen-Betreiber sichtbar.
                  </p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                </div>
              </div>

            </div>
          </div>
        </div>
	</div>
	</div>
    <div class="row">
	<div class="form-group">
		<label for="iban_input" class="col-lg-2 control-label">
            IBAN
            <span class="badge">
                <span class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#ibanModal"></span>
            </span>
        </label>

		<div class="col-sm-6 col-md-6 col-lg-6">
            ' . form_error('iban') . '
			' . form_input(array('id' => 'iban_input', 'name' => 'iban', 'value' => $myUser->iban, 'class' => 'form-control')) . '
		</div>
        <div class="col-sm-1 col-md-1 col-lg-1">
          <!-- Modal -->
          <div class="modal fade" id="ibanModal" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Bankverbindung für die Auszahlung</h4>
                </div>
                <div class="modal-body">
                  <p>
                    Die Auszahung (Verkaufspreis minus Provision) soll auf dieses Konto erfolgen.
                    <br>Die Adressangaben müssen mit dem Kontoinhaber übereinsteimmmen.
                    <br>Die Auszahlung erfolgt in der Woche nach der Börse.
                  </p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                </div>
              </div>

            </div>
          </div>
        </div>
	</div>
	<div class="form-group" id="ibanHinweis">
		<div class="col-lg-offset-2 col-lg-10">
			<div class="checkbox">
				<label>
					' . form_checkbox('iban_verstanden', '1', false, ['id'=>'input_check']) . '
					Ich habe den Hinweis zum IBAN verstanden.
				</label>
			</div>
		</div>
	</div>


    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
			<div class="checkbox">
				<label>
					' . form_checkbox('agb', '1', false, ['id'=>'agb_check']) . '
					Ich akzeptiere die ' . anchor_popup(base_url().'uploads/Teilnahmebedingungen_Veloboerse.pdf', 'Teilnahmebedingungen') . '.
				</label>
			</div>
		</div>
    </div>



    </div>
    <div class="row">
	<div class="form-group col-sm-offset-2 col-sm-10">
        ' . form_submit('submit', 'Speichern', 'class="btn"') . '
	</div>
	</div>';


echo form_close();

include APPPATH . 'views/footer.php';