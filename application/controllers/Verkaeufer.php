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


    public function index()
    {
        $this->load->view('verkaeufer/index', $this->data);
        return;
    }
}