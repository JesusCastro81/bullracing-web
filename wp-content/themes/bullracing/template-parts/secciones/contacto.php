<?php
/**
 * Sección "contacto" del inicio (diseño 1, igual al PDF): mosaico con fotos del taller,
 * clientes satisfechos, redes, horario, mapa y dirección.
 *
 * - Fotos: mientras el taller no las entregue, se muestran los recuadros del diseño
 *   (filtro 'bullracing_contacto_fotos').
 * - Mapa: NO se carga solo. Se carga cuando el cliente toca "Ver mapa" (página más rápida,
 *   funciona sin internet y no le avisa a Google cada vez que alguien abre la página).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller = bullracing_datos_taller();
$redes  = array_filter( (array) $taller['redes'] );

$fotos = apply_filters(
	'bullracing_contacto_fotos',
	array(
		'rampa'   => '', // URL de la foto real: mecánico trabajando en rampa.
		'entrega' => '', // URL de la foto real: entrega de una moto.
	)
);

// Horario en dos líneas, como en el diseño: "LUNES A SÁBADO" / "8:00 A. M. – 6:00 P. M.".
$horario = array_map( 'trim', explode( '·', (string) $taller['horario'] ) );

$direccion = (string) $taller['direccion'];
$mapa_url  = 'https://www.google.com/maps?q=' . rawurlencode( $direccion ) . '&output=embed';
$mapa_ver  = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $direccion );
?>
<section class="br-seccion br-seccion--contacto br-contacto" id="contacto">
	<div class="br-contenedor br-contacto__mosaico">
		<figure class="br-contacto__foto br-contacto__foto--rampa">
			<?php if ( $fotos['rampa'] ) : ?>
				<img src="<?php echo esc_url( $fotos['rampa'] ); ?>" alt="<?php esc_attr_e( 'Mecánico trabajando en la rampa del taller', 'bullracing' ); ?>" loading="lazy">
			<?php else : ?>
				<figcaption><?php esc_html_e( 'Foto real: mecánico trabajando en rampa', 'bullracing' ); ?></figcaption>
			<?php endif; ?>
		</figure>

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
			<p class="br-contacto__grande">
				<?php foreach ( $horario as $linea ) : ?>
					<span><?php echo esc_html( $linea ); ?></span>
				<?php endforeach; ?>
			</p>
		</div>

		<div class="br-contacto__mapa" data-br-mapa="<?php echo esc_url( $mapa_url ); ?>">
			<button type="button" class="br-contacto__ver-mapa" data-br-mapa-cargar>
				<?php esc_html_e( 'Ver mapa', 'bullracing' ); ?>
			</button>
			<p class="br-contacto__mapa-nota">
				<?php esc_html_e( 'El mapa de Google se carga al tocar el botón.', 'bullracing' ); ?>
				<a href="<?php echo esc_url( $mapa_ver ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Abrir en Google Maps ↗', 'bullracing' ); ?></a>
			</p>
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
