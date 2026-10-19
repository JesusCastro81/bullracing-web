<?php
/**
 * Página: Quiénes somos (slug "quienes-somos"), diseño 1 tal cual el Word de propuestas.
 *
 * - Historia, año de apertura y video: los entrega don Franz (inc/datos-taller.php).
 *   Mientras falten, se muestran los corchetes del diseño.
 * - Video: si 'video' es un archivo .mp4/.webm, se reproduce aquí mismo con play/pausa,
 *   la etiqueta "Reproduciendo" y la barra de progreso (lo que pide el Word). Si es un enlace
 *   de TikTok o Instagram, el recuadro abre el video en su red.
 * - Equipo: fotos, nombres y cargos reales los da el taller ('equipo' en datos-taller);
 *   mientras tanto, recuadros pendientes. Nunca nombres inventados.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller   = bullracing_datos_taller();
$desde    = (int) $taller['desde'];
$anios    = $desde ? (int) gmdate( 'Y' ) - $desde : 0;
$ano_txt  = $desde ? (string) $desde : '[AÑO]';
$video    = (string) $taller['video'];
$es_mp4   = (bool) preg_match( '/\.(mp4|webm)(\?.*)?$/i', $video );
$historia = (string) $taller['historia'];
$equipo   = array_slice( (array) ( $taller['equipo'] ?? array() ), 0, 3 );

// Áreas del equipo en el orden del diseño; si falta la persona, queda el recuadro pendiente.
$areas = array( __( 'Mantenimiento', 'bullracing' ), __( 'Diagnóstico', 'bullracing' ), __( 'Motolavado', 'bullracing' ) );

$servicios = array(
	array( __( 'Mantenimiento', 'bullracing' ), __( 'Combos de Básico a Pro para motos a gasolina y eléctricas, con precio según cilindraje o potencia.', 'bullracing' ) ),
	array( __( 'Diagnóstico', 'bullracing' ), __( 'Revisión de ingreso con orden de servicio: kilometraje, estado, luces y pastas antes de empezar.', 'bullracing' ) ),
	array( __( 'Motolavado', 'bullracing' ), __( 'Cuatro paquetes, de Clean Ride a Cero KM, con precio según el cilindraje de tu moto.', 'bullracing' ) ),
);

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
			array( 'valor' => $ano_txt, 'etiqueta' => __( 'Desde', 'bullracing' ) ),
			array( 'valor' => (string) $taller['clientes'], 'etiqueta' => __( 'Clientes', 'bullracing' ), 'destacado' => true ),
			array( 'valor' => 'BOG', 'etiqueta' => __( 'Bogotá', 'bullracing' ) ),
		),
	)
);
?>
<section class="br-contenedor br-historia">
	<div class="br-historia__texto">
		<p class="br-skin-etiqueta"><?php esc_html_e( 'Nuestra historia', 'bullracing' ); ?></p>
		<h2 class="br-historia__titulo">
			<?php echo esc_html( sprintf( __( 'Desde %s', 'bullracing' ), $ano_txt ) ); ?><br>
			<?php esc_html_e( 'en', 'bullracing' ); ?> <span><?php esc_html_e( 'Bogotá', 'bullracing' ); ?></span>
		</h2>
		<?php if ( $historia ) : ?>
			<?php echo wp_kses_post( wpautop( $historia ) ); ?>
		<?php else : ?>
			<p class="br-historia__parrafo"><?php esc_html_e( '[Historia del taller contada por Franz: cómo empezó, desde qué año, qué lo hace distinto.]', 'bullracing' ); ?></p>
			<p class="br-historia__parrafo br-historia__parrafo--suave"><?php esc_html_e( '[Cómo pasó de un taller de barrio a un centro técnico con mantenimiento, diagnóstico y motolavado. Qué tipo de motos atienden y qué lo hace volver a sus clientes.]', 'bullracing' ); ?></p>
		<?php endif; ?>
		<p class="br-historia__datos">
			<span><?php echo esc_html( 'DESDE ' . $ano_txt ); ?></span>
			<span><?php esc_html_e( 'BOGOTÁ', 'bullracing' ); ?></span>
			<span><?php esc_html_e( 'GASOLINA Y ELÉCTRICAS', 'bullracing' ); ?></span>
		</p>
	</div>

	<?php if ( $es_mp4 ) : ?>
		<div class="br-video" data-br-video>
			<video class="br-video__archivo" src="<?php echo esc_url( $video ); ?>" preload="metadata" playsinline
				poster="<?php echo esc_url( get_template_directory_uri() . '/assets/img/hero.jpg' ); ?>"></video>
			<span class="br-video__etiqueta" data-br-video-etiqueta data-reproduciendo="<?php esc_attr_e( '● REPRODUCIENDO', 'bullracing' ); ?>"><?php esc_html_e( 'VIDEO', 'bullracing' ); ?></span>
			<button type="button" class="br-video__boton" data-br-video-boton
				aria-label="<?php esc_attr_e( 'Reproducir video del taller', 'bullracing' ); ?>"
				data-pausar="<?php esc_attr_e( 'Pausar video del taller', 'bullracing' ); ?>">
				<span class="br-video__icono" aria-hidden="true"></span>
			</button>
			<span class="br-video__progreso" aria-hidden="true"><span data-br-video-barra></span></span>
		</div>
	<?php else : ?>
		<?php
		$etiqueta  = $video ? 'a' : 'div';
		$atributos = $video ? ' href="' . esc_url( $video ) . '" target="_blank" rel="noopener"' : '';
		?>
		<<?php echo $etiqueta . $atributos; // phpcs:ignore WordPress.Security.EscapeOutput -- valores fijos y URL escapada. ?> class="br-video br-video--pendiente">
			<img class="br-video__fondo" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/hero.jpg' ); ?>" alt="" loading="lazy">
			<span class="br-video__etiqueta"><?php echo $video ? esc_html__( 'VIDEO', 'bullracing' ) : esc_html__( 'VIDEO PENDIENTE', 'bullracing' ); ?></span>
			<span class="br-video__centro">
				<span class="br-video__boton" aria-hidden="true"><span class="br-video__icono"></span></span>
				<?php echo $video ? esc_html__( 'Ver video del taller', 'bullracing' ) : esc_html__( 'Video corto del taller', 'bullracing' ); ?>
				<small><?php esc_html_e( '(tomado de su TikTok o Instagram)', 'bullracing' ); ?></small>
			</span>
		</<?php echo $etiqueta; // phpcs:ignore WordPress.Security.EscapeOutput -- 'a' o 'div'. ?>>
	<?php endif; ?>
</section>

<section class="br-skin-rayas">
	<div class="br-contenedor br-hacemos">
		<div class="br-hacemos__intro">
			<p class="br-skin-etiqueta"><?php esc_html_e( 'Lo que hacemos', 'bullracing' ); ?></p>
			<h2 class="br-hacemos__titulo"><?php esc_html_e( 'Tres servicios,', 'bullracing' ); ?><br><?php esc_html_e( 'un mismo cuidado', 'bullracing' ); ?></h2>
			<p><?php esc_html_e( 'Cada moto que entra al taller tiene su orden de servicio. Sabes qué se le hizo, cuánto costó y cuándo te la entregamos.', 'bullracing' ); ?></p>
		</div>
		<ul class="br-hacemos__tarjetas">
			<?php foreach ( $servicios as $i => $servicio ) : ?>
				<li class="br-servicio">
					<span class="br-servicio__numero" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<h3 class="br-servicio__nombre"><?php echo esc_html( $servicio[0] ); ?></h3>
					<p><?php echo esc_html( $servicio[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="br-contenedor br-equipo">
	<figure class="br-equipo__taller">
		<span class="br-equipo__pendiente"><?php esc_html_e( 'Foto pendiente', 'bullracing' ); ?></span>
		<figcaption>
			<strong><?php esc_html_e( 'Foto del taller · pendiente', 'bullracing' ); ?></strong>
			<span><?php esc_html_e( 'La entrega el taller: fachada o rampa de trabajo.', 'bullracing' ); ?></span>
		</figcaption>
	</figure>

	<?php foreach ( $areas as $i => $area ) : ?>
		<?php $persona = $equipo[ $i ] ?? array(); ?>
		<figure class="br-persona">
			<?php if ( ! empty( $persona['foto'] ) ) : ?>
				<img src="<?php echo esc_url( $persona['foto'] ); ?>" alt="<?php echo esc_attr( $persona['nombre'] ?? '' ); ?>" loading="lazy">
			<?php else : ?>
				<span class="br-persona__foto"><?php esc_html_e( 'Foto del equipo · pendiente', 'bullracing' ); ?></span>
			<?php endif; ?>
			<figcaption>
				<strong><?php echo esc_html( $persona['nombre'] ?? __( '[Nombre]', 'bullracing' ) ); ?></strong>
				<span><?php echo esc_html( strtoupper( ( $persona['cargo'] ?? __( '[Cargo]', 'bullracing' ) ) . ' · ' . $area ) ); ?></span>
			</figcaption>
		</figure>
	<?php endforeach; ?>

	<div class="br-equipo__valoracion">
		<p class="br-equipo__etiqueta"><?php esc_html_e( 'Valoración', 'bullracing' ); ?></p>
		<div>
			<p class="br-equipo__estrellas" aria-hidden="true">★★★★★</p>
			<p class="br-equipo__cifra"><?php echo esc_html( number_format_i18n( (int) $taller['clientes'] ) ); ?></p>
			<p class="br-equipo__etiqueta"><?php esc_html_e( 'Clientes satisfechos', 'bullracing' ); ?></p>
		</div>
		<p class="br-equipo__nota"><?php esc_html_e( 'Reseñas de Google y TikTok.', 'bullracing' ); ?></p>
	</div>
</section>
<?php
get_footer();
