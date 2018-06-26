-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2018 a las 03:36:49
-- Versión del servidor: 10.1.31-MariaDB
-- Versión de PHP: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `merceria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medias`
--

CREATE TABLE `medias` (
  `id` int(11) NOT NULL,
  `color` varchar(30) NOT NULL,
  `marca` varchar(30) NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  `talle` int(11) NOT NULL,
  `foto` varchar(120) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `medias`
--

INSERT INTO `medias` (`id`, `color`, `marca`, `precio`, `talle`, `foto`, `active`) VALUES
(38, 'rojo', 'El coso', '17.50', 0, 'localhost:8080/Parcial_Programacion_3/imgs/lEl cosorojo.png', 0),
(39, 'rojo', 'El coso', '17.50', 0, 'localhost:8080/Parcial_Programacion_3/imgs/lEl cosorojo.png', 1),
(41, 'violeta', 'ardidas', '12.50', 0, 'localhost:8080/Parcial_Programacion_3/imgs/xsardidasvioleta.jpg', 1),
(42, 'azul', 'ardidas', '12.50', 0, 'localhost:8080/Parcial_Programacion_3/imgs/xsardidasazul.jpg', 1),
(43, 'magenta', 'ardidas', '12.50', 0, 'localhost:8080/Parcial_Programacion_3/imgs/xsardidasmagenta.jpg', 1),
(44, 'cian', 'mikePortnoy', '1.00', 0, 'localhost:8080/Parcial_Programacion_3/imgs/xlmikePortnoycian.jpg', 1),
(45, 'cian', 'mikePortnoy', '1.00', 0, 'localhost:8080/Parcial_Programacion_3/imgs/xlmikePortnoycian.jpg', 1),
(46, 'cian', 'mikePortnoy', '10.00', 0, 'localhost:8080/Parcial_Programacion_3/imgs/xllmikePortnoycian.jpg', 1),
(47, 'cian', 'mikePortnoy', '10.00', 0, 'localhost:8080/Parcial_Programacion_3/imgs/xllmikePortnoycian.jpg', 1),
(48, 'cian', 'mikePortnoy', '10.00', 0, 'localhost:8080/Parcial_Programacion_3/imgs/xllmikePortnoycian.jpg', 1),
(49, 'cian', 'mikePortnoy', '10.00', 0, 'localhost:8080/Parcial_Programacion_3/imgs/xllmikePortnoycian.jpg', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `user` varchar(70) NOT NULL,
  `password` varchar(10) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `foto` varchar(150) NOT NULL DEFAULT 'sin foto'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `user`, `password`, `active`, `foto`) VALUES
(24, 'Demian-empleado', 'dem45', '1234', 1, 'sin foto'),
(25, 'demian-dueño', 'dem4501', '1234', 1, 'sin foto'),
(26, 'pepe-encargado', 'pepehot', '1234', 1, 'sin foto'),
(28, 'MarcoAntonio-encargado', 'pepehotTuPapi', '1234', 1, 'sin foto'),
(32, 'MarcoAntonio-encargado', 'pepehotTuPa', '1234', 1, 'sin foto'),
(33, 'MarcoAntonio-Empleado', 'pepeLuis22', '1234', 0, 'sin foto'),
(35, 'MarcoAntonio-Empleado', 'pepeLuis11', '1234', 0, 'sin foto'),
(36, 'MarcoAntonio-dueño', 'panchita', '1234', 0, 'sin foto'),
(37, 'MarcoAntonio-dueño', 'loro', '4567', 0, 'sin foto.jpg'),
(39, 'MarcoAntonio-dueño', 'loro8', '789', 1, 'localhost:8080/Parcial_Programacion_3/UserImg/loro8.jpg'),
(40, 'MarcoAntonio-dueño', 'loro88', '789', 0, 'localhost:8080/Parcial_Programacion_3/UserImg/loro88.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `idMedia` int(11) NOT NULL,
  `foto` varchar(150) NOT NULL,
  `nombreCliente` varchar(11) NOT NULL,
  `fecha` date NOT NULL,
  `importe` decimal(11,2) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `idMedia`, `foto`, `nombreCliente`, `fecha`, `importe`, `active`) VALUES
(1, 41, 'localhost:8080/Parcial_Programacion_3/FotosVentas/41Morbos20100202.jpg', 'Morboson', '0000-00-00', '12.50', 1),
(2, 39, 'localhost:8080/Parcial_Programacion_3/FotosVentas/39ElCoco20100202.jpg', 'ElCoco', '2010-02-02', '31.50', 1),
(3, 39, 'localhost:8080/Parcial_Programacion_3/FotosVentas/39ElCoco20100202.jpg', 'ElCoco', '2010-02-02', '31.50', 1),
(4, 38, 'localhost:8080/Parcial_Programacion_3/FotosVentas/38ElCoco20100202.jpg', 'ElCoco', '2010-02-02', '31.50', 1),
(5, 39, 'localhost:8080/Parcial_Programacion_3/FotosVentas/39ElCoco20100202.jpg', 'ElCoco', '2010-02-02', '31.50', 1),
(6, 39, 'localhost:8080/Parcial_Programacion_3/FotosVentas/39ElCoco20100202.jpg', 'ElCoco', '2010-02-02', '51.50', 1),
(7, 39, 'localhost:8080/Parcial_Programacion_3/FotosVentas/39ElCoco20100202.jpg', 'ElCoco', '2010-02-02', '31.50', 1),
(8, 39, 'localhost:8080/Parcial_Programacion_3/FotosVentas/39ElCoco20100202.jpg', 'ElCoco', '2010-02-02', '51.50', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `medias`
--
ALTER TABLE `medias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `medias`
--
ALTER TABLE `medias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
