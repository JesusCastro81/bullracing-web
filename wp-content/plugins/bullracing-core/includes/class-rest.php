<?php
/**
 * API REST: /wp-json/bullracing/v1/...
 *
 * @package BullRacingCore
 */

defined( 'ABSPATH' ) || exit;

final class BullRacing_Rest {

	public const NS = 'bullracing/v1';

	public static function registrar_rutas(): void {
		// Lectura pública de tarifas (las ve cualquier visitante de la página).
		register_rest_route(
			self::NS,
			'/tarifas',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( self::class, 'tarifas' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'servicio' => array(
						'type'              => 'string',
						'enum'              => array( 'mantenimiento', 'motolavado' ),
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);
	}

	public static function tarifas( WP_REST_Request $request ): WP_REST_Response {
		$servicio = (string) $request->get_param( 'servicio' );
		return new WP_REST_Response( BullRacing_Tarifas::listar( $servicio ), 200 );
	}
}
