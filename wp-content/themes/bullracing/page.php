<?php
/**
 * Páginas genéricas.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) {
	the_post();
	?>
	<article class="br-pagina">
		<?php the_title( '<h1 class="br-pagina__titulo">', '</h1>' ); ?>
		<div class="br-pagina__contenido"><?php the_content(); ?></div>
	</article>
	<?php
}

get_footer();
