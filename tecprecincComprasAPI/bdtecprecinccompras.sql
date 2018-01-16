-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-01-2018 a las 00:25:33
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `codigo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `codigo`, `created_at`, `updated_at`) VALUES
(1, 'cat1', 'codcat1', '2018-01-13 23:54:49', '2018-01-13 23:54:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(10) UNSIGNED NOT NULL,
  `presupuesto_id` int(10) UNSIGNED NOT NULL,
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
  `codigo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id`, `nombre`, `codigo`, `created_at`, `updated_at`) VALUES
(2, 'dep2', '2', '2018-01-12 16:05:06', '2018-01-12 16:05:06'),
(4, 'dep3', '3', '2018-01-12 16:41:44', '2018-01-12 16:41:44');

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
('2018_01_11_111648_categorias_migration', 1),
('2018_01_11_111853_proveedores_migration', 1),
('2018_01_11_113152_productos_migration', 1),
('2018_01_11_114657_proveedores_productos_migration', 1),
('2018_01_11_114955_pedidos_migration', 1),
('2018_01_11_115224_stock_migration', 1),
('2018_01_11_123636_pedido_stock_migration', 1),
('2018_01_11_130721_stockDepartamentos_migration', 1),
('2018_01_11_142706_presupuestos_migration', 1),
('2018_01_11_144643_compras_migration', 1),
('2018_01_11_145347_controlesRecepcion_migration', 1);

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

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `estado`, `usuario_id`, `created_at`, `updated_at`) VALUES
(1, 'creado', 1, '2018-01-16 21:35:08', '2018-01-16 21:35:08');

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

--
-- Volcado de datos para la tabla `pedido_stock`
--

INSERT INTO `pedido_stock` (`id`, `pedido_id`, `stock_id`, `cantidad`, `aprobado`, `entregado`, `f_entrega`, `tipo_entrega`, `devuelto`, `cancelado`, `pendiente`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 0, 0, NULL, NULL, 0, 0, NULL, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 1, 2, 12, 0, 0, NULL, NULL, 0, 0, NULL, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

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
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `categoria_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `categoria_id`, `created_at`, `updated_at`) VALUES
(2, 'producto1', 1, '2018-01-13 23:57:56', '2018-01-14 00:18:10'),
(3, 'prod2', 1, '2018-01-14 17:53:33', '2018-01-14 17:53:33');

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

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `razonSocial`, `nombreFantacia`, `cuit`, `telefono`, `fax`, `email`, `habilitado`, `estado`, `calificacion`, `created_at`, `updated_at`) VALUES
(1, 'fr', 'prove', '122', '2334', '5000', 'e@c.com', 'si', 'estado', 10, '2018-01-14 17:54:50', '2018-01-16 16:45:02');

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

--
-- Volcado de datos para la tabla `proveedores_productos`
--

INSERT INTO `proveedores_productos` (`id`, `precio`, `proveedor_id`, `producto_id`, `created_at`, `updated_at`) VALUES
(3, 3000.00, 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 2000.00, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 5000.00, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` text COLLATE utf8_unicode_ci NOT NULL,
  `codigo` int(11) DEFAULT NULL,
  `precio` double(8,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `peps` double(8,2) NOT NULL,
  `valor_reposicion` double(8,2) NOT NULL,
  `stock_min` int(11) NOT NULL,
  `partida_parcial` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `categoria_id` int(10) UNSIGNED NOT NULL,
  `proveedor_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`id`, `nombre`, `codigo`, `precio`, `stock`, `peps`, `valor_reposicion`, `stock_min`, `partida_parcial`, `categoria_id`, `proveedor_id`, `created_at`, `updated_at`) VALUES
(1, 'prod1', 111, 1200.00, 6, 10.00, 10.00, 5, 'partida parcial', 1, 1, '2018-01-16 20:31:21', '2018-01-16 20:31:21'),
(2, 'prod2', 222, 1200.00, 6, 10.00, 10.00, 5, 'partida parcial', 1, 1, '2018-01-16 20:33:13', '2018-01-16 20:33:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stockdepartamentos`
--

CREATE TABLE `stockdepartamentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `codigo` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_min` int(11) NOT NULL,
  `categoria_id` int(10) UNSIGNED NOT NULL,
  `proveedor_id` int(10) UNSIGNED NOT NULL,
  `departamento_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `telefono` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rol` int(11) NOT NULL,
  `codigo_verificacion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `departamento_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `user`, `password`, `email`, `nombre`, `apellido`, `telefono`, `rol`, `codigo_verificacion`, `departamento_id`, `created_at`, `updated_at`) VALUES
(1, 'freddy', '$2y$10$UbrqaHGy0cHZCVhAkFFKge7sIqd41tsCfcaTzLscAdygtOLFoHGye', 'c@c.com', 'freddy ', 'ramirez', '3453434', 1, NULL, 2, '2018-01-13 16:40:05', '2018-01-13 16:40:05'),
(2, 'juan', '$2y$10$Scwu6YuEK32l8d2DjW9gZeF6Ay0ZFGQlv5Yxg0.U/3tHrJQ5TQtsa', 'c@juan.com', 'juan', 'ramirez', '3453434', 1, NULL, 2, '2018-01-13 16:42:39', '2018-01-13 16:42:39');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_nombre_unique` (`nombre`),
  ADD UNIQUE KEY `categorias_codigo_unique` (`codigo`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compras_presupuesto_id_foreign` (`presupuesto_id`);

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
  ADD UNIQUE KEY `departamentos_nombre_unique` (`nombre`);

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
  ADD UNIQUE KEY `productos_nombre_unique` (`nombre`),
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
  ADD KEY `stockdepartamentos_categoria_id_foreign` (`categoria_id`),
  ADD KEY `stockdepartamentos_proveedor_id_foreign` (`proveedor_id`),
  ADD KEY `stockdepartamentos_departamento_id_foreign` (`departamento_id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `pedido_stock`
--
ALTER TABLE `pedido_stock`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `proveedores_productos`
--
ALTER TABLE `proveedores_productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `stockdepartamentos`
--
ALTER TABLE `stockdepartamentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`);

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
  ADD CONSTRAINT `stockdepartamentos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `stockdepartamentos_departamento_id_foreign` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`),
  ADD CONSTRAINT `stockdepartamentos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_departamento_id_foreign` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
