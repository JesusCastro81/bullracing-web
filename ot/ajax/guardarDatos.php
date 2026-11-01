<?php
require_once __DIR__ . '/../includes/acceso.php'; // Solo usuarios autorizados (Recepción de motos).
bullracing_ot_exigir_acceso( true );
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

function clean($str)
{
    return htmlspecialchars(trim($str));
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'msg' => 'Datos no recibidos']);
    exit;
}

// Lista de campos obligatorios
$required = [
    'numero_orden',
    'nombre',
    'tipo_documento',
    'documento',
    'telefono',
    'correo',
    'placa',
    'marca',
    'modelo',
    'linea',
    'km',
    'cilindraje',
    'tecnico',
    'vence_tecnico',
    'vence_soat',
    'fecha_ingreso',
    'hora_ingreso',
    'fecha_salida',
    'hora_salida',
    'clase_trabajo',
    'tarjeta_propiedad',
    'llaves',
    'herramienta',
    'casco',
    'guantes',
    'alarma',
    'maletero',
    'parrilla',
    'combustible',
    'stop_estado',
    'direccionales_estado',
    'exploradoras_estado',
    'alta_estado',
    'baja_estado',
    'placa_luz_estado',
    'lat_der_estado',
    'lat_izq_estado',
    'carenaje_estado',
    'gb_del_estado',
    'gb_tras_estado',
    'prot_pinon_estado',
    'cupula_estado',
    'quilla_estado',
    'guardacadena_estado',
    'cola_der_estado',
    'cola_izq_estado',
    'farola_estado',
    'esp_der_estado',
    'esp_izq_estado',
    'calap_der_estado',
    'calap_izq_estado',
    'pos_der_estado',
    'pos_izq_estado',
    'tapa_tanque_estado',
    'pito_estado',
    'bateria_estado',
    'tacometro_estado',
    'firma_cliente',
    'firma_taller',
    'doc_cliente_firma'
];

$errors = [];
foreach ($required as $field) {
    if (!isset($input[$field]) || trim($input[$field]) === '') {
        $errors[] = "El campo $field es obligatorio.";
    }
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'msg' => implode(' ', $errors)]);
    exit;
}

// Validar firmas (que no estén vacías)
if (strlen($input['firma_cliente']) < 100 || strlen($input['firma_taller']) < 100) {
    echo json_encode(['success' => false, 'msg' => 'Las firmas deben estar dibujadas correctamente.']);
    exit;
}

// Campos que deben ir en mayúsculas
$camposMayusculas = ['nombre', 'placa', 'marca', 'modelo', 'linea', 'tecnico', 'tipo_documento'];
// Campos que solo deben contener números
$camposNumericos = ['documento', 'telefono', 'doc_cliente_firma', 'km', 'cilindraje', 'numero_orden'];

// Preparar datos
$data = [];
foreach ($input as $key => $value) {
    if ($key === 'marcas_croquis') {
        $data[$key] = $value;
        continue;
    }
    // Limpiar valor base
    $valor = clean($value);
    // Convertir a mayúsculas si está en la lista
    if (in_array($key, $camposMayusculas)) {
        $valor = strtoupper($valor);
    }
    // Eliminar todo excepto dígitos si es numérico
    if (in_array($key, $camposNumericos)) {
        $valor = preg_replace('/[^0-9]/', '', $valor);
    }
    $data[$key] = $valor;
}

// Validar que numero_orden sea numérico
if (!preg_match('/^[0-9]+$/', $data['numero_orden'])) {
    echo json_encode(['success' => false, 'msg' => 'El número de orden solo debe contener dígitos.']);
    exit;
}

// Observaciones puede ser null
if (empty($data['observaciones_generales'])) {
    $data['observaciones_generales'] = null;
}

// El número de la orden NO se toma del navegador: se asigna más abajo, justo antes de guardar (ver GET_LOCK).

