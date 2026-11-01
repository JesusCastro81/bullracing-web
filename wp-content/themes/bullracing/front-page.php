<?php
/**
 * Inicio (landing): orquestador.
 * Cada sección vive en template-parts/inicio/, numerada en el orden de la página.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/inicio/01-hero' );        // Agendar y cotizar por WhatsApp.
get_template_part( 'template-parts/inicio/02-taller' );      // Así entra tu moto (4 pasos).
get_template_part( 'template-parts/inicio/03-planes' );      // Planes de mantenimiento (precios desde bullracing-core).
get_template_part( 'template-parts/inicio/04-motolavado' );  // Paquetes de motolavado (solo motos a gasolina).
get_template_part( 'template-parts/inicio/05-valoracion' );  // Estrellas y reseñas de clientes.
get_template_part( 'template-parts/inicio/06-nosotros' );    // Resumen de Quiénes somos.
get_template_part( 'template-parts/inicio/07-contacto' );    // Fotos, horario, mapa y redes.

get_footer();
