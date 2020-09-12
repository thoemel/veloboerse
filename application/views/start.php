<?php
include APPPATH . 'views/header.php';

$st = config_item('starteseite');
if (empty($naechsteBoerse)) {
    $naechstesDatum = '(Datum leider noch nicht bekannt)';
} else {
    $naechstesDatum = date('d. F Y', strtotime($naechsteBoerse->datum)) . config_item('boerse_zeit');
}
$st = str_replace('{boerseDatumUndZeit}', $naechstesDatum, $st);
$st = str_replace('{spezielle_zeit_fuer_mitglieder}', config_item('spezielle_zeit_fuer_mitglieder'), $st);
$st = str_replace('{mitgliedschafts_link}', config_item('mitgliedschafts_link'), $st);

echo $st;

if (!empty($naechsteBoerse)) {
	echo '
    <div class="row">
	<p>
		Anzahl Velos, die für den Verkauf registriert wurden:
        <span class="badge">' . $anzahl . '</span>
	</p>';

	if (config_item('zeige_carousel')) {
    echo '
    <div class="col-lg-offset-3 col-lg-6">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">';
    $count = count($velos);
    for ($i = 0; $i < $count; $i++) {
        $active = (0 == $i) ? 'active' : '';
        echo '
        <li data-target="#myCarousel" data-slide-to="' . $i . '" class="' . $active . '"></li>';
    }
    echo '
      </ol>

      <!-- Wrapper for slides -->
      <div class="carousel-inner" role="listbox">';
      for ($i = 0; $i < $count; $i++) {
        $active = (0 == $i) ? 'active' : '';
        echo '
        <div class="item ' . $active . '">
          <img src="'.base_url().'uploads/' . $velos[$i]->img . '" style="width:100%;">
          <div class="carousel-caption">
            <p>CHF ' . $velos[$i]->preis . '</p>
          </div>
        </div>';
      }
      echo '

      <!-- Left and right controls -->
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Vorheriges</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Nächstes</span>
      </a>
    </div>
    </div>
    </div>';
	} // End if zeige_carousel
	echo '
    </div>';
} // End if !empty($naechsteBoerse)


include APPPATH . 'views/footer.php';