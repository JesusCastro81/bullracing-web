<?php
/**
 * Pie de página (diseño 1, tal cual el Word): logo, lema y asesoría técnica de
 * TecnoParque Nodo Bogotá · SENA en recuadro punteado.
 *
 * - Logo de TecnoParque: cuando la ingeniera lo entregue, se guarda como
 *   assets/img/tecnoparque-nodo-bogota.png (o .svg) y aparece solo en el recuadro.
 *   Va SIN enlace (TecnoParque no tiene página web propia) y sin contacto.
 * - Segunda línea: © y enlace a Términos y condiciones (la página legal debe poder abrirse).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller = bullracing_datos_taller();

// Logo de TecnoParque si ya está en el tema (PNG o SVG).
$logo_tp = '';
foreach ( array( 'svg', 'png' ) as $ext ) {
	$archivo = '/assets/img/tecnoparque-nodo-bogota.' . $ext;
	if ( file_exists( get_template_directory() . $archivo ) ) {
		$logo_tp = get_template_directory_uri() . $archivo;
		break;
	}
}
?>
</main>

<footer class="br-footer">
	<div class="br-contenedor br-footer__fila">
		<a class="br-footer__marca" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo.png' ); ?>"
				alt="<?php echo esc_attr( $taller['nombre'] ); ?>" width="52" height="52" loading="lazy">
			<span class="br-footer__lema"><?php echo esc_html( $taller['lema'] ); ?></span>
		</a>

		<p class="br-footer__asesor">
			<?php esc_html_e( 'Asesoría técnica', 'bullracing' ); ?>
			<span class="br-footer__tecnoparque">
				<?php if ( $logo_tp ) : ?>
					<img src="<?php echo esc_url( $logo_tp ); ?>" alt="<?php esc_attr_e( 'TecnoParque Nodo Bogotá · SENA', 'bullracing' ); ?>" height="36" loading="lazy">
				<?php else : ?>
					<?php esc_html_e( 'TecnoParque Nodo Bogotá · SENA', 'bullracing' ); ?>
				<?php endif; ?>
			</span>
		</p>
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
