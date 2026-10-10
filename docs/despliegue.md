# Despliegue al hosting

> Regla: **nada se toca en el sitio real hasta la entrega oficial.**
> Todo se desarrolla y se prueba en la copia local.

## Antes de desplegar

1. Respaldo completo del sitio real (archivos + base de datos) con UpdraftPlus, descargado
   **fuera** del repositorio y fuera de carpetas sincronizadas (OneDrive).
2. Confirmar versión de PHP del hosting (mínimo 8.1).
3. Probar en local con la rama `main` actualizada.

## Desplegar (manual)

1. Comprimir `wp-content/themes/bullracing` → `bullracing.zip`.
2. Comprimir cada plugin `wp-content/plugins/bullracing-*` → `bullracing-core.zip`, etc.
3. En el escritorio de WordPress del sitio real:
   - Apariencia → Temas → Añadir nuevo → Subir tema.
   - Plugins → Añadir nuevo → Subir plugin.
4. Activar los plugins primero y el tema al final.
5. Revisar: inicio, planes, agendamiento (una cita de prueba), contacto y panel de precios.

## Si algo sale mal

Volver a activar el tema anterior desde Apariencia → Temas. Si es necesario, restaurar el
respaldo del paso 1.

## Más adelante (opcional)

Automatizar el despliegue con GitHub Actions por FTP/SFTP cuando el proyecto esté estable.
