<?php
/**
 * Funciones del tema Bull Racing.
 * Solo configuración y carga de recursos. La lógica del negocio va en el plugin bullracing-core.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

define( 'BULLRACING_TEMA_VERSION', '0.1.0' );

/**
 * Soportes del tema.
 */
function bullracing_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'principal' => __( 'Menú principal', 'bullracing' ),
			'pie'       => __( 'Menú del pie de página', 'bullracing' ),
		)
	);
}
add_action( 'after_setup_theme', 'bullracing_setup' );

/**
 * Estilos y scripts.
 */
function bullracing_recursos(): void {
	wp_enqueue_style(
		'bullracing-fuentes',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'bullracing-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'bullracing-fuentes' ),
		BULLRACING_TEMA_VERSION
	);

	wp_enqueue_script(
		'bullracing-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		BULLRACING_TEMA_VERSION,
		array( 'in_footer' => true, 'strategy' => 'defer' )
	);

	// Datos que el JS necesita. Las tarifas las entrega la API de bullracing-core.
	wp_localize_script(
		'bullracing-main',
		'bullracingDatos',
		array(
			'apiTarifas' => esc_url_raw( rest_url( 'bullracing/v1/tarifas' ) ),
			'whatsapp'   => bullracing_whatsapp_url(),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'bullracing_recursos' );

/**
 * Enlace de WhatsApp del taller (único canal de contacto).
 * El número se podrá configurar desde el panel del taller (bullracing-core).
 */
function bullracing_whatsapp_url( string $mensaje = '' ): string {
	$numero = apply_filters( 'bullracing_whatsapp_numero', '573115495983' );
	$url    = 'https://wa.me/' . rawurlencode( $numero );

	if ( '' !== $mensaje ) {
		$url .= '?text=' . rawurlencode( $mensaje );
	}

	return $url;
}
