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
$lang['php_ldap_notpresent']='No està instal\·lada la funcionalitat de Ldap per PHP. Activeu el mòdul Ldap de PHP o utilitzeu un PHP amb suport per a Ldap compilat.';
$lang['successfully_authenticated_but_no_role']=" autenticat correctament, però no es permet l'accés perquè l'usuari no pertany a cap grup amb permisos per accedir a l'aplicació";
$lang['error_opening_audit_log']='Error obrint el log audit';
$lang['error_connecting_to']='Error connectant a';
$lang['could_not_connect_to_ldap']="No es pot connectar a cap dels servidors Ldap...";
$lang['error_connecting_to_ldap']="Error connectant als servidors Ldap. Si us plau reviseu la connexió i proveu un altre cop.";
$lang['unable_anonymous']="No s'ha pogut realitzar un bind anònim";
$lang['unable_bind']="No s'ha pogut realitzar el bind per localitzar el identificador del usuari";
$lang['successfully_bound']="S'ha realitzar el bind al servidor Ldap correctament. Realitzant la cerca del dn...";
$lang['error_searching_groups']='Error buscant el grup: ';
$lang['no_groups']="No es poden trobar els groups: ";
$lang['failed_login']='Intent de login erroni: ';
$lang['failed_login']=' de ';
$lang['has_no_role_to_play']=' no té cap rol assignat.';
$lang['succesful_login']='Login correcte: ';
$lang['from_ip']="origen";
$lang['failed_login']='Login incorrecte: ';

$lang['php_ldap_notpresent_log']='Funcionalitat Ldap de PHP no present.';
$lang['auth_ldap_initialization']='Auth_Ldap initialization ...';

