<?php
/**
 * Página: Contacto (slug "contacto"), diseño 1 tal cual el Word de propuestas.
 *
 * El contacto es solo por WhatsApp (acuerdo de la reunión): sin formulario ni correo.
 * - "Abrir chat" abre la conversación de WhatsApp con el taller.
 * - "Copiar número" copia el teléfono y confirma con "Número copiado".
 * - El mapa de Google se carga solo al tocar "Ver mapa de Google"; mientras tanto se ve
 *   el plano con el pin que late. "Cómo llegar" abre la ruta en Google Maps.
 * - Dirección y horario muestran [CONFIRMAR] mientras el taller no los confirme
 *   (lista 'por_confirmar' en inc/datos-taller.php).
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

$taller        = bullracing_datos_taller();
$por_confirmar = (array) ( $taller['por_confirmar'] ?? array() );
$redes         = array_filter( (array) $taller['redes'] );

// "Cra. 84 # 78-58, Bogotá" → "Cra. 84 # 78-58" y "Bogotá, Colombia".
$direccion  = (string) $taller['direccion'];
$partes     = array_map( 'trim', explode( ',', $direccion, 2 ) );
$calle      = $partes[0];
$ciudad     = ( $partes[1] ?? 'Bogotá' ) . ', Colombia';
$horario    = array_map( 'trim', explode( '·', (string) $taller['horario'], 2 ) );
$como_ir    = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( $direccion );
$mapa_url   = 'https://www.google.com/maps?q=' . rawurlencode( $direccion ) . '&output=embed';
$url_chat   = bullracing_whatsapp_url( __( 'Hola, quiero comunicarme con el taller.', 'bullracing' ) );
$instagram  = (string) ( $redes['Instagram'] ?? '' );
$usuario    = $instagram ? '@' . trim( (string) wp_parse_url( $instagram, PHP_URL_PATH ), '/' ) : '';

// Ícono de chat (el mismo de la burbuja flotante).
$icono_chat = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.4A8.4 8.4 0 1 1 21 11.5z"></path></svg>';

/** Etiqueta amarilla [CONFIRMAR] para datos que el taller aún no confirma. */
$confirmar = static function ( string $dato ) use ( $por_confirmar ): void {
	if ( in_array( $dato, $por_confirmar, true ) ) {
		echo '<span class="br-dato__confirmar">' . esc_html__( '[CONFIRMAR]', 'bullracing' ) . '</span>';
	}
};

get_header();

