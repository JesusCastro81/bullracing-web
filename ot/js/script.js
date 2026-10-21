// Toast, son las notificaciones
function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.style.background = type === 'success' ? '#1d3557' : '#1a1a2e';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// VALIDACIONES DE FORMATO

// 1. Solo letras y espacios
function validarNombre(texto) {
    return /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(texto);
}

// 2. Solo números, sin espacios, puntos ni comas
function validarSoloNumeros(texto) {
    return /^[0-9]+$/.test(texto);
}

// 3. Validar correo electrónico
function validarEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// 4. Formatear a mayúsculas (excepto correo que lo dejamos en minúsculas)
function convertirMayusculas(texto) {
    return texto.toUpperCase().trim();
}

// 5. Limpiar números (eliminar espacios, puntos, comas, guiones)
function limpiarNumeros(texto) {
    return texto.replace(/[\s.,\-]/g, '');
}

// CROQUIS DE LA MOTO
let marcas = [];

function drawMarcasGlobal() {
    const canvas = document.getElementById('motoCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    if (!marcas.length) return;
    const w = canvas.width, h = canvas.height;
    marcas.forEach(m => {
        const x = m.x * w, y = m.y * h;
        const size = Math.min(w, h) * 0.08;
        ctx.beginPath();
        ctx.moveTo(x - size / 2, y - size / 2);
        ctx.lineTo(x + size / 2, y + size / 2);
        ctx.moveTo(x + size / 2, y - size / 2);
        ctx.lineTo(x - size / 2, y + size / 2);
        ctx.strokeStyle = 'red';
        ctx.lineWidth = 4;
        ctx.stroke();
    });
    document.getElementById('marcasData').value = JSON.stringify(marcas);
}

function initCroquis() {
    const canvas = document.getElementById('motoCanvas');
    const img = document.getElementById('motoImage');
    if (!canvas || !img) return;

    function resizeCanvas() {
        const rect = img.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = rect.height;
        canvas.style.width = rect.width + 'px';
        canvas.style.height = rect.height + 'px';
        drawMarcas();
    }

    function drawMarcas() {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        if (!marcas.length) return;
        const w = canvas.width, h = canvas.height;
        marcas.forEach(m => {
            const x = m.x * w, y = m.y * h;
            const size = Math.min(w, h) * 0.05;
            ctx.beginPath();
            ctx.moveTo(x - size / 2, y - size / 2);
            ctx.lineTo(x + size / 2, y + size / 2);
            ctx.moveTo(x + size / 2, y - size / 2);
            ctx.lineTo(x - size / 2, y + size / 2);
            ctx.strokeStyle = 'red';
            ctx.lineWidth = 2;
            ctx.stroke();
        });
        document.getElementById('marcasData').value = JSON.stringify(marcas);
    }

    function getCoords(e) {
        const rect = canvas.getBoundingClientRect();
        let clientX, clientY;
        if (e.touches) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
            e.preventDefault();
        } else {
            clientX = e.clientX;
            clientY = e.clientY;
        }
        const x = (clientX - rect.left) / rect.width;
        const y = (clientY - rect.top) / rect.height;
        return { x: Math.min(1, Math.max(0, x)), y: Math.min(1, Math.max(0, y)) };
    }

    function addMarca(e) {
        const coords = getCoords(e);
        marcas.push(coords);
        drawMarcas();
    }

    canvas.addEventListener('click', addMarca);
    canvas.addEventListener('touchstart', addMarca, { passive: false });

    document.getElementById('borrarMarcas').addEventListener('click', function () {
        marcas = [];
        drawMarcas();
    });

    window.addEventListener('resize', resizeCanvas);
    img.addEventListener('load', resizeCanvas);
    resizeCanvas();
}

