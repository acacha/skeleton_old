<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  Create by Sergi Tur Badenas. Supports MySQL and Ldap
 * 
 *  Based on:
 * 
* Name:  Ion Auth Model
*
* Author:  Ben Edmunds
* 		   ben.edmunds@gmail.com
*	  	   @benedmunds
** 
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Skeleton_auth_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	public $tables = array();

	/**
	 * activation code
	 *
	 * @var string
	 **/
	public $activation_code;

	/**
	 * forgotten password key
	 *
	 * @var string
	 **/
	public $forgotten_password_code;

	/**
	 * new password
	 *
	 * @var string
	 **/
	public $new_password;

	/**
	 * Identity
	 *
	 * @var string
	 **/
	public $identity;

	/**
	 * Where
	 *
	 * @var array
	 **/
	public $_ion_where = array();

	/**
	 * Select
	 *
	 * @var array
	 **/
	public $_ion_select = array();

	/**
	 * Like
	 *
	 * @var array
	 **/
	public $_ion_like = array();

	/**
	 * Limit
	 *
	 * @var string
	 **/
	public $_ion_limit = NULL;

	/**
	 * Offset
	 *
	 * @var string
	 **/
	public $_ion_offset = NULL;

	/**
	 * Order By
	 *
	 * @var string
	 **/
	public $_ion_order_by = NULL;

	/**
	 * Order
	 *
	 * @var string
	 **/
	public $_ion_order = NULL;

	/**
	 * Hooks
	 *
	 * @var object
	 **/
	protected $_ion_hooks;

	/**
	 * Response
	 *
	 * @var string
	 **/
	protected $response = NULL;

	/**
	 * message (uses lang file)
	 *
	 * @var string
	 **/
	protected $messages;

	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 **/
	protected $errors;

	/**
	 * error start delimiter
	 *
	 * @var string
	 **/
	protected $error_start_delimiter;

	/**
	 * error end delimiter
	 *
	 * @var string
	 **/
	protected $error_end_delimiter;

	/**
	 * caching of users and their groups
	 *
	 * @var array
	 **/
	public $_cache_user_in_group = array();

	/**
	 * caching of groups
	 *
	 * @var array
	 **/
	protected $_cache_groups = array();
	
	/**
	 * realm
	 *
	 * @var string
	 **/
	protected $realm = "mysql";
	
	public function getRealm() {
        return $this->realm;
    }

    public function setRealm($realm) {
        $this->realm= $realm;
    }

	public function __construct()
	{
		
		//LOAD LDAP LIBRARY if Ldap is one of active realms
		$realms = explode(",",$this->config->item('realms','skeleton_auth'));
		if (in_array("ldap", $realms)) {
			$this->load->library('Auth_Ldap');	
		}
		
		parent::__construct();
		$this->load->database();
		$this->load->config('skeleton_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');

		//initialize db tables data
		$this->tables  = $this->config->item('tables', 'skeleton_auth');

		//initialize data
		$this->identity_column = $this->config->item('identity', 'skeleton_auth');
		$this->store_salt      = $this->config->item('store_salt', 'skeleton_auth');
		$this->salt_length     = $this->config->item('salt_length', 'skeleton_auth');
		$this->join			   = $this->config->item('join', 'skeleton_auth');


		//initialize hash method options (Bcrypt)
		$this->hash_method = $this->config->item('hash_method', 'skeleton_auth');
		$this->default_rounds = $this->config->item('default_rounds', 'skeleton_auth');
		$this->random_rounds = $this->config->item('random_rounds', 'skeleton_auth');
		$this->min_rounds = $this->config->item('min_rounds', 'skeleton_auth');
		$this->max_rounds = $this->config->item('max_rounds', 'skeleton_auth');


		//initialize messages and error
		$this->messages = array();
		$this->errors = array();
		$this->message_start_delimiter = $this->config->item('message_start_delimiter', 'skeleton_auth');
		$this->message_end_delimiter   = $this->config->item('message_end_delimiter', 'skeleton_auth');
		$this->error_start_delimiter   = $this->config->item('error_start_delimiter', 'skeleton_auth');
		$this->error_end_delimiter     = $this->config->item('error_end_delimiter', 'skeleton_auth');

		//initialize our hooks object
		$this->_ion_hooks = new stdClass;

		//load the bcrypt class if needed
		if ($this->hash_method == 'bcrypt') {
			if ($this->random_rounds)
			{
				$rand = rand($this->min_rounds,$this->max_rounds);
				$rounds = array('rounds' => $rand);
			}
			else
			{
				$rounds = array('rounds' => $this->default_rounds);
			}

			$this->load->library('bcrypt',$rounds);
		}

		$this->trigger_events('model_constructor');
	}
	
	private function _init_ldap() {
		// Load the configuration
        $CI =& get_instance();

        $CI->load->config('auth_ldap');

        // Verify that the LDAP extension has been loaded/built-in
        // No sense continuing if we can't
        if (! function_exists('ldap_connect')) {
            show_error(lang('php_ldap_notpresent'));
            log_message('error', lang('php_ldap_notpresent_log'));
        }

        $this->hosts = $CI->config->item('hosts');
        $this->ports = $CI->config->item('ports');
        $this->basedn = $CI->config->item('basedn');
        $this->account_ou = $CI->config->item('account_ou');
        $this->login_attribute  = $CI->config->item('login_attribute');
        $this->use_ad = $CI->config->item('use_ad');
        $this->ad_domain = $CI->config->item('ad_domain');
        $this->proxy_user = $CI->config->item('proxy_user');
        $this->proxy_pass = $CI->config->item('proxy_pass');
        $this->roles = $CI->config->item('roles');
        $this->auditlog = $CI->config->item('auditlog');
        $this->member_attribute = $CI->config->item('member_attribute');
        
    }

	/**
	 * Misc functions
	 *
	 * Hash password : Hashes the password to be stored in the database.
	 * Hash password db : This function takes a password and validates it
	 * against an entry in the users table.
	 * Salt : Generates a random salt value.
	 *
	 * @author Mathew
	 */

	public function hash_password($password, $salt=false, $use_sha1_override=FALSE)
	{
		if (empty($password))
		{
			return FALSE;
		}

		return  md5($password);
		
	}

	/**
	 * Hashes the password to be stored in the database.
	 *
	 * @return void
	 * @author Mathew
	 **/

	/*
	public function hash_password($password, $salt=false, $use_sha1_override=FALSE)
	{
		if (empty($password))
		{
			return FALSE;
		}

		//bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			return $this->bcrypt->hash($password);
		}


		if ($this->store_salt && $salt)
		{
			return  sha1($password . $salt);
		}
		else
		{
			$salt = $this->salt();
			return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}

*/
	/**
	 * This function takes a password and validates it
	 * against an entry in the users table.
	 *
	 * @return void
	 * @author Mathew
	 **/
	/*
	public function hash_password_db($id, $password, $use_sha1_override=FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->select('password, salt')
		                  ->where('id', $id)
		                  ->limit(1)
		                  ->get($this->tables['users']);

		$hash_password_db = $query->row();

		if ($query->num_rows() !== 1)
		{
			return FALSE;
		}

		// bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password,$hash_password_db->password))
			{
				return TRUE;
			}

			return FALSE;
		}

		// sha1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);

			$db_password =  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}

		if($db_password == $hash_password_db->password)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	*/

	public function hash_password_db($id, $password, $use_sha1_override=FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->select('password, salt')
		                  ->where('id', $id)
		                  ->limit(1)
		                  ->get($this->tables['users']);

		$hash_password_db = $query->row();

		if ($query->num_rows() !== 1)
		{
			return FALSE;
		}

		// bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password,$hash_password_db->password))
			{
				return TRUE;
			}

			return FALSE;
		}

		$db_password = md5($password);
		

		if($db_password == $hash_password_db->password)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}



	/**
	 * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_code($password)
	{
		return $this->hash_password($password, FALSE, TRUE);
	}

	/**
	 * Generates a random salt value.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function salt()
	{
		return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
	}

	/**
	 * Activation functions
	 *
	 * Activate : Validates and removes activation code.
	 * Deactivae : Updates a users row with an activation code.
	 *
	 * @author Mathew
	 */

	/**
	 * activate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function activate($id, $code = false)
	{
		$this->trigger_events('pre_activate');

		if ($code !== FALSE)
		{
			$query = $this->db->select($this->identity_column)
			                  ->where('activation_code', $code)
			                  ->limit(1)
			                  ->get($this->tables['users']);

			$result = $query->row();

			if ($query->num_rows() !== 1)
			{
				$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
				$this->set_error('activate_unsuccessful');
				return FALSE;
			}

			$identity = $result->{$this->identity_column};

			$data = array(
			    'activation_code' => NULL,
			    'active'          => 1
			);

			$this->trigger_events('extra_where');
			$this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));
		}
		else
		{
			$data = array(
			    'activation_code' => NULL,
			    'active'          => 1
			);


			$this->trigger_events('extra_where');
			$this->db->update($this->tables['users'], $data, array('id' => $id));
		}


		$return = $this->db->affected_rows() == 1;
		if ($return)
		{
			$this->trigger_events(array('post_activate', 'post_activate_successful'));
			$this->set_message('activate_successful');
		}
		else
		{
			$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
			$this->set_error('activate_unsuccessful');
		}


		return $return;
	}


	/**
	 * Deactivate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function deactivate($id = NULL)
	{
		$this->trigger_events('deactivate');

		if (!isset($id))
		{
			$this->set_error('deactivate_unsuccessful');
			return FALSE;
		}

		$activation_code       = sha1(md5(microtime()));
		$this->activation_code = $activation_code;

		$data = array(
		    'activation_code' => $activation_code,
		    'active'          => 0
		);

		$this->trigger_events('extra_where');
		$this->db->update($this->tables['users'], $data, array('id' => $id));

		$return = $this->db->affected_rows() == 1;
		if ($return)
			$this->set_message('deactivate_successful');
		else
			$this->set_error('deactivate_unsuccessful');

		return $return;
	}

	public function clear_forgotten_password_code($code) {

		if (empty($code))
		{
			return FALSE;
		}

		$this->db->where('forgotten_password_code', $code);

		if ($this->db->count_all_results($this->tables['users']) > 0)
		{
			$data = array(
			    'forgotten_password_code' => NULL,
			    'forgotten_password_time' => NULL,
			    'forgotten_password_realm' => NULL
			);

			$this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

			return TRUE;
		}

		return FALSE;
	}
	
	protected function generate_md5_hash($pwd)	{
		return  "{MD5}".base64_encode( pack('H*', md5($pwd)));
	}
	
	public function userHaveShadowAccount($dn) {
		$return_value=false;
		
		if ($this->_bind()) {
			$required_attributes=array("objectClass");
			$filter = '(objectClass=posixAccount)';		
			$search = ldap_search($this->ldapconn, $dn, $filter,$required_attributes);
        	$user = ldap_get_entries($this->ldapconn, $search);
        	
        	if ($user["count"] != 0) {		
				if (in_array("shadowAccount", $user[0]["objectclass"])) {
					$return_value=true;	
				}
			}
		}
		return $return_value;
	}
	
	/*! \brief Generate samba hashes
	*
	* Given a certain password this constructs an array like
	* array['sambaLMPassword'] etc.
	*
	* \param string 'password'
	* \return array contains several keys for lmPassword, ntPassword, pwdLastSet, etc. depending
	* on the samba version
	*/
	protected function generate_smb_nt_hash($password)	{
	
		$password = addcslashes($password, '$'); // <- Escape $ twice for transport from PHP to console-process.
		$password = addcslashes($password, '$'); 
		$password = addcslashes($password, '$'); // <- And again once, to be able to use it as parameter for the perl script.
		
		$command='perl -MCrypt::SmbHash -e "print join(q[:], ntlmgen %password), $/;"';
		$tmp = $command ;
		$tmp = preg_replace("/%userPassword/", escapeshellarg($password), $tmp);
		$tmp = preg_replace("/%password/", escapeshellarg($password), $tmp);
		
		exec($tmp, $ar);
		reset($ar);
		$hash= current($ar);
	
		if ($hash == "") {
			show_error("Configuration error: " . sprintf("Generating SAMBA hash by running %s failed: check %s!", $command, "sambaHashHook"));
			return(array());
		}
		
		list($lm,$nt)= explode(":", trim($hash));
		
		$attrs['sambaLMPassword']= $lm;
		$attrs['sambaNTPassword']= $nt;
		//$attrs['sambaPwdLastSet']= date('U');
		$attrs['sambaBadPasswordCount']= "0";
		$attrs['sambaBadPasswordTime']= "0";
		return($attrs);
	}
	
	public function change_ldap_password ($dn, $password)	{
		
		$newpass= "";
		// Not sure, why this is here, but maybe some encryption methods require it.
		mt_srand((double) microtime()*1000000);
		
		//GET_CURRENT_VALUES: "shadowLastChange", "userPassword","sambaNTPassword","sambaLMPassword", "uid", "objectClass"
		// Using dn
		$shadowAccountBool=true;
		
		$shadowAccountBool=$this->userHaveShadowAccount($dn);
		
		//Generate HASH NEW PASS for posixAccount
		$newpass= $this->generate_md5_hash($password);
		
		$attrs= array();
		
		$attrs= $this->generate_smb_nt_hash($password);
		if(!count($attrs) || !is_array($attrs)){
			show_error("Error: cannot generate SAMBA hash! ");
			return(FALSE);    
		}
		
		$attrs['userPassword']= $newpass;
		
		// For posixUsers - Set the last changed value.
        if($shadowAccountBool){
            $attrs['shadowLastChange'] = (int)(date("U") / 86400);
        }
        
        // Perform ldap operations
        return $this->changeLdapPassword($dn,$attrs);
	}
	
	protected function _bind() {        
        //Connect
        foreach($this->hosts as $host) {
            $this->ldapconn = ldap_connect($host);
            if($this->ldapconn) {
               break;
            }else {
                log_message('info', lang('error_connecting_to'). ' ' .$uri);
            }
        }
        
        // At this point, $this->ldapconn should be set.  If not... DOOM!
        if(! $this->ldapconn) {
            log_message('error', lang('could_not_connect_to_ldap'));
            show_error(lang('error_connecting_to_ldap'));
        }

       
        // These to ldap_set_options are needed for binding to AD properly
        // They should also work with any modern LDAP service.
        ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        
        // Find the DN of the user we are binding as
        // If proxy_user and proxy_pass are set, use those, else bind anonymously
        if($this->proxy_user) {
            $bind = @ldap_bind($this->ldapconn, $this->proxy_user, $this->proxy_pass);
        }else {
            $bind = @ldap_bind($this->ldapconn);
        }

        if(!$bind){
            log_message('error', lang('unable_anonymous'));
            show_error(lang('unable_bind'));
            return false;
        }   
        return true;
	}

	
	public function changeLdapPassword($user_dn,$attrs) {
		
		$this->_init_ldap();
		
		if ($this->_bind()) {
			//Debug:
			//echo "ldap modify:<br/>";
			//echo "user_dn: " . $user_dn . "<br/>";
			//echo "attrs: " . var_export($attrs) . "<br/>";
			if (ldap_modify($this->ldapconn,$user_dn,$attrs) === false){
				$error = ldap_error($this->ldapconn);
				$errno = ldap_errno($this->ldapconn);
				show_error("Ldap error changing password: " . $errno . " - " . $error);
				return false;
			} else {
				return true;
			}
		}
		return false;
	}
	
	public function getDNByIdentity($identity,$basedn=null) {
		
		$this->_init_ldap();
		
		if ($this->_bind()) {
			$needed_attrs = array('dn');
			$filter = '(uid='.$identity.')';
			if ($basedn == null)
				$basedn = $this->basedn;
			$search = ldap_search($this->ldapconn, $basedn, $filter, 
                $needed_attrs);
        
			$entries = ldap_get_entries($this->ldapconn, $search);
	
			if($entries['count'] != 0) {
				$dn = $entries[0]['dn'];
				return $dn;
			} else {
				$this->_audit("Ldap ERROR!");
				return FALSE;
			}
		}
		return false;
	}
	
	public function reset_password_ldap($identity, $new_password) {
		
		$this->trigger_events('pre_change_password');
		
		
		$dn = $this->getDNByIdentity($identity);
		
		$return_value = $this->change_ldap_password($dn, $new_password);
				
		if ($return_value)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
			$this->set_message('password_change_successful');
		}
		else
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
		}

		return $return_value;
	}
	
	public function reset_password_mysql($identity, $new_password) {
		$this->trigger_events('pre_change_password');

		if (!$this->identity_check($identity)) {
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->select('id, password, salt')
		                  ->where($this->identity_column, $identity)
		                  ->limit(1)
		                  ->get($this->tables['users']);

		if ($query->num_rows() !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$result = $query->row();

		$new_password = $this->hash_password($new_password, $result->salt);

		//store the new password and reset the remember code so all remembered instances have to re-login
		//also clear the forgotten password code
		$data = array(
		    'password' => $new_password,
		    'initial_password' => '',
            'force_change_password_next_login' => 'n',
            'last_modification_user' => $result->id,
			'active' => 1, 
		    'remember_code' => NULL,
		    'forgotten_password_code' => NULL,
		    'forgotten_password_time' => NULL,
		);

		$this->trigger_events('extra_where');
		$this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));

		$return = $this->db->affected_rows() == 1;
		if ($return)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
			$this->set_message('password_change_successful');
		}
		else
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
		}

		return $return;
	}

	/**
	 * reset password
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function reset_password($identity, $new_password) {
		$query = $this->db->select('forgotten_password_realm')
		                  ->where($this->identity_column, $identity)
		                  ->limit(1)
		                  ->get($this->tables['users']);

		if ($query->num_rows() !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}
		
		$realm= $query->row()->forgotten_password_realm;

		$force_change_on_both_realms = $this->config->item('force_change_on_both_realms', 'skeleton_auth');
		if ($force_change_on_both_realms) {
			$result = $this->reset_password_ldap($identity, $new_password);
			if ($result) {
				return $this->reset_password_mysql($identity, $new_password);	
			} else {
				return false;
			}
			
		} else {
			if ($realm == "ldap")  {
				return $this->reset_password_ldap($identity, $new_password);
			} else {
				return $this->reset_password_mysql($identity, $new_password);
			}	
		}
		
	}

	/**
	 * change password
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function change_password($identity, $old, $new)
	{
		$this->trigger_events('pre_change_password');

		$this->trigger_events('extra_where');

		$query = $this->db->select('id, password, salt')
		                  ->where($this->identity_column, $identity)
		                  ->limit(1)
		                  ->get($this->tables['users']);

		if ($query->num_rows() !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$user = $query->row();

		$old_password_matches = $this->hash_password_db($user->id, $old);

		if ($old_password_matches === TRUE)
		{
			//store the new password and reset the remember code so all remembered instances have to re-login
			$hashed_new_password  = $this->hash_password($new, $user->salt);
			$data = array(
			    'password' => $hashed_new_password,
			    'remember_code' => NULL,
			);

			$this->trigger_events('extra_where');

			$successfully_changed_password_in_db = $this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));
			if ($successfully_changed_password_in_db)
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
				$this->set_message('password_change_successful');
			}
			else
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
				$this->set_error('password_change_unsuccessful');
			}

			return $successfully_changed_password_in_db;
		}

		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}

	/**
	 * Checks username
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function username_check($username = '')
	{
		$this->trigger_events('username_check');

		if (empty($username))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');
		
		$users_found = $this->db->where('username', $username)
		                ->count_all_results($this->tables['users']);
		$result= $users_found > 0;
		return $result;
	}

	/**
	 * Checks email
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function email_check($email = '')
	{
		$this->trigger_events('email_check');

		if (empty($email))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		return $this->db->where('email', $email)
		                ->count_all_results($this->tables['users']) > 0;
	}

	/**
	 * Identity check
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function identity_check($identity = '')
	{
		$this->trigger_events('identity_check');

		if (empty($identity))
		{
			return FALSE;
		}

		return $this->db->where($this->identity_column, $identity)
		                ->count_all_results($this->tables['users']) > 0;
	}

	/**
	 * Insert a forgotten password key.
	 *
	 * @return bool
	 * @author Mathew
	 * @updated Ryan
	 * @updated 52aa456eef8b60ad6754b31fbdcc77bb
	 **/
	public function forgotten_password($identity,$identity_column="",$realm="mysql")
	{
		
		//DEBUG:
		//echo "parameters:<br/>";
		//echo "identity: " . $identity . "<br/>";
		//echo "identity_column: " . $identity_column . "<br/>";
		//echo "realm: " . $realm . "<br/>";

		if (empty($identity))
		{
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
			return FALSE;
		}
		//All some more randomness
		$activation_code_part = "";
		if(function_exists("openssl_random_pseudo_bytes")) {
			$activation_code_part = openssl_random_pseudo_bytes(128);
		}
		
		for($i=0;$i<1024;$i++) {
			$activation_code_part = sha1($activation_code_part . mt_rand() . microtime());
		}
		
		$key = $this->hash_code($activation_code_part.$identity);

		$this->forgotten_password_code = $key;

		$this->trigger_events('extra_where');

		$update = array(
		    'forgotten_password_code' => $key,
		    'forgotten_password_time' => time(),
		    'forgotten_password_realm' => $realm
		);
		
		$identity_field="";
		if ($identity_column == "") {
			$identity_field=$this->identity_column;
		} else {
			if ($identity_column =="email") {
				$identity_field="person_email";
			} elseif ($identity_column=="username") {
				$identity_field="username";
			} else {
				return false;	
			}
		}

		/* Example:
		UPDATE `users`
		INNER JOIN person ON person.person_id = users.person_id
		SET `forgotten_password_code` = '19cea29fcb89c6ba137b58d4b79626cd', `forgotten_password_time` = 1412066629, `forgotten_password_realm` = 'ldap' 
		WHERE `person_email` = 'sergiturbadenas@gmail.com' OR `person_secondary_email` = 'sergiturbadenas@gmail.com'
		*/
		$this->db->where(array( $identity_field => $identity));
		if ($identity_field == "person_email") {
			$this->db->or_where(array( "person_secondary_email" => $identity));	
			$this->db->update($this->tables['users'] . " INNER JOIN person ON person.person_id = users.person_id", $update);
		} elseif ($identity_field == "username") {
			$this->db->update($this->tables['users'], $update);
		} else {
			return false;
		}
		

		//echo $this->db->last_query();
		
		$return = $this->db->affected_rows() == 1;

		if ($return)
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_successful'));
		else
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
				
		return $return;
	}

	/**
	 * Forgotten Password Complete
	 *
	 * @return string
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code, $salt=FALSE)
	{
		$this->trigger_events('pre_forgotten_password_complete');

		if (empty($code))
		{
			$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
			return FALSE;
		}

		$profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if ($profile) {

			if ($this->config->item('forgot_password_expiration', 'skeleton_auth') > 0) {
				//Make sure it isn't expired
				$expiration = $this->config->item('forgot_password_expiration', 'skeleton_auth');
				if (time() - $profile->forgotten_password_time > $expiration) {
					//it has expired
					$this->set_error('forgot_password_expired');
					$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
					return FALSE;
				}
			}

			$password = $this->salt();

			$data = array(
			    'password'                => $this->hash_password($password, $salt),
			    'forgotten_password_code' => NULL,
			    'active'                  => 1,
			 );

			$this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

			$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_successful'));
			return $password;
		}

		$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
		return FALSE;
	}

	/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function register($username, $password, $email, $additional_data = array(), $groups = array())
	{
		$this->trigger_events('pre_register');

		$manual_activation = $this->config->item('manual_activation', 'skeleton_auth');

		if ($this->identity_column == 'email' && $this->email_check($email))
		{
			$this->set_error('account_creation_duplicate_email');
			return FALSE;
		}
		elseif ($this->identity_column == 'username' && $this->username_check($username))
		{
			$this->set_error('account_creation_duplicate_username');
			return FALSE;
		}

		// If username is taken, use username1 or username2, etc.
		if ($this->identity_column != 'username')
		{
			$original_username = $username;
			for($i = 0; $this->username_check($username); $i++)
			{
				if($i > 0)
				{
					$username = $original_username . $i;
				}
			}
		}
                 
                if(is_null($email)){
                	$email=''; 
		  }
		// IP Address
		$ip_address = $this->_prepare_ip($this->input->ip_address());
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$password   = $this->hash_password($password, $salt);

		$person_id = 0;
		//Search person_id

		//TODO: check if table person exists
		$table_person_exists =  true;

		if ($table_person_exists) {
			//Search if exists a person with username in table person
			//Example SQL: SELECT `person_id` FROM `person` WHERE username_original_ldap="dsubirats"
			$query = $this->db->select('person_id')
		                  ->where('username_original_ldap', $username)
		                  ->limit(1)
		                  ->get('person');

			if ($query->num_rows() === 1)	{
				$person = $query->row();
				$person_id = $person->person_id;
			}
		}

		// Users table.
		$data = array(
		    'username'   => $username,
		    'password'   => $password,
		    'person_id'  => $person_id,	
		    'email'      => $email,
		    'ip_address' => $ip_address,
		    'created_on' => date('Y-m-d H:i:s'),
		    'last_login' => date('Y-m-d H:i:s'),
		    'active'     => ($manual_activation === false ? 1 : 0)
		);

		if ($this->store_salt)
		{
			$data['salt'] = $salt;
		}

		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$user_data = array_merge($this->_filter_data($this->tables['users'], $additional_data), $data);

		$this->trigger_events('extra_set');
		
		$this->db->insert($this->tables['users'], $user_data);

		$id = $this->db->insert_id();

		if (!empty($groups))
		{
			//add to groups
			foreach ($groups as $group)
			{
				$this->add_to_group($group, $id);
			}
		}
		
		//Change made by Sergi Tur: firt check if default group exists in database before using it
		$default_group_query = $this->where('name', $this->config->item('default_group', 'skeleton_auth'))->group();
		if ($default_group_query->num_rows() > 0)	{
			$default_group = $default_group_query->row();
			if ((isset($default_group->id) && !isset($groups)) || (empty($groups) && !in_array($default_group->id, $groups)))
				{
				$this->add_to_group($default_group->id, $id);
				}
		}
		
		$this->trigger_events('post_register');

		return (isset($id)) ? $id : FALSE;
	}
	
	public function login($identity, $password, $remember = FALSE)
	{
		//GET REALM
		switch ($this->realm) {
			case "mysql":
				return $this->login_mysql($identity, $password, $remember);
				break;
			case "ldap":
				return $this->login_ldap($identity, $password, $remember);
				break;
			default:
				return $this->login_mysql($identity, $password, $remember);
				break;
		}
		
	}
	
	public function add_user_ifnotexists($identity,$password="") {
		// ADD USER TO users table if not exists
		if (!$this->username_check($identity)) {
			//NOT EXISTS -> ADD/REGISTER
			$additional_data = $this->auth_ldap->get_additional_data($identity);
			$email=$this->auth_ldap->get_email($identity);
			
			if ($password=="") {
				$password=substr(sha1(uniqid()), 0, 8);
			}
			$id=$this->register($identity, $password, $email, $additional_data);
		}	
	}


	public function get_user_preferencesId ($userid) {
                $this->db->select('user_preferencesId');
                $this->db->where('userId',$userid);
                $query = $this->db->get('user_preferences');
                if ($query->num_rows() > 0) 
                        return $query->row()->user_preferencesId;
                else
                        return false;
	}

	
	
	/**
	 * Checks credentials and logs the passed user in if possible.
	 *
	 * @return bool
	 */
	public function login_ldap($identity, $password, $remember = FALSE)
	{
		$this->trigger_events('pre_login');
		
		if (empty($identity) || empty($password))
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}
		
		//IN MYSQL HERE: $this->trigger_events('extra_where');
		//TODO: COULD WE DO SOMETHING SIMILAR IN LDAP?
		
		$return_value=$this->auth_ldap->login($identity, $password);
		
		switch ($return_value) {
			case 1:
				break;
			case -1:
				$this->increase_login_attempts($identity);
				$this->trigger_events('post_login_unsuccessful');
				$this->set_error('login_unsuccessful');
				return FALSE;
				break;
			case -2:
				$this->increase_login_attempts($identity);
				$this->trigger_events('post_login_unsuccessful');
				$this->set_error('login_unsuccessful_not_allowed_role');	
				return FALSE;
				break;
		}
		
		//AT THIS POINT USER HAS LOGGED CORRECTLY AT LDAP
		
		//CHECK IF ACCOUNT HAS TO BE LOCKED BY TOO MANY AUTH ATTEMPTS
		if($this->is_time_locked_out($identity))
		{
			//Hash something anyway, just to take up time
			$this->hash_password($password);

			$this->trigger_events('post_login_unsuccessful');
			$this->set_error('login_timeout');

			return FALSE;
		}
		
		//CORRECT LOGIN. SET DATA:
		
		$email;
		$id;
		$last_login;
		$username=$identity;
		$user = new stdClass;

		$create_mysql_user_if_exists_on_ldap = $this->config->item('create_mysql_user_if_exists_on_ldap', 'skeleton_auth');
		if (!$this->username_check($identity)) {
			if ($create_mysql_user_if_exists_on_ldap) {
				// ADD USER TO users table if not exists
				$this->add_user_ifnotexists($identity,$password);			
			} else {
				//ERROR
				$this->trigger_events('post_login_unsuccessful');
				$this->set_error('login_unsuccessful_not_registered_but_ldap_user_ok');
				return FALSE;
			}
		}
		
		
		if (!$this->username_check($identity)) {
			if ($create_mysql_user_if_exists_on_ldap) {
				//NOT EXISTS -> ADD/REGISTER
				$additional_data = $this->auth_ldap->get_additional_data($identity);
				$email=$this->auth_ldap->get_email($identity);
				$id=$this->register($identity, $password, $email, $additional_data);	
			} else {
				//ERROR
				$this->trigger_events('post_login_unsuccessful');
				$this->set_error('login_unsuccessful_not_registered');
				return FALSE;
			}
		}

		$database_user=$this->get_user_by_username($identity);

		$id=$database_user->id;
		$last_login=$database_user->last_login;
		
		//IS USER ACTIVE?
		if (!$this->is_user_active($identity)) {
			$this->trigger_events('post_login_unsuccessful');
			$this->set_error('login_unsuccessful_not_active');
			return FALSE;
		}

		//SET SESSION DATA
		$user->identity=$identity;
		$user->username=$username;
		$user->id=$id;
		$user->last_login=$last_login;
		
		$this->set_session($user);

		$this->update_last_login($user->id);
	
		$this->clear_login_attempts($identity);

		if ($remember && $this->config->item('remember_users', 'skeleton_auth')) {
			$this->remember_user($user->id);
		}
		
		//GET CURRENT ROLE INFO
		$current_rol_id = $this->session->userdata('role');
		$current_role_name=$this->_get_rolename_byId($current_rol_id);
		
		//SET CORRECT LDAP GRUPS IN DATABASE		
		
		//CHECK IF ROL EXISTS AS GROUP IN DATABASE
		if (! $this->_check_if_group_exists($current_role_name)) {
			//ADD ROLE AS GROUP AT DATABASE
			$group = $this->create_group($current_role_name, "Automatic group added as ldap skeleton role");
			if(!$group) {
				show_error($this->messages());
			}
		}
		
		//SET (IF NOT YET) LDAP ROLES AS DATABASE USER GROUPS
		$group_id = $this->_get_group_id_by_group_name($current_role_name);
		if (! $this->_check_if_user_group_exists($current_role_name) ) {
			$this->add_to_group($group_id,$user->id);
		}
		
		//USERS HAVE ONLY ON LDAP ROLE! -> DELETE OLD LDAP USERS GROUPS
		$ldap_roles = (array) $this->config->item('roles','skeleton_auth');
		$ldap_roles_without_current_role=array_diff($ldap_roles,(array) $current_role_name);
		
		$ldap_roles_database_keys=array();
		foreach ($ldap_roles_without_current_role as $ldaprole){
			$ldap_roles_database_keys[]=$this->_get_group_id_by_group_name($ldaprole);
		}
		
		//REMOVE USER FROM OTHER LDAP GROUPS:
		$this->remove_from_group($ldap_roles_database_keys, $user->id);
		
		$this->post_login_session_initialitze();
		return TRUE;
	}
	
	function post_login_session_initialitze() {
		//SET DEFAULT LANGUAGE
		if (!$this->session->userdata("current_language")) {
			$this->session->set_userdata("current_language",
										  $this->config->item('default_language','skeleton_auth'));
		}		
	}

	/**
	 * login
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function login_mysql($identity, $password, $remember=FALSE)
	{
		$this->trigger_events('pre_login');

		if (empty($identity) || empty($password))
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->select($this->identity_column . ', username, email, id, password, active, last_login')
		                  ->where($this->identity_column, $this->db->escape_str($identity))
		                  ->limit(1)
		                  ->get($this->tables['users']);

		if($this->is_time_locked_out($identity))
		{
			//Hash something anyway, just to take up time
			$this->hash_password($password);

			$this->trigger_events('post_login_unsuccessful');
			$this->set_error('login_timeout');

			return FALSE;
		}

		if ($query->num_rows() === 1)
		{
			$user = $query->row();

			$password = $this->hash_password_db($user->id, $password);
			if ($password === TRUE)
			{
				if ($user->active == 0)
				{
					$this->trigger_events('post_login_unsuccessful');
					$this->set_error('login_unsuccessful_not_active');

					return FALSE;
				}

				$this->set_session($user);

				$this->update_last_login($user->id);

				$this->clear_login_attempts($identity);

				if ($remember && $this->config->item('remember_users', 'skeleton_auth'))
				{
					$this->remember_user($user->id);
				}

				$this->trigger_events(array('post_login', 'post_login_successful'));
				$this->set_message('login_successful');
				
				$this->post_login_session_initialitze();
				return TRUE;
			}
		}

		//Hash something anyway, just to take up time
		$this->hash_password($password);

		$this->increase_login_attempts($identity);

		$this->trigger_events('post_login_unsuccessful');
		$this->set_error('login_unsuccessful');

		return FALSE;
	}

	/**
	 * is_max_login_attempts_exceeded
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity
	 * @return boolean
	 **/
	public function is_max_login_attempts_exceeded($identity) {
		if ($this->config->item('track_login_attempts', 'skeleton_auth')) {
			$max_attempts = $this->config->item('maximum_login_attempts', 'skeleton_auth');
			if ($max_attempts > 0) {
				$attempts = $this->get_attempts_num($identity);
				return $attempts >= $max_attempts;
			}
		}
		return FALSE;
	}

	/**
	 * Get number of attempts to login occured from given IP-address or identity
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param	string $identity
	 * @return	int
	 */
	function get_attempts_num($identity)
	{
		if ($this->config->item('track_login_attempts', 'skeleton_auth')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());

			$this->db->select('1', FALSE);
			$this->db->where('ip_address', $ip_address);
			if (strlen($identity) > 0) $this->db->or_where('login', $identity);

			$qres = $this->db->get($this->tables['login_attempts']);
			return $qres->num_rows();
		}
		return 0;
	}

	/**
	 * Get a boolean to determine if an account should be locked out due to
	 * exceeded login attempts within a given period
	 *
	 * @return	boolean
	 */
	public function is_time_locked_out($identity) {

		return $this->is_max_login_attempts_exceeded($identity) && $this->get_last_attempt_time($identity) > time() - $this->config->item('lockout_time', 'skeleton_auth');
	}

	/**
	 * Get the time of the last time a login attempt occured from given IP-address or identity
	 *
	 * @param	string $identity
	 * @return	int
	 */
	public function get_last_attempt_time($identity) {
		if ($this->config->item('track_login_attempts', 'skeleton_auth')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());

			$this->db->select_max('time');
			$this->db->where('ip_address', $ip_address);
			if (strlen($identity) > 0) $this->db->or_where('login', $identity);
			$qres = $this->db->get($this->tables['login_attempts'], 1);

			if($qres->num_rows() > 0) {
				return $qres->row()->time;
			}
		}

		return 0;
	}

	/**
	 * increase_login_attempts
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity
	 **/
	public function increase_login_attempts($identity) {
		if ($this->config->item('track_login_attempts', 'skeleton_auth')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());
			return $this->db->insert($this->tables['login_attempts'], array('ip_address' => $ip_address, 'login' => $identity, 'time' => time()));
		}
		return FALSE;
	}

	/**
	 * clear_login_attempts
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity
	 **/
	public function clear_login_attempts($identity, $expire_period = 86400) {
		if ($this->config->item('track_login_attempts', 'skeleton_auth')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());

			$this->db->where(array('ip_address' => $ip_address, 'login' => $identity));
			// Purge obsolete login attempts
			$this->db->or_where('time <', time() - $expire_period, FALSE);

			return $this->db->delete($this->tables['login_attempts']);
		}
		return FALSE;
	}

	public function limit($limit)
	{
		$this->trigger_events('limit');
		$this->_ion_limit = $limit;

		return $this;
	}

	public function offset($offset)
	{
		$this->trigger_events('offset');
		$this->_ion_offset = $offset;

		return $this;
	}

	public function where($where, $value = NULL)
	{
		$this->trigger_events('where');
		
		if (!is_array($where))
		{
			$where = array($where => $value);
		}
		array_push($this->_ion_where, $where);
		return $this;
	}

	public function like($like, $value = NULL)
	{
		$this->trigger_events('like');

		if (!is_array($like))
		{
			$like = array($like => $value);
		}

		array_push($this->_ion_like, $like);

		return $this;
	}

	public function select($select)
	{
		$this->trigger_events('select');

		$this->_ion_select[] = $select;

		return $this;
	}

	public function order_by($by, $order='desc')
	{
		$this->trigger_events('order_by');

		$this->_ion_order_by = $by;
		$this->_ion_order    = $order;

		return $this;
	}

	public function row()
	{
		$this->trigger_events('row');

		$row = $this->response->row();
		$this->response->free_result();

		return $row;
	}

	public function row_array()
	{
		$this->trigger_events(array('row', 'row_array'));

		$row = $this->response->row_array();
		$this->response->free_result();

		return $row;
	}

	public function result()
	{
		$this->trigger_events('result');

		$result = $this->response->result();
		$this->response->free_result();

		return $result;
	}

	public function result_array()
	{
		$this->trigger_events(array('result', 'result_array'));

		$result = $this->response->result_array();
		$this->response->free_result();

		return $result;
	}

	public function num_rows()
	{
		$this->trigger_events(array('num_rows'));

		$result = $this->response->num_rows();
		$this->response->free_result();

		return $result;
	}

	/**
	 * users
	 *
	 * @return object Users
	 * @author Ben Edmunds
	 **/
	public function users($groups = NULL)
	{
		$this->trigger_events('users');

		if (isset($this->_ion_select) && !empty($this->_ion_select))
		{
			foreach ($this->_ion_select as $select)
			{
				$this->db->select($select);
			}

			$this->_ion_select = array();
		}
		else
		{
			//default selects
			$this->db->select(array(
			    $this->tables['users'].'.*',
			    $this->tables['users'].'.id as id',
			    $this->tables['users'].'.id as user_id'
			));
		}

		//filter by group id(s) if passed
		if (isset($groups))
		{
			//build an array if only one group was passed
			if (is_numeric($groups))
			{
				$groups = Array($groups);
			}

			//join and then run a where_in against the group ids
			if (isset($groups) && !empty($groups))
			{
				$this->db->distinct();
				$this->db->join(
				    $this->tables['users_groups'],
				    $this->tables['users_groups'].'.'.$this->join['users'].'='.$this->tables['users'].'.id',
				    'inner'
				);

				$this->db->where_in($this->tables['users_groups'].'.'.$this->join['groups'], $groups);
			}
		}

		$this->trigger_events('extra_where');

		//run each where that was passed
		if (isset($this->_ion_where) && !empty($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->db->where($where);
			}

			$this->_ion_where = array();
		}

		if (isset($this->_ion_like) && !empty($this->_ion_like))
		{
			foreach ($this->_ion_like as $like)
			{
				$this->db->or_like($like);
			}

			$this->_ion_like = array();
		}

		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->db->limit($this->_ion_limit, $this->_ion_offset);

			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		else if (isset($this->_ion_limit))
		{
			$this->db->limit($this->_ion_limit);

			$this->_ion_limit  = NULL;
		}

		//set the order
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->db->order_by($this->_ion_order_by, $this->_ion_order);

			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

		$this->response = $this->db->get($this->tables['users']);
		
		return $this;
	}

	/**
	 * user
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function user($id = NULL)
	{
		$this->trigger_events('user');

		//if no id was passed use the current users id
		$id || $id = $this->session->userdata('user_id');

		$this->limit(1);
		$this->where($this->tables['users'].'.id', $id);

		$this->users();

		return $this;
	}

	/**
	 * get_users_groups
	 *
	 * @return array
	 * @author Ben Edmunds
	 **/
	public function get_users_groups($id=FALSE)
	{
		$this->trigger_events('get_users_group');

		//if no id was passed use the current users id
		$id || $id = $this->session->userdata('user_id');

		return $this->db->select($this->tables['users_groups'].'.'.$this->join['groups'].' as id, '.$this->tables['groups'].'.name, '.$this->tables['groups'].'.description')
		                ->where($this->tables['users_groups'].'.'.$this->join['users'], $id)
		                ->join($this->tables['groups'], $this->tables['users_groups'].'.'.$this->join['groups'].'='.$this->tables['groups'].'.id')
		                ->get($this->tables['users_groups']);
	}

	/**
	 * add_to_group
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function add_to_group($group_id, $user_id=false)
	{
		$this->trigger_events('add_to_group');

		//if no id was passed use the current users id
		$user_id || $user_id = $this->session->userdata('user_id');

		//check if unique - num_rows() > 0 means row found
		if ($this->db->where(array( $this->join['groups'] => (int)$group_id, $this->join['users'] => (int)$user_id))->get($this->tables['users_groups'])->num_rows()) return false;

		if ($return = $this->db->insert($this->tables['users_groups'], array( $this->join['groups'] => (int)$group_id, $this->join['users'] => (int)$user_id)))
		{
			if (isset($this->_cache_groups[$group_id])) {
				$group_name = $this->_cache_groups[$group_id];
			}
			else {
				$group = $this->group($group_id)->result();
				$group_name = $group[0]->name;
				$this->_cache_groups[$group_id] = $group_name;
			}
			$this->_cache_user_in_group[$user_id][$group_id] = $group_name;
		}
		return $return;
	}

	/**
	 * remove_from_group
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function remove_from_group($group_ids=false, $user_id=false)
	{
		$this->trigger_events('remove_from_group');

		// user id is required
		if(empty($user_id))
		{
			return FALSE;
		}

		// if group id(s) are passed remove user from the group(s)
		if( ! empty($group_ids))
		{
			if(!is_array($group_ids))
			{
				$group_ids = array($group_ids);
			}

			foreach($group_ids as $group_id)
			{
				$this->db->delete($this->tables['users_groups'], array($this->join['groups'] => (int)$group_id, $this->join['users'] => (int)$user_id));
				if (isset($this->_cache_user_in_group[$user_id]) && isset($this->_cache_user_in_group[$user_id][$group_id]))
				{
					unset($this->_cache_user_in_group[$user_id][$group_id]);
				}
			}

			$return = TRUE;
		}
		// otherwise remove user from all groups
		else
		{
			if ($return = $this->db->delete($this->tables['users_groups'], array($this->join['users'] => (int)$user_id))) {
				$this->_cache_user_in_group[$user_id] = array();
			}
		}
		return $return;
	}

	/**
	 * groups
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function groups()
	{
		$this->trigger_events('groups');

		//run each where that was passed
		if (isset($this->_ion_where) && !empty($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->db->where($where);
			}
			$this->_ion_where = array();
		}

		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->db->limit($this->_ion_limit, $this->_ion_offset);

			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		else if (isset($this->_ion_limit))
		{
			$this->db->limit($this->_ion_limit);

			$this->_ion_limit  = NULL;
		}

		//set the order
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->db->order_by($this->_ion_order_by, $this->_ion_order);
		}

		$this->response = $this->db->get($this->tables['groups']);

		return $this;
	}

	/**
	 * group
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function group($id = NULL)
	{
		$this->trigger_events('group');

		if (isset($id))
		{
			$this->db->where($this->tables['groups'].'.id', $id);
		}

		$this->limit(1);

		return $this->groups();
	}

	/**
	 * update
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function update($id, array $data)
	{
		$this->trigger_events('pre_update_user');

		$user = $this->user($id)->row();

		$this->db->trans_begin();

		if (array_key_exists($this->identity_column, $data) && $this->identity_check($data[$this->identity_column]) && $user->{$this->identity_column} !== $data[$this->identity_column])
		{
			$this->db->trans_rollback();
			$this->set_error('account_creation_duplicate_'.$this->identity_column);

			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');

			return FALSE;
		}

		// Filter the data passed
		$data = $this->_filter_data($this->tables['users'], $data);

		if (array_key_exists('username', $data) || array_key_exists('password', $data) || array_key_exists('email', $data))
		{
			if (array_key_exists('password', $data))
			{
				if( ! empty($data['password']))
				{
					$data['password'] = $this->hash_password($data['password'], $user->salt);
				}
				else
				{
					// unset password so it doesn't effect database entry if no password passed
					unset($data['password']);
				}
			}
		}

		$this->trigger_events('extra_where');
		$this->db->update($this->tables['users'], $data, array('id' => $user->id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->trigger_events(array('post_update_user', 'post_update_user_successful'));
		$this->set_message('update_successful');
		return TRUE;
	}

	/**
	* delete_user
	*
	* @return bool
	* @author Phil Sturgeon
	**/
	public function delete_user($id)
	{
		$this->trigger_events('pre_delete_user');

		$this->db->trans_begin();

		// remove user from groups
		$this->remove_from_group(NULL, $id);

		// delete user from users table should be placed after remove from group
		$this->db->delete($this->tables['users'], array('id' => $id));

		// if user does not exist in database then it returns FALSE else removes the user from groups
		if ($this->db->affected_rows() == 0)
		{
		    return FALSE;
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->trigger_events(array('post_delete_user', 'post_delete_user_unsuccessful'));
			$this->set_error('delete_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->trigger_events(array('post_delete_user', 'post_delete_user_successful'));
		$this->set_message('delete_successful');
		return TRUE;
	}

	/**
	 * update_last_login
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function update_last_login($id)
	{
		$this->trigger_events('update_last_login');

		$this->load->helper('date');

		$this->trigger_events('extra_where');

		$this->db->update($this->tables['users'], array('last_login' => date('Y-m-d H:i:s')), array('id' => $id));

		return $this->db->affected_rows() == 1;
	}

	/**
	 * set_lang
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function set_lang($lang = 'en')
	{
		$this->trigger_events('set_lang');

		// if the user_expire is set to zero we'll set the expiration two years from now.
		if($this->config->item('user_expire', 'skeleton_auth') === 0)
		{
			$expire = (60*60*24*365*2);
		}
		// otherwise use what is set
		else
		{
			$expire = $this->config->item('user_expire', 'skeleton_auth');
		}

		set_cookie(array(
			'name'   => 'lang_code',
			'value'  => $lang,
			'expire' => $expire
		));

		return TRUE;
	}

	/**
	 * remember_user
	 *
	 * @return bool
	 * @author jrmadsen67
	 **/
	public function set_session($user)
	{

		$this->trigger_events('pre_set_session');

		$session_data = array(
		    'identity'             => $user->{$this->identity_column},
		    'username'             => $user->username,
		    'user_id'              => $user->id, //everyone likes to overwrite id so we'll use user_id
		    'old_last_login'       => $user->last_login
		);

		$this->session->set_userdata($session_data);

		$this->trigger_events('post_set_session');

		return TRUE;
	}

	/**
	 * remember_user
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function remember_user($id)
	{
		$this->trigger_events('pre_remember_user');

		if (!$id)
		{
			return FALSE;
		}

		$user = $this->user($id)->row();

		$salt = sha1($user->password);

		$this->db->update($this->tables['users'], array('remember_code' => $salt), array('id' => $id));

		if ($this->db->affected_rows() > -1)
		{
			// if the user_expire is set to zero we'll set the expiration two years from now.
			if($this->config->item('user_expire', 'skeleton_auth') === 0)
			{
				$expire = (60*60*24*365*2);
			}
			// otherwise use what is set
			else
			{
				$expire = $this->config->item('user_expire', 'skeleton_auth');
			}

			set_cookie(array(
			    'name'   => 'identity',
			    'value'  => $user->{$this->identity_column},
			    'expire' => $expire
			));

			set_cookie(array(
			    'name'   => 'remember_code',
			    'value'  => $salt,
			    'expire' => $expire
			));

			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}

		$this->trigger_events(array('post_remember_user', 'remember_user_unsuccessful'));
		return FALSE;
	}

	/**
	 * login_remembed_user
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function login_remembered_user()
	{
		$this->trigger_events('pre_login_remembered_user');

		//check for valid data
		if (!get_cookie('identity') || !get_cookie('remember_code') || !$this->identity_check(get_cookie('identity')))
		{
			$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
			return FALSE;
		}

		//get the user
		$this->trigger_events('extra_where');
		$query = $this->db->select($this->identity_column.', id, username, email, last_login')
		                  ->where($this->identity_column, get_cookie('identity'))
		                  ->where('remember_code', get_cookie('remember_code'))
		                  ->limit(1)
		                  ->get($this->tables['users']);

		//if the user was found, sign them in
		if ($query->num_rows() == 1)
		{
			$user = $query->row();

			$this->update_last_login($user->id);

			$this->set_session($user);

			//extend the users cookies if the option is enabled
			if ($this->config->item('user_extend_on_login', 'skeleton_auth'))
			{
				$this->remember_user($user->id);
			}

			$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
			return TRUE;
		}

		$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
		return FALSE;
	}


	/**
	 * create_group
	 *
	 * @author aditya menon
	*/
	public function create_group($group_name = FALSE, $group_description = '', $additional_data = array())
	{
		// bail if the group name was not passed
		if(!$group_name)
		{
			$this->set_error('group_name_required');
			return FALSE;
		}

		// bail if the group name already exists
		$existing_group = $this->db->get_where($this->tables['groups'], array('name' => $group_name))->num_rows();
		if($existing_group !== 0)
		{
			$this->set_error('group_already_exists');
			return FALSE;
		}

		$data = array('name'=>$group_name,'description'=>$group_description);

		//filter out any data passed that doesnt have a matching column in the groups table
		//and merge the set group data and the additional data
		if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);

		$this->trigger_events('extra_group_set');

		// insert the new group
		$this->db->insert($this->tables['groups'], $data);
		$group_id = $this->db->insert_id();

		// report success
		$this->set_message('group_creation_successful');
		// return the brand new group id
		return $group_id;
	}

	/**
	 * update_group
	 *
	 * @return bool
	 * @author aditya menon
	 **/
	public function update_group($group_id = FALSE, $group_name = FALSE, $additional_data = array())
	{
		if (empty($group_id)) return FALSE;

		$data = array();

		if (!empty($group_name))
		{
			// we are changing the name, so do some checks

			// bail if the group name already exists
			$existing_group = $this->db->get_where($this->tables['groups'], array('name' => $group_name))->row();
			if(isset($existing_group->id) && $existing_group->id != $group_id)
			{
				$this->set_error('group_already_exists');
				return FALSE;
			}	

			$data['name'] = $group_name;		
		}
		

		// IMPORTANT!! Third parameter was string type $description; this following code is to maintain backward compatibility
		// New projects should work with 3rd param as array
		if (is_string($additional_data)) $additional_data = array('description' => $additional_data);
		

		//filter out any data passed that doesnt have a matching column in the groups table
		//and merge the set group data and the additional data
		if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);


		$this->db->update($this->tables['groups'], $data, array('id' => $group_id));

		$this->set_message('group_update_successful');

		return TRUE;
	}

	/**
	* delete_group
	*
	* @return bool
	* @author aditya menon
	**/
	public function delete_group($group_id = FALSE)
	{
		// bail if mandatory param not set
		if(!$group_id || empty($group_id))
		{
			return FALSE;
		}

		$this->trigger_events('pre_delete_group');

		$this->db->trans_begin();

		// remove all users from this group
		$this->db->delete($this->tables['users_groups'], array($this->join['groups'] => $group_id));
		// remove the group itself
		$this->db->delete($this->tables['groups'], array('id' => $group_id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->trigger_events(array('post_delete_group', 'post_delete_group_unsuccessful'));
			$this->set_error('group_delete_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->trigger_events(array('post_delete_group', 'post_delete_group_successful'));
		$this->set_message('group_delete_successful');
		return TRUE;
	}

	public function set_hook($event, $name, $class, $method, $arguments)
	{
		$this->_ion_hooks->{$event}[$name] = new stdClass;
		$this->_ion_hooks->{$event}[$name]->class     = $class;
		$this->_ion_hooks->{$event}[$name]->method    = $method;
		$this->_ion_hooks->{$event}[$name]->arguments = $arguments;
	}

	public function remove_hook($event, $name)
	{
		if (isset($this->_ion_hooks->{$event}[$name]))
		{
			unset($this->_ion_hooks->{$event}[$name]);
		}
	}

	public function remove_hooks($event)
	{
		if (isset($this->_ion_hooks->$event))
		{
			unset($this->_ion_hooks->$event);
		}
	}

	protected function _call_hook($event, $name)
	{
		if (isset($this->_ion_hooks->{$event}[$name]) && method_exists($this->_ion_hooks->{$event}[$name]->class, $this->_ion_hooks->{$event}[$name]->method))
		{
			$hook = $this->_ion_hooks->{$event}[$name];

			return call_user_func_array(array($hook->class, $hook->method), $hook->arguments);
		}

		return FALSE;
	}

	public function trigger_events($events)
	{
		if (is_array($events) && !empty($events))
		{
			foreach ($events as $event)
			{
				$this->trigger_events($event);
			}
		}
		else
		{
			if (isset($this->_ion_hooks->$events) && !empty($this->_ion_hooks->$events))
			{
				foreach ($this->_ion_hooks->$events as $name => $hook)
				{
					$this->_call_hook($events, $name);
				}
			}
		}
	}

	/**
	 * set_message_delimiters
	 *
	 * Set the message delimiters
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_message_delimiters($start_delimiter, $end_delimiter)
	{
		$this->message_start_delimiter = $start_delimiter;
		$this->message_end_delimiter   = $end_delimiter;

		return TRUE;
	}

	/**
	 * set_error_delimiters
	 *
	 * Set the error delimiters
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_error_delimiters($start_delimiter, $end_delimiter)
	{
		$this->error_start_delimiter = $start_delimiter;
		$this->error_end_delimiter   = $end_delimiter;

		return TRUE;
	}

	/**
	 * set_message
	 *
	 * Set a message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_message($message)
	{
		$this->messages[] = $message;

		return $message;
	}

	/**
	 * messages
	 *
	 * Get the messages
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function messages()
	{
		$_output = '';
		foreach ($this->messages as $message)
		{
			$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
			$_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
		}

		return $_output;
	}

	/**
	 * messages as array
	 *
	 * Get the messages as an array
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 **/
	public function messages_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->messages as $message)
			{
				$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
				$_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
			}
			return $_output;
		}
		else
		{
			return $this->messages;
		}
	}

	/**
	 * set_error
	 *
	 * Set an error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_error($error)
	{
		$this->errors[] = $error;

		return $error;
	}

	/**
	 * errors
	 *
	 * Get the error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function errors()
	{
		$_output = '';
		foreach ($this->errors as $error)
		{
			$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
			$_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
		}

		return $_output;
	}

	/**
	 * errors as array
	 *
	 * Get the error messages as an array
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 **/
	public function errors_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->errors as $error)
			{
				$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
				$_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
			}
			return $_output;
		}
		else
		{
			return $this->errors;
		}
	}

	protected function _filter_data($table, $data)
	{
		$filtered_data = array();
		$columns = $this->db->list_fields($table);

		if (is_array($data))
		{
			foreach ($columns as $column)
			{
				if (array_key_exists($column, $data))
					$filtered_data[$column] = $data[$column];
			}
		}

		return $filtered_data;
	}

	public function _prepare_ip($ip_address) {
		if ($this->db->platform() === 'postgre' || $this->db->platform() === 'sqlsrv' || $this->db->platform() === 'mssql')
		{
			return $ip_address;
		}
		else
		{
			return inet_pton($ip_address);
		}
	}
	
	/**
	 * Check if user is active
	 */
	public function is_user_active($username) {
		
		$query = $this->db->select($this->identity_column . ', username, active')
		                  ->where($this->identity_column, $this->db->escape_str($username))
		                  ->limit(1)
		                  ->get($this->tables['users']);
		
		if ($query->num_rows() === 1) {
			$user = $query->row();
			if ($user->active == 1)	{
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * Get user data by username
	 *
	 * @return user object
	 * @author Sergi Tur
	 **/
	public function get_user_by_username($username)
	{
		if (empty($username))
		{
			return FALSE;
		}

		$query = $this->db->where('username', $username)
						->limit(1)
		                ->get($this->tables['users']);
		return $query->row();
	}
	
	function _get_rolename_byId($id) {		
		$roles = (array) $this->config->item('roles', 'skeleton_auth');
		return $roles[(int) $id];
	}
	
	protected function _get_group_id_by_group_name ($groupname) {
		$groups = $this->groups()->result();
		foreach ($groups as $group) {
			if ( $group->name == $groupname )
				return $group->id;
		}
		return false;
	}
	
	protected function _check_if_user_group_exists($groupname) {
		$usergroups=$this->get_users_groups()->result();
		foreach ($usergroups as $usergroup) {
			if ( $usergroup->name == $groupname ) {
				return true;
			}
		}
		return false;
	}
	
	protected function _check_if_group_exists($groupname) {
		$groups = $this->groups()->result();
		foreach ($groups as $group) {
			if ( $group->name == $groupname )
				return true;
		}
		return false;
	}
	
	function get_user_theme($userid){
		$this->db->select('theme');
		$this->db->where('userId',$userid);
		$query = $this->db->get('user_preferences');
		if ($query->num_rows() > 0)
			return $query->row()->theme;
		else
			return false;
	}
	
	function get_user_dialogforms($userid){
		$this->db->select('dialogforms');
		$this->db->where('userId',$userid);
		$query = $this->db->get('user_preferences');
		if ($query->num_rows() > 0)
			return $query->row()->dialogforms;
		else
			return false;
	}
	
	function user_have_preferences ($userid) {
		$this->db->where('userId',$userid);
		$query = $this->db->get('user_preferences');
		if ($query->num_rows() != 0)
			return true;
		return false;
	}
	
	function get_main_organizational_unit_name_from_userid($userid){
		
		$unitid=$this->get_main_organizational_unit_from_userid($userid);
		$this->db->select('name');
		$this->db->where('organizational_unitId',$unitid);
		$query = $this->db->get('organizational_unit');
		if ($query->num_rows() > 0)
			return $query->row()->name;
		else
			return "";

	}
	
	function get_main_organizational_unit_from_userid($userid){
		
		$this->db->select('mainOrganizationaUnitId');
		$this->db->where('id',$userid);
		$query = $this->db->get('users');
		return $query->row()->mainOrganizationaUnitId;
	}
	
	function get_dropdown_values($table_name,$field_name,$primary_key=null,$order_by="asc") {
		
		$primary_key_field_name;
		if ($primary_key==null)
			$primary_key_field_name=$this->get_primary_key($table_name);
		else
			$primary_key_field_name=$primary_key;
		
		$this->db->select("$primary_key_field_name,$field_name");
		$this->db->order_by($field_name, $order_by); 
		$this->db->where("markedForDeletion", "n"); 
		$query = $this->db->get($table_name);
		if ($query->num_rows() != 0)
			return $query->result();
		return false;
	}
	
	function get_primary_key($table_name) {
		$fields = $this->db->field_data($table_name);
		
		foreach ($fields as $field)	{
			if ($field->primary_key) {
					return $field->name;
			}
		} 	
		return false;
	}
	
	function get_last_added_value($table_name,$primary_key=null) {
		$primary_key_field_name;
		if ($primary_key==null)
			$primary_key_field_name=$this->get_primary_key($table_name);
		else
			$primary_key_field_name=$primary_key;
		
		$this->db->select($primary_key_field_name);
		$this->db->order_by($primary_key_field_name, "desc");
		$this->db->where("markedForDeletion", "n");
		$query = $this->db->get($table_name);
		if ($query->num_rows() != 0)
			return $query->row()->$primary_key_field_name;
		return false;
	}
}