// Construir consulta SQL
$sql = "INSERT INTO ordenes_servicio SET 
    numero_orden = :numero_orden,
    nombre = :nombre,
    tipo_documento = :tipo_documento,
    documento = :documento,
    telefono = :telefono,
    correo = :correo,
    placa = :placa,
    marca = :marca,
    modelo = :modelo,
    linea = :linea,
    km = :km,
    cilindraje = :cilindraje,
    tecnico = :tecnico,
    vence_tecnico = :vence_tecnico,
    vence_soat = :vence_soat,
    fecha_ingreso = :fecha_ingreso,
    hora_ingreso = :hora_ingreso,
    fecha_salida = :fecha_salida,
    hora_salida = :hora_salida,
    clase_trabajo = :clase_trabajo,
    tarjeta_propiedad = :tarjeta_propiedad,
    llaves = :llaves,
    herramienta = :herramienta,
    casco = :casco,
    guantes = :guantes,
    alarma = :alarma,
    maletero = :maletero,
    parrilla = :parrilla,
    combustible = :combustible,
    stop_estado = :stop_estado,
    stop_obs = :stop_obs,
    direccionales_estado = :direccionales_estado,
    direccionales_obs = :direccionales_obs,
    exploradoras_estado = :exploradoras_estado,
    exploradoras_obs = :exploradoras_obs,
    alta_estado = :alta_estado,
    alta_obs = :alta_obs,
    baja_estado = :baja_estado,
    baja_obs = :baja_obs,
    placa_luz_estado = :placa_luz_estado,
    placa_luz_obs = :placa_luz_obs,
    lat_der_estado = :lat_der_estado,
    lat_der_obs = :lat_der_obs,
    lat_izq_estado = :lat_izq_estado,
    lat_izq_obs = :lat_izq_obs,
    carenaje_estado = :carenaje_estado,
    carenaje_obs = :carenaje_obs,
    gb_del_estado = :gb_del_estado,
    gb_del_obs = :gb_del_obs,
    gb_tras_estado = :gb_tras_estado,
    gb_tras_obs = :gb_tras_obs,
    prot_pinon_estado = :prot_pinon_estado,
    prot_pinon_obs = :prot_pinon_obs,
    cupula_estado = :cupula_estado,
    cupula_obs = :cupula_obs,
    quilla_estado = :quilla_estado,
    quilla_obs = :quilla_obs,
    guardacadena_estado = :guardacadena_estado,
    guardacadena_obs = :guardacadena_obs,
    cola_der_estado = :cola_der_estado,
    cola_der_obs = :cola_der_obs,
    cola_izq_estado = :cola_izq_estado,
    cola_izq_obs = :cola_izq_obs,
    farola_estado = :farola_estado,
    farola_obs = :farola_obs,
    esp_der_estado = :esp_der_estado,
    esp_der_obs = :esp_der_obs,
    esp_izq_estado = :esp_izq_estado,
    esp_izq_obs = :esp_izq_obs,
    calap_der_estado = :calap_der_estado,
    calap_der_obs = :calap_der_obs,
    calap_izq_estado = :calap_izq_estado,
    calap_izq_obs = :calap_izq_obs,
    pos_der_estado = :pos_der_estado,
    pos_der_obs = :pos_der_obs,
    pos_izq_estado = :pos_izq_estado,
    pos_izq_obs = :pos_izq_obs,
    tapa_tanque_estado = :tapa_tanque_estado,
    tapa_tanque_obs = :tapa_tanque_obs,
    pito_estado = :pito_estado,
    pito_obs = :pito_obs,
    bateria_estado = :bateria_estado,
    bateria_obs = :bateria_obs,
    tacometro_estado = :tacometro_estado,
    tacometro_obs = :tacometro_obs,
    observaciones_generales = :observaciones_generales,
    marcas_croquis = :marcas_croquis,
    firma_cliente = :firma_cliente,
    firma_taller = :firma_taller,
    doc_cliente_firma = :doc_cliente_firma";


