<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Auth Lang - Catalan
*
* Author: Sergi Tur Badenas
* 		  sergiturbadenas@gmail.com
*         @acacha
*
* Created:  17/08/2013
*
* Description:  Catalan language file for Ion Auth example views
*
*/

//LOGIN FORM
$lang['login-form-greetings']   = 'Si us plau, entreu';
$lang['User']   = 'Usuari';
$lang['Password']   = 'Paraula de pas';
$lang['Identity'] = 'Identitat';
$lang['Register']   = 'Registrar';
$lang['Login']   = 'Entrar';
$lang['application_of'] = "és una aplicació d'";
$lang['created by'] = "creada per";
$lang['remember']       		= 'Recordar';

//FORGOT PASSWORD FORM
$lang['come_back']="Tornar";
$lang['username'] = "Nom d'usuari";
$lang['email'] = 'Correu electrònic';

// Errors
$lang['error_csrf'] = 'This form post did not pass our security checks.';

// Login
$lang['login_heading']         = 'Login';
$lang['login_subheading']      = 'Please login with your email/username and password below.';
$lang['login_identity_label']  = 'Email/Username:';
$lang['login_password_label']  = 'Password:';
$lang['login_remember_label']  = 'Remember Me:';
$lang['login_submit_btn']      = 'Login';
$lang['login_forgot_password'] = 'Has oblidat la teva paraula de pas?';

// Index
$lang['index_heading']           = 'Users';
$lang['index_subheading']        = 'Below is a list of the users.';
$lang['index_fname_th']          = 'First Name';
$lang['index_lname_th']          = 'Last Name';
$lang['index_email_th']          = 'Email';
$lang['index_groups_th']         = 'Groups';
$lang['index_status_th']         = 'Status';
$lang['index_action_th']         = 'Action';
$lang['index_active_link']       = 'Active';
$lang['index_inactive_link']     = 'Inactive';
$lang['index_create_user_link']  = 'Create a new user';
$lang['index_create_group_link'] = 'Create a new group';

// Deactivate User
$lang['deactivate_heading']                  = 'Deactivate User';
$lang['deactivate_subheading']               = 'Are you sure you want to deactivate the user \'%s\'';
$lang['deactivate_confirm_y_label']          = 'Yes:';
$lang['deactivate_confirm_n_label']          = 'No:';
$lang['deactivate_submit_btn']               = 'Submit';
$lang['deactivate_validation_confirm_label'] = 'confirmation';
$lang['deactivate_validation_user_id_label'] = 'user ID';

// Create User
$lang['create_user_heading']                           = 'Create User';
$lang['create_user_subheading']                        = 'Please enter the users information below.';
$lang['create_user_fname_label']                       = 'First Name:';
$lang['create_user_lname_label']                       = 'Last Name:';
$lang['create_user_company_label']                     = 'Company Name:';
$lang['create_user_email_label']                       = 'Email:';
$lang['create_user_phone_label']                       = 'Phone:';
$lang['create_user_password_label']                    = 'Password:';
$lang['create_user_password_confirm_label']            = 'Confirm Password:';
$lang['create_user_submit_btn']                        = 'Create User';
$lang['create_user_validation_fname_label']            = 'First Name';
$lang['create_user_validation_lname_label']            = 'Last Name';
$lang['create_user_validation_email_label']            = 'Email Address';
$lang['create_user_validation_phone1_label']           = 'First Part of Phone';
$lang['create_user_validation_phone2_label']           = 'Second Part of Phone';
$lang['create_user_validation_phone3_label']           = 'Third Part of Phone';
$lang['create_user_validation_company_label']          = 'Company Name';
$lang['create_user_validation_password_label']         = 'Password';
$lang['create_user_validation_password_confirm_label'] = 'Password Confirmation';

// Edit User
$lang['edit_user_heading']                           = 'Edit User';
$lang['edit_user_subheading']                        = 'Please enter the users information below.';
$lang['edit_user_fname_label']                       = 'First Name:';
$lang['edit_user_lname_label']                       = 'Last Name:';
$lang['edit_user_company_label']                     = 'Company Name:';
$lang['edit_user_email_label']                       = 'Email:';
$lang['edit_user_phone_label']                       = 'Phone:';
$lang['edit_user_password_label']                    = 'Password: (if changing password)';
$lang['edit_user_password_confirm_label']            = 'Confirm Password: (if changing password)';
$lang['edit_user_groups_heading']                    = 'Member of groups';
$lang['edit_user_submit_btn']                        = 'Save User';
$lang['edit_user_validation_fname_label']            = 'First Name';
$lang['edit_user_validation_lname_label']            = 'Last Name';
$lang['edit_user_validation_email_label']            = 'Email Address';
$lang['edit_user_validation_phone1_label']           = 'First Part of Phone';
$lang['edit_user_validation_phone2_label']           = 'Second Part of Phone';
$lang['edit_user_validation_phone3_label']           = 'Third Part of Phone';
$lang['edit_user_validation_company_label']          = 'Company Name';
$lang['edit_user_validation_groups_label']           = 'Groups';
$lang['edit_user_validation_password_label']         = 'Password';
$lang['edit_user_validation_password_confirm_label'] = 'Password Confirmation';

