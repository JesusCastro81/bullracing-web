<?php
/**
 * Inicio · 01 Hero (piel Taller industrial).
 *
 * La barra blanca NO agrega funciones: usa las mismas opciones y tarifas del agendamiento
 * (API de bullracing-core) para mostrar el precio, y lleva al formulario de agendamiento
 * con lo escogido ya marcado (ver assets/js/precarga-agendamiento.js). Fecha y hora se
 * escogen en el formulario.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller      = bullracing_datos_taller();
$url_agendar = bullracing_url_pagina( 'agendamiento', '#planes' );
$url_cotizar = bullracing_whatsapp_url( __( 'Hola, quiero cotizar un servicio de mecánica general.', 'bullracing' ) );

// Aviso junto al estado: ciudad y un trozo por fila del horario (salen de inc/datos-taller.php).
$aviso = array( $taller['ciudad'] );
foreach ( $taller['horario'] as $fila ) {
	$aviso[] = $fila['corto'] . ' ' . $fila['horas'];
}
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
			<?php foreach ( $aviso as $parte ) : ?>
				<span class="br-hero__aviso">· <?php echo esc_html( mb_strtoupper( $parte ) ); ?></span>
			<?php endforeach; ?>
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
				'texto' => $taller['contador']['texto'],
			)
		);
		?>

		<a class="br-hero__cotizar" href="<?php echo esc_url( $url_cotizar ); ?>" target="_blank" rel="noopener">
			<?php /* Burbuja de chat con teléfono: ícono propio estilo WhatsApp (no es el logo oficial). */ ?>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path stroke-width="2" d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.4A8.4 8.4 0 1 1 21 11.5z"></path><path fill="currentColor" stroke="none" transform="translate(7.6 6.6) scale(0.38)" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
			<?php esc_html_e( 'Cotizar mecánica general', 'bullracing' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( '(se abre WhatsApp)', 'bullracing' ); ?></span>
		</a>
	</div>

	<form class="br-cotizador" data-br-cotizador
		action="<?php echo esc_url( $url_agendar ); ?>" method="get"
		aria-label="<?php esc_attr_e( 'Ver el precio antes de agendar', 'bullracing' ); ?>">
		<label class="br-cotizador__campo">
			<?php esc_html_e( 'Servicio', 'bullracing' ); ?>
			<select data-br-servicio disabled>
				<option><?php echo esc_html( 'Mantenimiento ' . mb_strtolower( bullracing_plan_recomendado() ) ); ?></option>
			</select>
		</label>
		<label class="br-cotizador__campo">
			<?php esc_html_e( 'Tu moto', 'bullracing' ); ?>
			<select data-br-referencia disabled>
				<option><?php echo esc_html( bullracing_referencia_hero() ); ?></option>
			</select>
		</label>
		<a class="br-cotizador__campo br-cotizador__fecha" data-br-agendar href="<?php echo esc_url( $url_agendar ); ?>">
			<?php esc_html_e( 'Fecha', 'bullracing' ); ?>
			<span><?php esc_html_e( 'Escoge día y hora', 'bullracing' ); ?></span>
		</a>
		<a class="br-cotizador__agendar" data-br-agendar href="<?php echo esc_url( $url_agendar ); ?>">
			<?php esc_html_e( 'Agendar', 'bullracing' ); ?>
			<output class="br-cotizador__precio" data-br-precio aria-live="polite"></output>
		</a>
	</form>
</section>
