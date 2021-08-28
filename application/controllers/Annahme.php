<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Annahme extends MY_Controller {


	public function __construct()
	{
		parent::__construct();

		// All methods require user to be logged in as Pro Velo staff.
		$this->require_min_level(8);
	}


	/**
	 * Der Einstieg zur Privatannahme. Hier wird nur ein Default-Text angezeigt und
	 * die Action fürs Formular im Header bestimmt.
	 */
	public function einstieg_private()
	{
		$this->load->view('annahme/einstieg_private', $this->data);
		return;
	}


	/**
	 * Zeigt das Erfassungsformular an.
	 *
	 * @param int	$id		Quittungsnummer. Falls eine angegeben, wird das Formular vorausgefüllt.
	 */
	public function formular_private($id = '')
	{
		$id = intval($id);
		if ($this->input->post('id')) {
			$id = intval($this->input->post('id'));
		}
		if (0 == $id) {
			$this->session->set_flashdata('error', 'Quittungs-Nummer fehlt.');
			redirect('annahme/einstieg_private');
			return;
		}
		if (10000 > $id) {
			$this->session->set_flashdata('error', 'Quittungsnummern < 10000 sind Händlerquittungen. Annahme fehlgeschlagen.');
			redirect('annahme/einstieg_private');
			return;
		}
		if (!Velo::istRegistriert($id)) {
			$this->session->set_flashdata('error', 'Es ist kein Velo mit dieser Quittungsnummer registriert.');
			redirect('annahme/einstieg_private');
			return;
		}


		// Prüfen, ob das Velo dem eingeloggten User gehört.
		$myVelo = new Velo();
		$myVelo->find($id);

		// Prüfen, ob das Velo den richtigen Status hat
		if ('yes' == $myVelo->angenommen) {
		    $this->session->set_flashdata('error', 'Das Velo ist schon drin.');
		    redirect('annahme/einstieg_private');
		    return;
		}

		$this->addData('myVelo', $myVelo);
		$this->addData('showSearchForm', FALSE);
		$this->load->view('header', $this->data);
		$this->load->view('verkaeufer/single', $this->data);
		$this->load->view('annahme/part_link_to_edit_velo', $this->data);
		$this->load->view('footer', $this->data);
		return;
	} // End of function formular_private


	/**
	 * Falls nicht anders in der URL gewünscht, wird die index-Methode aufgerufen.
	 */
	public function index()
	{
		/*
		 * Um sicher zu gehen, dass wir im richtigen Ressort gemeldet sind,
		 * wird hier das Ressort gewechselt. Der eigentliche Einstieg in das
		 * Ressort ist "einstieg_private".
		 */
		if ('privatannahme' != $this->session->userdata('user_ressort')) {
			redirect('login/dispatch/privatannahme');
		} else {
			$this->einstieg_private();
		}
		return ;
	}


	/**
	 * Stellt eine Quittung in einem PDF zum Ausdrucken zusammen.
	 * Das Format des PDF ist für den Etikettendrucker konfiguriert.
	 * Falls eine A4-Seite gedruckt werden soll, dann muss die PDF-Methode aus der Verkaeufer-Klasse verwendet werden.
	 * @param int $id   ID des Velos
	 */
	public function pdf($id)
	{
	    $myVelo = new Velo();
	    try {
	        $myVelo->find($id);
	    } catch (Exception $e) {
	        // Kein Velo mit dieser ID
	        $this->session->set_flashdata('error', 'Kein Velo mit dieser ID registriert.');
	        redirect('annahme/einstieg_private');
	        return;
	    }

	    if ($this->auth_user_id != $myVelo->verkaeufer_id && $this->auth_level < 8) {
	        // Eingeloggter User ist entweder Helfer, Admin oder Besitzer des Velos.
	        $this->session->set_flashdata('error', 'Nur Verkäufer oder Helfer dürfen drucken.');
	        redirect('annahme/einstieg_private');
	        return;
	    }


	    $this->load->library('pv_tcpdf');
	    $pdf = new Pv_tcpdf('L', 'mm', 'custom', true, 'UTF-8');
	    $pdf->SetMargins(2, 2);
	    $pdf->AddPage('L', [28, 300]);
	    $pdf->setPageOrientation('L', true, 2);


	    // Preis
	    $pdf->SetXY(30, 5);
	    $pdf->SetFont('', 'B', 45);
	    $pdf->SetTextColor(0,0,0);
	    $preisText = 'Fr. ' . $myVelo->preis;
	    $pdf->Write(0, $preisText, '', false, 'L');

	    // Typ
	    $pdf->SetXY(98, 5);
	    $pdf->SetFont('', '', 8);
	    $pdf->SetTextColor(0,0,0);
	    $pdf->Write(0, 'Typ: ' . $myVelo->typ, '', false, 'L', true);

	    // Marke
	    $pdf->SetX(98);
	    $pdf->SetFont('', '', 8);
	    $pdf->SetTextColor(0,0,0);
	    $pdf->Write(0, 'Marke: ' . $myVelo->marke, '', false, 'L', true);

	    // Farbe
	    $pdf->SetX(98);
	    $pdf->SetFont('', '', 8);
	    $pdf->SetTextColor(0,0,0);
	    $pdf->Write(0, 'Farbe: ' . $myVelo->farbe, '', false, 'L', true);

	    // Rahmennummer
	    $pdf->SetX(98);
	    $pdf->SetFont('', '', 8);
	    $pdf->SetTextColor(0,0,0);
	    $pdf->Write(0, 'Rahmennr: ' . $myVelo->rahmennummer, '', false, 'L', true);

	    // Verkäufer
	    $pdf->SetXY(150, 5);
	    $pdf->SetFont('', 'B', 8);
	    $pdf->write(0, 'Verkäufer:', '', false, 'L', true);
	    $pdf->SetFont('', '', 8);
	    $vi = $myVelo->verkaeuferInfo();
	    $pdf->SetX(150);
	    $pdf->write(0, $vi['vorname'] . ' ' . $vi['nachname'], '', false, 'L', true);
	    $pdf->SetX(150);
	    $pdf->write(0, $vi['strasse'], '', false, 'L', true);
	    $pdf->SetX(150);
	    $pdf->write(0, $vi['plz'], '', false, 'L');
	    $pdf->write(0, $vi['ort'], '', false, 'L');

	    // Pro Velo Bern kann trotz Kontrolle der Velos keine Haftung übernehmen.
	    $pdf->SetXY(42, 22);
	    $pdf->SetFont('', '', 8);
	    $pdf->write(0, 'Pro Velo Bern kann trotz Kontrolle der Velos keine Haftung übernehmen.', '', false, 'L', true);

	    // Preis
	    $pdf->SetXY(190, 5);
	    $pdf->SetFont('', 'B', 45);
	    $pdf->SetTextColor(0,0,0);
	    $preisText = 'Fr. ' . $myVelo->preis;
	    $pdf->Write(0, $preisText, '', false, 'L', false);

	    // Logo
	    $pdf->Image(FCPATH . '/img/logo.png', $pdf->GetX(), 3, 0, 5.0, 'png', '', 'M', true, 300, 'R');

	    // Barcode
	    $pdf->SetXY(260, 10);
	    $barcodeStyle = array(
	        'position' => 'R',
	        'align' => 'L',
	        'stretch' => false,
	        'fitwidth' => true,
	        'cellfitalign' => '',
	        'border' => false,
	        'hpadding' => 2,
	        'vpadding' => 0,
	        'fgcolor' => array(0,0,0),
	        'bgcolor' => false, //array(255,255,255),
	        'text' => true,
	        'font' => 'helvetica',
	        'fontsize' => 10,
	        'stretchtext' => 4
	    );
	    $pdf->write1DBarcode($myVelo->id, 'C128A', 260, 10, 40, 15, 0.4, $barcodeStyle, 'T');


	    $filename = 'Preisschild_' . $myVelo->id . '.pdf';
	    $pdf->Output($filename, 'D');

	    return ;
	} // End of function pdf()


	public function registriere()
	{
	    if (false === $this->form_validation->run('annahme_registriere')) {
	        $this->session->set_flashdata('error', validation_errors());
	        redirect('annahme/formular_private/' . $this->input->post('id'));
	        return ;
	    }
	    if ('no' == $this->input->post('rahmennummerOK')) {
	        $this->session->set_flashdata('error', 'Die Rahmennummer muss OK sein (nicht gefunden ist auch OK). Falls sie falsch ist, musst du sie korrigieren gehen.');
	        redirect('annahme/formular_private/' . $this->input->post('id'));
	        return ;
	    }
	    if (!Velo::istRegistriert($this->input->post('id'))) {
	        $this->session->set_flashdata('error', 'ungültige Quittungsnummer');
	        redirect('annahme/einstieg_private');
	        return ;
	    }

	    $myVelo = new Velo();
	    $myVelo->find($this->input->post('id'));
	    $myVelo->kein_ausweis = $this->input->post('ausweisOK') ? 'no' : 'yes';
	    $myVelo->angenommen = 'yes';
	    $myVelo->save();

	    $this->addData('myVelo', $myVelo);
	    $this->addData('showSearchForm', FALSE);
	    $this->load->view('annahme/etikette_drucken', $this->data);
	    return;
	}


	/**
	 * Formulardaten entgegennehmen und verarbeiten
	 * Nur Private mit von ProVelo vorgedruckten Quittungen (angenommen = 'yes')
	 * @deprecated
	 */
	public function speichern_private()
	{
		// Formular validieren
		$config = array(
				array(
						'field'   => 'preis',
						'label'   => 'Preis',
						'rules'   => 'required|is_natural'
				),
				array(
						'field'   => 'kein_ausweis',
						'label'   => 'Kein Ausweis',
						'rules'   => ''
				),
		);
		$this->form_validation->set_rules($config);
		if (false === $this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('annahme/formular_private/' . $this->input->post('id'));
		}


		$myVelo = new Velo();
		if (Velo::istRegistriert($this->input->post('id'))) {
			$this->session->set_flashdata('error', 'Diese Quittungs-Nummer ist schon registriert.');
			redirect('annahme/einstieg_private');
		}
		$myVelo->id = $this->input->post('id');
		$myVelo->preis = $this->input->post('preis');
		$myVelo->angenommen = 'yes';
		$myVelo->kein_ausweis = (1 == $this->input->post('kein_ausweis')) ? 'yes' : 'no';
		$myVelo->afrika = (1 == $this->input->post('velafrika')) ? 1 : 0;
		$myVelo->bemerkungen = $this->input->post('bemerkungen');
		if ('yes' == $this->input->post('keine_provision')) {
			$myVelo->keine_provision = 'yes';
		}

		// Bild Upload
		// 		$config['upload_path'] = './img/velos/';
		// 		$config['allowed_types'] = 'gif|jpg|png';
		// 		$config['max_size']	= '1024';
		// 		$config['max_width']  = '1024';
		// 		$config['max_height']  = '768';

		// 		$this->load->library('upload', $config);

		// 		if ( ! $this->upload->do_upload('img')) {
		// 			$this->data['error'] = 'Bild konnte nicht gespeichert werden.';
		// 			log_message('error', $this->upload->display_errors());
		// 		} else {
		// 			$upload_data = $this->upload->data();
		// 			$myVelo->img = $upload_data['file_name'];
		// 		}

		$success = $myVelo->save();
		if (!$success) {
			$this->session->set_flashdata('error', 'Velo Annahme ging schief.');
			$this->data['myBike'] = $myVelo;
			$this->load->view('velos/single', $this->data);
		} else {
			$this->addData('success', 'Annahme ok.');
			$this->addData('velo', $myVelo);
			$ausweisGezeigt = ('yes' == $myVelo->kein_ausweis) ? 'Nein' : 'Ja';
			$this->addData('ausweisGezeigt', $ausweisGezeigt);
			$velafrika = (1 == $myVelo->afrika) ? 'Ja' : 'Nein';
			$this->addData('velafrika', $velafrika);
			$this->load->view('annahme/einstieg_private', $this->data);
		}

		return;
		} // End of function speichern_private


}
