<?php
/**
 * Funciones de ayuda del tema: devuelven o muestran algo, no se enganchan a WordPress.
 * Los ganchos (add_action / add_filter) van en inc/setup.php.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hora en formato de 24 h a texto del diseño: 7 → "7:00 a. m.", 12 → "12:00 m.", 19 → "7:00 p. m.".
 *
 * @param int $hora Hora de 0 a 23.
 */
function bullracing_hora( int $hora ): string {
	if ( 12 === $hora ) {
		return '12:00 m.';
	}
	$sufijo = $hora < 12 ? 'a. m.' : 'p. m.';
	$hora12 = $hora % 12 ?: 12;
	return $hora12 . ':00 ' . $sufijo;
}

/**
 * Genera la cadena de texto de estrellas llenas y vacías.
 * Ej.: 4 → "★★★★☆".
 *
 * @param int $calificacion Número de estrellas de 0 a 5.
 * @return string Cadena con caracteres de estrellas.
 */
function bullracing_estrellas( int $calificacion ): string {
	$calificacion = min( 5, max( 0, $calificacion ) );
	return str_repeat( '★', $calificacion ) . str_repeat( '☆', 5 - $calificacion );
}

/**
 * URL de una página por su slug. Si la página aún no existe, devuelve el ancla de respaldo
 * en el inicio (ej. "#nosotros"), para que el menú nunca quede con enlaces rotos.
 */
function bullracing_url_pagina( string $slug, string $ancla_respaldo = '' ): string {
	$pagina = get_page_by_path( $slug );

	if ( $pagina instanceof WP_Post && 'publish' === $pagina->post_status ) {
		$url = get_permalink( $pagina );
	} else {
		$url = home_url( '/' . $ancla_respaldo );
	}

	/**
	 * Permite cambiar la dirección de una página sin tocar el tema.
	 * Ej.: en local, 'agendamiento' apunta a la página de prueba (mu-plugin local, fuera del repo).
	 */
	return (string) apply_filters( 'bullracing_url_pagina', $url, $slug );
}

/**
 * Enlace de WhatsApp del taller (único canal de contacto).
 * El número se lee directo de inc/datos-taller.php: se escribe en un solo lugar.
 */
function bullracing_whatsapp_url( string $mensaje = '' ): string {
	$numero = (string) bullracing_datos_taller()['whatsapp'];
	$url    = 'https://wa.me/' . rawurlencode( $numero );

	if ( '' !== $mensaje ) {
		$url .= '?text=' . rawurlencode( $mensaje );
	}

	return $url;
}

/**
 * Fotos del taller en assets/img/taller/, cada una con un nombre fijo (ver README).
 * Para poner o cambiar una foto se copia el archivo con ese nombre; si no está, se ve el recuadro pendiente.
 */
function bullracing_foto( string $archivo ): string {
	$ruta = get_template_directory() . '/assets/img/taller/' . $archivo;
	if ( ! file_exists( $ruta ) ) {
		return '';
	}
	// La fecha del archivo va en la dirección: al reemplazar una foto con el mismo nombre, el navegador descarga la nueva.
	return get_template_directory_uri() . '/assets/img/taller/' . rawurlencode( $archivo ) . '?v=' . filemtime( $ruta );
}

/**
 * Muestra un componente de template-parts/componentes/ con sus argumentos.
 *
 * @param string               $nombre Nombre del componente (estrellas, banner-pagina, video-reproductor).
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