// Extraer los placeholders de la consulta
preg_match_all('/:([a-zA-Z0-9_]+)/', $sql, $matches);
$placeholders = $matches[1];

// Filtrar $data para quedarnos solo con las claves que son placeholders
$dataFiltrada = [];
foreach ($placeholders as $placeholder) {
    if (array_key_exists($placeholder, $data)) {
        $dataFiltrada[$placeholder] = $data[$placeholder];
    } else {
        // Si falta algún placeholder, se reporta el error
        echo json_encode(['success' => false, 'msg' => "Falta el parámetro: $placeholder"]);
        exit;
    }
}

// Verificar que no sobre ningún dato extra (opcional)
$sobrantes = array_diff(array_keys($data), $placeholders);
if (!empty($sobrantes)) {
    // Solo advertencia, no es crítico porque no se usan
    // pero podemos ignorarlos
}

// NÚMERO DE ORDEN: lo asigna el servidor AL GUARDAR (no la página al abrirse).
// Así, si dos OT se abren al mismo tiempo, cada una recibe su propio número y ninguna sobrescribe a la otra.
// GET_LOCK da un turno: si dos OT se guardan en el mismo instante, la segunda espera (máximo 10 segundos).
if ((int) $pdo->query("SELECT GET_LOCK('bullracing_numero_ot', 10)")->fetchColumn() !== 1) {
    echo json_encode(['success' => false, 'msg' => 'Se está guardando otra orden en este momento. Intente de nuevo.']);
    exit;
}

$stmt = $pdo->prepare($sql);

try {
    // ORDEN REPETIDA (doble clic en Guardar o guardar dos veces el mismo formulario):
    // si ya existe una orden con la misma placa, fecha y hora de ingreso, no se crea otra; se devuelve la que ya estaba.
    $existente = $pdo->prepare("SELECT numero_orden FROM ordenes_servicio WHERE placa = ? AND fecha_ingreso = ? AND hora_ingreso = ? LIMIT 1");
    $existente->execute([$data['placa'], $data['fecha_ingreso'], $data['hora_ingreso']]);
    $repetida = $existente->fetchColumn();

    if ($repetida !== false) {
        echo json_encode(['success' => true, 'msg' => 'Esta orden ya estaba guardada', 'numero_orden' => $repetida]);
    } else {
        $max = $pdo->query("SELECT MAX(CAST(numero_orden AS UNSIGNED)) FROM ordenes_servicio")->fetchColumn();
        $data['numero_orden'] = str_pad((string) max(250, $max ? $max + 1 : 250), 4, '0', STR_PAD_LEFT);
        $dataFiltrada['numero_orden'] = $data['numero_orden'];

        $stmt->execute($dataFiltrada);
    
        // ACTUALIZAMOS EL AGENDAMIENTO A "USADO"
        if (isset($input['placa']) && !empty($input['placa'])) {
            $stmtUpd = $pdo->prepare("UPDATE agendamientos SET estado = 1, numero_ot_generada = ? WHERE placa = ? AND estado = 0 ORDER BY id DESC LIMIT 1");
            $stmtUpd->execute([$data['numero_orden'], $data['placa']]);
        }

        // TRAZABILIDAD: quién recibió la moto = el usuario conectado (no lo que se escriba en el formulario).
        if (class_exists('BullRacing_Acceso_OT')) {
            BullRacing_Acceso_OT::registrar_recepcion($data['numero_orden'], (string) $data['placa']);
        }

        echo json_encode(['success' => true, 'msg' => 'Orden guardada correctamente', 'numero_orden' => $data['numero_orden']]);
    }
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        echo json_encode(['success' => false, 'msg' => 'No se pudo asignar el número de orden. Intente guardar de nuevo.']);
    } else {
        echo json_encode(['success' => false, 'msg' => 'Error al guardar: ' . $e->getMessage()]);
    }
} finally {
    $pdo->query("SELECT RELEASE_LOCK('bullracing_numero_ot')");
}
