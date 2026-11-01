<?php
/**
 * Datos del taller: fuente única de verdad del tema.
 *
 * Todo dato que se muestra en la página (contacto, horario, contador, valoración,
 * servicios, redes) se escribe solo aquí. Las plantillas lo leen con bullracing_datos_taller().
 * Ordenado como la página: 00 identidad → 01 hero → 05 valoración → 06 nosotros → 07 contacto.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

/**
 * Datos del taller.
 *
 * @return array<string, mixed>
 */
function bullracing_datos_taller(): array {
	$datos = array(

		// 00 · Identidad y contacto (header, footer, botón de WhatsApp).
		'nombre'   => 'Motos Bull Racing',
		'lema'     => 'Rueda libre, rueda seguro',
		'whatsapp' => '573115495983',
		'telefono' => '(57) 311 549 5983',
		'correo'   => 'tienda@motosbullracing.com',

		// 01 · Hero: horario y contador.
		// Horario que da el taller. Domingos y festivos: cerrado.
		// Solo se escriben las horas en formato 24 h; el texto ("7:00 a. m. – 7:00 p. m.") se arma solo.
		// 'semana': días en que aplica (1 = lunes … 6 = sábado). Lo usa también el aviso abierto/cerrado.
		// 'corto': como se ve en el aviso del hero.
		'horario'  => array(
			array(
				'dias'   => 'Lunes a viernes',
				'corto'  => 'L-V',
				'semana' => array( 1, 2, 3, 4, 5 ),
				'abre'   => 7,
				'cierra' => 19,
			),
			array(
				'dias'   => 'Sábado',
				'corto'  => 'SÁB',
				'semana' => array( 6 ),
				'abre'   => 8,
				'cierra' => 17,
			),
		),

		// Clientes satisfechos: base que da el taller.
		// El plugin bullracing-core suma las órdenes de servicio guardadas.
		'contador' => array(
			'base'  => 3759,
			'texto' => 'Clientes satisfechos',
		),

		// 05 · Valoración: datos reales del perfil de Google. Se actualizan a mano.
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

		// 06 · Nosotros y Quiénes somos.
		'desde'     => 0,  // Pendiente: año en que abrió el taller (lo da el taller).
		'historia'  => '', // Pendiente: historia del taller (la da el taller).
		'video'     => '', // Pendiente: video del taller (archivo .mp4/.webm o enlace de TikTok/Instagram).
		// Servicios del taller: los usan "Lo que hacemos" y las áreas del equipo. Fotos en assets/img/taller/.
		'servicios' => array(
			array(
				'nombre' => 'Mantenimiento',
				'texto'  => 'Combos de Básico a Pro para motos a gasolina y eléctricas, con precio según cilindraje o potencia.',
				'foto'   => 'quienes-mantenimiento.jpg',
			),
			array(
				'nombre' => 'Diagnóstico',
				'texto'  => 'Revisión de ingreso con orden de servicio: kilometraje, estado, luces y pastas antes de empezar.',
				'foto'   => 'quienes-diagnostico.jpg',
			),
			array(
				'nombre' => 'Motolavado',
				'texto'  => 'Cuatro paquetes, de Clean Ride a Cero KM, con precio según el cilindraje de tu moto.',
				'foto'   => 'quienes-motolavado.jpg',
			),
		),

		// 07 · Contacto.
		'direccion'     => 'Cra. 84 # 78-58, Bogotá',
		'por_confirmar' => array(), // Datos que muestran [CONFIRMAR] en la página de Contacto. Vaciar cuando el taller los confirme.
		// Tomados de la página actual del taller.
		'redes'         => array(
			'TikTok'    => 'https://www.tiktok.com/@motos.bull.racing',
			'Instagram' => 'https://www.instagram.com/bullracing_motos/',
			'Facebook'  => 'https://www.facebook.com/Bullracingmotos/',
		),
	);

	// Valores calculados: no se escriben a mano.

	// 1. Texto del horario a partir de las horas ("7:00 a. m. – 7:00 p. m.").
	foreach ( $datos['horario'] as $i => $fila ) {
		$datos['horario'][ $i ]['horas'] = bullracing_hora( $fila['abre'] ) . ' – ' . bullracing_hora( $fila['cierra'] );
	}

	// 2. Calle y ciudad por separado ("Cra. 84 # 78-58" y "Bogotá") y mapa de Google de la dirección.
	$partes          = array_map( 'trim', explode( ',', $datos['direccion'], 2 ) );
	$datos['calle']  = $partes[0];
	$datos['ciudad'] = $partes[1] ?? '';
	// Se busca por nombre + dirección para que Google marque el perfil del taller, no solo la calle.
	$datos['mapa'] = 'https://www.google.com/maps?q=' . rawurlencode( $datos['nombre'] . ', ' . $datos['direccion'] ) . '&z=16&output=embed';

	// 3. Estrellas enteras según la calificación de Google (4,2 → 4).
	$datos['calificacion'] = (int) round( (float) str_replace( ',', '.', $datos['promedio'] ) );

	// 4. Años del taller (0 mientras no se sepa el año de apertura).
	$datos['anios'] = $datos['desde'] ? (int) gmdate( 'Y' ) - (int) $datos['desde'] : 0;

	// 5. Clientes antes del plugin: la base. El plugin bullracing-core la reemplaza por base + OT.
	$datos['clientes'] = $datos['contador']['base'];

	$datos = apply_filters( 'bullracing_datos_taller', $datos );

	// 6. Formato visual (después del filtro, para que incluya lo que sume el plugin).
	$datos['clientes_fmt'] = number_format_i18n( (int) $datos['clientes'] );

	return $datos;
}
