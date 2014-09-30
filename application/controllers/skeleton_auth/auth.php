<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	
	public $auth_page = "skeleton_auth/auth";
	public $login_page = "skeleton_auth/auth/login";
	public $login_view = "auth/login";
	public $login_index_view = "auth/index";
    public $after_succesful_login_page = "/";
    public $change_password_page ="skeleton_auth/auth/change_password";
    public $change_password_view ="auth/change_password";
    public $forgot_password_page ="skeleton_auth/auth/forgot_password";
    public $forgot_password_view ="auth/forgot_password";
    public $reset_password_page ="skeleton_auth/auth/reset_password/";
    public $reset_password_view ="auth/reset_password";
    public $deactive_user_view ="auth/deactivate_user";

    public $reset_form_submit_url = 'skeleton_auth/auth/reset_password/';
    public $forgotten_password_email_template = "skeleton_auth/auth/reset_password";
    public $forgot_password_submit_url='index.php/skeleton_auth/auth/forgot_password_';
    
    public $create_user_view ="auth/create_user";    
    public $edit_user_view = "auth/edit_user";
    public $create_group_view ="auth/create_group";    
    public $edit_group_view = "auth/edit_group";
    
    public $default_language = "catalan";

    //Default accepted realms
    public $realms = "mysql,ldap";    

	function __construct($model="skeleton_auth_model")
	{
		parent::__construct();
		
		$this->load->add_package_path(APPPATH.'third_party/skeleton/application/');
		$params = array('model' => $model);
		$this->load->library('skeleton_auth',$params);
		
		$this->load->library('Auth_Ldap');
			
		$this->load->library('form_validation');
		$this->load->helper('url');

		// Load MongoDB library instead of native db driver if required
		$this->config->item('use_mongodb', 'skeleton_auth') ?
		$this->load->library('mongo_db') :

		$this->load->database();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'skeleton_auth'), $this->config->item('error_end_delimiter', 'skeleton_auth'));

		$this->default_language= $this->config->item('default_language', 'skeleton_auth');
		
		$this->skeleton_auth->lang->load('skeleton', $this->default_language);
		$this->lang->load('ion_auth', $this->default_language);
		$this->lang->load('auth', $this->default_language);
		$this->lang->load('form_validation', $this->default_language);
		
		$this->load->helper('language');
		
		//GET REALMS FROM CONFIG
		if ($this->config->item('realms','skeleton_auth')!="") {
			$this->realms = explode(",",$this->config->item('realms','skeleton_auth'));
		}
		
		//GET FORGOT PASSSWORD REALMS FROM CONFIG
		if ($this->config->item('forgot_password_realms','skeleton_auth')!="") {
			$this->forgot_password_realms = explode(",",$this->config->item('forgot_password_realms','skeleton_auth'));
		}
		
		
	}