bullracing_componente(
	'banner-pagina',
	array(
		'ruta'      => __( 'Contacto', 'bullracing' ),
		'etiqueta'  => __( 'Contacto', 'bullracing' ),
		'titulo'    => __( 'Hablemos por', 'bullracing' ),
		'resaltado' => __( 'WhatsApp', 'bullracing' ),
		'subtitulo' => __( 'Cotizaciones, dudas de tu cita y estado de tu moto.', 'bullracing' ),
	)
);
?>
<section class="br-contenedor br-pcontacto">
	<div class="br-pcontacto__fila">
		<div class="br-chat">
			<span class="br-chat__fondo" aria-hidden="true"><?php echo $icono_chat; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fijo. ?></span>
			<p class="br-chat__canal"><span aria-hidden="true"></span><?php esc_html_e( 'Canal oficial de contacto', 'bullracing' ); ?></p>
			<h2 class="br-chat__titulo"><?php esc_html_e( 'Escríbenos', 'bullracing' ); ?><br><?php esc_html_e( 'al chat', 'bullracing' ); ?></h2>
			<p class="br-chat__numero"><?php echo esc_html( $taller['telefono'] ); ?></p>
			<ul class="br-chat__temas">
				<li><?php esc_html_e( 'Cotizar mecánica general', 'bullracing' ); ?></li>
				<li><?php esc_html_e( 'Estado de tu moto', 'bullracing' ); ?></li>
				<li><?php esc_html_e( 'Cambiar tu cita', 'bullracing' ); ?></li>
			</ul>
			<div class="br-chat__acciones">
				<a class="br-chat__abrir" href="<?php echo esc_url( $url_chat ); ?>" target="_blank" rel="noopener">
					<?php echo $icono_chat; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG fijo. ?>
					<?php esc_html_e( 'Abrir chat ↗', 'bullracing' ); ?>
				</a>
				<button type="button" class="br-chat__copiar" data-br-copiar="<?php echo esc_attr( $taller['telefono'] ); ?>"
					data-copiado="<?php esc_attr_e( 'Número copiado', 'bullracing' ); ?>">
					<?php esc_html_e( 'Copiar número', 'bullracing' ); ?>
				</button>
			</div>
		</div>

		<div class="br-pcontacto__datos">
			<div class="br-dato">
				<p class="br-dato__cabecera"><span><?php esc_html_e( 'Dirección', 'bullracing' ); ?></span><?php $confirmar( 'direccion' ); ?></p>
				<p class="br-dato__valor"><?php echo esc_html( $calle ); ?></p>
				<p class="br-dato__nota"><?php echo esc_html( $ciudad ); ?></p>
			</div>
			<div class="br-dato">
				<p class="br-dato__cabecera"><span><?php esc_html_e( 'Teléfono', 'bullracing' ); ?></span></p>
				<p class="br-dato__valor"><?php echo esc_html( $taller['telefono'] ); ?></p>
				<p class="br-dato__nota"><?php esc_html_e( 'Línea nacional · también es nuestro WhatsApp', 'bullracing' ); ?></p>
			</div>
			<div class="br-dato">
				<p class="br-dato__cabecera"><span><?php esc_html_e( 'Horario', 'bullracing' ); ?></span><?php $confirmar( 'horario' ); ?></p>
				<p class="br-dato__valor"><?php echo esc_html( $horario[0] ); ?></p>
				<p class="br-dato__nota"><?php echo esc_html( $horario[1] ?? '' ); ?></p>
			</div>
		</div>
	</div>

	<div class="br-pcontacto__fila br-pcontacto__fila--baja">
		<div class="br-plano" data-br-mapa="<?php echo esc_url( $mapa_url ); ?>">
			<span class="br-plano__calle br-plano__calle--a" aria-hidden="true"></span>
			<span class="br-plano__calle br-plano__calle--b" aria-hidden="true"></span>
			<span class="br-plano__pin" aria-hidden="true">
				<span class="br-plano__onda"></span>
				<svg width="36" height="46" viewBox="0 0 24 30"><path d="M12 0C5.4 0 0 5.2 0 11.6 0 20.3 12 30 12 30s12-9.7 12-18.4C24 5.2 18.6 0 12 0z" fill="currentColor"></path><circle cx="12" cy="11.5" r="4.5" fill="#fff"></circle></svg>
			</span>
			<p class="br-plano__etiqueta">
				<strong><?php echo esc_html( $taller['nombre'] ); ?></strong>
				<span><?php echo esc_html( $calle . ' · ' . ( $partes[1] ?? 'Bogotá' ) ); ?></span>
			</p>
			<button type="button" class="br-plano__ver" data-br-mapa-cargar><?php esc_html_e( 'Ver mapa de Google', 'bullracing' ); ?></button>
			<a class="br-plano__llegar" href="<?php echo esc_url( $como_ir ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Cómo llegar ↗', 'bullracing' ); ?></a>
		</div>

		<div class="br-sociales">
			<p class="br-sociales__cabecera">
				<span><?php esc_html_e( 'Síguenos', 'bullracing' ); ?></span>
				<?php if ( $usuario ) : ?>
					<span class="br-sociales__usuario"><?php echo esc_html( $usuario ); ?></span>
				<?php endif; ?>
			</p>
			<ul class="br-sociales__lista">
				<?php foreach ( array( 'Instagram', 'TikTok', 'Facebook' ) as $red ) : ?>
					<?php if ( ! empty( $redes[ $red ] ) ) : ?>
						<li><a href="<?php echo esc_url( $redes[ $red ] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( strtoupper( $red ) ); ?> ↗</a></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<p class="br-sociales__lema"><?php esc_html_e( '#RuedaLibre #RuedaSeguro', 'bullracing' ); ?></p>
		</div>
	</div>
</section>
<?php
get_footer();
