<?php
require_once __DIR__ . '/../includes/config.php';

$stmt = $pdo->query("SELECT MAX(CAST(numero_orden AS UNSIGNED)) AS max FROM ordenes_servicio");
$max = $stmt->fetchColumn();
$next = max(250, $max ? $max + 1 : 250);
echo json_encode(['next' => $next]);