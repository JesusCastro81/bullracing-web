# Motos Bull Racing · Sitio web

Rediseño de la landing de [motosbullracing.com](https://motosbullracing.com) sobre WordPress,
con el Diseño 1 "Taller industrial". Incluye la orden de servicio (OT) del taller y su acceso
por usuario.

## Qué contiene este repositorio

Solo el **código propio** del proyecto. WordPress, los plugins de terceros, las imágenes
subidas y la base de datos **no** se versionan (ver `.gitignore`).

```
├── .github/                      Plantilla de Pull Request y revisión automática de PHP
├── dev/                          Solo para desarrollo local (no va a producción)
│   ├── mu-plugins/               local-sin-correos.php: el entorno local no envía correos de WordPress
│   ├── verificar.ps1             Verificación del proyecto (el "build")
│   └── wp-config-local-ejemplo.php
├── ot/                           Orden de servicio (OT) del taller (ver sección OT)
└── wp-content/
    ├── themes/bullracing/        Tema propio: SOLO apariencia
    │   ├── functions.php         Solo constantes y carga de inc/
    │   ├── inc/
    │   │   ├── helpers.php       Funciones de ayuda: hora, estrellas, WhatsApp, fotos, video, ícono de chat
    │   │   ├── datos-taller.php  Datos del taller (fuente única): horario, contador, reseñas, servicios, contacto
    │   │   ├── datos-planes.php  Qué incluye cada plan, plan recomendado y nombres de motores
    │   │   ├── datos-terminos.php Texto legal
    │   │   └── setup.php         Ganchos de WordPress: soportes, CSS/JS y datos para el JS
    │   ├── template-parts/
    │   │   ├── componentes/      Piezas reutilizables: estrellas, banda de páginas internas, video, botón de WhatsApp
    │   │   ├── inicio/           Secciones del inicio en el orden de la página: 01-hero … 07-contacto
    │   │   ├── quienes-somos/    01-historia, 02-servicios, 03-equipo
    │   │   ├── contacto/         01-whatsapp, 02-datos, 03-mapa, 04-redes
    │   │   └── terminos/         01-indice, 02-texto
    │   ├── front-page.php, page-*.php  Orquestadores: solo llaman a sus partes en orden
    │   └── assets/
    │       ├── fonts/            Fuentes locales (el sitio funciona sin internet)
    │       ├── js/               main.js (índice 00–08), precarga y capa visual del agendamiento
    │       └── css/
    │           ├── tokens.css    Colores, fuentes y espacios como variables
    │           ├── skins/        Una "piel" por diseño (base, taller); taller.css con índice 00–09
    │           └── main.css      Estructura (solo usa tokens)
    └── plugins/
        └── bullracing-core/      Lógica del negocio: tarifas, roles "Taller" y "Recepción de motos",
                                  panel de precios (solo lectura), API REST y registro de recepción de la OT
```

**Regla del proyecto:** el tema solo presenta; la lógica vive en el plugin. Si mañana se cambia
el tema, los precios, los usuarios y la OT siguen funcionando.

El **agendamiento** es el formulario de la página actual y no está en este repositorio: el tema
solo le cambia la apariencia y le agrega que la placa acepte solo letras y números.

El tema carga la capa visual (`assets/js/agendamiento-capa.js` y
`assets/css/skins/taller-agendamiento.css`) **solo en la página de agendamiento** (`inc/setup.php`).
Si la página no trae un contenedor `[data-br-agenda]` alrededor del formulario, la capa lo crea.
Si el formulario no es el agendamiento definitivo (no tiene `#tipo_servicio`), la capa no actúa.
El enlace de dudas usa el WhatsApp de `inc/datos-taller.php`.

## Dónde cambiar cada cosa

Cada dato se escribe en un solo lugar; todo lo demás lo lee de ahí.

| Para cambiar… | Ve a… |
|---|---|
| Horario, teléfono, WhatsApp, correo, dirección, redes | `inc/datos-taller.php` |
| Base de clientes satisfechos (el plugin le suma las órdenes de servicio guardadas) | `inc/datos-taller.php` → `contador` |
| Calificación y reseñas de Google | `inc/datos-taller.php` → `promedio`, `total_resenas`, `resenas` |
| Año de apertura, historia y video del taller | `inc/datos-taller.php` → `desde`, `historia`, `video` |
| Servicios de Quiénes somos (y áreas del equipo) | `inc/datos-taller.php` → `servicios` |
| Qué incluye cada plan y cuál es el recomendado | `inc/datos-planes.php` |
| Nombres de los motores y referencia inicial de la barra del hero | `inc/datos-planes.php` |
| Precios | Panel de precios de `bullracing-core` (el tema no tiene precios) |
| Texto legal | `inc/datos-terminos.php` |
| Una sección del inicio | `template-parts/inicio/0N-….php` |
| Estilo de una sección | `assets/css/skins/taller.css`, bloque con el número de la sección (ver índice) |
| Versión del tema | `style.css` → `Version` |

**Fotos:** se copian en `assets/img/taller/` con estos nombres; si una falta, se ve el recuadro
pendiente o la tarjeta queda solo con texto.

| Dónde | Archivo |
|---|---|
| Inicio · El taller (4 pasos) | `paso-01.jpg` … `paso-04.jpg` |
| Inicio · Motolavado | `motolavado-antes.jpg`, `motolavado-despues.jpg` |
| Inicio · Contacto | `contacto-carrusel-1.jpg` … `contacto-carrusel-4.jpg`, `contacto-entrega.jpg`, `contacto-rampa.jpg` |
| Quiénes somos · Servicios | `quienes-mantenimiento.jpg`, `quienes-diagnostico.jpg`, `quienes-motolavado.jpg` |
| Quiénes somos · Equipo | `equipo-taller.jpg`, `equipo-1.jpg` … `equipo-3.jpg` |

### Material del taller: fotos, video y textos

> **Las fotos que hay hoy en `assets/img/taller/` son de prueba y NO están en el repositorio.**
> Se reemplazan por las reales del taller con **el mismo nombre** de la tabla de arriba.

**Fotos**
- JPG, nombre exacto en minúscula y sin espacios.
- Ancho máximo 1200 px y menos de 300 KB por foto, para que la página cargue rápido.
- La página recorta cada foto para llenar su recuadro, conservando el centro.
- Antes y después del motolavado: la misma moto, desde el mismo punto y con el mismo encuadre.
- El carrusel de contacto necesita al menos 2 fotos; con menos se usa `contacto-rampa.jpg`.
- Sin caras de clientes ni placas legibles sin autorización (Ley 1581). Personas del taller: con su autorización.
- Si falta una foto, la página no se rompe: se ve el recuadro pendiente o la tarjeta solo con texto.

**Video del taller**

Se usa el **video original** que grabó el taller (el archivo del celular o de la cámara), **no** el que se
descarga de TikTok o Instagram, para que no salga con la marca de agua de la red social.

A diferencia de las fotos, el video **no se copia en ninguna carpeta del tema ni lleva un nombre fijo**:
se sube a la **biblioteca de Medios de WordPress** (queda en `wp-content/uploads/`, que no está en el
repositorio) y su dirección se escribe en el campo `'video'` de `inc/datos-taller.php`. Hay **un solo video**
y aparece en **dos lugares** a la vez: la sección "Quiénes somos" del inicio (`template-parts/inicio/06-nosotros.php`)
y la página Quiénes somos, junto a la historia (`template-parts/quienes-somos/01-historia.php`).
Se reproduce dentro de la página, con play/pausa y barra de progreso. Vacío (`''`): se ve "Video pendiente".

*Pasos*
1. Pedirle al taller el **archivo original** del video (sin marca de agua) y su autorización de uso.
2. Prepararlo: **MP4 (H.264)**, 720p, corto (menos de 1 minuto) y comprimido. Nombre del archivo en
   minúscula, sin espacios ni tildes, por ejemplo `video-taller.mp4`, para que la dirección quede limpia.
3. Subirlo en el WordPress **del sitio real**: **Medios → Añadir nuevo** → arrastrar el archivo. El hosting
   pone un límite de subida, que se ve en esa misma pantalla ("Tamaño máximo de archivo"); si el video
   no sube, es por ese límite: comprimirlo más.
4. Abrir el video en **Medios → Biblioteca** → **Copiar URL al portapapeles**.
5. Pegar esa URL entre las comillas, sin borrar la coma del final:
   `'video'     => 'https://motosbullracing.com/wp-content/uploads/2026/11/video-taller.mp4',`
6. Ojo: un video subido en el WordPress **local** queda con una dirección `localhost` que **no existe** en
   el sitio real. Para probar en local se sube también en local y se usa esa URL; para el sitio real, la
   URL del video subido allá.

Mientras el video no se reproduce, el recuadro muestra de fondo la foto del hero (`assets/img/hero.jpg`).
El archivo de video **nunca** se sube al repositorio. Después de cambiar `'video'`: verificar, revisar las dos
páginas con **Ctrl + F5** y hacer commit de `inc/datos-taller.php`.

**Textos** (`inc/datos-taller.php`)
- `'historia'`: la historia del taller. Vacío: texto entre corchetes.
- `'desde'`: año de apertura en número, sin comillas. La página calcula los años. `0`: se ve `[AÑO]`.
- `'equipo'`: todavía no existe; se agrega después de `'servicios'`, en el mismo orden de los servicios
  (Mantenimiento, Diagnóstico, Motolavado). Las fotos de cada persona son `equipo-1.jpg` … `equipo-3.jpg`.

```php
'equipo'    => array(
	array( 'nombre' => 'Nombre Apellido', 'cargo' => 'Cargo' ), // Mantenimiento
	array( 'nombre' => 'Nombre Apellido', 'cargo' => 'Cargo' ), // Diagnóstico
	array( 'nombre' => 'Nombre Apellido', 'cargo' => 'Cargo' ), // Motolavado
),
```

**Pasos para integrar el material**
1. Revisar que el material tenga autorización de uso.
2. Preparar cada foto y copiarla con su nombre en `wp-content/themes/bullracing/assets/img/taller/`.
3. Escribir en `inc/datos-taller.php` la URL del video (ver "Video del taller"), la historia, el año y el equipo que se tengan.
4. Subir la versión del tema en `style.css`.
5. `.\dev\verificar.ps1 -ConServidor` en **TODO BIEN** y revisar en el navegador con **Ctrl + F5**,
   en escritorio y celular.
6. Commit **agregando cada archivo por su nombre**, nunca `git add -A` (así no se cuela ningún archivo de prueba):
   `git add wp-content/themes/bullracing/assets/img/taller/paso-01.jpg …`

### Cómo se cambia de diseño

El diseño visual se separa en capas para que cambiarlo cueste poco:

1. **Datos** (`bullracing-core` e `inc/`): no cambian.
2. **Estructura** (`template-parts/`): casi no cambia.
3. **Tokens** (`tokens.css`): colores, fuentes y espacios. `main.css` no usa colores a mano;
   los colores propios de un diseño van en su skin.
4. **Skin** (`assets/css/skins/`): la piel de cada diseño. La activa se define en `functions.php`
   con `BULLRACING_SKIN`. Las secciones de `taller.css` van en el orden de la página; el mapa y
   las tarjetas con foto van al final a propósito (bloque 09), porque ajustan reglas anteriores.

## Requisitos

- **PHP:** 8.1 o superior. Se recomienda una versión con soporte y con el parche de seguridad
  del 21-oct-2026 (8.2.35, 8.3.36, 8.4.27 o más nueva). Aviso oficial:
  https://www.php.net/archive/2026.php#2026-10-21-5
  - **Probado en local con PHP 8.1.25** (XAMPP 8.1.25). **Pendiente:** probar con PHP 8.4.27.
- **Extensión GD de PHP** (la OT la usa para el PDF).
- MariaDB 10.4+ / MySQL 8+.
- WordPress 6.4 o superior.

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
4. Copiar `dev/mu-plugins/local-sin-correos.php` a `wp-content/mu-plugins/` para que WordPress
   no envíe correos reales desde el entorno local.
5. Crear `ot/includes/config.php` a partir de `ot/includes/config.example.php` y, si no existe,
   importar la estructura de la base de la OT (`ot/bullracing_bd.sql`).
6. En `C:\xampp81\php\php.ini`, activar `extension=gd`.
7. Recomendado: que Apache y MySQL solo acepten conexiones del propio computador
   (`Listen 127.0.0.1:80` en `httpd.conf`, `Listen 127.0.0.1:443` en `httpd-ssl.conf`
   y `bind-address="127.0.0.1"` en `my.ini`).
8. En el escritorio de WordPress: activar el plugin **Bull Racing Core** y el tema **Bull Racing**.
   En **Ajustes → Generales**, zona horaria **Bogotá**.

## Cómo trabajar: 3 terminales

En VS Code se abren tres terminales (Terminal → New Terminal) en la carpeta del proyecto:

| Terminal | Para qué | Comandos |
|---|---|---|
| **Backend** | XAMPP encendido (Apache + MySQL) y API | `C:\xampp81\xampp-control.exe` · `.\dev\verificar.ps1 -ConServidor` |
| **Frontend** | Verificación del tema, plugins y OT (el "build") | `.\dev\verificar.ps1` |
| **Git** | Ramas, cambios y commits | `git status` · `git diff develop` · `git add` · `git commit` · `git push` |

**Vista previa:** http://localhost/bullracing-real (funciona sin internet).

## Conexión landing ↔ agendamiento

Los botones de agendar de la landing (barra del hero, botón Agendar de cada tarjeta de planes y
los paquetes de motolavado) llevan al formulario de agendamiento **con lo escogido ya marcado**.
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
3. Planes: pestaña Motos eléctricas, tarjeta General → Agendar → formulario con Eléctrica · General.
4. Paquete de motolavado Black Revive → formulario con Motolavado · Black Revive.
5. Entrar a la página de agendamiento sin datos → abre normal.

**En local**, el mu-plugin `wp-content/mu-plugins/local-agendamiento-prueba.php` (no está en el
repo) hace que los botones lleven a `http://localhost/prueba-agendamiento/`: el formulario de
agendamiento sin cambios. Esa página de prueba vive **fuera** de la carpeta del proyecto
(`htdocs/prueba-agendamiento`), para no mezclar archivos ajenos con el repositorio.

## Orden de servicio (OT)

La carpeta `ot/` es la orden de servicio que el taller llena al recibir la moto.
La programó **John Garzón** para el proyecto Motos Bull Racing, a partir del formato de orden que suministró don Franz.

- Busca el agendamiento por placa y lo marca como atendido al guardar la orden.
- **Número de orden:** lo asigna el servidor al guardar (`ajax/guardarDatos.php`), no al abrir la
  página. Nunca sobrescribe una orden existente, y un doble clic en Guardar no duplica la orden.
- `includes/config.php` tiene los datos de la base de datos y **no se sube a git**.
  Para instalarla: copiar `includes/config.example.php` como `includes/config.php` y poner los datos del servidor.
- `bullracing_bd.sql` trae solo la estructura de las tablas (sin datos de clientes).
- Los `temp_*.png` los crea la OT al generar el PDF (firmas y croquis): no se suben (Ley 1581).
- Necesita la extensión **GD** de PHP para el PDF.

### Acceso a la OT

La OT original no tenía inicio de sesión. Se protege con el inicio de sesión de WordPress:

- **Rol "Recepción de motos"** (plugin `bullracing-core`): para las personas que el administrador
  del taller autoriza a recibir motos. Ese rol **solo sirve para la OT**: no puede tocar la página
  ni los precios. Si entra a `wp-admin`, va a la OT (menos a su Perfil, para cambiar la contraseña).
- **Un usuario por persona**, todos con el mismo rol. El administrador los crea, edita o quita en
  **Usuarios** de WordPress.
- **Contraseña:** cada uno la cambia en su Perfil o la recupera con "¿Has olvidado tu contraseña?"
  (el sitio debe poder enviar correos).
- **Quitar el acceso:** cambiar el rol a "Sin rol en este sitio" (o borrar el usuario). El permiso
  se revisa en cada acción, así que pierde el acceso al instante.
- **Trazabilidad:** cada orden guardada queda en la tabla `{prefijo}bullracing_ot_recepciones` de
  WordPress con quién la recibió y a qué hora.
- El control está en `ot/includes/acceso.php`, incluido al principio de cada archivo PHP de la OT.
  `ot/.htaccess` no deja descargar el `.sql` ni `includes/`.
- La OT debe estar en la carpeta `ot/` dentro de la raíz de WordPress (usa `wp-load.php`).

**Cómo entra el personal a la OT** (no hay botón en la página pública, a propósito):

1. Abrir `https://motosbullracing.com/ot/` (en local: `http://localhost/bullracing-real/ot/`).
   También sirve `/wp-admin`: el usuario de recepción va directo a la OT.
2. Si no ha iniciado sesión, WordPress pide usuario y contraseña y luego vuelve a la OT.
3. Para no escribir la dirección: marcador "OT Bull Racing" en el navegador del mostrador
   (o acceso directo en el escritorio) y, en el celular, "Agregar a pantalla de inicio".
4. En un computador compartido, al terminar, usar **Salir** en la barra "Recibe · Salir".

## Despliegue

> **Probado el 01-nov-2026** en la copia local del sitio real con Elementor 4.3.1 y Elementor Pro
> activos. Nada se toca en el sitio real hasta la entrega oficial.

**Quién lo instala** (lo decide el taller):
- **Opción 1:** la persona que el taller tenga destinada para su página, siguiendo estos pasos.
- **Opción 2:** el equipo del proyecto, con un acceso temporal que se retira al terminar.

**Antes**
1. Respaldo completo del sitio real (archivos + base de datos), guardado **fuera** del repositorio.
2. Confirmar con el hosting que la página corre en una versión de PHP con soporte y con el parche
   del 21-oct-2026, y que la extensión GD está activa.
3. Confirmar que la página tiene HTTPS.
4. Confirmar que en el `wp-config.php` del sitio real `WP_DEBUG` está en `false`. Si queda en
   `true`, los errores técnicos se le muestran al público.

**Instalar**
5. Subir la versión en `wp-content/themes/bullracing/style.css` (`Version`), para que los
   navegadores descarguen el CSS y el JS nuevos. Comprimir `wp-content/themes/bullracing` → `bullracing.zip` y `wp-content/plugins/bullracing-core`
   → `bullracing-core.zip`.
6. **Plugins → Añadir nuevo → Subir plugin** → `bullracing-core.zip` → Activar
   (crea la tabla de tarifas, los roles y el registro de recepción).
7. Subir la carpeta `ot/` a la raíz de WordPress (administrador de archivos del hosting o FTP).
   Crear `ot/includes/config.php` con los datos de la base del servidor e importar
   `ot/bullracing_bd.sql` si las tablas de la OT no existen.
8. **Apariencia → Temas → Añadir nuevo → Subir tema** → `bullracing.zip` → Activar.
9. En **Páginas**, abrir **Inicio** (HOME) con **Editar** (no "Editar con Elementor") → botón
   **"Volver al editor de WordPress"** → **Plantilla: Plantilla por defecto** → **Guardar**.
   Repetir con **Contacto**. No tocar la página de agendamiento.
   Por qué: esas dos páginas están armadas en Elementor. Si no se hace, Elementor muestra el
   diseño viejo en lugar del tema, y sus estilos globales ponen el fondo y los enlaces en blanco.
   El contenido viejo no se borra: vuelve si alguien usa "Editar con Elementor".
10. **Páginas y menú.** Revisar en **Páginas** que existan y estén publicadas `contacto`,
    `quienes-somos`, `terminos` y `agendamiento` (con esas direcciones exactas). Si falta alguna,
    el enlace no se rompe: lleva a la sección del inicio. En **Apariencia → Menús**, si el sitio
    tiene asignado un menú viejo (con tienda o carrito), quitarlo: sin menú asignado, el tema usa
    el suyo (Planes, Motolavado, El taller, Quiénes somos y Contacto).
11. **Ajustes → Generales → Zona horaria:** Bogotá.
12. Comprobar que WordPress envía correos (por ejemplo, "¿Has olvidado tu contraseña?").
    Si no llegan, configurar un SMTP.
13. Crear los usuarios con el rol **Recepción de motos**.

**Probar**
14. Inicio (hero, planes, motolavado, valoración, contacto), páginas Quiénes somos, Contacto y
    Términos, en escritorio y celular. El fondo debe ser negro y no debe aparecer el diseño viejo.
15. Agendamiento: una cita de prueba que llegue por correo.
16. OT: entrar con un usuario de recepción, buscar la placa de la cita de prueba, guardar y sacar el PDF.
17. Borrar la cita de prueba, su orden de servicio y su fila del registro de recepción: así el
    contador de clientes satisfechos vuelve a la base que dio el taller.

**Si algo sale mal:** volver a activar el tema anterior desde **Apariencia → Temas** y, si es
necesario, restaurar el respaldo del paso 1.

## Después de publicar

Todo cambio se hace primero en local y en el repositorio (con su verificación y su commit), y
después se lleva al sitio real. Antes de tocar el sitio real, respaldo.

**Fotos, video y textos del taller**
1. Integrarlos en local según "Material del taller" y hacer commit.
2. En el sitio real, con el administrador de archivos del hosting o FTP, subir las fotos a
   `wp-content/themes/bullracing/assets/img/taller/` **con el mismo nombre**, y el
   `inc/datos-taller.php` cambiado al mismo lugar.
3. El video no se sube por FTP: va en **Medios** del WordPress real (ver "Video del taller").
4. Si el sitio usa caché (plugin del hosting o de WordPress), vaciarla. Revisar con **Ctrl + F5**.

**Precios**

Los precios están en **dos lugares** y un cambio se hace en los dos:
- En la tabla `{prefijo}bullracing_tarifas` de WordPress, que usan la página de inicio y la
  pantalla **Bull Racing → Precios**. Esa pantalla es **solo de lectura**: el precio se cambia en
  la base de datos (phpMyAdmin del hosting → tabla de tarifas → editar la fila del servicio, tipo
  de moto, plan y referencia).
- En la lista `TARIFAS_2026` del agendamiento (`js/ba-agendamiento.js`, del paquete del
  agendamiento, que no está en este repositorio).

Si solo se cambia en uno, la página muestra un precio y el formulario otro. `tarifas-2026.php`
del plugin solo se usa para llenar la tabla la primera vez; cambiarlo no actualiza los precios.

**Código del tema o del plugin:** rama `feature/<nombre>` desde `develop`, verificar, subir la
versión en `style.css` (o la del plugin), Pull Request y luego subir el tema o el plugin al sitio
real (pasos de "Instalar").

## Datos personales (Ley 1581 de 2012)

Este repositorio **no** debe contener datos de clientes: nada de volcados de base de datos,
respaldos ni exportaciones. Para probar se usan datos ficticios. Las tarifas iniciales las crea
el plugin `bullracing-core` al activarse. `ot/includes/config.php` y los `ot/temp_*.png` nunca
se suben (están en `.gitignore`).

## Flujo de trabajo

- `main`: lo que se entrega. Protegida, solo recibe cambios por Pull Request desde `develop`.
- `develop`: integración.
- `feature/<nombre>`: una rama por funcionalidad (`feature/landing-inicio`, `feature/panel-precios`).
- Commits con [Conventional Commits](https://www.conventionalcommits.org/es/):
  `feat(tema): sección de planes`, `fix(core): cálculo de tarifa eléctrica`.

### Checklist antes de un Pull Request

1. `.\dev\verificar.ps1 -ConServidor` termina en **TODO BIEN**.
2. Revisar en el navegador `localhost/bullracing-real` (escritorio y celular).
3. `git diff develop` para confirmar que solo cambió lo que se quería.
4. Actualizar este README si cambió la estructura o la forma de trabajar.
5. Abrir el PR hacia `develop` como **borrador** (*Create draft pull request*).
6. Con el check automático en verde → *Ready for review* → **Squash and merge** → borrar la rama.

## Propiedad

Repositorio creado durante el desarrollo y transferido al finalizar a la cuenta de GitHub
de Motos Bull Racing.
