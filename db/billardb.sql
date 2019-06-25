-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-01-2018 a las 00:17:29
-- Versión del servidor: 10.1.29-MariaDB
-- Versión de PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `billardb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `documento` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `tipo_documento`, `documento`, `nombre`, `direccion`, `telefono`, `status`) VALUES
(1, 'CC', 1125436736, 'Jhon Doe', 'Bucaramanga', '311-8001020', 1),
(2, 'CC', 11234567, 'Jairo Yepez', 'San luis', '311-111111', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `id` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cuenta`
--

INSERT INTO `cuenta` (`id`, `id_mesa`, `id_cliente`, `id_usuario`, `fecha_creacion`, `fecha_actualizacion`, `fecha_cierre`, `status`) VALUES
(2, 3, 1, 1, '2018-01-18 00:00:00', '2018-01-18 00:00:00', '2018-01-31 00:00:00', 1),
(3, 3, 2, 2, '2018-01-24 00:00:00', '2018-01-24 00:00:00', '2018-01-21 00:00:00', 1),
(4, 2, 1, 1, '2018-01-24 00:00:00', '2018-01-24 00:00:00', '2018-01-24 00:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_detalle`
--

CREATE TABLE `cuenta_detalle` (
  `id` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` double NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cuenta_detalle`
--

INSERT INTO `cuenta_detalle` (`id`, `id_cuenta`, `id_producto`, `cantidad`, `precio`) VALUES
(1, 2, 1, 3, '2000.00'),
(2, 2, 2, 4.5, '1200.00'),
(3, 3, 1, 4, '1500.00'),
(4, 4, 3, 2, '6000.00'),
(5, 4, 5, 4, '1300.00'),
(6, 4, 5, 4, '1300.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`id`, `nombre`, `tipo`, `status`) VALUES
(1, 'Mesa 01 - Billar', 'Billar', 0),
(2, 'Mesa 02 - Billar', 'Billar', 0),
(3, 'Mesa 03 - Billar', 'Billar', 1),
(4, 'Mesa 04 - Billar', 'Billar', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mov_cuenta`
--

CREATE TABLE `mov_cuenta` (
  `id` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `tipo_movimiento` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Movimientos de la cuenta, historico y auditoria';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `valor_compra` double DEFAULT NULL,
  `valor_venta` double NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `valor_compra`, `valor_venta`, `foto`, `status`) VALUES
(1, 'Horas de Billar', 'Tiempo de juego por horas', 0, 2500, '', 1),
(2, 'Gaseosa Hipinto', 'Gaseosa', 1000, 1500, NULL, 1),
(3, 'Cerveza', 'Cerveza', 1200, 1500, NULL, 1),
(4, 'Cigarro', 'Cigarro', 5000, 6000, NULL, 1),
(5, 'Aguila', 'Cerveza', 1200, 1300, NULL, 1),
(6, 'Poker', 'Cerveza', NULL, 2500, NULL, 1),
(7, 'Heineken', 'Cerveza', NULL, 3000, NULL, 1),
(8, 'Redds', 'Cerveza', NULL, 3000, NULL, 1),
(9, 'Corona', 'Cerveza', NULL, 3000, NULL, 1),
(10, 'Gaseosa Postobon', 'Gaseosa', NULL, 2000, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `fecha_creacion` date NOT NULL,
  `hora_desde` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `valor_venta` double NOT NULL,
  `hora_hasta` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`id`, `id_producto`, `fecha_creacion`, `hora_desde`, `valor_venta`, `hora_hasta`, `status`) VALUES
(10, 1, '2018-01-18', '10:00:00', 2000, '12:00:00', 1),
(12, 2, '2018-01-18', '10:00:00', 2000, '12:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `perfil` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL,
  `telefono` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `perfil`, `status`, `telefono`) VALUES
(1, 'jmangarret', 'Cajero', 1, '311-8002621'),
(2, 'Maria Perez', 'Cajera', 1, '311-5002121');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesa_idx` (`id_mesa`),
  ADD KEY `cliente_idx` (`id_cliente`),
  ADD KEY `usuario_idx` (`id_usuario`);

--
-- Indices de la tabla `cuenta_detalle`
--
ALTER TABLE `cuenta_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_116648A517F00A22` (`id_cuenta`),
  ADD KEY `IDX_116648A5F760EA80` (`id_producto`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mov_cuenta`
--
ALTER TABLE `mov_cuenta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuenta_idx` (`id_cuenta`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_idx` (`id_producto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cuenta_detalle`
--
ALTER TABLE `cuenta_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mov_cuenta`
--
ALTER TABLE `mov_cuenta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD CONSTRAINT `FK_31C7BFCF13939EF0` FOREIGN KEY (`id_mesa`) REFERENCES `mesa` (`id`),
  ADD CONSTRAINT `FK_31C7BFCF2A813255` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `FK_31C7BFCFFCF8192D` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `cuenta_detalle`
--
ALTER TABLE `cuenta_detalle`
  ADD CONSTRAINT `FK_116648A517F00A22` FOREIGN KEY (`id_cuenta`) REFERENCES `cuenta` (`id`),
  ADD CONSTRAINT `FK_116648A5F760EA80` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `mov_cuenta`
--
ALTER TABLE `mov_cuenta`
  ADD CONSTRAINT `FK_7BEEB6E617F00A22` FOREIGN KEY (`id_cuenta`) REFERENCES `cuenta` (`id`);

--
-- Filtros para la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD CONSTRAINT `FK_CD312F7F760EA80` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
