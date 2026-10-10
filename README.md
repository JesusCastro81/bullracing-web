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
├── dev/                          Archivos solo para desarrollo local (no van a producción)
└── wp-content/
    ├── themes/bullracing/        Tema propio: SOLO apariencia (landing y páginas)
    └── plugins/
        ├── bullracing-core/      Lógica del negocio: tarifas, rol "Taller", panel de precios, API REST
        └── bullracing-agendamiento/  Módulo de agendamiento (fase TecnoParque, se mantiene)
```

**Regla del proyecto:** el tema solo presenta; la lógica vive en plugins. Si mañana se cambia
el tema, los precios, los usuarios y el agendamiento siguen funcionando.

La orden de servicio (OT) es un módulo de la fase anterior y se mantiene sin cambios.

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
