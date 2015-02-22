<?php
include APPPATH . 'views/header.php';

echo heading('Cash Management', 1);

echo '<p>So viel Cash haben wir eingenommen: CHF ' . $cash . '</p>';

echo '<p>So viel Cash brauchen wir für die Auszahlung gemäss jetzigem Verkaufsstand: CHF ' . $benoetigtesCash . '</p>';

echo '<p>So viel Cash brauchen maximal für die Auszahlung: CHF ' . $worstCaseCash . '</p>';

include APPPATH . 'views/footer.php';