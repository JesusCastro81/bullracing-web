# Cambios

Registro de lo que cambia en cada Pull Request. Lo más reciente va arriba.
Formato: [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

## [Sin publicar]

### Agregado
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
- `main.css` usa solo tokens; se quitaron los colores escritos a mano.
- Se quitó la carga de Google Fonts.

## 2026-10-10

### Agregado
- Estructura inicial del repositorio, tema `bullracing` y plugin `bullracing-core` con tarifas 2026, rol Taller y API de tarifas.
- Plantilla de Pull Request y revisión automática de sintaxis PHP (GitHub Actions).
