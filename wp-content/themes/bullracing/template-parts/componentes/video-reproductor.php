<?php
/**
 * Componente: reproductor del video del taller (archivo .mp4 o .webm).
 *
 * Se reproduce ahí mismo al hacer clic: el ícono cambia a pausa, aparece la etiqueta
 * "Reproduciendo" y avanza la barra de progreso (assets/js/main.js, bloque "video del taller").
 * Lo usan la sección Quiénes somos del inicio y la página Quiénes somos.
 *
 * Uso: bullracing_componente( 'video-reproductor', array( 'url' => '...mp4', 'clase' => 'br-nosotros__video' ) );
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args ?? array(), array( 'url' => '', 'clase' => '' ) );
?>
<div class="br-video <?php echo esc_attr( $args['clase'] ); ?>" data-br-video>
	<video class="br-video__archivo" src="<?php echo esc_url( $args['url'] ); ?>" preload="metadata" playsinline
		poster="<?php echo esc_url( get_template_directory_uri() . '/assets/img/hero.jpg' ); ?>"></video>
	<span class="br-video__etiqueta" data-br-video-etiqueta data-reproduciendo="<?php esc_attr_e( '● REPRODUCIENDO', 'bullracing' ); ?>"><?php esc_html_e( 'VIDEO', 'bullracing' ); ?></span>
	<button type="button" class="br-video__boton" data-br-video-boton
		aria-label="<?php esc_attr_e( 'Reproducir video del taller', 'bullracing' ); ?>"
		data-pausar="<?php esc_attr_e( 'Pausar video del taller', 'bullracing' ); ?>">
		<span class="br-video__icono" aria-hidden="true"></span>
	</button>
	<span class="br-video__progreso" aria-hidden="true"><span data-br-video-barra></span></span>
</div>
