<?php

/*
 * Hier kannst du das Anmelde-Formular für deinen Newsletter einbinden.
 * {vorname} wird ersetzt durch den Vornamen des eingeloggten Verkäufys.
 * {nachname} wird ersetzt durch den Nachnamen des eingeloggten Verkäufys.
 * {email} wird ersetzt durch die Mailadresse des eingeloggten Verkäufys.
 *
 * Falls die Config-Variable leer ist, wird der Link auf die Newsletter-Anmeldung nicht angezeigt.
 * Dazu folgende Zeile einfügen:
 * $config['newsletter_html'] = '';
 *
 */


$config['newsletter_html'] = '
<div>
    <h1>Newsletter abonnieren</h1>
</div>

<div>
    <p>Unser Newsletter informiert dich über Velo-relevante Themen. Insbesondere wirst du informiert, wenn wieder eine Börse angesagt ist.</p>
</div>

<!-- Begin Mailchimp Signup Form -->
<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
<style type="text/css">
#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; width:300px;}
/*
    Add your own Mailchimp form style overrides in your site stylesheet or in this style block.
    We recommend moving this block and the preceding CSS link to the HEAD of your HTML file.
*/
</style>

<div id="mc_embed_signup">
    <form action="https://provelobern.us20.list-manage.com/subscribe/post?u=e8f260791b7f7170adac5a291&amp;id=f2e5ba2ee3"
    	method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate"
    	target="_blank" novalidate>
        <div id="mc_embed_signup_scroll">
        	<div class="indicates-required">
        		Felder mit <span class="asterisk">*</span> müssen ausgefüllt werden.
        	</div>
        	<div class="mc-field-group">
        		<label for="mce-EMAIL">E-Mail <span class="asterisk">*</span></label>
        		<input type="email" value="{email}" name="EMAIL" class="required email" id="mce-EMAIL">
        	</div>
        	<div class="mc-field-group">
        		<label for="mce-FNAME">Vorname </label>
        		<input type="text" value="{vorname}" name="FNAME" class="" id="mce-FNAME">
        	</div>
        	<div class="mc-field-group">
        		<label for="mce-LNAME">Nachname </label>
        		<input type="text" value="{nachname}" name="LNAME" class="" id="mce-LNAME">
        	</div>
        	<div id="mce-responses" class="clear">
        		<div class="response" id="mce-error-response" style="display:none">
        		</div>
        		<div class="response" id="mce-success-response" style="display:none">
        		</div>
        	</div>
        	<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
        	<div style="position: absolute; left: -5000px;" aria-hidden="true">
        		<input type="text" name="b_e8f260791b7f7170adac5a291_f2e5ba2ee3" tabindex="-1" value="">
        	</div>
        	<div class="clear">
        		<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
        	</div>
        </div>
    </form>
</div>

<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script>
<script type="text/javascript">
(function($) {window.fnames =new Array(); window.ftypes = new
Array();fnames[0]="EMAIL";ftypes[0]="email";fnames[1]="FNAME";ftypes[1]="text";fnam
es[2]="LNAME";ftypes[2]="text";
/*
* Translated default messages for the $ validation plugin.
* Locale: DE
*/
$.extend($.validator.messages, {
    required: "Dieses Feld ist ein Pflichtfeld.",
    maxlength: $.validator.format("Geben Sie bitte maximal {0} Zeichen ein."),
    minlength: $.validator.format("Geben Sie bitte mindestens {0} Zeichen ein."),
    rangelength: $.validator.format("Geben Sie bitte mindestens {0} und maximal {1} Zeichen ein."),
    email: "Geben Sie bitte eine gültige E-Mail Adresse ein.",
    url: "Geben Sie bitte eine gültige URL ein.",
    date: "Bitte geben Sie ein gültiges Datum ein.",
    number: "Geben Sie bitte eine Nummer ein.",
    digits: "Geben Sie bitte nur Ziffern ein.",
    equalTo: "Bitte denselben Wert wiederholen.",
    range: $.validator.format("Geben Sie bitten einen Wert zwischen {0} und {1}."),
    max: $.validator.format("Geben Sie bitte einen Wert kleiner oder gleich {0} ein."),
    min: $.validator.format("Geben Sie bitte einen Wert größer oder gleich {0} ein."),
    creditcard: "Geben Sie bitte ein gültige Kreditkarten-Nummer ein."
});}(jQuery));
var $mcj = jQuery.noConflict(true);
</script>
<!--End mc_embed_signup-->
';