<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Klasse für die Auszahlung von Privatvelos.
 * Die Händlerauszahlung erfolgt über die Händleradmin
 * @author thoemel
 */
class Auszahlung extends MY_Controller {


	public function __construct()
	{
		parent::__construct();

		// All methods require user to be logged in.
		$this->requireLoggedIn();
	}


	/**
	 * Zeigt eine Einstiegsseite mit Introtext
	 *
	 */
	public function formular_private()
	{
		$ichwill = 'debuggen';
		if ($this->session->flashdata('gespeichertesVelo')) {
			$myVelo = new Velo();
			try {
				$myVelo->find($this->session->flashdata('gespeichertesVelo'));
			} catch (Exception $e) {
				log_message('error', 'Auszahlung::formular_private() - Velo nicht gefunden');
				$this->session->set_flashdata('error', 'Fehler. Das angeblich ausbezahlte Velo konnte nicht gefunden werden.');
				redirect('auszahlung/formular_private');
				return ;
			}
			$this->addData('velo', $myVelo);
			$keineProvision = ('yes' == $myVelo->keine_provision) ? 'Ja' : 'Nein';
			$this->addData('keineProvision', $keineProvision);
		}

		$this->addData('newStatistics', Statistik::cashMgmt());

		$this->load->view('auszahlung/einstieg_private', $this->data);
	}


	/**
	 * Per Default auf formular_index umleiten.
	 */
	public function index()
	{
		$this->formular_private();
		return;
	}


	/**
	 * Zeigt gewisse für die Auszahlung relevante Details an, damit wir
	 * kontrollieren können, ob das Velo mit der Quittung übereinstimmt.
	 * Namentlich Preis und (falls irgendwann implementiert) Foto werden angezeigt.
	 * Zudem wird die Provision und der auszuzahlende Betrag berechnet und
	 * angezeigt.
	 */
	public function kontrollblick()
	{
		// Input validation
		$quittungNr = $this->input->post('id');
		if (10000 > $quittungNr) {
			$this->session->set_flashdata('error', 'Das ist ein Händlervelo. Auszahlung erfolgt nicht hier.');
			if (empty($quittungNr)) {
				$this->session->set_flashdata('error', 'Keine Quittungs-Nummer eingegeben. Bitte kontrolliere, ob das letzte Velo korrekt abgehandelt wurde!');
			}
			redirect('auszahlung/formular_private');
			return;
		}

		$myVelo = new Velo();
		try {
			$myVelo->find($quittungNr);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			$this->session->set_flashdata('error', 'Velo ist nicht registriert. Auszahlung nicht möglich.');
			redirect('auszahlung/formular_private');
			return;
		}

		// Verkäufy-Info
		if ($myVelo->verkaeufer_id > 0) {
		    $verkaeuferInfo = $this->load->view('verkaeufer/verkaeuferinfo', ['verkaeuferInfo'=>$myVelo->verkaeuferInfo()], TRUE);
		    $this->addData('verkaeuferInfo', $verkaeuferInfo);
		    $this->addData('verkaeufyId', $myVelo->verkaeufer_id);
		}

		// Heading
		$this->addData('h1', 'Auszahlung bestätigen');

		$alleMeine = Velo::fuerVerkaeufer($myVelo->verkaeufer_id);
		$meineVelos = [];
		$verkaufssumme = 0;
		$provision_total = 0;
		$auszahlung_betrag = 0;
		$keinAusweis = false;
		$maxPreis = 0;

		foreach ($alleMeine->result() as $row) {
		    $diesesVelo = [];
		    $diesesVelo['divAround'] = '<div class="alert-success">';
		    $diesesVelo['status'] = 'Verkauft';
		    if ('yes' == $row->ausbezahlt) {
		        $diesesVelo['divAround'] = '<div class="alert-warning">';
		        $diesesVelo['status'] = 'Schon ausbezahlt';
		    }
		    if ('no' == $row->verkauft) {
		        $diesesVelo['divAround'] = '<div class="alert-warning">';
		        $diesesVelo['status'] = 'Noch nicht verkauft';
		    }
		    if (1 == $row->gestohlen) {
		        $diesesVelo['divAround'] = '<div class="alert-warning">';
		        $diesesVelo['status'] = 'Gestohlen';
		    }

		    $diesesVelo['img'] = $row->img;
		    $diesesVelo['id'] = $row->id;
		    $diesesVelo['preis'] = $row->preis;
		    $diesesVelo['marke'] = $row->marke;
		    $diesesVelo['typ'] = $row->typ;
		    $diesesVelo['farbe'] = $row->farbe;
		    $diesesVelo['bemerkungen'] = $row->bemerkungen;
		    $diesesVelo['verkauft'] = $row->verkauft;
		    $diesesVelo['angenommen'] = $row->angenommen;
		    $diesesVelo['gestohlen'] = $row->gestohlen;
		    $diesesVelo['ausbezahlt'] = $row->ausbezahlt;

		    $meineVelos[] = $diesesVelo;

		    if ('yes' == $row->verkauft && 'yes' == $row->angenommen && 0 == $row->gestohlen && 'no' == $row->ausbezahlt) {
		        $verkaufssumme += $row->preis;
		        $provision_total += Velo::getProvision($row->preis);
		        $auszahlung_betrag += ($row->preis - Velo::getProvision($row->preis));
		        // Provisionserlass für Helferlein
		        $maxPreis = max([$diesesVelo['preis'], $maxPreis]);
		    }

		    if ('yes' == $row->kein_ausweis) {
		        $keinAusweis = TRUE;
		    }
		} // End foreach $alleMeine


		$this->addData('meineVelos', $meineVelos);
		$this->addData('verkaufssumme', $verkaufssumme);
		$this->addData('auszahlung_betrag', $auszahlung_betrag);
		$this->addData('provision_total', $provision_total);
		$this->addData('auszahlung_maxProvision', Velo::getProvision($maxPreis));
		$this->addData('keinAusweis', $keinAusweis);
		$this->addData('hideNavi', true);

		$this->load->view('auszahlung/kontrollblick', $this->data);
	}


