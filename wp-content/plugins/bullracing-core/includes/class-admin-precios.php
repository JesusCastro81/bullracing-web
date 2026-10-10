<?php
/**
 * Pantalla "Bull Racing → Precios" dentro del escritorio de WordPress.
 * Pendiente: formulario de edición (con nonce y validación) según lo que apruebe el taller.
 *
 * @package BullRacingCore
 */

defined( 'ABSPATH' ) || exit;

final class BullRacing_Admin_Precios {

	public static function registrar_menu(): void {
		add_menu_page(
			__( 'Bull Racing · Precios', 'bullracing-core' ),
			__( 'Bull Racing', 'bullracing-core' ),
			BullRacing_Rol_Taller::PERMISO,
			'bullracing-precios',
			array( self::class, 'pantalla' ),
			'dashicons-tag',
			3
		);
	}

	public static function pantalla(): void {
		if ( ! current_user_can( BullRacing_Rol_Taller::PERMISO ) ) {
			wp_die( esc_html__( 'No tienes permiso para ver esta página.', 'bullracing-core' ) );
		}

		$tarifas = BullRacing_Tarifas::listar();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Precios', 'bullracing-core' ); ?></h1>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Servicio', 'bullracing-core' ); ?></th>
						<th><?php esc_html_e( 'Motor', 'bullracing-core' ); ?></th>
						<th><?php esc_html_e( 'Plan', 'bullracing-core' ); ?></th>
						<th><?php esc_html_e( 'Referencia', 'bullracing-core' ); ?></th>
						<th><?php esc_html_e( 'Precio', 'bullracing-core' ); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ( $tarifas as $t ) : ?>
					<tr>
						<td><?php echo esc_html( $t['servicio'] ); ?></td>
						<td><?php echo esc_html( $t['tipo_motor'] ); ?></td>
						<td><?php echo esc_html( $t['plan'] ); ?></td>
						<td><?php echo esc_html( $t['referencia'] ); ?></td>
						<td>$ <?php echo esc_html( number_format_i18n( (int) $t['precio'] ) ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
