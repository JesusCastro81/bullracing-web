<?php
/**
 * Quiénes somos · 02 Lo que hacemos: los servicios del taller con su foto.
 * Nombre, texto y foto de cada servicio salen de 'servicios' en inc/datos-taller.php.
 * Si la foto no está en assets/img/taller/, la tarjeta queda solo con texto.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$servicios = (array) bullracing_datos_taller()['servicios'];
?>
<section class="br-skin-rayas">
	<div class="br-contenedor br-hacemos">
		<div class="br-hacemos__intro">
			<p class="br-skin-etiqueta"><?php esc_html_e( 'Lo que hacemos', 'bullracing' ); ?></p>
			<h2 class="br-hacemos__titulo"><?php esc_html_e( 'Tres servicios,', 'bullracing' ); ?><br><?php esc_html_e( 'un mismo cuidado', 'bullracing' ); ?></h2>
			<p><?php esc_html_e( 'Cada moto que entra al taller tiene su orden de servicio. Sabes qué se le hizo, cuánto costó y cuándo te la entregamos.', 'bullracing' ); ?></p>
		</div>

		<ul class="br-hacemos__tarjetas">
			<?php foreach ( $servicios as $i => $servicio ) : ?>
				<?php $foto = bullracing_foto( $servicio['foto'] ); ?>
				<li class="br-servicio">
					<?php if ( $foto ) : ?>
						<div class="br-servicio__fondo" style="--fondo: url('<?php echo esc_url( $foto ); ?>');"></div>
					<?php endif; ?>
					<div class="br-servicio__contenido">
						<span class="br-servicio__numero" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3 class="br-servicio__nombre"><?php echo esc_html( $servicio['nombre'] ); ?></h3>
						<p><?php echo esc_html( $servicio['texto'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
