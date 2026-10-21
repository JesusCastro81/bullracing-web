<?php
require_once __DIR__ . '/../includes/acceso.php'; // Solo usuarios autorizados (Recepción de motos).
bullracing_ot_exigir_acceso( true );
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

$placa = isset($_GET['placa']) ? trim($_GET['placa']) : '';
if (empty($placa)) {
    echo json_encode(['success' => false, 'msg' => 'Placa requerida']);
    exit;
}

// SOLO BUSCAMOS AGENDAMIENTOS PENDIENTES (estado = 0)
$stmt = $pdo->prepare("SELECT * FROM agendamientos WHERE placa = ? AND estado = 0 ORDER BY id DESC LIMIT 1");
$stmt->execute([$placa]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'msg' => 'No se encontró un agendamiento pendiente para esa placa']);
}