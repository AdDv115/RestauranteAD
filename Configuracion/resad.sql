-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2025 at 03:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resad`
--

-- --------------------------------------------------------

--
-- Table structure for table `domicilios`
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
-- Dumping data for table `domicilios`
--

INSERT INTO `domicilios` (`ID_Domicilio`, `ID_Pedido`, `DireccionEntrega`, `ContactoEntrega`, `EstadoEntrega`, `FechaCreacion`) VALUES
(2, 13, 'Calle 2', '123', 'Pendiente', '2025-12-04 14:44:26');

-- --------------------------------------------------------

--
-- Table structure for table `mesas`
--

CREATE TABLE `mesas` (
  `ID_R` int(5) NOT NULL,
  `NumeroMesa` int(11) NOT NULL,
  `Capacidad` int(50) NOT NULL,
  `Ubicacion` varchar(255) NOT NULL,
  `Disponibilidad` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesas`
--

INSERT INTO `mesas` (`ID_R`, `NumeroMesa`, `Capacidad`, `Ubicacion`, `Disponibilidad`) VALUES
(2, 1, 8, 'Esquina A', 1),
(3, 2, 4, 'Esquina B', 1),
(4, 3, 3, 'Centro', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
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
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`ID_P`, `NumeroPedido`, `FechaPedido`, `CantidadPlatos`, `Estado`, `ID_Plato`, `ID_User`, `ID_Mesa`) VALUES
(13, 1764859454, '2025-12-05', 1, 'Pendiente', 1, 11, NULL),
(14, 1764859454, '2025-12-05', 1, 'Pendiente', 10, 11, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `platos`
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
-- Dumping data for table `platos`
--

INSERT INTO `platos` (`ID_Plato`, `NombrePlato`, `Descripcion`, `Precio`, `ImagenUrl`, `Disponible`) VALUES
(1, 'Costillas BBQ con Papas Horneadas', 'Papa y costilla asi tal cual', 15000.00, 0x636f7374696c6c612e6a7067, 1),
(9, 'Pollo a la Broaster con papas', 'Pollo broaster con papas', 17000.00, 0x706f6c6c6f62726f61737465722e6a7067, 0),
(10, 'Salchipapa', 'Salchicha con papas', 12000.00, 0x73616c63686970617061342e6a7067, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `ID_RE` int(11) NOT NULL,
  `NumeroPersonas` int(11) DEFAULT NULL,
  `FechaReserva` date DEFAULT NULL,
  `HoraReserva` time DEFAULT NULL,
  `Estado` varchar(100) NOT NULL,
  `ID_User` int(11) DEFAULT NULL,
  `ID_M` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`ID_RE`, `NumeroPersonas`, `FechaReserva`, `HoraReserva`, `Estado`, `ID_User`, `ID_M`) VALUES
(1, 3, '2025-12-04', '17:05:00', 'Pendiente', 10, 2),
(3, 3, '2025-12-05', '17:12:00', 'Pendiente', 11, 2);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
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
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`ID_User`, `Nombre`, `Apellido`, `Email`, `Passwrd`, `Rolusu`, `Telefono`, `ImagenPerfil`) VALUES
(1, 'Brandon', 'Administrador', 'b@gmail.com', '$2y$10$mbJyoWAp3SzUhR/gHa9lA.6I4/8ZUjMRyewGIHCFVlPtApTnW6ZKO', 'Administrador', '3011613525', 0x315f313736343835323237322e6a7067),
(10, 'Ola', 'Mar', 'o@m.com', '$2y$10$fy9A/Tsjm0E3CA0uLNpGPOaEYpub0i13lB7OYyMDQP2cizbWYW756', 'Cliente', '134', NULL),
(11, 'Ethan', 'Velez', 'e@m.com', '$2y$10$LdmM9Gelm0MpCn4je4oq4O2F9GH.gmJFUQumGfi5316rf8Pp4sU8W', 'Cliente', '315660555', 0x31315f313736343138353936382e6a7067);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `domicilios`
--
ALTER TABLE `domicilios`
  ADD PRIMARY KEY (`ID_Domicilio`),
  ADD KEY `fk_domicilio_pedido` (`ID_Pedido`);

--
-- Indexes for table `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`ID_R`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`ID_P`),
  ADD KEY `ID_Plato` (`ID_Plato`),
  ADD KEY `ID_User` (`ID_User`),
  ADD KEY `ID_Mesa` (`ID_Mesa`);

--
-- Indexes for table `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`ID_Plato`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`ID_RE`),
  ADD KEY `ID_User` (`ID_User`),
  ADD KEY `ID_M` (`ID_M`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_User`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `domicilios`
--
ALTER TABLE `domicilios`
  MODIFY `ID_Domicilio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mesas`
--
ALTER TABLE `mesas`
  MODIFY `ID_R` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `ID_P` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `platos`
--
ALTER TABLE `platos`
  MODIFY `ID_Plato` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `ID_RE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_User` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `domicilios`
--
ALTER TABLE `domicilios`
  ADD CONSTRAINT `fk_domicilio_pedido` FOREIGN KEY (`ID_Pedido`) REFERENCES `pedidos` (`ID_P`) ON DELETE CASCADE;

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`ID_Plato`) REFERENCES `platos` (`ID_Plato`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`ID_User`) REFERENCES `usuarios` (`ID_User`),
  ADD CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`ID_Mesa`) REFERENCES `mesas` (`ID_R`);

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`ID_User`) REFERENCES `usuarios` (`ID_User`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`ID_M`) REFERENCES `mesas` (`ID_R`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
