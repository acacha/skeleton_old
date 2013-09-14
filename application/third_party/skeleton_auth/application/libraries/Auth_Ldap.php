<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * This file is part of Auth_Ldap.

    Auth_Ldap is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Auth_Ldap is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Auth_Ldap.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
/**
 * Auth_Ldap Class
 *
 * Simple LDAP Authentication library for Code Igniter.
 *
 * @package         Auth_Ldap
 * @author          Greg Wojtak <gwojtak@techrockdo.com>
 * @version         0.6
 * @link            http://www.techrockdo.com/projects/auth_ldap
 * @license         GNU Lesser General Public License (LGPL)
 * @copyright       Copyright © 2010,2011 by Greg Wojtak <gwojtak@techrockdo.com>
 * @todo            Allow for privileges in groups of groups in AD
 * @todo            Rework roles system a little bit to a "auth level" paradigm
 */
class Auth_Ldap {
    function __construct() {
        $this->ci =& get_instance();
        
        // Load the language file
        $this->ci->lang->load('auth_ldap','catalan');
        $this->ci->load->helper('language');

        log_message('debug', lang('auth_ldap_initialization'));

        // Load the session library
        $this->ci->load->library('session');

        // Load the configuration
        $this->ci->load->config('auth_ldap');

        $this->_init();
    }

    
    /**
     * @access private
     * @return void
     */
    private function _init() {

        // Verify that the LDAP extension has been loaded/built-in
        // No sense continuing if we can't
        if (! function_exists('ldap_connect')) {
            show_error(lang('php_ldap_notpresent'));
            log_message('error', lang('php_ldap_notpresent_log'));
        }

        $this->hosts = $this->ci->config->item('hosts');
        $this->ports = $this->ci->config->item('ports');
        $this->basedn = $this->ci->config->item('basedn');
        $this->account_ou = $this->ci->config->item('account_ou');
        $this->login_attribute  = $this->ci->config->item('login_attribute');
        $this->use_ad = $this->ci->config->item('use_ad');
        $this->ad_domain = $this->ci->config->item('ad_domain');
        $this->proxy_user = $this->ci->config->item('proxy_user');
        $this->proxy_pass = $this->ci->config->item('proxy_pass');
        $this->roles = $this->ci->config->item('roles');
        $this->auditlog = $this->ci->config->item('auditlog');
        $this->member_attribute = $this->ci->config->item('member_attribute');
    }

    /**
     * @access public
     * @param string $username
     * @param string $password
     * @return bool 
     */
    function login($username, $password) {
        /*
         * For now just pass this along to _authenticate.  We could do
         * something else here before hand in the future.
         */

        $user_info = $this->_authenticate($username,$password);
        
        if(!$user_info) {
			return -1;
		}
        if(empty($user_info['role'])) {
            log_message('info', $username.lang('has_no_role_to_play'));
            //show_error($username.lang('successfully_authenticated_but_no_role'));
            return -2;
        }
        // Record the login
        $this->_audit(lang('succesful_login').$user_info['cn']." (".$username.") ". lang('from_ip') . " " .$this->ci->input->ip_address());

        // Set the session data
        $customdata = array('username' => $username,
                            'cn' => $user_info['cn'],
                            'role' => $user_info['role'],
                            'logged_in' => TRUE);
    
        $this->ci->session->set_userdata($customdata);
        return 1;
    }

