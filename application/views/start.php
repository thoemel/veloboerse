<?php
include APPPATH . 'views/header.php';

echo '

<div class="jumbotron">
	<h1>Velos kaufen und verkaufen: so einfach geht\'s an den Velobörsen von Pro Velo Bern.</h1>
</div>
<div class="row">
    <h2>Velo verkaufen</h2>
    <p>
        Du willst dein Velo verkaufen? ' . anchor('login/registrationForm', 'Registriere dich') . '
        und trage die Velos ein, die du verkaufen möchtest.
        Pro Person können maximal fünf Velos verlauft werden.
    </p>

    <h2>Velo kaufen</h2>
    <p>
        Vorbeischauen, sich beraten lassen und eine Probefahrt machen.';
if (empty($naechsteBoerse)) {
    $naechstesDatum = '(Datum leider noch nicht bekannt)';
} else {
    $naechstesDatum = date('d. F Y', strtotime($naechsteBoerse->datum));
}
echo '<br>' . $naechstesDatum . ', 10:00 Uhr bis 14:00 Uhr.

        <br>Mehrzweckhalle Kaserne, Papiermühlestrasse 13c.
        <br>Für Mitglieder mit Ausweis ab 9.00 Uhr
        (<a href="https://www.provelobern.ch/ueber-uns/mitglied-werden">Jetzt Mitglied werden</a>).
        Der Erwerb der Neumitgliedschaft vor Ort ist möglich.
    </p>';

if (!empty($naechsteBoerse)) {
	echo '
	<p>
		Anzahl Velos, die für den Verkauf registriert wurden:
        <span class="badge">' . $anzahl . '</span>
	</p>';

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
    </div>';
}

echo '</div>';

include APPPATH . 'views/footer.php';