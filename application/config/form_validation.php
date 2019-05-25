<?php
$config = array ();
$config ['login'] = array (
		array (
				'field' => 'username',
				'label' => 'E-Mail',
				'rules' => 'trim|required' 
		),
		array (
				'field' => 'password',
				'label' => 'Passwort',
				'rules' => 'trim|required' 
		) 
);

$config ['createUser'] = array (
		array (
				'field' => 'email',
				'label' => 'E-Mail',
				'rules' => 'trim|required' 
		),
		array (
				'field' => 'pw',
				'label' => 'Passwort',
				'rules' => 'trim' 
		),
		array (
				'field' => 'role',
				'label' => 'Rolle',
				'rules' => 'trim|required' 
		) 
);

$config ['editUser'] = $config ['createUser'];
$config ['editUser'] [] = array (
		'field' => 'id',
		'label' => 'user_id',
		'rules' => 'trim|required|is_natural_no_zero' 
);

$config['veloFormular'] = array(
		array(
			'field' => 'id',
			'label' => 'Quittungsnummer',
			'rules' => 'trim|required|is_natural_no_zero'
		),
		array(
			'field' => 'preis',
			'label' => 'Preis',
			'rules' => 'trim|required|is_natural'
		),
		array(
			'field' => 'verkauft',
			'label' => 'Verkauft',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'abgeholt',
			'label' => 'Abgeholt',
			'rules' => 'trim|required'
		),
// 		array(
// 			'field' => 'zahlungsart',
// 			'label' => 'Zahlungsart',
// 			'rules' => 'trim|required'
// 		),
		array(
			'field' => 'ausbezahlt',
			'label' => 'Ausbezahlt',
			'rules' => 'trim|required'
		),
// 		array(
// 			'field' => 'kein_ausweis',
// 			'label' => 'Kein Ausweis',
// 			'rules' => 'trim|required'
// 		),
// 		array(
// 			'field' => 'keine_provision',
// 			'label' => 'Keine Provision',
// 			'rules' => 'trim|required'
// 		),
// 		array(
// 			'field' => 'helfer_kauft',
// 			'label' => 'Von Helfer gekauft',
// 			'rules' => 'trim|required'
// 		),
		array(
			'field' => 'haendler_id',
			'label' => 'Händler-Nummer',
			'rules' => 'trim'
		),
);

$config ['boerseSpeichern'] = array (
		array (
				'field' => 'boerseDatum',
				'label' => 'Datum',
				'rules' => 'trim|required|callback_future_date'
		),
);

$config ['haendlerConfigSpeichern'] = array (
		array (
				'field' => 'input_Firma',
				'label' => 'Firma',
				'rules' => 'trim'
		),
		array (
				'field' => 'input_Person',
				'label' => 'Person',
				'rules' => 'trim|required'
		),
		array (
				'field' => 'input_Adresse',
				'label' => 'Adresse',
				'rules' => 'trim|required'
		),
		array (
				'field' => 'input_Email',
				'label' => 'E-Mail',
				'rules' => 'trim|valid_email'
		),
		array (
				'field' => 'input_Telefon',
				'label' => 'Telefon',
				'rules' => 'trim'
		),
		array (
				'field' => 'input_Bankverb',
				'label' => 'Bankverbindung',
				'rules' => 'trim'
		),
		array (
				'field' => 'input_Kommentar',
				'label' => 'Kommentar',
				'rules' => 'trim'
		),
		array (
				'field' => 'input_busse',
				'label' => 'Busse',
				'rules' => 'trim|integer'
		),
		array (
				'field' => 'input_uptodate',
				'label' => 'Aktualisiert',
				'rules' => 'trim|in_list[0,1]',
				'errors' => array(
						'in_list' => 'Aktualisiert muss 0 oder 1 sein.',
				)
		),
		array (
				'field' => 'input_velos',
				'label' => 'Anzahl Velos',
				'rules' => 'trim|is_natural'
		),
		array (
				'field' => 'input_standgebuehr',
				'label' => 'Standgebühr',
				'rules' => 'trim|numeric'
		),
		array (
				'field' => 'input_status',
				'label' => 'Status',
				'rules' => 'trim|callback_valid_haendler_status'
		),
);