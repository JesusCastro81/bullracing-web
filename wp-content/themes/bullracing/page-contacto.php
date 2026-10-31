<?php
/**
 * Página: Contacto (slug "contacto"), diseño 1. Orquestador.
 * El contacto es solo por WhatsApp: sin formulario ni correo.
 * Las partes viven en template-parts/contacto/; los datos salen de inc/datos-taller.php.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

get_header();

bullracing_componente(
	'banner-pagina',
	array(
		'ruta'      => __( 'Contacto', 'bullracing' ),
		'etiqueta'  => __( 'Contacto', 'bullracing' ),
		'titulo'    => __( 'Hablemos por', 'bullracing' ),
		'resaltado' => __( 'WhatsApp', 'bullracing' ),
		'subtitulo' => __( 'Cotizaciones, dudas de tu cita y estado de tu moto.', 'bullracing' ),
	)
);
?>
<section class="br-contenedor br-pcontacto">
	<div class="br-pcontacto__fila">
		<?php get_template_part( 'template-parts/contacto/01-whatsapp' ); ?>
		<?php get_template_part( 'template-parts/contacto/02-datos' ); ?>
	</div>

	<div class="br-pcontacto__fila br-pcontacto__fila--baja">
		<?php get_template_part( 'template-parts/contacto/03-mapa' ); ?>
		<?php get_template_part( 'template-parts/contacto/04-redes' ); ?>
	</div>
</section>
<?php
get_footer();
