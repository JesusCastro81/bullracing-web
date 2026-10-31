<?php
/**
 * Página: Términos y condiciones (slug "terminos"), diseño 1. Orquestador.
 * Las partes viven en template-parts/terminos/; el texto legal sale de inc/datos-terminos.php.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

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
	<?php get_template_part( 'template-parts/terminos/01-indice' ); ?>
	<?php get_template_part( 'template-parts/terminos/02-texto' ); ?>
</div>
<?php
get_footer();
