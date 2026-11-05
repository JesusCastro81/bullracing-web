<?php
/**
 * Quiénes somos · 03 Equipo: foto del taller, una persona por área y la valoración.
 *
 * - Fotos, nombres y cargos reales los da el taller ('equipo' en inc/datos-taller.php);
 *   mientras tanto, recuadros pendientes. Nunca nombres inventados.
 * - Las áreas son los mismos servicios del taller ('servicios' en inc/datos-taller.php).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller      = bullracing_datos_taller();
$equipo      = array_slice( (array) ( $taller['equipo'] ?? array() ), 0, 3 );
$areas       = wp_list_pluck( (array) $taller['servicios'], 'nombre' );
$foto_taller = bullracing_foto( 'equipo-taller.jpg' );
?>
<section class="br-contenedor br-equipo">
	<figure class="br-equipo__taller<?php echo $foto_taller ? ' br-equipo__taller--foto' : ''; ?>">
		<?php if ( $foto_taller ) : ?>
			<img src="<?php echo esc_url( $foto_taller ); ?>" alt="<?php esc_attr_e( 'El taller Motos Bull Racing', 'bullracing' ); ?>" loading="lazy">
		<?php else : ?>
			<span class="br-equipo__pendiente"><?php esc_html_e( 'Foto pendiente', 'bullracing' ); ?></span>
			<figcaption>
				<strong><?php esc_html_e( 'Foto del taller · pendiente', 'bullracing' ); ?></strong>
				<span><?php esc_html_e( 'La entrega el taller: fachada o rampa de trabajo.', 'bullracing' ); ?></span>
			</figcaption>
		<?php endif; ?>
	</figure>

	<?php foreach ( $areas as $i => $area ) : ?>
		<?php
		$persona = $equipo[ $i ] ?? array();
		// Foto: la de inc/datos-taller.php o, si no hay, equipo-1.jpg, equipo-2.jpg, equipo-3.jpg de assets/img/taller/.
		$foto = $persona['foto'] ?? bullracing_foto( 'equipo-' . ( $i + 1 ) . '.jpg' );
		?>
		<figure class="br-persona">
			<?php if ( $foto ) : ?>
				<img src="<?php echo esc_url( $foto ); ?>" alt="<?php echo esc_attr( $persona['nombre'] ?? '' ); ?>" loading="lazy">
			<?php else : ?>
				<span class="br-persona__foto"><?php esc_html_e( 'Foto del equipo · pendiente', 'bullracing' ); ?></span>
			<?php endif; ?>
			<figcaption>
				<strong><?php echo esc_html( $persona['nombre'] ?? __( '[Nombre]', 'bullracing' ) ); ?></strong>
				<span><?php echo esc_html( mb_strtoupper( ( $persona['cargo'] ?? __( '[Cargo]', 'bullracing' ) ) . ' · ' . $area ) ); ?></span>
			</figcaption>
		</figure>
	<?php endforeach; ?>

	<div class="br-equipo__valoracion">
		<p class="br-equipo__etiqueta"><?php esc_html_e( 'Valoración', 'bullracing' ); ?></p>
		<div>
			<p class="br-equipo__estrellas" aria-hidden="true"><?php echo esc_html( bullracing_estrellas( (int) $taller['calificacion'] ) ); ?></p>
			<p class="br-equipo__cifra"><?php echo esc_html( $taller['clientes_fmt'] ); ?></p>
			<p class="br-equipo__etiqueta"><?php echo esc_html( $taller['contador']['texto'] ); ?></p>
		</div>
		<p class="br-equipo__nota"><?php echo esc_html( sprintf( __( 'Calificación en Google: %s de 5.', 'bullracing' ), $taller['promedio'] ) ); ?></p>
	</div>
</section>
