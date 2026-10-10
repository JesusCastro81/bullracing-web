<?php
/**
 * Acceso a la tabla de tarifas.
 *
 * @package BullRacingCore
 */

defined( 'ABSPATH' ) || exit;

final class BullRacing_Tarifas {

	/**
	 * Nombre de la tabla con el prefijo de WordPress (ej. wpbu_bullracing_tarifas).
	 */
	public static function tabla(): string {
		global $wpdb;
		return $wpdb->prefix . 'bullracing_tarifas';
	}

	/**
	 * Crea o actualiza la tabla con dbDelta (seguro de ejecutar varias veces).
	 */
	public static function crear_tabla(): void {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$tabla   = self::tabla();
		$charset = $wpdb->get_charset_collate();

		dbDelta(
			"CREATE TABLE {$tabla} (
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
				servicio VARCHAR(20) NOT NULL,
				tipo_motor VARCHAR(20) NOT NULL,
				plan VARCHAR(40) NOT NULL,
				referencia VARCHAR(60) NOT NULL,
				precio INT UNSIGNED NOT NULL,
				activo TINYINT(1) NOT NULL DEFAULT 1,
				actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY  (id),
				UNIQUE KEY tarifa_unica (servicio, tipo_motor, plan, referencia)
			) {$charset};"
		);
	}

	/**
	 * Llena la tabla con las tarifas 2026 solo si está vacía.
	 */
	public static function sembrar_si_vacia(): void {
		global $wpdb;
		$tabla = self::tabla();

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- nombre de tabla interno.
		if ( (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$tabla}" ) > 0 ) {
			return;
		}

		$tarifas = require BULLRACING_CORE_DIR . 'includes/datos/tarifas-2026.php';

		foreach ( $tarifas as [ $servicio, $motor, $plan, $referencia, $precio ] ) {
			$wpdb->insert(
				$tabla,
				array(
					'servicio'   => $servicio,
					'tipo_motor' => $motor,
					'plan'       => $plan,
					'referencia' => $referencia,
					'precio'     => $precio,
				),
				array( '%s', '%s', '%s', '%s', '%d' )
			);
		}
	}

	/**
	 * Tarifas activas, opcionalmente filtradas por servicio.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function listar( string $servicio = '' ): array {
		global $wpdb;
		$tabla = self::tabla();

		if ( '' === $servicio ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			return $wpdb->get_results( "SELECT servicio, tipo_motor, plan, referencia, precio FROM {$tabla} WHERE activo = 1 ORDER BY id", ARRAY_A );
		}

		return $wpdb->get_results(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				"SELECT servicio, tipo_motor, plan, referencia, precio FROM {$tabla} WHERE activo = 1 AND servicio = %s ORDER BY id",
				$servicio
			),
			ARRAY_A
		);
	}

	/**
	 * Precio de una combinación. Lo usa el servidor para NO confiar en el precio del navegador.
	 */
	public static function precio( string $servicio, string $motor, string $plan, string $referencia ): ?int {
		global $wpdb;
		$tabla = self::tabla();

		$precio = $wpdb->get_var(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				"SELECT precio FROM {$tabla} WHERE activo = 1 AND servicio = %s AND tipo_motor = %s AND plan = %s AND referencia = %s",
				$servicio,
				$motor,
				$plan,
				$referencia
			)
		);

		return null === $precio ? null : (int) $precio;
	}
}
