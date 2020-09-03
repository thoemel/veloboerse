<?php

$x = new XMLWriter();
$x->openMemory();
$x->setIndent(TRUE);
$x->setIndentString('    ');
$x->startDocument("1.0", 'UTF-8');

$x->startElement("Document");

$x->writeAttribute('xmlns', 'http://www.six-interbank-clearing.com/de/pain.001.001.03.ch.02.xsd');
$x->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
$x->writeAttribute('xsi:schemaLocation', 'http://www.six-interbank-clearing.com/de/pain.001.001.03.ch.02.xsd  pain.001.001.03.ch.02.xsd');

$x->startElement('CstmrCdtTrfInitn');

// Header
$x->startElement('GrpHdr');
$x->startElement('MsgId');
$x->text('Boerse-auszahlung-01');
$x->endElement(); // MsgId
$x->startElement('CreDtTm');
$x->text(date('c'));
$x->endElement(); // CreDtTm
$x->startElement('NbOfTxs');
$x->text(count($velos));
$x->endElement(); // NbOfTxs
$x->startElement('CtrlSum');
$x->text($grandTotal);
$x->endElement(); // CtrlSum
$x->startElement('InitgPty');
$x->startElement('Nm');
$x->text(config_item('ezag_Nm'));
$x->endElement(); // Nm
$x->endElement(); // InitgPty
$x->endElement(); // GrpHdr

// Start payment
$x->startElement('PmtInf');

$x->startElement('PmtInfId');
$x->text('PMTINF-01');
$x->endElement(); // PmtInfId

$x->startElement('PmtMtd');
$x->text('TRF');
$x->endElement(); // PmtMtd

$x->startElement('BtchBookg');
$x->text('true');
$x->endElement(); // BtchBookg

$x->startElement('ReqdExctnDt');
$x->text(date('Y-m-d', strtotime('next wednesday')));
$x->endElement(); // ReqdExctnDt

$x->startElement('Dbtr');
$x->startElement('Nm');
$x->text(config_item('ezag_Nm'));
$x->endElement(); // Nm
$x->startElement('PstlAdr');
$x->startElement('Ctry');
$x->text(config_item('ezag_Ctry'));
$x->endElement(); // Ctry
$x->startElement('AdrLine');
$x->text(config_item('ezag_AdrLine'));
$x->endElement(); // AdrLine
$x->endElement(); // PstlAdr
$x->endElement(); // Dbtr

$x->startElement('DbtrAcct');
$x->startElement('Id');
$x->startElement('IBAN');
$x->text(str_replace(' ', '', config_item('ezag_iban')));
$x->endElement(); // IBAN
$x->endElement(); // Id
$x->endElement(); // DbtrAcct

$x->startElement('DbtrAgt');
$x->startElement('FinInstnId');
$x->startElement('BIC');
$x->text(config_item('ezag_BIC'));
$x->endElement(); // BIC
$x->endElement(); // FinInstnId
$x->endElement(); // DbtrAgt


// Eine Zahlung pro Velo
$i = 0;
foreach ($velos as $v) {
    $i++;
    $x->startElement('CdtTrfTxInf');

    $x->startElement('PmtId');
    $x->startElement('InstrId');
    $x->text('INSTRID-01-'.$i);
    $x->endElement(); // InstrId
    $x->startElement('EndToEndId');
    $x->text('ENDTOENDID-'.$i);
    $x->endElement(); // EndToEndId
    $x->endElement(); // PmtId

//     $x->startElement('PmtTpInf');
//     $x->startElement('LclInstrm');
//     $x->startElement('Prtry');
//     $x->text('');
//     $x->endElement(); // Prtry
//     $x->endElement(); // LclInstrm
//     $x->endElement(); // PmtTpInf

    $x->startElement('Amt');
    $x->startElement('InstdAmt');
    $x->writeAttribute('Ccy', 'CHF');
    $x->text($v->auszuzahlen);
    $x->endElement(); // InstdAmt
    $x->endElement(); // Amt

    $x->startElement('Cdtr');
    $x->startElement('Nm');
    $x->text($v->vorname . ' ' . $v->nachname);
    $x->endElement(); // Nm
    $x->startElement('PstlAdr');
    $x->startElement('AdrLine');
    $x->text($v->strasse);
    $x->endElement(); // AdrLine
    $x->startElement('AdrLine');
    $x->text($v->plz . ' ' . $v->ort);
    $x->endElement(); // AdrLine
    $x->endElement(); // PstlAdr
    $x->endElement(); // Cdtr

    $x->startElement('CdtrAcct');
    $x->startElement('Id');
    $x->startElement('IBAN');
    $x->text(str_replace(' ', '', $v->iban));
    $x->endElement(); // IBAN
    $x->endElement(); // Id
    $x->endElement(); // CdtrAcct

    $x->startElement('RmtInf');
    $x->startElement('Ustrd');
    $x->text(str_replace('<qn>', $v->qn, config_item('ezag_bemerkung')));
    $x->endElement(); // Ustrd
    $x->endElement(); // RmtInf

    $x->endElement(); // CdtTrfTxInf
} // End foreach verkaeufys


$x->endElement(); // PmtInf


$x->endElement(); // CstmrCdtTrfInitn
$x->endElement(); // Document

$x->endDocument();

echo $x->outputMemory();


