-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 26-01-2018 a las 04:02:34
-- Versión del servidor: 10.1.28-MariaDB
-- Versión de PHP: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdtecprecinccompras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_id` int(10) UNSIGNED DEFAULT NULL,
  `rubro_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `codigo`, `tipo_id`, `rubro_id`, `created_at`, `updated_at`) VALUES
(192, 'ABERTURAS', '1', 2, 1, '2018-01-24 23:07:18', '2018-01-25 06:38:38'),
(193, 'ABRAZADERA', '2', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(194, 'BULONES', '3', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(195, 'CEPILLO', '4', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(196, 'CERRADURA', '5', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(197, 'CINTA M?TRICA', '6', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(198, 'CINTA PAPEL', '7', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(199, 'CINTA SELLADORA PARA ROSCAS', '8', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(200, 'DILUYENTE', '9', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(201, 'DISCOS AMOLADORA', '10', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(202, 'ELECTRODOS', '11', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(203, 'GRASA', '12', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(204, 'HERAMIENTA MANUAL', '13', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(205, 'LIJAS', '14', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(206, 'LUBRICANTE', '15', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(207, 'MASILLA', '16', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(208, 'PEGAMENTO', '17', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(209, 'ELECTRICIDAD', '18', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(210, 'PINCEL', '19', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(211, 'PINTURA', '20', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(212, 'REMACHE', '21', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(213, 'REPUESTO EXTINTOR', '22', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(214, 'RODILLO', '23', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(215, 'RUEDA', '24', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(216, 'SELLADOR', '25', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(217, 'SODA CAUSTICA', '26', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(218, 'TARUGOS', '27', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(219, 'TARUGOS COMPLETOS', '28', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(220, 'TORNILLO', '29', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(221, 'TRAPO', '30', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(222, 'VALVULAS', '31', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(223, 'VIDRIERIA', '32', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(224, 'ARANDELAS', '33', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(225, 'CA?OS', '34', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(226, 'MADERAS', '35', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(227, 'TUBO', '36', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(228, 'AGENTE EXTINTOR', '37', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(229, 'JUNTAS DE GOMA', '38', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(230, 'BALDE', '39', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(231, 'CADENAS', '40', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(232, 'CAJA', '41', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(233, 'DERIVACI?N', '42', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(234, 'ETIQUETAS', '43', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(235, 'EXTINTOR', '44', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(236, 'FUNDA', '45', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(237, 'GABINETE', '46', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(238, 'HIDRANTES', '47', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(239, 'JUNTA', '48', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(240, 'LABORATORIO', '49', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(241, 'LANZA', '50', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(242, 'LLAVE', '51', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(243, 'MANGUERAS', '52', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(244, 'MECHAS A/R', '53', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(245, 'MONITOR', '54', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(246, 'O`RING', '55', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(247, 'RCI', '56', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(248, 'MANTA', '57', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(249, 'SOPORTES', '60', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(250, 'TAPA', '61', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(251, 'BANDAS EL?STICAS', '64', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(252, 'BANDEJA', '65', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(253, 'BIBLIORATO', '66', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(254, 'BOLIGRAFO', '67', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(255, 'BROCHES', '68', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(256, 'CAJAS ARCHIVO', '69', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(257, 'CALCULADORA', '70', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(258, 'CARB?NICOS', '71', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(259, 'CARPET?N', '72', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(260, 'CARTUCHO TINTA', '73', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(261, 'CD?S', '74', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(262, 'CINTA EMBALAR', '75', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(263, 'CORRECTOR', '76', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(264, 'CUADERNO', '77', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(265, 'FOLIOS', '78', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(266, 'MARCADOR', '79', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(267, 'PAPEL', '80', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(268, 'PAPEL P/FAX', '81', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(269, 'REGLAS', '82', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(270, 'RED', '83', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(271, 'TIJERA', '84', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(272, 'SOBRES', '85', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(273, 'UNIONES', '86', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(274, 'LIMPIEZA', '87', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(275, 'PRIMEROS AUXILIOS', '88', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(276, 'REFRIGERIO', '89', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(277, 'RTO. V?L. PRESI?N Y VAC?O', '90', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(278, 'V?LVULA PRESI?N Y VAC?O', '91', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(279, 'CARTELERIA', '92', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(280, 'AUTOADHESIVOS', '93', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(281, 'TARJETERIA', '94', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(282, 'INDUMENTARIA', '95', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(283, 'EPP', '96', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(284, 'INSUMOS IMPRESORAS', '98', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(285, 'PC NOTEBOOK', '99', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(286, 'PC ESCRITORIO', '100', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(287, 'INSUMOS COMPUTACION', '101', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18'),
(288, 'RODADOS', '102', 1, 1, '2018-01-24 23:07:18', '2018-01-24 23:07:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categs`
--

CREATE TABLE `categs` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `categs`
--

INSERT INTO `categs` (`id`, `nombre`, `codigo`) VALUES
(1, 'ABERTURAS', 1),
(2, 'ABRAZADERA', 2),
(3, 'BULONES', 3),
(4, 'CEPILLO', 4),
(5, 'CERRADURA', 5),
(6, 'CINTA M?TRICA', 6),
(7, 'CINTA PAPEL', 7),
(8, 'CINTA SELLADORA PARA ROSCAS', 8),
(9, 'DILUYENTE', 9),
(10, 'DISCOS AMOLADORA', 10),
(11, 'ELECTRODOS', 11),
(12, 'GRASA', 12),
(13, 'HERAMIENTA MANUAL', 13),
(14, 'LIJAS', 14),
(15, 'LUBRICANTE', 15),
(16, 'MASILLA', 16),
(17, 'PEGAMENTO', 17),
(18, 'ELECTRICIDAD', 18),
(19, 'PINCEL', 19),
(20, 'PINTURA', 20),
(21, 'REMACHE', 21),
(22, 'REPUESTO EXTINTOR', 22),
(23, 'RODILLO', 23),
(24, 'RUEDA', 24),
(25, 'SELLADOR', 25),
(26, 'SODA CAUSTICA', 26),
(27, 'TARUGOS', 27),
(28, 'TARUGOS COMPLETOS', 28),
(29, 'TORNILLO', 29),
(30, 'TRAPO', 30),
(31, 'VALVULAS', 31),
(32, 'VIDRIERIA', 32),
(33, 'ARANDELAS', 33),
(34, 'CA?OS', 34),
(35, 'MADERAS', 35),
(36, 'TUBO', 36),
(37, 'AGENTE EXTINTOR', 37),
(38, 'JUNTAS DE GOMA', 38),
(39, 'BALDE', 39),
(40, 'CADENAS', 40),
(41, 'CAJA', 41),
(42, 'DERIVACI?N', 42),
(43, 'ETIQUETAS', 43),
(44, 'EXTINTOR', 44),
(45, 'FUNDA', 45),
(46, 'GABINETE', 46),
(47, 'HIDRANTES', 47),
(48, 'JUNTA', 48),
(49, 'LABORATORIO', 49),
(50, 'LANZA', 50),
(51, 'LLAVE', 51),
(52, 'MANGUERAS', 52),
(53, 'MECHAS A/R', 53),
(54, 'MONITOR', 54),
(55, 'O`RING', 55),
(56, 'RCI', 56),
(57, 'MANTA', 57),
(60, 'SOPORTES', 60),
(61, 'TAPA', 61),
(64, 'BANDAS EL?STICAS', 64),
(65, 'BANDEJA', 65),
(66, 'BIBLIORATO', 66),
(67, 'BOLIGRAFO', 67),
(68, 'BROCHES', 68),
(69, 'CAJAS ARCHIVO', 69),
(70, 'CALCULADORA', 70),
(71, 'CARB?NICOS', 71),
(72, 'CARPET?N', 72),
(73, 'CARTUCHO TINTA', 73),
(74, 'CD?S', 74),
(75, 'CINTA EMBALAR', 75),
(76, 'CORRECTOR', 76),
(77, 'CUADERNO', 77),
(78, 'FOLIOS', 78),
(79, 'MARCADOR', 79),
(80, 'PAPEL', 80),
(81, 'PAPEL P/FAX', 81),
(82, 'REGLAS', 82),
(83, 'RED', 83),
(84, 'TIJERA', 84),
(85, 'SOBRES', 85),
(86, 'UNIONES', 86),
(87, 'LIMPIEZA', 87),
(88, 'PRIMEROS AUXILIOS', 88),
(89, 'REFRIGERIO', 89),
(90, 'RTO. V?L. PRESI?N Y VAC?O', 90),
(91, 'V?LVULA PRESI?N Y VAC?O', 91),
(92, 'CARTELERIA', 92),
(93, 'AUTOADHESIVOS', 93),
(94, 'TARJETERIA', 94),
(95, 'INDUMENTARIA', 95),
(96, 'EPP', 96),
(98, 'INSUMOS IMPRESORAS', 98),
(99, 'PC NOTEBOOK', 99),
(100, 'PC ESCRITORIO', 100),
(101, 'INSUMOS COMPUTACION', 101),
(102, 'RODADOS', 102);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(10) UNSIGNED NOT NULL,
  `presupuesto_id` int(10) UNSIGNED DEFAULT NULL,
  `pedido_id` int(10) UNSIGNED NOT NULL,
  `proveedor_id` int(10) UNSIGNED NOT NULL,
  `estado` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `f_envio` date DEFAULT NULL,
  `confir_ajuste` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `f_respuesta` date DEFAULT NULL,
  `confir_rec_oc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `controlesrecepcion`
--

CREATE TABLE `controlesrecepcion` (
  `id` int(10) UNSIGNED NOT NULL,
  `compra_id` int(10) UNSIGNED NOT NULL,
  `f_recepcion` date DEFAULT NULL,
  `documento` text COLLATE utf8_unicode_ci,
  `nota_credito` text COLLATE utf8_unicode_ci,
  `estado` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2018_01_11_104025_departamentos_migration', 1),
('2018_01_11_104529_usuarios_migration', 1),
('2018_01_11_111648_tipos_migration', 1),
('2018_01_11_111650_rubros_migration', 1),
('2018_01_11_111655_categorias_migration', 1),
('2018_01_12_111853_proveedores_migration', 1),
('2018_01_12_113152_productos_migration', 1),
('2018_01_12_114657_proveedores_productos_migration', 1),
('2018_01_12_114955_pedidos_migration', 1),
('2018_01_12_115224_stock_migration', 1),
('2018_01_12_123636_pedido_stock_migration', 1),
('2018_01_12_130721_stockDepartamentos_migration', 1),
('2018_01_12_142706_presupuestos_migration', 1),
('2018_01_12_144643_compras_migration', 1),
('2018_01_12_145347_controlesRecepcion_migration', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(10) UNSIGNED NOT NULL,
  `estado` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_stock`
--

CREATE TABLE `pedido_stock` (
  `id` int(10) UNSIGNED NOT NULL,
  `pedido_id` int(10) UNSIGNED NOT NULL,
  `stock_id` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `aprobado` int(11) DEFAULT NULL,
  `entregado` int(11) DEFAULT NULL,
  `f_entrega` date DEFAULT NULL,
  `tipo_entrega` int(11) DEFAULT NULL,
  `devuelto` int(11) DEFAULT NULL,
  `cancelado` int(11) DEFAULT NULL,
  `pendiente` int(11) DEFAULT NULL,
  `observaciones` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos`
--

CREATE TABLE `presupuestos` (
  `id` int(10) UNSIGNED NOT NULL,
  `pedido_id` int(10) UNSIGNED NOT NULL,
  `proveedor_id` int(10) UNSIGNED NOT NULL,
  `estado` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `f_envio` date DEFAULT NULL,
  `f_respuesta` date DEFAULT NULL,
  `f_entrega` date DEFAULT NULL,
  `documento` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` text COLLATE utf8_unicode_ci NOT NULL,
  `categoria_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(10) UNSIGNED NOT NULL,
  `razonSocial` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nombreFantacia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `habilitado` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `calificacion` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores_productos`
--

CREATE TABLE `proveedores_productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `precio` double(8,2) DEFAULT NULL,
  `proveedor_id` int(10) UNSIGNED NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubros`
--

CREATE TABLE `rubros` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rubros`
--

INSERT INTO `rubros` (`id`, `nombre`, `codigo`, `created_at`, `updated_at`) VALUES
(1, 'FERRETERIA', '1', '2018-01-19 18:56:39', '2018-01-19 18:56:39'),
(2, 'EXTINTORES', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'REDES', '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'INFORMATICA', '4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'INDUMENTARIA & EPPS', '5', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'REFRIGERIO', '6', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'LIMPIEZA', '7', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'AUTOMOTOS', '8', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'OFICINA', '9', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'ELECTRICIDAD', '10', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'HERRAMIENTAS MANUALES', '11', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'HERRAMIENTAS ELECTRICAS', '12', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` text COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `precio` double(8,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `peps` double(8,2) NOT NULL,
  `valor_reposicion` double(8,2) NOT NULL,
  `stock_min` int(11) NOT NULL,
  `partida_parcial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `categoria_id` int(10) UNSIGNED DEFAULT NULL,
  `proveedor_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stockdepartamentos`
--

CREATE TABLE `stockdepartamentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `stock_id` int(10) UNSIGNED NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_min` int(11) DEFAULT NULL,
  `departamento_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos`
--

CREATE TABLE `tipos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipos`
--

INSERT INTO `tipos` (`id`, `nombre`, `codigo`, `created_at`, `updated_at`) VALUES
(1, 'CONSUMO', '1', '2018-01-19 17:29:13', '2018-01-19 17:29:13'),
(2, 'USO', '2', '2018-01-19 17:32:29', '2018-01-19 17:47:03'),
(4, 'SERVICIOS', '3', '2018-01-19 17:49:28', '2018-01-19 17:49:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rol` int(11) NOT NULL,
  `codigo_verificacion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `departamento_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_nombre_unique` (`nombre`),
  ADD UNIQUE KEY `categorias_codigo_unique` (`codigo`),
  ADD KEY `categorias_tipo_id_foreign` (`tipo_id`),
  ADD KEY `categorias_rubros_id_foreign` (`rubro_id`);

--
-- Indices de la tabla `categs`
--
ALTER TABLE `categs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compras_presupuesto_id_foreign` (`presupuesto_id`),
  ADD KEY `compras_pedido_id_foreign` (`pedido_id`),
  ADD KEY `compras_proveedor_id_foreign` (`proveedor_id`);

--
-- Indices de la tabla `controlesrecepcion`
--
ALTER TABLE `controlesrecepcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `controlesrecepcion_compra_id_foreign` (`compra_id`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departamentos_nombre_unique` (`nombre`),
  ADD UNIQUE KEY `departamentos_codigo_unique` (`codigo`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_usuario_id_foreign` (`usuario_id`);

--
-- Indices de la tabla `pedido_stock`
--
ALTER TABLE `pedido_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_stock_pedido_id_foreign` (`pedido_id`),
  ADD KEY `pedido_stock_stock_id_foreign` (`stock_id`);

--
-- Indices de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `presupuestos_pedido_id_foreign` (`pedido_id`),
  ADD KEY `presupuestos_proveedor_id_foreign` (`proveedor_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productos_categoria_id_foreign` (`categoria_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proveedores_razonsocial_unique` (`razonSocial`),
  ADD UNIQUE KEY `proveedores_cuit_unique` (`cuit`),
  ADD UNIQUE KEY `proveedores_nombrefantacia_unique` (`nombreFantacia`),
  ADD UNIQUE KEY `proveedores_email_unique` (`email`);

--
-- Indices de la tabla `proveedores_productos`
--
ALTER TABLE `proveedores_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedores_productos_proveedor_id_foreign` (`proveedor_id`),
  ADD KEY `proveedores_productos_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `rubros`
--
ALTER TABLE `rubros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rubros_nombre_unique` (`nombre`),
  ADD UNIQUE KEY `rubros_codigo_unique` (`codigo`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_codigo_unique` (`codigo`),
  ADD KEY `stock_categoria_id_foreign` (`categoria_id`),
  ADD KEY `stock_proveedor_id_foreign` (`proveedor_id`);

--
-- Indices de la tabla `stockdepartamentos`
--
ALTER TABLE `stockdepartamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stockdepartamentos_stock_id_foreign` (`stock_id`),
  ADD KEY `stockdepartamentos_departamento_id_foreign` (`departamento_id`);

--
-- Indices de la tabla `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipos_nombre_unique` (`nombre`),
  ADD UNIQUE KEY `tipos_codigo_unique` (`codigo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_user_unique` (`user`),
  ADD UNIQUE KEY `usuarios_email_unique` (`email`),
  ADD KEY `usuarios_departamento_id_foreign` (`departamento_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT de la tabla `categs`
--
ALTER TABLE `categs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `controlesrecepcion`
--
ALTER TABLE `controlesrecepcion`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido_stock`
--
ALTER TABLE `pedido_stock`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores_productos`
--
ALTER TABLE `proveedores_productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rubros`
--
ALTER TABLE `rubros`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `stockdepartamentos`
--
ALTER TABLE `stockdepartamentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos`
--
ALTER TABLE `tipos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_rubros_id_foreign` FOREIGN KEY (`rubro_id`) REFERENCES `rubros` (`id`),
  ADD CONSTRAINT `categorias_tipo_id_foreign` FOREIGN KEY (`tipo_id`) REFERENCES `tipos` (`id`);

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `compras_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`),
  ADD CONSTRAINT `compras_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `controlesrecepcion`
--
ALTER TABLE `controlesrecepcion`
  ADD CONSTRAINT `controlesrecepcion_compra_id_foreign` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedido_stock`
--
ALTER TABLE `pedido_stock`
  ADD CONSTRAINT `pedido_stock_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedido_stock_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`id`);

--
-- Filtros para la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD CONSTRAINT `presupuestos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `presupuestos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `proveedores_productos`
--
ALTER TABLE `proveedores_productos`
  ADD CONSTRAINT `proveedores_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `proveedores_productos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `stock_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `stockdepartamentos`
--
ALTER TABLE `stockdepartamentos`
  ADD CONSTRAINT `stockdepartamentos_departamento_id_foreign` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`),
  ADD CONSTRAINT `stockdepartamentos_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_departamento_id_foreign` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
