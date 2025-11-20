-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 20, 2025 at 02:40 PM
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
(2, 1, 24, 'Alla', 1);

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
(1, 'Costillas BBQ con Papas Horneadas', 'Papa y costilla asi tal cual', 15000.00, 0x30316338636533642d666661392d343761642d393537322d3833346234326464616239322e6a706567, 1),
(9, 'Pollo', 'Pollo frito', 17000.00, 0x38396363366137342d366632372d343738642d616239302d6131383432346166373134392e6a706567, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
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
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`ID_RE`, `NumeroPersonas`, `FechaReserva`, `HoraReserva`, `ID_User`, `ID_M`) VALUES
(1, 3, '2025-11-28', '17:05:00', 10, 2);

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
(1, 'Brandon', 'Administrador', 'b@gmail.com', '$2y$10$mbJyoWAp3SzUhR/gHa9lA.6I4/8ZUjMRyewGIHCFVlPtApTnW6ZKO', 'Administrador', '3011613525', 0x37356264626331612d643235342d343762382d613939642d6634616366306331316339382e6a706567),
(10, 'Ola', 'Mar', 'o@m.com', '$2y$10$fy9A/Tsjm0E3CA0uLNpGPOaEYpub0i13lB7OYyMDQP2cizbWYW756', 'Cliente', '134', NULL),
(11, 'Ethan', 'Velez', 'e@m.com', '$2y$10$LdmM9Gelm0MpCn4je4oq4O2F9GH.gmJFUQumGfi5316rf8Pp4sU8W', 'Cliente', '3156605', 0x31315f313736333634353437352e6a7067);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`ID_R`);

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
-- AUTO_INCREMENT for table `mesas`
--
ALTER TABLE `mesas`
  MODIFY `ID_R` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `platos`
--
ALTER TABLE `platos`
  MODIFY `ID_Plato` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `ID_RE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_User` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

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
