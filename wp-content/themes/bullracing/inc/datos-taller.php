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
		'direccion'    => 'Cra. 84 # 78-58, Bogotá', // Igual al perfil de Google del taller (Engativá).
		// Horario igual al perfil de Google del taller (consultado el 20-oct-2026). Domingos y festivos: cerrado.
		// 'semana': días en que aplica (1 = lunes … 6 = sábado). 'abre' y 'cierra': hora en formato 24 h (lo usa el aviso abierto/cerrado).
		'horario'      => array(
			array( 'dias' => 'Lunes a viernes', 'horas' => '7:00 a. m. – 6:00 p. m.', 'semana' => array( 1, 2, 3, 4, 5 ), 'abre' => 7, 'cierra' => 18 ),
			array( 'dias' => 'Sábado', 'horas' => '7:00 a. m. – 5:00 p. m.', 'semana' => array( 6 ), 'abre' => 7, 'cierra' => 17 ),
		),
		'por_confirmar' => array(), // Datos que muestran [CONFIRMAR] en la página de Contacto. Vaciar cuando el taller los confirme.
		'clientes'     => 166,
		'calificacion' => 5,
		'desde'        => 0,  // Pendiente: año en que abrió el taller (lo da don Franz).
		'historia'     => '', // Pendiente: historia del taller contada por don Franz.
		'video'        => '', // Pendiente: enlace del video corto (TikTok o Instagram).
		// Valoración REAL del perfil de Google Maps del taller (consultado el 20-oct-2026). Actualizar a mano.
		'promedio'      => '4,2',
		'total_resenas' => 29,
		'url_calificar' => 'https://search.google.com/local/writereview?placeid=ChIJxUc6jg2bP44RoRKKHa7fvko',
		'url_resenas'   => 'https://search.google.com/local/reviews?placeid=ChIJxUc6jg2bP44RoRKKHa7fvko',
		// 3 reseñas reales de 5 estrellas, con el texto exacto de Google. Nombre + inicial para cuidar los datos de los clientes.
		'resenas'       => array(
			array(
				'texto'  => 'Recomiendo el trabajo de motos bull racing, buen servicio, mantenimiento seguros, pruebas de satisfacción, cumplimiento con el horario. Y muy buen atención del servicio.',
				'nombre' => 'Sergio C.',
				'moto'   => '',
				'fuente' => 'Google',
			),
			array(
				'texto'  => 'Excelente atención, recibí asesoramiento completo y claro. Resalto sobre todo puntualidad en la entrega del servicio.',
				'nombre' => 'Juan R.',
				'moto'   => '',
				'fuente' => 'Google',
			),
			array(
				'texto'  => 'Honestos con el trabajo y cuando hacen las cosas le van mostrando el proceso.',
				'nombre' => 'Thiago R.',
				'moto'   => '',
				'fuente' => 'Google',
			),
		),
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
