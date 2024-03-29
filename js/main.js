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
		if ($('#helfer_kauft').prop('checked')) {
			$('#velo_bemerkungen').removeClass('hidden');
		} else {
			$('#velo_bemerkungen').addClass('hidden');
		}
	});
	
	$('#erfassungsformular').submit(function() {
		// Annahme: Warnung bei fünfstelligem Preis
		var meinPreis = $('#preis_input').prop('value');
		if (10000 < meinPreis) {
			return confirm('Kostet das Velo wirklich Fr. ' + meinPreis + '?');
		}
		// Annahme: Rechtliche Hinweise müssen akzeptiert werden.
		if ($('#rechtliche_hinweise').prop('checked') == false) {
			event.preventDefault();
	    	alert("Die rechtlichen Hinweise müssen akzeptiert werden.");
		}
	});
	
	$('input[name="typ"]').change(function() {
		// Hinweis auf Akku Kapazitätsprüfung bei E-Bikes
		if($('input[name="typ"]:checked').val() == 'E-Bike') {
			$('#eBike_modal').modal('show');
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
	
//	$('.datepicker').datepicker({
//	    language: 'de',
//	    format: 'yyyy-mm-dd'
//	});
	
	/**
	 * Zeige Checkbox für IBAN, falls der nicht leer ist.
	 */
//	$('#iban_input').on('keyup', function() {
//		if ($('#iban_input').val() == '') {
//			$('#ibanHinweis').hide();
//		} else {
//			$('#ibanHinweis').show();
//		}
//	});
	
	/**
	 * Schick die Registration nicht ab, wenn IBAN Feld nicht leer und Checkbox nicht checked.
	 * Ebensowenig, wenn Teilnahmebedingungen nicht checked.
	 * @returns
	 */
	$("#registrierFormular").submit(function(event){
		if ($('#iban_input').val() != '' && $('#input_check').prop('checked') == false) {
			event.preventDefault();
	    	alert("Bitte bestätige, dass du den Hinweis zum IBAN verstanden hast und damit einverstanden bist.");
		}
		if ($('#agb_check').prop('checked') == false) {
			event.preventDefault();
	    	alert("Die Teilnahmebedingungen müssen akzeptiert werden.");
		}
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
	if ($('#no_provision').prop('checked')) {
		auszahlung_betrag = (verkaufssumme - provision_total + parseInt(provision));
		$('#auszahlungsquittung_helfy').removeClass('hidden');
		$('#auszahlungsquittung').addClass('hidden');
	} else {
		auszahlung_betrag = verkaufssumme - provision_total;
		$('#auszahlungsquittung_helfy').addClass('hidden');
		$('#auszahlungsquittung').removeClass('hidden');
	}
	$('.auszahlungsbetrag').text(auszahlung_betrag);
}


/*
 * Auf Kasse-Formular Preis ändern, wenn "Helfer kauft" angeklickt ist.
 */
function calcProvisionAtCashier() {
	var angeschriebener_preis = parseInt($('input[name=angeschriebener_preis]').prop('value'), 10);
	var provision = parseInt($('input[name=provision]').prop('value'), 10);
	var preis;
	if ($('#helfer_kauft').prop('checked')) {
		preis = angeschriebener_preis - provision;
	} else {
		preis = angeschriebener_preis;
	}
	$('#preis').text(preis + ' Fr.');
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
