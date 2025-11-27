-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2025 a las 01:22:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `resad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `domicilios`
--

CREATE TABLE `domicilios` (
  `ID_Domicilio` int(11) NOT NULL,
  `ID_Pedido` int(11) NOT NULL,
  `DireccionEntrega` varchar(255) NOT NULL,
  `ContactoEntrega` varchar(255) NOT NULL,
  `EstadoEntrega` varchar(50) NOT NULL DEFAULT 'Pendiente',
  `FechaCreacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `domicilios`
--

INSERT INTO `domicilios` (`ID_Domicilio`, `ID_Pedido`, `DireccionEntrega`, `ContactoEntrega`, `EstadoEntrega`, `FechaCreacion`) VALUES
(1, 11, 'Calle 49', 'e@m.com', 'Pendiente', '2025-11-27 00:03:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `ID_R` int(5) NOT NULL,
  `NumeroMesa` int(11) NOT NULL,
  `Capacidad` int(50) NOT NULL,
  `Ubicacion` varchar(255) NOT NULL,
  `Disponibilidad` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`ID_R`, `NumeroMesa`, `Capacidad`, `Ubicacion`, `Disponibilidad`) VALUES
(2, 1, 24, 'Alla', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `ID_P` int(5) NOT NULL,
  `NumeroPedido` int(11) DEFAULT NULL,
  `FechaPedido` date DEFAULT NULL,
  `CantidadPlatos` int(11) NOT NULL,
  `Estado` varchar(80) NOT NULL,
  `ID_Plato` int(11) DEFAULT NULL,
  `ID_User` int(11) DEFAULT NULL,
  `ID_Mesa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`ID_P`, `NumeroPedido`, `FechaPedido`, `CantidadPlatos`, `Estado`, `ID_Plato`, `ID_User`, `ID_Mesa`) VALUES
(11, 1764200326, '2025-11-28', 1, 'Pendiente', 9, 11, 2),
(12, 1764200326, '2025-11-28', 1, 'Pendiente', 10, 11, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `ID_Plato` int(5) NOT NULL,
  `NombrePlato` varchar(255) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Precio` decimal(10,2) DEFAULT NULL,
  `ImagenUrl` blob DEFAULT NULL,
  `Disponible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `platos`
--

INSERT INTO `platos` (`ID_Plato`, `NombrePlato`, `Descripcion`, `Precio`, `ImagenUrl`, `Disponible`) VALUES
(1, 'Costillas BBQ con Papas Horneadas', 'Papa y costilla asi tal cual', 15000.00, 0x636f7374696c6c612e6a7067, 1),
(9, 'Pollo a la Broaster con papas', 'Pollo broaster con papas', 17000.00, 0x706f6c6c6f62726f61737465722e6a7067, 0),
(10, 'Salchipapa', 'Salchicha con papas', 12000.00, 0x73616c63686970617061342e6a7067, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `ID_RE` int(11) NOT NULL,
  `NumeroPersonas` int(11) DEFAULT NULL,
  `FechaReserva` date DEFAULT NULL,
  `HoraReserva` time DEFAULT NULL,
  `ID_User` int(11) DEFAULT NULL,
  `ID_M` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`ID_RE`, `NumeroPersonas`, `FechaReserva`, `HoraReserva`, `ID_User`, `ID_M`) VALUES
(1, 3, '2025-11-28', '17:05:00', 10, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_User` int(5) NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellido` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Passwrd` varchar(255) DEFAULT NULL,
  `Rolusu` varchar(100) DEFAULT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `ImagenPerfil` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID_User`, `Nombre`, `Apellido`, `Email`, `Passwrd`, `Rolusu`, `Telefono`, `ImagenPerfil`) VALUES
(1, 'Brandon', 'Administrador', 'b@gmail.com', '$2y$10$mbJyoWAp3SzUhR/gHa9lA.6I4/8ZUjMRyewGIHCFVlPtApTnW6ZKO', 'Administrador', '3011613525', 0x37356264626331612d643235342d343762382d613939642d6634616366306331316339382e6a706567),
(10, 'Ola', 'Mar', 'o@m.com', '$2y$10$fy9A/Tsjm0E3CA0uLNpGPOaEYpub0i13lB7OYyMDQP2cizbWYW756', 'Cliente', '134', NULL),
(11, 'Ethan', 'Velez', 'e@m.com', '$2y$10$LdmM9Gelm0MpCn4je4oq4O2F9GH.gmJFUQumGfi5316rf8Pp4sU8W', 'Cliente', '315660555', 0x31315f313736343138353936382e6a7067);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `domicilios`
--
ALTER TABLE `domicilios`
  ADD PRIMARY KEY (`ID_Domicilio`),
  ADD KEY `fk_domicilio_pedido` (`ID_Pedido`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`ID_R`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`ID_P`),
  ADD KEY `ID_Plato` (`ID_Plato`),
  ADD KEY `ID_User` (`ID_User`),
  ADD KEY `ID_Mesa` (`ID_Mesa`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`ID_Plato`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`ID_RE`),
  ADD KEY `ID_User` (`ID_User`),
  ADD KEY `ID_M` (`ID_M`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_User`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `domicilios`
--
ALTER TABLE `domicilios`
  MODIFY `ID_Domicilio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `ID_R` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `ID_P` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `ID_Plato` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `ID_RE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_User` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `domicilios`
--
ALTER TABLE `domicilios`
  ADD CONSTRAINT `fk_domicilio_pedido` FOREIGN KEY (`ID_Pedido`) REFERENCES `pedidos` (`ID_P`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`ID_Plato`) REFERENCES `platos` (`ID_Plato`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`ID_User`) REFERENCES `usuarios` (`ID_User`),
  ADD CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`ID_Mesa`) REFERENCES `mesas` (`ID_R`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`ID_User`) REFERENCES `usuarios` (`ID_User`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`ID_M`) REFERENCES `mesas` (`ID_R`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
