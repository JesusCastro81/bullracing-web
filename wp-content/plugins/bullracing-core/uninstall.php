<?php
/**
 * Se ejecuta al BORRAR el plugin desde el escritorio (no al desactivarlo).
 * Quita los roles "Taller" y "Recepción de motos".
 * Las tablas de tarifas y del registro de recepción NO se borran, para no perder los precios
 * ni el historial de quién recibió cada moto.
 *
 * @package BullRacingCore
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

require_once __DIR__ . '/includes/class-rol-taller.php';
require_once __DIR__ . '/includes/class-acceso-ot.php';

BullRacing_Rol_Taller::eliminar();
BullRacing_Acceso_OT::eliminar();
