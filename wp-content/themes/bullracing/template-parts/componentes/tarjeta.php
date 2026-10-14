<?php
/**
 * Componente: tarjeta (servicios, planes, valores).
 *
 * Uso: bullracing_componente( 'tarjeta', array( 'titulo' => 'Mantenimiento', 'texto' => '...', 'url' => '...' ) );
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'titulo'    => '',
		'texto'     => '',
		'etiqueta'  => '',
		'precio'    => null,
		'url'       => '',
		'destacada' => false,
	)
);

$clases = 'br-tarjeta' . ( $args['destacada'] ? ' br-tarjeta--destacada' : '' );
?>
<article class="<?php echo esc_attr( $clases ); ?>">
	<?php if ( $args['etiqueta'] ) : ?>
		<span class="br-tarjeta__etiqueta"><?php echo esc_html( $args['etiqueta'] ); ?></span>
	<?php endif; ?>

	<h3 class="br-tarjeta__titulo"><?php echo esc_html( $args['titulo'] ); ?></h3>

	<?php if ( $args['texto'] ) : ?>
		<p class="br-tarjeta__texto"><?php echo esc_html( $args['texto'] ); ?></p>
	<?php endif; ?>

	<?php if ( null !== $args['precio'] ) : ?>
		<p class="br-tarjeta__precio">$ <?php echo esc_html( number_format_i18n( (int) $args['precio'] ) ); ?></p>
	<?php endif; ?>

	<?php if ( $args['url'] ) : ?>
		<a class="br-tarjeta__enlace" href="<?php echo esc_url( $args['url'] ); ?>"><?php esc_html_e( 'Ver más', 'bullracing' ); ?></a>
	<?php endif; ?>
</article>
