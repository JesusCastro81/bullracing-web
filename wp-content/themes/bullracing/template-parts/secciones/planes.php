<?php
/**
 * Sección "planes" de la landing (diseño 1, igual al PDF): fichas tipo orden de servicio
 * y tacómetro con el precio "desde" del plan elegido.
 *
 * Textos: inc/datos-planes.php (los mismos de la página actual del taller).
 * Precios: tabla de tarifas de bullracing-core (las mismas del agendamiento).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$planes      = bullracing_datos_planes();
$desde       = bullracing_precios_desde();
$url_agendar = bullracing_url_pagina( 'agendamiento', '#planes' );
$motores     = array(
	'gasolina'  => __( 'Gasolina', 'bullracing' ),
	'electrica' => __( 'Eléctrica', 'bullracing' ),
);

/** Formato del diseño: $280.000 o "No aplica". */
$formato = static function ( ?int $valor ): string {
	return $valor ? '$' . number_format( $valor, 0, ',', '.' ) : __( 'No aplica', 'bullracing' );
};

// Plan que arranca elegido: el recomendado (Preventivo), como en el diseño.
$nombres = array_keys( $planes );
$plan_0  = $nombres[0];
foreach ( $planes as $nombre => $plan ) {
	if ( ! empty( $plan['recomendado'] ) ) {
		$plan_0 = $nombre;
		break;
	}
}
$valor_0 = $desde['gasolina'][ $plan_0 ] ?? null;
?>
<section class="br-seccion br-seccion--planes br-planes br-skin-rayas" id="planes" data-br-planes>
	<div class="br-contenedor">
		<div class="br-planes__cabecera">
			<div>
				<p class="br-skin-etiqueta"><?php esc_html_e( 'Planes de mantenimiento', 'bullracing' ); ?></p>
				<h2 class="br-planes__titulo">
					<?php esc_html_e( 'Elige tu orden', 'bullracing' ); ?><br>
					<?php esc_html_e( 'de servicio', 'bullracing' ); ?>
				</h2>
			</div>

			<div class="br-planes__motores" role="group" aria-label="<?php esc_attr_e( 'Tipo de moto', 'bullracing' ); ?>">
				<?php foreach ( $motores as $id => $etiqueta ) : ?>
					<button type="button" class="br-planes__motor" data-br-motor="<?php echo esc_attr( $id ); ?>"
						aria-pressed="<?php echo 'gasolina' === $id ? 'true' : 'false'; ?>">
						<?php echo esc_html( $etiqueta ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="br-planes__cuerpo">
			<div class="br-planes__fichas">
				<?php foreach ( $nombres as $i => $nombre ) : ?>
					<?php
					$plan = $planes[ $nombre ];
					?>
					<button type="button" class="br-ficha" data-br-plan="<?php echo esc_attr( $nombre ); ?>"
						aria-pressed="<?php echo $nombre === $plan_0 ? 'true' : 'false'; ?>"
						<?php foreach ( array_keys( $motores ) as $motor ) : ?>
							data-precio-<?php echo esc_attr( $motor ); ?>="<?php echo esc_attr( (string) ( $desde[ $motor ][ $nombre ] ?? 0 ) ); ?>"
						<?php endforeach; ?>>
						<span class="br-ficha__codigo"><span>OT</span><span><?php echo esc_html( sprintf( 'N.º %03d', $i + 1 ) ); ?></span></span>
						<span class="br-ficha__nombre"><?php echo esc_html( $nombre ); ?></span>
						<?php foreach ( $plan['incluye'] as $motor => $items ) : ?>
							<span class="br-ficha__lista" data-br-lista="<?php echo esc_attr( $motor ); ?>" <?php echo 'gasolina' === $motor ? '' : 'hidden'; ?>>
								<?php foreach ( $items as $item ) : ?>
									<span class="br-ficha__item"><span class="br-ficha__check" aria-hidden="true">✓</span><?php echo esc_html( $item ); ?></span>
								<?php endforeach; ?>
							</span>
						<?php endforeach; ?>
						<span class="br-ficha__precio" data-br-ficha-precio><?php echo esc_html( $formato( $desde['gasolina'][ $nombre ] ?? null ) ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="br-tacometro">
				<p class="br-tacometro__etiqueta"><?php esc_html_e( 'Mano de obra desde', 'bullracing' ); ?></p>
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
				<p class="br-tacometro__precio" data-br-precio-grande aria-live="polite"><?php echo esc_html( $formato( $valor_0 ) ); ?></p>
				<p class="br-tacometro__detalle">
					<?php esc_html_e( 'Plan', 'bullracing' ); ?> <span data-br-plan-nombre><?php echo esc_html( $plan_0 ); ?></span>
					· <span data-br-motor-nombre><?php esc_html_e( 'moto a gasolina', 'bullracing' ); ?></span>
				</p>
				<a class="br-tacometro__agendar" data-br-agendar-plan href="<?php echo esc_url( $url_agendar ); ?>"><?php esc_html_e( 'Agendar este plan', 'bullracing' ); ?></a>
				<p class="br-tacometro__nota"><?php esc_html_e( 'El valor final depende del cilindraje o potencia.', 'bullracing' ); ?></p>
			</div>
		</div>
	</div>
</section>
