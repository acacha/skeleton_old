<?php defined('BASEPATH') OR exit('No direct script access allowed');


class skeleton_main extends CI_Controller {
	
	function __construct()
    {
        parent::__construct();
        
        $this->load->add_package_path(APPPATH.'third_party/skeleton_auth/application/');
    	$params = array('model' => "skeleton_auth_model");
		$this->load->library('skeleton_auth',$params);
        
        
	}
	
	public function index() {
		
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			//redirect($this->skeleton_auth->login_page, 'refresh');
			echo "No tens accés!";
		}
		echo "Hello World!";
	}
		
}
