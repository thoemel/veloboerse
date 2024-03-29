<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Velos extends MY_Controller {


	public function __construct()
	{
		parent::__construct();

	}


	/**
	 * Der Einstieg zum Veloformular. Hier wird nur ein Default-Text angezeigt.
	 */
	public function einstieg()
	{
		// Require user to be logged in.
		$this->requireLoggedIn();

		$this->load->view('velos/einstieg', $this->data);
		return;
	}


	/**
	 * Formulardaten entgegennehmen und verarbeiten
	 */
	public function erfasse()
	{
		// Require user to be logged in.
		$this->requireLoggedIn();

		// form validation
		if ($this->form_validation->run('veloFormular') === false) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('velos/formular/'.$this->input->post('id'));
			return;
		}

		$myVelo = new Velo();
		try {
			$myVelo->find($this->input->post('id'));
		} catch (Exception $e) {
			$myVelo->id = $this->input->post('id');
		}
		$myVelo->abgeholt			= $this->input->post('abgeholt');
		$myVelo->afrika				= $this->input->post('velafrika');
		$myVelo->angenommen			= $this->input->post('angenommen');
		$myVelo->ausbezahlt			= $this->input->post('ausbezahlt');
		$myVelo->bemerkungen		= $this->input->post('bemerkungen');
		$myVelo->gestohlen			= $this->input->post('gestohlen');
		$myVelo->farbe				= $this->input->post('farbe');
		$myVelo->haendler_id		= $this->input->post('haendler_id');
		$myVelo->helfer_kauft		= false === $this->input->post('helfer_kauft') ? 'no' : $this->input->post('helfer_kauft');
		$myVelo->kein_ausweis		= false === $this->input->post('kein_ausweis') ? 'no' : $this->input->post('kein_ausweis');
		$myVelo->keine_provision	= false == $this->input->post('keine_provision') ? 'no' : $this->input->post('keine_provision');
		$myVelo->marke				= $this->input->post('marke');
		$myVelo->preis				= $this->input->post('preis');
		$myVelo->problemfall		= $this->input->post('problemfall');
		$myVelo->rahmennummer		= $this->input->post('rahmennummer');
		$myVelo->storniert			= $this->input->post('storniert');
		$myVelo->typ				= $this->input->post('typ');
		$myVelo->vignettennummer	= $this->input->post('vignettennummer');
		$myVelo->verkauft			= $this->input->post('verkauft');
		$myVelo->zahlungsart		= false === $this->input->post('zahlungsart') ? 'no': $this->input->post('zahlungsart');

		$myVelo->save();

		$this->addData('myVelo', $myVelo);
		$this->load->view('velos/single', $this->data);

		return;
	} // End of function erfasse


	/**
	 * Zeigt das Erfassungsformular an.
	 *
	 * @param int	$id		Quittungsnummer. Falls eine angegeben, wird das Formular vorausgefüllt. Kann auch über Post-Variable kommen.
	 */
	public function formular($id = '')
	{
		// Require user to be logged in.
		$this->requireLoggedIn();

		$id = intval($id);
		if ($this->input->post('id')) {
			$id = $this->input->post('id');
		}
		$myVelo = new Velo();
		try {
			$myVelo->find($id);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			$this->addData('error', 'Es ist kein Velo mit dieser Quittungsnummer registriert.');
			$this->einstieg();
			return ;
		}

		// Händler Dropdown
		$haendlerQuery = Haendler::getAll();
		$arrHaendler = array(0 => 'privat');
		foreach ($haendlerQuery->result() as $row) {
			$arrHaendler[$row->id] = $row->id . ' - ' . $row->firma . ' | ' . $row->person;
		}
		$this->addData('haendlerDropdown', form_dropdown('haendler_id', $arrHaendler, $myVelo->haendler_id, 'id="haendler_id" class="form-control"'));

		$this->data['myVelo'] = $myVelo;

		if ($myVelo->haendler_id > 0) {
			$haendler = new Haendler();
			$haendler->find($myVelo->haendler_id);
			$this->addData('haendler', $haendler);;
		}

		// Verkäufy-Info
		if ($myVelo->verkaeufer_id > 0) {
		    $verkaeuferInfo = $this->load->view('verkaeufer/verkaeuferinfo', [
		        'verkaeuferInfo'=>$myVelo->verkaeuferInfo(),
		        'verkaeufer_id'=> $myVelo->verkaeufer_id
		    ], TRUE);
		    $this->addData('verkaeuferInfo', $verkaeuferInfo);
		}

		$provisionsliste = Velo::provisionsliste();
		$tstest = json_encode($provisionsliste);
		$this->data['provisionsliste'] = $provisionsliste;

		$this->load->view('velos/formular', $this->data);
	}


    /**
     * Eine Liste aller Velos eines Verkäufys
     *
     * @param int $verkaeufy_id
     */
	public function fuerVerkaeufy($verkaeufy_id) {
	    $verkaeufy = new M_user();
	    try {
	        $verkaeufy->fetch($verkaeufy_id);
	    } catch (Exception $e) {
	        $this->session->set_flashdata('error', 'kein Verkäufy mit dieser ID.');
	        redirect();
	        return;
	    }
	    $this->addData('verkaeufy', $verkaeufy);
	    $this->addData('meineVelos', Velo::fuerVerkaeufer($verkaeufy->id));
	    $this->addData('showFormLink', $this->verify_role('admin', 'Helfer'));

	    $this->load->view('velos/liste', $this->data);
	    return;
	}


	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		// Require user to be logged in.
		$this->requireLoggedIn();

		$this->listing();
	}


	/**
	 * Liste aller Velos
	 * @deprecated
	 */
	public function listing()
	{
		// Require user to be logged in.
		$this->requireLoggedIn();

		$this->data['liste'] = Velo::getAll();
		$this->load->view('velos/liste', $this->data);
	}


	/**
	 * Sucht nach einem Velo in der DB.
	 *
	 * @param	int	$id	Quittungsnummer
	 */
	public function suche($id = '')
	{
		if ($this->input->post('id')) {
			$searchId = $this->input->post('id');
		} else {
			$searchId = intval($id);
		}

		try {
			$this->data['myVelo'] = $this->velo->find($searchId);
			$this->load->view('velos/forClient', $this->data);
		} catch (Exception $e) {
			$this->load->view('velos/notFound', $this->data);
		}


	}
}
