<?php
/**
 * Página: Contacto (slug "contacto").
 * WordPress la usa automáticamente para la página con ese slug.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) {
	the_post();
	?>
	<article class="br-pagina br-pagina--contacto">
		<?php the_title( '<h1 class="br-pagina__titulo">', '</h1>' ); ?>
		<div class="br-pagina__contenido"><?php the_content(); ?></div>
	</article>
	<?php
}

get_footer();
