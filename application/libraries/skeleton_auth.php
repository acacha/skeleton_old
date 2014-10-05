<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 *  Created Sergi Tur (sergiturbadenas@gmail.com)
 * 
 *  BASED ON: Ion Auth
 *
 * Author: Ben Edmunds
 *		  ben.edmunds@gmail.com
 *         @benedmunds
 *
 *
*/

class skeleton_auth
{

	public $forgotten_password_email_template="skeleton_auth/auth/reset_password";

	/**
	 * account status ('not_activated', etc ...)
	 *
	 * @var string
	 **/
	protected $status;

	/**
	 * extra where
	 *
	 * @var array
	 **/
	public $_extra_where = array();

	/**
	 * extra set
	 *
	 * @var array
	 **/
	public $_extra_set = array();

	/**
	 * caching of users and their groups
	 *
	 * @var array
	 **/
public $_cache_user_in_group;

	/**
	 * __construct
	 *
	 * @return void
	 * @author Ben
	 **/
	public function __construct($params)
	{
		$this->load->config('skeleton_auth', TRUE);
		$this->load->library('email');
		
		$default_language=$this->config->item('default_language', 'skeleton_auth');
		
		$this->lang->load('ion_auth',$default_language);
		$this->load->helper('cookie');
		

		// Load the session, CI2 as a library, CI3 uses it as a driver
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->load->library('session');
		}
		else
		{
			$this->load->driver('session');
		}

		// Load IonAuth MongoDB model if it's set to use MongoDB,
		// We assign the model object to "skeleton_auth_model" variable.
		$this->config->item('use_mongodb', 'skeleton_auth_model') ?
			$this->load->model('ion_auth_mongodb_model', 'skeleton_auth_model') :
			$this->load->model($params['model'],'skeleton_auth_model');
		
		
		$this->_cache_user_in_group =& $this->skeleton_auth_model->_cache_user_in_group;

		//auto-login the user if they are remembered
		if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code'))
		{
			$this->skeleton_auth_model->login_remembered_user();
		}

		$email_config = $this->config->item('email_config', 'skeleton_auth');

		if ($this->config->item('use_ci_email', 'skeleton_auth') && isset($email_config) && is_array($email_config))
		{
			$this->email->initialize($email_config);
		}

