-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 01-05-2024 a las 23:32:12
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comunidad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `COMUNIDAD`
--

CREATE TABLE `COMUNIDAD` (
  `com_comunidad` int(11) NOT NULL,
  `com_nombre` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `COMUNIDAD`
--

INSERT INTO `COMUNIDAD` (`com_comunidad`, `com_nombre`) VALUES
(1, 'CAMI SALARD, 13'),
(2, 'VALLE BUENAVISTA, 9');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `GASTO`
--

CREATE TABLE `GASTO` (
  `gas_comunidad` int(11) NOT NULL,
  `gas_gasto` int(11) NOT NULL,
  `gas_nombre` varchar(99) COLLATE utf8_bin NOT NULL,
  `gas_reparto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `GASTO`
--

INSERT INTO `GASTO` (`gas_comunidad`, `gas_gasto`, `gas_nombre`, `gas_reparto`) VALUES
(1, 1, 'Luz', 1),
(1, 2, 'Agua', 1),
(1, 3, 'Administración', 1),
(1, 4, 'Limpieza', 1),
(1, 5, 'Seguro', 2),
(1, 6, 'Comisiones Banco', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `GASTO_PISO`
--

CREATE TABLE `GASTO_PISO` (
  `gpi_gasto` int(11) NOT NULL,
  `gpi_piso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `GASTO_PISO`
--

INSERT INTO `GASTO_PISO` (`gpi_gasto`, `gpi_piso`) VALUES
(3, 1),
(5, 1),
(6, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(1, 4),
(3, 4),
(4, 4),
(5, 4),
(6, 4),
(1, 5),
(2, 5),
(3, 5),
(4, 5),
(5, 5),
(6, 5),
(1, 6),
(2, 6),
(3, 6),
(4, 6),
(5, 6),
(6, 6),
(1, 7),
(2, 7),
(3, 7),
(4, 7),
(5, 7),
(6, 7),
(1, 8),
(2, 8),
(3, 8),
(4, 8),
(5, 8),
(6, 8),
(1, 9),
(2, 9),
(3, 9),
(4, 9),
(5, 9),
(6, 9),
(1, 10),
(2, 10),
(3, 10),
(4, 10),
(5, 10),
(6, 10),
(1, 11),
(2, 11),
(3, 11),
(4, 11),
(5, 11),
(6, 11),
(1, 12),
(2, 12),
(3, 12),
(4, 12),
(5, 12),
(6, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MOVIMIENTO`
--

CREATE TABLE `MOVIMIENTO` (
  `mov_movimiento` int(11) NOT NULL,
  `mov_comunidad` int(11) NOT NULL,
  `mov_detalle` varchar(200) NOT NULL,
  `mov_fecha` date NOT NULL,
  `mov_detalle1` varchar(200) DEFAULT NULL,
  `mov_detalle2` varchar(200) DEFAULT NULL,
  `mov_detalle3` varchar(200) DEFAULT NULL,
  `mov_importe` decimal(8,2) NOT NULL,
  `mov_piso` int(11) DEFAULT NULL,
  `mov_gasto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `MOVIMIENTO`
--

INSERT INTO `MOVIMIENTO` (`mov_movimiento`, `mov_comunidad`, `mov_detalle`, `mov_fecha`, `mov_detalle1`, `mov_detalle2`, `mov_detalle3`, `mov_importe`, `mov_piso`, `mov_gasto`) VALUES
(1, 1, 'Movimiento DUMMY', '2023-01-01', NULL, NULL, NULL, '75.00', 7, NULL),
(2, 1, 'Movimiento DUMMY 2', '2023-02-10', NULL, NULL, NULL, '75.00', 6, NULL),
(3, 1, 'Movimiento DUMMY 3', '2023-03-05', NULL, NULL, NULL, '75.00', 5, NULL),
(4, 1, 'Movimiento DUMMY 4', '2023-06-11', NULL, NULL, NULL, '75.00', 3, NULL),
(5, 1, 'Movimiento DUMMY 3.5', '2023-06-10', NULL, NULL, NULL, '75.00', 3, NULL),
(6, 1, 'Movimiento YUPPY', '2023-06-01', NULL, NULL, NULL, '-55.50', NULL, NULL),
(7, 1, 'Movimiento YUPPY 2', '2023-06-02', NULL, NULL, NULL, '-35.65', NULL, NULL),
(8, 1, 'Movimiento YUPPY -1', '2023-06-19', NULL, NULL, NULL, '-100.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PERFIL`
--

CREATE TABLE `PERFIL` (
  `per_perfil` int(11) NOT NULL,
  `per_nombre` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `PERFIL`
--

INSERT INTO `PERFIL` (`per_perfil`, `per_nombre`) VALUES
(1, 'Administración'),
(2, 'Autorizado'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PISO`
--

CREATE TABLE `PISO` (
  `pis_piso` int(11) NOT NULL,
  `pis_comunidad` int(11) NOT NULL,
  `pis_nombre` varchar(10) NOT NULL,
  `pis_porcentaje` decimal(7,3) NOT NULL DEFAULT 0.000,
  `pis_comentario` varchar(999) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `PISO`
--

INSERT INTO `PISO` (`pis_piso`, `pis_comunidad`, `pis_nombre`, `pis_porcentaje`, `pis_comentario`) VALUES
(1, 1, 'LOCAL', '0.000', NULL),
(2, 1, 'PISO 1A', '0.000', NULL),
(3, 1, 'PISO 1B', '0.000', NULL),
(4, 1, 'PISO 1C', '0.000', NULL),
(5, 1, 'PISO 2A', '0.000', NULL),
(6, 1, 'PISO 2B', '0.000', NULL),
(7, 1, 'PISO 2C', '0.000', NULL),
(8, 1, 'PISO 3A', '0.000', NULL),
(9, 1, 'PISO 3B', '0.000', NULL),
(10, 1, 'PISO 3C', '0.000', NULL),
(11, 1, 'PISO 4A', '0.000', NULL),
(12, 1, 'PISO 4B', '0.000', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PROPIETARIO`
--

CREATE TABLE `PROPIETARIO` (
  `pro_propietario` int(11) NOT NULL,
  `pro_nombre` varchar(50) NOT NULL,
  `pro_apellidos` varchar(50) DEFAULT NULL,
  `pro_dni` varchar(20) DEFAULT NULL,
  `pro_fnacimiento` date DEFAULT NULL,
  `pro_correo` varchar(99) DEFAULT NULL,
  `pro_telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PROPIETARIO_PISO`
--

CREATE TABLE `PROPIETARIO_PISO` (
  `ppi_propietario` int(11) NOT NULL,
  `ppi_piso` int(11) NOT NULL,
  `ppi_desde` date NOT NULL,
  `ppi_hasta` date NOT NULL,
  `ppi_inquilino` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `REPARTO`
--

CREATE TABLE `REPARTO` (
  `rep_reparto` int(11) NOT NULL,
  `rep_nombre` varchar(99) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `REPARTO`
--

INSERT INTO `REPARTO` (`rep_reparto`, `rep_nombre`) VALUES
(1, 'Partes iguales'),
(2, 'Porcentaje'),
(3, 'Importe Fijo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SESION`
--

CREATE TABLE `SESION` (
  `ses_correo` varchar(99) NOT NULL,
  `ses_token` varchar(99) NOT NULL,
  `ses_primero` datetime NOT NULL,
  `ses_ultimo` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `SESION`
--

INSERT INTO `SESION` (`ses_correo`, `ses_token`, `ses_primero`, `ses_ultimo`) VALUES
('cusquiskas@gmail.com', '905a9704c815740312e031ebb68721c72cfe698bc47e23eb97fd07af51a482a7', '2024-05-01 22:28:50', '2024-05-01 22:29:27'),
('cusquiskas@gmail.com', 'e830a32a14de45cb8e09c6adad82ea9fb5f408a6c4b86b60ac7d44beeb03d0c9', '2024-05-01 22:29:50', '2024-05-01 23:28:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SPLIT`
--

CREATE TABLE `SPLIT` (
  `spl_split` int(11) NOT NULL COMMENT 'id del split',
  `spl_movimiento` int(11) NOT NULL COMMENT 'id del movimiento',
  `spl_fecha` date NOT NULL COMMENT 'fecha del movimiento',
  `spl_descripcion` varchar(300) NOT NULL COMMENT 'descripción del movimiento',
  `spl_importe` decimal(7,3) NOT NULL COMMENT 'importe del movimiento',
  `spl_piso` int(11) NOT NULL,
  `spl_gasto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USUARIO`
--

CREATE TABLE `USUARIO` (
  `usu_correo` varchar(99) NOT NULL,
  `usu_nombre` varchar(99) NOT NULL,
  `usu_contrasena` varchar(99) NOT NULL,
  `usu_errorlogin` int(11) NOT NULL,
  `usu_facceso` datetime NOT NULL,
  `usu_fvalida` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `USUARIO`
--

INSERT INTO `USUARIO` (`usu_correo`, `usu_nombre`, `usu_contrasena`, `usu_errorlogin`, `usu_facceso`, `usu_fvalida`) VALUES
('antoniapons@me.com', 'Antonia', 'ce86e5921962c3ec0f2f5901790ee4bc', 0, '2023-06-26 22:58:19', '2022-06-03'),
('cusquiskas@gmail.com', 'José Miguel', 'ce86e5921962c3ec0f2f5901790ee4bc', 0, '2024-05-01 22:29:50', '2024-05-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USUARIO_COMUNIDAD`
--

CREATE TABLE `USUARIO_COMUNIDAD` (
  `uco_comunidad` int(11) NOT NULL,
  `uco_correo` varchar(99) NOT NULL,
  `uco_perfil` int(11) NOT NULL,
  `uco_desde` date NOT NULL,
  `uco_hasta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `USUARIO_COMUNIDAD`
--

INSERT INTO `USUARIO_COMUNIDAD` (`uco_comunidad`, `uco_correo`, `uco_perfil`, `uco_desde`, `uco_hasta`) VALUES
(1, 'antoniapons@me.com', 1, '2023-06-05', '9999-12-31'),
(2, 'antoniapons@me.com', 3, '2023-06-05', '9999-12-31'),
(1, 'cusquiskas@gmail.com', 1, '2024-05-01', '9999-12-31');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `COMUNIDAD`
--
ALTER TABLE `COMUNIDAD`
  ADD PRIMARY KEY (`com_comunidad`);

--
-- Indices de la tabla `GASTO`
--
ALTER TABLE `GASTO`
  ADD PRIMARY KEY (`gas_gasto`),
  ADD KEY `gas_comunidad` (`gas_comunidad`),
  ADD KEY `gas_rep_fk` (`gas_reparto`);

--
-- Indices de la tabla `GASTO_PISO`
--
ALTER TABLE `GASTO_PISO`
  ADD PRIMARY KEY (`gpi_piso`,`gpi_gasto`),
  ADD KEY `gpi_gas_fk` (`gpi_gasto`);

--
-- Indices de la tabla `MOVIMIENTO`
--
ALTER TABLE `MOVIMIENTO`
  ADD PRIMARY KEY (`mov_movimiento`),
  ADD KEY `movimiento_ix` (`mov_comunidad`,`mov_fecha`),
  ADD KEY `pis_mov_fk` (`mov_piso`),
  ADD KEY `gas_mov_fk` (`mov_gasto`);

--
-- Indices de la tabla `PERFIL`
--
ALTER TABLE `PERFIL`
  ADD PRIMARY KEY (`per_perfil`);

--
-- Indices de la tabla `PISO`
--
ALTER TABLE `PISO`
  ADD PRIMARY KEY (`pis_piso`),
  ADD KEY `pis_com_fk` (`pis_comunidad`);

--
-- Indices de la tabla `PROPIETARIO`
--
ALTER TABLE `PROPIETARIO`
  ADD PRIMARY KEY (`pro_propietario`),
  ADD UNIQUE KEY `pro_correo_ix` (`pro_correo`);

--
-- Indices de la tabla `PROPIETARIO_PISO`
--
ALTER TABLE `PROPIETARIO_PISO`
  ADD PRIMARY KEY (`ppi_propietario`,`ppi_piso`),
  ADD KEY `ppi_pis` (`ppi_piso`);

--
-- Indices de la tabla `REPARTO`
--
ALTER TABLE `REPARTO`
  ADD PRIMARY KEY (`rep_reparto`);

--
-- Indices de la tabla `SESION`
--
ALTER TABLE `SESION`
  ADD PRIMARY KEY (`ses_token`),
  ADD KEY `ses_usu_fk` (`ses_correo`);

--
-- Indices de la tabla `SPLIT`
--
ALTER TABLE `SPLIT`
  ADD PRIMARY KEY (`spl_split`),
  ADD KEY `mov_spl_fk` (`spl_movimiento`),
  ADD KEY `pis_spl_fk` (`spl_piso`),
  ADD KEY `gas_spl_fk` (`spl_gasto`);

--
-- Indices de la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  ADD PRIMARY KEY (`usu_correo`);

--
-- Indices de la tabla `USUARIO_COMUNIDAD`
--
ALTER TABLE `USUARIO_COMUNIDAD`
  ADD PRIMARY KEY (`uco_correo`,`uco_comunidad`) USING BTREE,
  ADD KEY `uco_com_fk` (`uco_comunidad`),
  ADD KEY `uco_per_fk` (`uco_perfil`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `COMUNIDAD`
--
ALTER TABLE `COMUNIDAD`
  MODIFY `com_comunidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `GASTO`
--
ALTER TABLE `GASTO`
  MODIFY `gas_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `MOVIMIENTO`
--
ALTER TABLE `MOVIMIENTO`
  MODIFY `mov_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `PERFIL`
--
ALTER TABLE `PERFIL`
  MODIFY `per_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `PISO`
--
ALTER TABLE `PISO`
  MODIFY `pis_piso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `PROPIETARIO`
--
ALTER TABLE `PROPIETARIO`
  MODIFY `pro_propietario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `SPLIT`
--
ALTER TABLE `SPLIT`
  MODIFY `spl_split` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del split';

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `GASTO`
--
ALTER TABLE `GASTO`
  ADD CONSTRAINT `gas_com_fk` FOREIGN KEY (`gas_comunidad`) REFERENCES `COMUNIDAD` (`com_comunidad`),
  ADD CONSTRAINT `gas_rep_fk` FOREIGN KEY (`gas_reparto`) REFERENCES `REPARTO` (`rep_reparto`);

--
-- Filtros para la tabla `GASTO_PISO`
--
ALTER TABLE `GASTO_PISO`
  ADD CONSTRAINT `gpi_gas_fk` FOREIGN KEY (`gpi_gasto`) REFERENCES `GASTO` (`gas_gasto`),
  ADD CONSTRAINT `gpi_pis_fk` FOREIGN KEY (`gpi_piso`) REFERENCES `PISO` (`pis_piso`);

--
-- Filtros para la tabla `MOVIMIENTO`
--
ALTER TABLE `MOVIMIENTO`
  ADD CONSTRAINT `com_mov_fk` FOREIGN KEY (`mov_comunidad`) REFERENCES `COMUNIDAD` (`com_comunidad`),
  ADD CONSTRAINT `gas_mov_fk` FOREIGN KEY (`mov_gasto`) REFERENCES `GASTO` (`gas_gasto`),
  ADD CONSTRAINT `pis_mov_fk` FOREIGN KEY (`mov_piso`) REFERENCES `PISO` (`pis_piso`);

--
-- Filtros para la tabla `PISO`
--
ALTER TABLE `PISO`
  ADD CONSTRAINT `pis_com_fk` FOREIGN KEY (`pis_comunidad`) REFERENCES `COMUNIDAD` (`com_comunidad`);

--
-- Filtros para la tabla `PROPIETARIO_PISO`
--
ALTER TABLE `PROPIETARIO_PISO`
  ADD CONSTRAINT `ppi_pis` FOREIGN KEY (`ppi_piso`) REFERENCES `PISO` (`pis_piso`),
  ADD CONSTRAINT `ppi_pro_fk` FOREIGN KEY (`ppi_propietario`) REFERENCES `PROPIETARIO` (`pro_propietario`);

--
-- Filtros para la tabla `SESION`
--
ALTER TABLE `SESION`
  ADD CONSTRAINT `ses_usu_fk` FOREIGN KEY (`ses_correo`) REFERENCES `USUARIO` (`usu_correo`);

--
-- Filtros para la tabla `SPLIT`
--
ALTER TABLE `SPLIT`
  ADD CONSTRAINT `gas_spl_fk` FOREIGN KEY (`spl_gasto`) REFERENCES `GASTO` (`gas_gasto`),
  ADD CONSTRAINT `mov_spl_fk` FOREIGN KEY (`spl_movimiento`) REFERENCES `MOVIMIENTO` (`mov_movimiento`),
  ADD CONSTRAINT `pis_spl_fk` FOREIGN KEY (`spl_piso`) REFERENCES `PISO` (`pis_piso`);

--
-- Filtros para la tabla `USUARIO_COMUNIDAD`
--
ALTER TABLE `USUARIO_COMUNIDAD`
  ADD CONSTRAINT `uco_com_fk` FOREIGN KEY (`uco_comunidad`) REFERENCES `COMUNIDAD` (`com_comunidad`),
  ADD CONSTRAINT `uco_per_fk` FOREIGN KEY (`uco_perfil`) REFERENCES `PERFIL` (`per_perfil`),
  ADD CONSTRAINT `uco_usu_fk` FOREIGN KEY (`uco_correo`) REFERENCES `USUARIO` (`usu_correo`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`contador`@`localhost` EVENT `BORRAR_SESIONES_CADUCADAS` ON SCHEDULE EVERY 12 HOUR STARTS '2023-06-11 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM SESION WHERE ses_ultimo < NOW() - INTERVAL 1 HOUR$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;