<?php
/**
 * Botón flotante de WhatsApp (presente en todas las páginas).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;
?>
<a class="br-whatsapp"
	href="<?php echo esc_url( bullracing_whatsapp_url( __( 'Hola, quiero cotizar un servicio para mi moto.', 'bullracing' ) ) ); ?>"
	target="_blank" rel="noopener"
	aria-label="<?php esc_attr_e( 'Escribir por WhatsApp', 'bullracing' ); ?>">
	WhatsApp
</a>