		$this->skeleton_auth_model->trigger_events('library_constructor');
		
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 **/
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->skeleton_auth_model, $method) )
		{
			throw new Exception('Undefined method skeleton_auth::' . $method . '() called');
		}

		return call_user_func_array( array($this->skeleton_auth_model, $method), $arguments);
	}

	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var)
	{
		return get_instance()->$var;
	}


	public function get_user_info_by_email($email) {

		$this->db->select('id, forgotten_password_code, username, person_email, person_secondary_email');
		$this->db->where(array( 'person_email' => $email));
		$this->db->or_where(array( "person_secondary_email" => $email));
		$this->db->join('person','person.person_id = users.person_id');
		$this->db->from('users');
		$this->db->limit(1);

		$query = $this->db->get();
		
		if ($query->num_rows() == 1){ 
			return $query->row();
		}
		else {
			return false;
		}

	}

	public function get_user_info_by_username($username) {

		$this->db->select('id, forgotten_password_code, username, person_email, person_secondary_email');
		$this->db->where(array( 'username' => $username));
		$this->db->from('users');
		$this->db->join('person','person.person_id = users.person_id');
		$this->db->limit(1);

		$query = $this->db->get();

		//echo $this->db->last_query();
		
		if ($query->num_rows() == 1){ 
			return $query->row();
		}
		else {
			return false;
		}

	}


	/**
	 * forgotten password feature
	 *
	 * @return mixed  boolian / array
	 * @author Mathew. Sergi Tur Badenas
	 **/
	public function forgotten_password($identity,$identity_key="email",$realm="mysql")    //changed $email to $identity
	{
		$this->skeleton_auth_model->identity_column=$identity_key;
		if ( $this->skeleton_auth_model->forgotten_password($identity,$identity_key,$realm) )   //changed
		{
			// Get user information
			$user = null;
			if ($identity_key=="email") {
				$user = $this->get_user_info_by_email($identity);
			} else if ($identity_key=="username") {
				$user = $this->get_user_info_by_username($identity);
			} else {
				return false;
			}
			//DEBUG:
			//var_export($user);
			
			if ($user)
			{
				$data = array(
					'identity'		=> $user->{$this->config->item('identity', 'skeleton_auth')},
					'forgotten_password_code' => $user->forgotten_password_code
				);

				if(!$this->config->item('use_ci_email', 'skeleton_auth'))
				{
					$this->set_message('forgot_password_successful');
					return $data;
				}
				else
				{
		
					$data['organization'] = $this->config->item('organization', 'skeleton_auth');	
					$data['app_name'] = $this->config->item('app_name', 'skeleton_auth');	
					//Example: "skeleton_auth/ebre_escool_auth/reset_password":
					$data['forgotten_password_email_template'] = $this->forgotten_password_email_template ; 
					$message = $this->load->view($this->config->item('email_templates', 'skeleton_auth').$this->config->item('email_forgot_password', 'skeleton_auth'), $data, true);
					
					$this->email->clear();
					$this->email->from($this->config->item('admin_email', 'skeleton_auth'), $this->config->item('site_title', 'skeleton_auth'));
					
					$email_to_send_forgotten_password = $this->config->item('email_to_send_forgotten_password', 'skeleton_auth');
					
					$this->email->to($user->$email_to_send_forgotten_password);

					//DEBUG:
					//echo "Preparing to send email! TO: " . $user->$email_to_send_forgotten_password . "<br/>";

					$this->email->subject($this->config->item('site_title', 'skeleton_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject'));
					$this->email->message($message);
					
					

					if ($this->email->send())
					{
						$this->set_message('forgot_password_successful');
						return TRUE;
					}
					else
					{
						$this->set_error('forgot_password_unsuccessful');
						//echo "EMAIL NOT SEND!<br/>";
						//echo $this->email->print_debugger();
						return FALSE;
					}
				}
			}
			else
			{
				$this->set_error('forgot_password_unsuccessful');
				return FALSE;
			}
		}
		else
		{
			$this->set_error('forgot_password_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * forgotten_password_complete
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code)
	{
		$this->skeleton_auth_model->trigger_events('pre_password_change');

		$identity = $this->config->item('identity', 'skeleton_auth');
		$profile  = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!$profile)
		{
			$this->skeleton_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$new_password = $this->skeleton_auth_model->forgotten_password_complete($code, $profile->salt);

		if ($new_password)
		{
			$data = array(
				'identity'     => $profile->{$identity},
				'new_password' => $new_password
			);
			if(!$this->config->item('use_ci_email', 'skeleton_auth'))
			{
				$this->set_message('password_change_successful');
				$this->skeleton_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
					return $data;
			}
			else
			{
				$message = $this->load->view($this->config->item('email_templates', 'skeleton_auth').$this->config->item('email_forgot_password_complete', 'skeleton_auth'), $data, true);

				$this->email->clear();
				$this->email->from($this->config->item('admin_email', 'skeleton_auth'), $this->config->item('site_title', 'skeleton_auth'));
				$this->email->to($profile->email);
				$this->email->subject($this->config->item('site_title', 'skeleton_auth') . ' - ' . $this->lang->line('email_new_password_subject'));
				$this->email->message($message);

				if ($this->email->send())
				{
					$this->set_message('password_change_successful');
					$this->skeleton_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
					return TRUE;
				}
				else
				{
					$this->set_error('password_change_unsuccessful');
					$this->skeleton_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
					return FALSE;
				}

			}
		}

		$this->skeleton_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
		return FALSE;
	}

	/**
	 * forgotten_password_check
	 *
	 * @return void
	 * @author Michael
	 **/
	public function forgotten_password_check($code)
	{
		$profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!is_object($profile))
		{
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}
		else
		{
			if ($this->config->item('forgot_password_expiration', 'skeleton_auth') > 0) {
				//Make sure it isn't expired
				$expiration = $this->config->item('forgot_password_expiration', 'skeleton_auth');
				if (time() - $profile->forgotten_password_time > $expiration) {
					//it has expired
					$this->clear_forgotten_password_code($code);
					$this->set_error('password_change_unsuccessful');
					return FALSE;
				}
			}
			return $profile;
		}
	}

	/**
	 * register
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function register($username, $password, $email, $additional_data = array(), $group_ids = array()) //need to test email activation
	{
		$this->skeleton_auth_model->trigger_events('pre_account_creation');

		$email_activation = $this->config->item('email_activation', 'skeleton_auth');

		if (!$email_activation)
		{
			$id = $this->skeleton_auth_model->register($username, $password, $email, $additional_data, $group_ids);
			if ($id !== FALSE)
			{
				$this->set_message('account_creation_successful');
				$this->skeleton_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful'));
				return $id;
			}
			else
			{
				$this->set_error('account_creation_unsuccessful');
				$this->skeleton_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}
		}
		else
		{
			$id = $this->skeleton_auth_model->register($username, $password, $email, $additional_data, $group_ids);

			if (!$id)
			{
				$this->set_error('account_creation_unsuccessful');
				return FALSE;
			}

			$deactivate = $this->skeleton_auth_model->deactivate($id);

			if (!$deactivate)
			{
				$this->set_error('deactivate_unsuccessful');
				$this->skeleton_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}

			$activation_code = $this->skeleton_auth_model->activation_code;
			$identity        = $this->config->item('identity', 'skeleton_auth');
			$user            = $this->skeleton_auth_model->user($id)->row();

			$data = array(
				'identity'   => $user->{$identity},
				'id'         => $user->id,
				'email'      => $email,
				'activation' => $activation_code,
			);
			if(!$this->config->item('use_ci_email', 'skeleton_auth'))
			{
				$this->skeleton_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
				$this->set_message('activation_email_successful');
					return $data;
			}
			else
			{
				$message = $this->load->view($this->config->item('email_templates', 'skeleton_auth').$this->config->item('email_activate', 'skeleton_auth'), $data, true);

				$this->email->clear();
				$this->email->from($this->config->item('admin_email', 'skeleton_auth'), $this->config->item('site_title', 'skeleton_auth'));
				$this->email->to($email);
				$this->email->subject($this->config->item('site_title', 'skeleton_auth') . ' - ' . $this->lang->line('email_activation_subject'));
				$this->email->message($message);

				if ($this->email->send() == TRUE)
				{
					$this->skeleton_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
					$this->set_message('activation_email_successful');
					return $id;
				}
			}

			$this->skeleton_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful'));
			$this->set_error('activation_email_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * logout
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logout()
	{
		$this->skeleton_auth_model->trigger_events('logout');

		$identity = $this->config->item('identity', 'skeleton_auth');
                $this->session->unset_userdata( array($identity => '', 'id' => '', 'user_id' => '') );

		//delete the remember me cookies if they exist
		if (get_cookie('identity'))
		{
			delete_cookie('identity');
		}
		if (get_cookie('remember_code'))
		{
			delete_cookie('remember_code');
		}

		//Destroy the session
		$this->session->sess_destroy();

		//Recreate the session
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->session->sess_create();
		}

		$this->set_message('logout_successful');
		return TRUE;
	}

	/**
	 * logged_in
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function logged_in()
	{
		$this->skeleton_auth_model->trigger_events('logged_in');

		//return (bool) $this->session->userdata('identity');
		return (bool) $this->session->userdata('logged_in');
	}

	/**
	 * logged_in
	 *
	 * @return integer
	 * @author jrmadsen67
	 **/
	public function get_user_id()
	{
		$user_id = $this->session->userdata('user_id');
		if (!empty($user_id))
		{
			return $user_id;
		}
		return null;
	}


	/**
	 * is_admin
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function is_admin($id=false)
	{
		$this->skeleton_auth_model->trigger_events('is_admin');

		$admin_group = $this->config->item('admin_group', 'skeleton_auth');
		return $this->in_group($admin_group, $id);
	}

	/**
	 * in_group
	 *
	 * @param mixed group(s) to check
	 * @param bool user id
	 * @param bool check if all groups is present, or any of the groups
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function in_group($check_group, $id=false, $check_all = false)
	{
		$this->skeleton_auth_model->trigger_events('in_group');

		$id || $id = $this->session->userdata('user_id');

		if (!is_array($check_group))
		{
			$check_group = array($check_group);
		}

		if (isset($this->_cache_user_in_group[$id]))
		{
			$groups_array = $this->_cache_user_in_group[$id];
		}
		else
		{
			$users_groups = $this->skeleton_auth_model->get_users_groups($id)->result();
			$groups_array = array();
			foreach ($users_groups as $group)
			{
				$groups_array[$group->id] = $group->name;
			}
			$this->_cache_user_in_group[$id] = $groups_array;
		}
		foreach ($check_group as $key => $value)
		{
			$groups = (is_string($value)) ? $groups_array : array_keys($groups_array);

			/**
			 * if !all (default), in_array
			 * if all, !in_array
			 */
			if (in_array($value, $groups) xor $check_all)
			{
				/**
				 * if !all (default), true
				 * if all, false
				 */
				return !$check_all;
			}
		}

		/**
		 * if !all (default), false
		 * if all, true
		 */
		return $check_all;
	}

}
