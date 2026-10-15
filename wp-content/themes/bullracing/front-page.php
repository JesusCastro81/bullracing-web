<?php
/**
 * Inicio (landing). Se arma por secciones según el diseño escogido.
 * Cada sección vive en template-parts/secciones/.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

get_header();

$secciones = array(
	'hero',        // Imagen actual + agendar + cotizar por WhatsApp.
	'taller',      // Así entra y así sale tu moto (4 pasos).
	'planes',      // Planes de mantenimiento (precios desde bullracing-core).
	'motolavado',  // Paquetes de motolavado (solo motos a gasolina).
	'valoracion',  // Estrellas y reseñas de clientes.
	'nosotros',    // Resumen de Quiénes somos.
	'contacto',    // WhatsApp, dirección, horario, mapa y redes.
);

foreach ( $secciones as $seccion ) {
	get_template_part( 'template-parts/secciones/' . $seccion );
}

get_footer();
