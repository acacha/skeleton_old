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
		
		// TODO: check if role permit to show management menu
		$data['show_managment_menu']=true;
		
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
	
	public function change_language($language) {
		$this->session->set_userdata('current_language', $language);
		redirect($_SERVER[‘HTTP_REFERER’]);
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
	
	public function users() {
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->login_page, 'refresh');
		}
		//CHECK IF USER IS READONLY --> unset add, edit & delete actions
		$readonly_group = $this->config->item('readonly_group');
		if ($this->skeleton_auth->in_group($readonly_group)) {
			$this->grocery_crud->unset_add();
			$this->grocery_crud->unset_edit();
			$this->grocery_crud->unset_delete();
	    }
		$user_groups = $this->skeleton_auth->get_users_groups($this->session->userdata('user_id'))->result();
		
		$table_name="users";
	    $this->current_table=$table_name;
        $this->grocery_crud->set_table($this->current_table);  
        
        $this->grocery_crud->add_fields('first_name','last_name','username','password','verify_password','mainOrganizationaUnitId','email','active','company','phone','groups','created_on','ip_address');
        $this->grocery_crud->edit_fields('first_name','last_name','username','password','verify_password','mainOrganizationaUnitId','email','active','company','phone','groups','last_login','ip_address');
        

        //CHECK IF STATE IS UPDATE o UPDATE_VALIDATION
        $state = $this->grocery_crud->getState();
        if ($state == "update" || $state == "update_validation" || $state == "edit") {
			$this->grocery_crud->required_fields('username','email','active','groups');
			$this->grocery_crud->set_rules('password', lang('password'), 'min_length[' . $this->config->item('min_password_length', 'skeleton_auth') . ']|max_length[' . $this->config->item('max_password_length', 'skeleton_auth') . ']|md5');
			$this->grocery_crud->set_rules('verify_password', lang('verify_password'), 'matches[password]');
		} else {
			$this->grocery_crud->required_fields('username','password','verify_password','email','active','groups');
			$this->grocery_crud->set_rules('password', lang('password'), 'required|min_length[' . $this->config->item('min_password_length', 'skeleton_auth') . ']|max_length[' . $this->config->item('max_password_length', 'skeleton_auth') . ']|md5');
			$this->grocery_crud->set_rules('verify_password', lang('verify_password'), 'required|matches[password]');
		}

        //Establish subject:
        $this->grocery_crud->set_subject(lang('users_subject'));
        
        //COMMON_COLUMNS               
        $this->set_common_columns_name();
        
        //ESPECIFIC COLUMNS                                            
        $this->grocery_crud->display_as('verify_password',lang('verify_password'));
        $this->grocery_crud->display_as('mainOrganizationaUnitId',lang('MainOrganizationaUnitId'));
        $this->grocery_crud->display_as('ip_address',lang('ip_address'));
        $this->grocery_crud->display_as('username',lang('username')); 
        $this->grocery_crud->display_as('password',lang('Password')); 
        $this->grocery_crud->display_as('email',lang('email'));
        $this->grocery_crud->display_as('activation_code',lang('activation_code'));
        $this->grocery_crud->display_as('forgotten_password_code',lang('forgotten_password_code'));
        $this->grocery_crud->display_as('forgotten_password_time',lang('forgotten_password_time'));
        $this->grocery_crud->display_as('remember_code',lang('remember_code'));
        $this->grocery_crud->display_as('created_on',lang('created_on'));                
        $this->grocery_crud->display_as('active',lang('active'));
        $this->grocery_crud->display_as('first_name',lang('first_name'));
        $this->grocery_crud->display_as('last_name',lang('last_name'));
        $this->grocery_crud->display_as('company',lang('company'));
        $this->grocery_crud->display_as('phone',lang('phone'));
        
        //Establish fields/columns order and wich camps to show
        $this->grocery_crud->columns($this->session->userdata('users_current_fields_to_show'));

        //FIELD TYPES
        $this->grocery_crud->field_type('password', 'password');
        $this->grocery_crud->field_type('verify_password', 'password');
        $this->grocery_crud->field_type('created_on', 'datetime');
		$this->grocery_crud->field_type('last_login', 'datetime');
		$this->grocery_crud->field_type('active', 'dropdown',
		            array('1' => lang('Yes'), '2' => lang('No')));
		$this->grocery_crud->field_type('ip_address', 'invisible');
		$this->grocery_crud->field_type('created_on', 'invisible');
		
		//RULES
		$this->grocery_crud->set_rules('email', lang('email'), 'required|valid_email');
        
        $this->grocery_crud->unset_add_fields('ip_address','salt','activation_code','forgotten_password_code','forgotten_password_time','remember_code','last_login','created_on');
        $this->grocery_crud->unset_edit_fields('ip_address','salt','activation_code','forgotten_password_code','forgotten_password_time','remember_code','last_login','created_on');
        
        $this->grocery_crud->unique_fields('username','email');

	    //GROUPS
        $this->grocery_crud->set_relation_n_n('groups', 'users_groups','groups', 'user_id', 'group_id', 'name');
        
        //USER MAIN ORGANIZATIONAL UNIT
        $this->grocery_crud->set_relation('mainOrganizationaUnitId','organizational_unit','{name}',array('markedForDeletion' => 'n'));
        
        $this->grocery_crud->callback_before_insert(array($this,'callback_unset_verification_and_hash_and_extra_actions'));
		$this->grocery_crud->callback_before_update(array($this,'callback_unset_verification_and_hash_and_extra_actions'));
		
		//ON UPDATE SHOW VOID PASSWORD FIELDS
		$this->grocery_crud->callback_edit_field('password',array($this,'edit_field_callback_password'));
		
		$this->set_theme($this->grocery_crud);
		$this->set_dialogforms($this->grocery_crud);
        
        try {
			
        $output = $this->grocery_crud->render();
        
        } catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
        
        $this->load_header($output);
        
        // VIEW WITH DINAMIC JAVASCRIPT. Purpose: set default values
        $default_values=$this->_get_default_values();
        $default_values["table_name"]=$table_name;
        $this->load->view('defaultvalues_view.php',$default_values); 
               
        $this->load->view('users_view.php',$output);
        $this->load->view('include/footer');
}
	
	function user_info() {
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->login_page, 'refresh');
		}
		
		$data['inventory_userinfo_js_files'] = array(
           base_url('assets/grocery_crud/js/jquery-1.10.2.min.js'),
		);
            
        $data['not_show_header2']=true;
        
        $current_rol_id = $this->session->userdata('role');
		$current_role_name = $this->_get_rolename_byId($current_rol_id);
        $data['institution_name'] = $this->config->item('institution_name');
        $data['grocerycrudstate']=true;
        $data['grocerycrudstate_text']=lang('user_info_title');
        $user_groups_in_database= $this->skeleton_auth->get_users_groups()->result();
        $user_groups_in_database_names=array();
        foreach ($user_groups_in_database as $user_group_in_database) {
			$user_groups_in_database_names[]=$user_group_in_database->name;
		}
		
		$userid=$this->session->userdata('user_id');
		$user=$this->skeleton_auth->user($userid)->row();
		
		//print_r($user);
        
        $data['fields']=array (
			lang('user_id_title') => $userid,
			lang('username_title') => $this->session->userdata('username'),
			lang('name_title') => $user->first_name,
			lang('surname_title') => $user->last_name,
			lang('email_title') => $this->session->userdata('email'),
			lang('user_groups_in_database') => implode(", ",$user_groups_in_database_names),
			lang('rol_title') => $current_role_name,
			lang('realm_title') => $this->session->userdata('default_realm'),
			lang('main_user_organizational_unit') => $this->inventory_Model->get_main_organizational_unit_name_from_userid($userid),
			lang('inventory_object_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_inventory_object')),
			lang('externalIDType_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_externalIDType')),
			lang('organizational_unit_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_organizational_unit')),
			lang('location_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_location')),
			lang('material_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_material')),
			lang('brand_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_brand')),
			lang('model_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_model')),
			lang('provider_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_provider')),
			lang('money_source_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_money_source')),
			lang('users_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_users')),
			lang('groups_fields_title') => implode(", ",(array) $this->config->item('default_fields_table_groups'))
        );
		$this->load_header($data,false);
		
		$this->load->view('user_info_view'); 
		$this->load->view('include/footer'); 
	}
	
	public function user_preferences() {
		//IF USER IS NOT LOGGED REDIRECT TO LOGIN PAGE: PROTECTED PAGE
		if (!$this->skeleton_auth->logged_in()) {	
			//redirect them to the login page
			redirect($this->login_page, 'refresh');
		}
		
		//OBTAIN GROUPS/ROLES
		$readonly_group = $this->config->item('readonly_group');		
		$organizationalunit_group = $this->config->item('organizationalunit_group');
		$dataentry_group = $this->config->item('dataentry_group');
		$inventory_admin_group = $this->config->item('inventory_admin_group');
		
		//NOT ALL USERS HAVE PREFERENCES: IN CASE no preferences then
		//default ones are applied
		$user_have_preferences=false;
		$user_id = $this->session->userdata('user_id');
		
		$user_have_preferences=$this->inventory_model->user_have_preferences($user_id);
		$user_preferences_id=null;
		if ($user_have_preferences) {
			$user_preferences_id = $this->inventory_model->get_user_preferencesId($user_id);  
		}
		
		//GET STATE AND STATE INFO
		try {
			$state = $this->grocery_crud->getState();
			$state_info = $this->grocery_crud->getStateInfo();
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
			
		$skip_grocerycrud=false;		
		$default_values2 = array();
		
		
		//SKIP IS USER IS ADMIN	
		if (!$this->skeleton_auth->in_group($inventory_admin_group)) {
			//CHECK OPERATIONS DONE BY NO ADMIN USERS DEPENDING ON STATE
			if($state == 'add')	{
				//Prepare add form to force userId
				$this->grocery_crud->required_fields('markedForDeletion','language','theme');
				$this->grocery_crud->unset_back_to_list();
				$this->grocery_crud->field_type('userId', 'hidden', $user_id);
				//IF USER have preferences an is not admin then operation not permited
				//Avoid adding to times same register
				if ($user_have_preferences) {
					$skip_grocerycrud=true;
					$alternate_view_to_grocerycrud="insert_not_allowed.php";
				}
				
			} 
			elseif($state == 'insert_validation') {
				//CHECK IF EDIT IS ALLOWED DEPENDING ON USER ROLES	
				$primary_key = $state_info->primary_key;
				
				$skip_grocerycrud=true;
				$alternate_view_to_grocerycrud="insert_not_allowed.php";
				
				if (!($primary_key == $user_preferences_id)) {			
					//CHECK IF PRIMARY KEY TO EDIT IS OWNED BY USER
					//NOT ALLOWED
					$skip_grocerycrud=true;
					$alternate_view_to_grocerycrud="insert_not_allowed.php";				
				}					
			}
			elseif($state == 'edit') {
				$this->grocery_crud->unset_back_to_list();
				if ($this->skeleton_auth->in_group($organizationalunit_group) || 
					$this->skeleton_auth->in_group($dataentry_group) ) {
						//Prepare edit form to force userId
						$this->grocery_crud->required_fields('markedForDeletion','language','theme');
						$this->grocery_crud->unset_back_to_list();
						$this->grocery_crud->field_type('userId', 'hidden', $user_id);
				}
				
				//******* TODO****************
				//CHECK IF EDIT IS ALLOWED DEPENDING ON USER ROLES	
				$primary_key = $state_info->primary_key;
				if (!($primary_key == $user_preferences_id)) {			
					//CHECK IF PRIMARY KEY TO EDIT IS OWNED BY USER
					//NOT ALLOWED
					$skip_grocerycrud=true;
					$alternate_view_to_grocerycrud="edit_not_allowed.php";				
				}					
			} elseif ($state == 'list' || $state == 'ajax_list' || $state == 'success') {
				//******* TODO****************
				if ($this->skeleton_auth->in_group($readonly_group) || 
					$this->skeleton_auth->in_group($organizationalunit_group) || 
					$this->skeleton_auth->in_group($dataentry_group) ) {
						$this->grocery_crud->unset_operations();
				}
				if ($this->skeleton_auth->in_group($organizationalunit_group) || 
					$this->skeleton_auth->in_group($dataentry_group) ) {
						$this->grocery_crud->unset_list();
				}
			}
			else {
				$this->grocery_crud->required_fields('userId','markedForDeletion','language','theme');
			}	
		} else {
			//USER IS ADMIN
			$this->grocery_crud->required_fields('userId','markedForDeletion','language','theme');
		}
		$this->grocery_crud->unique_fields('userId');
		
		$this->current_table="user_preferences";
        $this->grocery_crud->set_table($this->current_table);
        
        //Establish subject:
        $this->grocery_crud->set_subject(lang('user_preferences_subject'));
                        
        //COMMON_COLUMNS               
        $this->set_common_columns_name();
        
        

        //ESPECIFIC COLUMNS                                            
        $this->grocery_crud->display_as('userId',lang('userId'));
        $this->grocery_crud->display_as('language',lang('language'));
        $this->grocery_crud->display_as('theme',lang('theme'));
        $this->grocery_crud->display_as('dialogforms',lang('dialogforms'));
        
        //Establish fields/columns order and wich camps to show
        $this->grocery_crud->columns($this->session->userdata('user_preferences_current_fields_to_show'));       
        
        $this->grocery_crud->unset_add_fields('last_update','manualLast_update');
        
        //ExternID types
        $this->grocery_crud->set_relation('userId','users','{username}');
        
        $this->grocery_crud->unset_dropdowndetails("userId");
		$this->grocery_crud->set_default_value($this->current_table,'userId',$this->session->userdata('username'));
		
		//ENTRY DATE
		//DEFAULT VALUE=NOW. ONLY WHEN ADDING
		//EDITING: SHOW CURRENT VALUE READONLY
		$this->grocery_crud->callback_add_field('entryDate',array($this,'add_field_callback_entryDate'));
		$this->grocery_crud->callback_edit_field('entryDate',array($this,'edit_field_callback_entryDate'));
		
		//LAST UPDATE
		//DEFAULT VALUE=NOW. ONLY WHEN ADDING
		//EDITING: SHOW CURRENT VALUE READONLY
		$this->grocery_crud->callback_add_field('last_update',array($this,'add_callback_last_update'));
		$this->grocery_crud->callback_edit_field('last_update',array($this,'edit_callback_last_update'));
		
		//UPDATE AUTOMATIC FIELDS
		if ($this->skeleton_auth->in_group($inventory_admin_group)) {
			$this->grocery_crud->callback_before_insert(array($this,'before_insert_object_callback'));
		} else {
			//If not admin user, force UserId always to be the userid of actual user
			$this->grocery_crud->callback_before_insert(array($this,'before_insert_user_preference_callback'));
		}
		
		if ($this->skeleton_auth->in_group($inventory_admin_group)) {
			$this->grocery_crud->callback_before_update(array($this,'before_update_object_callback'));
		} else {
			//If not admin user, force UserId always to be the userid of actual user
			$this->grocery_crud->callback_before_update(array($this,'before_update_user_preference_callback'));
		}
        
        //USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_insert_object_callback
        $this->grocery_crud->set_relation('creationUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'creationUserId',$this->session->userdata('user_id'));

        //LAST UPDATE USER ID: show only active users and by default select current userid. IMPORTANT: Field is not editable, always forced to current userid by before_update_object_callback
        $this->grocery_crud->set_relation('lastupdateUserId','users','{username}',array('active' => '1'));
        $this->grocery_crud->set_default_value($this->current_table,'lastupdateUserId',$this->session->userdata('user_id'));
        
        $this->grocery_crud->unset_dropdowndetails("creationUserId","lastupdateUserId");
        
        $this->set_theme($this->grocery_crud);
        $this->set_dialogforms($this->grocery_crud);
		
		try{
			$output = $this->grocery_crud->render();
			$this->load_header($output,true,false);
               
			// VIEW WITH DINAMIC JAVASCRIPT. Purpose: set default values
			$default_values=$this->_get_default_values();
			$default_values["table_name"]=$this->current_table;
			$this->load->view('defaultvalues_view.php',$default_values); 
        
			//ADMIN USER: SHOW HELPER TO EDIT HIS PREFERENCES: SHORTCUT
			//GROCERYCRUD VIEW
        
			$data = array( 'user_preferences_id' => $user_preferences_id );

			if($state == 'list') {
				// IF USER HAVE NO PREFERENCES YES SHOW MESSAGE
				if (!$user_have_preferences){
					$this->load->view('user_preferences_NOT_yet_header.php',$data);                
				} else {
				$this->load->view('user_preferences_for_admin_header.php',$data);                
				}
			}
                
			//GROCERYCRUD VIEW
			if ($skip_grocerycrud) {
				$this->load->view($alternate_view_to_grocerycrud,$output);
			}
			else{
				$this->load->view('defaultvalues_view.php',array_merge($this->_get_default_values()));            
				$this->load->view('inventory_object_view.php',$output);        
			}
                
			$this->load->view('include/footer');
			}catch(Exception $e){
				show_error($e->getMessage().' --- '.$e->getTraceAsString());
			}   
	}
    
    
    
    
    public function preferences() {
		
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->login_page, 'refresh');
		}
		
		//CHECK IF USER IS READONLY --> REDIRECT TO LIST
		$readonly_group = $this->config->item('readonly_group');
		if ($this->skeleton_auth->in_group($readonly_group)) {
			redirect("main/user_preferences", 'refresh');
		}
		
		//CHECK IF USER IS ADMIN --> REDIRECT TO LIST ALL USER 
		//PREFERENCES
		$inventory_admin_group = $this->config->item('inventory_admin_group');
		if ($this->skeleton_auth->in_group($inventory_admin_group)) {
			//Manage all user preferences:
			//$this->user_preferences();
			redirect("main/user_preferences", 'refresh');
		}
		
		//Other groups/roles (inventory_organizationunit, inventory_dataentry)
		$user_have_preferences=false;
		$user_id = $this->session->userdata('user_id');
		$user_have_preferences=$this->inventory_model->user_have_preferences($user_id);
		$user_preferences_id=null;
		if ($user_have_preferences) {
			$user_preferences_id = $this->inventory_model->get_user_preferencesId($user_id);  
			redirect("main/user_preferences/edit/". $user_preferences_id);
		}
		else {
			redirect("main/user_preferences/add");
		}
		
	}
	
	public function groups(){
	   if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->login_page, 'refresh');
		}
		//CHECK IF USER IS READONLY --> unset add, edit & delete actions
		$readonly_group = $this->config->item('readonly_group');
		if ($this->skeleton_auth->in_group($readonly_group)) {
			$this->grocery_crud->unset_add();
			$this->grocery_crud->unset_edit();
			$this->grocery_crud->unset_delete();
	   }
	   $table_name="groups";
	   $this->current_table=$table_name;
       $this->grocery_crud->set_table($this->current_table);  
       
       $this->grocery_crud->required_fields('name');

       //Establish subject:
        $this->grocery_crud->set_subject(lang('groups_subject'));
        
       //COMMON_COLUMNS               
       $this->set_common_columns_name();

       //ESPECIFIC COLUMNS                                            
       $this->grocery_crud->display_as('name',lang('name'));
       $this->grocery_crud->display_as('description',lang('description')); 
       
       //Establish fields/columns order and wich camps to show
       $this->grocery_crud->columns($this->session->userdata('groups_current_fields_to_show'));
       
       $this->grocery_crud->set_relation_n_n('users', 'users_groups','users', 'user_id', 'id', 'username');
       
       $this->set_theme($this->grocery_crud);
       $this->set_dialogforms($this->grocery_crud);
            
       $output = $this->grocery_crud->render();
       
       $this->load_header($output);
       
       // VIEW WITH DINAMIC JAVASCRIPT. Purpose: set default values
       $default_values=$this->_get_default_values();
       $default_values["table_name"]=$table_name;
       $this->load->view('defaultvalues_view.php',$default_values); 
       
       $this->load->view('groups_view.php',$output);
       $this->load->view('include/footer');                          
}      
		
}