	/**
	 * Liefert eine Quittung für das Verkäufy
	 * @param int $verkaeufyId
	 */
	public function pdf($verkaeufyId) {

	    $alleMeine = Velo::fuerVerkaeufer($verkaeufyId);
	    $meineVelos = [];
	    $verkaufssumme = 0;
	    $provision_total = 0;
	    $auszahlung_betrag = 0;
	    $keinAusweis = false;
	    $maxPreis = 0;

	    foreach ($alleMeine->result() as $row) {
	        if ('no' == $row->verkauft || 'no' == $row->angenommen || 'yes' == $row->ausbezahlt) {
	            continue;
	        }
	        $diesesVelo = [];
	        $diesesVelo['status'] = 'Verkauft';
	        if ('yes' == $row->ausbezahlt) {
	            $diesesVelo['status'] = 'Schon ausbezahlt';
	        }
	        if ('no' == $row->verkauft) {
	            $diesesVelo['status'] = 'Noch nicht verkauft';
	        }
	        if (1 == $row->gestohlen) {
	            $diesesVelo['status'] = 'Gestohlen';
	        }

	        $diesesVelo['id'] = $row->id;
	        $diesesVelo['preis'] = $row->preis;
	        $diesesVelo['marke'] = $row->marke;
	        $diesesVelo['typ'] = $row->typ;
	        $diesesVelo['farbe'] = $row->farbe;
	        $diesesVelo['rahmennummer'] = $row->rahmennummer;
	        $diesesVelo['verkauft'] = $row->verkauft;
	        $diesesVelo['angenommen'] = $row->angenommen;
	        $diesesVelo['gestohlen'] = $row->gestohlen;
	        $diesesVelo['ausbezahlt'] = $row->ausbezahlt;

	        $meineVelos[] = $diesesVelo;

	        if ('yes' == $row->verkauft && 'yes' == $row->angenommen && 0 == $row->gestohlen && 'no' == $row->ausbezahlt) {
	            $verkaufssumme += $row->preis;
	            $provision_total += Velo::getProvision($row->preis);
	            $auszahlung_betrag += ($row->preis - Velo::getProvision($row->preis));
	            // Provisionserlass für Helferlein
	            $maxPreis = max([$diesesVelo['preis'], $maxPreis]);
	        }

	    } // End foreach $alleMeine


	    /*
	     * PDF Quittung erstellen
	     */
	    $this->load->library('pv_tcpdf');
	    $pdf = new Pv_tcpdf('L', 'mm', 'custom', true, 'UTF-8');
	    $pdf->SetMargins(2, 2);
	    $pageHeight = 35 + 45 * count($meineVelos);
	    $pdf->AddPage('P', [54, $pageHeight]);
	    $pdf->setPageOrientation('P', true, 2);


	    // Logo
	    $pdf->Image(FCPATH . '/img/logo.png', $pdf->GetX(), 3, 0, 10.0, 'png', '', 'M', true, 300, 'L');

	    foreach ($meineVelos as $velo) {

	        // Nicht auf Quittung nehmen, wenn nicht jetzt ausbezahlt.
	        if ($velo['verkauft'] != 'yes' || $velo['ausbezahlt'] != 'no') {
	            continue;
	        }

    	    // Quittungsnummer
    	    $pdf->Ln(7);
    	    $pdf->SetFont('', '', 8);
    	    $pdf->SetTextColor(0,0,0);
    	    $idText = 'Velo Nr. ' . $velo['id'];
    	    $pdf->Write(0, $idText, '', false, 'L', true);

    	    // Typ
    	    $pdf->Write(0, 'Typ: ' . $velo['typ'], '', false, 'L', true);

    	    // Marke
    	    $pdf->Write(0, 'Marke: ' . $velo['marke'], '', false, 'L', true);

    	    // Farbe
    	    $pdf->Write(0, 'Farbe: ' . $velo['farbe'], '', false, 'L', true);

    	    // Rahmennummer
    	    $pdf->Write(0, 'Rahmennr: ' . $velo['rahmennummer'], '', false, 'L', true);

    	    // Preis
    	    $preisText = 'Verkaufspreis: Fr. ' . $velo['preis'] . '.--';
    	    $pdf->Write(0, $preisText, '', false, 'L', true);

    	    // Provision
    	    $pdf->write(0, 'Provision: Fr. ' . velo::getProvision($velo['preis']) . '.--', '', false, 'L', true);

    	    // Auszahlungsbetrag
    	    $pdf->Write(0, 'Auszahlungsbetrag: Fr. ' . ($velo['preis'] - velo::getProvision($velo['preis'])) . '.--', '', false, 'L', true);

	    } // end foreach $meineVelos


	    // Unterschrift
	    $pdf->Ln(5);
	    $pdf->Write(0, 'Total Fr. ' . $auszahlung_betrag . '.-- erhalten', '', false, 'R', true);
	    $pdf->Write(0, 'Datum: ' . date('d. m. Y'), '', false, 'R', true);
	    $pdf->Write(0, 'Unterschrift:', '', false, 'R', true);
	    $pdf->Ln(9);
	    $pdf->Write(0, '______________________', '', false, 'R', true);


	    $filename = 'Auszahlungsquittung_' . $verkaeufyId . '.pdf';
	    $pdf->Output($filename, 'I');

	    return ;
	}


