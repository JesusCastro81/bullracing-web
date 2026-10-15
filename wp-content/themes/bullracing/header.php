<?php
/**
 * Encabezado: logo, menú principal y botón de agendar.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller = bullracing_datos_taller();
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

<a class="screen-reader-text br-saltar" href="#contenido"><?php esc_html_e( 'Ir al contenido', 'bullracing' ); ?></a>

<header class="br-header">
	<div class="br-contenedor br-header__fila">
		<div class="br-header__logo">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); /* WordPress ya lo envuelve en su propio enlace al inicio. */ ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo.png' ); ?>"
						alt="<?php echo esc_attr( $taller['nombre'] ); ?>" width="56" height="56">
				</a>
			<?php endif; ?>
		</div>

		<input type="checkbox" id="br-menu-toggle" class="br-header__toggle">
		<label for="br-menu-toggle" class="br-header__hamburguesa" aria-label="<?php esc_attr_e( 'Abrir menú', 'bullracing' ); ?>">
			<span aria-hidden="true">☰</span> <?php esc_html_e( 'Menú', 'bullracing' ); ?>
		</label>

		<nav class="br-header__nav" aria-label="<?php esc_attr_e( 'Menú principal', 'bullracing' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'principal',
					'container'      => false,
					'menu_class'     => 'br-menu',
					'fallback_cb'    => 'bullracing_menu_respaldo',
				)
			);
			?>
		</nav>
	</div>
</header>

<main id="contenido" class="br-main">
