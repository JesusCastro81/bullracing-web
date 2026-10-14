<?php
/**
 * Componente: calificación con estrellas.
 *
 * Uso: bullracing_componente( 'estrellas', array( 'valor' => 5, 'total' => 166 ) );
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'valor' => 5,
		'total' => 0,
		'texto' => __( 'clientes satisfechos', 'bullracing' ),
	)
);

$valor = max( 0, min( 5, (int) $args['valor'] ) );
?>
<div class="br-estrellas">
	<span class="br-estrellas__iconos" aria-hidden="true">
		<?php echo esc_html( str_repeat( '★', $valor ) . str_repeat( '☆', 5 - $valor ) ); ?>
	</span>
	<span class="br-estrellas__texto">
		<?php if ( $args['total'] ) : ?>
			<strong><?php echo esc_html( number_format_i18n( (int) $args['total'] ) ); ?></strong>
		<?php endif; ?>
		<?php echo esc_html( $args['texto'] ); ?>
	</span>
	<span class="screen-reader-text">
		<?php
		/* translators: %d: calificación de 0 a 5 */
		echo esc_html( sprintf( __( 'Calificación: %d de 5', 'bullracing' ), $valor ) );
		?>
	</span>
</div>
