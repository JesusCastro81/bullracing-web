<?php
require_once __DIR__ . '/../includes/acceso.php'; // Solo usuarios autorizados (Recepción de motos).
bullracing_ot_exigir_acceso( true );
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

// Historial de órdenes por placa o por documento del cliente (motos sin placa). Solo lectura.
// La placa se compara sin guiones ni espacios, así "ABC-123" y "ABC123" son la misma placa.
// Por documento salen todas las motos de ese cliente; las columnas placa y documento las distinguen.
$placa = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_GET['placa'] ?? ''));
if ($placa === '') {
    echo json_encode(['success' => false, 'msg' => 'Placa requerida']);
    exit;
}

$stmt = $pdo->prepare(
    "SELECT numero_orden, placa, documento, fecha_ingreso, hora_ingreso
     FROM ordenes_servicio
     WHERE REPLACE(REPLACE(placa, '-', ''), ' ', '') = ? OR documento = ?
     ORDER BY CAST(numero_orden AS UNSIGNED) DESC
     LIMIT 50"
);
$stmt->execute([$placa, $placa]);
$ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Quién recibió cada orden: registro de recepción de WordPress (plugin bullracing-core).
// Las órdenes guardadas antes de ese registro quedan sin este dato.
if ($ordenes && class_exists('BullRacing_Acceso_OT')) {
    global $wpdb;
    $numeros = array_column($ordenes, 'numero_orden');
    $marcas  = implode(',', array_fill(0, count($numeros), '%s'));
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- nombre de tabla interno.
    $filas   = $wpdb->get_results($wpdb->prepare('SELECT numero_orden, usuario_nombre FROM ' . BullRacing_Acceso_OT::tabla() . " WHERE numero_orden IN ($marcas)", $numeros), ARRAY_A);
    $recibio = array_column((array) $filas, 'usuario_nombre', 'numero_orden');
    foreach ($ordenes as &$orden) {
        $orden['recibio'] = $recibio[$orden['numero_orden']] ?? '';
    }
    unset($orden);
}

echo json_encode(['success' => true, 'data' => $ordenes]);
