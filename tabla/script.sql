-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 16-06-2023 a las 22:30:30
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

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
  `mov_piso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `MOVIMIENTO`
--

INSERT INTO `MOVIMIENTO` (`mov_movimiento`, `mov_comunidad`, `mov_detalle`, `mov_fecha`, `mov_detalle1`, `mov_detalle2`, `mov_detalle3`, `mov_importe`, `mov_piso`) VALUES
(1, 1, 'Movimiento DUMMY', '2023-01-01', NULL, NULL, NULL, '75.00', NULL),
(2, 1, 'Movimiento DUMMY 2', '2023-02-10', NULL, NULL, NULL, '75.00', NULL),
(3, 1, 'Movimiento DUMMY 3', '2023-03-05', NULL, NULL, NULL, '75.00', NULL),
(4, 1, 'Movimiento DUMMY 4', '2023-06-11', NULL, NULL, NULL, '75.00', NULL),
(5, 1, 'Movimiento DUMMY 3.5', '2023-06-10', NULL, NULL, NULL, '75.00', NULL),
(6, 1, 'Movimiento YUPPY', '2023-06-01', NULL, NULL, NULL, '-55.50', NULL),
(7, 1, 'Movimiento YUPPY 2', '2023-06-02', NULL, NULL, NULL, '-35.65', NULL);

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
  `pis_nombre` varchar(10)  NOT NULL,
  `pis_porcentaje` decimal(7,3) NOT NULL DEFAULT 0.000,
  `pis_comentario` varchar(999)  DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

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
  `pro_nombre` varchar(50)  NOT NULL,
  `pro_apellidos` varchar(50)  DEFAULT NULL,
  `pro_dni` varchar(20)  DEFAULT NULL,
  `pro_fnacimiento` date DEFAULT NULL,
  `pro_correo` varchar(99)  DEFAULT NULL,
  `pro_telefono` varchar(20)  DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

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
-- Estructura de tabla para la tabla `SESION`
--

CREATE TABLE `SESION` (
  `ses_correo` varchar(99) CHARACTER SET utf8 NOT NULL,
  `ses_token` varchar(99)  NOT NULL,
  `ses_primero` datetime NOT NULL,
  `ses_ultimo` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

--
-- Volcado de datos para la tabla `SESION`
--

INSERT INTO `SESION` (`ses_correo`, `ses_token`, `ses_primero`, `ses_ultimo`) VALUES
('cusquiskas@gmail.com', '159a6a8583c11a4e4fd69edd5b77a938cfbb2f9c2d2cb652b0571192c5dfd793', '2023-06-11 23:13:28', '9999-12-31 00:00:00'),
('antoniapons@me.com', '1f319f439d3d697f3d84ada498440ff4f28c7c550013220d4c11788157615ce0', '2023-06-13 16:44:25', '2023-06-13 16:45:12'),
('antoniapons@me.com', '2b96381c891498957c32badb6314b251856a93fc60a7fb083c1df59156205ffc', '2023-06-13 16:47:27', '2023-06-13 18:27:50'),
('antoniapons@me.com', '306fb97c97ef571ba7d400783dec3af49c14ede581509a27a0a98354fc0fa968', '2023-06-12 17:19:31', '2023-06-12 17:23:41'),
('antoniapons@me.com', '33537e6f66a7fc225aef098ec00c23ae912b883a3fc88bd75e0a1e2d236b3948', '2023-06-12 17:18:03', '2023-06-12 17:18:03'),
('antoniapons@me.com', '69386590a7aab222036c8535c9dfd49ea45ca0d27ede0e079bb4b227af095fff', '2023-06-12 00:17:02', '2023-06-12 01:13:16'),
('antoniapons@me.com', '7504211e5d63bbc0f0f6983950eeeef4525573a22e47a74f6ba3a63a406dfd96', '2023-06-11 23:17:08', '2023-06-11 23:17:08'),
('antoniapons@me.com', '772581ac76a4164d32888aa362ab870e0d7bb472c925174bb0bdd082567dc25c', '2023-06-11 23:16:13', '2023-06-11 23:16:13'),
('antoniapons@me.com', '7be8f8ec684422cef39162f5c54d5fc196ce0bda574b1f6d3b7d75132ae1639b', '2023-06-12 20:49:57', '2023-06-12 23:42:55'),
('antoniapons@me.com', '7fd5ed5355dc540bd556bf34a3dced8fc5f92bb9a1e458454aa35d2c767ca9fe', '2023-06-12 23:54:24', '2023-06-13 00:08:29'),
('antoniapons@me.com', '93e9cdb9d187845ca1630a1a8dccc6decfedad1fb8952161596348b56f7cbe66', '2023-06-13 20:59:14', '2023-06-13 22:01:47'),
('antoniapons@me.com', '96f29ea108c85e083892c6acfd9ce7004d214e94ad3882df3dc14d4eadf3ca39', '2023-06-13 22:39:09', '2023-06-14 00:10:08'),
('antoniapons@me.com', '988a94ab28fa64b96e1c1a2fbc6501cf49b72b52ccd1619f9cf17e6ffaa4c3f2', '2023-06-11 23:53:40', '2023-06-12 00:08:54'),
('antoniapons@me.com', 'be3c4393f9205656f2586020884b430406a9cb98f6f3141409c964cb13381250', '2023-06-12 17:17:49', '2023-06-12 17:17:49'),
('antoniapons@me.com', 'c7eb0542b691409143abc1647419d77fc7b3286c89dc89ea4b6306fc9a5c2a26', '2023-06-13 16:37:44', '2023-06-13 16:43:50'),
('antoniapons@me.com', 'f39ff89f607ff9b700580ebb87422f277f20b9c9121ef14d6953e1cd5dbc0452', '2023-06-13 16:36:38', '2023-06-13 16:36:57'),
('antoniapons@me.com', 'f497770ae6733b94e8a6491d3eae16f89029a4878eb42810375056cf028c1816', '2023-06-12 17:19:20', '2023-06-12 17:19:20'),
('antoniapons@me.com', 'fc94e8a7c4f3157a6d084667161e7669bc3f366f6ef3f7e81c0b79d0f16e3754', '2023-06-10 23:40:37', '2023-06-11 00:25:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USUARIO`
--

CREATE TABLE `USUARIO` (
  `usu_correo` varchar(99) NOT NULL,
  `usu_nombre` varchar(99) CHARACTER SET utf8  NOT NULL,
  `usu_contrasena` varchar(99) NOT NULL,
  `usu_errorlogin` int(11) NOT NULL,
  `usu_facceso` datetime NOT NULL,
  `usu_fvalida` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `USUARIO`
--

INSERT INTO `USUARIO` (`usu_correo`, `usu_nombre`, `usu_contrasena`, `usu_errorlogin`, `usu_facceso`, `usu_fvalida`) VALUES
('antoniapons@me.com', 'Antonia', 'ce86e5921962c3ec0f2f5901790ee4bc', 0, '2023-06-13 22:39:09', '2022-06-03'),
('cusquiskas@gmail.com', 'José Miguel', 'ce86e5921962c3ec0f2f5901790ee4bc', 0, '2023-06-11 23:13:28', '9999-12-31');

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
  ADD KEY `movimiento_ix` (`mov_comunidad`,`mov_fecha`),
  ADD KEY `pis_mov_fk` (`mov_piso`);

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
  MODIFY `mov_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `com_mov_fk` FOREIGN KEY (`mov_comunidad`) REFERENCES `COMUNIDAD` (`com_comunidad`),
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