    /**
     * @access public
     * @return bool
     */
    function is_authenticated() {
        if($this->ci->session->userdata('logged_in')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * @access public
     */
    function logout() {
        // Just set logged_in to FALSE and then destroy everything for good measure
        $this->ci->session->set_userdata(array('logged_in' => FALSE));
        $this->ci->session->sess_destroy();
    }

    /**
     * @access private
     * @param string $msg
     * @return bool
     */
    private function _audit($msg){
        $date = date('Y/m/d H:i:s');
        if( ! file_put_contents($this->auditlog, $date.": ".$msg."\n",FILE_APPEND)) {
            log_message('info', lang('error_opening_audit_log'). ' '.$this->auditlog);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @access private
     * @param string $username
     * @param string $password
     * @return array 
     */
    private function _authenticate($username, $password) {
        $needed_attrs = array('dn', 'cn', $this->login_attribute);
        
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

        // We've connected, now we can attempt the login...
        
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
        }

        log_message('debug', lang('successfully_bound').$username);
        $filter = '('.$this->login_attribute.'='.$username.')';
        $search = ldap_search($this->ldapconn, $this->basedn, $filter, 
                array('dn', $this->login_attribute, 'cn'));
        $entries = ldap_get_entries($this->ldapconn, $search);

        if($entries['count'] != 0) {
			$binddn = $entries[0]['dn'];
            // Now actually try to bind as the user
			$bind = @ldap_bind($this->ldapconn, $binddn, $password);
			if(! $bind) {
				$this->_audit(lang('failed_login') . $username. lang('from')." ".$_SERVER['REMOTE_ADDR']);
				return FALSE;
			}
		} else {
				$this->_audit(lang('failed_login') . $username. lang('from')." ".$_SERVER['REMOTE_ADDR']);
				return FALSE;
		}
		
        $cn = $entries[0]['cn'][0];
        $dn = stripslashes($entries[0]['dn']);
        $id = $entries[0][$this->login_attribute][0];
        
        $get_role_arg = $id;               
        
        return array('cn' => $cn, 'dn' => $dn, 'id' => $id,
            'role' => $this->_get_role($get_role_arg));
    }

    /**
     * @access private
     * @param string $str
     * @param bool $for_dn
     * @return string 
     */
    private function ldap_escape($str, $for_dn = false) {
        /**
         * This function courtesy of douglass_davis at earthlink dot net
         * Posted in comments at
         * http://php.net/manual/en/function.ldap-search.php on 2009/04/08
         */
        // see:
        // RFC2254
        // http://msdn.microsoft.com/en-us/library/ms675768(VS.85).aspx
        // http://www-03.ibm.com/systems/i/software/ldap/underdn.html  
        
        if  ($for_dn)
            $metaChars = array(',','=', '+', '<','>',';', '\\', '"', '#');
        else
            $metaChars = array('*', '(', ')', '\\', chr(0));

        $quotedMetaChars = array();
        foreach ($metaChars as $key => $value) $quotedMetaChars[$key] = '\\'.str_pad(dechex(ord($value)), 2, '0');
        $str=str_replace($metaChars,$quotedMetaChars,$str); //replace them
        return ($str);  
    }
    
    /**
     * @access private
     * @param string $username
     * @return int
     */
    private function _get_role($username) {
    
        $filter = '('.$this->member_attribute.'='.$username.')';
        $search = ldap_search($this->ldapconn, $this->basedn, $filter, array('cn'));
        if(! $search ) {
            log_message('error', lang('error_searching_groups').ldap_error($this->ldapconn));
            show_error(lang('no_groups').ldap_error($this->ldapconn));
        }
        $results = ldap_get_entries($this->ldapconn, $search);
        if($results['count'] != 0) {
            for($i = 0; $i < $results['count']; $i++) {
                $role = array_search($results[$i]['cn'][0], $this->roles);
                if($role !== FALSE) {
                    return $role;
                }
            }
        }
        return false;
    }
    
    /**
	 * Get user additional data from Ldap
	 *
	 * @return array with additional data
	 * @author Sergi Tur
	 **/
	public function get_additional_data($username) 	{
		$filter = '('.$this->login_attribute.'='.$username.')';
        $search = ldap_search($this->ldapconn, $this->basedn, $filter, 
                array('dn', 'givenname', 'sn','homephone','ou'));
        if(! $search ) {
            log_message('error', lang('error_searching_groups').ldap_error($this->ldapconn));
            show_error(lang('no_groups').ldap_error($this->ldapconn));
        }
        $results = ldap_get_entries($this->ldapconn, $search);		
		
		if($results['count'] === 1) {
			//CHECK WICH VALUES EXISTS
			$first_name=null;
			$last_name=null;
			$phone=null;
			$company=null;
			if (array_key_exists('givenname', $results[0]))
				$first_name=$results[0]['givenname'][0];
			if (array_key_exists('sn', $results[0]))
				$last_name=$results[0]['sn'][0];
			if (array_key_exists('homephone', $results[0]))
				$phone=$results[0]['homephone'][0];
			if (array_key_exists('ou', $results[0]))
				$company=$results[0]['ou'][0];
			return array(
					"first_name" => $first_name,
					"last_name"  => $last_name,
					"phone"      => $phone,
					"company"      => $company
					);
        }
        return false;
	}
	
	/**
	 * Get user additional data from Ldap
	 *
	 * @return array with additional data
	 * @author Sergi Tur
	 **/
	public function get_email($username) 	{
		$filter = '('.$this->login_attribute.'='.$username.')';
        $search = ldap_search($this->ldapconn, $this->basedn, $filter, 
                array('dn', 'email'));
        if(! $search ) {
            log_message('error', 'error_searching_username: '.ldap_error($this->ldapconn));
            show_error('error_searching_username: '.ldap_error($this->ldapconn));
        }
        $results = ldap_get_entries($this->ldapconn, $search);		
		
		if($results['count'] === 1) {
			if (array_key_exists('email', $results[0])) {
				return $results[0]['email'][0];
			}
		}
        return false;
	}
}

?>
