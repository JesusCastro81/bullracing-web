<?php
/**
 * Plantilla de wp-config.php para el entorno LOCAL.
 * Copiar como wp-config.php en la raíz de WordPress y completar los valores.
 * El wp-config.php real NUNCA se sube al repositorio.
 */

define( 'DB_NAME', 'nombre_base_local' );
define( 'DB_USER', 'usuario_local' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

// Generar llaves nuevas en: https://api.wordpress.org/secret-key/1.1/salt/
define( 'AUTH_KEY',         'poner-llave-unica' );
define( 'SECURE_AUTH_KEY',  'poner-llave-unica' );
define( 'LOGGED_IN_KEY',    'poner-llave-unica' );
define( 'NONCE_KEY',        'poner-llave-unica' );
define( 'AUTH_SALT',        'poner-llave-unica' );
define( 'SECURE_AUTH_SALT', 'poner-llave-unica' );
define( 'LOGGED_IN_SALT',   'poner-llave-unica' );
define( 'NONCE_SALT',       'poner-llave-unica' );

$table_prefix = 'wpbu_';

// Entorno local
define( 'WP_HOME', 'http://localhost/bullracing-real' );
define( 'WP_SITEURL', 'http://localhost/bullracing-real' );
define( 'WP_ENVIRONMENT_TYPE', 'local' );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'DISABLE_WP_CRON', true );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
