<?php
/**
 * Pie de página: datos del taller, menú del pie y TecnoParque como asesor técnico.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="br-footer">
	<p class="br-footer__lema"><?php esc_html_e( 'Rueda libre, rueda seguro', 'bullracing' ); ?></p>

	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'pie',
			'container'      => 'nav',
			'fallback_cb'    => false,
		)
	);
	?>

	<p class="br-footer__asesor">
		<?php esc_html_e( 'Asesor técnico: TecnoParque SENA', 'bullracing' ); ?>
	</p>
	<p class="br-footer__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Motos Bull Racing</p>
</footer>

<?php get_template_part( 'template-parts/boton-whatsapp' ); ?>

<?php wp_footer(); ?>
</body>
</html>
