<?php
/**
 * Configuración del tema: todo lo que se engancha a WordPress (add_action / add_filter).
 * Orden: 1. soportes del tema → 2. estilos y scripts → 3. fotos de las secciones.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

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
			// Horario para el aviso abierto/cerrado (sale de inc/datos-taller.php).
			'horario'    => array_map(
				static fn( $fila ) => array(
					'dias'   => array_map( 'intval', (array) $fila['semana'] ),
					'abre'   => (int) $fila['abre'],
					'cierra' => (int) $fila['cierra'],
				),
				(array) bullracing_datos_taller()['horario']
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'bullracing_recursos' );

add_filter(
	'bullracing_contacto_fotos',
	static function ( array $fotos ): array {
		$fotos['rampa']    = bullracing_foto( 'contacto-rampa.jpg' );
		$fotos['entrega']  = bullracing_foto( 'contacto-entrega.jpg' );
		$fotos['carrusel'] = array(
			array( 'texto' => __( 'Recibo', 'bullracing' ), 'url' => bullracing_foto( 'contacto-carrusel-1.jpg' ) ),
			array( 'texto' => __( 'Rampa', 'bullracing' ), 'url' => bullracing_foto( 'contacto-carrusel-2.jpg' ) ),
			array( 'texto' => __( 'Lavado', 'bullracing' ), 'url' => bullracing_foto( 'contacto-carrusel-3.jpg' ) ),
			array( 'texto' => __( 'Entrega', 'bullracing' ), 'url' => bullracing_foto( 'contacto-carrusel-4.jpg' ) ),
		);
		return $fotos;
	}
);

add_filter(
	'bullracing_motolavado_fotos',
	static function ( array $fotos ): array {
		$fotos['antes']   = bullracing_foto( 'motolavado-antes.jpg' );
		$fotos['despues'] = bullracing_foto( 'motolavado-despues.jpg' );
		return $fotos;
	}
);
