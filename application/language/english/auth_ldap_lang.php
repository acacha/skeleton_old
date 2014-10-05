<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Auth Ldap Lang - English
*
* Author: Sergi Tur Badenas
* 		  sergiturbadenas@gmail.com
*         @sergitur
*
*
* Location: http://github.com/acacha
*
* Created:  03.09.2013
*
* Description:  English language file for Ion Auth Ldap views
*
*/

// Errors & LOGS
$lang['php_ldap_notpresent']='LDAP functionality not present.  Either load the module ldap php module or use a php with ldap support compiled in.';
$lang['successfully_authenticated_but_no_role']=' successfully authenticated, but is not allowed because the username was not found in an allowed access group.';
$lang['error_opening_audit_log']='Error opening audit log';
$lang['error_connecting_to']='Error connecting to';
$lang['could_not_connect_to_ldap']="Couldn't connect to any LDAP servers.  Bailing...";
$lang['error_connecting_to_ldap']="Error connecting to your LDAP server(s).  Please check the connection and try again.";
$lang['unable_anonymous']='Unable to perform anonymous/proxy bind';
$lang['unable_bind']='Unable to bind for user id lookup';
$lang['successfully_bound']='Successfully bound to directory.  Performing dn lookup for ';
$lang['error_searching_groups']='Error searching for group: ';
$lang['no_groups']="Couldn't find groups: ";
$lang['failed_login']='Failed login attempt: ';
$lang['failed_login']=' from ';
$lang['has_no_role_to_play']=' has no role to play.';
$lang['succesful_login']='Successful login: ';
$lang['from_ip']="from";
$lang['failed_login']='Failed login: ';

$lang['php_ldap_notpresent_log']='LDAP functionality not present in php.';
$lang['auth_ldap_initialization']='Auth_Ldap initialization commencing...';
