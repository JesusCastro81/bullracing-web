<?php
/**
 * Sección "Quiénes somos" del inicio (diseño 1, igual al PDF).
 *
 * Video: si es un archivo .mp4/.webm se reproduce aquí mismo al hacer clic (componente
 * video-reproductor); si es un enlace de TikTok o Instagram, abre el video en su red.
 *
 * La historia, el año de apertura y el video los entrega don Franz. Mientras estén
 * vacíos en inc/datos-taller.php, se muestran los textos entre corchetes del diseño
 * para que se note que falta el dato real.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller = bullracing_datos_taller();
$desde  = (int) $taller['desde'];
$anios  = $desde ? (int) gmdate( 'Y' ) - $desde : 0;
$video  = (string) $taller['video'];
?>
<section class="br-seccion br-seccion--nosotros br-nosotros" id="nosotros">
	<div class="br-contenedor br-nosotros__grid">
		<?php
		if ( preg_match( '/\.(mp4|webm)(\?.*)?$/i', $video ) ) :
			// Archivo de video: se reproduce aquí mismo al hacer clic (propuesta).
			bullracing_componente( 'video-reproductor', array( 'url' => $video, 'clase' => 'br-nosotros__video' ) );
		else :
			// Enlace de TikTok/Instagram: el recuadro abre el video en su red. Sin video: recuadro del diseño.
			$etiqueta  = $video ? 'a' : 'div';
			$atributos = $video ? ' href="' . esc_url( $video ) . '" target="_blank" rel="noopener"' : '';
			?>
			<<?php echo $etiqueta . $atributos; // phpcs:ignore WordPress.Security.EscapeOutput -- valores fijos y URL escapada arriba. ?> class="br-nosotros__video">
				<span class="br-nosotros__play" aria-hidden="true">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"></path></svg>
				</span>
				<span>
					<?php
					echo $video
						? esc_html__( 'Ver video del taller', 'bullracing' )
						: esc_html__( 'Video del taller', 'bullracing' );
					?>
				</span>
			</<?php echo $etiqueta; // phpcs:ignore WordPress.Security.EscapeOutput -- 'a' o 'div'. ?>>
		<?php endif; ?>

		<div class="br-nosotros__texto">
			<p class="br-skin-etiqueta"><?php esc_html_e( 'Quiénes somos', 'bullracing' ); ?></p>
			<h2 class="br-nosotros__titulo">
				<?php
				echo esc_html(
					$anios
						/* translators: %d: años del taller */
						? sprintf( _n( '%d año', '%d años', $anios, 'bullracing' ), $anios )
						: __( '[N] años', 'bullracing' )
				);
				?>
				<br><?php esc_html_e( 'con las manos', 'bullracing' ); ?><br>
				<span><?php esc_html_e( 'en el motor', 'bullracing' ); ?></span>
			</h2>
			<p class="br-nosotros__historia">
				<?php
				echo esc_html(
					$taller['historia']
						? $taller['historia']
						: __( '[Historia del taller: cómo empezó, desde qué año y qué lo hace distinto.]', 'bullracing' )
				);
				?>
			</p>
			<p class="br-nosotros__datos">
				<span><?php echo esc_html( $desde ? sprintf( 'DESDE %d', $desde ) : 'DESDE [AÑO]' ); ?></span>
				<span><?php esc_html_e( 'BOGOTÁ', 'bullracing' ); ?></span>
				<span><?php esc_html_e( 'GASOLINA Y ELÉCTRICAS', 'bullracing' ); ?></span>
			</p>
		</div>
	</div>
</section>
