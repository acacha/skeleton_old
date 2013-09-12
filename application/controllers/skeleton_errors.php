<?php defined('BASEPATH') OR exit('No direct script access allowed');


class skeleton_errors extends CI_Controller {
	
	function __construct()
    {
        parent::__construct();
        
        //LOAD GLOBAL SKELETON CONFIG
        //$this->config->load('inventory');
 
        $this->load->helper('url');
   		//$this->load->library('session');  

        $this->load->add_package_path(APPPATH.'third_party/skeleton_auth/application/');

		//$params = array('model' => "inventory_ion_auth_model");
		//$this->load->library('ion_auth',$params);
		
        //Localization:
        //$this->lang->load('inventory', 'catalan');	       
        //$this->load->helper('language');
 
    }
    
    /*
     * function _get_rolename_byId($id){

		$roles = (array) $this->config->item('roles');		
		return $roles[(int) $id];
	}
	* */
	
	public function load_header($not_show_header = true){

        /*
        $data['not_show_header2']=$not_show_header;
        
        $data['current_role_id']   = $this->session->userdata('role');
        
        $show_maintenace_menu=true;
        
        if ($data['current_role_id'] == "") {
			$show_maintenace_menu=false;
		} else { 
			$data['current_role_name'] = $this->_get_rolename_byId($data['current_role_id']);
			if ($data['current_role_name'] == $this->config->item('organizationalunit_group') )
				$show_maintenace_menu=false;
		}
        
        $data['show_maintenace_menu'] =$show_maintenace_menu;

        $data['inventory_js_files'] = array(
            '/javascript/jquery/jquery.min.js',
            base_url('assets/js/bootstrap.min.js')
            );
        $data['inventory_css_files'] = array(
            base_url('assets/css/bootstrap.min.css'),
            base_url('assets/css/bootstrap-responsive.min.css'),
            base_url('assets/css/font-awesome.css')
            );                 
            
        
        $data['institution_name'] = $this->config->item('institution_name');
        //TODO: use real user name		
        
        $data['usuari']=$this->session->userdata('session_id');
        
        $this->load->view('include/header',array_merge($data));
                   */ 
    }
    
	
	
	function error404()	{
		echo "Error 404!";
		/*
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->login_page, 'refresh');
		}
		$this->load_header();
        $this->load->view('404.php');        
        $this->load->view('include/footer');   	
        * */
	}
	
	function tablenotfound()	{
		$this->load_header();
        $this->load->view('tablenotfound.php');        
        $this->load->view('include/footer');   	
	}
	
	


}
