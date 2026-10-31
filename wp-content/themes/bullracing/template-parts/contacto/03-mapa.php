<?php
/**
 * Contacto · 03 Mapa de Google con la ubicación del taller.
 * El mapa se ve de una vez (se carga al llegar a esa parte). "Cómo llegar" abre la ruta en Google Maps.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller  = bullracing_datos_taller();
$como_ir = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( $taller['direccion'] );
?>
<div class="br-plano br-plano--vivo">
	<iframe class="br-plano__mapa" src="<?php echo esc_url( $taller['mapa'] ); ?>"
		title="<?php esc_attr_e( 'Mapa con la ubicación del taller', 'bullracing' ); ?>"
		loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
	<p class="br-plano__etiqueta">
		<strong><?php echo esc_html( $taller['nombre'] ); ?></strong>
		<span><?php echo esc_html( $taller['calle'] . ' · ' . $taller['ciudad'] ); ?></span>
	</p>
	<a class="br-plano__llegar" href="<?php echo esc_url( $como_ir ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Cómo llegar ↗', 'bullracing' ); ?></a>
</div>
