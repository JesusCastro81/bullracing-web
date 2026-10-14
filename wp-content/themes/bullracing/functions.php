<?php
/**
 * Funciones del tema Bull Racing.
 * Solo configuración y carga de recursos. La lógica del negocio va en el plugin bullracing-core.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

define( 'BULLRACING_TEMA_VERSION', '0.2.0' );

/**
 * Skin activa: el diseño visual escogido. Cambiar de diseño = cambiar este valor
 * (y tener su archivo en assets/css/skins/).
 */
define( 'BULLRACING_SKIN', 'base' );

require_once get_template_directory() . '/inc/datos-taller.php';

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
 * Estilos y scripts. Orden de carga: fuentes → tokens → skin → main.
 * Todo es local: el sitio funciona sin conexión a servicios externos.
 */
function bullracing_recursos(): void {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'bullracing-fuentes', $uri . '/assets/fonts/fonts.css', array(), BULLRACING_TEMA_VERSION );
	wp_enqueue_style( 'bullracing-tokens', $uri . '/assets/css/tokens.css', array( 'bullracing-fuentes' ), BULLRACING_TEMA_VERSION );
	wp_enqueue_style( 'bullracing-skin', $uri . '/assets/css/skins/' . BULLRACING_SKIN . '.css', array( 'bullracing-tokens' ), BULLRACING_TEMA_VERSION );
	wp_enqueue_style( 'bullracing-main', $uri . '/assets/css/main.css', array( 'bullracing-skin' ), BULLRACING_TEMA_VERSION );

	wp_enqueue_script(
		'bullracing-main',
		$uri . '/assets/js/main.js',
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
 * Muestra un componente de template-parts/componentes/ con sus argumentos.
 *
 * @param string               $nombre Nombre del componente (boton, estrellas, tarjeta).
 * @param array<string, mixed> $args   Argumentos del componente.
 */
function bullracing_componente( string $nombre, array $args = array() ): void {
	get_template_part( 'template-parts/componentes/' . sanitize_key( $nombre ), null, $args );
}

/**
 * Menú principal por defecto, mientras no se configure uno en Apariencia → Menús.
 */
function bullracing_menu_respaldo(): void {
	$enlaces = array(
		__( 'Planes', 'bullracing' )        => home_url( '/#planes' ),
		__( 'Motolavado', 'bullracing' )    => home_url( '/#motolavado' ),
		__( 'Quiénes somos', 'bullracing' ) => bullracing_url_pagina( 'quienes-somos', '#nosotros' ),
		__( 'Contacto', 'bullracing' )      => bullracing_url_pagina( 'contacto', '#contacto' ),
	);

	echo '<ul class="br-menu">';
	foreach ( $enlaces as $texto => $url ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $texto ) );
	}
	echo '</ul>';
}

/**
 * Enlace de WhatsApp del taller (único canal de contacto).
 * El número sale de bullracing_datos_taller() (inc/datos-taller.php).
 */
function bullracing_whatsapp_url( string $mensaje = '' ): string {
	$numero = apply_filters( 'bullracing_whatsapp_numero', '573115495983' );
	$url    = 'https://wa.me/' . rawurlencode( $numero );

	if ( '' !== $mensaje ) {
		$url .= '?text=' . rawurlencode( $mensaje );
	}

	return $url;
}
