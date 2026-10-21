<?php
/**
 * Texto legal de Términos y condiciones.
 *
 * Copiado TAL CUAL del sitio actual (página "Ayuda e información", /help-info/).
 * Solo se ordenó en secciones para armar el índice; no se cambió ninguna
 * palabra del contenido. Cualquier ajuste al texto lo decide el taller.
 *
 * @package BullRacing
 */

defined( 'ABSPATH' ) || exit;

/**
 * Datos de la página de términos.
 *
 * @return array{actualizado: string, empresa: array<string, string>, secciones: array<int, array{id: string, titulo: string, parrafos: string[]}>}
 */
function bullracing_datos_terminos(): array {
	$datos = array(
		// Fecha de la última versión del texto (la confirma el taller). Vacía = se muestra [FECHA].
		'actualizado' => '',

		// Datos de la empresa tal como aparecen en el texto legal.
		'empresa'     => array(
			'NIT'            => '901785575-1',
			'Notificaciones' => 'CR 84 78 – 58, Bogotá',
			'Línea nacional' => '(57) 3115495983',
		),

		'secciones'   => array(
			array(
				'id'       => 'uso-web',
				'titulo'   => 'Términos y condiciones de uso web',
				'parrafos' => array(
					'Bienvenido al sitio web www.motosbullracing.com (en adelante “el Sitio Web” o “el Sitio”) identificada con NIT 901785575-1, su dirección de notificación es: CR 84 78 – 58, de la Ciudad de Bogotá (Colombia), línea nacional (57) 3115495983, correo electrónico tienda@motosbulracing.com.co, quienes en adelante y para efectos del presente documento se denominarán “Motos Bull Racing” o “la empresa”. El Sitio Web ha sido abierto para el acceso y la utilización de diversos servicios y contenidos puestos a disposición de los usuarios.',
					'El derecho para utilizar este Sitio y su contenido está garantizado si usted acepta cumplir los Términos y Condiciones de Uso (en adelante, los “Términos y Condiciones”) e igualmente todos los avisos, reglamentos de uso e instrucciones puestos en conocimiento del usuario por el Sitio, que completan lo previsto en estos Términos y Condiciones en cuanto no se opongan a ellas.',
				),
			),
			array(
				'id'       => 'generalidades',
				'titulo'   => 'Generalidades',
				'parrafos' => array(
					'Al ingresar, revisar y comprar en este sitio web usted se compromete a leer, informarse y cumplir los términos y condiciones de uso, además se obliga a respetar las políticas de privacidad. De conformidad con la legislación colombiana sobre el tema, contenido, productos y ofertas que usted encuentra en el sitio, aplican únicamente para Colombia.',
					'Le informamos que se podrá realizar modificaciones en cualquier momento de los términos y condiciones aquí descritas. En todo caso, sí estos términos y condiciones varían lo informaremos, para que usted los conozca y acepte nuevamente.',
				),
			),
			array(
				'id'       => 'responsabilidad',
				'titulo'   => 'Exención y limitación de la responsabilidad',
				'parrafos' => array(
					'Motos Bull Racing, ni sus accionistas, representantes, funcionarios, directores, empleados, agentes, proveedores de contenidos de terceros, diseñadores, contratistas, distribuidores, vendedores, patrocinadores, concedentes, Socios Comerciales y demás (en adelante “Relacionados”) garantizan que el uso del Sitio Web no sufrirá interrupciones ni contendrá errores. Se resalta que ni la Compañía o sus relacionados garantizan la precisión ni la integridad del contenido ofrecido en el Sitio Web.',
					'Además, Motos Bull Racing, declara que el contenido provisto en el Sitio Web no es aplicable ni de uso apropiado en lugares fuera del territorio colombiano. Bajo ninguna circunstancia la Compañía ni sus relacionados serán responsables de ningún daño directo, indirecto, imprevisto, especial ni emergente que se genere, ya sea por el uso o falta de capacidad para usar el Sitio Web, incluidos de forma no exclusiva, los daños que surjan a raíz de su confianza en la información obtenida en el Sitio Web que ocasione errores, omisiones, interrupciones, eliminación o corrupción de archivos, virus, demoras en la operación o transmisión, o cualquier otro tipo de error en el funcionamiento.',
					'Se advierte a los usuarios que la información del sitio puede contener errores o inexactitudes, no estar completa o actualizada. Por ello, se reserva el derecho de corregir cualquier error, omisión o inexactitud, cambiar o actualizar la misma en cualquier momento y sin previo aviso.',
				),
			),
			array(
				'id'       => 'propiedad-intelectual',
				'titulo'   => 'Derechos de Propiedad intelectual e industrial',
				'parrafos' => array(
					'La información, documentos (imágenes, vídeos, herramientas, gráficos y demás), marcas, nombres, logos y demás material que se encuentra en esta página web, está protegido conforme a lo establecido en la legislación colombiana e internacional sobre derechos de autor, propiedad intelectual e industrial; por lo tanto, su uso está exclusivamente delimitado a fines personales de revisión, compra y consulta. De tal suerte que, el uso no autorizado que infrinja las leyes colombianas, a través de su venta, distribución, copia, modificación o adaptación o cualquier otra conducta prohibida, será sancionado de acuerdo con lo establecido por las normas aplicables.  Así las cosas, no podrán entenderse por el hecho de la publicación de los contenidos que se encuentran en el sitio, que se concede algún tipo de licencia de uso de marca, derechos de autor o propiedad intelectual a quien visita, consulta o realiza compras a través de este sitio web.',
				),
			),
			array(
				'id'       => 'uso-autorizado',
				'titulo'   => 'Uso autorizado',
				'parrafos' => array(
					'La página web podrá ser utilizada para revisión, compra y consulta de productos importados y/o nacionales que Motos Bull Racing tendrá disponible para la entrega a los usuarios, así como la información de usos y características de los productos y servicios ofrecidos, que están diseñados únicamente con fines informativos del usuario.',
				),
			),
			array(
				'id'       => 'usos-prohibidos',
				'titulo'   => 'Usos prohibidos',
				'parrafos' => array(
					'La información publicada en el sitio no podrá ser objeto de comercialización, distribución, copia o modificación. No está autorizado el acceso a cuentas o información confidencial de los usuarios, así como la violación de la red y/o servidores o cualquier otro acto que atente contra la seguridad de las transacciones y de la página web.',
					'La información que el usuario registre debe ser veraz y completa; se prohíbe todo acto de suplantación de identidad y/o información incorrecta que no corresponda a la persona natural o jurídica que hace uso de la página web.',
					'En todo caso, únicamente se permitirá a personas mayores de edad, debidamente identificadas con cédula de ciudadanía o cédula de extranjería registrarse y efectuar operaciones de compra a través de esta página.',
				),
			),
			array(
				'id'       => 'informacion-usuarios',
				'titulo'   => 'Información de los usuarios',
				'parrafos' => array(
					'Para realizar pagos online será necesario el registro por parte de los usuarios en el sitio, para tal efecto se requiere de información personal y confidencial. Esta información será de uso exclusivo de Motos Bull Racing y no será revelada a terceros, salvo la información que requiera PAYCO Colombia para efectos de procesar las transacciones electrónicas de pago.',
					'Buscando la seguridad en las transacciones, queda expresamente prohibido a los usuarios el compartir con terceros su contraseña o el password; pues dicha información es de uso exclusivo del usuario y no se podrá utilizar para fines no autorizados.',
					'Información adicional incluida por los usuarios. Los datos referidos en estos términos y condiciones tendrán como finalidad validar las órdenes de compra, mejorar la labor de información y comercialización de los productos y servicios prestados por las empresas. Sólo podrán ser entregados a las empresas filiales o relacionadas con Motos Bull Racing., en ningún caso serán traspasados a terceros. Dicha entrega será revocable por el usuario.',
					'Sumado a ello, el comprador de un producto tendrá la posibilidad de incluir comentarios o reseñas del artículo, para ello deberá identificarse, Motos Bull Racing no se hace responsable por el contenido, objetividad o expresiones usadas por los usuarios en dichos comentarios, sin perjuicio que puedan ser retirados de la página sí resultan ofensivas, denigrantes o discriminatorias.',
				),
			),
		),
	);

	/**
	 * Permite reemplazar el texto legal sin tocar el tema (por ejemplo, cuando el taller entregue una versión nueva).
	 */
	return (array) apply_filters( 'bullracing_datos_terminos', $datos );
}
