<?php
require_once __DIR__ . '/includes/acceso.php'; // Solo usuarios autorizados (Recepción de motos).
bullracing_ot_exigir_acceso();
require_once './includes/config.php';
require_once './includes/fpdf.php';

// Función para arreglar tildes y ñ (UTF-8 a ISO-8859-1)
function t($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

$numero = isset($_GET['id']) ? $_GET['id'] : '';
if (is_numeric($numero)) {
    $numero = str_pad($numero, 4, '0', STR_PAD_LEFT);
}

if (!$numero) {
    die('Falta número de orden');
}

$stmt = $pdo->prepare("SELECT * FROM ordenes_servicio WHERE numero_orden = ?");
$stmt->execute([$numero]);
$orden = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$orden) {
    die('Orden no encontrada');
}

// Crear PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// ====== GENERAR IMAGEN DEL CROQUIS======
$croquis_img = '';
if (!empty($orden['marcas_croquis']) && $orden['marcas_croquis'] != '[]') {
    $marcas = json_decode($orden['marcas_croquis'], true);
    $moto_path = './assets/images/moto1.jpg';
    if (file_exists($moto_path)) {
        $img = imagecreatefromjpeg($moto_path);
        
        // Redimensionar a 600px de ancho para que no pese tanto
        $new_width = 600;
        $new_height = (int)round(imagesy($img) * ($new_width / imagesx($img)));
        $img = imagescale($img, $new_width, $new_height);
        
        $w = imagesx($img);
        $h = imagesy($img);
        $red = imagecolorallocate($img, 255, 0, 0);
        
        // Engrosar las líneas de las X
        imagesetthickness($img, 2);
        
        $size = max($w, $h) * 0.05;
        
        foreach ($marcas as $m) {
            $x = $m['x'] * $w;
            $y = $m['y'] * $h;
            imageline($img, $x - $size/2, $y - $size/2, $x + $size/2, $y + $size/2, $red);
            imageline($img, $x + $size/2, $y - $size/2, $x - $size/2, $y + $size/2, $red);
        }
        
        $croquis_img = 'temp_croquis.png';
        imagepng($img, $croquis_img);
        imagedestroy($img);
    }
}

// ====== ENCABEZADO (LOGO IZQUIERDA, TITULO CENTRO, NÚMERO DERECHA) ======
$header_height = 45; // Altura del header
$pdf->SetFillColor(0, 0, 0);
$pdf->Rect(0, 0, 210, $header_height, 'F');

// 1. Logo (Izquierda)
$pdf->Image('./assets/images/logoBR.png', 10, 10, 25, 25);

// 2. Título y lema
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetXY(0, 18);
$pdf->Cell(190, 8, t('MOTOS BULLRACING'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(0, 25);
$pdf->Cell(190, 5, t('Rueda libre, Rueda seguro'), 0, 1, 'C');

// 3. Bloque DERECHO: "ORDEN DE SERVICIO"
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(140, 15); 
$pdf->Cell(60, 8, t('ORDEN DE SERVICIO'), 0, 1, 'R'); 

// Número en caja blanca (Debajo de "ORDEN DE SERVICIO")
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 14);
$num_width = 40;
$centro = 177;
$num_x = $centro - ($num_width / 2);
$pdf->SetXY($num_x, 22);
$pdf->Cell($num_width, 8, '# ' . $orden['numero_orden'], 0, 1, 'C', true);

// Restaurar colores y bajar a la siguiente sección
$pdf->SetTextColor(0, 0, 0);
$pdf->SetY($header_height + 5);

// ====== FUNCIÓN PARA TÍTULOS DE SECCIÓN (ROJO CON ICONO) ======
function sectionLine($pdf, $titulo) {
    $y_start = $pdf->GetY();
    
    // Título en rojo
    $pdf->SetTextColor(200, 0, 0);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetX(10);
    $pdf->Cell(0, 8, t($titulo), 0, 1);
    
    // Dibujar la línea negra separadora
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.3);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(3);
    
    // Restaurar color negro
    $pdf->SetTextColor(0, 0, 0);
}

