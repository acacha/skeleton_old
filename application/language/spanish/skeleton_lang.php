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
$lang['managment']   = 'Gestion';
$lang['users'] = 'Usuarios';
$lang['groups'] = 'Grupos';
$lang['preferences'] = 'Preferencias';

//MAINTENANCES MENU
$lang['maintenances'] = "Mantenimientos";
$lang['organizationalunit_menu'] = "Unidades organizatives";

//location menu
$lang['location_menu'] = 'Espacios';

//SUPPORTED LANGUAGES
$lang['language'] = 'Idioma';
$lang['catalan'] = 'Catalan';
$lang['spanish'] = 'Castellano';
$lang['english'] = 'Ingles';

//LOGIN & AUTH
$lang['CloseSession'] = 'Cerrar sesión';

// Fields
$lang['name']       		= 'Nombre';
$lang['shortName']        	= 'Nombre corto';           
$lang['description']            = 'Descripción';
$lang['entryDate']              = 'Fecha de entrada (automática)';
$lang['manualEntryDate']        = 'Fecha de entrada (manual)';
$lang['last_update']            = 'Última actualización (automática)';
$lang['manual_last_update']     = 'Última actualización (manual)';
$lang['creationUserId']         = 'Usuario de creación';
$lang['lastupdateUserId']       = 'Usuario última modificación';
$lang['markedForDeletion']      = 'Baja lógica?'; 
$lang['markedForDeletionDate']  = 'Fecha de baja lógica'; 

//User fields
$lang['ip_address'] = 'Dirección IP';
$lang['username'] = 'Nombre de usuario';
$lang['email'] = 'Email';
$lang['Password'] = 'Password';
$lang['activation_code'] = 'Código de activación';
$lang['forgotten_password_code'] = 'Forgotten password code';
$lang['forgotten_password_time'] = 'Forgotten password time' ;
$lang['remember_code'] = 'Código de recuperación';
$lang['created_on'] = 'Creado el';
$lang['active'] = 'Activo';
$lang['first_name'] = 'Nombre';
$lang['last_name'] = 'Apellidos';
$lang['company'] = 'Compañia';
$lang['phone'] = 'Teléfono';
$lang['verify_password']='Verificar password';
$lang['MainOrganizationaUnitId']='Organizational Unit';

//User preferences fields
$lang['userId'] = 'Identificador de usuario';
$lang['theme'] = 'Tema';
$lang['dialogforms'] = 'Activar formualarios en modo diálogo';

//Location fields
$lang['parentLocation'] = 'Espació padre';

//Organizational unit fields
$lang['code'] = 'Código';
$lang['location'] = 'Espacio';


//USER PREFERENCES VIEWS
$lang['user_preferences_admin message1']="Estas viendo todas las preferencias de usuarios porque tienes permiso para ello.";
$lang['user_preferences_admin message2']="Puedes ver tus preferencias ";
$lang['user_preferences_admin message3']="Puedes editar tus preferencias ";
$lang['user_preferences_not_yet_message1']="Estas utilizando las preferencias por defecto ya que no has especificado unas preferencias específicas.";
$lang['user_preferences_not_yet_message2']="Puedes crear tus preferencias ";
$lang['here']="aquí";

$lang['operation_not_allowed']="Operació no permesa";
$lang['edit_not_allowed']="L'edició d'aquest registre no li està permesa al vostre usuari";
$lang['insert_not_allowed']="L'inserció d'aquest registre no li està permesa al vostre usuari";

//Grocery Crud Subjects
$lang['users_subject'] = 'usuarios';
$lang['groups_subject'] = 'grupos';
$lang['user_preferences_subject'] = 'preferencia de usuario';
$lang['location_subject']     = 'espacio';
$lang['organizationalunit_subject'] = 'Unidad organizativa';

$lang['user_info_title']="Información del usuario";
$lang['user_id_title']="Identificador del usuario";
$lang['username_title']="Nombre de usuario";
$lang['name_title']="Nombre";
$lang['surname_title']="Apellidos";
$lang['email_title']="Email";
$lang['realm_title']="Reino";
$lang['user_groups_in_database']="Grupos";
$lang['main_user_organizational_unit']="Unidad organizativa principal";
$lang['rol_title']="Rol";

//ERRORS
$lang['404_page_not_found'] = '404 Page not found';
$lang['404_page_not_found_message'] = "Asked page not found!";
$lang['table_not_found'] = 'Table not found';
$lang['table_not_found_message'] = "Can't found the table";
$lang['InventoryObjectId_not_found']="Object database id doesn't found";

$lang['Yes']= "Si";
$lang['No']= "No";

$lang['show_express_form']="Mostrar formulario expres";
$lang['hide_express_form']="Ocultar formulario expres";
