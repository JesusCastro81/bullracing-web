<?php
/**
 * Botón flotante de WhatsApp (presente en todas las páginas).
 * Círculo con el ícono de chat; el texto queda para lectores de pantalla.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;
?>
<a class="br-whatsapp"
	href="<?php echo esc_url( bullracing_whatsapp_url( __( 'Hola, quiero cotizar un servicio para mi moto.', 'bullracing' ) ) ); ?>"
	target="_blank" rel="noopener"
	aria-label="<?php esc_attr_e( 'Escribir por WhatsApp', 'bullracing' ); ?>">
	<svg class="br-whatsapp__icono" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.4A8.4 8.4 0 1 1 21 11.5z"></path></svg>
	<span class="screen-reader-text"><?php esc_html_e( 'WhatsApp', 'bullracing' ); ?></span>
</a>