// ====== FIRMAS ======
function setupFirma(canvasId) {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext('2d');
    let isDrawing = false, lastX = 0, lastY = 0;
    function resizeCanvas() {
        const parent = canvas.parentElement;
        const width = parent.clientWidth - 20;
        canvas.width = width;
        canvas.height = 150;
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#1a1a2e';
        ctx.fillStyle = '#fff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    function getMousePos(e) {
        const rect = canvas.getBoundingClientRect();
        return { x: (e.clientX - rect.left) * (canvas.width / rect.width), y: (e.clientY - rect.top) * (canvas.height / rect.height) };
    }
    function start(e) {
        isDrawing = true;
        const pos = getMousePos(e);
        lastX = pos.x; lastY = pos.y;
        e.preventDefault();
    }
    function draw(e) {
        if (!isDrawing) return;
        const pos = getMousePos(e);
        ctx.beginPath(); ctx.moveTo(lastX, lastY); ctx.lineTo(pos.x, pos.y); ctx.stroke();
        lastX = pos.x; lastY = pos.y;
        e.preventDefault();
    }
    function stop() { isDrawing = false; }
    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stop);
    canvas.addEventListener('mouseleave', stop);
    canvas.addEventListener('touchstart', (e) => { const touch = e.touches[0]; start(touch); }, { passive: false });
    canvas.addEventListener('touchmove', (e) => { const touch = e.touches[0]; draw(touch); }, { passive: false });
    canvas.addEventListener('touchend', stop);
}
function clearFirma(canvasId) {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#fff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
}

// ====== RECOPILAR DATOS ======
function getFormData() {
    const data = {
        numero_orden: document.getElementById('order_num').value,
        nombre: document.getElementById('nombre').value,
        tipo_documento: document.getElementById('tipo_doc').value,
        documento: document.getElementById('cedula').value,
        telefono: document.getElementById('telefono').value,
        correo: document.getElementById('correo').value,
        placa: document.getElementById('placa').value,
        marca: document.getElementById('marca').value,
        modelo: document.getElementById('modelo').value,
        linea: document.getElementById('linea').value,
        km: document.getElementById('km').value,
        cilindraje: document.getElementById('cilindraje').value,
        tecnico: document.getElementById('tecnico').value,
        vence_tecnico: document.getElementById('vence_tecnico').value,
        vence_soat: document.getElementById('vence_soat').value,
        fecha_ingreso: document.getElementById('fecha_ingreso').value,
        hora_ingreso: document.getElementById('hora_ingreso').value,
        fecha_salida: document.getElementById('fecha_salida').value,
        hora_salida: document.getElementById('hora_salida').value,
        observaciones_generales: document.getElementById('observaciones').value,
        doc_cliente_firma: document.getElementById('firma_doc_cliente').value,
        firma_taller_nombre: document.getElementById('firma_taller_nombre').value,
        marcas_croquis: document.getElementById('marcasData').value
    };

    // Clase de trabajo
    const claseChecks = document.querySelectorAll('input[name="clase"]:checked');
    data.clase_trabajo = Array.from(claseChecks).map(cb => cb.value).join(',');

    // Elementos entregados
    const elementos = ['tarjeta_propiedad', 'llaves', 'herramienta', 'casco', 'guantes', 'alarma', 'maletero', 'parrilla'];
    elementos.forEach(el => {
        const radio = document.querySelector(`input[name="${el}"]:checked`);
        data[el] = radio ? radio.value : 'NO';
    });

    // Combustible
    const fuelRadio = document.querySelector('input[name="combustible"]:checked');
    data.combustible = fuelRadio ? fuelRadio.value : '';

    // Iluminarias
    const ilumItems = ['stop', 'direccionales', 'exploradoras', 'alta', 'baja', 'placa_luz'];
    ilumItems.forEach(item => {
        const radio = document.querySelector(`input[name="${item}"]:checked`);
        data[item + '_estado'] = radio ? radio.value : 'NO';
        const tr = radio ? radio.closest('tr') : null;
        data[item + '_obs'] = tr ? tr.querySelector('.obs-input').value : '';
    });

    // Plásticos
    const plasticItems = ['lat_der', 'lat_izq', 'carenaje', 'gb_del', 'gb_tras', 'prot_pinon', 'cupula', 'quilla', 'guardacadena', 'cola_der', 'cola_izq', 'farola'];
    plasticItems.forEach(item => {
        const radio = document.querySelector(`input[name="${item}"]:checked`);
        data[item + '_estado'] = radio ? radio.value : 'NO';
        const tr = radio ? radio.closest('tr') : null;
        data[item + '_obs'] = tr ? tr.querySelector('.obs-input').value : '';
    });

    // Periféricos
    const periItems = ['esp_der', 'esp_izq', 'calap_der', 'calap_izq', 'pos_der', 'pos_izq', 'tapa_tanque', 'pito', 'bateria', 'tacometro'];
    periItems.forEach(item => {
        const radio = document.querySelector(`input[name="${item}"]:checked`);
        data[item + '_estado'] = radio ? radio.value : 'NO';
        const tr = radio ? radio.closest('tr') : null;
        data[item + '_obs'] = tr ? tr.querySelector('.obs-input').value : '';
    });

    // Firmas
    const firmaClienteCanvas = document.getElementById('firmaCliente');
    const firmaTallerCanvas = document.getElementById('firmaTaller');
    data.firma_cliente = firmaClienteCanvas.toDataURL('image/png');
    data.firma_taller = firmaTallerCanvas.toDataURL('image/png');

    return data;
}

