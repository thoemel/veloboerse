<?php
// CI not normally available in config files
$CI =& get_instance();

// Load the external model for validation of passwords
$CI->load->model('validation_callables');


$config = array ();
$config ['login_rules'] = array (
		array (
				'field' => 'login_string',
				'label' => 'E-Mail',
				'rules' => 'trim|required'
		),
		array (
				'field' => 'login_pass',
				'label' => 'Passwort',
				'rules' => 'trim|required'
		)
);

$config ['createUser'] = array (
	array (
		'field' => 'email',
		'label' => 'E-Mail',
	    'rules' => ['trim','required','valid_email', [
	        'check_unique_email', [ $CI->validation_callables, 'check_unique_email']
	       ]
	    ]
	),
    array (
        'field' => 'username',
        'label' => 'Benutzername',
        'rules' => [
            'trim', [
                'check_unique_username', [ $CI->validation_callables, 'check_unique_username']
            ]
        ]
    ),
	array (
		'field' => 'password',
		'label' => 'Passwort',
	    'rules' => [
	        'trim',
	        'required',
	        [
	            'check_password_strength', [ $CI->validation_callables, 'check_password_strength' ]
	        ]
	    ]
	),
    array (
        'field' => 'vorname',
        'label' => 'Vorname',
        'rules' => ['trim','required']
    ),
    array (
        'field' => 'nachname',
        'label' => 'Nachname',
        'rules' => ['trim','required']
    ),
    array (
        'field' => 'strasse',
        'label' => 'Strasse',
        'rules' => ['trim','required']
    ),
    array (
        'field' => 'plz',
        'label' => 'Postleitzahl',
        'rules' => ['trim','required']
    ),
    array (
        'field' => 'ort',
        'label' => 'Ort',
        'rules' => ['trim','required']
    ),
    array (
        'field' => 'telefon',
        'label' => 'Telefon',
        'rules' => ['trim']
    ),
    array (
        'field' => 'iban',
        'label' => 'IBAN',
        'rules' => ['trim', [
            'check_iban_syntax', [ $CI->validation_callables, 'check_iban_syntax']
            ]
        ]
    ),
    array (
        'field' => 'agb',
        'label' => 'Teilnahmebedingungen',
        'rules' => ['trim','required']
    )
);

/*
 * E-Mail und Username können nicht mit diesen Mitteln auf Einmaligkeit geprüft werden.
 * Das braucht eine extra Prüfung im Controller.
 */
$config ['editUser'] = [
    [
        'field' => 'user_id',
        'label' => 'user_id',
        'rules' => 'trim|required|is_natural_no_zero'
    ], [
        'field' => 'email',
        'label' => 'E-Mail',
        'rules' => ['trim','required','valid_email']
    ], [
        'field' => 'vorname',
        'label' => 'Vorname',
        'rules' => ['trim','required']
    ], [
        'field' => 'nachname',
        'label' => 'Nachname',
        'rules' => ['trim','required']
    ], [
        'field' => 'strasse',
        'label' => 'Strasse',
        'rules' => ['trim','required']
    ], [
        'field' => 'plz',
        'label' => 'Postleitzahl',
        'rules' => ['trim','required']
    ], [
        'field' => 'ort',
        'label' => 'Ort',
        'rules' => ['trim','required']
    ], [
        'field' => 'telefon',
        'label' => 'Telefon',
        'rules' => ['trim']
    ], [
        'field' => 'iban',
        'label' => 'IBAN',
        'rules' => ['trim', [
            'check_iban_syntax', [ $CI->validation_callables, 'check_iban_syntax']
        ]]
    ], [
        'field' => 'password',
        'label' => 'Passwort',
        'rules' => 'trim'
    ], [
        'field' => 'username',
        'label' => 'Benutzername',
        'rules' => 'trim'
    ]
];

/*
 * E-Mail und Username können nicht mit diesen Mitteln auf Einmaligkeit geprüft werden.
 * Das braucht eine extra Prüfung im Controller.
 */
$config ['editVerkaeufer'] = [
    [
        'field' => 'email',
        'label' => 'E-Mail',
        'rules' => ['trim','required','valid_email']
    ], [
        'field' => 'vorname',
        'label' => 'Vorname',
        'rules' => ['trim','required']
    ], [
        'field' => 'nachname',
        'label' => 'Nachname',
        'rules' => ['trim','required']
    ], [
        'field' => 'strasse',
        'label' => 'Strasse',
        'rules' => ['trim','required']
    ], [
        'field' => 'plz',
        'label' => 'Postleitzahl',
        'rules' => ['trim','required']
    ], [
        'field' => 'ort',
        'label' => 'Ort',
        'rules' => ['trim','required']
    ], [
        'field' => 'telefon',
        'label' => 'Telefon',
        'rules' => ['trim']
    ], [
        'field' => 'iban',
        'label' => 'IBAN',
        'rules' => ['trim', [
            'check_iban_syntax', [ $CI->validation_callables, 'check_iban_syntax']
        ]]
    ], [
        'field' => 'password',
        'label' => 'Passwort',
        'rules' => 'trim'
    ], [
        'field' => 'username',
        'label' => 'Benutzername',
        'rules' => 'trim'
    ]
];

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

$config['veloErfassenVerkaeufer'] = array(
    array(
        'field' => 'id',
        'label' => 'Quittungsnummer',
        'rules' => ['trim','required','is_natural']
    ),
    array(
        'field' => 'preis',
        'label' => 'Preis',
        'rules' => ['trim','required']
    ),
    array(
        'field' => 'userfile',
        'label' => 'Foto',
        'rules' => ['trim']
    ),
    array(
        'field' => 'rechtliche_hinweise',
        'label' => 'rechtliche Hinweise',
        'rules' => ['trim','required']
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

$config ['kasse'] = array (
    array (
        'field' => 'zahlungsart',
        'label' => 'Zahlungsart',
        'rules' => 'trim|required',
        'errors' => array(
            'required' => 'Wie wurde behahlt - bar oder mit Karte?',
        )
    ),
);

$config['annahme_registriere'] = [
    [
        'field' => 'ausweisOK',
        'label' => 'Ausweiskontrolle',
        'rules' => 'trim|required',
    ],
    [
        'field' => 'rahmennummerOK',
        'label' => 'Rahmennummer',
        'rules' => 'trim|required',
    ],

];