<?php
/**
 * Contacto · 02 Datos: dirección, teléfono y horario con el aviso abierto/cerrado.
 * Dirección y horario muestran [CONFIRMAR] mientras el taller no los confirme
 * (lista 'por_confirmar' en inc/datos-taller.php).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller        = bullracing_datos_taller();
$por_confirmar = (array) ( $taller['por_confirmar'] ?? array() );

/** Etiqueta amarilla [CONFIRMAR] para datos que el taller aún no confirma. */
$confirmar = static function ( string $dato ) use ( $por_confirmar ): void {
	if ( in_array( $dato, $por_confirmar, true ) ) {
		echo '<span class="br-dato__confirmar">' . esc_html__( '[CONFIRMAR]', 'bullracing' ) . '</span>';
	}
};
?>
<div class="br-pcontacto__datos">
	<div class="br-dato">
		<p class="br-dato__cabecera"><span><?php esc_html_e( 'Dirección', 'bullracing' ); ?></span><?php $confirmar( 'direccion' ); ?></p>
		<p class="br-dato__valor"><?php echo esc_html( $taller['calle'] ); ?></p>
		<p class="br-dato__nota"><?php echo esc_html( $taller['ciudad'] . ', Colombia' ); ?></p>
	</div>
	<div class="br-dato">
		<p class="br-dato__cabecera"><span><?php esc_html_e( 'Teléfono', 'bullracing' ); ?></span></p>
		<p class="br-dato__valor"><?php echo esc_html( $taller['telefono'] ); ?></p>
		<p class="br-dato__nota"><?php esc_html_e( 'Línea nacional · también es nuestro WhatsApp', 'bullracing' ); ?></p>
	</div>
	<div class="br-dato">
		<p class="br-dato__cabecera"><span><?php esc_html_e( 'Horario', 'bullracing' ); ?></span><?php $confirmar( 'horario' ); ?></p>
		<?php foreach ( (array) $taller['horario'] as $fila ) : ?>
			<p class="br-dato__valor"><?php echo esc_html( $fila['dias'] ); ?></p>
			<p class="br-dato__nota"><?php echo esc_html( $fila['horas'] ); ?></p>
		<?php endforeach; ?>
		<?php /* Mismo aviso del hero: verde si está abierto ahora, rojo si está cerrado (lo calcula main.js). */ ?>
		<p class="br-estado" data-br-estado>
			<span class="br-estado__punto" aria-hidden="true"></span>
			<span data-br-estado-texto
				data-abierto="<?php esc_attr_e( 'Abierto ahora', 'bullracing' ); ?>"
				data-cerrado="<?php esc_attr_e( 'Cerrado ahora', 'bullracing' ); ?>"><?php esc_html_e( 'Abierto ahora', 'bullracing' ); ?></span>
		</p>
	</div>
</div>
