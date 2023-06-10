-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 11-06-2023 a las 00:29:10
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `COMUNIDAD`
--

INSERT INTO `COMUNIDAD` (`com_comunidad`, `com_nombre`) VALUES
(1, 'CAMI SALARD, 13'),
(2, 'VALLE BUENAVISTA, 9');

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
  `mov_importe` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `MOVIMIENTO`
--

INSERT INTO `MOVIMIENTO` (`mov_movimiento`, `mov_comunidad`, `mov_detalle`, `mov_fecha`, `mov_detalle1`, `mov_detalle2`, `mov_detalle3`, `mov_importe`) VALUES
(1, 1, 'Movimiento DUMMY', '2023-01-01', NULL, NULL, NULL, '75.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PERFIL`
--

CREATE TABLE `PERFIL` (
  `per_perfil` int(11) NOT NULL,
  `per_nombre` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `pis_nombre` varchar(10) COLLATE utf8_bin NOT NULL,
  `pis_porcentaje` decimal(7,3) NOT NULL DEFAULT 0.000,
  `pis_comentario` varchar(999) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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
  `pro_nombre` varchar(50) COLLATE utf8_bin NOT NULL,
  `pro_apellidos` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `pro_dni` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `pro_fnacimiento` date DEFAULT NULL,
  `pro_correo` varchar(99) COLLATE utf8_bin DEFAULT NULL,
  `pro_telefono` varchar(20) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SESION`
--

CREATE TABLE `SESION` (
  `ses_correo` varchar(99) CHARACTER SET utf8mb4 NOT NULL,
  `ses_token` varchar(99) COLLATE utf8_bin NOT NULL,
  `ses_primero` datetime NOT NULL,
  `ses_ultimo` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `SESION`
--

INSERT INTO `SESION` (`ses_correo`, `ses_token`, `ses_primero`, `ses_ultimo`) VALUES
('cusquiskas@gmail.com', '07ced0e7d6707425fb10336a78a8177d075347534586ad11b79ab3f9d605cc32', '2023-06-05 22:53:21', '9999-12-31 00:00:00'),
('antoniapons@me.com', '17b1aea8363eb3d7f6f00349a0d98097ee2b2b6ec82163997bc0195e9861ffe3', '2023-06-06 21:19:01', '2023-06-06 21:19:01'),
('antoniapons@me.com', '21fa13d67d48dc86182b63dce71359f67a514eb74daf2da033a89e4e9d528dad', '2023-06-06 23:50:53', '2023-06-07 00:09:26'),
('antoniapons@me.com', '22155f3fe669216dbe3ba0187be8fed78aec5f7a4dfe79ac2a7be2d218fae963', '2023-06-09 22:15:58', '2023-06-09 22:39:04'),
('antoniapons@me.com', '29d83f549605ffb27b7c15bb481d7ab3791b177fe898515c1ce268bf0465e33b', '2023-06-06 22:42:55', '2023-06-06 22:44:05'),
('antoniapons@me.com', '5dcd8aee1defae034a59fc7a4db2a6e18f9653018de743d4ddbadd4bf95e7c7c', '2023-06-05 23:06:25', '2023-06-05 23:18:59'),
('antoniapons@me.com', '6fd00e858c2f3bb12963cf925a8ec7383f40d6d27b8137e548d4aa2be5b43d66', '2023-06-06 22:44:34', '2023-06-06 22:48:05'),
('antoniapons@me.com', '8a74bcc74bceb40c5b53953d0819685899ea3f60458b4618195595c7d6a00dcc', '2023-06-07 23:49:13', '2023-06-08 00:38:10'),
('antoniapons@me.com', 'a48f0ca2ff6e2f1982fdac7d4f1d0cfca8d56a20e66906a959a63e004f1cbc9c', '2023-06-07 22:29:25', '2023-06-07 23:13:50'),
('antoniapons@me.com', 'b7fd4a68a289e520a190d3c1de6ae04b4deb1b6ff4a9e9221a2457ee5a2c00ef', '2023-06-06 23:27:28', '2023-06-06 23:50:14'),
('antoniapons@me.com', 'f1c146f4e04f7df4f6de07e8c1aafa9194398353523c87b4c2a844e996e79380', '2023-06-09 23:10:46', '2023-06-10 00:47:40'),
('antoniapons@me.com', 'fc94e8a7c4f3157a6d084667161e7669bc3f366f6ef3f7e81c0b79d0f16e3754', '2023-06-10 23:40:37', '2023-06-11 00:25:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USUARIO`
--

CREATE TABLE `USUARIO` (
  `usu_correo` varchar(99) NOT NULL,
  `usu_nombre` varchar(99) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `usu_contrasena` varchar(99) NOT NULL,
  `usu_errorlogin` int(11) NOT NULL,
  `usu_facceso` datetime NOT NULL,
  `usu_fvalida` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `USUARIO`
--

INSERT INTO `USUARIO` (`usu_correo`, `usu_nombre`, `usu_contrasena`, `usu_errorlogin`, `usu_facceso`, `usu_fvalida`) VALUES
('antoniapons@me.com', 'Antonia', 'ce86e5921962c3ec0f2f5901790ee4bc', 0, '2023-06-10 23:40:37', '2022-06-03'),
('cusquiskas@gmail.com', 'José Miguel', 'ce86e5921962c3ec0f2f5901790ee4bc', 0, '2023-06-05 22:53:21', '9999-12-31');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `USUARIO_COMUNIDAD`
--

INSERT INTO `USUARIO_COMUNIDAD` (`uco_comunidad`, `uco_correo`, `uco_perfil`, `uco_desde`, `uco_hasta`) VALUES
(1, 'antoniapons@me.com', 1, '2023-06-05', '9999-12-31'),
(2, 'antoniapons@me.com', 3, '2023-06-05', '9999-12-31');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `COMUNIDAD`
--
ALTER TABLE `COMUNIDAD`
  ADD PRIMARY KEY (`com_comunidad`);

--
-- Indices de la tabla `MOVIMIENTO`
--
ALTER TABLE `MOVIMIENTO`
  ADD PRIMARY KEY (`mov_movimiento`),
  ADD KEY `movimiento_ix` (`mov_comunidad`,`mov_fecha`);

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
-- Indices de la tabla `SESION`
--
ALTER TABLE `SESION`
  ADD PRIMARY KEY (`ses_token`),
  ADD KEY `ses_usu_fk` (`ses_correo`);

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
-- AUTO_INCREMENT de la tabla `MOVIMIENTO`
--
ALTER TABLE `MOVIMIENTO`
  MODIFY `mov_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `MOVIMIENTO`
--
ALTER TABLE `MOVIMIENTO`
  ADD CONSTRAINT `com_mov_fk` FOREIGN KEY (`mov_comunidad`) REFERENCES `COMUNIDAD` (`com_comunidad`);

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
-- Filtros para la tabla `USUARIO_COMUNIDAD`
--
ALTER TABLE `USUARIO_COMUNIDAD`
  ADD CONSTRAINT `uco_com_fk` FOREIGN KEY (`uco_comunidad`) REFERENCES `COMUNIDAD` (`com_comunidad`),
  ADD CONSTRAINT `uco_per_fk` FOREIGN KEY (`uco_perfil`) REFERENCES `PERFIL` (`per_perfil`),
  ADD CONSTRAINT `uco_usu_fk` FOREIGN KEY (`uco_correo`) REFERENCES `USUARIO` (`usu_correo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;