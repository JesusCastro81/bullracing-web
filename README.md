# Motos Bull Racing · Sitio web

Rediseño de la landing de [motosbullracing.com](https://motosbullracing.com) sobre WordPress.
Proyecto desarrollado con acompañamiento técnico de **TecnoParque SENA**.

## Qué contiene este repositorio

Solo el **código propio** del proyecto. WordPress, los plugins de terceros, las imágenes
subidas y la base de datos **no** se versionan (ver `.gitignore`).

```
├── docs/                         Documentación del proyecto
│   ├── decisiones.md             Decisiones técnicas y por qué se tomaron
│   ├── despliegue.md             Cómo subir los cambios al hosting
│   └── propuestas/               Propuestas de diseño presentadas al taller
├── ot/                           Orden de servicio (OT) del taller: la hizo John Garzón (ver sección OT)
├── dev/                          Solo para desarrollo local (no va a producción)
│   └── verificar.ps1             Verificación del proyecto (el "build")
├── CHANGELOG.md                  Qué cambió en cada Pull Request
└── wp-content/
    ├── themes/bullracing/        Tema propio: SOLO apariencia
    │   ├── inc/datos-taller.php  Datos del taller (dirección, horario, WhatsApp…) en un solo lugar
    │   ├── template-parts/
    │   │   ├── componentes/      Piezas pequeñas reutilizables: botón, estrellas, tarjeta
    │   │   └── secciones/        Bloques de la landing: hero, planes, motolavado…
    │   └── assets/
    │       ├── fonts/            Poppins local (el sitio funciona sin internet)
    │       └── css/
    │           ├── tokens.css    Colores, fuentes y espacios como variables
    │           ├── skins/        Una "piel" por diseño (base, taller…)
    │           └── main.css      Estructura y componentes (solo usa tokens)
    └── plugins/
        └── bullracing-core/      Lógica del negocio: tarifas, rol "Taller", panel de precios, API REST
```

**Regla del proyecto:** el tema solo presenta; la lógica vive en plugins. Si mañana se cambia
el tema, los precios, los usuarios y el agendamiento siguen funcionando.

La orden de servicio (OT) y el agendamiento son módulos de la fase con TecnoParque y se mantienen
sin cambios de funcionamiento (todavía no están en este repositorio).

### Cómo se cambia de diseño

El diseño visual se separa en capas para que cambiarlo cueste poco:

1. **Datos** (`bullracing-core`): no cambian.
2. **Estructura** (`template-parts/`): casi no cambia.
3. **Tokens** (`tokens.css`): colores, fuentes y espacios. Nunca se escriben colores a mano en el resto del CSS.
4. **Skin** (`assets/css/skins/`): la piel de cada diseño. La activa se define en `functions.php` con `BULLRACING_SKIN`.

## Requisitos

- PHP 8.1 o superior
- MariaDB 10.4+ / MySQL 8+
- WordPress 6.x

## Instalación local (Windows + XAMPP)

1. Instalar WordPress en `C:\xampp81\htdocs\bullracing-real` (o restaurar una copia del sitio).
2. Clonar este repositorio **dentro de esa misma carpeta**:
   ```bash
   cd C:\xampp81\htdocs\bullracing-real
   git init
   git remote add origin <url-del-repositorio>
   git fetch origin
   git checkout -t origin/main
   ```
   Git solo ve los archivos del proyecto; el resto de WordPress queda fuera gracias al `.gitignore`.
3. Crear `wp-config.php` a partir de `dev/wp-config-local-ejemplo.php` (nunca se sube al repo).
4. Copiar `dev/mu-plugins/local-sin-correos.php` a `wp-content/mu-plugins/` para que el entorno
   local no envíe correos reales.
5. En el escritorio de WordPress: activar el tema **Bull Racing** y los plugins **Bull Racing Core**
   y **Bull Racing Agendamiento**.

## Datos personales (Ley 1581 de 2012)

Este repositorio **no** debe contener datos de clientes: nada de volcados de base de datos,
respaldos ni exportaciones. Para probar se usan datos ficticios. Las tarifas iniciales las crea
el plugin `bullracing-core` al activarse.

## Cómo trabajar: 3 terminales

En VS Code se abren tres terminales (Terminal → New Terminal) en la carpeta del proyecto:

| Terminal | Para qué | Comandos |
|---|---|---|
| **Backend** | XAMPP encendido (Apache + MySQL) y API | `C:\xampp81\xampp-control.exe` · `.\dev\verificar.ps1 -ConServidor` |
| **Frontend** | Verificación del tema (el "build") | `.\dev\verificar.ps1` |
| **Git** | Ramas, cambios y commits | `git status` · `git diff develop` · `git add` · `git commit` · `git push` |

**Vista previa:** http://localhost/bullracing-real (funciona sin internet).

## Conexión landing ↔ agendamiento

Los botones de agendar de la landing (barra del hero, "Agendar este plan" del tacómetro y los
paquetes de motolavado) llevan al formulario de agendamiento **con lo escogido ya marcado**.
Lo hace `assets/js/precarga-agendamiento.js`, que se carga solo en la página de agendamiento:
lee los datos del enlace (`?servicio=…&motor=…&plan=…&referencia=…`) y escoge las mismas
opciones en el formulario. **No modifica el agendamiento.**

**Regla:** al cambiar la apariencia del agendamiento se puede cambiar todo menos el `id` de
estas listas, porque la precarga las busca por ese nombre:

| Lista del formulario | `id` |
|---|---|
| Tipo de servicio | `tipo_servicio` |
| Tipo de vehículo | `engine_type` |
| Combo de mantenimiento | `service_type` |
| Referencia / cilindraje / potencia | `cyl_select` |
| Paquete de motolavado | `motolavado_paquete` |
| Cilindraje de motolavado | `motolavado_cc` |

**Pruebas cada vez que se toque el agendamiento o la landing:**

1. Barra del hero: Mantenimiento pro + 750W → Agendar → formulario con Eléctrica · Pro · 750W · $390.000.
2. Barra del hero: Motolavado Cero KM + 151 cc - 250 cc → "Escoge día y hora" → formulario con Motolavado · Cero KM · 151 cc - 250 cc.
3. Tacómetro: Eléctrica + General → Agendar este plan → formulario con Eléctrica · General.
4. Paquete de motolavado Black Revive → formulario con Motolavado · Black Revive.
5. Entrar a la página de agendamiento sin datos → abre normal.

**En local**, mientras el agendamiento definitivo no esté instalado, el mu-plugin
`wp-content/mu-plugins/local-agendamiento-prueba.php` (no está en el repo) hace que los botones
lleven a `http://localhost/prueba-agendamiento/`: el formulario definitivo de la fase TecnoParque sin cambios.
Esa página de prueba vive **fuera** de la carpeta del proyecto (`htdocs/prueba-agendamiento`), para no mezclar
archivos ajenos con el repositorio. Se borran los dos cuando el agendamiento quede instalado.

## Checklist antes de un Pull Request

1. `.\dev\verificar.ps1 -ConServidor` termina en **TODO BIEN**.
2. Revisar en el navegador `localhost/bullracing-real` (escritorio y celular).
3. `git diff develop` para confirmar que solo cambió lo que se quería.
4. Actualizar `CHANGELOG.md` (y este README si cambió la estructura o la forma de trabajar).
5. Abrir el PR hacia `develop` como **borrador** (*Create draft pull request*).
6. Con el check automático en verde → *Ready for review* → **Squash and merge** → borrar la rama.

## Flujo de trabajo

- `main`: lo que se entrega. Protegida, solo recibe cambios por Pull Request desde `develop`.
- `develop`: integración.
- `feature/<nombre>`: una rama por funcionalidad (`feature/landing-inicio`, `feature/panel-precios`).
- Commits con [Conventional Commits](https://www.conventionalcommits.org/es/):
  `feat(tema): sección de planes`, `fix(core): cálculo de tarifa eléctrica`.

## Despliegue

Ver [`docs/despliegue.md`](docs/despliegue.md).

## Propiedad

Repositorio creado durante el desarrollo y transferido al finalizar a la cuenta de GitHub
de Motos Bull Racing.

## Orden de servicio (OT)

La carpeta `ot/` es la orden de servicio que el taller llena al recibir la moto.
La programó **John Garzón** para el proyecto Motos Bull Racing, a partir del formato de orden que suministró don Franz.

- Busca el agendamiento por placa y lo marca como atendido al guardar la orden.
- `includes/config.php` tiene los datos de la base de datos y **no se sube a git**.
  Para instalarla: copiar `includes/config.example.php` como `includes/config.php` y poner los datos del servidor.
- `bullracing_bd.sql` trae solo la estructura de las tablas (sin datos de clientes).
- Los `temp_*.png` los crea la OT al generar el PDF (firmas y croquis): no se suben (Ley 1581).
- Necesita la extensión **GD** de PHP para el PDF.
- En el servidor debe quedar protegida con usuario y contraseña (no tiene inicio de sesión propio).
