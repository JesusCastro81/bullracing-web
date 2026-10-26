<?php
/**
 * Página: Términos y condiciones (slug "terminos"), diseño 1.
 *
 * - El texto legal sale de inc/datos-terminos.php, copiado tal cual del sitio actual.
 * - Índice fijo al hacer scroll: al tocar una sección salta a ella, la marca activa y
 *   actualiza la barra de avance de lectura (assets/js/main.js, bloque "Términos").
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$terminos  = bullracing_datos_terminos();
$secciones = $terminos['secciones'];
$total     = count( $secciones );

get_header();

bullracing_componente(
	'banner-pagina',
	array(
		'ruta'      => __( 'Términos y condiciones', 'bullracing' ),
		'etiqueta'  => __( 'Legal', 'bullracing' ),
		'titulo'    => __( 'Términos y', 'bullracing' ),
		'resaltado' => __( 'condiciones', 'bullracing' ),
		'subtitulo' => __( 'Condiciones de uso del sitio web de Motos Bull Racing. Aplican únicamente para Colombia.', 'bullracing' ),
	)
);
?>
<div class="br-contenedor br-legal" data-br-legal>
	<aside class="br-legal__lateral">
		<nav class="br-indice" aria-label="<?php esc_attr_e( 'Índice', 'bullracing' ); ?>">
			<p class="br-indice__titulo"><?php esc_html_e( 'Índice', 'bullracing' ); ?></p>
			<ol class="br-indice__lista">
				<?php foreach ( $secciones as $i => $seccion ) : ?>
					<li>
						<a class="br-indice__enlace<?php echo 0 === $i ? ' is-activo' : ''; ?>" href="#<?php echo esc_attr( $seccion['id'] ); ?>" data-br-legal-enlace>
							<span><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
							<?php echo esc_html( $seccion['titulo'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ol>
			<div class="br-indice__avance">
				<div class="br-indice__barra"><span data-br-legal-barra></span></div>
				<p data-br-legal-estado
					data-plantilla="<?php echo esc_attr( sprintf( /* translators: 1: sección actual, 2: total, 3: porcentaje */ __( 'Sección %1$s de %2$s · %3$s%% leído', 'bullracing' ), '{n}', $total, '{p}' ) ); ?>">
					<?php echo esc_html( sprintf( __( 'Sección %1$s de %2$s · %3$s%% leído', 'bullracing' ), 1, $total, 0 ) ); ?>
				</p>
			</div>
		</nav>

		<dl class="br-legal__empresa">
			<p class="br-legal__empresa-titulo"><?php esc_html_e( 'Datos de la empresa', 'bullracing' ); ?></p>
			<?php foreach ( $terminos['empresa'] as $etiqueta => $valor ) : ?>
				<div>
					<dt><?php echo esc_html( $etiqueta ); ?></dt>
					<dd><?php echo esc_html( $valor ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</aside>

	<article class="br-legal__texto">
		<header class="br-legal__meta">
			<span><?php esc_html_e( 'Términos y condiciones · uso web', 'bullracing' ); ?></span>
			<span>
				<?php esc_html_e( 'Última actualización:', 'bullracing' ); ?>
				<strong><?php echo esc_html( $terminos['actualizado'] ? $terminos['actualizado'] : '[FECHA]' ); ?></strong>
			</span>
		</header>

		<?php foreach ( $secciones as $i => $seccion ) : ?>
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
</div>
<?php
get_footer();
