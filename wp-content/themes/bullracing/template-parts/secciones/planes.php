<?php
/**
 * Sección "planes" de la landing (diseño 1).
 * Igual a la sección de la página oficial del taller (reunión 20-oct-2026):
 * pestañas gasolina / eléctricas, una tarjeta por plan con su desplegable de referencias,
 * lo que incluye y el botón Agendar. Encima, el tacómetro con el precio de la tarjeta elegida
 * y el aviso de que el precio es solo mano de obra.
 *
 * Textos: inc/datos-planes.php (los mismos de la página actual del taller).
 * Precios: tabla de tarifas de bullracing-core (las mismas del agendamiento).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$planes      = bullracing_datos_planes();
$precios     = bullracing_precios_por_referencia();
$url_agendar = bullracing_url_pagina( 'agendamiento', '#planes' );
$motores     = array(
	'gasolina'  => __( 'Motos gasolina', 'bullracing' ),
	'electrica' => __( 'Motos eléctricas', 'bullracing' ),
);
$nombre_motor = array(
	'gasolina'  => __( 'moto a gasolina', 'bullracing' ),
	'electrica' => __( 'moto eléctrica', 'bullracing' ),
);

/** Formato del diseño: $280.000. */
$formato = static function ( int $valor ): string {
	return '$' . number_format( $valor, 0, ',', '.' );
};

// Sin tarifas (plugin inactivo) no hay precios que mostrar.
if ( empty( $precios ) ) {
	return;
}

