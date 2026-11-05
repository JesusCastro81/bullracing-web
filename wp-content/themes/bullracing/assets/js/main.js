/**
 * Bull Racing · Interacciones del sitio.
 * Los datos del servidor llegan en window.bullracingDatos (se arma en inc/setup.php).
 * Sin librerías: JavaScript del navegador.
 *
 * Índice (orden de la página):
 *   00 Global: datos, formato de precios y tarifas
 *   01 Hero: barra de precio y aviso abierto/cerrado
 *   03 Planes
 *   04 Motolavado
 *   06 Video del taller (Nosotros y Quiénes somos)
 *   07 Contacto: carrusel de fotos
 *   08 Páginas internas: copiar número (Contacto) y Términos
 */
(() => {
  'use strict';

  /* ---------- 00 Global ---------- */

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

  /** Pone en un enlace de "Agendar" lo que escogió el cliente, para precargar el formulario. */
  const enlaceConEleccion = (enlace, eleccion) => {
    const url = new URL(enlace.getAttribute('href'), window.location.href);
    ['servicio', 'motor', 'plan', 'referencia'].forEach((dato) => url.searchParams.delete(dato));
    Object.entries(eleccion).forEach(([dato, valor]) => {
      if (valor) url.searchParams.set(dato, valor);
    });
    enlace.setAttribute('href', url.toString());
  };

  /* ---------- 01 Hero: barra para ver el precio antes de agendar ---------- */

  // Grupos de la lista, en el orden del agendamiento (salen de inc/datos-planes.php).
  const GRUPOS = Array.isArray(datos.grupos) ? datos.grupos : [];

  // Elección de entrada: el plan recomendado a gasolina con la referencia del diseño.
  const POR_DEFECTO = {
    valor: `mantenimiento|gasolina|${datos.planInicial || ''}`,
    referencia: datos.refInicial || '',
  };

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

          // "Escoge día y hora" y "Agendar" llevan lo escogido al formulario de agendamiento.
          form.querySelectorAll('[data-br-agendar]').forEach((enlace) =>
            enlaceConEleccion(enlace, { servicio, motor, plan, referencia: selReferencia.value })
          );
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

  /* ---------- 01 Hero: taller abierto / cerrado ---------- */

  // Horario del taller (hora de Bogotá): llega desde inc/datos-taller.php. Domingos y festivos: cerrado.
  // dias: 1 = lunes … 6 = sábado. Sin horario no se muestra el aviso: mejor nada que un dato equivocado.
  const HORARIO = Array.isArray(datos.horario) ? datos.horario : [];

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
      diaSemana: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].indexOf(partes.weekday),
    };
  };

  const tallerAbierto = () => {
    const ahora = ahoraEnBogota();
    if (festivosColombia(ahora.anio).has(ahora.clave)) return false;
    const turno = HORARIO.find((fila) => fila.dias.includes(ahora.diaSemana));
    return Boolean(turno) && ahora.hora >= turno.abre && ahora.hora < turno.cierra;
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

  document.querySelectorAll('[data-br-estado]').forEach((elemento) => {
    if (HORARIO.length) {
      iniciarEstado(elemento);
    } else {
      elemento.hidden = true;
    }
  });

  /* ---------- 03 Planes: tarjetas con desplegable + tacómetro ---------- */

  // Cómo se nombra cada motor en el detalle del tacómetro (sale de inc/datos-planes.php).
  const nombreMotor = (motor) => (datos.motores && datos.motores[motor] ? datos.motores[motor].frase : '');

  const iniciarPlanes = (seccion) => {
    const botonesMotor = [...seccion.querySelectorAll('[data-br-motor]')];
    const grupos = [...seccion.querySelectorAll('[data-br-grupo]')];
    const tarjetas = [...seccion.querySelectorAll('[data-br-plan]')];
    const aguja = seccion.querySelector('[data-br-aguja]');
    const precioGrande = seccion.querySelector('[data-br-precio-grande]');
    const planNombre = seccion.querySelector('[data-br-plan-nombre]');
    const motorNombre = seccion.querySelector('[data-br-motor-nombre]');
    const refNombre = seccion.querySelector('[data-br-ref-nombre]');
    if (!tarjetas.length) return;

    const selectDe = (tarjeta) => tarjeta.querySelector('[data-br-referencia]');
    const precioDe = (tarjeta) => Number(selectDe(tarjeta).selectedOptions[0]?.dataset.precio || 0);

    // Escala del tacómetro: el precio más alto de todos queda casi al tope.
    const todos = tarjetas.flatMap((t) => [...selectDe(t).options].map((o) => Number(o.dataset.precio || 0)));
    const escala = Math.ceil((Math.max(...todos, 1) * 1.125) / 50000) * 50000;

    let motor = 'gasolina';
    let elegida = tarjetas.find((t) => t.getAttribute('aria-current') === 'true') || tarjetas[0];

    // Cada tarjeta: precio de la referencia escogida y el Agendar con esa elección.
    const pintarTarjeta = (tarjeta) => {
      const select = selectDe(tarjeta);
      tarjeta.querySelector('[data-br-valor]').textContent = formatoPrecio.format(precioDe(tarjeta));
      tarjeta.querySelector('[data-br-desde]').textContent = select.selectedIndex === 0 ? 'Desde' : 'Precio';
      enlaceConEleccion(tarjeta.querySelector('[data-br-agendar]'), {
        servicio: 'mantenimiento',
        motor: tarjeta.dataset.motor,
        plan: tarjeta.dataset.brPlan,
        referencia: select.value,
      });
    };

    // Pestañas, tarjeta elegida y tacómetro.
    const pintar = () => {
      botonesMotor.forEach((b) => b.setAttribute('aria-pressed', String(b.dataset.brMotor === motor)));
      grupos.forEach((g) => {
        g.hidden = g.dataset.brGrupo !== motor;
      });
      tarjetas.forEach((t) => t.setAttribute('aria-current', String(t === elegida)));

      const valor = precioDe(elegida);
      precioGrande.textContent = formatoPrecio.format(valor);
      planNombre.textContent = elegida.dataset.brPlan;
      motorNombre.textContent = nombreMotor(motor);
      refNombre.textContent = selectDe(elegida).value;
      aguja.style.transform = `rotate(${-90 + Math.min(valor / escala, 1) * 180}deg)`;
    };

    botonesMotor.forEach((b) =>
      b.addEventListener('click', () => {
        motor = b.dataset.brMotor;
        const delMotor = tarjetas.filter((t) => t.dataset.motor === motor);
        // Mismo plan en el otro motor; si no existe (Viaje en eléctricas), el recomendado.
        elegida =
          delMotor.find((t) => t.dataset.brPlan === elegida.dataset.brPlan) ||
          delMotor.find((t) => t.classList.contains('br-plan--recomendado')) ||
          delMotor[0];
        pintar();
      })
    );

    tarjetas.forEach((t) => {
      t.addEventListener('click', (evento) => {
        if (evento.target.closest('a, select')) return;
        elegida = t;
        pintar();
      });
      selectDe(t).addEventListener('change', () => {
        pintarTarjeta(t);
        elegida = t;
        pintar();
      });
      pintarTarjeta(t);
    });

    // Celular: un punto por plan para ir a cada tarjeta y ver en cuál va el cliente.
    grupos.forEach((grupo) => {
      const fila = grupo.querySelector('[data-br-fila]');
      const puntos = [...grupo.querySelectorAll('[data-br-punto]')];
      const deFila = [...fila.querySelectorAll('[data-br-plan]')];
      const posicion = (k) => deFila[k].offsetLeft - deFila[0].offsetLeft;
      const marcar = () => {
        const alFinal = fila.scrollLeft >= fila.scrollWidth - fila.clientWidth - 2;
        const actual = alFinal
          ? deFila.length - 1
          : deFila.findIndex(
              (t, k) => k === deFila.length - 1 || fila.scrollLeft < (posicion(k) + posicion(k + 1)) / 2
            );
        puntos.forEach((p, k) => p.setAttribute('aria-current', String(k === actual)));
      };
      puntos.forEach((p, k) =>
        p.addEventListener('click', () =>
          fila.scrollTo({
            left: posicion(k),
            behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
          })
        )
      );
      fila.addEventListener('scroll', marcar, { passive: true });
      marcar();
    });

    pintar();
  };

  document.querySelectorAll('[data-br-planes]').forEach(iniciarPlanes);

  /* ---------- 04 Motolavado: antes y después ---------- */

  // El deslizador es un <input type="range">: funciona con mouse, dedo y teclado.
  document.querySelectorAll('[data-br-comparar]').forEach((caja) => {
    const rango = caja.querySelector('[data-br-comparar-rango]');
    const mover = () => caja.style.setProperty('--corte', `${rango.value}%`);
    rango.addEventListener('input', mover);
    mover();
  });

  /* ---------- 06 Video del taller (Nosotros y Quiénes somos) ---------- */

  // Play/pausa, etiqueta "Reproduciendo" y barra de progreso.
  document.querySelectorAll('[data-br-video]').forEach((caja) => {
    const video = caja.querySelector('video');
    const boton = caja.querySelector('[data-br-video-boton]');
    const etiqueta = caja.querySelector('[data-br-video-etiqueta]');
    const barra = caja.querySelector('[data-br-video-barra]');
    const textoEtiqueta = etiqueta.textContent;
    const textoBoton = boton.getAttribute('aria-label');

    const pintar = () => {
      const sonando = !video.paused && !video.ended;
      caja.classList.toggle('is-reproduciendo', sonando);
      etiqueta.textContent = sonando ? etiqueta.dataset.reproduciendo : textoEtiqueta;
      boton.setAttribute('aria-label', sonando ? boton.dataset.pausar : textoBoton);
    };

    boton.addEventListener('click', () => (video.paused ? video.play() : video.pause()));
    video.addEventListener('click', () => (video.paused ? video.play() : video.pause()));
    ['play', 'pause', 'ended'].forEach((evento) => video.addEventListener(evento, pintar));
    video.addEventListener('timeupdate', () => {
      barra.style.width = video.duration ? `${(video.currentTime / video.duration) * 100}%` : '0';
    });
  });

  /* ---------- 07 Contacto: carrusel de fotos del taller ---------- */

  // Al pasar el mouse las fotos se encadenan solas; al quitarlo se detiene.
  // En celular (sin mouse) y con teclado, cada toque o Enter pasa a la siguiente.
  document.querySelectorAll('[data-br-carrusel]').forEach((caja) => {
    const fotos = [...caja.querySelectorAll('.br-carrusel__foto')];
    const puntos = [...caja.querySelectorAll('.br-carrusel__puntos span')];
    const texto = caja.querySelector('[data-br-carrusel-texto]');
    const sinMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let actual = 0;
    let reloj = null;

    const mostrar = (indice) => {
      actual = (indice + fotos.length) % fotos.length;
      fotos.forEach((foto, i) => foto.classList.toggle('is-activa', i === actual));
      puntos.forEach((punto, i) => punto.classList.toggle('is-activo', i === actual));
      if (texto) texto.textContent = fotos[actual].dataset.texto || '';
    };
    const siguiente = () => mostrar(actual + 1);
    const detener = () => {
      clearInterval(reloj);
      reloj = null;
    };

    // pointerenter + pointerType: solo un mouse real arranca el avance automático
    // (un toque en el celular también "entra", pero ahí manda el click).
    caja.addEventListener('pointerenter', (evento) => {
      if (evento.pointerType !== 'mouse' || sinMovimiento || reloj) return;
      siguiente();
      reloj = setInterval(siguiente, 1500);
    });
    caja.addEventListener('pointerleave', detener);
    caja.addEventListener('click', (evento) => {
      if (evento.pointerType === 'mouse') return; // Con mouse ya avanza solo.
      siguiente();
    });
    caja.addEventListener('keydown', (evento) => {
      if (evento.key === 'Enter' || evento.key === ' ') {
        evento.preventDefault();
        siguiente();
      }
    });
  });

  /* ---------- 08 Página Contacto: copiar número ---------- */

  document.querySelectorAll('[data-br-copiar]').forEach((boton) => {
    const textoOriginal = boton.textContent.trim();
    boton.addEventListener('click', async () => {
      const numero = boton.dataset.brCopiar;
      try {
        await navigator.clipboard.writeText(numero);
      } catch (e) {
        // Navegadores sin permiso de portapapeles: se copia con una caja de texto temporal.
        const caja = Object.assign(document.createElement('textarea'), { value: numero });
        document.body.append(caja);
        caja.select();
        document.execCommand('copy');
        caja.remove();
      }
      boton.textContent = boton.dataset.copiado;
      boton.classList.add('is-copiado');
      setTimeout(() => {
        boton.textContent = textoOriginal;
        boton.classList.remove('is-copiado');
      }, 2500);
    });
  });

  /* ---------- 08 Página Términos: índice, sección activa y avance de lectura ---------- */

  document.querySelectorAll('[data-br-legal]').forEach((legal) => {
    const enlaces = [...legal.querySelectorAll('[data-br-legal-enlace]')];
    const secciones = [...legal.querySelectorAll('[data-br-legal-seccion]')];
    const barra = legal.querySelector('[data-br-legal-barra]');
    const estado = legal.querySelector('[data-br-legal-estado]');
    const texto = legal.querySelector('.br-legal__texto');
    const plantilla = estado.dataset.plantilla;
    let marcada = -1;

    const marcar = (indice) => {
      if (indice === marcada) return;
      marcada = indice;
      enlaces.forEach((enlace, i) => {
        enlace.classList.toggle('is-activo', i === indice);
        if (i === indice) enlace.setAttribute('aria-current', 'location');
        else enlace.removeAttribute('aria-current');
      });
    };

    const actualizar = () => {
      // Sección activa: la última cuyo título ya pasó la parte de arriba de la pantalla.
      const linea = window.innerHeight * 0.3;
      let activa = 0;
      secciones.forEach((seccion, i) => {
        if (seccion.getBoundingClientRect().top <= linea) activa = i;
      });

      // Avance: cuánto del texto legal ya se leyó (100 % al llegar al final del texto).
      const caja = texto.getBoundingClientRect();
      const recorrido = caja.height - window.innerHeight;
      const leido = recorrido > 0 ? Math.min(1, Math.max(0, -caja.top / recorrido)) : 1;
      if (leido >= 0.995) activa = secciones.length - 1;

      marcar(activa);
      const porcentaje = Math.round(leido * 100);
      barra.style.width = `${porcentaje}%`;
      estado.textContent = plantilla.replace('{n}', activa + 1).replace('{p}', porcentaje);
    };

    // Al tocar una sección del índice se marca de una vez (el scroll suave lo hace el CSS).
    enlaces.forEach((enlace, i) => enlace.addEventListener('click', () => marcar(i)));

    let pendiente = false;
    window.addEventListener('scroll', () => {
      if (pendiente) return;
      pendiente = true;
      requestAnimationFrame(() => {
        pendiente = false;
        actualizar();
      });
    }, { passive: true });
    window.addEventListener('resize', actualizar);
    actualizar();
  });
})();
