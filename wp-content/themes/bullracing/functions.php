<?php
/**
 * Funciones del tema Bull Racing.
 * Solo configuración y carga de recursos. La lógica del negocio va en el plugin bullracing-core.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

define( 'BULLRACING_TEMA_VERSION', '0.6.4' );

/**
 * Skin activa: el diseño visual escogido. Cambiar de diseño = cambiar este valor
 * (y tener su archivo en assets/css/skins/).
 */
define( 'BULLRACING_SKIN', 'taller' );

require_once get_template_directory() . '/inc/datos-taller.php';
require_once get_template_directory() . '/inc/datos-planes.php';
require_once get_template_directory() . '/inc/datos-terminos.php';

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
 * Estilos y scripts. Orden de carga: fuentes → tokens → main → skin.
 * La skin va de última para que sus ajustes visuales ganen sobre la estructura de main.css
 * (los tokens del nivel 2 que redefine aplican igual en cualquier orden).
 * Todo es local: el sitio funciona sin conexión a servicios externos.
 */
function bullracing_recursos(): void {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'bullracing-fuentes', $uri . '/assets/fonts/fonts.css', array(), BULLRACING_TEMA_VERSION );
	wp_enqueue_style( 'bullracing-tokens', $uri . '/assets/css/tokens.css', array( 'bullracing-fuentes' ), BULLRACING_TEMA_VERSION );
	wp_enqueue_style( 'bullracing-main', $uri . '/assets/css/main.css', array( 'bullracing-tokens' ), BULLRACING_TEMA_VERSION );
	wp_enqueue_style( 'bullracing-skin', $uri . '/assets/css/skins/' . BULLRACING_SKIN . '.css', array( 'bullracing-main' ), BULLRACING_TEMA_VERSION );

	wp_enqueue_script(
		'bullracing-main',
		$uri . '/assets/js/main.js',
		array(),
		BULLRACING_TEMA_VERSION,
		array( 'in_footer' => true, 'strategy' => 'defer' )
	);

	// En la página de agendamiento: precarga lo que el cliente escogió en la landing.
	// No modifica el agendamiento, solo escoge sus opciones (ver el archivo).
	if ( is_page( 'agendamiento' ) ) {
		wp_enqueue_script(
			'bullracing-precarga-agendamiento',
			$uri . '/assets/js/precarga-agendamiento.js',
			array(),
			BULLRACING_TEMA_VERSION,
			array( 'in_footer' => true )
		);
	}

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
		__( 'El taller', 'bullracing' )     => home_url( '/#taller' ),
		__( 'Quiénes somos', 'bullracing' ) => bullracing_url_pagina( 'quienes-somos', '#nosotros' ),
		__( 'Contacto', 'bullracing' )      => bullracing_url_pagina( 'contacto', '#contacto' ),
	);

	// Página actual (para marcarla en el menú). Los enlaces con # son secciones del inicio.
	$actual = is_singular() ? untrailingslashit( (string) get_permalink() ) : '';

	echo '<ul class="br-menu">';
	foreach ( $enlaces as $texto => $url ) {
		$es_actual = $actual && false === strpos( $url, '#' ) && untrailingslashit( $url ) === $actual;
		printf(
			'<li><a href="%s"%s>%s</a></li>',
			esc_url( $url ),
			$es_actual ? ' aria-current="page"' : '',
			esc_html( $texto )
		);
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
