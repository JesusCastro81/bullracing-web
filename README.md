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
