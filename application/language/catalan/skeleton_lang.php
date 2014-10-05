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

//SUPPORTED LANGUAGES
$lang['language'] = 'Idioma';
$lang['catalan'] = 'Català';
$lang['spanish'] = 'Castellà';
$lang['english'] = 'Anglès';

//LOGIN & AUTH
$lang['CloseSession'] = 'Tancar sessió';

// Fields
$lang['name']       		= 'Nom';
$lang['shortName']        	= 'Nom curt';           
$lang['description']            = 'Descripció';
$lang['entryDate']              = "Data d'entrada (automàtica)";
$lang['manualEntryDate']        = "Data d'entrada (manual)";
$lang['last_update']            = 'Última actualització (automàtica)';
$lang['manual_last_update']     = 'Última actualització (manual)';
$lang['creationUserId']         = 'Usuari de creació';
$lang['lastupdateUserId']       = 'Usuari darrera actualització';
$lang['markedForDeletion']      = 'Baixa lògica?'; 
$lang['markedForDeletionDate']  = 'Data de baixa'; 

//User fields
$lang['ip_address'] = 'Adreça IP';
$lang['username'] = "Nom d'usuari";
$lang['email'] = 'Correu electrònic';
$lang['Password'] = 'Paraula de pas';
$lang['activation_code'] = "Codi d'activació";
$lang['forgotten_password_code'] = 'Codi paraula de pas oblidada';
$lang['forgotten_password_time'] = 'Temps de la paraula de pas oblidada' ;
$lang['remember_code'] = 'Codi de recuperació';
$lang['created_on'] = 'Creat el';
$lang['active'] = 'Actiu';
$lang['first_name'] = 'Nom';
$lang['last_name'] = 'Cognoms';
$lang['company'] = 'Companyia';
$lang['phone'] = 'Telèfon';
$lang['verify_password']="Verificar paraula de pas";
$lang['MainOrganizationaUnitId']="Unitat organitzativa";

//USER PREFERENCES FIELDS
$lang['userId'] = "Id d'usuari";
$lang['theme'] = 'Tema';
$lang['dialogforms'] = 'Activar formularis en mode diàleg';

//Location fields
$lang['parentLocation'] = 'Espai pare';

//Organizational unit fields
$lang['code'] = 'Codi';
$lang['location'] = 'Espai';

//USER PREFERENCES VIEWS
$lang['user_preferences_admin message1']="Es mostren les preferències de tots els usuaris perque sou un usuari amb un rol que ho permet.";
$lang['user_preferences_admin message2']="Podeu veure les vostres preferències ";
$lang['user_preferences_admin message3']="Podeu editar les vostres preferències ";
$lang['user_preferences_not_yet_message1']="Esteu utilitzant les preferències per defecte ja que encara no les heu definit.";
$lang['user_preferences_not_yet_message2']="Podeu crear les vostres preferències ";
$lang['here']="aquí";

//Grocery Crud Subjects
$lang['users_subject'] = 'usuaris';
$lang['groups_subject'] = 'grups';
$lang['user_preferences_subject'] = "preferència d'usuari";
$lang['location_subject']     = 'espai';
$lang['organizationalunit_subject'] = 'unitat organitzativa';


$lang['user_info_title']="Informació de l'usuari";
$lang['user_id_title']="Identificador d'usuari";
$lang['username_title']="Nom d'usuari";
$lang['name_title']="Nom";
$lang['surname_title']="Cognoms";
$lang['email_title']="Correu electrònic";
$lang['realm_title']="Reialme";
$lang['user_groups_in_database']="Grups";
$lang['main_user_organizational_unit']="Unitat organitzativa principal";
$lang['rol_title']="Rol";

//ERRORS
$lang['404_page_not_found'] = '404 Pàgina no trobada';
$lang['404_page_not_found_message'] = "La pàgina que heu demanat no s'ha pogut trobar";
$lang['table_not_found'] = 'Taula no trobada';
$lang['table_not_found_message'] = "La taula no s'ha pogut trobar";
$lang['InventoryObjectId_not_found']="No s'ha trobat el identificador del objecte a la base de dades";

$lang['Yes']= "Sí";
$lang['No']= "No";

$lang['show_express_form']="Mostrar el formulari express";
$lang['hide_express_form']="Amagar el formulari express";

$lang['Atention']="Atenció";
$lang['SPAM_Message_Part1']="Comproveu la vostra carpeta <b>d'SPAM</b> o <b>correu no desitjat</b>!";
$lang['SPAM_Message_Part2']='Molts sistemes de correu electrònic com Gmail o Hotmail us posaran els emails enviats per <br/>aquesta aplicació a la carpeta "Correu no desitjat"';
