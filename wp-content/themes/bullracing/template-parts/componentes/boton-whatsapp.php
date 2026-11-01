<?php
/**
 * Componente: botón flotante de WhatsApp (presente en todas las páginas).
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
	<?php echo bullracing_icono_chat( 30, 'br-whatsapp__icono' ); // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fijo. ?>
	<span class="screen-reader-text"><?php esc_html_e( 'WhatsApp', 'bullracing' ); ?></span>
</a>
