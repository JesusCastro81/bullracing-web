<?php
// EJEMPLO de configuración. Copiar como includes/config.php y poner los datos reales del servidor.
// includes/config.php NUNCA se sube a git (está en .gitignore).
// Configuración de la base de datos
$host = '127.0.0.1';
$ports = [3306, 3307, 3308, 3309];
$dbname = 'bullracing_bd';
$user = 'root';
$pass = ''; // La contraseña real va SOLO en includes/config.php (no se sube a git).


try {
    foreach ($ports as $port) {
        try {
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            break;
        } catch (PDOException $e) {
            // Ignore connection error and try the next port
        }
    }
} catch (PDOException $e) {
    // Devuelve un JSON de error en lugar de texto plano
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'msg' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}