// ====== FUNCIÓN PARA SUBTÍTULOS (ROJO CON LÍNEA ROJA) ======
function subSectionLine($pdf, $titulo) {
    $pdf->SetTextColor(200, 0, 0);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetX(20);
    $pdf->Cell(0, 7, t($titulo), 0, 1);
    $pdf->SetDrawColor(200, 0, 0);
    $pdf->SetLineWidth(0.3);
    $pdf->Line(20, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(2);
    $pdf->SetTextColor(0, 0, 0);
}

// ====== DATOS DEL CLIENTE (2 Columnas) ======
sectionLine($pdf, 'DATOS DEL CLIENTE');
$x_center = 20;

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Nombre:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, t($orden['nombre']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Documento:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t($orden['tipo_documento'] . ' ' . $orden['documento']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Teléfono:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, t($orden['telefono']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Correo:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t($orden['correo']), 0, 1, 'L');

$pdf->Ln(8);

// ====== DATOS DE LA MOTOCICLETA ======
sectionLine($pdf, 'DATOS DE LA MOTOCICLETA');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Placa:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, t($orden['placa']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Marca:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t($orden['marca']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Modelo:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, t($orden['modelo']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Línea:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t($orden['linea']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('KM:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, t($orden['km']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Cilindraje:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t($orden['cilindraje']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Técnico:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, t($orden['tecnico']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Cargo:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t('Técnico'), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Vence Tecno:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, t($orden['vence_tecnico']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Vence SOAT:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t($orden['vence_soat']), 0, 1, 'L');

$pdf->Ln(8);

// ====== FECHAS Y CLASE ======
sectionLine($pdf, 'FECHAS Y CLASE DE TRABAJO');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Fecha Ingreso:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, t($orden['fecha_ingreso']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Hora Ingreso:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t($orden['hora_ingreso']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Fecha Salida:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, t($orden['fecha_salida']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Hora Salida:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t($orden['hora_salida']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Clase de Trabajo:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t(str_replace(',', ' | ', $orden['clase_trabajo'])), 0, 1, 'L');

$pdf->Ln(8);

// ====== ELEMENTOS ENTREGADOS ======
sectionLine($pdf, 'ELEMENTOS ENTREGADOS');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Tarjeta:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(15, 8, t($orden['tarjeta_propiedad']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Llaves:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(15, 8, t($orden['llaves']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Herramienta:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t($orden['herramienta']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Casco:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(15, 8, t($orden['casco']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Guantes:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(15, 8, t($orden['guantes']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Alarma:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, t($orden['alarma']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Maletero:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(15, 8, t($orden['maletero']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(35, 8, t('Parrilla:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(15, 8, t($orden['parrilla']), 0, 0, 'L');

$pdf->Ln(8);

sectionLine($pdf, 'COMBUSTIBLE');
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX($x_center);
$pdf->Cell(35, 8, t('Nivel de Combustible:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(35, 8, t($orden['combustible']), 0, 1, 'C');
$pdf->Ln(8);

// ====== CROQUIS DE DAÑOS (Con salto de página automático) ======
if ($croquis_img) {
    // Obtener dimensiones de la imagen temporal
    list($w_img, $h_img) = getimagesize($croquis_img);
    
    // Ancho en el PDF
    $ancho_pdf = 120;
    $alto_pdf = $h_img * ($ancho_pdf / $w_img);
    
    $espacio_requerido = 15 + $alto_pdf + 10;
    
    if ($pdf->GetY() + $espacio_requerido > 260) {
        $pdf->AddPage();
    }
    
    sectionLine($pdf, 'CROQUIS DE DAÑOS');
    
    // Centrar horizontalmente
    $x_img = (210 - $ancho_pdf) / 2;
    $y_img = $pdf->GetY();
    
    // Dibujar la imagen completa
    $pdf->Image($croquis_img, $x_img, $y_img, $ancho_pdf, $alto_pdf);
    
    // Bajar justo después de la imagen (más un margen)
    $pdf->SetY($y_img + $alto_pdf + 10);
}

// ====== REVISIÓN DE INGRESO (Con subtítulos rojos, alineación central y observaciones más grandes) ======
sectionLine($pdf, 'REVISIÓN DE INGRESO');

// Función auxiliar para imprimir una subcategoría en 2 columnas centradas
function printItemsFPDF($pdf, $items, $x_center) {
    $mitad = ceil(count($items) / 2);
    $col_izq = array_slice($items, 0, $mitad);
    $col_der = array_slice($items, $mitad);
    
    for ($i = 0; $i < $mitad; $i++) {
        // Columna izquierda
        $pdf->SetX($x_center);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(35, 7, t($col_izq[$i][0]), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(15, 7, t($col_izq[$i][1]), 0, 0, 'L');
        $obs_izq = trim($col_izq[$i][2]);
        if ($obs_izq === '') { $obs_izq = '-'; }
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(45, 7, t($obs_izq), 0, 0, 'L');
        
        // Columna derecha (si existe)
        if (isset($col_der[$i])) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(35, 7, t($col_der[$i][0]), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 7, t($col_der[$i][1]), 0, 0, 'L');
            $obs_der = trim($col_der[$i][2]);
            if ($obs_der === '') { $obs_der = '-'; }
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(45, 7, t($obs_der), 0, 0, 'L');
        }
        $pdf->Ln();
    }
    $pdf->Ln(2);
}

// --- ILUMINARIAS ---
subSectionLine($pdf, 'ILUMINARIAS');

$iluminarias = [
    ['Stop', $orden['stop_estado'], $orden['stop_obs']],
    ['Direccionales', $orden['direccionales_estado'], $orden['direccionales_obs']],
    ['Exploradoras', $orden['exploradoras_estado'], $orden['exploradoras_obs']],
    ['Alta', $orden['alta_estado'], $orden['alta_obs']],
    ['Baja', $orden['baja_estado'], $orden['baja_obs']],
    ['Placa', $orden['placa_luz_estado'], $orden['placa_luz_obs']]
];
printItemsFPDF($pdf, $iluminarias, $x_center);

// --- PLASTICOS ---
subSectionLine($pdf, 'PLASTICOS');

$plasticos = [
    ['Lateral Derecho', $orden['lat_der_estado'], $orden['lat_der_obs']],
    ['Lateral Izquierdo', $orden['lat_izq_estado'], $orden['lat_izq_obs']],
    ['Carenaje', $orden['carenaje_estado'], $orden['carenaje_obs']],
    ['Guardabarros Del.', $orden['gb_del_estado'], $orden['gb_del_obs']],
    ['Guardabarros Tras.', $orden['gb_tras_estado'], $orden['gb_tras_obs']],
    ['Protector Piñón', $orden['prot_pinon_estado'], $orden['prot_pinon_obs']],
    ['Cúpula', $orden['cupula_estado'], $orden['cupula_obs']],
    ['Quilla', $orden['quilla_estado'], $orden['quilla_obs']],
    ['Guardacadena', $orden['guardacadena_estado'], $orden['guardacadena_obs']],
    ['Cola Derecha', $orden['cola_der_estado'], $orden['cola_der_obs']],
    ['Cola Izquierda', $orden['cola_izq_estado'], $orden['cola_izq_obs']],
    ['Farola', $orden['farola_estado'], $orden['farola_obs']]
];
printItemsFPDF($pdf, $plasticos, $x_center);

// PERIFÉRICOS
subSectionLine($pdf, 'PERIFÉRICOS');

$perifericos = [
    ['Espejo Derecho', $orden['esp_der_estado'], $orden['esp_der_obs']],
    ['Espejo Izquierdo', $orden['esp_izq_estado'], $orden['esp_izq_obs']],
    ['Calapié Derecho', $orden['calap_der_estado'], $orden['calap_der_obs']],
    ['Calapié Izquierdo', $orden['calap_izq_estado'], $orden['calap_izq_obs']],
    ['Posapiés Derecho', $orden['pos_der_estado'], $orden['pos_der_obs']],
    ['Posapiés Izquierdo', $orden['pos_izq_estado'], $orden['pos_izq_obs']],
    ['Tapa Tanque', $orden['tapa_tanque_estado'], $orden['tapa_tanque_obs']],
    ['Pito', $orden['pito_estado'], $orden['pito_obs']],
    ['Batería', $orden['bateria_estado'], $orden['bateria_obs']],
    ['Tacómetro', $orden['tacometro_estado'], $orden['tacometro_obs']]
];
printItemsFPDF($pdf, $perifericos, $x_center);

$pdf->Ln(10);

// OBSERVACIONES
sectionLine($pdf, 'OBSERVACIONES GENERALES');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 6, t($orden['observaciones_generales']), 0);
$pdf->Ln(12);

// FIRMAS
sectionLine($pdf, 'FIRMAS');

$firma1 = $orden['firma_cliente'];
$firma2 = $orden['firma_taller'];

if (strpos($firma1, 'data:image') === 0) {
    $firma1 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $firma1));
    file_put_contents('temp_firma1.png', $firma1);
    $firma1 = 'temp_firma1.png';
}
if (strpos($firma2, 'data:image') === 0) {
    $firma2 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $firma2));
    file_put_contents('temp_firma2.png', $firma2);
    $firma2 = 'temp_firma2.png';
}

// Definimos la mitad de la página y el tamaño de las imágenes
$half_width = 95;
$img_w = 60;
$img_h = 20;

//TÍTULOS
$pdf->SetFont('Arial', 'B', 11);
$y_titulo = $pdf->GetY();

$pdf->SetXY(10, $y_titulo);
$pdf->Cell($half_width, 10, t('Firma Cliente'), 0, 0, 'C');

$pdf->SetXY(105, $y_titulo);
$pdf->Cell($half_width, 10, t('Firma Recibido por (Taller)'), 0, 1, 'C');

//FIRMAS
$y_img = $pdf->GetY();

$pdf->SetXY(57.5 - ($img_w / 2), $y_img);
$pdf->Image($firma1, $pdf->GetX(), $pdf->GetY(), $img_w, $img_h);

$pdf->SetXY(152.5 - ($img_w / 2), $y_img);
$pdf->Image($firma2, $pdf->GetX(), $pdf->GetY(), $img_w, $img_h);

// Bajamos la altura después de las imágenes
$pdf->SetY($y_img + $img_h + 5);

// DATOS DEBAJO DE LAS FIRMAS
$pdf->SetFont('Arial', '', 11);
$y_datos = $pdf->GetY();

$pdf->SetXY(10, $y_datos);
$pdf->Cell($half_width, 6, t('C.C: ' . $orden['doc_cliente_firma']), 0, 0, 'C');

$nombre_taller = isset($orden['firma_taller_nombre']) && !empty($orden['firma_taller_nombre']) ? $orden['firma_taller_nombre'] : $orden['tecnico'];
$pdf->SetXY(105, $y_datos);
$pdf->Cell($half_width, 6, t( $nombre_taller), 0, 0, 'C');

// TEXTO DE ACEPTACIoN FINAL
$pdf->Ln(12);
$pdf->SetFont('Arial', 'I', 9);
$pdf->MultiCell(0, 5, t('De forma voluntaria y libre, doy aceptación al estado estético y mecánico de mi motocicleta.'), 0, 'C');

$pdf->Output('D', 'orden_' . $orden['numero_orden'] . '.pdf');
?>