<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Inventory Lang - Catalan
*
* Author: Sergi Tur Badenas
* 		  sergitur@ebretic.com
*         @sergitur
*
* Author: ...
*         @....
*
*
* Created:  31.05.2013
*
* Description:  Català per a l'aplicació d'inventari
*
*/

//GENERAL
$lang['inventory']       		= 'Inventario';
$lang['remember']       		= 'Recordar';

//LOGIN FORM
$lang['login-form-greetings']   = 'Por favor, entrad';
$lang['User']   = 'Usuario';
$lang['Password']   = 'Contraseña';
$lang['Register']   = 'Registrar-se';
$lang['Login']   = 'Entrar';


// Camps
$lang['name']       		= 'Nombre';
$lang['shortName']        	= 'Nombre corto';           
$lang['description']            = 'Descripción';
$lang['entryDate']              = "Fecha de entrada (automática)";
$lang['manualEntryDate']        = "Fecha de entrada (manual)";
$lang['last_update']            = 'Última actualitzación (automática)';
$lang['manual_last_update']     = 'Última actualitzación (manual)';
$lang['creationUserId']         = 'Usuario de creación';
$lang['lastupdateUserId']       = 'Usuario última actualitzación';
$lang['materialId']             = 'Tipo de material';
$lang['brandId']             = 'Marca';
$lang['brand']             = 'Marca';
$lang['modelId']             = 'Modelo';
$lang['location']               = 'Ubicación';
$lang['quantityInStock']        = 'Cantidad'; 
$lang['price']                  = 'Precio'; 
$lang['moneySourceIdcolumn']    = 'Origen del dinero'; 
$lang['providerId']             = 'Proveidor'; 
$lang['preservationState']      = 'Estado de conservación'; 
$lang['markedForDeletion']      = 'Baja lógica?'; 
$lang['markedForDeletionDate']  = 'Fecha de baja'; 
$lang['file_url']               = 'Fichero principal'; 
$lang['OwnerOrganizationalUnit']  = 'Unidad organizativa'; 
$lang['publicId'] = 'Id público';
$lang['externalId'] = 'Id externo';
$lang['externalID'] = 'Id externo';
$lang['externalIDType'] = 'Tipo de Id externo';
$lang['Id'] = 'Id';
$lang['id'] = 'Id';

$lang['code'] = 'Código';
$lang['parentLocation'] = 'Espacio padre';
$lang['parentMaterialId'] = 'Material padre'; 

//SUBJECTS
$lang['object_subject'] = 'objeto';
$lang['externalID_subject']       		= 'identificador externo';
$lang['organizationalunit_subject']     = 'unidad organitzativa';
$lang['location_subject']     = 'ubicación';
$lang['material_subject']     = 'tipo de material';
$lang['brand_subject']     = 'marca';
$lang['model_subject']     = 'modelo';
$lang['provider_subject']     = 'proveidor';
$lang['money_source_id_subject'] = 'origen del dinero';
$lang['users_subject'] = 'usuario';
$lang['groups_subject'] = 'grupo';

//BUTTONS
$lang['reset'] = 'Reset';
$lang['select_all'] = 'Seleccionar tot';
$lang['unselect_all'] = 'Deseleccionar tot';
$lang['apply'] = 'Aplicar';

//PLACEHOLDERS
$lang['choose_fields'] = 'Escoge los campos a mostrar';
$lang['fields_tho_show'] = 'Campos a mostrar';


//ACTIONS
$lang['Images'] = 'Imagenes';
$lang['QRCode'] = 'Código QR';
$lang['View'] = 'Ver';

//LOGIN & AUTH
$lang['CloseSession'] = 'Cerrar sessión';

//MENUS
$lang['devices'] = 'Dispositivos';
 $lang['computers'] = 'Ordenadores';
 $lang['others'] = 'Otros';

$lang['maintenances'] = 'Mantenimientos';
 $lang['externalid_menu'] = 'Tipo de identificador externo';
 $lang['organizationalunit_menu'] = 'Unidades organizativas';
 $lang['location_menu'] = 'Espacis';
 $lang['brand_menu'] = 'Marcas';
 $lang['model_menu'] = 'Modelos';
 $lang['material_menu'] = 'Tipo de material';
 $lang['provider_menu'] = 'Proveidores';
 $lang['money_source_menu'] = 'Origen del dinero';

$lang['reports'] = 'Informes';
 $lang['global_reports'] = 'Informes globales';
 $lang['reports_by_organizationalunit'] = 'Informes por unidad organizativa';

