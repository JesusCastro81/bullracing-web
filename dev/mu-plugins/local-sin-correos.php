<?php
/**
 * Plugin Name: Local · Sin correos
 * Description: Evita que el entorno LOCAL envíe correos reales. No subir a producción.
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'pre_wp_mail', '__return_false' );
