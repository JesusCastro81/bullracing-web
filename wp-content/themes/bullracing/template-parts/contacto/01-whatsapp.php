<?php
/**
 * Contacto · 01 Chat de WhatsApp.
 * - "Abrir chat" abre la conversación de WhatsApp con el taller.
 * - "Copiar número" copia el teléfono y confirma con "Número copiado".
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller   = bullracing_datos_taller();
$url_chat = bullracing_whatsapp_url( __( 'Hola, quiero comunicarme con el taller.', 'bullracing' ) );

// Ícono de chat (el mismo de la burbuja flotante).
$icono_chat = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.4A8.4 8.4 0 1 1 21 11.5z"></path></svg>';
?>
<div class="br-chat">
	<span class="br-chat__fondo" aria-hidden="true"><?php echo $icono_chat; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fijo. ?></span>
	<p class="br-chat__canal"><span aria-hidden="true"></span><?php esc_html_e( 'Canal oficial de contacto', 'bullracing' ); ?></p>
	<h2 class="br-chat__titulo"><?php esc_html_e( 'Escríbenos', 'bullracing' ); ?><br><?php esc_html_e( 'al chat', 'bullracing' ); ?></h2>
	<p class="br-chat__numero"><?php echo esc_html( $taller['telefono'] ); ?></p>
	<ul class="br-chat__temas">
		<li><?php esc_html_e( 'Cotizar mecánica general', 'bullracing' ); ?></li>
		<li><?php esc_html_e( 'Estado de tu moto', 'bullracing' ); ?></li>
		<li><?php esc_html_e( 'Cambiar tu cita', 'bullracing' ); ?></li>
	</ul>
	<div class="br-chat__acciones">
		<a class="br-chat__abrir" href="<?php echo esc_url( $url_chat ); ?>" target="_blank" rel="noopener">
			<?php echo $icono_chat; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fijo. ?>
			<?php esc_html_e( 'Abrir chat ↗', 'bullracing' ); ?>
		</a>
		<button type="button" class="br-chat__copiar" data-br-copiar="<?php echo esc_attr( $taller['telefono'] ); ?>"
			data-copiado="<?php esc_attr_e( 'Número copiado', 'bullracing' ); ?>">
			<?php esc_html_e( 'Copiar número', 'bullracing' ); ?>
		</button>
	</div>
</div>
