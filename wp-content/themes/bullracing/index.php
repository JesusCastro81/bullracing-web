<?php
/**
 * Plantilla por defecto (obligatoria en todo tema de WordPress).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		the_title( '<h1>', '</h1>' );
		the_content();
	}
}

get_footer();
