<?php
/**
 * Encabezado: logo + menú principal.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="br-header">
	<a class="br-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( has_custom_logo() ) {
			the_custom_logo();
		} else {
			bloginfo( 'name' );
		}
		?>
	</a>

	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'principal',
			'container'      => 'nav',
			'container_class'=> 'br-header__nav',
			'fallback_cb'    => false,
		)
	);
	?>
</header>

<main id="contenido" class="br-main">