	/**
	 * Auszahlung für Private registrieren
	 * @uses	post data
	 */
	public function speichern_private()
	{
	    $meineVelos = [];
	    $ausbezahlteIds = $this->input->post('id');
	    $teuerstes = 0; // ID des teuersten Velos
	    $hoechsterPreis = 0; // Preis des teuersten Velos

	    foreach ($ausbezahlteIds as $myId) {
	        $meineVelos[$myId] = new Velo();
	        try {
	            $meineVelos[$myId]->find($myId);
	        } catch (Exception $e) {
	            $this->session->set_flashdata('error', 'Für Quittung Nr. ' . $myId . ' wurde kein Velo gefunden.');
	            redirect('auszahlung/formular_private');
	            return;
	        }
	        $meineVelos[$myId]->ausbezahlt = 'yes';
	        $success = $meineVelos[$myId]->save();
	        if (!$success) {
	            log_message('error', 'Auszahlung speichern fuer Quittung Nr. '.$myId.' ging schief.');
	            $this->session->set_flashdata('error', 'Auszahlung speichern ging schief.');
	        } else {
	            $this->session->set_flashdata('success', 'Auszahlung wurde gespeichert.');
	        }

	        if ($meineVelos[$myId]->preis > $hoechsterPreis) {
	            $hoechsterPreis = $meineVelos[$myId]->preis;
	            $teuerstes = $myId;
	        }
	    }

	    // Provisionserlass für teuerstes Velo markieren
		$noProvision = ($this->input->post('no_provision') == 'yes') ? 'yes' : 'no';
        if ('yes' == $noProvision) {
            $meineVelos[$teuerstes]->keine_provision = 'yes';
            if (!$meineVelos[$teuerstes]->save()) {
                log_message('error', 'Provisionserlass konnte nicht gespeichert werden für ' . $teuerstes . '.');
                $this->session->set_flashdata('error', 'Provisionserlass speichern ging schief.');
            }
        }


		redirect('auszahlung/formular_private');
		return;
	} // End of function speichern_private


} // End of class Auszahlung

// EOF
