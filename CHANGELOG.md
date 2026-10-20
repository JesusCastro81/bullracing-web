# Cambios

Registro de lo que cambia en cada Pull Request. Lo más reciente va arriba.
Formato: [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

## [Sin publicar]

### Agregado
- Valoración con datos reales de Google: calificación 4,2 con estrellas proporcionales, "29 reseñas en Google", 3 reseñas reales de 5 estrellas (texto exacto, nombre e inicial), "Califica tu servicio" y "Ver todas en Google".
- Apariencia del agendamiento (Diseño 1, según la propuesta): capa visual `agendamiento-capa.js` + `skins/taller-agendamiento.css`. Botones para servicio, vehículo, combo y paquete, horas en casillas (las completas no se pueden escoger), valor estimado con detalle y resumen de la cita que acompaña el scroll. El formulario original no cambia: mismos campos, precios, cupos, backend y EmailJS; los botones solo escogen en sus listas.
- Video del taller que se reproduce al hacer clic también en el inicio (propuesta): componente `video-reproductor` compartido con la página Quiénes somos. Se activa al poner el archivo .mp4/.webm en `video` de `inc/datos-taller.php`; mientras no haya video, el recuadro queda igual.
- Página Términos y condiciones (Diseño 1, según la propuesta): índice fijo que salta a cada sección, marca la activa y llena la barra de avance de lectura; datos de la empresa. Texto legal copiado tal cual del sitio actual (7 secciones) en `inc/datos-terminos.php`; fecha de actualización pendiente.
- Página Quiénes somos (Diseño 1, tal cual el Word): banda con cifras (año, clientes, Bogotá), historia, video del taller que se reproduce ahí mismo (play/pausa, etiqueta "Reproduciendo" y barra de progreso), "Lo que hacemos" con tarjetas que se elevan y se marcan en rojo, foto del taller, equipo y valoración. Año, historia, video, fotos y nombres pendientes del taller (se muestran corchetes, nada inventado).
- Página de Contacto (Diseño 1, tal cual el Word): banda con foto, recuadro verde "Escríbenos al chat" con Abrir chat y Copiar número, dirección/teléfono/horario con [CONFIRMAR], plano con pin que late (mapa real al tocar), Cómo llegar y redes. Componente `banner-pagina` para las páginas internas. El menú marca la página actual.
- Sección de contacto (Diseño 1): mosaico con fotos (pendientes), clientes satisfechos, redes reales, horario, dirección, teléfono (WhatsApp, sin correo: el contacto es solo por WhatsApp) y mapa de Google que se carga solo al tocar "Ver mapa".
- Precarga del agendamiento: la barra del hero, "Agendar este plan" y los paquetes de motolavado mandan lo escogido y `precarga-agendamiento.js` lo marca en el formulario, sin modificar el agendamiento. Documentado en el README ("Conexión landing ↔ agendamiento").
- Sección de valoración (Diseño 1): calificación y 3 reseñas. Sin nombres ni reseñas inventadas: se muestran corchetes hasta tener reseñas reales en `inc/datos-taller.php`.
- Sección Quiénes somos (Diseño 1): años calculados desde el año de apertura, historia y video (pendientes de don Franz, se muestran los corchetes del diseño mientras tanto). Datos del taller: correo y enlaces reales de TikTok, Instagram y Facebook.
- Sección de motolavado (Diseño 1): antes y después con deslizador (mouse, dedo y teclado) y los 4 paquetes con su precio "desde" según las tarifas del agendamiento. Fotos reales pendientes (filtro `bullracing_motolavado_fotos`).
- Sección de planes (Diseño 1): fichas tipo orden de servicio, selector gasolina/eléctrica y tacómetro con el precio "desde" de cada plan según las tarifas del agendamiento. Lo que incluye cada plan está en `inc/datos-planes.php`, tal cual la página actual.
- Sección "Así entra y así sale tu moto" (4 pasos del taller), enlazada desde "El taller" en el menú.
- Header del inicio sobre la foto (igual al diseño), enlace "El taller" en el menú y botón flotante de WhatsApp en círculo.
- Hero del inicio (Diseño 1): imagen actual, título, estrellas, cotizar por WhatsApp, aviso de taller abierto/cerrado (horario, domingos y festivos) y barra con el precio según las tarifas del agendamiento.
- Piel "Taller industrial" (Diseño 1): colores, letras esténcil y mono locales.
- Tokens de diseño (`tokens.css`) en dos niveles: paleta de marca y tokens semánticos.
- Sistema de skins (`assets/css/skins/`) con la skin `base` neutra.
- Fuente Poppins local: el tema funciona sin internet.
- Componentes reutilizables: botón, estrellas y tarjeta.
- Datos del taller centralizados en `inc/datos-taller.php`.
- Header con logo, menú (con respaldo si no hay menú configurado), menú para celular y botón Agendar.
- Footer con datos de contacto, estrellas, enlaces y TecnoParque SENA como asesor técnico.
- Script `dev/verificar.ps1` para revisar el proyecto antes de cada Pull Request.

### Cambiado
- Footer del Diseño 1 (tal cual el Word): logo, "Rueda libre, rueda seguro" y "Asesoría técnica: TecnoParque Nodo Bogotá · SENA" en recuadro punteado, sin enlace ni contacto. El logo de TecnoParque aparece solo al guardar `assets/img/tecnoparque-nodo-bogota.png` (o .svg). Segunda línea con © y enlace a Términos y condiciones.
- `bullracing_url_pagina()` acepta el filtro `bullracing_url_pagina` (permite apuntar a otra página sin tocar el tema).
- Versión del tema 0.3.0 para que los navegadores descarguen el CSS y JS nuevos.
- Motolavado: cada paquete lleva al agendamiento; al pasar el mouse se pone rojo y aparece "Agendar →" (Full Shine queda rojo por defecto).
- Hero: el botón pasa a "Cotizar mecánica general" con ícono de burbuja y teléfono (el verde ya indica WhatsApp), para diferenciarlo de la burbuja flotante.
- Hero: botón "Cotizar por WhatsApp" en verde, línea punteada en los campos de la barra y marco punteado con esquinas rojas en la barra y en el botón de cotizar.
- Footer: el asesor técnico se nombra completo, TecnoParque Nodo Bogotá · SENA.
- Orden de carga de estilos: la skin carga después de `main.css` para que sus ajustes apliquen.
- Se quitó el botón Agendar del header (en el diseño 1 el agendar está en el hero).
- `main.css` usa solo tokens; se quitaron los colores escritos a mano.
- Se quitó la carga de Google Fonts.

## 2026-10-10

### Agregado
- Estructura inicial del repositorio, tema `bullracing` y plugin `bullracing-core` con tarifas 2026, rol Taller y API de tarifas.
- Plantilla de Pull Request y revisión automática de sintaxis PHP (GitHub Actions).
