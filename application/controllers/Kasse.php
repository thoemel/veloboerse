<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kasse extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		// All methods require user to be logged in.
		$this->requireLoggedIn();
	}


	/**
	 * Zeigt gewisse für die Kasse relevante Details an, damit die kontrollieren können, ob das
	 * Velo mit der Quittung übereinstimmt.
	 * Namentlich Preis und Foto werden angezeigt.
	 *
	 * @param int  $id Quittungsnummer. Im Normalfall via Post Variable übergeben, bei Form-Redirect aber Get.
	 */
	public function kontrollblick($id = '')
	{
	    if (!empty($id)) {
	      $quittungNr = $id;
	    } else {
		  $quittungNr = $this->input->post('id');
	    }

		// Prüfung, ob Velo überhaupt registriert ist.
		if (!Velo::istRegistriert($quittungNr)) {
			$this->session->set_flashdata('error', 'Keine gültige Quittungsnummer');
			redirect('kasse/index');
			return;
		}

		$myVelo = new Velo();
		$myVelo->find($quittungNr);

		// Velo darf nicht mehr verkauft werden, wenn schon abgeholt
		if ('yes' == $myVelo->abgeholt) {
			$this->session->set_flashdata('error', 'Hilfe! <br>Hol den Thoemel! <br>Das Velo ist als "abgeholt" registriert - das muss dringend geklärt werden!');
			redirect('kasse/index');
			return;
		}

		// Velo nicht ein zweites Mal verkaufen
		if ('yes' == $myVelo->verkauft) {
			$this->session->set_flashdata('error', 'Hilfe! <br>Hol den Thoemel! <br>Das Velo ist als "verkauft" registriert - das muss dringend geklärt werden!');
			redirect('kasse/index');
			return;
		}
		$this->addData('velo', $myVelo);
		$this->addData('hideNavi', true);

		$this->load->view('kasse/kontrollblick', $this->data);
	}


	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->load->view('kasse/formular', $this->data);
	}


	/**
	 * Schliesst den Verkauf ab.
	 * @uses	POST vars
	 */
	public function verkaufe()
	{
	    // form validation
	    if ($this->form_validation->run('kasse') === false) {
	        $this->session->set_flashdata('error', validation_errors());
	        redirect('kasse/kontrollblick/' . $this->input->post('id'));
	        return;
	    }

		$myVelo = new Velo();
		$quittungNr = $this->input->post('id');
		try {
			$myVelo->find($quittungNr);
		} catch (Exception $e) {
			$this->session->set_flashdata('error', 'Das Velo ist nicht registriert. Verkauf fehlgeschlagen.');
			redirect();
		}

		if ('yes' == $myVelo->verkauft) {
			$this->session->set_flashdata('error', 'Das Velo wurde schon früher verkauft. Verkauf fehlgeschlagen.');
			redirect();
		}

		if (false !== $this->input->post('bemerkungen')) {
			$myVelo->bemerkungen = $this->input->post('bemerkungen');
		}

		$myVelo->verkauft = 'yes';
		$myVelo->zahlungsart = $this->input->post('zahlungsart');
		$myVelo->helfer_kauft = ('yes' == $this->input->post('helfer_kauft')) ? 'yes' : 'no';

		if ($myVelo->save()) {
			$this->data['success'] = 'Verkauf wurde registriert :-)';
			$this->data['velo'] = $myVelo;
			$vonHelferGekauft = ('yes' == $myVelo->helfer_kauft) ? 'Ja' : 'Nein';
			$this->addData('vonHelferGekauft', $vonHelferGekauft);

			// Mail an Verkäufy
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

			$this->email->subject('Dein Velo wurde verkauft');
			$msg = 'Gratuliere, du hast ein Velo verkauft!';
			$msg .= '
Dein Velo mit der Quittung Nr. ' . $myVelo->id . ' wurde für Fr. ' . $myVelo->preis . '.-- verkauft.';
if (empty($verkaeufy->iban)) {
    $msg .= '
Du musst deinen Erlös vor Börsenschluss abholen kommen.';
} else {
    $msg .= '
Der Erlös wird dir in den nächsten Tagen auf dein Konto überwiesen.';
}
            $msg .= '
Liebe Grüsse
Deine Pro Velo';
			$msg = $msg;
			$this->email->message($msg);

			$success = $this->email->send(FALSE);
			if (!$success) {
			    log_message('error', $this->email->print_debugger());
			    // Keine weiteren Aktionen hier.
			}
		} else {
			$this->data['error'] = 'Verkauf nicht geklappt.';
		}


		$this->load->view('kasse/formular', $this->data);
	} // End of function verkaufe()


	/**
	 * Falls jemand den Verkauf nicht abschliesst und schon eine neue Quittung scannt,
	 * kommt er hierher (Fokus ist auf einem andern Formular.
	 * Wir leiten ihn weiter zur Kontrollblick-Methode, allerdings mit einer Warnmeldung.
	 */
	public function verklickt()
	{
		$this->addData('quittungNr', $this->input->post('id'));
		$this->load->view('kasse/verklickt', $this->data);
		return;
	} // End of function verklickt()


} // End of class Kasse
