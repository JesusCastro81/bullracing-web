<?php
/**
 * Funciones del tema Bull Racing: solo constantes y carga de archivos.
 * La lógica del negocio va en el plugin bullracing-core.
 *
 * Mapa de inc/:
 *   helpers.php        funciones de ayuda (hora, URLs, WhatsApp, fotos, componentes, menú)
 *   datos-taller.php   fuente única de datos del taller
 *   datos-planes.php   planes de mantenimiento
 *   datos-terminos.php términos y condiciones
 *   setup.php          ganchos de WordPress (soportes, estilos, scripts, fotos)
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

// La versión se escribe solo en style.css (donde WordPress la busca). Va en la dirección de los
// CSS y JS: al subirla, los navegadores descargan los archivos nuevos en vez de usar los guardados.
define( 'BULLRACING_TEMA_VERSION', (string) wp_get_theme( get_template() )->get( 'Version' ) );

/**
 * Skin activa: el diseño visual escogido. Cambiar de diseño = cambiar este valor
 * (y tener su archivo en assets/css/skins/).
 */
define( 'BULLRACING_SKIN', 'taller' );

$bullracing_inc = get_template_directory() . '/inc/';

require_once $bullracing_inc . 'helpers.php';
require_once $bullracing_inc . 'datos-taller.php';
require_once $bullracing_inc . 'datos-planes.php';
require_once $bullracing_inc . 'datos-terminos.php';
require_once $bullracing_inc . 'setup.php';
