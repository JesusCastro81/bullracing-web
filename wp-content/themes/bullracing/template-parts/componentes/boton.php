<?php
/**
 * Componente: botón.
 *
 * Uso: bullracing_componente( 'boton', array( 'texto' => 'Agendar', 'url' => '...', 'variante' => 'primario' ) );
 *
 * Variantes: primario | contorno | whatsapp
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'texto'         => '',
		'url'           => '#',
		'variante'      => 'primario',
		'nueva_pestana' => false,
		'clase'         => '',
	)
);

$variantes = array( 'primario', 'contorno', 'whatsapp' );
$variante  = in_array( $args['variante'], $variantes, true ) ? $args['variante'] : 'primario';
$clases    = trim( 'br-boton br-boton--' . $variante . ' ' . $args['clase'] );
?>
<a class="<?php echo esc_attr( $clases ); ?>"
	href="<?php echo esc_url( $args['url'] ); ?>"
	<?php if ( $args['nueva_pestana'] ) : ?>target="_blank" rel="noopener"<?php endif; ?>>
	<?php echo esc_html( $args['texto'] ); ?>
</a>
