<?php
/**
 * Plugin Name:       Bull Racing Core
 * Description:       Lógica del negocio de Motos Bull Racing: tarifas, rol "Taller", panel de precios y API REST.
 * Version:           0.1.0
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Text Domain:       bullracing-core
 *
 * @package BullRacingCore
 */

defined( 'ABSPATH' ) || exit;

define( 'BULLRACING_CORE_VERSION', '0.1.0' );
define( 'BULLRACING_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'BULLRACING_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once BULLRACING_CORE_DIR . 'includes/class-tarifas.php';
require_once BULLRACING_CORE_DIR . 'includes/class-rol-taller.php';
require_once BULLRACING_CORE_DIR . 'includes/class-admin-precios.php';
require_once BULLRACING_CORE_DIR . 'includes/class-rest.php';

/**
 * Activación: crea la tabla de tarifas, la llena si está vacía y crea el rol "Taller".
 */
function bullracing_core_activar(): void {
	BullRacing_Tarifas::crear_tabla();
	BullRacing_Tarifas::sembrar_si_vacia();
	BullRacing_Rol_Taller::registrar();
}
register_activation_hook( __FILE__, 'bullracing_core_activar' );

add_action( 'admin_menu', array( 'BullRacing_Admin_Precios', 'registrar_menu' ) );
add_action( 'rest_api_init', array( 'BullRacing_Rest', 'registrar_rutas' ) );
