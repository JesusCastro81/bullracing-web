<?php
/**
 * Sección "contacto" del inicio (diseño 1, igual al PDF): mosaico con fotos del taller,
 * clientes satisfechos, redes, horario, mapa y dirección.
 *
 * - Fotos: mientras el taller no las entregue, se muestran los recuadros del diseño
 *   (filtro 'bullracing_contacto_fotos').
 * - Mapa: se ve de una vez, pero el navegador lo carga solo cuando el cliente baja hasta
 *   esta parte (loading="lazy"), para que la página abra rápido.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller = bullracing_datos_taller();
$redes  = array_filter( (array) $taller['redes'] );

$fotos = apply_filters(
	'bullracing_contacto_fotos',
	array(
		'rampa'    => '', // URL de la foto real: mecánico trabajando en rampa.
		'entrega'  => '', // URL de la foto real: entrega de una moto.
		// Carrusel del recuadro grande: fotos reales del proceso, en orden. Se mueve al pasar el mouse
		// (en celular, con cada toque). Cada foto: array( 'url' => '...', 'texto' => 'Recibo' ).
		'carrusel' => array(),
	)
);
$carrusel = array_values( array_filter( (array) $fotos['carrusel'], static fn( $f ) => ! empty( $f['url'] ) ) );

// Horario como en el diseño: días en una línea y horas en la siguiente ("LUNES A VIERNES" / "7:00 A. M. – 6:00 P. M.").
$horario = (array) $taller['horario'];

$direccion = (string) $taller['direccion'];
// Se busca por nombre + dirección para que Google marque el perfil del taller, no solo la calle.
$mapa_url  = 'https://www.google.com/maps?q=' . rawurlencode( $taller['nombre'] . ', ' . $direccion ) . '&z=16&output=embed';
$mapa_ver  = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $direccion );
?>
<section class="br-seccion br-seccion--contacto br-contacto" id="contacto">
	<div class="br-contenedor br-contacto__mosaico">
		<?php if ( count( $carrusel ) > 1 ) : ?>
			<figure class="br-contacto__foto br-contacto__foto--rampa br-carrusel" data-br-carrusel tabindex="0"
				aria-label="<?php esc_attr_e( 'Fotos del taller. Pase el mouse o toque para ver la siguiente.', 'bullracing' ); ?>">
				<?php foreach ( $carrusel as $i => $foto ) : ?>
					<img class="br-carrusel__foto<?php echo 0 === $i ? ' is-activa' : ''; ?>" src="<?php echo esc_url( $foto['url'] ); ?>"
						alt="<?php echo esc_attr( $foto['texto'] ?? '' ); ?>" loading="lazy" data-texto="<?php echo esc_attr( $foto['texto'] ?? '' ); ?>">
				<?php endforeach; ?>
				<figcaption class="br-carrusel__pie">
					<span class="br-carrusel__texto" data-br-carrusel-texto><?php echo esc_html( $carrusel[0]['texto'] ?? '' ); ?></span>
					<span class="br-carrusel__puntos" aria-hidden="true">
						<?php foreach ( $carrusel as $i => $foto ) : ?>
							<span class="<?php echo 0 === $i ? 'is-activo' : ''; ?>"></span>
						<?php endforeach; ?>
					</span>
				</figcaption>
			</figure>
		<?php else : ?>
		<figure class="br-contacto__foto br-contacto__foto--rampa">
			<?php if ( $fotos['rampa'] ) : ?>
				<img src="<?php echo esc_url( $fotos['rampa'] ); ?>" alt="<?php esc_attr_e( 'Mecánico trabajando en la rampa del taller', 'bullracing' ); ?>" loading="lazy">
			<?php else : ?>
				<figcaption><?php esc_html_e( 'Fotos reales del taller: recibo, rampa, lavado y entrega (carrusel al pasar el mouse)', 'bullracing' ); ?></figcaption>
			<?php endif; ?>
		</figure>
		<?php endif; ?>

		<div class="br-contacto__clientes">
			<span class="br-contacto__estrellas" aria-hidden="true">★★★★★</span>
			<p>
				<strong><?php echo esc_html( number_format_i18n( (int) $taller['clientes'] ) ); ?></strong>
				<span><?php esc_html_e( 'Clientes satisfechos', 'bullracing' ); ?></span>
			</p>
		</div>

		<figure class="br-contacto__foto">
			<?php if ( $fotos['entrega'] ) : ?>
				<img src="<?php echo esc_url( $fotos['entrega'] ); ?>" alt="<?php esc_attr_e( 'Entrega de una moto en el taller', 'bullracing' ); ?>" loading="lazy">
			<?php else : ?>
				<figcaption><?php esc_html_e( 'Foto real: entrega', 'bullracing' ); ?></figcaption>
			<?php endif; ?>
		</figure>

		<div class="br-contacto__redes">
			<p class="br-contacto__etiqueta"><?php esc_html_e( 'Síguenos', 'bullracing' ); ?></p>
			<ul>
				<?php foreach ( $redes as $red => $url ) : ?>
					<li><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( strtoupper( $red ) ); ?> ↗</a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="br-contacto__horario">
			<p class="br-contacto__etiqueta"><?php esc_html_e( 'Horario', 'bullracing' ); ?></p>
			<div>
				<p class="br-contacto__grande">
					<?php foreach ( $horario as $fila ) : ?>
						<span><?php echo esc_html( $fila['dias'] ); ?></span>
						<span class="br-contacto__horas"><?php echo esc_html( $fila['horas'] ); ?></span>
					<?php endforeach; ?>
				</p>
				<?php /* Mismo aviso del hero: verde si está abierto ahora, rojo si está cerrado (lo calcula main.js). */ ?>
				<p class="br-estado" data-br-estado>
					<span class="br-estado__punto" aria-hidden="true"></span>
					<span data-br-estado-texto
						data-abierto="<?php esc_attr_e( 'Abierto ahora', 'bullracing' ); ?>"
						data-cerrado="<?php esc_attr_e( 'Cerrado ahora', 'bullracing' ); ?>"><?php esc_html_e( 'Abierto ahora', 'bullracing' ); ?></span>
				</p>
			</div>
		</div>

		<div class="br-contacto__mapa br-contacto__mapa--vivo">
			<iframe class="br-contacto__iframe" src="<?php echo esc_url( $mapa_url ); ?>"
				title="<?php esc_attr_e( 'Mapa con la ubicación del taller', 'bullracing' ); ?>"
				loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			<a class="br-contacto__abrir-mapa" href="<?php echo esc_url( $mapa_ver ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Abrir en Google Maps ↗', 'bullracing' ); ?></a>
		</div>

		<div class="br-contacto__direccion">
			<p class="br-contacto__etiqueta"><?php esc_html_e( 'Dirección', 'bullracing' ); ?></p>
			<p class="br-contacto__grande"><?php echo esc_html( $direccion ); ?></p>
			<p class="br-contacto__datos">
				<?php /* Contacto solo por WhatsApp (acuerdo de la reunión): sin correo. */ ?>
				<a href="<?php echo esc_url( bullracing_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $taller['telefono'] ); ?> · WhatsApp</a>
			</p>
		</div>
	</div>
</section>