// Tarjeta que arranca en el tacómetro: el plan recomendado (Preventivo) a gasolina, primera referencia.
$plan_0 = (string) array_key_first( $precios['gasolina'] ?? array() );
foreach ( $planes as $nombre => $plan ) {
	if ( ! empty( $plan['recomendado'] ) && isset( $precios['gasolina'][ $nombre ] ) ) {
		$plan_0 = $nombre;
		break;
	}
}
$fila_0 = $precios['gasolina'][ $plan_0 ][0] ?? array( 'referencia' => '', 'precio' => 0 );
?>
<section class="br-seccion br-seccion--planes br-planes br-skin-rayas" id="planes" data-br-planes>
	<div class="br-contenedor">
		<div class="br-planes__arriba">
			<div class="br-planes__intro">
				<p class="br-skin-etiqueta"><?php esc_html_e( 'Planes de mantenimiento', 'bullracing' ); ?></p>
				<h2 class="br-planes__titulo">
					<?php esc_html_e( 'Elige tu orden', 'bullracing' ); ?><br>
					<?php esc_html_e( 'de servicio', 'bullracing' ); ?>
				</h2>
				<p class="br-planes__sub"><?php esc_html_e( 'Selecciona tu máquina para ver el precio exacto.', 'bullracing' ); ?></p>

				<div class="br-planes__motores" role="group" aria-label="<?php esc_attr_e( 'Tipo de moto', 'bullracing' ); ?>">
					<?php foreach ( $motores as $id => $etiqueta ) : ?>
						<button type="button" class="br-planes__motor" data-br-motor="<?php echo esc_attr( $id ); ?>"
							aria-pressed="<?php echo 'gasolina' === $id ? 'true' : 'false'; ?>">
							<?php echo esc_html( $etiqueta ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="br-tacometro">
				<p class="br-tacometro__etiqueta"><?php esc_html_e( 'Mano de obra', 'bullracing' ); ?></p>
				<svg class="br-tacometro__svg" width="300" height="180" viewBox="0 0 300 180" role="img"
					aria-label="<?php esc_attr_e( 'Tacómetro con el precio del plan elegido', 'bullracing' ); ?>">
					<path d="M 30 160 A 120 120 0 0 1 270 160" fill="none" class="br-tacometro__fondo" stroke-width="18"></path>
					<path d="M 222 63 A 120 120 0 0 1 270 160" fill="none" class="br-tacometro__zona" stroke-width="18"></path>
					<g class="br-tacometro__marcas" stroke-width="2">
						<line x1="30" y1="160" x2="48" y2="160"></line><line x1="65" y1="75" x2="78" y2="88"></line><line x1="150" y1="40" x2="150" y2="58"></line><line x1="235" y1="75" x2="222" y2="88"></line><line x1="270" y1="160" x2="252" y2="160"></line>
					</g>
					<g class="br-tacometro__aguja" data-br-aguja>
						<line x1="150" y1="160" x2="150" y2="62" stroke-width="4" stroke-linecap="round"></line>
					</g>
					<circle cx="150" cy="160" r="12" class="br-tacometro__centro"></circle>
				</svg>
				<p class="br-tacometro__precio" data-br-precio-grande aria-live="polite"><?php echo esc_html( $formato( (int) $fila_0['precio'] ) ); ?></p>
				<p class="br-tacometro__detalle">
					<?php esc_html_e( 'Plan', 'bullracing' ); ?> <span data-br-plan-nombre><?php echo esc_html( $plan_0 ); ?></span>
					· <span data-br-motor-nombre><?php echo esc_html( $nombre_motor['gasolina'] ); ?></span>
					· <span data-br-ref-nombre><?php echo esc_html( $fila_0['referencia'] ); ?></span>
				</p>
				<p class="br-tacometro__aviso">
					<strong><?php esc_html_e( 'Precio solo de mano de obra.', 'bullracing' ); ?></strong>
					<?php esc_html_e( 'El valor final puede variar si tu moto necesita repuestos.', 'bullracing' ); ?>
				</p>
			</div>
		</div>

		<?php foreach ( array_keys( $motores ) as $motor ) : ?>
			<?php
			$planes_motor = array_filter(
				array_keys( $planes ),
				static fn( $nombre ) => ! empty( $precios[ $motor ][ $nombre ] )
			);
			?>
			<div class="br-planes__tarjetas" data-br-grupo="<?php echo esc_attr( $motor ); ?>"
				style="--columnas: <?php echo (int) count( $planes_motor ); ?>"
				<?php echo 'gasolina' === $motor ? '' : 'hidden'; ?>>
				<?php foreach ( array_values( $planes_motor ) as $i => $nombre ) : ?>
					<?php
					$plan        = $planes[ $nombre ];
					$filas       = $precios[ $motor ][ $nombre ];
					$recomendado = ! empty( $plan['recomendado'] );
					$elegida     = 'gasolina' === $motor && $nombre === $plan_0;
					$id_select   = sprintf( 'br-ref-%s-%d', $motor, $i + 1 );
					$url_plan    = add_query_arg(
						array(
							'servicio'   => 'mantenimiento',
							'motor'      => $motor,
							'plan'       => $nombre,
							'referencia' => $filas[0]['referencia'],
						),
						$url_agendar
					);
					?>
					<article class="br-plan<?php echo $recomendado ? ' br-plan--recomendado' : ''; ?>"
						data-br-plan="<?php echo esc_attr( $nombre ); ?>" data-motor="<?php echo esc_attr( $motor ); ?>"
						aria-current="<?php echo $elegida ? 'true' : 'false'; ?>">
						<?php if ( $recomendado ) : ?>
							<span class="br-plan__sello"><?php esc_html_e( 'Recomendado', 'bullracing' ); ?></span>
						<?php endif; ?>
						<p class="br-plan__codigo"><span>OT</span><span><?php echo esc_html( sprintf( 'N.º %03d', $i + 1 ) ); ?></span></p>
						<h3 class="br-plan__nombre"><?php echo esc_html( $nombre ); ?></h3>
						<p class="br-plan__precio">
							<span class="br-plan__desde" data-br-desde><?php esc_html_e( 'Desde', 'bullracing' ); ?></span>
							<span class="br-plan__valor" data-br-valor><?php echo esc_html( $formato( (int) $filas[0]['precio'] ) ); ?></span>
						</p>
						<label class="br-plan__etiqueta" for="<?php echo esc_attr( $id_select ); ?>"><?php esc_html_e( 'Tu moto', 'bullracing' ); ?></label>
						<select class="br-plan__referencia" id="<?php echo esc_attr( $id_select ); ?>" data-br-referencia>
							<?php foreach ( $filas as $fila ) : ?>
								<option value="<?php echo esc_attr( $fila['referencia'] ); ?>" data-precio="<?php echo esc_attr( (string) $fila['precio'] ); ?>">
									<?php echo esc_html( $fila['referencia'] ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<ul class="br-plan__lista">
							<?php foreach ( $plan['incluye'][ $motor ] ?? array() as $item ) : ?>
								<li><span class="br-plan__check" aria-hidden="true">✓</span><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
						<a class="br-plan__agendar" data-br-agendar href="<?php echo esc_url( $url_plan ); ?>">
							<?php esc_html_e( 'Agendar', 'bullracing' ); ?>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
