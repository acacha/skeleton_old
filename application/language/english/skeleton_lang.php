<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 *  Name:  skeleton lang - English
 *
 * Author: Sergi Tur Badenas
 * 		  sergiturbadenas@gmail.com
 *
 * Created:  15.09.2013
 *
 * Description:  English language file for skeleton
 *
 */

//MANAGMENT MENU
$lang['managment']   = 'Managment';
$lang['users'] = 'Users';
$lang['groups'] = 'Groups';
$lang['preferences'] = 'Preferences';

//MAINTENANCES MENU
$lang['maintenances'] = "Maintenances";
$lang['organizationalunit_menu'] = "Organizational units";

//location menu
$lang['location_menu'] = 'Locations';

//SUPPORTED LANGUAGES
$lang['language'] = 'Language';
$lang['catalan'] = 'Catalan';
$lang['spanish'] = 'Spanish';
$lang['english'] = 'English';

//LOGIN & AUTH
$lang['CloseSession'] = 'Close sesion';

// Fields
$lang['name']       		= 'Name';
$lang['shortName']        	= 'Short Name';           
$lang['description']            = 'Description';
$lang['entryDate']              = 'Entry Date (automatic)';
$lang['manualEntryDate']        = 'Entry Date (manual)';
$lang['last_update']            = 'Last update (automatic)';
$lang['manual_last_update']     = 'Last update (manual)';
$lang['creationUserId']         = 'Creation User';
$lang['lastupdateUserId']       = 'Last update user';
$lang['markedForDeletion']      = 'Marked for deletion?'; 
$lang['markedForDeletionDate']  = 'Marked for deletion date'; 

//User fields
$lang['ip_address'] = 'IP address';
$lang['username'] = "User name";
$lang['email'] = 'Email';
$lang['Password'] = 'Password';
$lang['activation_code'] = "Activation code";
$lang['forgotten_password_code'] = 'Forgotten password code';
$lang['forgotten_password_time'] = 'Forgotten password time' ;
$lang['remember_code'] = 'Remember code';
$lang['created_on'] = 'Created on';
$lang['active'] = 'Active';
$lang['first_name'] = 'First Name';
$lang['last_name'] = 'Last Name';
$lang['company'] = 'Company';
$lang['phone'] = 'Telephon';
$lang['verify_password']="Verify password";
$lang['MainOrganizationaUnitId']="Organizational Unit";

//User preferences fields
$lang['userId'] = "User Id";
$lang['theme'] = 'Theme';
$lang['dialogforms'] = 'Activate dialog forms';

//$lang['userId'] = "Id d'usuari";
//$lang['theme'] = 'Tema';
//$lang['dialogforms'] = 'Activar formularis en mode diàleg';

//Location fields
$lang['parentLocation'] = 'Parent location';

//Organizational unit fields
$lang['code'] = 'Code';
$lang['location'] = 'Location';


//USER PREFERENCES VIEWS
$lang['user_preferences_admin message1']="You are viewing all user prefereces because you are allowed to.";
$lang['user_preferences_admin message2']="You can view your preferences ";
$lang['user_preferences_admin message3']="You can edit your preferences ";
$lang['user_preferences_not_yet_message1']="You are using default preferences because you have not defined specific ones.";
$lang['user_preferences_not_yet_message2']="You can create you user preferences ";
$lang['here']="here";

$lang['operation_not_allowed']="Operation not allowed";
$lang['edit_not_allowed']="Your user can't edit this record";
$lang['insert_not_allowed']="Your user can't add this record";

//Grocery Crud Subjects
$lang['users_subject'] = 'users';
$lang['groups_subject'] = 'group';
$lang['user_preferences_subject'] = 'user preference';
$lang['location_subject']     = 'location';
$lang['organizationalunit_subject'] = 'organizational unit';

$lang['user_info_title']="User info";
$lang['user_id_title']="User id";
$lang['username_title']="Username";
$lang['name_title']="First Name";
$lang['surname_title']="Last Name";
$lang['email_title']="Email";
$lang['realm_title']="Realm";
$lang['user_groups_in_database']="Groups";
$lang['main_user_organizational_unit']="Main organizational unit";
$lang['rol_title']="Role";

//ERRORS
$lang['404_page_not_found'] = '404 Page not found';
$lang['404_page_not_found_message'] = "Asked page not found!";
$lang['table_not_found'] = 'Table not found';
$lang['table_not_found_message'] = "Can't found the table";
$lang['InventoryObjectId_not_found']="Object database id doesn't found";

$lang['Yes']= "Yes";
$lang['No']= "No";

$lang['show_express_form']="Show express form";
$lang['hide_express_form']="Hide express form";
