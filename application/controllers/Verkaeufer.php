<?php
/**
 * Was die privaten VerkäuferInnen so machen
 *
 * @author thoemel@thoemel.ch
 */
class Verkaeufer extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        // User must be logged in.
        $this->requireLoggedIn();
    }


    /**
     * Zeigt die Druckansicht für ein Velo
     * @param int $id
     */
    public function drucken($id) {
        // Prüfen, ob das Velo dem eingeloggten User gehört.
        $myVelo = new Velo();
        $myVelo->find($id);
        if ($myVelo->verkaeufer_id != $this->auth_user_id) {
            $this->session->set_flashdata('error', 'Du darfst nur für deine eigenen Velos drucken.');
            redirect('verkaeufer');
        }

        // Prüfen, ob das Velo den richtigen Status hat
        if ('yes' == $myVelo->angenommen) {
            $this->session->set_flashdata('error', 'Du darfst nicht mehr drucken, wenn das Velo schon drin ist.');
            redirect('verkaeufer');
        }

        $this->addData('myVelo', $myVelo);
        $this->load->view('header', $this->auth_data);
        $this->load->view('verkaeufer/single', $this->data);
        $this->load->view('footer', $this->data);
        return;
    }


    /**
     * Ändert die Daten eines Benutzers.
     */
    public function editUser()
    {
        $this->requireRole('Verkäufer privat');

        if ($this->form_validation->run('editVerkaeufer') === false) {
            // Not registered because of wrong input
            $this->appendData('error', validation_errors());
            $this->userForm();
            return;
        }

        /*
         * Testen, ob ein anderer User diese E-Mail hat.
         */
        $testUser = new M_user();
        $testUser->fetch4email($this->input->post('email'));
        if ($testUser->id != $this->auth_user_id) {
            $this->appendData('error', 'Diese E-Mail ist schon vergeben.');
            $this->userForm();
            return;
        }

        /*
         * Testen, ob ein anderer User diesen Benutzernamen hat (falls username geändert).
         */
        if ($this->auth_username != $this->input->post('username')) {
            $testUser = new M_user();
            $testUser->fetch4username($this->input->post('username'));
            if ($testUser->id !== $this->auth_user_id) {
                $this->appendData('error', 'Dieser Benutzername ist schon vergeben.');
                $this->userForm();
                return;
            }
        }

        $myUser = new M_user();
        $myUser->fetch($this->auth_user_id);
        $myUser->email = $this->input->post('email');
        $myUser->username = $this->input->post('username');
        $myUser->vorname = $this->input->post('vorname');
        $myUser->nachname = $this->input->post('nachname');
        $myUser->strasse = $this->input->post('strasse');
        $myUser->plz = $this->input->post('plz');
        $myUser->ort = $this->input->post('ort');
        $myUser->telefon = $this->input->post('telefon');
        $myUser->iban = $this->input->post('iban');
        if (!empty($this->input->post('password'))) {
            $myUser->set_password($this->input->post('password'));
            $myUser->passwd_modified_at = date('Y-m-d H:i:s');
        }
        $myUser->modified_at = date('Y-m-d H:i:s');


        if($myUser->save()) {
            $this->session->set_flashdata('success', 'Die Angaben wurden gespeichert.');
        } else {
            // Not registered because of technical reason
            $this->session->set_flashdata('error', 'Speichern fehlgeschlagen.');
        }

        redirect('verkaeufer/index');
    } // End of function editUser()


    public function index()
    {
        $meineVelos = Velo::fuerVerkaeufer($this->auth_user_id);
        $this->addData('meineVelos', $meineVelos);
        $ich = new M_user();
        $ich->fetch($this->auth_user_id);
        $this->addData('ich', $ich);

        // Newsletter-Anmelde-Link
        $this->load->config('newsletter_anmeldung');
        $newsletter_html = config_item('newsletter_html');
        if (empty($newsletter_html)) {
            $nl_li = '';
        } else {
            $nl_li = '<li>' . anchor('verkaeufer/newsletter', 'Newsletter abonnieren');
        }
        $this->addData('nl_li', $nl_li);

        $this->load->view('verkaeufer/index', $this->data);
        return;
    }


    /**
     * Verkäufer will ein Velo doch nicht verkaufen...
     * @param int $id
     */
    public function stornieren($id) {
        $myVelo = new Velo();
        try {
            $myVelo->find($id);
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Kein Velo mit dieser ID registriert.');
            redirect('verkaeufer/index');
            return;
        }

        // Nur Velos, die noch nicht durch die Annahme sind, dürfen vom Verkäufer storniert werden
        if ('yes' == $myVelo->angenommen) {
            $this->session->set_flashdata('error', 'Velos, die schon im Verkauf sind, dürfen nicht mehr verändert werden.');
            redirect('verkaeufer/index');
            return;
        }

        // Nur eigene Velos
        if ($myVelo->verkaeufer_id !== $this->auth_user_id) {
            $this->session->set_flashdata('error', 'Sieht so aus, als gehöre das Velo gar nicht dir.');
            redirect('verkaeufer/index');
            return;
        }

        $myVelo->storniert = 1;
        if (true === $myVelo->save()) {
            $this->session->set_flashdata('success', 'Dein Angebot wurde storniert.');
        } else {
            $this->session->set_flashdata('error', 'Da ist etwas schief gelaufen. Melde dich bitte bei Pro Velo.');
        }
        redirect('verkaeufer/index');
        return;
    } // End of function stornieren()


    /**
     * Zeige ein Formular um einen User zu erstellen oder editieren.
     *
     * @param int $user_id
     */
    public function userForm()
    {
        $this->addData('formAction', 'verkaeufer/editUser');

        $myUser = new M_user();
        $myUser->fetch($this->auth_user_id);
        if (!empty(validation_errors())) {
            $myUser->email = set_value('email');
            $myUser->username = set_value('username');
            $myUser->vorname = set_value('vorname');
            $myUser->nachname = set_value('nachname');
            $myUser->strasse = set_value('strasse');
            $myUser->plz = set_value('plz');
            $myUser->ort = set_value('ort');
            $myUser->telefon = set_value('telefon');
            $myUser->iban = set_value('iban');
        }
        $this->addData('myUser', $myUser);


        $this->load->view('login/createUser', $this->data);
    }


    /**
     * Zeige das Erfassungs- bzw. Bearbeitungs-Formular an.
     * TODO Überlegen: Sollen Helfer "keine Provision" wählen können?
     *
     * @param number $id
     */
    public function veloformular($id = 0)
    {
        $id = intval($id);
        $myVelo = new Velo();
        if ($id > 0) {
            try {
                $myVelo->find($id);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                $this->addData('error', 'Es ist kein Velo mit dieser Quittungsnummer registriert.');
                $this->index();
                return ;
            }
        }

        $this->addData('myVelo', $myVelo);
        $this->addData('provisionsliste', Velo::provisionsliste());
        $this->load->view('verkaeufer/veloformular', $this->data);
    }


    public function newsletter() {
        $this->config->load('newsletter_anmeldung');
        $verkaeufy = new M_user();
        $verkaeufy->fetch($this->auth_user_id);
        $this->addData('verkaeufy', $verkaeufy);

        $newsletter_html = config_item('newsletter_html');
        $newsletter_html = str_replace('{email}', $verkaeufy->email, $newsletter_html);
        $newsletter_html = str_replace('{vorname}', $verkaeufy->vorname, $newsletter_html);
        $newsletter_html = str_replace('{nachname}', $verkaeufy->nachname, $newsletter_html);
        $this->addData('newsletter_html', $newsletter_html);

        $this->load->view('verkaeufer/newsletter_anmeldung', $this->data);
        return;
    }


    /**
     * Stellt eine Quittung in einem PDF zum Ausdrucken zusammen.
     * Speichert das PDF auf dem Server unter dem Namen Preisschild_<Quittungsnummer>.pdf
     * Speicherort: Ordner "quittungen".
     *
     * @param int $id   ID des Velos
     * @param bool $outputAsDownload Datei zum Download an den Browser schicken. Default: true
     */
    public function pdf($id, $outputAsDownload = TRUE)
    {
        $myVelo = new Velo();
        try {
            $myVelo->find($id);
        } catch (Exception $e) {
           // Kein Velo mit dieser ID
            $this->session->set_flashdata('error', 'Kein Velo mit dieser ID registriert.');
            redirect('verkaeufer');
            return;
        }

        if ($this->auth_user_id != $myVelo->verkaeufer_id && $this->auth_level < 8) {
            // Eingeloggter User ist entweder Helfer, Admin oder Besitzer des Velos.
            $this->session->set_flashdata('error', 'Dieses Velo gehört nicht dir.');
            redirect('verkaeufer');
            return;
        }


        $this->load->library('pv_tcpdf');
        $pdf = new Pv_tcpdf('P');
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, -1, TRUE);
        $pdf->AddPage();

        $barcodeStyle = array(
            'position' => 'L',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );


        /*
         * Obere Seitenhälfte für ans Velo
         */
        $topOfReceipt = [$pdf->getX(), $pdf->GetY()];

        // Logo
        $pdf->Image(FCPATH . '/img/logo.png', $pdf->GetX(), $pdf->GetY(), 0, 10.0, 'png', '', 'M', true, 300, 'R');

        // Barcode
        $pdf->SetXY($topOfReceipt[0], $topOfReceipt[1]);
        $pdf->write1DBarcode($myVelo->id, 'C128A', '', '', '', 16, 0.4, $barcodeStyle);

        // Bild
        $pdf->Ln(10);
        $bildOberkante = [$pdf->GetX(), $pdf->GetY()];
        if (!empty($myVelo->img)) {
            if (file_exists(FCPATH . 'uploads/' . $myVelo->img)) {
                $imgType = substr($myVelo->img, (strrpos($myVelo->img, '.')+1));
                $pdf->Image(FCPATH . 'uploads/' . $myVelo->img, $pdf->GetX(), $pdf->GetY(), 0, 50, '', $imgType, 'B', true, 300, 'L');
            }
        }

        // Preis
        $pdf->Ln();
        $pdf->SetFont('', 'B', 64);
        $pdf->SetTextColor(0,0,0);
        $preisText = 'Fr. ' . $myVelo->preis . '.--';
        $pdf->Write(0, $preisText, '', false, 'L', true);
        $preisUnterkante = [$pdf->GetX(), $pdf->GetY()];

        // Rechtlicher Hinweis
        $pdf->SetFont('', '', 6);
        $gewaehrleistung = config_item('gewaehrleistung');
        $pdf->write(0, $gewaehrleistung, '', false, 'L', true);

        // Quittungs-Nr.
        $pdf->SetXY($bildOberkante[0], $bildOberkante[1] + 5);
        $title = 'Velo Nr: ' . $id;
        $pdf->SetFont('', 'B', 10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Write(0, $title, '', false, 'R', true);

        // Marke
        $pdf->SetFont('', '', 10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Write(0, 'Marke: ' . $myVelo->marke, '', false, 'R', true);

        // Farbe
        $pdf->SetFont('', '', 10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Write(0, 'Farbe: ' . $myVelo->farbe, '', false, 'R', true);

        // Rahmennummer
        $pdf->SetFont('', '', 10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Write(0, 'Rahmennummer: ' . $myVelo->rahmennummer, '', false, 'R', true);

        // Verkäufer
        $pdf->Ln();
        $pdf->SetFont('', 'B', 10);
        $pdf->write(0, 'Verkäufer:', '', false, 'R', true);
        $pdf->SetFont('', '', 10);
        $vi = $myVelo->verkaeuferInfo();
        $pdf->write(0, $vi['vorname'] . ' ' . $vi['nachname'], '', false, 'R', true);
        $pdf->write(0, $vi['strasse'], '', false, 'R', true);
        $pdf->write(0, $vi['plz'] . ' ' . $vi['ort'], '', false, 'R');
        $pdf->Ln(10);

        // Börsendatum
        $pdf->SetTextColor(0,0,0);
        $pdf->Write(0, 'Datum: ' . date('d. m. Y'), '', false, 'R', true);

        // Velafrica
        if ($myVelo->afrika == 1) {
            $pdf->Image(FCPATH . '/img/velafrica.ch_logo_de.png', $pdf->GetX(), 120, 0, 10.0, 'png', '', 'M', true, 300, 'R');
        }

        // Horizontale Linie plus Abstand
        $Seitenmitte = 144;
        $pdf->setY($Seitenmitte);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Cell(0,1,'','B',1);


        /*
         * Untere Seitenhälfte für Verkäufer
         */
        $topOfReceipt = 150;

        // "Kopie für den Verkäufer"
        $pdf->SetY($topOfReceipt);
        $pdf->SetFillColor(192, 192, 255);
        $pdf->SetFont('', 'B', 24);
        $pdf->SetTextColor(0,0,0);
        $preisText = 'Kopie für Verkäufer';
        $pdf->Write(0, $preisText, '', true, 'C', true);

        // Logo
        $pdf->Image(FCPATH . '/img/logo.png', $pdf->GetX(), $pdf->GetY(), 0, 10.0, 'png', '', 'M', true, 300, 'R');

        // Bild
        $bildOberkante = [$pdf->GetX(), $pdf->GetY() + 5];
        if (!empty($myVelo->img)) {
            if (file_exists(FCPATH . 'uploads/' . $myVelo->img)) {
                $imgType = substr($myVelo->img, (strrpos($myVelo->img, '.')+1));
                $pdf->Image(FCPATH . 'uploads/' . $myVelo->img, $pdf->GetX(), $bildOberkante[1], 0, 50, '', $imgType, 'B', true, 300, 'L');
            }
        }

        // Preis
        $pdf->Ln();
        $pdf->SetFont('', 'B', 36);
        $pdf->SetTextColor(0,0,0);
        $preisText = 'Fr. ' . $myVelo->preis . '.--';
        $pdf->Write(0, $preisText, '', false, 'L', true);

        // Auszahlung
        $pdf->SetFont('', '', 24);
        $pdf->SetTextColor(0,0,0);
        $preisText = 'Auszahlung: Fr. ' . ($myVelo->preis - $myVelo->getProvision($myVelo->preis)) . '.--';
        $pdf->Write(0, $preisText, '', false, 'L', true);
        $preisUnterkante = [$pdf->GetX(), $pdf->GetY()];

        // Quittungs-Nr.
        $pdf->SetXY($bildOberkante[0], $bildOberkante[1]);
        $pdf->Ln(5);
        $title = 'Velo Nr: ' . $id;
        $pdf->SetFont('', 'B', 10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Write(0, $title, '', false, 'R', true);

        // Marke
        $pdf->SetFont('', '', 8);
        $pdf->SetTextColor(0,0,0);
        $pdf->Write(0, 'Marke: ' . $myVelo->marke, '', false, 'R', true);

        // Farbe
        $pdf->SetFont('', '', 8);
        $pdf->SetTextColor(0,0,0);
        $pdf->Write(0, 'Farbe: ' . $myVelo->farbe, '', false, 'R', true);

        // Rahmennummer
        $pdf->SetFont('', '', 8);
        $pdf->SetTextColor(0,0,0);
        $pdf->Write(0, 'Rahmennummer: ' . $myVelo->rahmennummer, '', false, 'R', true);

        // Verkäufer
        $pdf->Ln();
        $pdf->SetFont('', 'B', 8);
        $pdf->write(0, 'Verkäufer:', '', false, 'R', true);
        $pdf->SetFont('', '', 8);
        $vi = $myVelo->verkaeuferInfo();
        $pdf->write(0, $vi['vorname'] . ' ' . $vi['nachname'], '', false, 'R', true);
        $pdf->write(0, $vi['strasse'], '', false, 'R', true);
        $pdf->write(0, $vi['plz'] . ' ' . $vi['ort'], '', false, 'R');
        $pdf->Ln(10);

        $pdf->SetFont('', 'B', 8);
        $pdf->write(0, 'Auszahlung bestätigen:', '', false, 'R', true);

        // Börsendatum
        $pdf->SetFont('', '', 8);
        $pdf->Write(0, 'Datum: ' . date('d. m. Y', strtotime(Boerse::naechsteOffene()->datum)), '', false, 'R', true);

        $pdf->SetFont('', '', 8);
        $pdf->write(0, 'Verkaufserlös erhalten:', '', false, 'R', true);


        $pdf->Ln(10);
        $pdf->SetFont('', '', 8);
        $pdf->write(0, 'Unterschrift _____________________________', '', false, 'R', true);

        // Barcode
        $pdf->Ln(5);
        $barcodeStyle['position'] = 'R';
        $barcodeStyle['align'] = 'R';
        $barcodeStyle['hpadding'] = 0;
        $barcodeStyle['vpadding'] = 1;
        $pdf->write1DBarcode($myVelo->id, 'C128A', '', '', '', 16, 0.4, $barcodeStyle);

        // Rechtlicher Hinweis
        $pdf->SetY(261);
        $pdf->SetFont('', 'B', 6);
        $pdf->write(0, 'Rechtlicher Hinweis:', '', false, 'L', true);
        $pdf->SetFont('', '', 6);
        $Hinweis = config_item('haftungsausschluss');
        $pdf->write(0, $Hinweis, '', false, 'L', true);


        $pdf->Output($this->quittungspfad($myVelo->id), 'F');

        if ($outputAsDownload) {
            $filename = 'Preisschild_' . $id . '.pdf';
            $pdf->Output($filename, 'D');
        }

        return ;
    } // End of function pdf()


    /**
     * Gib den Serverpfad für meine Quittung
     *
     * @param int $id Quittungsnummer
     * @return String $strOut Absoluten Pfad der Quittung mit dieser Nummer
     */
    private function quittungspfad($id) {
        $strOut = '';
        $filename = 'Preisschild_' . $id . '.pdf';
        $strOut = realpath(__DIR__ . '/../../quittungen/') . '/' . $filename;
        return $strOut;
    }


    /**
     * Verkäufer hat das Formular ausgefüllt (neu oder bearbeitet).
     */
    public function speichereVelo()
    {
        // Require user to be logged in.
        $this->requireLoggedIn();

        $this->form_validation->set_message('matches', 'Die rechtlichen Hinweise müssen akzeptiert werden.');

        // form validation
        if ($this->form_validation->run('veloErfassenVerkaeufer') === false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('verkaeufer/veloformular/'.$this->input->post('id'));
            return;
        }

        // Preis in ganzen Zehnern
        $preis = round($this->input->post('preis')/10) * 10;

        $myVelo = new Velo();
        try {
            $myVelo->find($this->input->post('id'));
        } catch (Exception $e) {
            $myVelo->id = 0;
        }
        $myVelo->verkaeufer_id = $this->auth_user_id;
        $myVelo->angenommen = 'no';
        $myVelo->afrika				= $this->input->post('velafrika');
        $myVelo->farbe				= $this->input->post('farbe');
        $myVelo->marke				= $this->input->post('marke');
        $myVelo->preis				= $preis;
        $myVelo->rahmennummer		= $this->input->post('rahmennummer');
        $myVelo->typ				= $this->input->post('typ');

        /*
         * Foto Upload
         */
        if (0 < $_FILES['userfile']['size']) {
            $config['upload_path']          = './uploads/';
            $config['allowed_types']        = 'gif|jpg|jpeg|png';
            $config['encrypt_name']        = TRUE;

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('userfile')) {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('verkaeufer/veloformular/'.$this->input->post('id'));
                return;
            } else {
                $myVelo->img = $this->upload->data('file_name');
            }

            // Rescale to save bandwidth
            $img = NULL;
            $imgPath = $this->upload->data('full_path');
            $type = $this->upload->data('image_type');
            switch ($type) {
                case 'gif':
                    $img = imagecreatefromgif($imgPath);
                    break;
                case 'jpg':
                case 'jpeg':
                    $img = imagecreatefromjpeg($this->upload->data('full_path'));
                    break;
                case 'png':
                    $img = imagecreatefrompng($this->upload->data('full_path'));
                    break;
                default:
                    $img = imagecreate(600, 400);
                break;
            }
            $scaledImg = imagescale($img, 600);
            imagedestroy($img);
            $saveSuccess = false;
            switch ($type) {
                case 'gif':
                    $saveSuccess = imagegif($scaledImg, $imgPath);
                    break;
                case 'jpg':
                case 'jpeg':
                    $saveSuccess = imagejpeg($scaledImg, $imgPath);
                    break;
                case 'png':
                    $saveSuccess = imagepng($scaledImg, $imgPath);
                    break;
                default:
            }
            if (!$saveSuccess) {
                $this->session->set_flashdata('error', 'Bild konnte nicht verkleinert werden.');
                redirect('verkaeufer/veloformular/'.$this->input->post('id'));
                return;
            }

        }

        // TODO Foto Rahmennummer

        $myVelo->save();

        /*
         * Mail mit Quittung schicken
         */
        $verkaeufy = new M_user();
        $verkaeufy->fetch($myVelo->verkaeufer_id);
        $this->config->load('email', 'forgot_pw');
        $mailConfig = config_item('forgot_pw');
        $mailConfig['smtp_host'] = config_item('smtp_host');
        $mailConfig['smtp_adress'] = config_item('smtp_adress');
        $mailConfig['smtp_name'] = config_item('smtp_name');
        $mailConfig['smtp_user'] = config_item('smtp_user');
        $mailConfig['smtp_pass'] = config_item('smtp_pass');
        $mailConfig['smtp_port'] = config_item('smtp_port');
        $this->load->library('email');
        $this->email->initialize($mailConfig);

        $this->email->from(config_item('smtp_adress'), config_item('smtp_name'));
        $this->email->to($verkaeufy->email);
        $this->email->subject('Dein Velo ist registriert');

        $msg = 'Liebe/lieber ' . $verkaeufy->vorname . ' ' . $verkaeufy->nachname;
        $msg .= "\n\nDanke, dass du dein Velo bei uns verkaufen willst!";
        $msg .= "\nBitte drucke die Quittung in der Beilage aus und bring sie mit dem Velo an die Börse.";
        $msg .= "\nLiebe Grüsse";
        $msg .= "\n\nDeine Pro Velo";
        $this->email->message($msg);

        $this->pdf($myVelo->id, FALSE);
        $this->email->attach($this->quittungspfad($myVelo->id));

        $success = $this->email->send(FALSE);
        if (!$success) {
            log_message('error', $this->email->print_debugger());
            // Keine weiteren Aktionen hier.
        }


        redirect('verkaeufer/index');

        return;
    } // End of function speichereVelo
}