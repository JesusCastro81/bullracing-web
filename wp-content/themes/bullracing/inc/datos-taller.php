<?php
/**
 * Datos del taller usados en header, footer y contacto.
 * Un solo lugar para cambiarlos. Más adelante se podrán editar desde el panel del taller
 * (bullracing-core) usando el filtro 'bullracing_datos_taller'.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, mixed>
 */
function bullracing_datos_taller(): array {
	$datos = array(
		'nombre'       => 'Motos Bull Racing',
		'lema'         => 'Rueda libre, rueda seguro',
		'whatsapp'     => '573115495983',
		'telefono'     => '(57) 311 549 5983',
		'correo'       => 'tienda@motosbullracing.com',
		'direccion'    => 'Cra. 84 # 78-58, Bogotá', // Pendiente: confirmar con el taller.
		'horario'      => 'Lunes a sábado · 8:00 a. m. – 6:00 p. m.', // Pendiente: confirmar con el taller.
		'clientes'     => 166,
		'calificacion' => 5,
		'desde'        => 0,  // Pendiente: año en que abrió el taller (lo da don Franz).
		'historia'     => '', // Pendiente: historia del taller contada por don Franz.
		'video'        => '', // Pendiente: enlace del video corto (TikTok o Instagram).
		'promedio'      => '',      // Pendiente: calificación real en Google (ej. '4,9').
		'url_calificar' => '',      // Pendiente: enlace para dejar reseña en Google.
		'resenas'       => array(), // Pendiente: hasta 3 reseñas REALES: array( 'texto', 'nombre', 'moto', 'fuente' => 'Google' | 'TikTok' ).
		'redes'        => array(
			// Tomados de la página actual del taller.
			'TikTok'    => 'https://www.tiktok.com/@motos.bull.racing',
			'Instagram' => 'https://www.instagram.com/bullracing_motos/',
			'Facebook'  => 'https://www.facebook.com/Bullracingmotos/',
		),
	);

	return apply_filters( 'bullracing_datos_taller', $datos );
}

/**
 * El número de WhatsApp que usa bullracing_whatsapp_url() (functions.php) sale de estos datos.
 */
add_filter(
	'bullracing_whatsapp_numero',
	static function (): string {
		return (string) bullracing_datos_taller()['whatsapp'];
	}
);

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
