<?php
/**
 * Componente: banda superior de las páginas internas (diseño 1).
 * Foto actual del sitio, ruta "Inicio / Página", etiqueta, título y subtítulo.
 * El header queda encima de la foto, igual que en el inicio.
 *
 * Uso: bullracing_componente( 'banner-pagina', array( 'ruta' => 'Contacto', 'etiqueta' => 'Contacto',
 *      'titulo' => 'Hablemos por', 'resaltado' => 'WhatsApp', 'subtitulo' => '...' ) );
 * Opcional: 'salto' => true pone el texto resaltado en otra línea; 'cifras' => array de
 * array( 'valor', 'etiqueta', 'destacado' => bool ) muestra el recuadro de cifras a la derecha.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'ruta'      => '',
		'etiqueta'  => '',
		'titulo'    => '',
		'resaltado' => '',
		'subtitulo' => '',
		'salto'     => false,
		'cifras'    => array(),
	)
);
?>
<section class="br-banner">
	<img class="br-banner__imagen"
		src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/hero.jpg' ); ?>"
		alt="" width="1920" height="1080">

	<div class="br-contenedor br-banner__fila">
	<div class="br-banner__contenido">
		<nav class="br-banner__ruta" aria-label="<?php esc_attr_e( 'Estás en', 'bullracing' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'bullracing' ); ?></a>
			<span aria-hidden="true">/</span>
			<span aria-current="page"><?php echo esc_html( $args['ruta'] ); ?></span>
		</nav>
		<?php if ( $args['etiqueta'] ) : ?>
			<p class="br-skin-etiqueta"><?php echo esc_html( $args['etiqueta'] ); ?></p>
		<?php endif; ?>
		<h1 class="br-banner__titulo">
			<?php echo esc_html( $args['titulo'] ); ?>
			<?php if ( $args['resaltado'] ) : ?>
				<?php echo $args['salto'] ? '<br>' : ''; ?>
				<span><?php echo esc_html( $args['resaltado'] ); ?></span>
			<?php endif; ?>
		</h1>
		<?php if ( $args['subtitulo'] ) : ?>
			<p class="br-banner__subtitulo"><?php echo esc_html( $args['subtitulo'] ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( $args['cifras'] ) : ?>
		<dl class="br-banner__cifras">
			<?php foreach ( $args['cifras'] as $cifra ) : ?>
				<div class="br-banner__cifra<?php echo empty( $cifra['destacado'] ) ? '' : ' is-destacada'; ?>">
					<dt><?php echo esc_html( $cifra['etiqueta'] ); ?></dt>
					<dd><?php echo esc_html( $cifra['valor'] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	<?php endif; ?>
	</div>
</section>
