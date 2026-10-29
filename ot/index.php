<?php
// Solo usuarios autorizados (Recepción de motos o administrador). Ver includes/acceso.php.
require_once __DIR__ . '/includes/acceso.php';
bullracing_ot_exigir_acceso();
$usuario_ot = wp_get_current_user();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Servicio — Bull Racing</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/styles.css?v=7">
</head>

<body>
    <!-- Quién está usando la OT y botón para salir (agregado: control de acceso). -->
    <div style="display:flex; justify-content:flex-end; align-items:center; gap:16px; padding:8px 16px; background:#111; color:#ddd; font:14px 'Poppins', sans-serif;">
        <span>Recibe: <strong style="color:#fff;"><?php echo esc_html( $usuario_ot->display_name ); ?></strong></span>
        <a href="<?php echo esc_url( wp_logout_url( home_url( '/ot/' ) ) ); ?>" style="color:#fff; font-weight:600;">Salir</a>
    </div>

    <div class="container" id="form-container">
        <!-- Encabezado -->
        <div class="form-header">
            <div class="logo-box">
                <img src="./assets/images/logoBR.png" alt="Bull Racing Logo" onerror="this.style.display='none'">
                <p class="lema">Rueda libre, Rueda seguro</p>
            </div>
            <div class="header-title">
                <h1>Orden de Servicio</h1>
                <p>Motos Bull Racing</p>
            </div>
            <div class="order-number">
                <i class="fas fa-hashtag"></i>
                <input type="text" id="order_num" placeholder="N.°" readonly>
            </div>
        </div>

        <!-- Buscar por placa -->
        <div class="section">
            <div class="section-title"><i class="fas fa-search"></i> Buscar por Placa</div>
            <!-- Agendamiento pendiente: llena el formulario con la cita. Historial de OT: órdenes anteriores de la placa con su PDF. -->
            <div class="busqueda-modo">
                <label><input type="radio" name="modo_busqueda" value="agendamiento" checked> Agendamiento pendiente</label>
                <label><input type="radio" name="modo_busqueda" value="historial"> Historial de OT</label>
            </div>
            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <input type="text" id="buscar_placa" placeholder="Ingrese placa o documento"
                    style="flex:1; min-width:200px; padding:10px; border-radius:50px; border:2px solid #e0e0e0;">
                    <button class="btn btn-pdf" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
                <span id="resultado_busqueda" style="font-size:0.8rem; color:#888; margin-left:10px;"></span>
            </div>
            <div id="historial_ot" class="historial" hidden></div>
        </div>
        <!-- Datos del Cliente -->
        <div class="section">
            <div class="section-title"><i class="fas fa-user"></i> Datos del Cliente</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" id="nombre" placeholder="Nombre completo">
                </div>

                <!-- Campo de documento con tipo -->
                <div class="form-group">
                    <div class="documento-group">
                        <div class="doc-tipo">
                            <label>Tipo de documento</label>
                            <select id="tipo_doc" class="tipo-doc-select">
                                <option value="CC">CC</option>
                                <option value="CE">CE</option>
                                <option value="Visa">Visa</option>
                                <option value="PEP/PPT">PEP/PPT</option>
                                <option value="Pasaporte">Pasaporte</option>
                            </select>
                        </div>
                        <div class="doc-numero">
                            <label>Número de
                                <br>documento</label>
                            <input type="text" id="cedula" placeholder="Número de Documento" class="doc-input">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="tel" id="telefono" placeholder="Teléfono">
                </div>
                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" id="correo" placeholder="Correo">
                </div>
            </div>
        </div>

        <!-- Datos de la Motocicleta -->
        <div class="section">
            <div class="section-title"><i class="fas fa-motorcycle"></i> Datos de la Motocicleta</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Placa</label>
                    <input type="text" id="placa" placeholder="ABC123">
                </div>
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" id="marca" placeholder="Marca">
                </div>
                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" id="modelo" placeholder="Modelo">
                </div>
                <div class="form-group">
                    <label>Línea</label>
                    <input type="text" id="linea" placeholder="Línea">
                </div>
                <div class="form-group">
                    <label>KM</label>
                    <input type="text" id="km" placeholder="Kilometraje">
                </div>
                <div class="form-group">
                    <label>Cilindraje</label>
                    <input type="text" id="cilindraje" placeholder="Cilindraje">
                </div>
                <div class="form-group">
                    <label>Técnico</label>
                    <input type="text" id="tecnico" placeholder="Técnico asignado">
                </div>
                <div class="form-group">
                    <label>Vence Tecno.</label>
                    <input type="date" id="vence_tecnico">
                </div>
                <div class="form-group">
                    <label>Vence SOAT</label>
                    <input type="date" id="vence_soat">
                </div>
            </div>
        </div>

        <!-- Fechas -->
        <div class="section">
            <div class="section-title"><i class="fas fa-calendar"></i> Fechas de Ingreso y Salida</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Fecha Ingreso</label>
                    <input type="date" id="fecha_ingreso">
                </div>
                <div class="form-group">
                    <label>Hora Ingreso</label>
                    <input type="time" id="hora_ingreso">
                </div>
                <div class="form-group">
                    <label>Fecha Salida</label>
                    <input type="date" id="fecha_salida">
                </div>
                <div class="form-group">
                    <label>Hora Salida</label>
                    <input type="time" id="hora_salida">
                </div>
            </div>
        </div>

        <!-- Clase de Trabajo (centrado) -->
        <div class="section">
            <div class="section-title"><i class="fas fa-tasks"></i> Clase de Trabajo</div>
            <div class="checkbox-group">
                <label class="checkbox-item"><input type="checkbox" name="clase" value="Mantenimiento">
                    Mantenimiento</label>
                <label class="checkbox-item"><input type="checkbox" name="clase" value="Revisión"> Revisión</label>
                <label class="checkbox-item"><input type="checkbox" name="clase" value="Garantía"> Garantía</label>
                <label class="checkbox-item"><input type="checkbox" name="clase" value="Reparación"> Reparación</label>
                <label class="checkbox-item"><input type="checkbox" name="clase" value="Tecno"> Tecno</label>
                <label class="checkbox-item"><input type="checkbox" name="clase" value="Lavada"> Lavada</label>
            </div>
        </div>

        <!-- Elementos Entregados -->
        <div class="section">
            <div class="section-title"><i class="fas fa-box-open"></i> Elementos Entregados</div>
            <div class="grid-3 grid-entregados">
                <div class="check-radio-group">
                    <span class="mini-label">Tarjeta de<br>propiedad</span>
                    <div class="sn-toggle">
                        <input type="radio" name="tarjeta_propiedad" id="tarjeta_si" value="SI"><label
                            for="tarjeta_si">SI</label>
                        <input type="radio" name="tarjeta_propiedad" id="tarjeta_no" value="NO" checked><label
                            for="tarjeta_no">NO</label>
                    </div>
                </div>
                <div class="check-radio-group">
                    <span class="mini-label">Llaves</span>
                    <div class="sn-toggle">
                        <input type="radio" name="llaves" id="llaves_si" value="SI"><label for="llaves_si">SI</label>
                        <input type="radio" name="llaves" id="llaves_no" value="NO" checked><label
                            for="llaves_no">NO</label>
                    </div>
                </div>
                <div class="check-radio-group">
                    <span class="mini-label">Herramienta</span>
                    <div class="sn-toggle">
                        <input type="radio" name="herramienta" id="herr_si" value="SI"><label for="herr_si">SI</label>
                        <input type="radio" name="herramienta" id="herr_no" value="NO" checked><label
                            for="herr_no">NO</label>
                    </div>
                </div>
                <div class="check-radio-group">
                    <span class="mini-label">Casco</span>
                    <div class="sn-toggle">
                        <input type="radio" name="casco" id="casco_si" value="SI"><label for="casco_si">SI</label>
                        <input type="radio" name="casco" id="casco_no" value="NO" checked><label
                            for="casco_no">NO</label>
                    </div>
                </div>
                <div class="check-radio-group">
                    <span class="mini-label">Guantes</span>
                    <div class="sn-toggle">
                        <input type="radio" name="guantes" id="guantes_si" value="SI"><label for="guantes_si">SI</label>
                        <input type="radio" name="guantes" id="guantes_no" value="NO" checked><label
                            for="guantes_no">NO</label>
                    </div>
                </div>
                <div class="check-radio-group">
                    <span class="mini-label">Alarma</span>
                    <div class="sn-toggle">
                        <input type="radio" name="alarma" id="alarma_si" value="SI"><label for="alarma_si">SI</label>
                        <input type="radio" name="alarma" id="alarma_no" value="NO" checked><label
                            for="alarma_no">NO</label>
                    </div>
                </div>
                <div class="check-radio-group">
                    <span class="mini-label">Maletero</span>
                    <div class="sn-toggle">
                        <input type="radio" name="maletero" id="maletero_si" value="SI"><label
                            for="maletero_si">SI</label>
                        <input type="radio" name="maletero" id="maletero_no" value="NO" checked><label
                            for="maletero_no">NO</label>
                    </div>
                </div>
                <div class="check-radio-group">
                    <span class="mini-label">Parrilla</span>
                    <div class="sn-toggle">
                        <input type="radio" name="parrilla" id="parrilla_si" value="SI"><label
                            for="parrilla_si">SI</label>
                        <input type="radio" name="parrilla" id="parrilla_no" value="NO" checked><label
                            for="parrilla_no">NO</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Combustible -->
        <div class="section">
            <div class="section-title"><i class="fas fa-gas-pump"></i> Combustible</div>
            <div class="fuel-selector">
                <input type="radio" name="combustible" id="fuel_r" value="R"><label for="fuel_r">R</label>
                <input type="radio" name="combustible" id="fuel_025" value="0.25"><label for="fuel_025">0.25</label>
                <input type="radio" name="combustible" id="fuel_05" value="0.5"><label for="fuel_05">0.5</label>
                <input type="radio" name="combustible" id="fuel_075" value="0.75"><label for="fuel_075">0.75</label>
                <input type="radio" name="combustible" id="fuel_f" value="F"><label for="fuel_f">F</label>
            </div>
        </div>

        <!-- Revisión de Ingreso -->
        <div class="section">
            <div class="section-title"><i class="fas fa-search"></i> Revisión de Ingreso</div>

            <!-- Espacio Moto -->
            <div class="section">
                <div class="section-title"><i class="fas fa-motorcycle"></i> Referencia Visual de la Moto</div>
                <div class="moto-sketch">
                    <div style="position: relative; display: inline-block;">
                        <img src="./assets/images/moto1.jpg" alt="Moto" id="motoImage" style="max-width:100%; display:block;"
                            onerror="this.style.display='none'">
                        <canvas id="motoCanvas"
                            style="position:absolute; top:0; left:0; width:100%; height:100%; cursor: crosshair; touch-action: none;"></canvas>
                    </div>
                </div>
                <p class="sketch-note">Toca o haz clic en la imagen para marcar con una X el lugar del daño.</p>
                <button type="button" id="borrarMarcas" class="btn-limpiar" style="margin-top:10px;"><i
                        class="fas fa-undo"></i> Borrar marcas</button>
                <input type="hidden" id="marcasData" value="">
            </div>

            <!-- Iluminarias -->
            <h4 class="sub-title"><i class="fas fa-lightbulb"></i> Iluminarias</h4>
            <table class="revision-table">
                <thead>
                    <tr>
                        <th>Ítem</th>
                        <th>Estado</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Stop</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="stop" id="stop_si" value="SI"><label
                                    for="stop_si">SI</label><input type="radio" name="stop" id="stop_no" value="NO"
                                    checked><label for="stop_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Direccionales</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="direccionales" id="dir_si"
                                    value="SI"><label for="dir_si">SI</label><input type="radio" name="direccionales"
                                    id="dir_no" value="NO" checked><label for="dir_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Exploradoras</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="exploradoras" id="expl_si"
                                    value="SI"><label for="expl_si">SI</label><input type="radio" name="exploradoras"
                                    id="expl_no" value="NO" checked><label for="expl_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Alta</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="alta" id="alta_si" value="SI"><label
                                    for="alta_si">SI</label><input type="radio" name="alta" id="alta_no" value="NO"
                                    checked><label for="alta_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Baja</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="baja" id="baja_si" value="SI"><label
                                    for="baja_si">SI</label><input type="radio" name="baja" id="baja_no" value="NO"
                                    checked><label for="baja_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Placa</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="placa_luz" id="placa_luz_si"
                                    value="SI"><label for="placa_luz_si">SI</label><input type="radio" name="placa_luz"
                                    id="placa_luz_no" value="NO" checked><label for="placa_luz_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                </tbody>
            </table>

            <!-- Plásticos -->
            <h4 class="sub-title"><i class="fas fa-car"></i> Plásticos</h4>
            <table class="revision-table">
                <thead>
                    <tr>
                        <th>Ítem</th>
                        <th>Estado</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lateral Derecho</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="lat_der" id="lat_der_si" value="SI"><label
                                    for="lat_der_si">SI</label><input type="radio" name="lat_der" id="lat_der_no"
                                    value="NO" checked><label for="lat_der_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Lateral Izquierdo</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="lat_izq" id="lat_izq_si" value="SI"><label
                                    for="lat_izq_si">SI</label><input type="radio" name="lat_izq" id="lat_izq_no"
                                    value="NO" checked><label for="lat_izq_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Carenaje</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="carenaje" id="carenaje_si"
                                    value="SI"><label for="carenaje_si">SI</label><input type="radio" name="carenaje"
                                    id="carenaje_no" value="NO" checked><label for="carenaje_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Guardabarros Delantero</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="gb_del" id="gb_del_si" value="SI"><label
                                    for="gb_del_si">SI</label><input type="radio" name="gb_del" id="gb_del_no"
                                    value="NO" checked><label for="gb_del_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Guardabarros Trasero</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="gb_tras" id="gb_tras_si" value="SI"><label
                                    for="gb_tras_si">SI</label><input type="radio" name="gb_tras" id="gb_tras_no"
                                    value="NO" checked><label for="gb_tras_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Protector Piñón</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="prot_pinon" id="prot_pinon_si"
                                    value="SI"><label for="prot_pinon_si">SI</label><input type="radio"
                                    name="prot_pinon" id="prot_pinon_no" value="NO" checked><label
                                    for="prot_pinon_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Cúpula</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="cupula" id="cupula_si" value="SI"><label
                                    for="cupula_si">SI</label><input type="radio" name="cupula" id="cupula_no"
                                    value="NO" checked><label for="cupula_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Quilla</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="quilla" id="quilla_si" value="SI"><label
                                    for="quilla_si">SI</label><input type="radio" name="quilla" id="quilla_no"
                                    value="NO" checked><label for="quilla_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Guardacadena</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="guardacadena" id="guardacadena_si"
                                    value="SI"><label for="guardacadena_si">SI</label><input type="radio"
                                    name="guardacadena" id="guardacadena_no" value="NO" checked><label
                                    for="guardacadena_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Cola Derecha</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="cola_der" id="cola_der_si"
                                    value="SI"><label for="cola_der_si">SI</label><input type="radio" name="cola_der"
                                    id="cola_der_no" value="NO" checked><label for="cola_der_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Cola Izquierda</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="cola_izq" id="cola_izq_si"
                                    value="SI"><label for="cola_izq_si">SI</label><input type="radio" name="cola_izq"
                                    id="cola_izq_no" value="NO" checked><label for="cola_izq_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Farola</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="farola" id="farola_si" value="SI"><label
                                    for="farola_si">SI</label><input type="radio" name="farola" id="farola_no"
                                    value="NO" checked><label for="farola_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                </tbody>
            </table>

            <!-- Periféricos -->
            <h4 class="sub-title"><i class="fas fa-cog"></i> Periféricos</h4>
            <table class="revision-table">
                <thead>
                    <tr>
                        <th>Ítem</th>
                        <th>Estado</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Espejo Derecho</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="esp_der" id="esp_der_si" value="SI"><label
                                    for="esp_der_si">SI</label><input type="radio" name="esp_der" id="esp_der_no"
                                    value="NO" checked><label for="esp_der_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Espejo Izquierdo</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="esp_izq" id="esp_izq_si" value="SI"><label
                                    for="esp_izq_si">SI</label><input type="radio" name="esp_izq" id="esp_izq_no"
                                    value="NO" checked><label for="esp_izq_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Calapié Derecho</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="calap_der" id="calap_der_si"
                                    value="SI"><label for="calap_der_si">SI</label><input type="radio" name="calap_der"
                                    id="calap_der_no" value="NO" checked><label for="calap_der_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Calapié Izquierdo</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="calap_izq" id="calap_izq_si"
                                    value="SI"><label for="calap_izq_si">SI</label><input type="radio" name="calap_izq"
                                    id="calap_izq_no" value="NO" checked><label for="calap_izq_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Posapiés Derecho</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="pos_der" id="pos_der_si" value="SI"><label
                                    for="pos_der_si">SI</label><input type="radio" name="pos_der" id="pos_der_no"
                                    value="NO" checked><label for="pos_der_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Posapiés Izquierdo</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="pos_izq" id="pos_izq_si" value="SI"><label
                                    for="pos_izq_si">SI</label><input type="radio" name="pos_izq" id="pos_izq_no"
                                    value="NO" checked><label for="pos_izq_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Tapa Tanque</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="tapa_tanque" id="tapa_tanque_si"
                                    value="SI"><label for="tapa_tanque_si">SI</label><input type="radio"
                                    name="tapa_tanque" id="tapa_tanque_no" value="NO" checked><label
                                    for="tapa_tanque_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Pito</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="pito" id="pito_si" value="SI"><label
                                    for="pito_si">SI</label><input type="radio" name="pito" id="pito_no" value="NO"
                                    checked><label for="pito_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Batería</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="bateria" id="bateria_si" value="SI"><label
                                    for="bateria_si">SI</label><input type="radio" name="bateria" id="bateria_no"
                                    value="NO" checked><label for="bateria_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                    <tr>
                        <td>Tacómetro</td>
                        <td>
                            <div class="sn-toggle"><input type="radio" name="tacometro" id="tacometro_si"
                                    value="SI"><label for="tacometro_si">SI</label><input type="radio" name="tacometro"
                                    id="tacometro_no" value="NO" checked><label for="tacometro_no">NO</label></div>
                        </td>
                        <td><input type="text" class="obs-input"></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Observaciones Generales -->
        <div class="section">
            <div class="section-title"><i class="fas fa-comment"></i> Observaciones Generales</div>
            <div class="observaciones">
                <textarea id="observaciones" rows="4" placeholder="Escriba observaciones generales..."></textarea>
            </div>
        </div>

        <!-- Firmas -->
        <div class="firmas">
            <div class="firma-box">
                <p>Firma Cliente</p>
                <canvas class="firma-canvas" id="firmaCliente" width="300" height="150"></canvas>
                <div class="firma-acciones">
                    <button type="button" class="btn-limpiar" onclick="clearFirma('firmaCliente')"><i
                            class="fas fa-eraser"></i> Limpiar</button>
                </div>
                <input type="text" id="firma_doc_cliente" placeholder="Número de Documento:" class="firma-dato">
            </div>
            <div class="firma-box">
                <p>Firma Recibido por (Taller)</p>
                <canvas class="firma-canvas" id="firmaTaller" width="300" height="150"></canvas>
                <div class="firma-acciones">
                    <button type="button" class="btn-limpiar" onclick="clearFirma('firmaTaller')"><i
                            class="fas fa-eraser"></i> Limpiar</button>
                </div>
                <input type="text" id="firma_taller_nombre" placeholder="Nombre y cargo:" class="firma-dato">
            </div>
        </div>

        <!-- Aceptación -->
        <div class="aceptacion">
            <i class="fas fa-check-circle"></i>
            De forma voluntaria y libre, doy aceptación al estado estético y mecánico de mi motocicleta.
        </div>

        <!-- Botones -->
        <div class="actions">
            <!--<button class="btn btn-pdf" onclick="exportPDF()"><i class="fas fa-file-pdf"></i> Exportar PDF</button>-->
            <button class="btn btn-save" onclick="saveData()"><i class="fas fa-database"></i> Guardar</button>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script src="./js/script.js?v=7"></script>
</body>

</html>