-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-07-2018 a las 03:46:07
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
-- Base de datos: `la_comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `employee_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `name`, `active`, `employee_type`) VALUES
(1, 'pinta cerveza artesanal', 1, 2),
(2, 'bife de chorizo', 1, 3),
(3, 'bocha de helado de chocolate', 1, 4),
(4, 'panqueques', 1, 4),
(5, 'ravioles', 1, 3),
(6, 'copa de vino', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logeo`
--

CREATE TABLE `logeo` (
  `time_log` date NOT NULL,
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `client_name` varchar(60) NOT NULL,
  `code` varchar(5) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `estimate_time` datetime DEFAULT NULL,
  `ordered_time` datetime NOT NULL,
  `mesa_id` int(11) NOT NULL,
  `foto` varchar(120) NOT NULL DEFAULT 'SINFOTO',
  `active` int(11) NOT NULL DEFAULT '1',
  `delivered_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id`, `client_name`, `code`, `status`, `estimate_time`, `ordered_time`, `mesa_id`, `foto`, `active`, `delivered_time`) VALUES
(101, 'Pepe', 'ONFUV', 0, NULL, '2018-07-01 17:28:00', 4, 'imgs/orders/ONFUV6.png', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `cant` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `estimated_time` datetime NOT NULL,
  `preparer_employee` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `order_item`
--

INSERT INTO `order_item` (`id`, `order_id`, `item_id`, `cant`, `active`, `estimated_time`, `preparer_employee`, `status`) VALUES
(25, 101, 1, 1, 1, '0000-00-00 00:00:00', 0, 0),
(26, 101, 2, 1, 1, '0000-00-00 00:00:00', 0, 0),
(27, 101, 5, 1, 1, '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `category` int(11) NOT NULL,
  `user` varchar(70) NOT NULL,
  `password` varchar(10) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `ative` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `category`, `user`, `password`, `active`, `ative`) VALUES
(1, 'Federico', 0, 'Fede123', '123456', 1, 1),
(2, 'Marcelo', 1, 'Marce4', '123456', 1, 1),
(26, 'ElMozo', 5, 'mozoHot', '1', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logeo`
--
ALTER TABLE `logeo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `logeo`
--
ALTER TABLE `logeo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
