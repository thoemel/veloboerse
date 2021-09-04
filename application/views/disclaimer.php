<?php
include APPPATH . 'views/header.php';


echo '
<h1>Disclaimer</h1>
<h2>Veranstalter</h2>
<p>' . config_item('veranstalter') . '<br>
' . config_item('adresse') . '</p>

<h2>Teilnahmebedingungen</h2>
' . anchor_popup(base_url().'uploads/Teilnahmebedingungen_Veloboerse.pdf', 'Teilnahmebedingungen') . '
';


include APPPATH . 'views/footer.php';