// provision
var provision = 0;

$(document).ready(function() {
	
	// Das letzte Formularfeld mit Klasse "focusPlease" auf der Seite kriegt den Fokus.
	$('.focusPlease').last().focus();

	// Auszahlung: Provision berechnen
	$('input[name=no_provision]').change(calcProvision);
	
	// Kasse: Provision berechnen 
	$('input[name=helfer_kauft]').change(calcProvisionAtCashier);
	
	// Annahme: Warnung bei fünfstelligem Preis
	$('#erfassungsformular').submit(function() {
		var meinPreis = $('#preis_input').attr('value');
		if (10000 < meinPreis) {
			return confirm('Kostet das Velo wirklich Fr. ' + meinPreis + '?');
		}
	});
	
	$('#preis_input').keyup(calcProvisionDynamic);
	
	/*
	 * Layer einblenden, wannimmer ein Formular abgeschickt wird.
	 * Dies damit nicht ein zweites mal gescannt wird, 
	 * solange der erste Request noch nicht fertig ist.
	 */
	$('form').on('submit', function() {
		$('#confirmation_modal').modal('show');
	});
});


/*
 * Bei Auszahlung auszuzahlenden Betrag ändern, wenn "keine Provision" angewählt ist.
 */
function calcProvision() {
	myProvision = $('#preis').text() - $('#auszahlung_betrag').text();
	if (myProvision > 0) {
		provision = myProvision;
	}
	var auszahlung_betrag = $('#auszahlung_betrag').text();
	if ($('#no_provision').attr('checked')) {
		auszahlung_betrag = $('#preis').text();
	} else {
		auszahlung_betrag = ($('#preis').text() - provision);
	}
	$('#auszahlung_betrag').text(auszahlung_betrag);
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
	if (preis > 3000) { 
		provision = preis * 0.1;
	}
	
	$('.provision').text(provision);
	return;
}