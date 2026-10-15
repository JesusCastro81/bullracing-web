/**
 * Bull Racing · Interacciones de la landing.
 * Los datos del servidor llegan en window.bullracingDatos (ver functions.php).
 * Sin librerías: JavaScript del navegador.
 */
(() => {
  'use strict';

  const datos = window.bullracingDatos || {};

  // Formato del diseño: $360.000 (sin espacio después del signo).
  const formatoNumero = new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 });
  const formatoPrecio = { format: (valor) => `$${formatoNumero.format(valor)}` };

  /** Pide las tarifas (las mismas del agendamiento) una sola vez y las comparte. */
  let tarifasPromesa = null;
  const obtenerTarifas = () => {
    if (!tarifasPromesa) {
      tarifasPromesa = datos.apiTarifas
        ? fetch(datos.apiTarifas, { headers: { Accept: 'application/json' } })
            .then((r) => (r.ok ? r.json() : Promise.reject(new Error(`HTTP ${r.status}`))))
            .then((json) => (Array.isArray(json) ? json : []))
        : Promise.reject(new Error('Sin URL de tarifas'));
    }
    return tarifasPromesa;
  };

  const unicos = (lista) => [...new Set(lista)];

  /* ---------- Hero: barra para ver el precio antes de agendar ---------- */

  // Grupos en el mismo orden del agendamiento.
  const GRUPOS = [
    { servicio: 'mantenimiento', motor: 'gasolina', titulo: 'Moto a gasolina' },
    { servicio: 'mantenimiento', motor: 'electrica', titulo: 'Moto eléctrica' },
    { servicio: 'motolavado', motor: 'gasolina', titulo: 'Motolavado' },
  ];

  // Lo que muestra el diseño por defecto: Mantenimiento preventivo · Naked < 200.
  const POR_DEFECTO = { valor: 'mantenimiento|gasolina|Preventivo', referencia: 'Naked < 200' };

  const nombreServicio = (servicio, plan) =>
    servicio === 'mantenimiento' ? `Mantenimiento ${plan.toLowerCase()}` : `Motolavado ${plan}`;

  const iniciarCotizador = (form) => {
    const selServicio = form.querySelector('[data-br-servicio]');
    const selReferencia = form.querySelector('[data-br-referencia]');
    const salidaPrecio = form.querySelector('[data-br-precio]');

    // El agendamiento real es otra página: Enter no envía nada.
    form.addEventListener('submit', (e) => e.preventDefault());

    obtenerTarifas()
      .then((tarifas) => {
        const elegido = () => {
          const [servicio, motor, plan] = selServicio.value.split('|');
          return { servicio, motor, plan };
        };

        const llenarServicios = () => {
          const grupos = GRUPOS.map(({ servicio, motor, titulo }) => {
            const planes = unicos(
              tarifas.filter((t) => t.servicio === servicio && t.tipo_motor === motor).map((t) => t.plan)
            );
            const grupo = document.createElement('optgroup');
            grupo.label = titulo;
            grupo.append(
              ...planes.map((plan) => new Option(nombreServicio(servicio, plan), `${servicio}|${motor}|${plan}`))
            );
            return grupo;
          }).filter((g) => g.children.length);
          selServicio.replaceChildren(...grupos);
        };

        const llenarReferencias = () => {
          const { servicio, motor } = elegido();
          const anterior = selReferencia.value;
          const refs = unicos(
            tarifas.filter((t) => t.servicio === servicio && t.tipo_motor === motor).map((t) => t.referencia)
          );
          selReferencia.replaceChildren(...refs.map((r) => new Option(r, r)));
          if (refs.includes(anterior)) selReferencia.value = anterior;
        };

        const actualizarPrecio = () => {
          const { servicio, motor, plan } = elegido();
          const tarifa = tarifas.find(
            (t) =>
              t.servicio === servicio &&
              t.tipo_motor === motor &&
              t.plan === plan &&
              t.referencia === selReferencia.value
          );
          salidaPrecio.textContent = tarifa ? formatoPrecio.format(Number(tarifa.precio)) : '';
        };

        llenarServicios();
        selServicio.value = POR_DEFECTO.valor;
        if (!selServicio.value) selServicio.selectedIndex = 0;
        llenarReferencias();
        selReferencia.value = POR_DEFECTO.referencia;
        if (!selReferencia.value) selReferencia.selectedIndex = 0;
        actualizarPrecio();

        selServicio.disabled = false;
        selReferencia.disabled = false;
        selServicio.addEventListener('change', () => {
          llenarReferencias();
          actualizarPrecio();
        });
        selReferencia.addEventListener('change', actualizarPrecio);
      })
      .catch(() => {
        // Sin tarifas no se muestra ningún precio: queda el botón Agendar sin valor.
        salidaPrecio.textContent = '';
      });
  };

  document.querySelectorAll('[data-br-cotizador]').forEach(iniciarCotizador);

  /* ---------- Planes: fichas + tacómetro ---------- */

  const NOMBRE_MOTOR = { gasolina: 'moto a gasolina', electrica: 'moto eléctrica' };

  const iniciarPlanes = (seccion) => {
    const botonesMotor = [...seccion.querySelectorAll('[data-br-motor]')];
    const fichas = [...seccion.querySelectorAll('[data-br-plan]')];
    const aguja = seccion.querySelector('[data-br-aguja]');
    const precioGrande = seccion.querySelector('[data-br-precio-grande]');
    const planNombre = seccion.querySelector('[data-br-plan-nombre]');
    const motorNombre = seccion.querySelector('[data-br-motor-nombre]');

    const precioDe = (ficha, motor) => Number(ficha.dataset[`precio${motor[0].toUpperCase()}${motor.slice(1)}`] || 0);
    const texto = (valor) => (valor ? formatoPrecio.format(valor) : 'No aplica');

    // Escala del tacómetro: el plan más caro queda casi al tope (como en el diseño).
    const todos = fichas.flatMap((f) => ['gasolina', 'electrica'].map((m) => precioDe(f, m)));
    const escala = Math.ceil((Math.max(...todos, 1) * 1.125) / 50000) * 50000;

    let motor = 'gasolina';
    let elegida = fichas.find((f) => f.getAttribute('aria-pressed') === 'true') || fichas[0];

    const pintar = () => {
      botonesMotor.forEach((b) => b.setAttribute('aria-pressed', String(b.dataset.brMotor === motor)));

      fichas.forEach((f) => {
        const valor = precioDe(f, motor);
        f.setAttribute('aria-pressed', String(f === elegida));
        f.classList.toggle('is-no-aplica', !valor);
        f.querySelector('[data-br-ficha-precio]').textContent = texto(valor);
        f.querySelectorAll('[data-br-lista]').forEach((l) => {
          l.hidden = l.dataset.brLista !== motor;
        });
      });

      const valor = precioDe(elegida, motor);
      precioGrande.textContent = texto(valor);
      planNombre.textContent = elegida.dataset.brPlan;
      motorNombre.textContent = NOMBRE_MOTOR[motor];
      aguja.style.transform = `rotate(${-90 + Math.min(valor / escala, 1) * 180}deg)`;
    };

    botonesMotor.forEach((b) =>
      b.addEventListener('click', () => {
        motor = b.dataset.brMotor;
        // Si el plan elegido no aplica para este motor, se pasa al recomendado.
        if (!precioDe(elegida, motor)) {
          elegida = fichas.find((f) => precioDe(f, motor)) || elegida;
        }
        pintar();
      })
    );

    fichas.forEach((f) =>
      f.addEventListener('click', () => {
        elegida = f;
        pintar();
      })
    );

    pintar();
  };

  document.querySelectorAll('[data-br-planes]').forEach(iniciarPlanes);

  /* ---------- Motolavado: antes y después ---------- */

  // El deslizador es un <input type="range">: funciona con mouse, dedo y teclado.
  document.querySelectorAll('[data-br-comparar]').forEach((caja) => {
    const rango = caja.querySelector('[data-br-comparar-rango]');
    const mover = () => caja.style.setProperty('--corte', `${rango.value}%`);
    rango.addEventListener('input', mover);
    mover();
  });

  /* ---------- Hero: taller abierto / cerrado ---------- */

  // Horario del taller: lunes a sábado de 8:00 a 18:00, hora de Bogotá. Cerrado domingos y festivos.
  const HORARIO = { abre: 8, cierra: 18 };

  /** Domingo de Pascua (algoritmo gregoriano anónimo). Mes base 0. */
  const pascua = (anio) => {
    const a = anio % 19;
    const b = Math.floor(anio / 100);
    const c = anio % 100;
    const d = Math.floor(b / 4);
    const e = b % 4;
    const f = Math.floor((b + 8) / 25);
    const g = Math.floor((b - f + 1) / 3);
    const h = (19 * a + b - d - g + 15) % 30;
    const i = Math.floor(c / 4);
    const k = c % 4;
    const l = (32 + 2 * e + 2 * i - h - k) % 7;
    const m = Math.floor((a + 11 * h + 22 * l) / 451);
    const mes = Math.floor((h + l - 7 * m + 114) / 31) - 1;
    const dia = ((h + l - 7 * m + 114) % 31) + 1;
    return new Date(Date.UTC(anio, mes, dia));
  };

  const sumarDias = (fecha, dias) => new Date(fecha.getTime() + dias * 86400000);

  /** Ley Emiliani: si el festivo no cae lunes, se pasa al lunes siguiente. */
  const alLunes = (fecha) => sumarDias(fecha, (8 - fecha.getUTCDay()) % 7);

  const claveDia = (fecha) => fecha.toISOString().slice(0, 10);

  /** Festivos de Colombia de un año, como 'AAAA-MM-DD'. */
  const festivosColombia = (anio) => {
    const dia = (mes, d) => new Date(Date.UTC(anio, mes - 1, d));
    const p = pascua(anio);
    return new Set(
      [
        // Fijos.
        dia(1, 1), dia(5, 1), dia(7, 20), dia(8, 7), dia(12, 8), dia(12, 25),
        // Se corren al lunes.
        ...[dia(1, 6), dia(3, 19), dia(6, 29), dia(8, 15), dia(10, 12), dia(11, 1), dia(11, 11)].map(alLunes),
        // Según la Pascua.
        sumarDias(p, -3), // Jueves Santo.
        sumarDias(p, -2), // Viernes Santo.
        sumarDias(p, 43), // Ascensión.
        sumarDias(p, 64), // Corpus Christi.
        sumarDias(p, 71), // Sagrado Corazón.
      ].map(claveDia)
    );
  };

  /** Fecha y hora actuales en Bogotá, sin importar la zona del computador del cliente. */
  const ahoraEnBogota = () => {
    const partes = Object.fromEntries(
      new Intl.DateTimeFormat('en-CA', {
        timeZone: 'America/Bogota',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        hourCycle: 'h23',
        weekday: 'short',
      })
        .formatToParts(new Date())
        .map(({ type, value }) => [type, value])
    );
    return {
      anio: Number(partes.year),
      clave: `${partes.year}-${partes.month}-${partes.day}`,
      hora: Number(partes.hour),
      domingo: partes.weekday === 'Sun',
    };
  };

  const tallerAbierto = () => {
    const ahora = ahoraEnBogota();
    if (ahora.domingo || festivosColombia(ahora.anio).has(ahora.clave)) return false;
    return ahora.hora >= HORARIO.abre && ahora.hora < HORARIO.cierra;
  };

  const iniciarEstado = (elemento) => {
    const texto = elemento.querySelector('[data-br-estado-texto]');
    const pintar = () => {
      const abierto = tallerAbierto();
      elemento.classList.toggle('is-cerrado', !abierto);
      texto.textContent = abierto ? texto.dataset.abierto : texto.dataset.cerrado;
    };
    pintar();
    setInterval(pintar, 60000); // Se actualiza solo, sin recargar la página.
  };

  document.querySelectorAll('[data-br-estado]').forEach(iniciarEstado);
})();
