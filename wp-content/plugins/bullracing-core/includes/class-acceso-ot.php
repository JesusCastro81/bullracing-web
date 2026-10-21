<?php
/**
 * Acceso a la Orden de Servicio (OT).
 *
 * - Rol "Recepción de motos": personas autorizadas por el taller para recibir motos y llenar la OT.
 *   Ese rol SOLO sirve para la OT: no puede tocar la página ni los precios.
 * - Permiso "usar la OT": lo tienen ese rol y el administrador.
 * - Registro de recepción: quién guardó cada orden y a qué hora (trazabilidad).
 *   Se guarda en una tabla aparte; las tablas de la OT no se modifican.
 *
 * @package BullRacingCore
 */

defined( 'ABSPATH' ) || exit;

final class BullRacing_Acceso_OT {

	public const ROL     = 'bullracing_recepcion';
	public const PERMISO = 'bullracing_usar_ot';
	private const VERSION = '1'; // Subir si cambian el rol o la tabla.

	public static function tabla(): string {
		global $wpdb;
		return $wpdb->prefix . 'bullracing_ot_recepciones';
	}

	/** Dirección de la OT (carpeta ot/ en la raíz del sitio). */
	public static function url_ot(): string {
		return home_url( '/ot/' );
	}

	/** Crea el rol, da el permiso al administrador y crea la tabla del registro. Seguro de repetir. */
	public static function instalar(): void {
		add_role( self::ROL, __( 'Recepción de motos', 'bullracing-core' ), array( 'read' => true, self::PERMISO => true ) );

		$admin = get_role( 'administrator' );
		if ( $admin ) {
			$admin->add_cap( self::PERMISO );
		}

		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta(
			'CREATE TABLE ' . self::tabla() . " (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				numero_orden varchar(20) NOT NULL,
				placa varchar(15) NOT NULL DEFAULT '',
				usuario_id bigint(20) unsigned NOT NULL,
				usuario_nombre varchar(250) NOT NULL,
				recibido_en datetime NOT NULL,
				PRIMARY KEY  (id),
				KEY numero_orden (numero_orden)
			) " . $wpdb->get_charset_collate() . ';'
		);

		update_option( 'bullracing_acceso_ot_version', self::VERSION );
	}

	/** Si el plugin ya estaba activo cuando se agregó esto, lo instala una sola vez. */
	public static function actualizar_si_hace_falta(): void {
		if ( get_option( 'bullracing_acceso_ot_version' ) !== self::VERSION ) {
			self::instalar();
		}
	}

	/** Anota quién guardó la orden: el usuario conectado, no lo que se escriba en el formulario. */
	public static function registrar_recepcion( string $numero_orden, string $placa ): void {
		global $wpdb;
		$usuario = wp_get_current_user();
		$wpdb->insert(
			self::tabla(),
			array(
				'numero_orden'   => $numero_orden,
				'placa'          => $placa,
				'usuario_id'     => $usuario->ID,
				'usuario_nombre' => $usuario->display_name,
				'recibido_en'    => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%d', '%s', '%s' )
		);
	}

	/** ¿El usuario es solo de recepción (no administrador)? */
	private static function es_solo_recepcion( ?WP_User $usuario = null ): bool {
		$usuario = $usuario ?? wp_get_current_user();
		return in_array( self::ROL, (array) $usuario->roles, true ) && ! user_can( $usuario, 'manage_options' );
	}

	/** El usuario de recepción solo usa la OT: en el escritorio de WordPress solo puede abrir su Perfil (para la contraseña). */
	public static function limitar_escritorio(): void {
		if ( wp_doing_ajax() || ! self::es_solo_recepcion() ) {
			return;
		}
		global $pagenow;
		if ( 'profile.php' !== $pagenow ) {
			wp_safe_redirect( self::url_ot() );
			exit;
		}
	}

	/** Al entrar, el usuario de recepción va directo a la OT. */
	public static function redirigir_al_entrar( string $destino, string $pedido, $usuario ): string {
		if ( $usuario instanceof WP_User && self::es_solo_recepcion( $usuario ) && ( '' === $pedido || str_contains( $pedido, 'wp-admin' ) ) ) {
			return self::url_ot();
		}
		return $destino;
	}

	/** Sin barra de WordPress en la página para el usuario de recepción. */
	public static function ocultar_barra( bool $mostrar ): bool {
		return self::es_solo_recepcion() ? false : $mostrar;
	}
}
