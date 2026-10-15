<?php
/**
 * Pie de página: datos del taller, redes, menú del pie y TecnoParque Nodo Bogotá (SENA) como asesor técnico.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller = bullracing_datos_taller();
$redes  = array_filter( $taller['redes'] );
?>
</main>

<footer class="br-footer">
	<div class="br-contenedor br-footer__columnas">
		<div class="br-footer__marca">
			<p class="br-footer__nombre"><?php echo esc_html( $taller['nombre'] ); ?></p>
			<p class="br-footer__lema"><?php echo esc_html( $taller['lema'] ); ?></p>
			<?php bullracing_componente( 'estrellas', array( 'valor' => $taller['calificacion'], 'total' => $taller['clientes'] ) ); ?>
		</div>

		<div class="br-footer__contacto">
			<p class="br-footer__titulo"><?php esc_html_e( 'Contacto', 'bullracing' ); ?></p>
			<ul>
				<li><?php echo esc_html( $taller['direccion'] ); ?></li>
				<li><?php echo esc_html( $taller['horario'] ); ?></li>
				<li><a href="<?php echo esc_url( bullracing_whatsapp_url() ); ?>" target="_blank" rel="noopener">WhatsApp <?php echo esc_html( $taller['telefono'] ); ?></a></li>
			</ul>
		</div>

		<div class="br-footer__enlaces">
			<p class="br-footer__titulo"><?php esc_html_e( 'Enlaces', 'bullracing' ); ?></p>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'pie',
					'container'      => false,
					'fallback_cb'    => 'bullracing_menu_respaldo',
				)
			);
			?>
			<?php if ( $redes ) : ?>
				<ul class="br-footer__redes">
					<?php foreach ( $redes as $red => $url ) : ?>
						<li><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $red ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>

	<div class="br-contenedor br-footer__base">
		<p class="br-footer__asesor"><?php esc_html_e( 'Asesor técnico: TecnoParque Nodo Bogotá · SENA', 'bullracing' ); ?></p>
		<p class="br-footer__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $taller['nombre'] ); ?></p>
	</div>
</footer>

<?php get_template_part( 'template-parts/boton-whatsapp' ); ?>

<?php wp_footer(); ?>
</body>
</html>
