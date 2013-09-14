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
        
        //Languages
        $this->lang->load('skeleton');

	}
	
	
	
	
	protected function _load_html_header($html_header_data=array()) {
		
		$data=array();
		
		//print_r($html_header_data);
		
		$header_title = array_key_exists("header_title",$html_header_data) ? $html_header_data['header_title'] : $this->config->item('header_title', 'skeleton_auth');
		$header_description = array_key_exists("header_description",$html_header_data) ? $html_header_data['header_description'] : $this->config->item('header_description', 'skeleton_auth');
		$header_authors = array_key_exists("header_authors",$html_header_data) ? $html_header_data['header_authors'] : $this->config->item('header_authors', 'skeleton_auth');
		
		$skeleton_css_files = array_key_exists("skeleton_css_files",$html_header_data) ? $html_header_data['skeleton_css_files'] : array();
		$skeleton_js_files = array_key_exists("skeleton_js_files",$html_header_data) ? $html_header_data['skeleton_js_files'] : array();
		
		$data['header_title'] = $header_title ;
		$data['header_description'] = $header_description ;
		$data['header_authors'] = $header_authors ;
		$data['skeleton_css_files'] = $skeleton_css_files ;
		$data['skeleton_js_files'] = $skeleton_js_files ;
		
		$this->load->view('include/html_header',$data);
	}
	
	protected function _load_body_header() {
		$data=array();
		
		$data['body_header_app_name']="Skeleton";
		$this->load->view('include/body_header',$data);
	}
	
	protected function _load_body() {
		$data=array();
		$this->load->view('include/body',$data);		
	}
	
	protected function _load_body_footer() {
		$data=array();
		$data['body_footer_entity_url'] = "http://www.ebretic.com/";
		$data['body_footer_entity_url_name'] = "www.ebretic.com";
		$data['body_footer_entity_name'] = "EBRETIC ENGINYERA SL";
		$data['body_footer_entity_image_url'] = base_url('assets/img/ebretic_100x36.jpg');
		$data['body_footer_copyright_date'] = "2013";
		$data['body_footer_wiki_url'] = "http://acacha.org/mediawiki/index.php/skeleton";
		$data['body_footer_github_url'] = "https://github.com/acacha/skeleton";
		$data['body_footer_authors'] = '<a href="http://acacha.org">Sergi Tur Badenas</a>';

		$this->load->view('include/body_footer',$data);
	}
	
	
	public function index() {
		log_message('debug', 'prova 1');
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		//redirect($this->skeleton_auth->login_page, 'refresh');
		
		//LOAD VIEW
		
		/*******************
		/*      HEADER     *
		/******************/
		
		$header_data['header_title']="Títol a provar!";	
		$skeleton_css_files=array();
		
		$bootstrap_min=base_url("assets/css/bootstrap.min.css");
		$bootstrap_responsive=base_url("assets/css/bootstrap-responsive.min.css");
		$font_awesome=base_url("assets/css/font-awesome.css");
		
		/*
		<link href="http://localhost/ebre-inventory/assets/css/font-awesome.css" rel="stylesheet" type="text/css">
<link href="http://localhost/ebre-inventory/assets/css/custom.css" rel="stylesheet" type="text/css">
<link href="http://localhost/ebre-inventory/assets/css/jquery.multiselect.css" rel="stylesheet" type="text/css">
<link href="http://localhost/ebre-inventory/assets/grocery_crud/themes/flexigrid/css/flexigrid.css" rel="stylesheet" type="text/css">
<link href="http://localhost/ebre-inventory/assets/grocery_crud/css/jquery_plugins/chosen/chosen.css" rel="stylesheet" type="text/css">
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css">
* */
		
		array_push($skeleton_css_files, $bootstrap_min, $bootstrap_responsive,$font_awesome);
		$header_data['skeleton_css_files']=$skeleton_css_files;			
		
		$skeleton_js_files=array();
		
		$lodash_js="http://cdnjs.cloudflare.com/ajax/libs/lodash.js/1.2.1/lodash.min.js";
		$jquery_js="http://code.jquery.com/jquery-1.10.2.min.js";
		$bootstrap_js=base_url("assets/js/bootstrap.min.js");
		
		array_push($skeleton_js_files, $lodash_js ,$jquery_js , $bootstrap_js);
		$header_data['skeleton_js_files']=$skeleton_js_files;	
		
		/*
		 <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/1.2.1/lodash.min.js">
<script src="http://localhost/ebre-inventory/assets/js/bootstrap.min.js">
<script src="http://localhost/ebre-inventory/assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js">
<script src="http://localhost/ebre-inventory/assets/grocery_crud/js/common/lazyload-min.js">
<script src="http://localhost/ebre-inventory/assets/js/jquery-ui.min.js">
<script src="http://localhost/ebre-inventory/assets/js/jquery-chosen-sortable.js">
* 
		 * */		
		
		$this->_load_html_header($header_data);
		
		
		/*******************
		/*      BODY     *
		/******************/
		$this->_load_body_header();
		
		$this->_load_body();
		
		 
		/*******************
		/*      FOOTER     *
		*******************/
		$this->_load_body_footer();		
	}
		
}
