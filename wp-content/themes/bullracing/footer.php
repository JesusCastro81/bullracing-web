<?php
/**
 * Pie de página (diseño 1): logo, lema y correo del taller en recuadro punteado.
 *
 * - El pie de página muestra el correo del taller (reunión del 20-oct-2026).
 * - Segunda línea: © y enlace a Términos y condiciones (la página legal debe poder abrirse).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller = bullracing_datos_taller();

$correo = (string) $taller['correo'];
?>
</main>

<footer class="br-footer">
	<div class="br-contenedor br-footer__fila">
		<a class="br-footer__marca" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo.png' ); ?>"
				alt="<?php echo esc_attr( $taller['nombre'] ); ?>" width="52" height="52" loading="lazy">
			<span class="br-footer__lema"><?php echo esc_html( $taller['lema'] ); ?></span>
		</a>

		<?php if ( $correo ) : ?>
			<p class="br-footer__contacto">
				<?php esc_html_e( 'Correo', 'bullracing' ); ?>
				<a class="br-footer__correo" href="<?php echo esc_url( 'mailto:' . $correo ); ?>"><?php echo esc_html( $correo ); ?></a>
			</p>
		<?php endif; ?>
	</div>

	<div class="br-contenedor br-footer__base">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) . ' ' . $taller['nombre'] ); ?></p>
		<a href="<?php echo esc_url( bullracing_url_pagina( 'terminos', '' ) ); ?>"><?php esc_html_e( 'Términos y condiciones', 'bullracing' ); ?></a>
	</div>
</footer>

<?php get_template_part( 'template-parts/boton-whatsapp' ); ?>

<?php wp_footer(); ?>
</body>
</html>