//redirect if needed, otherwise display the user list
function index()
	{
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->login_page, 'refresh');
		}
		elseif (!$this->skeleton_auth->is_admin())
		{
			//redirect them to the home page because they must be an administrator to view this
			redirect($this->after_succesful_login_page, 'refresh');
		}
		else
		{
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->skeleton_auth->users()->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->skeleton_auth->get_users_groups($user->id)->result();
			}

			$this->_render_page($this->login_index_view, $this->data);
		}
	}
	
	public function _getvar($name){
		if (isset($_GET[$name])) return $_GET[$name];
		else if (isset($_POST[$name])) return $_POST[$name];
		else return false;
	}

	//VOID: implement it on child classes
	public function on_exit_login_hook($username="") {
		return null;
	}

	//log the user in
	function login()
	{
		$this->data['title'] = "Login";
		$this->data['register_url'] = $this->create_user_view;;

		//BY DEFAULT REDIRECT AFTER SUCCESFUL LOGIN TO default value of:
		// $this->after_succesful_login_page
		// But if a POST or GET variable named redirect exists use this page
		$this->data['redirect'] = "";
		$redirect_value=$this->_getvar("redirect");
		if ($redirect_value) {
			//echo urldecode($redirect_value);
			$this->after_succesful_login_page=urldecode($redirect_value);
			$this->data['redirect'] = "?redirect=".urldecode($redirect_value);
		}

		
		//validate form input
		$this->form_validation->set_rules('identity', lang('Identity'), 'required');
		$this->form_validation->set_rules('password', lang('Password'), 'required');


		if ($this->form_validation->run() == true)
		{
			$realm = $this->input->post('realm');
			
			switch ($realm) {
				case "maintenance_mode":
					$this->_maintenance_mode();
					break;
				case "mysql":
					//HARDCODED AS DEFAULT
					$this->skeleton_auth->skeleton_auth_model->setRealm("mysql");
					break;
					
				case "ldap":
					$this->skeleton_auth->skeleton_auth_model->setRealm("ldap");
					break;	
			}
			
			//check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');
					
			if ($this->skeleton_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->skeleton_auth->messages());
				$this->on_exit_login_hook($this->input->post('identity'));
				redirect($this->after_succesful_login_page, 'refresh');
			}
			else
			{
				//if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('message', $this->skeleton_auth->errors());
				redirect($this->login_page, 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
			
		}
		else
		{
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);
	     	
	     	$this->data['realms'] = $this->realms;
	     	
	     	//if maintenance mode is active at config file add as a "realm"
	     	if ($this->config->item('maintenance_mode','skeleton_auth')) {
				array_push($this->data['realms'], "maintenance_mode");
			}
	     	$this->data['default_realm'] = $this->config->item('default_realm', 'skeleton_auth');
	     	
	     	$this->_set_common_data();

	     	$this->data['login_url'] = $this->login_page;

	     	$this->_render_page($this->login_view, $this->data);
		}
	}

	protected function _set_common_data() {
	
		$this->data['copyright_url'] = $this->config->item('copyright_url', 'skeleton_auth');
	    $this->data['copyright_app_name'] = $this->config->item('copyright_app_name', 'skeleton_auth');
	    $this->data['copyright_entity_name'] = $this->config->item('copyright_entity_name', 'skeleton_auth');
	    $this->data['copyright_entity_url'] = $this->config->item('copyright_entity_url', 'skeleton_auth');
	    $this->data['copyright_entity_url_name'] = $this->config->item('copyright_entity_url_name', 'skeleton_auth');
	    $this->data['copyright_authors_text'] = $this->config->item('copyright_authors_text', 'skeleton_auth');
	    $this->data['copyright_authors_html'] = $this->config->item('copyright_authors_html', 'skeleton_auth');
	     	
	    $this->data['login_appname'] = $this->config->item('login_appname', 'skeleton_auth');
	    $this->data['login_entity'] = $this->config->item('login_entity', 'skeleton_auth');
	}


	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";

		//log the user out
		$logout = $this->skeleton_auth->logout();

		//redirect them to the login page
		$this->session->set_flashdata('message', $this->skeleton_auth->messages());
		redirect($this->login_page, 'refresh');
	}

	//change password
	function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'skeleton_auth') . ']|max_length[' . $this->config->item('max_password_length', 'skeleton_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->skeleton_auth->logged_in())
		{
			redirect($this->login_page, 'refresh');
		}

		$user = $this->skeleton_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			//display the form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'skeleton_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id'   => 'new',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			//render
			$this->_render_page($this->change_password_view, $this->data);
		}
		else
		{
			$identity = $this->session->userdata($this->config->item('identity', 'skeleton_auth'));

			$change = $this->skeleton_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->skeleton_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->skeleton_auth->errors());
				redirect($this->change_password_page, 'refresh');
			}
		}
	}
	
	protected function _forgot_password_by_identity($identity="email") {	

		$this->data['identity']="email";
		$this->data['alternative_identity']="username";

		$this->data['forgot_password_submit_url']= $this->forgot_password_submit_url;
		
		if ($identity == "username" ) {
			$this->form_validation->set_rules('username', $this->lang->line('forgot_password_validation_username_label'), 'required');		
			$this->form_validation->set_rules('realm', $this->lang->line('forgot_password_validation_realms_label'), 'required');		
			$this->data['identity']="username";
			$this->data['alternative_identity']="email";	
		}
		else {
			$this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
			$this->form_validation->set_rules('realm', $this->lang->line('forgot_password_validation_realms_label'), 'required');		
		}
			
		if ($this->form_validation->run() == false)
		{			
			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['realms'] = $this->forgot_password_realms;
	     	$this->data['default_realm'] = $this->config->item('default_forgot_password_realm', 'skeleton_auth');
	     	$this->_render_page($this->forgot_password_view , $this->data);
		}
		else
		{
			//Which realm to use?
			$realm=$this->input->post("realm");

			$identity_post_value=$this->input->post($identity);
			$identity_value="";
		    //CHECK IDENTITY: IF IDENTITY (USERNAME OR EMAIL) is not in database come back to forgot_password form with error message		    
			if ($realm == "ldap" ) {
				//CHeck identity. Search in Ldap username or email depending on identity variable If not redirect to forgot password page with error message
				$username=$this->check_identity_ldap($identity);
				//Be sure user is in mysql database to be able to insert forgotten password code!
				// Similar to Ldap login, if first time user is added to mysql database
				$create_mysql_user_if_exists_on_ldap = $this->config->item('create_mysql_user_if_exists_on_ldap', 'skeleton_auth');
				if ($create_mysql_user_if_exists_on_ldap) {
					$this->skeleton_auth->add_user_ifnotexists($username);
				} 
			} else {
				//REALM=MySQL
				$identity_value = $this->check_identity($identity);
			}

			// Same method for Ldap and Email realms. 
			//run the forgotten password method to email an activation code to the user
			$this->skeleton_auth->forgotten_password_email_template=$this->forgotten_password_email_template;
			$forgotten = $this->skeleton_auth->forgotten_password($identity_post_value,$identity,$realm);
			if ($forgotten)
			{
				//if there were no errors
				$this->session->set_flashdata('message', $this->skeleton_auth->messages());
				$this->session->set_flashdata('spam_alert', true);
				redirect($this->login_page, 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->skeleton_auth->errors());
				redirect($this->forgot_password_page. "_" . $identity, 'refresh');
			}
		}
	}
	
	public function forgot_password_email()
	{
		$this->_set_common_data();
		
		//DEFAULT: forgot_password_email();
		$this->_forgot_password_by_identity();
	}

	public function forgot_password_username()
	{
		$this->_set_common_data();
		//DEFAULT: forgot_password_email();
		$this->_forgot_password_by_identity("username");
	}
	
	//forgot password
	function forgot_password()
	{
		$this->forgot_password_email();
	}
	
	public function check_identity($identity="email") {
		// get identity
		
		$config_tables = $this->config->item('tables', 'skeleton_auth');
		$this->db->where($identity, $this->input->post($identity));
		$identity_row = $this->db->get($config_tables['users'])->row();
		
		$num = $this->db->count_all_results();
			
		if ( $num <= 0) {
			$this->session->set_flashdata('message', sprintf(lang("forgot_password_identity_not_found"),$identity));
			redirect($this->forgot_password_page . "_" . $identity, 'refresh');
		}
			
		if ( $num > 1) {
			$this->session->set_flashdata('message', sprintf(lang("forgot_password_identity_found_more_than_one"),$identity));
			redirect($this->forgot_password_page . "_" . $identity, 'refresh');
		}
		return $identity_row;
	}
	
	public function check_identity_ldap($identity="email") {
		
		//Debug:
		//echo "<br/>check_identity_ldap. Identity: " . $identity . "<br/>";
		$identity_value = $this->input->post($identity);
		
		$result = $this->auth_ldap->check_identity_ldap($identity,$identity_value);
		
		if ( $result["count"] <= 0) {
			$this->session->set_flashdata('message', sprintf(lang("forgot_password_identity_not_found"),$identity));
			redirect($this->forgot_password_page . "_" . $identity, 'refresh');
		}
			
		if ( $result["count"] > 1) {
			$this->session->set_flashdata('message', sprintf(lang("forgot_password_identity_found_more_than_one"),$identity));
			redirect($this->forgot_password_page . "_" . $identity, 'refresh');
		}
		return $result["value"];
	}

	//reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		$this->_set_common_data();

		if (!$code)
		{
			show_404();
		}

		$user = $this->skeleton_auth->forgotten_password_check($code);

		if ($user)
		{
			//if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'skeleton_auth') . ']|max_length[' . $this->config->item('max_password_length', 'skeleton_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				//display the form

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'skeleton_auth');
				
				$this->data['user_id']= $user->id;
				
				$csrf= $this->_get_csrf_nonce();
				
				$this->data['csrf'] = $csrf;
				$this->data['code'] = $code;

				$this->data['reset_form_submit_url'] = $this->reset_form_submit_url;

				//render
				$this->_render_page($this->reset_password_view, $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					//something fishy might be up
					$this->skeleton_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'skeleton_auth')};
					
					$change_result = $this->skeleton_auth->reset_password($identity, $this->input->post('new'));

					if ($change_result)
					{
						//if the password was successfully changed
						$this->session->set_flashdata('message', $this->skeleton_auth->messages());
						$this->skeleton_auth->clear_forgotten_password_code($code);
						$this->logout();
					}
					else
					{
						$this->session->set_flashdata('message', $this->skeleton_auth->errors());
						$this->skeleton_auth->clear_forgotten_password_code($code);
						redirect($this->reset_password_page . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->skeleton_auth->errors());
			redirect($this->forgot_password_page, 'refresh');
		}
	}


	//activate the user
	function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->skeleton_auth->activate($id, $code);
		}
		else if ($this->skeleton_auth->is_admin())
		{
			$activation = $this->skeleton_auth->activate($id);
		}

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->skeleton_auth->messages());
			redirect($this->auth_page, 'refresh');
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->skeleton_auth->errors());
			redirect($this->forgot_password_page, 'refresh');
		}
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'skeleton_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->skeleton_auth->user($id)->row();

			$this->_render_page($this->deactive_user_view, $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->skeleton_auth->logged_in() && $this->skeleton_auth->is_admin())
				{
					$this->skeleton_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect($this->auth_page, 'refresh');
		}
	}

	//create a new user
	function create_user()
	{
		$this->data['title'] = "Create User";

		if (!$this->skeleton_auth->logged_in() || !$this->skeleton_auth->is_admin())
		{
			redirect($this->auth_page, 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required|xss_clean');
		$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'skeleton_auth') . ']|max_length[' . $this->config->item('max_password_length', 'skeleton_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
			$email    = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone'),
			);
		}
		if ($this->form_validation->run() == true && $this->skeleton_auth->register($username, $password, $email, $additional_data))
		{
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->skeleton_auth->messages());
			redirect($this->auth_page, 'refresh');
		}
		else
		{
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->skeleton_auth->errors() ? $this->skeleton_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$this->data['last_name'] = array(
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['company'] = array(
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company'),
			);
			$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);

			$this->_render_page($this->create_user_view, $this->data);
		}
	}

	//edit a user
	function edit_user($id)
	{
		$this->data['title'] = "Edit User";

		if (!$this->skeleton_auth->logged_in() || !$this->skeleton_auth->is_admin())
		{
			redirect($this->auth_page, 'refresh');
		}

		$user = $this->skeleton_auth->user($id)->row();
		$groups=$this->skeleton_auth->groups()->result_array();
		$currentGroups = $this->skeleton_auth->get_users_groups($id)->result();

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required|xss_clean');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required|xss_clean');
		$this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone'),
			);

			//Update the groups user belongs to
			$groupData = $this->input->post('groups');

			if (isset($groupData) && !empty($groupData)) {

				$this->skeleton_auth->remove_from_group('', $id);

				foreach ($groupData as $grp) {
					$this->skeleton_auth->add_to_group($grp, $id);
				}

			}

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'skeleton_auth') . ']|max_length[' . $this->config->item('max_password_length', 'skeleton_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$this->skeleton_auth->update($user->id, $data);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', "User Saved");
				redirect($this->auth_page, 'refresh');
			}
		}

		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->skeleton_auth->errors() ? $this->skeleton_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->_render_page($this->edit_user_view, $this->data);
	}

	// create a new group
	function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->skeleton_auth->logged_in() || !$this->skeleton_auth->is_admin())
		{
			redirect($this->auth_page, 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->skeleton_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->skeleton_auth->messages());
				redirect($this->auth_page, 'refresh');
			}
		}
		else
		{
			//display the create group form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->skeleton_auth->errors() ? $this->skeleton_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page($this->create_group_view, $this->data);
		}
	}

	//edit a group
	function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect($this->auth_page, 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->skeleton_auth->logged_in() || !$this->skeleton_auth->is_admin())
		{
			redirect($this->auth_page, 'refresh');
		}

		$group = $this->skeleton_auth->group($id)->row();

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('group_description', $this->lang->line('edit_group_validation_desc_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->skeleton_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->skeleton_auth->errors());
				}
				redirect($this->auth_page, 'refresh');
			}
		}

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->skeleton_auth->errors() ? $this->skeleton_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['group'] = $group;

		$this->data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group->name),
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->_render_page($this->edit_group_view, $this->data);
	}


	protected function _maintenance_mode() {
		$maintenance_user= $this->config->item('maintenance_mode_user','skeleton_auth');
		$maintenance_password= $this->config->item('maintenance_mode_password','skeleton_auth');
			
		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == true)	{
				
			//check maintenance LOGIN
			if (( $this->input->post('identity') == $maintenance_user ) && ( $this->input->post('password') == $maintenance_password)) 			{
				//if the login is successful redirect them back to the home page
				$session_data = array(
					'identity'             => $maintenance_user,
					'username'             => $maintenance_user,
					'email'                => $this->config->item('maintenance_mode_user_email','skeleton_auth'),
					'user_id'              => $this->config->item('maintenance_mode_user_id','skeleton_auth'), //everyone likes to overwrite id so we'll use user_id
					'old_last_login'       => "nothing"
				);
				$this->session->set_userdata($session_data);
				redirect($this->after_succesful_login_page, 'refresh');
			}
			else {
				//if the login was un-successful redirect them back to the login page
				$this->session->set_flashdata('message', lang('maintenance_mode_login_error_message'));
				redirect($this->login_page, 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else {
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
					'id' => 'password',
					'type' => 'password',
				);
				$this->_render_page($this->login_view, $this->data);
			}
		}
	


	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $render=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if (!$render) return $view_html;
	}

}
