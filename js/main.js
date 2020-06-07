// provision
var provision = 0;

$(document).ready(function() {
	
	// Das letzte Formularfeld mit Klasse "focusPlease" auf der Seite kriegt den Fokus.
	$('.focusPlease').last().focus();

	// Auszahlung: Provision berechnen
	$('input[name=no_provision]').change(calcProvision);
	
	
	// Kasse: Provision berechnen 
	$('input[name=helfer_kauft]').change(calcProvisionAtCashier);
	
	// Kasse: Bemerkungsfeld für Helferlein Namen ein- oder ausblenden
	$('input[name=helfer_kauft]').change(function() {
		if ($('#helfer_kauft').attr('checked')) {
			$('#velo_bemerkungen').removeClass('hidden');
		} else {
			$('#velo_bemerkungen').addClass('hidden');
		}
	});
	
	// Annahme: Warnung bei fünfstelligem Preis
	$('#erfassungsformular').submit(function() {
		var meinPreis = $('#preis_input').attr('value');
		if (10000 < meinPreis) {
			return confirm('Kostet das Velo wirklich Fr. ' + meinPreis + '?');
		}
	});
	
	// Generell Anzeige von Provision und Auszahlung aus Preis berechnen
	$('#preis_input').keyup(calcProvisionDynamic);
	
	/*
	 * Layer einblenden, wannimmer ein Formular abgeschickt wird.
	 * Dies damit nicht ein zweites mal gescannt wird, 
	 * solange der erste Request noch nicht fertig ist.
	 */
	$('form').on('submit', function() {
		$('#confirmation_modal').modal('show');
	});
	
	$('.datepicker').datepicker({
	    language: 'de',
	    format: 'yyyy-mm-dd'
	});
});


/*
 * Bei Auszahlung auszuzahlenden Betrag ändern, wenn "keine Provision" angewählt ist.
 */
function calcProvision() {
	myProvision = $('#maxProvision').text();
	if (myProvision > 0) {
		provision = myProvision;
	}
	var auszahlung_betrag = $('.auszahlungsbetrag').first().text();
	var verkaufssumme = $('.verkaufssumme').first().text();
	var provision_total = $('.provision_total').first().text();
	if ($('#no_provision').attr('checked')) {
		auszahlung_betrag = (verkaufssumme - provision_total + parseInt(provision));
	} else {
		auszahlung_betrag = verkaufssumme - provision_total;
	}
	$('.auszahlungsbetrag').text(auszahlung_betrag);
}


/*
 * Auf Kasse-Formular Preis ändern, wenn "Helfer kauft" angeklickt ist.
 */
function calcProvisionAtCashier() {
	var angeschriebener_preis = parseInt($('input[name=angeschriebener_preis]').attr('value'), 10);
	var provision = parseInt($('input[name=provision]').attr('value'), 10);
	var preis;
	if ($('#helfer_kauft').attr('checked')) {
		preis = angeschriebener_preis - provision;
	} else {
		preis = angeschriebener_preis;
	}
	$('#preis').text(preis);
	return;
}


function calcProvisionDynamic() {
	var preis = parseInt($('#preis_input').val());
	provision = 0;
	
	// Provisionsliste von unten nach oben durchloopen
	var prevWasIt = false;
	jQuery.each(provisionsliste, function(i, val) {
		if (prevWasIt) {
			return;
		}
		provision = val;
		if (preis <= parseInt(i)) {
			prevWasIt = true;
		}
		return;
	});
	
	// Falls wir über der obersten Provisionsgrenze sind, werden 10% verrechnet.
	if (preis > 1000) { 
		provision = Math.round(preis * 0.15 / 10) * 10;
	}
	
	$('.provision').text(provision);
	
	var auszahlungsbetrag = preis - provision;
	$('.auszahlungsbetrag').text(auszahlungsbetrag);
	
	return;
}
