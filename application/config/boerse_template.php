<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Datenbank Anbindung
 */
$db['default']['hostname'] = ''; // z.B. 'localhost'
$db['default']['database'] = ''; // z.B. 'probern_boerse'
$db['default']['username'] = ''; // z. B. 'probern_int'
$db['default']['password'] = ''; // z. B. '324öljkLJK@#¼'

/*
 * Mail Server Settings
 */
$config['smtp_host'] = ''; // z.B. 'smtp.mydomain.ch'
$config['smtp_adress'] = ''; // z.B. 'boerse@provelo-meineregion.ch'
$config['smtp_name'] = ''; // z.B. 'Pro velo Börse'
$config['smtp_user'] = ''; // z.B. 'boerse' oder 'boerse@provelo-meineregion.ch'
$config['smtp_pass'] = ''; // z.B. '324öljkLJK@#¼)('
$config['smtp_port'] = '587';

/*
 * Text für das Bestätigungsmail, wenn ein Velo verkauft wurde.
 * Die in spitzen Klammern stehenden Texte <...> werden durch die entsprechenden Angaben ersetzt.
 * <mit_oder_ohne_iban> wird je nachdem, ob ein IBAN erfasst wurde durch den entsprechenden Text ersetzt.
 * Falls du solche Klammerausdrücke weglässt, kommen die entsprechenden Texte nicht in den Text.
 */
$config['text_bestaetigungsmail'] = 'Liebe / lieber <vorname nachname>

Gratuliere, du hast ein Velo verkauft!

Dein Velo mit der Quittung Nr. <quittungsnummer> wurde für Fr. <preis>.-- verkauft.
<mit_oder_ohne_iban>
Liebe Grüsse
Deine Pro Velo';


$config['text_mit_iban'] = '
Der Erlös von Fr. <betrag_auszahlung>.-- wird dir in den nächsten Tagen auf dein Konto überwiesen.
';
$config['text_ohne_iban'] = '
Du musst deinen Erlös von Fr. <betrag_auszahlung>.-- vor Börsenschluss abholen kommen.
';

// Für die Zahlungsliste (EZAG)
$config['ezag_iban'] = '';
$config['ezag_Nm'] = 'Pro Velo Bern';
$config['ezag_AdrLine'] = 'Bern';
$config['ezag_Ctry'] = 'CH';
$config['ezag_BIC'] = 'POFICHBEXXX';
$config['ezag_bemerkung'] = 'Pro Velo Börse, Velo Nr. <qn>';
