<?php
/**
 * Se ejecuta al BORRAR el plugin desde el escritorio (no al desactivarlo).
 * Quita el rol. La tabla de tarifas NO se borra para no perder los precios del taller.
 *
 * @package BullRacingCore
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

require_once __DIR__ . '/includes/class-rol-taller.php';

BullRacing_Rol_Taller::eliminar();
