<?php
/**
 * Rol "Taller": usuarios del taller que solo administran precios (y más adelante agendamientos).
 *
 * @package BullRacingCore
 */

defined( 'ABSPATH' ) || exit;

final class BullRacing_Rol_Taller {

	public const ROL     = 'bullracing_taller';
	public const PERMISO = 'bullracing_gestionar_tarifas';

	public static function registrar(): void {
		add_role(
			self::ROL,
			__( 'Taller', 'bullracing-core' ),
			array(
				'read'        => true,
				self::PERMISO => true,
			)
		);

		// El administrador también puede gestionar tarifas.
		$admin = get_role( 'administrator' );
		if ( $admin ) {
			$admin->add_cap( self::PERMISO );
		}
	}

	public static function eliminar(): void {
		remove_role( self::ROL );

		$admin = get_role( 'administrator' );
		if ( $admin ) {
			$admin->remove_cap( self::PERMISO );
		}
	}
}