// ====== VALIDAR FIRMAS (no vacías) ======
function isCanvasEmpty(canvas) {
    const ctx = canvas.getContext('2d');
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;
    for (let i = 0; i < data.length; i += 4) {
        if (data[i] < 255 || data[i + 1] < 255 || data[i + 2] < 255) {
            return false;
        }
    }
    return true;
}

function validarFirmas() {
    const canvas1 = document.getElementById('firmaCliente');
    const canvas2 = document.getElementById('firmaTaller');
    return !isCanvasEmpty(canvas1) && !isCanvasEmpty(canvas2);
}

// ====== GUARDAR Y EXPORTAR PDF AUTOMÁTICAMENTE ======
async function saveData() {

    // Nombre (solo letras y espacios)
    const nombre = document.getElementById('nombre');
    if (!validarNombre(nombre.value)) {
        showToast('El nombre solo debe contener letras y espacios.', 'error');
        nombre.focus();
        return;
    }
    nombre.value = convertirMayusculas(nombre.value);

    // 2. Cédula (solo números)
    const cedula = document.getElementById('cedula');
    const cedulaLimpia = limpiarNumeros(cedula.value);
    if (!validarSoloNumeros(cedulaLimpia)) {
        showToast('La cédula solo debe contener números (sin puntos, comas o espacios).', 'error');
        cedula.focus();
        return;
    }
    cedula.value = cedulaLimpia;

    // 3. Teléfono (solo números)
    const telefono = document.getElementById('telefono');
    const telefonoLimpio = limpiarNumeros(telefono.value);
    if (!validarSoloNumeros(telefonoLimpio)) {
        showToast('El teléfono solo debe contener números.', 'error');
        telefono.focus();
        return;
    }
    telefono.value = telefonoLimpio;

    // 4. Correo (formato válido)
    const correo = document.getElementById('correo');
    if (!validarEmail(correo.value)) {
        showToast('Ingrese un correo electrónico válido (ej: usuario@dominio.com).', 'error');
        correo.focus();
        return;
    }
    // Convertir correo a minúsculas
    correo.value = correo.value.toLowerCase();

    // 5. Validar que el documento de la firma coincida con la cédula
    const docFirma = document.getElementById('firma_doc_cliente');
    const docFirmaLimpio = limpiarNumeros(docFirma.value);
    if (docFirmaLimpio !== cedula.value) {
        showToast('El número de documento bajo la firma debe coincidir con la cédula.', 'error');
        docFirma.focus();
        return;
    }
    docFirma.value = docFirmaLimpio;

    // 6. Convertir a mayúsculas los campos de texto (nombre, placa, marca, modelo, línea, técnico, firma_taller_nombre)
    const camposTexto = ['nombre', 'placa', 'marca', 'modelo', 'linea', 'tecnico', 'firma_taller_nombre'];
    camposTexto.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.value = el.value.toUpperCase().trim();
        }
    });

    // 7. Limpiar km y cilindraje (solo dígitos)
    ['km'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.value = el.value.replace(/[^0-9]/g, '');
        }
    });

    // 8. Validar número de orden (solo dígitos)
    const orderNum = document.getElementById('order_num');
    if (!/^[0-9]+$/.test(orderNum.value.trim())) {
        showToast('El número de orden solo debe contener dígitos.', 'error');
        orderNum.focus();
        return;
    }
    orderNum.value = orderNum.value.trim();

    // VALIDACIÓN DE CAMPOS OBLIGATORIOS
    const requiredFields = ['order_num', 'placa', 'marca', 'modelo', 'linea', 'km', 'cilindraje', 'tecnico',
        'vence_tecnico', 'vence_soat', 'fecha_ingreso', 'hora_ingreso',
        'fecha_salida', 'hora_salida'];
    for (let id of requiredFields) {
        const el = document.getElementById(id);
        if (!el || el.value.trim() === '') {
            showToast(`El campo ${el.placeholder || id} es obligatorio.`, 'error');
            el.focus();
            return;
        }
    }

    // Validar clase de trabajo
    const claseChecks = document.querySelectorAll('input[name="clase"]:checked');
    if (claseChecks.length === 0) { showToast('Debe seleccionar al menos una Clase de Trabajo.', 'error'); return; }

    // Validar combustible
    const fuelRadio = document.querySelector('input[name="combustible"]:checked');
    if (!fuelRadio) { showToast('Seleccione el nivel de combustible.', 'error'); return; }

    // Validar firmas
    if (!validarFirmas()) { showToast('Las firmas son obligatorias.', 'error'); return; }

    // -------- RECOPILAR DATOS Y ENVIAR --------
    const data = getFormData();

    try {
        const response = await fetch('ajax/guardarDatos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();

        if (result.success) {
            showToast('Orden guardada correctamente. Generando PDF...', 'success');
            setTimeout(() => {
                window.location.href = 'generar_pdf.php?id=' + encodeURIComponent(result.numero_orden);
            }, 500);
            setTimeout(() => {
                if (confirm('¿Quieres hacer una nueva orden de ingreso?')) {
                    resetForm();
                }
            }, 1500);
        } else {
            showToast('Error: ' + result.msg, 'error');
        }
    } catch (error) {
        showToast('Error de conexión: ' + error.message, 'error');
    }
}

