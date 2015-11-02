<?php

$lang = array(

//----------------------------
// General word list
//----------------------------

'no' =>
'No',

'yes' =>
'Si',

'on' =>
'on',

'off' =>
'off',

'first' =>
'Primero',

'last' =>
'Último',

'enabled' =>
'enabled',

'disabled' =>
'disabled',

'back' =>
'Atrás',

'submit' =>
'Enviar',

'update' =>
'Actualizar',

'thank_you' =>
'Gracias!',

'page' =>
'Page',

'of' =>
'de',

'by' =>
'por',

'at' =>
'en',

'dot' =>
'punto',

'and' =>
'y',

'or' =>
'o',

'id' =>
'ID',

'encoded_email' =>
'(Javascript debe ser habilitado para ver esta dirección de correo electrónico)',

'search' =>
'búsqueda',

'system_off_msg' =>
'Este sitio se encuentra actualmente inactivo.',

'not_authorized' =>
'Usted no está autorizado para realizar esta acción',

'auto_redirection' =>
'Usted será redirigido automáticamente en %x segundos',

'click_if_no_redirect' =>
'Haga clic aquí si usted no está redirigido',

'return_to_previous' =>
'Volver a la pagina anterior',

'not_available' =>
'No disponible',

'setting' =>
'ajuste',

'preference' =>
'preferencia',

'pag_first_link' => '&lsaquo; primero',
'pag_last_link' => 'último &rsaquo;',

//----------------------------
// Errors
//----------------------------

'error' =>
'Error',

'generic_fatal_error' =>
'Algo ha salido mal y este URL no puede ser procesado en este momento.',

'invalid_url' =>
'La URL que ha enviado no es válido.',

'submission_error' =>
'ATENCIÓN',

'general_error' =>
'Se encontraron los siguientes errores',

'invalid_action' =>
'La acción que ha solicitado no es válido.',

'csrf_token_expired' =>
'Esta forma ha expirado. Por favor regenere y vuelva a intentarlo.',

'current_password_required' =>
'Se requiere su contraseña actual.',

'current_password_incorrect' =>
'Su contraseña actual no se presentó correctamente.',

'captcha_required' =>
'Debe enviar la palabra que aparece en la imagen',

'captcha_incorrect' =>
'El texto que ingresaste no es el mismo que el de la imagen. Por favor intenta nuevamente.',

'nonexistent_page' =>
'La página solicitada no fue encontrada',

'unable_to_load_field_type' =>
'Unable to load requested field type file:  %s.<br />
Confirm the fieldtype file is located in the expressionengine/third_party/ directory',

'unwritable_cache_folder' =>
'Your cache folder does not have proper permissions.<br>
To fix: Set the cache folder (/expressionengine/cache/) permissions to 777 (or equivalent for your server).',

'unwritable_config_file' =>
'Your configuration file does not have the proper permissions.<br>
To fix: Set the config file (/expressionengine/config/config.php) permissions to 666 (or equivalent for your server).',

'redirect_xss_fail' => 'The link you are being redirected to contained some
potentially malicious or dangerous code. We recommend you hit the back button
and email %s to report the link that generated this message.',

//----------------------------
// Member Groups
//----------------------------

'banned' =>
'Banned',

'guests' =>
'Guests',

'members' =>
'Members',

'pending' =>
'Pending',

'super_admins' =>
'Super Admins',


//----------------------------
// Template.php
//----------------------------

'error_tag_syntax' =>
'La siguiente etiqueta tiene un error de sintaxis:',

'error_fix_syntax' =>
'Por favor, corrija la sintaxis en su plantilla.',

'error_tag_module_processing' =>
'La siguiente etiqueta no puede ser procesada:',

'error_fix_module_processing' =>
'Por favor verifique que el \'%x\' módulo está instalado y que \'%y\' es un método disponible del módulo ',

'template_loop' =>
'Has causado un bucle de plantilla debido a la sub-plantillas incorrectamente anidados (\'%s\' recursiva llamada)',

'template_load_order' =>
'Orden de carga de plantilla',

'error_multiple_layouts' =>
'Múltiples diseños encontrados, por favor asegúrese de que sólo tiene una etiqueta de diseño por plantilla',

'error_layout_too_late' =>
'Plugin o módulo tag found antes de la declaración de diseño. Por favor, mueva la etiqueta de diseño para la tapa de su plantilla.',

'error_invalid_conditional' =>
'Usted tiene una condición no válida en su plantilla. Por favor, revise sus condicionales para una cadena sin cerrar, los operadores no válidos, un faltante}, o una falta {/if}.',

'layout_contents_reserved' =>
'The name "contents" is reserved for the template data and cannot be used as a layout variable (i.e. {layout:set name="contents"} or {layout="foo/bar" contents=""}).',

//----------------------------
// Email
//----------------------------

'forgotten_email_sent' =>
'Si esta dirección de correo electrónico está asociada a una cuenta, instrucciones para restablecer su contraseña acaban de ser enviado por correo electrónico a usted.',

'error_sending_email' =>
'No se puede enviar correo electrónico en este momento.',

'no_email_found' =>
'La dirección de correo electrónico que ha enviado no se encontró en la base de datos.',

'password_reset_flood_lock' =>
'Usted ha tratado de restablecer su contraseña demasiadas veces hoy. Por favor, compruebe sus carpetas de bandeja de entrada de spam y para las solicitudes anteriores, o póngase en contacto con el administrador del sitio.',

'your_new_login_info' =>
'información de acceso',

'password_has_been_reset' =>
'Su contraseña se restableció y uno nuevo ha sido enviado por correo electrónico a usted.',

//----------------------------
// Date
//----------------------------

'singular' =>
'uno',

'less_than' =>
'menos que',

'about' =>
'acerca de',

'past' =>
'%s atrás',

'future' =>
'en %s',

'ago' =>
'%x atrás',

'year' =>
'año',

'years' =>
'añoss',

'month' =>
'mes',

'months' =>
'meses',

'fortnight' =>
'quincena',

'fortnights' =>
'quincenas',

'week' =>
'semana',

'weeks' =>
'semanas',

'day' =>
'día',

'days' =>
'días',

'hour' =>
'hora',

'hours' =>
'horass',

'minute' =>
'minuto',

'minutes' =>
'minutoss',

'second' =>
'segundo',

'seconds' =>
'segundoss',

'am' =>
'am',

'pm' =>
'pm',

'AM' =>
'AM',

'PM' =>
'PM',

'Sun' =>
'Dom',

'Mon' =>
'Lun',

'Tue' =>
'Mar',

'Wed' =>
'Mie',

'Thu' =>
'Jue',

'Fri' =>
'Vie',

'Sat' =>
'Sab',

'Su' =>
'D',

'Mo' =>
'L',

'Tu' =>
'M',

'We' =>
'M',

'Th' =>
'J',

'Fr' =>
'V',

'Sa' =>
'S',

'Sunday' =>
'Domingo',

'Monday' =>
'Lunes',

'Tuesday' =>
'Martes',

'Wednesday' =>
'Miércoles',

'Thursday' =>
'Jueves',

'Friday' =>
'Viernes',

'Saturday' =>
'Sabado',


'Jan' =>
'Ene',

'Feb' =>
'Feb',

'Mar' =>
'Mar',

'Apr' =>
'Abr',

'May' =>
'May',

'Jun' =>
'Jun',

'Jul' =>
'Jul',

'Aug' =>
'Ago',

'Sep' =>
'Sep',

'Oct' =>
'Oct',

'Nov' =>
'Nov',

'Dec' =>
'Dic',

'January' =>
'Enero',

'February' =>
'Febrero',

'March' =>
'Marzo',

'April' =>
'Abril',

'May_l' =>
'Mayo',

'June' =>
'Junio',

'July' =>
'Julio',

'August' =>
'Agosto',

'September' =>
'Septiembre',

'October' =>
'Octubre',

'November' =>
'Noviember',

'December' =>
'Diciembre',


'UM12'		=>	'(UTC -12:00) Baker/Howland Island',
'UM11'		=>	'(UTC -11:00) Niue',
'UM10'		=>	'(UTC -10:00) Hawaii-Aleutian Standard Time, Cook Islands, Tahiti',
'UM95'		=>	'(UTC -9:30) Marquesas Islands',
'UM9'		=>	'(UTC -9:00) Alaska Standard Time, Gambier Islands',
'UM8'		=>	'(UTC -8:00) Pacific Standard Time, Clipperton Island',
'UM7'		=>	'(UTC -7:00) Mountain Standard Time',
'UM6'		=>	'(UTC -6:00) Central Standard Time',
'UM5'		=>	'(UTC -5:00) Eastern Standard Time, Western Caribbean Standard Time',
'UM45'		=>	'(UTC -4:30) Venezuelan Standard Time',
'UM4'		=>	'(UTC -4:00) Atlantic Standard Time, Eastern Caribbean Standard Time',
'UM35'		=>	'(UTC -3:30) Newfoundland Standard Time',
'UM3'		=>	'(UTC -3:00) Argentina, Brazil, French Guiana, Uruguay',
'UM2'		=>	'(UTC -2:00) South Georgia/South Sandwich Islands',
'UM1'		=>	'(UTC -1:00) Azores, Cape Verde Islands',
'UTC'		=>	'(UTC) Greenwich Mean Time, Western European Time',
'UP1'		=>	'(UTC +1:00) Central European Time, West Africa Time',
'UP2'		=>	'(UTC +2:00) Central Africa Time, Eastern European Time, Kaliningrad Time',
'UP3'		=>	'(UTC +3:00) East Africa Time, Arabia Standard Time',
'UP35'		=>	'(UTC +3:30) Iran Standard Time',
'UP4'		=>	'(UTC +4:00) Moscow Time, Azerbaijan Standard Time',
'UP45'		=>	'(UTC +4:30) Afghanistan',
'UP5'		=>	'(UTC +5:00) Pakistan Standard Time, Yekaterinburg Time',
'UP55'		=>	'(UTC +5:30) Indian Standard Time, Sri Lanka Time',
'UP575'		=>	'(UTC +5:45) Nepal Time',
'UP6'		=>	'(UTC +6:00) Bangladesh Standard Time, Bhutan Time, Omsk Time',
'UP65'		=>	'(UTC +6:30) Cocos Islands, Myanmar',
'UP7'		=>	'(UTC +7:00) Krasnoyarsk Time, Cambodia, Laos, Thailand, Vietnam',
'UP8'		=>	'(UTC +8:00) Australian Western Standard Time, Beijing Time, Irkutsk Time',
'UP875'		=>	'(UTC +8:45) Australian Central Western Standard Time',
'UP9'		=>	'(UTC +9:00) Japan Standard Time, Korea Standard Time, Yakutsk Time',
'UP95'		=>	'(UTC +9:30) Australian Central Standard Time',
'UP10'		=>	'(UTC +10:00) Australian Eastern Standard Time, Vladivostok Time',
'UP105'		=>	'(UTC +10:30) Lord Howe Island',
'UP11'		=>	'(UTC +11:00) Magadan Time, Solomon Islands, Vanuatu',
'UP115'		=>	'(UTC +11:30) Norfolk Island',
'UP12'		=>	'(UTC +12:00) Fiji, Gilbert Islands, Kamchatka Time, New Zealand Standard Time',
'UP1275'	=>	'(UTC +12:45) Chatham Islands Standard Time',
'UP13'		=>	'(UTC +13:00) Samoa Time Zone, Phoenix Islands Time, Tonga',
'UP14'		=>	'(UTC +14:00) Line Islands',

"select_timezone" =>
"Select Timezone",

"no_timezones" =>
"No Timezones",

// IGNORE
''=>'');
/* End of file core_lang.php */
/* Location: ./system/expressionengine/language/english/core_lang.php */
