<?php
/**
 * Términos · 02 Texto legal, copiado tal cual del sitio actual (inc/datos-terminos.php).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$terminos = bullracing_datos_terminos();
?>
<article class="br-legal__texto">
	<header class="br-legal__meta">
		<span><?php esc_html_e( 'Términos y condiciones · uso web', 'bullracing' ); ?></span>
		<span>
			<?php esc_html_e( 'Última actualización:', 'bullracing' ); ?>
			<strong><?php echo esc_html( $terminos['actualizado'] ? $terminos['actualizado'] : '[FECHA]' ); ?></strong>
		</span>
	</header>

	<?php foreach ( $terminos['secciones'] as $i => $seccion ) : ?>
		<section class="br-legal__seccion" id="<?php echo esc_attr( $seccion['id'] ); ?>" data-br-legal-seccion>
			<h2 class="br-legal__titulo">
				<span aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				<?php echo esc_html( $seccion['titulo'] ); ?>
			</h2>
			<?php foreach ( $seccion['parrafos'] as $parrafo ) : ?>
				<p><?php echo esc_html( $parrafo ); ?></p>
			<?php endforeach; ?>
		</section>
	<?php endforeach; ?>
</article>
