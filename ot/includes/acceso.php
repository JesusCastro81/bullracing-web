<?php
/**
 * Control de acceso de la OT (agregado al proyecto; no es parte de la OT original de John).
 *
 * Solo entran usuarios de WordPress con el permiso "usar la OT":
 * rol "Recepción de motos" o administrador. El permiso se revisa en CADA acción
 * (abrir, buscar, guardar, PDF), así que si don Franz le quita el rol a alguien, pierde el acceso al instante.
 *
 * Se incluye al principio de cada archivo PHP de la OT.
 */

require_once dirname( __DIR__, 2 ) . '/wp-load.php';

/**
 * @param bool $es_interno true en los archivos de ajax/ (responden JSON); false en páginas (index, PDF).
 */
function bullracing_ot_exigir_acceso( bool $es_interno = false ): void {
	$responder = static function ( int $codigo, string $mensaje ) use ( $es_interno ): void {
		if ( $es_interno ) {
			http_response_code( $codigo );
			header( 'Content-Type: application/json' );
			echo wp_json_encode( array( 'success' => false, 'msg' => $mensaje ) );
			exit;
		}
		// Página: el mensaje y, si hay alguien conectado, un enlace para cerrar su sesión y entrar con otro usuario
		// (útil en un computador compartido del taller).
		$html = '<p>' . esc_html( $mensaje ) . '</p>';
		if ( is_user_logged_in() ) {
			$html .= '<p><a href="' . esc_url( wp_logout_url( home_url( '/ot/' ) ) ) . '">' . esc_html__( 'Entrar con otro usuario', 'bullracing-core' ) . '</a></p>';
		}
		wp_die( $html, esc_html__( 'Orden de Servicio', 'bullracing-core' ), array( 'response' => $codigo ) );
	};

	// Sin sesión: la página va al inicio de sesión de WordPress y vuelve a la OT; los archivos internos responden 401.
	if ( ! is_user_logged_in() ) {
		if ( ! $es_interno ) {
			$aqui = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			wp_safe_redirect( wp_login_url( $aqui ) );
			exit;
		}
		$responder( 401, 'Su sesión terminó. Recargue la página y vuelva a entrar.' );
	}

	if ( ! current_user_can( 'bullracing_usar_ot' ) ) {
		$responder( 403, 'Su usuario no tiene permiso para usar la Orden de Servicio.' );
	}

	// Protección CSRF: no aceptar envíos que vengan de otra página web.
	$origen = $_SERVER['HTTP_ORIGIN'] ?? '';
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && $origen && wp_parse_url( $origen, PHP_URL_HOST ) !== wp_parse_url( 'http://' . $_SERVER['HTTP_HOST'], PHP_URL_HOST ) ) {
		$responder( 403, 'Solicitud no permitida.' );
	}

	nocache_headers(); // Que el navegador no guarde la OT con datos de clientes.
}
