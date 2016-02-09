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
				'rules' => 'trim|required|valid_email' 
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