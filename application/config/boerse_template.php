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
Wenn du zu deiner Adresse eine IBAN registriert hast, kannst du dich zurücklehnen - dein Geld wird in der nächsten Woche auf dein Konto überwiesen. Du kannst das Geld aber auch an der Börse in Bar abholen kommen.
Falls du keine IBAN hinterlegt hast, musst du vor Börsenschluss dein Geld abholen kommen.
Liebe Grüsse
Deine Pro Velo';


// Für die Zahlungsliste (EZAG).
$config['ezag_iban'] = '';
$config['ezag_Nm'] = 'Pro Velo Bern';
$config['ezag_AdrLine'] = 'Bern';
$config['ezag_Ctry'] = 'CH';
$config['ezag_BIC'] = 'POFICHBEXXX';
$config['ezag_bemerkung'] = 'Pro Velo Börse, Velo Nr. <qn>'; // <qn> wird durch die Quittungsnummer ersetzt.
