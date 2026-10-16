/**
 * Bull Racing · Precarga del formulario de agendamiento.
 *
 * Se carga SOLO en la página de agendamiento. Lee del enlace lo que el cliente escogió
 * en la landing (barra del hero, tacómetro de planes o paquetes de motolavado) y escoge
 * esas mismas opciones en el formulario, como si el cliente hubiera hecho clic.
 *
 * No modifica el agendamiento: solo cambia el valor de sus listas y dispara el evento
 * "change", así el formulario recalcula precio y horarios con sus propias funciones.
 * Si un dato no existe en el formulario, se ignora y queda la opción por defecto.
 *
 * Datos del enlace: ?servicio=mantenimiento|motolavado&motor=gasolina|electrica&plan=...&referencia=...
 */
(() => {
  'use strict';

  const parametros = new URLSearchParams(window.location.search);
  const servicio = parametros.get('servicio');
  if (!servicio) return;

  // Valores que usa el formulario del agendamiento para el tipo de vehículo.
  const MOTOR_FORMULARIO = {
    gasolina: 'Moto Gasolina',
    electrica: 'Moto Eléctrica / Scooter',
  };

  /** Escoge una opción (por valor o por texto) y avisa al formulario. */
  const escoger = (id, buscado, porTexto = false) => {
    const lista = document.getElementById(id);
    if (!lista || !buscado) return false;

    const opcion = [...lista.options].find(
      (o) => !o.disabled && (porTexto ? o.text.trim() === buscado : o.value === buscado)
    );
    if (!opcion) return false;

    lista.value = opcion.value;
    lista.dispatchEvent(new Event('change', { bubbles: true }));
    return true;
  };

  const precargar = () => {
    if (!document.getElementById('form-motos-bull')) return;

    const plan = parametros.get('plan');
    const referencia = parametros.get('referencia');

    // Versiones viejas del formulario no tienen "Tipo de Servicio" (solo mantenimiento).
    const tieneTipo = Boolean(document.getElementById('tipo_servicio'));
    if (tieneTipo && !escoger('tipo_servicio', servicio)) return;
    if (!tieneTipo && servicio !== 'mantenimiento') return;

    if (servicio === 'motolavado') {
      escoger('motolavado_paquete', plan);
      escoger('motolavado_cc', referencia);
      return;
    }

    // Mantenimiento: primero el motor (rellena las referencias), luego plan y referencia.
    escoger('engine_type', MOTOR_FORMULARIO[parametros.get('motor')]);
    escoger('service_type', plan);
    escoger('cyl_select', referencia, true); // Las referencias se buscan por su texto (ej. "750W").
  };

  // Después de que el agendamiento termine de iniciar su formulario.
  if (document.readyState === 'complete') {
    precargar();
  } else {
    window.addEventListener('load', precargar);
  }
})();