// Create Group
$lang['create_group_title']                  = 'Create Group';
$lang['create_group_heading']                = 'Create Group';
$lang['create_group_subheading']             = 'Please enter the group information below.';
$lang['create_group_name_label']             = 'Group Name:';
$lang['create_group_desc_label']             = 'Description:';
$lang['create_group_submit_btn']             = 'Create Group';
$lang['create_group_validation_name_label']  = 'Group Name';
$lang['create_group_validation_desc_label']  = 'Description';

// Edit Group
$lang['edit_group_title']                  = 'Edit Group';
$lang['edit_group_saved']                  = 'Group Saved';
$lang['edit_group_heading']                = 'Edit Group';
$lang['edit_group_subheading']             = 'Please enter the group information below.';
$lang['edit_group_name_label']             = 'Group Name:';
$lang['edit_group_desc_label']             = 'Description:';
$lang['edit_group_submit_btn']             = 'Save Group';
$lang['edit_group_validation_name_label']  = 'Group Name';
$lang['edit_group_validation_desc_label']  = 'Description';

// Change Password
$lang['change_password_heading']                               = 'Canviar la paraula de pas';
$lang['change_password_old_password_label']                    = 'Paraula de pas antiga:';
$lang['change_password_new_password_label']                    = 'Nova paraula de pas (com a mínim ha de tenir %s caràcters):';
$lang['change_password_new_password_confirm_label']            = 'Confirmar la nova paraula de pas:';
$lang['change_password_submit_btn']                            = 'Canviar';
$lang['change_password_validation_old_password_label']         = 'Paraula de pas antiga';
$lang['change_password_validation_new_password_label']         = 'Nova paraula de pas';
$lang['change_password_validation_new_password_confirm_label'] = 'Confirmeu la nova paraula de pas';

// Forgot Password
$lang['forgot_password_heading']                 = 'Paraula de pas oblidada';
$lang['forgot_password_subheading']              = "Si us plau ompliu el següent formulari per tal d'enviar-vos un correu electrònic amb la nova paraula de pas.";
$lang['forgot_password_email_label']             = '%s';
$lang['forgot_password_submit_btn']              = 'Enviar';
$lang['forgot_password_validation_email_label']  = 'Adreça de correu electrònic';
$lang['forgot_password_validation_username_label']  = "Nom d'usuari";
$lang['forgot_password_username_identity_label'] = "Nom d'usuari";
$lang['forgot_password_username_realms_label'] = "Reialme";
$lang['forgot_password_email_identity_label']    = 'Correu electrònic';
$lang['forgot_password_identity_not_found']    = 'El %s no pertany a cap usuari de la base de dades!';
$lang['forgot_password_identity_found_more_than_one'] = "Hi ha més d'un usuari amb aquest %s. Aviseu al administrador de la base de dades per corregir l'error";
$lang['do_you_not_remember_your_identity'] = "No recordeu el vostre %s";
$lang['try_with_your_identity'] = "Proveu amb el %s";

// Reset Password
$lang['reset_password_heading']                               = 'Canviar la paraula de pas';
$lang['reset_password_new_password_label']                    = 'Nova paraula de pas (com a mínim ha de tenir %s caràcters):';
$lang['reset_password_new_password_confirm_label']            = 'Confirmar la nova paraula de pas:';
$lang['reset_password_submit_btn']                            = 'Canviar';
$lang['reset_password_validation_new_password_label']         = 'Nova paraula de pas';
$lang['reset_password_validation_new_password_confirm_label'] = 'Confirmar la nova paraula de pas';

// Activation Email
$lang['email_activate_heading']    = 'Activar el compte de %s';
$lang['email_activate_subheading'] = 'Si us plau feu clic al següent enllaç per tal de %s.';
$lang['email_activate_link']       = 'Activeu el vostre compte';

// Forgot Password Email
$lang['email_forgot_password_heading']    = "Reestablir la paraula de pas per a l'usuariReset Password for %s";
$lang['email_forgot_password_subheading'] = 'Si us plau feu clic al següent enllaç per %s.';
$lang['email_forgot_password_link']       = 'reestablir la paraula de pas';

// New Password Email
$lang['email_new_password_heading']    = 'New Password for %s';
$lang['email_new_password_subheading'] = 'Your password has been reset to: %s';

