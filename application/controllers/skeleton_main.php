<?php defined('BASEPATH') OR exit('No direct script access allowed');


class skeleton_main extends CI_Controller {
	
	function __construct()
    {
		log_message('debug', 'prova 0');
        parent::__construct();
        log_message('debug', 'prova 01');
        
        $this->load->add_package_path(APPPATH.'third_party/skeleton_auth/application/');
        log_message('debug', 'prova 02');
    	$params = array('model' => "skeleton_auth_model");
		$this->load->library('skeleton_auth',$params);
		
		//CONFIG skeleton_auth library:
		$this->skeleton_auth->login_page="skeleton_auth/auth/login";
		
		log_message('debug', 'prova 03');
        
        //Helpers
        $this->load->helper('url');

        
	}
	
	public function index() {
		log_message('debug', 'prova 1');
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		//redirect($this->skeleton_auth->login_page, 'refresh');
		
		echo '<a href="http://localhost/skeleton/index.php/skeleton_auth/auth/login">logout</a>';
	}
		
}
