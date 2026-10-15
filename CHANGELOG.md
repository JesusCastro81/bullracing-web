# Cambios

Registro de lo que cambia en cada Pull Request. Lo más reciente va arriba.
Formato: [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

## [Sin publicar]

### Agregado
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
