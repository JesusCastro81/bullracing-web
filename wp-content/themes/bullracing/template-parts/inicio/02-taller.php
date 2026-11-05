<?php
/**
 * Inicio · 02 El taller: cómo entra la moto (diseño 1). Explica el proceso; no toca la orden de servicio (OT).
 * Fotos: paso-01.jpg … paso-04.jpg en assets/img/taller/. Si falta una, la tarjeta queda solo con texto.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$pasos = array(
	array(
		'titulo' => __( 'Agendas en línea', 'bullracing' ),
		'texto'  => __( 'Eliges plan, cilindraje y hora con cupo disponible.', 'bullracing' ),
	),
	array(
		'titulo' => __( 'Revisión de ingreso', 'bullracing' ),
		'texto'  => __( 'Llenamos la orden de servicio: kilometraje, estado, luces y pastas.', 'bullracing' ),
	),
	array(
		'titulo' => __( 'Trabajo en rampa', 'bullracing' ),
		'texto'  => __( 'Técnicos especializados hacen el plan que elegiste, sin sorpresas.', 'bullracing' ),
	),
	array(
		'titulo' => __( 'Entrega con soporte', 'bullracing' ),
		'texto'  => __( 'Te entregamos la moto con la copia de su orden de servicio.', 'bullracing' ),
	),
);
?>
<section class="br-seccion br-seccion--taller br-taller" id="taller">
	<div class="br-contenedor">
		<div class="br-taller__cabecera">
			<h2 class="br-taller__titulo">
				<?php esc_html_e( 'Así entra', 'bullracing' ); ?><br>
				<span><?php esc_html_e( 'tu moto', 'bullracing' ); ?></span>
			</h2>
			<p class="br-taller__intro">
				<?php esc_html_e( 'Cada moto que entra al taller tiene su orden de servicio. Sabes qué se le hizo, cuánto costó y cuándo te la entregamos.', 'bullracing' ); ?>
			</p>
		</div>

		<ol class="br-taller__pasos">
			<?php foreach ( $pasos as $i => $paso ) : ?>
				<?php
				$numero = sprintf( '%02d', $i + 1 );
				$foto   = bullracing_foto( 'paso-' . $numero . '.jpg' );
				?>
				<li class="br-taller__paso">
					<?php if ( $foto ) : ?>
						<div class="br-taller__paso-fondo" style="--fondo: url('<?php echo esc_url( $foto ); ?>');"></div>
					<?php endif; ?>
					<div class="br-taller__paso-contenido">
						<span class="br-taller__numero" aria-hidden="true"><?php echo esc_html( $numero ); ?></span>
						<h3 class="br-taller__paso-titulo"><?php echo esc_html( $paso['titulo'] ); ?></h3>
						<p class="br-taller__paso-texto"><?php echo esc_html( $paso['texto'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
