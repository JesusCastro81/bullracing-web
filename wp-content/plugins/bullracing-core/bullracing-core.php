<?php
/**
 * Plugin Name:       Bull Racing Core
 * Description:       Lógica del negocio de Motos Bull Racing: tarifas, rol "Taller", panel de precios, API REST y acceso a la OT (rol "Recepción de motos").
 * Version:           0.2.0
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Text Domain:       bullracing-core
 *
 * @package BullRacingCore
 */

defined( 'ABSPATH' ) || exit;

define( 'BULLRACING_CORE_VERSION', '0.2.0' );
define( 'BULLRACING_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'BULLRACING_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once BULLRACING_CORE_DIR . 'includes/class-tarifas.php';
require_once BULLRACING_CORE_DIR . 'includes/class-rol-taller.php';
require_once BULLRACING_CORE_DIR . 'includes/class-admin-precios.php';
require_once BULLRACING_CORE_DIR . 'includes/class-rest.php';
require_once BULLRACING_CORE_DIR . 'includes/class-acceso-ot.php';

/**
 * Activación: crea la tabla de tarifas, la llena si está vacía, crea el rol "Taller"
 * y el acceso a la OT (rol "Recepción de motos" y registro de recepción).
 */
function bullracing_core_activar(): void {
	BullRacing_Tarifas::crear_tabla();
	BullRacing_Tarifas::sembrar_si_vacia();
	BullRacing_Rol_Taller::registrar();
	BullRacing_Acceso_OT::instalar();
}
register_activation_hook( __FILE__, 'bullracing_core_activar' );

add_action( 'admin_menu', array( 'BullRacing_Admin_Precios', 'registrar_menu' ) );
add_action( 'rest_api_init', array( 'BullRacing_Rest', 'registrar_rutas' ) );

// Acceso a la OT.
add_action( 'init', array( 'BullRacing_Acceso_OT', 'actualizar_si_hace_falta' ) );
add_action( 'admin_init', array( 'BullRacing_Acceso_OT', 'limitar_escritorio' ) );
add_filter( 'login_redirect', array( 'BullRacing_Acceso_OT', 'redirigir_al_entrar' ), 10, 3 );
add_filter( 'show_admin_bar', array( 'BullRacing_Acceso_OT', 'ocultar_barra' ) );
add_filter( 'bullracing_datos_taller', array( 'BullRacing_Acceso_OT', 'contador_en_datos' ) );
