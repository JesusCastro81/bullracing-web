<?php
/**
 * Sección "hero" de la landing (piel Taller industrial), igual al diseño aprobado en el PDF.
 *
 * La barra blanca NO agrega funciones: usa las mismas opciones y tarifas del agendamiento
 * (API de bullracing-core) solo para mostrar el precio, y lleva al formulario de
 * agendamiento de la fase anterior, que es donde se escogen fecha y hora.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller      = bullracing_datos_taller();
$url_agendar = bullracing_url_pagina( 'agendamiento', '#planes' );
$url_cotizar = bullracing_whatsapp_url( __( 'Hola, quiero cotizar un servicio de mecánica general.', 'bullracing' ) );
?>
<section class="br-seccion br-seccion--hero br-hero" id="hero">
	<img class="br-hero__imagen"
		src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/hero.jpg' ); ?>"
		alt="<?php esc_attr_e( 'Motociclista en carretera, imagen actual del sitio', 'bullracing' ); ?>"
		width="1920" height="1080" fetchpriority="high">

	<div class="br-contenedor br-hero__contenido">
		<?php /* Abierto (verde) o cerrado (rojo) según la hora de Bogotá, domingos y festivos: lo calcula main.js. */ ?>
		<p class="br-hero__estado" data-br-estado>
			<span class="br-hero__punto" aria-hidden="true"></span>
			<span data-br-estado-texto
				data-abierto="<?php esc_attr_e( 'TALLER ABIERTO', 'bullracing' ); ?>"
				data-cerrado="<?php esc_attr_e( 'TALLER CERRADO', 'bullracing' ); ?>"><?php esc_html_e( 'TALLER ABIERTO', 'bullracing' ); ?></span>
			<?php esc_html_e( '· BOGOTÁ · L-S 8:00 a 6:00', 'bullracing' ); ?>
		</p>

		<h1 class="br-hero__titulo">
			<?php esc_html_e( 'Centro', 'bullracing' ); ?><br>
			<?php esc_html_e( 'técnico', 'bullracing' ); ?><br>
			<span><?php esc_html_e( 'de motos', 'bullracing' ); ?></span>
		</h1>

		<p class="br-hero__texto">
			<?php esc_html_e( 'Mantenimiento, diagnóstico y motolavado en Bogotá. Gasolina y eléctricas. Ves el precio antes de llegar.', 'bullracing' ); ?>
		</p>

		<?php
		bullracing_componente(
			'estrellas',
			array(
				'valor' => $taller['calificacion'],
				'total' => $taller['clientes'],
			)
		);
		?>

		<a class="br-hero__cotizar" href="<?php echo esc_url( $url_cotizar ); ?>" target="_blank" rel="noopener">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.4A8.4 8.4 0 1 1 21 11.5z"></path></svg>
			<?php esc_html_e( 'Cotizar por WhatsApp · mecánica general', 'bullracing' ); ?>
		</a>
	</div>

	<form class="br-cotizador" data-br-cotizador
		action="<?php echo esc_url( $url_agendar ); ?>" method="get"
		aria-label="<?php esc_attr_e( 'Ver el precio antes de agendar', 'bullracing' ); ?>">
		<label class="br-cotizador__campo">
			<?php esc_html_e( 'Servicio', 'bullracing' ); ?>
			<select data-br-servicio disabled>
				<option><?php esc_html_e( 'Mantenimiento preventivo', 'bullracing' ); ?></option>
			</select>
		</label>
		<label class="br-cotizador__campo">
			<?php esc_html_e( 'Tu moto', 'bullracing' ); ?>
			<select data-br-referencia disabled>
				<option><?php esc_html_e( 'Naked < 200', 'bullracing' ); ?></option>
			</select>
		</label>
		<a class="br-cotizador__campo br-cotizador__fecha" href="<?php echo esc_url( $url_agendar ); ?>">
			<?php esc_html_e( 'Fecha', 'bullracing' ); ?>
			<span><?php esc_html_e( 'Escoge día y hora', 'bullracing' ); ?></span>
		</a>
		<a class="br-cotizador__agendar" href="<?php echo esc_url( $url_agendar ); ?>">
			<?php esc_html_e( 'Agendar', 'bullracing' ); ?>
			<output class="br-cotizador__precio" data-br-precio aria-live="polite"></output>
		</a>
	</form>
</section>
