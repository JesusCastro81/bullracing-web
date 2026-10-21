/**
 * Capa visual del agendamiento (Diseño 1 – Taller).
 *
 * NO cambia el funcionamiento del agendamiento (propuesta: "los campos, los precios y el
 * funcionamiento quedan iguales"). Reglas de esta capa:
 *  - El formulario original sigue intacto: mismos campos, id, name y su ba-agendamiento.js.
 *  - Las listas originales se ocultan a la vista, pero siguen siendo las que valen: los botones
 *    nuevos solo escogen una opción en ellas y lanzan "change", igual que si el cliente la escogiera.
 *  - Las casillas de hora LEEN las horas que el agendamiento ya marca como completas.
 *  - El resumen solo lee lo que hay en el formulario. No calcula precios: muestra el del agendamiento.
 *
 * La página debe tener un contenedor [data-br-agenda] que envuelva el formulario.
 * Opcional: data-whatsapp="https://wa.me/..." para el enlace de dudas.
 */
(() => {
  'use strict';

  const caja = document.querySelector('[data-br-agenda]');
  const form = document.getElementById('form-motos-bull');
  if (!caja || !form) return;

  const $ = (id) => document.getElementById(id);
  const crear = (etiqueta, clase, texto) => {
    const el = document.createElement(etiqueta);
    if (clase) el.className = clase;
    if (texto !== undefined) el.textContent = texto;
    return el;
  };
  const pesos = (valor) => new Intl.NumberFormat('es-CO', {
    style: 'currency', currency: 'COP', maximumFractionDigits: 0,
  }).format(valor);

  // Tarifas del propio agendamiento (constantes globales de ba-agendamiento.js), solo para leer.
  // eslint-disable-next-line no-undef
  const tarifas = typeof TARIFAS_2026 !== 'undefined' ? TARIFAS_2026 : null;
  // eslint-disable-next-line no-undef
  const tarifasLavado = typeof TARIFAS_MOTOLAVADO !== 'undefined' ? TARIFAS_MOTOLAVADO : null;

  /** Escoge un valor en una lista original como si lo hiciera el cliente. */
  const escoger = (select, valor) => {
    if (select.value === valor) return;
    select.value = valor;
    select.dispatchEvent(new Event('change', { bubbles: true }));
  };

  caja.classList.add('is-capa');

  /* ---------- Encabezados de paso (01 y 02) ---------- */
  const paso = (numero, titulo, texto) => {
    const el = crear('div', 'br-agenda__paso');
    el.append(crear('span', 'br-agenda__paso-numero', numero));
    const textos = crear('div');
    textos.append(crear('h2', 'br-agenda__paso-titulo', titulo), crear('p', 'br-agenda__paso-texto', texto));
    el.append(textos);
    return el;
  };

  form.prepend(paso('01', 'Tu servicio', 'Elige qué necesita tu moto. El valor se calcula al instante.'));
  const paso2 = paso('02', 'Tus datos y tu hora', 'Con esto te confirmamos la cita por correo.');
  const divisor = form.querySelector('.ba-divider');
  if (divisor) divisor.replaceWith(paso2);

  /* ---------- Botones que escogen en las listas originales ---------- */
  const botoneras = [];

  /**
   * @param {HTMLSelectElement} select Lista original.
   * @param {string} clase Variante visual.
   * @param {(op: HTMLOptionElement) => {titulo: string, detalle?: string}} contenido Texto de cada botón.
   */
  const botonera = (select, clase, contenido) => {
    const grupo = crear('div', `br-opciones br-opciones--${clase}`);
    grupo.setAttribute('role', 'radiogroup');
    const etiqueta = select.closest('.ba-form-group')?.querySelector('label');
    if (etiqueta) {
      etiqueta.id = etiqueta.id || `br-etq-${select.id}`;
      grupo.setAttribute('aria-labelledby', etiqueta.id);
    }
    select.classList.add('br-oculto');
    select.tabIndex = -1;
    select.after(grupo);

    const pintar = () => {
      grupo.replaceChildren();
      [...select.options].forEach((op) => {
        const boton = crear('button', 'br-opcion');
        boton.type = 'button';
        boton.setAttribute('role', 'radio');
        const { titulo, detalle } = contenido(op);
        boton.append(crear('span', 'br-opcion__titulo', titulo));
        if (detalle) boton.append(crear('span', 'br-opcion__detalle', detalle));
        const activa = op.value === select.value;
        boton.classList.toggle('is-activa', activa);
        boton.setAttribute('aria-checked', String(activa));
        boton.disabled = op.disabled;
        boton.addEventListener('click', () => escoger(select, op.value));
        grupo.append(boton);
      });
    };
    botoneras.push(pintar);
    return grupo;
  };

  const tipoServicio = $('tipo_servicio');
  const motor = $('engine_type');
  const combo = $('service_type');
  const referencia = $('cyl_select');
  const paquete = $('motolavado_paquete');
  const cilindraje = $('motolavado_cc');
  const hora = $('booking_time');
  const fecha = $('booking_date');
  const placa = $('user_plate');

  // Placa: solo letras y números, sin guiones ni espacios (así la OT la encuentra al buscarla).
  if (placa) {
    placa.setAttribute('pattern', '[A-Z0-9]+');
    placa.setAttribute('autocapitalize', 'characters');
    placa.title = 'Solo letras y números, sin guiones ni espacios';
    placa.addEventListener('input', () => {
      const limpia = placa.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
      if (limpia !== placa.value) placa.value = limpia;
    });
  }

  botonera(tipoServicio, 'servicio', (op) => ({
    titulo: op.text,
    detalle: op.value === 'motolavado' ? '4 paquetes · según cilindraje' : 'Combos · gasolina y eléctricas',
  }));

  botonera(motor, 'vehiculo', (op) => ({ titulo: op.text }));

  botonera(combo, 'combo', (op) => {
    const fila = tarifas?.[motor.value]?.[referencia.value];
    const valor = fila ? fila[op.value] : 0;
    return {
      titulo: op.value,
      // Viaje dice "Solo gasolina" como en la propuesta (decisión del equipo).
      detalle: op.value === 'Viaje' ? 'Solo gasolina' : (valor ? pesos(valor) : ''),
    };
  });

  botonera(paquete, 'combo', (op) => {
    const valor = tarifasLavado?.[cilindraje.value]?.[op.value];
    return { titulo: op.text, detalle: valor ? pesos(valor) : '' };
  });

  /* ---------- Horas como casillas (leen los cupos del agendamiento) ---------- */
  const grupoHora = hora.closest('.ba-form-group');
  const etiquetaHora = grupoHora.querySelector('label');
  const ayudaHora = crear('span', 'br-agenda__ayuda');
  etiquetaHora.append(ayudaHora);
  const casillas = crear('div', 'br-horas');
  casillas.setAttribute('role', 'radiogroup');
  etiquetaHora.id = 'br-etq-hora';
  casillas.setAttribute('aria-labelledby', etiquetaHora.id);
  hora.classList.add('br-oculto');
  hora.tabIndex = -1;
  hora.after(casillas);

  const pintarHoras = () => {
    ayudaHora.textContent = tipoServicio.value === 'motolavado' ? ' · cada hora' : ' · cada 2 horas';
    casillas.replaceChildren();
    [...hora.options].filter((op) => op.value).forEach((op) => {
      const boton = crear('button', 'br-hora');
      boton.type = 'button';
      boton.setAttribute('role', 'radio');
      const elegida = op.value === hora.value;
      let estado = '';
      if (op.disabled) estado = 'Completo';
      else if (elegida) estado = 'Elegida';
      else if (fecha.value) estado = 'Cupo';
      boton.append(crear('span', 'br-hora__valor', op.value));
      if (estado) boton.append(crear('span', 'br-hora__estado', estado));
      boton.disabled = op.disabled;
      boton.classList.toggle('is-activa', elegida);
      boton.setAttribute('aria-checked', String(elegida));
      boton.addEventListener('click', () => {
        escoger(hora, op.value);
        casillas.classList.remove('is-error');
      });
      casillas.append(boton);
    });
  };

  // Si intenta confirmar sin hora, se marca el bloque de horas (la validación sigue siendo la del navegador).
  $('btn-submit')?.addEventListener('click', () => {
    if (!hora.value) casillas.classList.add('is-error');
  });

  /* ---------- Valor estimado: detalle debajo del título ---------- */
  const cajaPrecio = form.querySelector('.ba-price-display');
  const precio = $('total-price');
  const detallePrecio = crear('span', 'br-precio__detalle');
  if (cajaPrecio) cajaPrecio.querySelector('span')?.after(detallePrecio);

  /* ---------- Bloque 02 en rejilla, como la propuesta ---------- */
  const datos = crear('div', 'br-agenda__datos');
  const desde = paso2.isConnected ? paso2 : null;
  if (desde) {
    const piezas = [];
    let nodo = desde.nextElementSibling;
    while (nodo) {
      piezas.push(nodo);
      nodo = nodo.nextElementSibling;
    }
    piezas.forEach((pieza) => datos.append(pieza));
    desde.after(datos);
  }
  // Cada campo recibe su área según su "name" (no se cambia nada del campo).
  datos.querySelectorAll('.ba-form-group').forEach((grupo) => {
    const campo = grupo.querySelector('[name]');
    if (campo) grupo.dataset.campo = campo.name;
  });
  const aviso = crear('p', 'br-agenda__aviso', 'Al confirmar te llega el resumen de la cita a tu correo.');
  $('btn-submit')?.after(aviso);

  /* ---------- Resumen de la cita (acompaña el scroll) ---------- */
  const lateral = crear('aside', 'br-agenda__lateral');
  const ticket = crear('section', 'br-ticket');
  ticket.setAttribute('aria-label', 'Resumen de tu cita');
  const cabecera = crear('div', 'br-ticket__cabecera');
  cabecera.append(crear('span', '', 'Orden de servicio'), crear('span', '', 'Borrador'));
  ticket.append(cabecera, crear('h2', 'br-ticket__titulo', 'Resumen de tu cita'));
  const lista = crear('dl', 'br-ticket__lista');
  const filas = {};
  ['Servicio', 'Vehículo', 'Combo', 'Referencia', 'Fecha', 'Hora de llegada', 'Placa'].forEach((nombre) => {
    const fila = crear('div', 'br-ticket__fila');
    const dt = crear('dt', '', nombre);
    const dd = crear('dd');
    fila.append(dt, dd);
    lista.append(fila);
    filas[nombre] = { dt, dd };
  });
  const total = crear('div', 'br-ticket__total');
  const totalValor = crear('strong');
  total.append(crear('span', '', 'Total estimado'), totalValor);
  ticket.append(lista, total);

  const antes = crear('section', 'br-antes');
  antes.append(crear('p', 'br-antes__titulo', 'Antes de llegar'));
  const puntos = crear('ul', 'br-antes__lista');
  [
    'Las horas marcadas como completas ya no tienen cupo.',
    'Mantenimiento y motolavado tienen horarios distintos.',
    'Recibes la confirmación en el correo que registres.',
  ].forEach((texto) => puntos.append(crear('li', '', texto)));
  antes.append(puntos);
  if (caja.dataset.whatsapp) {
    const enlace = crear('a', 'br-antes__whatsapp', '¿Dudas? Escríbenos por WhatsApp');
    enlace.href = caja.dataset.whatsapp;
    enlace.target = '_blank';
    enlace.rel = 'noopener';
    antes.append(enlace);
  }
  lateral.append(ticket, antes);
  caja.append(lateral);

  const textoOpcion = (select) => select.options[select.selectedIndex]?.text || '';
  const fechaLarga = (valor) => {
    if (!valor) return '';
    const [a, m, d] = valor.split('-').map(Number);
    return new Intl.DateTimeFormat('es-CO', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' })
      .format(new Date(a, m - 1, d));
  };
  const poner = (nombre, valor, vacio = 'Sin elegir') => {
    filas[nombre].dd.textContent = valor || vacio;
    filas[nombre].dd.classList.toggle('is-vacio', !valor);
  };

  const pintarResumen = () => {
    const lavado = tipoServicio.value === 'motolavado';
    poner('Servicio', textoOpcion(tipoServicio));
    poner('Vehículo', lavado ? '' : textoOpcion(motor), lavado ? 'No aplica' : 'Sin elegir');
    filas.Combo.dt.textContent = lavado ? 'Paquete' : 'Combo';
    poner('Combo', lavado ? paquete.value : combo.value);
    filas.Referencia.dt.textContent = lavado ? 'Cilindraje' : 'Referencia';
    poner('Referencia', lavado ? cilindraje.value : textoOpcion(referencia));
    poner('Fecha', fechaLarga(fecha.value));
    poner('Hora de llegada', hora.value);
    poner('Placa', placa.value.trim().toUpperCase(), 'Sin registrar');
    totalValor.textContent = precio.textContent;
    detallePrecio.textContent = `${lavado ? paquete.value : combo.value} · ${lavado ? cilindraje.value : textoOpcion(referencia)}`;
  };

  /* ---------- Mantener todo al día ---------- */
  const refrescar = () => {
    botoneras.forEach((pintar) => pintar());
    pintarHoras();
    pintarResumen();
  };

  // El agendamiento cambia valores por código (por ejemplo, Viaje → Básico con Moto Eléctrica),
  // así que después de cada cambio se vuelve a leer todo.
  let pendiente = false;
  const programar = () => {
    if (pendiente) return;
    pendiente = true;
    setTimeout(() => {
      pendiente = false;
      refrescar();
    }, 0);
  };
  form.addEventListener('change', programar);
  form.addEventListener('input', programar);
  // Las horas completas llegan después (consulta de disponibilidad): se observan las opciones.
  new MutationObserver(programar).observe(hora, { childList: true, subtree: true, attributes: true, characterData: true });
  new MutationObserver(programar).observe(referencia, { childList: true });
  new MutationObserver(programar).observe(precio, { childList: true, characterData: true, subtree: true });
  form.addEventListener('reset', () => setTimeout(refrescar, 0));

  refrescar();
  // ba-agendamiento.js llena referencias y horas al cargar la página: se vuelve a leer al terminar.
  document.addEventListener('DOMContentLoaded', programar);
  window.addEventListener('load', programar);
})();
