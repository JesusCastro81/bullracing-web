<?php
/**
 * Página: Quiénes somos (slug "quienes-somos"), diseño 1. Orquestador.
 * Las secciones viven en template-parts/quienes-somos/; los datos salen de inc/datos-taller.php.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller = bullracing_datos_taller();
$anios  = (int) $taller['anios'];

get_header();

bullracing_componente(
	'banner-pagina',
	array(
		'ruta'      => __( 'Quiénes somos', 'bullracing' ),
		'etiqueta'  => __( 'Quiénes somos', 'bullracing' ),
		/* translators: %d: años del taller */
		'titulo'    => $anios ? sprintf( _n( '%d año', '%d años', $anios, 'bullracing' ), $anios ) : __( '[N] años', 'bullracing' ),
		'resaltado' => __( 'en el motor', 'bullracing' ),
		'salto'     => true,
		'subtitulo' => __( 'Taller de Bogotá para motos a gasolina y eléctricas.', 'bullracing' ),
		'cifras'    => array(
			array( 'valor' => $taller['desde'] ? (string) $taller['desde'] : '[AÑO]', 'etiqueta' => __( 'Desde', 'bullracing' ) ),
			array( 'valor' => $taller['clientes_fmt'], 'etiqueta' => $taller['contador']['texto'], 'destacado' => true ),
			array( 'valor' => 'BOG', 'etiqueta' => $taller['ciudad'] ),
		),
	)
);

get_template_part( 'template-parts/quienes-somos/01-historia' ); // Historia y video del taller.
get_template_part( 'template-parts/quienes-somos/02-servicios' ); // Lo que hacemos: 3 servicios con foto.
get_template_part( 'template-parts/quienes-somos/03-equipo' );   // Foto del taller, equipo y valoración.

get_footer();
