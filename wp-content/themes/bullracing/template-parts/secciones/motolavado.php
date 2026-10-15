<?php
/**
 * Sección "motolavado" de la landing (diseño 1, igual al PDF): antes y después con
 * deslizador, y los 4 paquetes con su precio "desde" (tarifas del agendamiento).
 *
 * Fotos: mientras el taller no entregue fotos reales de antes y después, se muestran
 * los recuadros de "foto pendiente" del diseño. Para ponerlas se usa el filtro
 * 'bullracing_motolavado_fotos' (o se reemplazan las URL aquí).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$desde     = bullracing_precios_desde( 'motolavado' )['gasolina'] ?? array();
$destacado = 'Full Shine'; // El paquete resaltado en rojo, como en el diseño.

$fotos = apply_filters(
	'bullracing_motolavado_fotos',
	array(
		'antes'   => '', // URL de la foto real de la moto sucia al llegar.
		'despues' => '', // URL de la foto de la misma moto después del lavado.
	)
);
?>
<section class="br-seccion br-seccion--motolavado br-lavado" id="motolavado">
	<div class="br-contenedor br-lavado__grid">
		<div class="br-comparar" data-br-comparar style="--corte: 50%;">
			<div class="br-comparar__capa br-comparar__capa--antes">
				<?php if ( $fotos['antes'] ) : ?>
					<img src="<?php echo esc_url( $fotos['antes'] ); ?>" alt="<?php esc_attr_e( 'Moto sucia al llegar al taller', 'bullracing' ); ?>" loading="lazy">
				<?php else : ?>
					<span class="br-comparar__pendiente"><?php esc_html_e( 'Foto real: moto sucia al llegar', 'bullracing' ); ?></span>
				<?php endif; ?>
			</div>
			<div class="br-comparar__capa br-comparar__capa--despues">
				<?php if ( $fotos['despues'] ) : ?>
					<img src="<?php echo esc_url( $fotos['despues'] ); ?>" alt="<?php esc_attr_e( 'La misma moto después del motolavado', 'bullracing' ); ?>" loading="lazy">
				<?php else : ?>
					<span class="br-comparar__pendiente"><?php esc_html_e( 'La misma moto después', 'bullracing' ); ?></span>
				<?php endif; ?>
			</div>

			<span class="br-comparar__linea" aria-hidden="true"></span>
			<span class="br-comparar__manija" aria-hidden="true">⟷</span>
			<span class="br-comparar__marca br-comparar__marca--antes"><?php esc_html_e( 'ANTES', 'bullracing' ); ?></span>
			<span class="br-comparar__marca br-comparar__marca--despues"><?php esc_html_e( 'DESPUÉS', 'bullracing' ); ?></span>

			<label class="screen-reader-text" for="br-comparar-rango"><?php esc_html_e( 'Deslizar para comparar antes y después', 'bullracing' ); ?></label>
			<input class="br-comparar__rango" id="br-comparar-rango" type="range" min="0" max="100" value="50" data-br-comparar-rango>
		</div>

		<div class="br-lavado__texto">
			<p class="br-skin-etiqueta"><?php esc_html_e( 'Nuevo en el taller', 'bullracing' ); ?></p>
			<h2 class="br-lavado__titulo"><?php esc_html_e( 'Moto', 'bullracing' ); ?><br><?php esc_html_e( 'lavado', 'bullracing' ); ?></h2>
			<p class="br-lavado__intro">
				<?php esc_html_e( 'Desliza y mira la diferencia. Cuatro niveles de cuidado, con precio según el cilindraje.', 'bullracing' ); ?>
				<strong><?php esc_html_e( 'Solo para motos a gasolina.', 'bullracing' ); ?></strong>
			</p>

			<ul class="br-lavado__paquetes">
				<?php foreach ( $desde as $paquete => $precio ) : ?>
					<li class="br-paquete<?php echo $paquete === $destacado ? ' br-paquete--destacado' : ''; ?>">
						<span class="br-paquete__nombre"><?php echo esc_html( $paquete ); ?></span>
						<span class="br-paquete__precio">
							<?php
							/* translators: %s: precio */
							echo esc_html( sprintf( __( 'desde %s', 'bullracing' ), '$' . number_format( $precio, 0, ',', '.' ) ) );
							?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