$lang['managment'] = 'Gestión';
 $lang['users'] = 'Usuarios';
 $lang['groups'] = 'Grupos';
 $lang['preferences'] = 'Preferencias';

//ERRORS
$lang['404_page_not_found'] = '404 Página no encontrada';
$lang['404_page_not_found_message'] = 'La página que habeis pedido no se ha podido encontrar';
$lang['table_not_found'] = 'Tabla no encontrada';
$lang['table_not_found_message'] = 'La tabla no se ha podido encontrar';

 
//OPTIONS
$lang['Good'] = 'Bueno';
$lang['Regular'] = 'Regular';
$lang['Bad'] = 'Malo';
$lang['Yes'] = 'Si';
$lang['No'] = 'No';

//SUPPORTED LANGUAGES
$lang['language'] = 'Idioma';
$lang['catalan'] = 'catalan';
$lang['spanish'] = 'Castellano';
$lang['english'] = 'Inglés';

$lang['ip_address'] = 'Dirección IP';
$lang['username'] = "Nombre de usuario";
$lang['email'] = 'Correo electrónico';
$lang['activation_code'] = "Códi de activación";
$lang['forgotten_password_code'] = 'Codi paraula de pas oblidada';
$lang['forgotten_password_time'] = 'Temps de la paraula de pas oblidada' ;
$lang['remember_code'] = 'Codi de recuperació';
$lang['created_on'] = 'Creat el';
$lang['active'] = 'Actiu';
$lang['first_name'] = 'Nom';
$lang['last_name'] = 'Cognoms';
$lang['company'] = 'Companyia';
$lang['phone'] = 'Telèfon';


$lang['Filter by organizational units'] = 'Filtrar per unitats organitzatives';
$lang['choose_organization_unit'] = 'Escolliu una unitat organitzativa';

$lang['maintenance_mode_message'] = "El sistema es troba actualment en manteniment. No podeu entrar a l'aplicació en aquests moments, proveu més tard o poseu-vos en contacte amb l'administrador. Disculpeu les molèsties.";
$lang['maintenance_mode']="Mode manteniment";
$lang['maintenance_mode_login_error_message']="El login no és correcte";

$lang['grocerycrud_state_unknown']="Desconegut";
$lang['grocerycrud_state_listing']="Llistant";
$lang['grocerycrud_state_adding']="Afegint";
$lang['grocerycrud_state_editing']="Editant";
$lang['grocerycrud_state_deleting']="Esborrant";
$lang['grocerycrud_state_inserting']="inserting";
$lang['grocerycrud_state_updating']="Actualitzant";
$lang['grocerycrud_state_listing_ajax']="Llista ajax";
$lang['grocerycrud_state_listing_ajax_info']="Llista d'informació Ajax";
$lang['grocerycrud_state_inserting_validation']="Validant inserció";
$lang['grocerycrud_state_uploading_validation']="Validant pujada de fitxer";
$lang['grocerycrud_state_uploading_file']="Pujant fitxer";
$lang['grocerycrud_state_deleting_file']="Esborrant fitxer";
$lang['grocerycrud_state_ajax_relation']="Relació Ajax";
$lang['grocerycrud_state_ajax_relation_n_n']="Relació Ajax n_n";
$lang['grocerycrud_state_exit']="Èxit";
$lang['grocerycrud_state_exporting']="Exportant";
$lang['grocerycrud_state_printing']="Imprimint";

$lang['login_unsuccessful_not_allowed_role'] = "El login és correcte però l'usuari no té un rol adequat per accedir a l'aplicació";

$lang['user_info_title']="Informació de l'usuari";
$lang['user_id_title']="Identificador d'usuari";
$lang['username_title']="Nom d'usuari";
$lang['email_title']="Correu electrònic";
$lang['realm_title']="Reialme";
$lang['roles_title']="Roles";
$lang['inventory_object_fields_title']="Camps per defecte dels objectes";
$lang['externalIDType_fields_title']="Camps per defecte dels identificadors externs";
$lang['organizational_unit_fields_title']="Camps per defecte de les unitats organitzatives";
$lang['location_fields_title']="Camps per defecte dels espais";
$lang['material_fields_title']="Camps per defecte dels tipus de material";
$lang['brand_fields_title']="Camps per defecte de les marques";
$lang['model_fields_title']="Camps per defecte dels models";
$lang['provider_fields_title']="Camps per defecte dels proveïdors";
$lang['money_source_fields_title']="Camps per defecte dels origens dels diners";
$lang['users_fields_title']="Camps per defecte dels usuaris";
$lang['groups_fields_title']="Camps per defecte dels grups";

$lang['come_back']="Tornar";
