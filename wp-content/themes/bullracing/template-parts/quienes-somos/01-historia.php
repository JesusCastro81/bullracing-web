<?php
/**
 * Quiénes somos · 01 Historia y video del taller.
 *
 * - Historia, año de apertura y video: los entrega el taller (inc/datos-taller.php).
 *   Mientras falten, se muestran los corchetes del diseño.
 * - Video: si 'video' es un archivo .mp4/.webm, se reproduce aquí mismo con play/pausa,
 *   la etiqueta "Reproduciendo" y la barra de progreso. Si es un enlace
 *   de TikTok o Instagram, el recuadro abre el video en su red.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller   = bullracing_datos_taller();
$ano_txt  = $taller['desde'] ? (string) $taller['desde'] : '[AÑO]';
$video    = (string) $taller['video'];
$es_mp4   = (bool) preg_match( '/\.(mp4|webm)(\?.*)?$/i', $video );
$historia = (string) $taller['historia'];
?>
<section class="br-contenedor br-historia">
	<div class="br-historia__texto">
		<p class="br-skin-etiqueta"><?php esc_html_e( 'Nuestra historia', 'bullracing' ); ?></p>
		<h2 class="br-historia__titulo">
			<?php echo esc_html( sprintf( __( 'Desde %s', 'bullracing' ), $ano_txt ) ); ?><br>
			<?php esc_html_e( 'en', 'bullracing' ); ?> <span><?php echo esc_html( $taller['ciudad'] ); ?></span>
		</h2>
		<?php if ( $historia ) : ?>
			<?php echo wp_kses_post( wpautop( $historia ) ); ?>
		<?php else : ?>
			<p class="br-historia__parrafo"><?php esc_html_e( '[Historia del taller: cómo empezó, desde qué año y qué lo hace distinto.]', 'bullracing' ); ?></p>
			<p class="br-historia__parrafo br-historia__parrafo--suave"><?php esc_html_e( '[Cómo pasó de un taller de barrio a un centro técnico con mantenimiento, diagnóstico y motolavado. Qué tipo de motos atienden y qué lo hace volver a sus clientes.]', 'bullracing' ); ?></p>
		<?php endif; ?>
		<p class="br-historia__datos">
			<span><?php echo esc_html( 'DESDE ' . $ano_txt ); ?></span>
			<span><?php echo esc_html( mb_strtoupper( $taller['ciudad'] ) ); ?></span>
			<span><?php esc_html_e( 'GASOLINA Y ELÉCTRICAS', 'bullracing' ); ?></span>
		</p>
	</div>

	<?php if ( $es_mp4 ) : ?>
		<?php bullracing_componente( 'video-reproductor', array( 'url' => $video ) ); ?>
	<?php else : ?>
		<?php
		$etiqueta  = $video ? 'a' : 'div';
		$atributos = $video ? ' href="' . esc_url( $video ) . '" target="_blank" rel="noopener"' : '';
		?>
		<<?php echo $etiqueta . $atributos; // phpcs:ignore WordPress.Security.EscapeOutput -- valores fijos y URL escapada. ?> class="br-video br-video--pendiente">
			<img class="br-video__fondo" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/hero.jpg' ); ?>" alt="" loading="lazy">
			<span class="br-video__etiqueta"><?php echo $video ? esc_html__( 'VIDEO', 'bullracing' ) : esc_html__( 'VIDEO PENDIENTE', 'bullracing' ); ?></span>
			<span class="br-video__centro">
				<span class="br-video__boton" aria-hidden="true"><span class="br-video__icono"></span></span>
				<?php echo $video ? esc_html__( 'Ver video del taller', 'bullracing' ) : esc_html__( 'Video del taller', 'bullracing' ); ?>
			</span>
		</<?php echo $etiqueta; // phpcs:ignore WordPress.Security.EscapeOutput -- 'a' o 'div'. ?>>
	<?php endif; ?>
</section>
