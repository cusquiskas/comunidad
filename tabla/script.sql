-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 07-08-2024 a las 21:07:49
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 7.4.27

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
(1, 6, 'Comisiones Banco', 1),
(1, 7, 'Otro no especificado', 1);

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
(7, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(1, 4),
(3, 4),
(4, 4),
(5, 4),
(6, 4),
(7, 4),
(1, 5),
(2, 5),
(3, 5),
(4, 5),
(5, 5),
(6, 5),
(7, 5),
(1, 6),
(2, 6),
(3, 6),
(4, 6),
(5, 6),
(6, 6),
(7, 6),
(1, 7),
(2, 7),
(3, 7),
(4, 7),
(5, 7),
(6, 7),
(7, 7),
(1, 8),
(2, 8),
(3, 8),
(4, 8),
(5, 8),
(6, 8),
(7, 8),
(1, 9),
(2, 9),
(3, 9),
(4, 9),
(5, 9),
(6, 9),
(7, 9),
(1, 10),
(2, 10),
(3, 10),
(4, 10),
(5, 10),
(6, 10),
(7, 10),
(1, 11),
(2, 11),
(3, 11),
(4, 11),
(5, 11),
(6, 11),
(7, 11),
(1, 12),
(2, 12),
(3, 12),
(4, 12),
(5, 12),
(6, 12),
(7, 12);

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
(9, 1, 'Inicialización Aplicación', '2022-12-31', NULL, NULL, NULL, '1379.67', NULL, NULL),
(10, 1, 'AB.TR.SEPA 00048345835 GREGORIA GALLEGO A', '2023-01-02', NULL, NULL, NULL, '55.00', 3, NULL),
(11, 1, 'AB.TR.SEPA 00048398165 SELENA COCA VIRUMB', '2023-01-03', NULL, NULL, NULL, '60.00', 7, NULL),
(12, 1, 'ABONO REC.DOM.OT.ENT', '2023-01-04', NULL, NULL, NULL, '860.00', NULL, NULL),
(13, 1, 'LIQ.REM.DEBIT.INTERC', '2023-01-04', NULL, NULL, NULL, '-10.89', NULL, 6),
(14, 1, 'TRANSFER. JAIME PERELLO PONS', '2023-01-04', NULL, NULL, NULL, '1125.30', 2, NULL),
(15, 1, 'DEV.RECIBOS', '2023-01-09', NULL, NULL, NULL, '-220.00', NULL, NULL),
(16, 1, 'LIQ.REM.DEVOL.DEBIT.', '2023-01-09', NULL, NULL, NULL, '-4.84', NULL, 6),
(17, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000794081', '2023-01-09', NULL, NULL, NULL, '-19.21', NULL, 1),
(18, 1, 'AB.TR.SEPA 00048526847 GOMILA PEREZ ANTON', '2023-01-10', NULL, NULL, NULL, '120.00', 10, NULL),
(19, 1, 'AB.TR.SEPA 00048547036 JOSE MIGUEL MUNAR', '2023-01-11', NULL, NULL, NULL, '65.00', 4, NULL),
(20, 1, 'LIQUID.PROPIA CUENTA', '2023-01-25', NULL, NULL, NULL, '-92.60', NULL, 6),
(21, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-01-27', NULL, NULL, NULL, '-95.00', NULL, 3),
(22, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-01-27', NULL, NULL, NULL, '-95.00', NULL, 3),
(23, 1, 'TRANSFER. SEGURO MAPHRE', '2023-01-28', NULL, NULL, NULL, '-1177.52', NULL, 5),
(24, 1, 'AB.TR.SEPA 00048848616 MIQUEL ANGEL FERNA', '2023-01-30', NULL, NULL, NULL, '70.00', 11, NULL),
(25, 1, 'AB.TR.SEPA 00048978312 GREGORIA GALLEGO A', '2023-02-02', NULL, NULL, NULL, '55.00', NULL, NULL),
(26, 1, 'AB.TR.SEPA 00049009802 SELENA COCA VIRUMB', '2023-02-03', NULL, NULL, NULL, '60.00', 7, NULL),
(27, 1, 'RECIBO CONVERSIA PROTECCION 010000679987', '2023-02-09', NULL, NULL, NULL, '-37.30', NULL, 7),
(28, 1, 'TRANSFER. PEDRO MARTI', '2023-02-09', NULL, NULL, NULL, '-122.00', NULL, 7),
(29, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000802836', '2023-02-10', NULL, NULL, NULL, '-18.00', NULL, 1),
(30, 1, 'R.AGUA E.M.A.Y.A. 000070937798 0000000802', '2023-02-10', NULL, NULL, NULL, '-451.78', NULL, 2),
(31, 1, 'TRANSFER. EMAYA', '2023-02-21', NULL, NULL, NULL, '-530.06', NULL, 2),
(32, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-02-26', NULL, NULL, NULL, '-95.00', NULL, 3),
(33, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-02-26', NULL, NULL, NULL, '-97.00', NULL, 4),
(34, 1, 'ABONO REC.DOM.OT.ENT', '2023-03-02', NULL, NULL, NULL, '860.00', NULL, NULL),
(35, 1, 'AB.TR.SEPA 00049540611 GREGORIA GALLEGO A', '2023-03-02', NULL, NULL, NULL, '55.00', NULL, NULL),
(36, 1, 'AB.TR.SEPA 00049540612 MIQUEL ANGEL FERNA', '2023-03-02', NULL, NULL, NULL, '70.00', 11, NULL),
(37, 1, 'LIQ.REM.DEBIT.INTERC', '2023-03-02', NULL, NULL, NULL, '-10.89', NULL, 6),
(38, 1, 'TRANSFER. EFIKAZ & SERVICIOS', '2023-03-02', NULL, NULL, NULL, '-431.00', NULL, 7),
(39, 1, 'AB.TR.SEPA 00049579060 SELENA COCA VIRUMB', '2023-03-03', NULL, NULL, NULL, '60.00', 7, NULL),
(40, 1, 'TRANSF.INM 00049687838 SELENA COCA VIRUMB', '2023-03-08', NULL, NULL, NULL, '79.30', 7, NULL),
(41, 1, 'AB.TR.SEPA 00049705413 GOMILA PEREZ ANTON', '2023-03-09', NULL, NULL, NULL, '120.00', 10, NULL),
(42, 1, 'AB.TR.SEPA 00049743976 JOSE MIGUEL MUNAR', '2023-03-13', NULL, NULL, NULL, '65.00', 4, NULL),
(43, 1, 'AB.TR.SEPA 00049743977 MARTINA FRONTERA G', '2023-03-13', NULL, NULL, NULL, '220.00', NULL, NULL),
(44, 1, 'RECIBO AXA SEGUROS GENERALE 00000BCJ0GH9', '2023-03-13', NULL, NULL, NULL, '-1618.99', NULL, 5),
(45, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000810888', '2023-03-13', NULL, NULL, NULL, '-17.35', NULL, 1),
(46, 1, 'ANULACION AXA SEGUROS GENERALE 00000BCJ0G', '2023-03-14', NULL, NULL, NULL, '1618.99', NULL, 5),
(47, 1, 'ENTREGA EFV.-IMPOS. GREGORIO PEREZ 3-A', '2023-03-17', NULL, NULL, NULL, '120.00', NULL, NULL),
(48, 1, 'TRANSFER. JAIME PERELLO PONS', '2023-03-31', NULL, NULL, NULL, '226.00', NULL, NULL),
(49, 1, 'AB.TR.SEPA 00049891479 GOMILA PEREZ ANTON', '2023-03-21', NULL, NULL, NULL, '250.00', 10, NULL),
(50, 1, 'TRANSFER. MARTA MUGICA AGOTE', '2023-03-24', NULL, NULL, NULL, '1069.00', NULL, NULL),
(51, 1, 'DISP.EFV.-REINTEGRO PAGO ARREGLO TERRAZA', '2023-03-24', NULL, NULL, NULL, '-470.00', NULL, 7),
(52, 1, 'AB.TR.SEPA 00050112354 SERVANDO SALGUERO', '2023-03-31', NULL, NULL, NULL, '223.11', 2, NULL),
(53, 1, 'AB.TR.SEPA 00050112355 SERVANDO SALGUERO', '2023-03-31', NULL, NULL, NULL, '365.30', 2, NULL),
(54, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-03-31', NULL, NULL, NULL, '-95.00', NULL, 3),
(55, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-03-31', NULL, NULL, NULL, '-97.00', NULL, 4),
(56, 1, 'ABONO REC.DOM.OT.ENT', '2023-04-03', NULL, NULL, NULL, '1448.00', NULL, NULL),
(57, 1, 'AB.TR.SEPA 00050158769 SERVANDO', '2023-04-03', NULL, NULL, NULL, '60.00', 3, NULL),
(58, 1, 'LIQ.REM.DEBIT.INTERC', '2023-04-03', NULL, NULL, NULL, '-9.78', NULL, 6),
(59, 1, 'AB.TR.SEPA 00050262545 GOMILA PEREZ ANTON', '2023-04-05', NULL, NULL, NULL, '160.00', 10, NULL),
(60, 1, 'DEV.RECIBOS', '2023-04-06', NULL, NULL, NULL, '-766.00', NULL, NULL),
(61, 1, 'LIQ.REM.DEVOL.DEBIT.', '2023-04-06', NULL, NULL, NULL, '-5.02', NULL, 6),
(62, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000818136', '2023-04-06', NULL, NULL, NULL, '-19.07', NULL, 1),
(63, 1, 'AB.TR.SEPA 00050314238 SELENA COCA VIRUMB', '2023-04-11', NULL, NULL, NULL, '60.00', 7, NULL),
(64, 1, 'R.AGUA E.M.A.Y.A. 000070937798 0000000819', '2023-04-17', NULL, NULL, NULL, '-432.05', NULL, 2),
(65, 1, 'AB.TR.SEPA 00050463793 JOSE MIGUEL MUNAR', '2023-04-18', NULL, NULL, NULL, '250.00', 4, NULL),
(66, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-04-27', NULL, NULL, NULL, '-267.00', NULL, 7),
(67, 1, 'AB.TR.SEPA 00050741187 SERVANDO', '2023-05-02', NULL, NULL, NULL, '60.00', 3, NULL),
(68, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-05-02', NULL, NULL, NULL, '-95.00', NULL, 3),
(69, 1, 'AB.TR.SEPA 00050820789 SELENA COCA VIRUMB', '2023-03-03', NULL, NULL, NULL, '60.00', 7, NULL),
(70, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-05-03', NULL, NULL, NULL, '-97.00', NULL, 4),
(71, 1, 'AB.TR.SEPA 00051000859 GOMILA PEREZ ANTON', '2023-05-10', NULL, NULL, NULL, '160.00', 10, NULL),
(72, 1, 'AB.TR.SEPA 00051021093 JOSE MIGUEL MUNAR', '2023-05-11', NULL, NULL, NULL, '65.00', 4, NULL),
(73, 1, 'DISP.EFV.-REINTEGRO PAGO ELECTRICISTA', '2023-05-11', NULL, NULL, NULL, '-3500.00', NULL, 7),
(74, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000828554', '2023-05-12', NULL, NULL, NULL, '-23.09', NULL, 1),
(75, 1, 'AB.TR.SEPA 00051054311 MIQUEL ANGEL FERNA', '2023-05-12', NULL, NULL, NULL, '120.00', 11, NULL),
(76, 1, 'TRANSFER. EMAYA', '2023-05-15', NULL, NULL, NULL, '-464.77', NULL, 2),
(77, 1, 'TRANSFER. PEDRO EGEA SANTIAGO', '2023-05-15', NULL, NULL, NULL, '1500.00', 1, NULL),
(78, 1, 'DISP.EFV.-REINTEGRO PAGO ELECTRICISTA', '2023-05-16', NULL, NULL, NULL, '-1500.00', NULL, 7),
(79, 1, 'ENTREGA EFV.-IMPOS. PISO 3-A -- MAYO-JUNI', '2023-05-17', NULL, NULL, NULL, '346.00', 8, NULL),
(80, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-05-28', NULL, NULL, NULL, '-97.00', NULL, 4),
(81, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-05-28', NULL, NULL, NULL, '-95.00', NULL, 3),
(82, 1, 'AB.TR.SEPA 00051511218 SERVANDO', '2023-06-02', NULL, NULL, NULL, '60.00', 3, NULL),
(83, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000834336', '2023-06-02', NULL, NULL, NULL, '-14.68', NULL, 1),
(84, 1, 'AB.TR.SEPA 00051553035 SELENA COCA VIRUMB', '2023-06-05', NULL, NULL, NULL, '60.00', 7, NULL),
(85, 1, 'R.AGUA E.M.A.Y.A. 000070937798 0000000836', '2023-06-12', NULL, NULL, NULL, '-447.40', NULL, 2),
(86, 1, 'ABONO REC.DOM.OT.ENT', '2023-06-14', NULL, NULL, NULL, '640.00', NULL, NULL),
(87, 1, 'LIQ.REM.DEBIT.INTERC', '2023-06-14', NULL, NULL, NULL, '-8.71', NULL, 6),
(88, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-07-02', NULL, NULL, NULL, '-95.00', NULL, 3),
(89, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-07-02', NULL, NULL, NULL, '-97.00', NULL, 3),
(90, 1, 'ABONO REC.DOM.OT.ENT', '2023-07-03', NULL, NULL, NULL, '640.00', NULL, NULL),
(91, 1, 'AB.TR.SEPA 00052173949 SERVANDO', '2023-07-03', NULL, NULL, NULL, '60.00', 3, NULL),
(92, 1, 'AB.TR.SEPA 00052173950 MIQUEL ANGEL FERNA', '2023-07-03', NULL, NULL, NULL, '200.00', 11, NULL),
(93, 1, 'LIQ.REM.DEBIT.INTERC', '2023-07-03', NULL, NULL, NULL, '-8.71', NULL, 6),
(94, 1, 'TRANSFER. PEDRO EGEA SANTIAGO', '2023-07-05', NULL, NULL, NULL, '3340.00', 1, NULL),
(95, 1, 'AB.TR.SEPA 00052325327 GOMILA PEREZ ANTON', '2023-07-06', NULL, NULL, NULL, '253.00', 10, NULL),
(96, 1, 'AB.TR.SEPA 00052325328 GOMILA PEREZ ANTON', '2023-07-06', NULL, NULL, NULL, '62.30', 10, NULL),
(97, 1, 'AB.TR.SEPA 00052353695 SELENA COCA VIRUMB', '2023-07-07', NULL, NULL, NULL, '60.00', 7, NULL),
(98, 1, 'DISP.EFV.-REINTEGRO PAGO ELECTRICISTA', '2023-07-07', NULL, NULL, NULL, '-3850.00', NULL, 7),
(99, 1, 'AB.TR.SEPA 00052413109 JOSE MIGUEL MUNAR', '2023-07-11', NULL, NULL, NULL, '65.00', 4, NULL),
(100, 1, 'AB.TR.SEPA 00052419950 MIQUEL ANGEL FERNA', '2023-07-11', NULL, NULL, NULL, '250.00', 11, NULL),
(101, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000845130', '2023-07-11', NULL, NULL, NULL, '-20.88', NULL, 1),
(102, 1, 'AB.TR.SEPA 00052543454 JOSE MIGUEL MUNAR', '2023-07-18', NULL, NULL, NULL, '300.00', 4, NULL),
(103, 1, 'LIQUID.PROPIA CUENTA', '2023-07-25', NULL, NULL, NULL, '-122.40', NULL, 6),
(104, 1, 'ENTREGA EFV.-IMPOS. GREGORIO PEREZ', '2023-07-27', NULL, NULL, NULL, '14.00', 8, NULL),
(105, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-07-30', NULL, NULL, NULL, '-97.00', NULL, 4),
(106, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-07-30', NULL, NULL, NULL, '-95.00', NULL, 3),
(107, 1, 'AB.TR.SEPA 00052922201 SERVANDO', '2023-08-02', NULL, NULL, NULL, '60.00', 3, NULL),
(108, 1, 'AB.TR.SEPA 00053022474 SELENA COCA VIRUMB', '2023-08-07', NULL, NULL, NULL, '60.00', 7, NULL),
(109, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000853388', '2023-08-08', NULL, NULL, NULL, '-21.10', NULL, 1),
(110, 1, 'R.AGUA E.M.A.Y.A. 000070937798 0000000853', '2023-08-08', NULL, NULL, NULL, '-460.57', NULL, 2),
(111, 1, 'RECIBO PROFESSIONAL GROUP C 010000689961', '2023-08-24', NULL, NULL, NULL, '-37.30', NULL, 7),
(112, 1, 'AB.TR.SEPA 00053323575 MIQUEL ANGEL FERNA', '2023-08-25', NULL, NULL, NULL, '160.00', 11, NULL),
(113, 1, 'TRANSFER. JAIME PERELLO PONS', '2023-08-29', NULL, NULL, NULL, '254.32', 2, NULL),
(114, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-08-30', NULL, NULL, NULL, '-97.00', NULL, 4),
(115, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-08-30', NULL, NULL, NULL, '-95.00', NULL, 3),
(116, 1, 'TRANSFER. MARIA SALGUERO CALERO', '2023-09-02', NULL, NULL, NULL, '120.00', 2, NULL),
(117, 1, 'AB.TR.SEPA 00053622859 SELENA COCA VIRUMB', '2023-09-05', NULL, NULL, NULL, '60.00', 7, NULL),
(118, 1, 'ABONO REC.DOM.OT.ENT', '2023-09-06', NULL, NULL, NULL, '640.00', NULL, NULL),
(119, 1, 'LIQ.REM.DEBIT.INTERC', '2023-09-06', NULL, NULL, NULL, '-8.71', NULL, 6),
(120, 1, 'AB.TR.SEPA 00053686401 GOMILA PEREZ ANTON', '2023-09-07', NULL, NULL, NULL, '160.00', 10, NULL),
(121, 1, 'ABONO REC.DOM.OT.ENT', '2023-09-08', NULL, NULL, NULL, '1017.28', NULL, NULL),
(122, 1, 'DEV.RECIBOS', '2023-09-08', NULL, NULL, NULL, '-160.00', NULL, NULL),
(123, 1, 'LIQ.REM.DEBIT.INTERC', '2023-09-08', NULL, NULL, NULL, '-8.71', NULL, 6),
(124, 1, 'LIQ.REM.DEVOL.DEBIT.', '2023-09-08', NULL, NULL, NULL, '-4.84', NULL, 6),
(125, 1, 'TRANSFER. PEDRO EGEA SANTIAGO', '2023-09-09', NULL, NULL, NULL, '300.00', 1, NULL),
(126, 1, 'AB.TR.SEPA 00053729010 JOSE MIGUEL MUNAR', '2023-09-11', NULL, NULL, NULL, '65.00', 4, NULL),
(127, 1, 'DEV.RECIBOS', '2023-09-11', NULL, NULL, NULL, '-250.58', NULL, NULL),
(128, 1, 'LIQ.REM.DEVOL.DEBIT.', '2023-09-11', NULL, NULL, NULL, '-4.84', NULL, 6),
(129, 1, 'TRANSFER. JAVIER FERNANDEZ', '2023-09-18', NULL, NULL, NULL, '-302.00', NULL, 7),
(130, 1, 'TRANSFER. EMAYA', '2023-09-19', NULL, NULL, NULL, '-438.09', NULL, 2),
(131, 1, 'DISP.EFV.-REINTEGRO PAGO CUARTO CONTADORE', '2023-09-22', NULL, NULL, NULL, '-300.00', NULL, 7),
(132, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-09-25', NULL, NULL, NULL, '-95.00', NULL, 3),
(133, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-09-25', NULL, NULL, NULL, '-97.00', NULL, 4),
(134, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000867303', '2023-10-02', NULL, NULL, NULL, '-22.34', NULL, 1),
(135, 1, 'AB.TR.SEPA 00054239684 SELENA COCA VIRUMB', '2023-10-03', NULL, NULL, NULL, '60.00', 7, NULL),
(136, 1, 'TRANSFER. FACTURA LUZ', '2023-10-08', NULL, NULL, NULL, '-69.59', NULL, 1),
(137, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000869854', '2023-10-09', NULL, NULL, NULL, '-30.55', NULL, 1),
(138, 1, 'R.AGUA E.M.A.Y.A. 000070937798 0000000870', '2023-10-10', NULL, NULL, NULL, '-517.72', NULL, 2),
(139, 1, 'TRANSFER. EFIKAZ', '2023-10-12', NULL, NULL, NULL, '-1342.00', NULL, 7),
(140, 1, 'RECIBO IVNOSYS SOLUCIONES H57525446/1 000', '2023-10-19', NULL, NULL, NULL, '-41.41', NULL, 7),
(141, 1, 'TRANSFER. MARIA SALGUERO CALERO', '2023-10-29', NULL, NULL, NULL, '120.00', 3, NULL),
(142, 1, 'AB.TR.SEPA 00054827788 MIQUEL ANGEL FERNA', '2023-10-31', NULL, NULL, NULL, '120.00', 11, NULL),
(143, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-11-01', NULL, NULL, NULL, '-97.00', NULL, 4),
(144, 1, 'ABONO REC.DOM.OT.ENT', '2023-11-03', NULL, NULL, NULL, '640.00', NULL, NULL),
(145, 1, 'AB.TR.SEPA 00054938452 SELENA COCA VIRUMB', '2023-11-03', NULL, NULL, NULL, '60.00', 7, NULL),
(146, 1, 'LIQ.REM.DEBIT.INTERC', '2023-11-03', NULL, NULL, NULL, '-8.71', NULL, 6),
(147, 1, 'AB.TR.SEPA 00055029006 GOMILA PEREZ ANTON', '2023-11-07', NULL, NULL, NULL, '160.00', 10, NULL),
(148, 1, 'AB.TR.SEPA 00055029007 GOMILA PEREZ ANTON', '2023-11-07', NULL, NULL, NULL, '140.00', 10, NULL),
(149, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-11-12', NULL, NULL, NULL, '-95.00', NULL, 3),
(150, 1, 'AB.TR.SEPA 00055122983 JOSE MIGUEL MUNAR', '2023-11-13', NULL, NULL, NULL, '65.00', 4, NULL),
(151, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000879866', '2023-11-14', NULL, NULL, NULL, '-20.89', NULL, 1),
(152, 1, 'TRANSF.INM 00055186726 SELENA COCA VIRUMB', '2023-11-15', NULL, NULL, NULL, '690.50', 7, NULL),
(153, 1, 'TRANSFER. PEDRO EGEA SANTIAGO', '2023-11-23', NULL, NULL, NULL, '370.00', 1, NULL),
(154, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-11-25', NULL, NULL, NULL, '-95.00', NULL, 3),
(155, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-11-25', NULL, NULL, NULL, '-97.00', NULL, 4),
(156, 1, 'TRANSFER. JAVIER FERNANDEZ', '2023-11-26', NULL, NULL, NULL, '-372.00', NULL, 7),
(157, 1, 'AB.TR.SEPA 00055417533 JOSE MIGUEL MUNAR', '2023-11-28', NULL, NULL, NULL, '260.50', 4, NULL),
(158, 1, 'TRANSFER. SEGURO MAPHRE', '2023-11-29', NULL, NULL, NULL, '-1324.07', NULL, 5),
(159, 1, 'AB.TR.SEPA 00055645879 SELENA COCA VIRUMB', '2023-12-05', NULL, NULL, NULL, '60.00', 7, NULL),
(160, 1, 'RECIBO GASILUZ 48B6B1A7ED69 0000000887626', '2023-12-12', NULL, NULL, NULL, '-23.53', NULL, 1),
(161, 1, 'R.AGUA E.M.A.Y.A. 000070937798 0000000888', '2023-12-13', NULL, NULL, NULL, '-494.93', NULL, 2),
(162, 1, 'AB.TR.SEPA 00055968161 MIQUEL ANGEL FERNA', '2023-12-21', NULL, NULL, NULL, '120.00', 11, NULL),
(163, 1, 'TRANSFER. MARIA SALGUERO CALERO', '2023-12-24', NULL, NULL, NULL, '240.00', 3, NULL),
(164, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-12-27', NULL, NULL, NULL, '-95.00', NULL, 3),
(165, 1, 'TRANSFER. ANTONIA MARIA PONS OLIVE', '2023-12-27', NULL, NULL, NULL, '-97.00', NULL, 4);

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
-- Estructura de tabla para la tabla `PROMESA`
--

CREATE TABLE `PROMESA` (
  `psm_promesa` int(11) NOT NULL COMMENT 'id de la promesa',
  `psm_detalle` varchar(200) NOT NULL COMMENT 'descripción de la promesa',
  `psm_fdesde` date NOT NULL COMMENT 'fecha desde la que aplica (inclusive)',
  `psm_fhasta` date NOT NULL DEFAULT '9999-12-31' COMMENT 'fecha hasta la que aplica (inclusive)',
  `psm_comunidad` int(11) NOT NULL COMMENT 'id de la comunidad',
  `psm_importe` decimal(8,2) NOT NULL COMMENT 'importe periódico de la promesa',
  `psm_periodo` int(11) NOT NULL COMMENT 'cada cuantos meses se renueva'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
('cusquiskas@gmail.com', '391eb5c0a939fbf8f27e424a34badd6f007254a3e4c2a1cfdf347c10be57fafe', '2024-08-02 17:02:47', '2024-08-02 18:15:16'),
('cusquiskas@gmail.com', '6baba6753bd6c96bdeb24da49417aebfce960e6b2233743cb0e3ed01b77be05c', '2024-08-01 14:16:41', '2024-08-01 14:38:29'),
('cusquiskas@gmail.com', '8d58be6d0dac76717a5333fa6912b10a30547ec1761a418f266acb223621c5f7', '2024-07-28 13:25:01', '2024-07-28 15:54:10'),
('cusquiskas@gmail.com', '98be9c4617cfa0828976f17c1a6b8a3def2f267c76b61c69e43a26e746042d41', '2024-08-01 23:59:56', '2024-08-02 00:36:28'),
('cusquiskas@gmail.com', 'f7a837f4623cca2a69f91ac34a8b18ba43d7fb16194927405ff5698e02a945b7', '2024-08-07 19:06:42', '2024-08-07 19:52:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SPLIT`
--

CREATE TABLE `SPLIT` (
  `spl_split` int(11) NOT NULL COMMENT 'id del split',
  `spl_comunidad` int(11) NOT NULL COMMENT 'id de la comunidad',
  `spl_movimiento` int(11) NOT NULL COMMENT 'id del movimiento',
  `spl_fecha` date NOT NULL COMMENT 'fecha del movimiento',
  `spl_detalle` varchar(300) NOT NULL COMMENT 'descripción del movimiento',
  `spl_importe` decimal(7,3) NOT NULL COMMENT 'importe del movimiento',
  `spl_piso` int(11) DEFAULT NULL,
  `spl_gasto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `SPLIT`
--

INSERT INTO `SPLIT` (`spl_split`, `spl_comunidad`, `spl_movimiento`, `spl_fecha`, `spl_detalle`, `spl_importe`, `spl_piso`, `spl_gasto`) VALUES
(3, 1, 144, '2023-11-03', 'ABONO REC.DOM.OT.ENT 2A', '160.000', 5, NULL),
(4, 1, 144, '2023-11-03', 'ABONO REC.DOM.OT.ENT 2B', '160.000', 6, NULL),
(5, 1, 144, '2023-11-03', 'ABONO REC.DOM.OT.ENT 3B', '160.000', 9, NULL),
(6, 1, 144, '2023-11-03', 'ABONO REC.DOM.OT.ENT 4B', '160.000', 12, NULL),
(7, 1, 118, '2023-09-06', 'ABONO REC.DOM.OT.ENT 2A', '160.000', 5, NULL),
(8, 1, 118, '2023-09-06', 'ABONO REC.DOM.OT.ENT 2B', '160.000', 6, NULL),
(9, 1, 118, '2023-09-06', 'ABONO REC.DOM.OT.ENT 3B', '160.000', 9, NULL),
(10, 1, 118, '2023-09-06', 'ABONO REC.DOM.OT.ENT 4B', '160.000', 12, NULL),
(11, 1, 90, '2023-07-03', 'ABONO REC.DOM.OT.ENT 2A', '160.000', 5, NULL),
(12, 1, 90, '2023-07-03', 'ABONO REC.DOM.OT.ENT 2B', '160.000', 6, NULL),
(13, 1, 90, '2023-07-03', 'ABONO REC.DOM.OT.ENT 3B', '160.000', 9, NULL),
(14, 1, 90, '2023-07-03', 'ABONO REC.DOM.OT.ENT 4B', '160.000', 12, NULL),
(15, 1, 86, '2023-06-14', 'ABONO REC.DOM.OT.ENT 2A', '160.000', 5, NULL),
(16, 1, 86, '2023-06-14', 'ABONO REC.DOM.OT.ENT 2B', '160.000', 6, NULL),
(17, 1, 86, '2023-06-14', 'ABONO REC.DOM.OT.ENT 3B', '160.000', 9, NULL),
(18, 1, 86, '2023-06-14', 'ABONO REC.DOM.OT.ENT 4B', '160.000', 12, NULL);

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
('cusquiskas@gmail.com', 'José Miguel', 'ce86e5921962c3ec0f2f5901790ee4bc', 0, '2024-08-07 19:06:41', '2024-05-01');

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
-- Indices de la tabla `PROMESA`
--
ALTER TABLE `PROMESA`
  ADD PRIMARY KEY (`psm_promesa`,`psm_comunidad`,`psm_fhasta`),
  ADD KEY `com_psm_fk` (`psm_comunidad`);

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
  ADD KEY `gas_spl_fk` (`spl_gasto`),
  ADD KEY `spl_split_comunidad` (`spl_comunidad`);

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
  MODIFY `gas_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `MOVIMIENTO`
--
ALTER TABLE `MOVIMIENTO`
  MODIFY `mov_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

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
  MODIFY `spl_split` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del split', AUTO_INCREMENT=19;

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
-- Filtros para la tabla `PROMESA`
--
ALTER TABLE `PROMESA`
  ADD CONSTRAINT `com_psm_fk` FOREIGN KEY (`psm_comunidad`) REFERENCES `COMUNIDAD` (`com_comunidad`);

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
  ADD CONSTRAINT `com_spl_fk` FOREIGN KEY (`spl_comunidad`) REFERENCES `COMUNIDAD` (`com_comunidad`),
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;