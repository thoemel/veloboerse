<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;

$config['pw_vergessen_text'] = 'Du hast dein Passwort vergessen?
Mit dem folgenden Link kannst du dir ein neues setzen:
{unwrap}%s{/unwrap}
Viel Erfolg an der Börse!
Deine Pro Velo';

/* End of file email.php */
/* Location: ./application/config/email.php */