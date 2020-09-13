<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$db['default']['hostname'] = 'localhost';
$db['default']['database'] = 'probern_boerse';
$db['default']['username'] = 'probern_boerse';
$db['default']['password'] = 'upVGSUY3LbMCzKeC';


$config['smtp_host'] = 'server2.ahja.ch';
$config['smtp_adress'] = 'boerse@provelobern.ch';
$config['smtp_name'] = 'Pro Velo Bern';
$config['smtp_user'] = 'boerse@provelobern.ch';
$config['smtp_pass'] = '4w@f33D!!7Qz';
$config['smtp_port'] = '587';

// Ort
$config['ort'] = 'Himalaya';
$config['veranstalter'] = 'Pro Velo Bern';

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


// Für die Zahlungsliste (EZAG)
$config['ezag_iban'] = 'CH56 0900 0000 3001 9027 6';
$config['ezag_Nm'] = 'Pro Velo Bern';
$config['ezag_AdrLine'] = 'Bern';
$config['ezag_Ctry'] = 'CH';
$config['ezag_BIC'] = 'POFICHBEXXX';
$config['ezag_bemerkung'] = 'Pro Velo Börse, Velo Nr. <qn>';


// Startseite
$config['boerse_zeit'] = ', 10:00 Uhr bis 14:00 Uhr';
$config['spezielle_zeit_fuer_mitglieder'] = '<br>Für Mitglieder mit Ausweis ab 9.00 Uhr';
$config['mitgliedschafts_link'] = ' (<a href="https://www.provelobern.ch/ueber-uns/mitglied-werden">Jetzt Mitglied werden</a>). Der Erwerb der Neumitgliedschaft vor Ort ist möglich.';
$config['starteseite'] = '
    <div class="jumbotron">
        <h1>Velos kaufen und verkaufen: so einfach geht\'s an den Velobörsen von Pro Velo Bern.</h1>
    </div>
    <div class="row">
        <h2>Velo verkaufen</h2>
        <p>
            Du willst dein Velo verkaufen? Registriere dich über den Menupunkt "Registrierung"
            und trage die Velos ein, die du verkaufen möchtest.
            Pro Person können maximal fünf Velos verkauft werden.
        </p>

        <h2>Velo kaufen</h2>
        <p>
            Vorbeischauen, sich beraten lassen und eine Probefahrt machen.
            <br>{boerseDatumUndZeit}
            <br>Mehrzweckhalle Kaserne, Papiermühlestrasse 13c.
            {spezielle_zeit_fuer_mitglieder}
            {mitgliedschafts_link}
        </p>
    </div>';
$config['zeige_carousel'] = TRUE;
$config['anzahl_bilder_fuer_carousel'] = 30;


// Haftungsausschluss auf Quittung
$config['haftungsausschluss'] = 'Pro Velo Bern kann trotz Überwachung der Börse & Kontrolle der Velos für Verlust und Beschädigungen keine Haftung übernehmen. Am Veranstaltungstag ist der Verkaufserlös oder das Velo bis spätestens Börsenschluss abzuholen. Über nicht abgeholte Velos und Verkaufserlöse verfügt Pro Velo Bern. Pro Velo Bern haftet nicht für das verkaufte Velo.';
