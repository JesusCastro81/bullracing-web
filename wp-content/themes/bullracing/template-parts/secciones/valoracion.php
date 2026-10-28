<?php
/**
 * Sección "valoración" del inicio (diseño 1): calificación del taller
 * y tres reseñas de clientes.
 *
 * Las reseñas, lo ideal es que sean REALES (copiadas de Google o TikTok). Mientras no
 * estén en inc/datos-taller.php se muestran recuadros entre corchetes, para que nunca
 * salga una reseña o un nombre inventado.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller    = bullracing_datos_taller();
$promedio  = (string) $taller['promedio'];
$calificar = (string) $taller['url_calificar'];
$ver_todas = (string) ( $taller['url_resenas'] ?? '' );
$total     = (int) ( $taller['total_resenas'] ?? 0 );
// Estrellas proporcionales a la calificación real (4,2 = 84 %).
$numero    = (float) str_replace( ',', '.', $promedio );
$relleno   = $numero ? min( 100, max( 0, $numero / 5 * 100 ) ) : 100;

$resenas  = array_slice( (array) $taller['resenas'], 0, 3 );
$ejemplos = array( 'Google', 'TikTok', 'Google' ); // Mismo orden del diseño.
while ( count( $resenas ) < 3 ) {
	// Recuadro de ejemplo, como en el diseño: se ve claramente que falta el dato real.
	$fuente    = $ejemplos[ count( $resenas ) ];
	$resenas[] = array(
		'texto'  => 'TikTok' === $fuente ? __( '[Comentario real de TikTok]', 'bullracing' ) : __( '[Reseña real de Google]', 'bullracing' ),
		'nombre' => __( '[Nombre del cliente]', 'bullracing' ),
		'moto'   => __( '[Moto del cliente]', 'bullracing' ),
		'fuente' => $fuente,
	);
}
?>
<section class="br-seccion br-seccion--valoracion br-valoracion" id="valoracion">
	<div class="br-contenedor br-valoracion__grid">
		<div class="br-valoracion__nota">
			<p class="br-valoracion__etiqueta"><?php esc_html_e( 'Valoración de clientes', 'bullracing' ); ?></p>
			<p class="br-valoracion__promedio"><?php echo esc_html( $promedio ? $promedio : '[4,9]' ); ?></p>
			<p class="br-valoracion__estrellas" style="--relleno: <?php echo esc_attr( round( $relleno, 1 ) ); ?>%"
				role="img" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: calificación */ __( '%s de 5 estrellas', 'bullracing' ), $promedio ? $promedio : '5' ) ); ?>">★★★★★</p>
			<p class="br-valoracion__total">
				<?php
				echo esc_html(
					$total
						/* translators: %d: número de reseñas en Google */
						? sprintf( _n( '%d reseña en Google', '%d reseñas en Google', $total, 'bullracing' ), $total )
						/* translators: %d: número de motos atendidas */
						: sprintf( __( '%d motos atendidas · reseñas de Google', 'bullracing' ), (int) $taller['clientes'] )
				);
				?>
			</p>
			<?php if ( $calificar ) : ?>
				<a class="br-valoracion__calificar" href="<?php echo esc_url( $calificar ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Califica tu servicio →', 'bullracing' ); ?></a>
			<?php else : ?>
				<span class="br-valoracion__calificar"><?php esc_html_e( 'Califica tu servicio →', 'bullracing' ); ?></span>
			<?php endif; ?>
			<?php if ( $ver_todas ) : ?>
				<a class="br-valoracion__ver" href="<?php echo esc_url( $ver_todas ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Ver todas en Google →', 'bullracing' ); ?></a>
			<?php endif; ?>
		</div>

		<ul class="br-valoracion__resenas">
			<?php foreach ( $resenas as $resena ) : ?>
				<li class="br-resena">
					<p class="br-resena__cabecera">
						<span class="br-resena__estrellas" aria-hidden="true">★★★★★</span>
						<span class="br-resena__fuente"><?php echo esc_html( strtoupper( $resena['fuente'] ) ); ?></span>
					</p>
					<blockquote class="br-resena__texto">“<?php echo esc_html( $resena['texto'] ); ?>”</blockquote>
					<p class="br-resena__cliente">
						<?php echo esc_html( $resena['nombre'] ); ?>
						<?php if ( ! empty( $resena['moto'] ) ) : ?>
							<span><?php echo esc_html( $resena['moto'] ); ?></span>
						<?php endif; ?>
					</p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
