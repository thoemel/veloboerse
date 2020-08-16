<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Authentifizierungs-Angaben für den SMTP-Server müssen in application/config/boerse.php erfasst werden.
 */
$config['protocol'] = 'smtp';


$config['pw_vergessen_text'] = 'Du hast dein Passwort vergessen?
Mit dem folgenden Link kannst du dir ein neues setzen:
{unwrap}%s{/unwrap}
Viel Erfolg an der Börse!
Deine Pro Velo';

/* End of file email.php */
/* Location: ./application/config/email.php */