//EXPORTAR PDF
function exportPDF() {
    const numero = document.getElementById('order_num').value;
    if (!numero) {
        showToast('Ingrese el número de orden para reimprimir el PDF.', 'error');
        return;
    }
    // Descarga directa del PDF consultando la BD
    window.location.href = 'generar_pdf.php?id=' + encodeURIComponent(numero);
}

// REINICIAR FORMULARIO
function resetForm() {
    document.querySelector('form')?.reset();
    location.reload();
}

// INICIALIZACIÓN
document.addEventListener('DOMContentLoaded', function() {
    setupFirma('firmaCliente');
    setupFirma('firmaTaller');
    initCroquis();

    // Fechas por defecto
    const today = new Date().toISOString().split('T')[0];
    const fechaIngreso = document.getElementById('fecha_ingreso');
    if (!fechaIngreso.value) fechaIngreso.value = today;
    const now = new Date();
    const horas = String(now.getHours()).padStart(2,'0');
    const minutos = String(now.getMinutes()).padStart(2,'0');
    const horaIngreso = document.getElementById('hora_ingreso');
    if (!horaIngreso.value) horaIngreso.value = horas + ':' + minutos;

    // Conversión en tiempo real a mayúsculas y números
    document.querySelectorAll('#nombre, #placa, #marca, #modelo, #linea, #tecnico, #firma_taller_nombre, #buscar_placa')
        .forEach(el => {
            el.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        });

    document.querySelectorAll('#cedula, #telefono, #firma_doc_cliente, #km, #order_num')
        .forEach(el => {
            el.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });

    // ===== Obtener número de orden =====
    fetch('ajax/obtener_siguiente_orden.php')
    .then(res => res.json())
    .then(data => {
        document.getElementById('order_num').value = String(data.next).padStart(4, '0');
    })
    // ===== Buscar al presionar Enter =====
    document.getElementById('buscar_placa').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            buscarAgendamiento();
        }
    });

    document.getElementById('btnBuscar').addEventListener('click', buscarAgendamiento);
});


function buscarAgendamiento() {
    const placa = document.getElementById('buscar_placa').value.trim();
    const resultado = document.getElementById('resultado_busqueda');
    if (!placa) {
        resultado.textContent = 'Ingrese una placa';
        return;
    }
    resultado.textContent = 'Buscando...';
    fetch(`ajax/buscar_agendamiento.php?placa=${encodeURIComponent(placa)}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                resultado.textContent = data.msg;
                return;
            }
            const d = data.data;

            document.getElementById('nombre').value = d.nombre_cliente || '';
            document.getElementById('telefono').value = d.telefono || '';
            document.getElementById('correo').value = d.correo || '';
            document.getElementById('placa').value = d.placa || '';
            document.getElementById('cilindraje').value = d.referencia || '';

            if (d.fecha_cita) document.getElementById('fecha_ingreso').value = d.fecha_cita;

            if (d.hora_cita) document.getElementById('hora_ingreso').value = d.hora_cita;

            resultado.textContent = '✅ Datos cargados desde agendamiento';
        })
        .catch(err => {
            resultado.textContent = 'Error al buscar';
            console.error(err);
        });
}