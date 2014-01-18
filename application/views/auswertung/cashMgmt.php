<?php
include APPPATH . 'views/header.php';

echo heading('Cash Management', 1);

echo '<p>So viel Cash haben wir eingenommen: CHF ' . $cash . '</p>';

echo '<p>So viel Cash brauchen wir für die Auszahlung: CHF ' . $benoetigtesCash . '</p>';

include APPPATH . 'views/footer.php';