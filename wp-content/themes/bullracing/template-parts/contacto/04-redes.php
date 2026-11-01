<?php
/**
 * Contacto · 04 Redes sociales del taller ('redes' en inc/datos-taller.php).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller    = bullracing_datos_taller();
$redes     = array_filter( (array) $taller['redes'] );
$instagram = (string) ( $redes['Instagram'] ?? '' );
$usuario   = $instagram ? '@' . trim( (string) wp_parse_url( $instagram, PHP_URL_PATH ), '/' ) : '';
?>
<div class="br-sociales">
	<p class="br-sociales__cabecera">
		<span><?php esc_html_e( 'Síguenos', 'bullracing' ); ?></span>
		<?php if ( $usuario ) : ?>
			<span class="br-sociales__usuario"><?php echo esc_html( $usuario ); ?></span>
		<?php endif; ?>
	</p>
	<ul class="br-sociales__lista">
		<?php foreach ( array( 'Instagram', 'TikTok', 'Facebook' ) as $red ) : ?>
			<?php if ( ! empty( $redes[ $red ] ) ) : ?>
				<li><a href="<?php echo esc_url( $redes[ $red ] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( strtoupper( $red ) ); ?> ↗</a></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<p class="br-sociales__lema"><?php esc_html_e( '#RuedaLibre #RuedaSeguro', 'bullracing' ); ?></p>
</div>
