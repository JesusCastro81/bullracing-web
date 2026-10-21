<?php
/**
 * Qué incluye cada plan de mantenimiento, tal cual aparece hoy en la página del taller.
 * Los PRECIOS no van aquí: salen de la tabla de tarifas de bullracing-core.
 * Un solo lugar para cambiar los textos (filtro 'bullracing_datos_planes').
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, array<string, mixed>> Planes por nombre (igual que en la tabla de tarifas).
 */
function bullracing_datos_planes(): array {
	$planes = array(
		'Básico'     => array(
			'recomendado' => false,
			'incluye'     => array(
				'gasolina'  => array( 'Cambio de aceite', 'Mantenimiento de guayas', 'Mantenimiento kit arrastre', 'Mantenimiento frenos' ),
				'electrica' => array( 'Calibración frenos', 'Control velocidad', 'Ajuste luces/tornillería' ),
			),
		),
		'Viaje'      => array(
			'recomendado' => false,
			'incluye'     => array(
				'gasolina'  => array( 'Todo lo de Básico', 'Sincronización', 'Calibración llantas/luces' ),
				'electrica' => array(), // No aplica para motos eléctricas.
			),
		),
		'Preventivo' => array(
			'recomendado' => true,
			'incluye'     => array(
				'gasolina'  => array( 'Todo lo de Viaje', 'Mantenimiento cunas', 'Suspensión delantera', 'Rodamientos (D/T)' ),
				'electrica' => array( 'Todo lo Básico', 'Suspensión y Cunas', 'Rodamientos y Transmisión' ),
			),
		),
		'General'    => array(
			'recomendado' => false,
			'incluye'     => array(
				'gasolina'  => array( 'Todo lo Preventivo', 'Mantenimiento tijera', 'Ejes y refrigerante', 'Lavada' ),
				'electrica' => array( 'Todo lo Preventivo', 'Batería, BMS y Sensores', 'Sellado conectores' ),
			),
		),
		'Pro'        => array(
			'recomendado' => false,
			'incluye'     => array(
				'gasolina'  => array( 'Todo lo General', 'Ramal eléctrico', 'Limpieza chasis', 'Detallado Premium' ),
				'electrica' => array( 'Todo lo General', 'Ajuste Motor y Chasis', 'Prueba técnica integral' ),
			),
		),
	);

	return apply_filters( 'bullracing_datos_planes', $planes );
}

/**
 * Precio "desde" de cada plan o paquete (el menor según cilindraje o potencia), por tipo de motor.
 * Lee la tabla de bullracing-core; si el plugin no está activo devuelve un arreglo vacío.
 *
 * @param string $servicio 'mantenimiento' o 'motolavado' (igual que en la tabla de tarifas).
 * @return array<string, array<string, int>> [ motor => [ plan => precio ] ]
 */
function bullracing_precios_desde( string $servicio = 'mantenimiento' ): array {
	if ( ! class_exists( 'BullRacing_Tarifas' ) ) {
		return array();
	}

	$desde = array();
	foreach ( BullRacing_Tarifas::listar( $servicio ) as $tarifa ) {
		$motor  = (string) $tarifa['tipo_motor'];
		$plan   = (string) $tarifa['plan'];
		$precio = (int) $tarifa['precio'];

		if ( ! isset( $desde[ $motor ][ $plan ] ) || $precio < $desde[ $motor ][ $plan ] ) {
			$desde[ $motor ][ $plan ] = $precio;
		}
	}

	return $desde;
}

/**
 * Precio de cada referencia (cilindraje o potencia) por plan y tipo de motor,
 * en el mismo orden de la tabla de tarifas. Lo usan los desplegables de la sección de planes.
 * Si el plugin no está activo devuelve un arreglo vacío.
 *
 * @param string $servicio 'mantenimiento' o 'motolavado' (igual que en la tabla de tarifas).
 * @return array<string, array<string, array<int, array{referencia: string, precio: int}>>> [ motor => [ plan => filas ] ]
 */
function bullracing_precios_por_referencia( string $servicio = 'mantenimiento' ): array {
	if ( ! class_exists( 'BullRacing_Tarifas' ) ) {
		return array();
	}

	$precios = array();
	foreach ( BullRacing_Tarifas::listar( $servicio ) as $tarifa ) {
		$precios[ (string) $tarifa['tipo_motor'] ][ (string) $tarifa['plan'] ][] = array(
			'referencia' => (string) $tarifa['referencia'],
			'precio'     => (int) $tarifa['precio'],
		);
	}

	return $precios;
}
