<?php
/**
 * Erledigt das registrieren von Benutzerzugriffen.
 * Ursprünglicher Zweck ist, dass bei der Fehlersuche nach Errormails die Geschichte des 
 * Besuchs rekonstruiert werden kann.
 */
class Statistik extends CI_Model {

    public function __construct($id = 0)
    {
        parent::__construct();
    }
    
    
    /**
     * Registriert einen Request
     * 
     */
    public static function registriere()
    {
    	$CI =& get_instance();
        
    	$CI->db->set('zeitpunkt', date('Y-m-d H:i:s'));
    	$CI->db->set('url', current_url());
    	if (!empty($_POST)) {
    		$CI->db->set('post_json', json_encode($_POST));
    	}
    	$CI->db->set('user_id', $CI->session->userdata('user_id'));
    	$CI->db->set('user_agent', $CI->session->userdata('user_agent'));
    	$CI->db->insert('statistik');
    	
    	return;
    }
}
