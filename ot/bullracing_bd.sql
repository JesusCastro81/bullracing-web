-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 08-09-2026 a las 21:02:46
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bullracing_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agendamientos`
--

CREATE TABLE `agendamientos` (
  `id` int(11) NOT NULL,
  `plan` varchar(20) NOT NULL,
  `tipo_servicio` varchar(20) NOT NULL DEFAULT 'mantenimiento',
  `tipo_motor` varchar(30) NOT NULL,
  `referencia` varchar(50) NOT NULL,
  `precio_estimado` varchar(30) DEFAULT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` varchar(10) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) NOT NULL DEFAULT 0,
  `numero_ot_generada` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_servicio`
--

CREATE TABLE `ordenes_servicio` (
  `numero_orden` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_documento` varchar(10) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `placa` varchar(15) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `linea` varchar(50) NOT NULL,
  `km` varchar(20) NOT NULL,
  `cilindraje` varchar(20) NOT NULL,
  `tecnico` varchar(100) NOT NULL,
  `vence_tecnico` date NOT NULL,
  `vence_soat` date NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `hora_ingreso` time NOT NULL,
  `fecha_salida` date DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `clase_trabajo` varchar(255) NOT NULL,
  `tarjeta_propiedad` enum('SI','NO') NOT NULL,
  `llaves` enum('SI','NO') NOT NULL,
  `herramienta` enum('SI','NO') NOT NULL,
  `casco` enum('SI','NO') NOT NULL,
  `guantes` enum('SI','NO') NOT NULL,
  `alarma` enum('SI','NO') NOT NULL,
  `maletero` enum('SI','NO') NOT NULL,
  `parrilla` enum('SI','NO') NOT NULL,
  `combustible` varchar(10) NOT NULL,
  `stop_estado` enum('SI','NO') NOT NULL,
  `stop_obs` varchar(255) DEFAULT NULL,
  `direccionales_estado` enum('SI','NO') NOT NULL,
  `direccionales_obs` varchar(255) DEFAULT NULL,
  `exploradoras_estado` enum('SI','NO') NOT NULL,
  `exploradoras_obs` varchar(255) DEFAULT NULL,
  `alta_estado` enum('SI','NO') NOT NULL,
  `alta_obs` varchar(255) DEFAULT NULL,
  `baja_estado` enum('SI','NO') NOT NULL,
  `baja_obs` varchar(255) DEFAULT NULL,
  `placa_luz_estado` enum('SI','NO') NOT NULL,
  `placa_luz_obs` varchar(255) DEFAULT NULL,
  `lat_der_estado` enum('SI','NO') NOT NULL,
  `lat_der_obs` varchar(255) DEFAULT NULL,
  `lat_izq_estado` enum('SI','NO') NOT NULL,
  `lat_izq_obs` varchar(255) DEFAULT NULL,
  `carenaje_estado` enum('SI','NO') NOT NULL,
  `carenaje_obs` varchar(255) DEFAULT NULL,
  `gb_del_estado` enum('SI','NO') NOT NULL,
  `gb_del_obs` varchar(255) DEFAULT NULL,
  `gb_tras_estado` enum('SI','NO') NOT NULL,
  `gb_tras_obs` varchar(255) DEFAULT NULL,
  `prot_pinon_estado` enum('SI','NO') NOT NULL,
  `prot_pinon_obs` varchar(255) DEFAULT NULL,
  `cupula_estado` enum('SI','NO') NOT NULL,
  `cupula_obs` varchar(255) DEFAULT NULL,
  `quilla_estado` enum('SI','NO') NOT NULL,
  `quilla_obs` varchar(255) DEFAULT NULL,
  `guardacadena_estado` enum('SI','NO') NOT NULL,
  `guardacadena_obs` varchar(255) DEFAULT NULL,
  `cola_der_estado` enum('SI','NO') NOT NULL,
  `cola_der_obs` varchar(255) DEFAULT NULL,
  `cola_izq_estado` enum('SI','NO') NOT NULL,
  `cola_izq_obs` varchar(255) DEFAULT NULL,
  `farola_estado` enum('SI','NO') NOT NULL,
  `farola_obs` varchar(255) DEFAULT NULL,
  `esp_der_estado` enum('SI','NO') NOT NULL,
  `esp_der_obs` varchar(255) DEFAULT NULL,
  `esp_izq_estado` enum('SI','NO') NOT NULL,
  `esp_izq_obs` varchar(255) DEFAULT NULL,
  `calap_der_estado` enum('SI','NO') NOT NULL,
  `calap_der_obs` varchar(255) DEFAULT NULL,
  `calap_izq_estado` enum('SI','NO') NOT NULL,
  `calap_izq_obs` varchar(255) DEFAULT NULL,
  `pos_der_estado` enum('SI','NO') NOT NULL,
  `pos_der_obs` varchar(255) DEFAULT NULL,
  `pos_izq_estado` enum('SI','NO') NOT NULL,
  `pos_izq_obs` varchar(255) DEFAULT NULL,
  `tapa_tanque_estado` enum('SI','NO') NOT NULL,
  `tapa_tanque_obs` varchar(255) DEFAULT NULL,
  `pito_estado` enum('SI','NO') NOT NULL,
  `pito_obs` varchar(255) DEFAULT NULL,
  `bateria_estado` enum('SI','NO') NOT NULL,
  `bateria_obs` varchar(255) DEFAULT NULL,
  `tacometro_estado` enum('SI','NO') NOT NULL,
  `tacometro_obs` varchar(255) DEFAULT NULL,
  `observaciones_generales` text DEFAULT NULL,
  `marcas_croquis` text DEFAULT NULL,
  `firma_cliente` longtext NOT NULL,
  `firma_taller` longtext NOT NULL,
  `doc_cliente_firma` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `firma_taller_nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agendamientos`
--
ALTER TABLE `agendamientos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ordenes_servicio`
--
ALTER TABLE `ordenes_servicio`
  ADD PRIMARY KEY (`numero_orden`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agendamientos`
--
ALTER TABLE `agendamientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
