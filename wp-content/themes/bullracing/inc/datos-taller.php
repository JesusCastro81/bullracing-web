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
		'direccion'    => 'Cra. 84 # 78-58, Bogotá', // Pendiente: confirmar con el taller.
		'horario'      => 'Lunes a sábado · 8:00 a. m. – 6:00 p. m.', // Pendiente: confirmar con el taller.
		'clientes'     => 166,
		'calificacion' => 5,
		'redes'        => array(
			// Pendiente: enlaces reales del taller.
			'Instagram' => '',
			'TikTok'    => '',
			'Facebook'  => '',
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
		return get_permalink( $pagina );
	}

	return home_url( '/' . $ancla_respaldo );
}
