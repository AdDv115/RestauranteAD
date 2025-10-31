-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-10-2025 a las 13:09:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `ID_Plato` int(5) NOT NULL,
  `NombrePlato` varchar(255) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `ImagenUrl` blob NOT NULL,
  `Disponible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `platos`
--

INSERT INTO `platos` (`ID_Plato`, `NombrePlato`, `Descripcion`, `Precio`, `ImagenUrl`, `Disponible`) VALUES
(1, 'Costillas BBQ con Papas Horneadas', 'Papa y costilla asi tal cual', 15000.00, 0x30316338636533642d666661392d343761642d393537322d3833346234326464616239322e6a706567, 1),
(7, 'Arroz con queso y sopa', 'arroz que le eche queso', 15000.00, '', 1),
(9, 'Pollo', 'Pollo frito', 17000.00, 0x38396363366137342d366632372d343738642d616239302d6131383432346166373134392e6a706567, 0);

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
  `ImagenPerfil` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID_User`, `Nombre`, `Apellido`, `Email`, `Passwrd`, `Rolusu`, `Telefono`, `ImagenPerfil`) VALUES
(1, 'Admin', 'Principal', 'b@gmail.com', '$2y$10$mbJyoWAp3SzUhR/gHa9lA.6I4/8ZUjMRyewGIHCFVlPtApTnW6ZKO', 'Administrador', '3011011010', 0x37356264626331612d643235342d343762382d613939642d6634616366306331316339382e6a706567),
(3, 'Ethan', 'Velez', 'ev@m.com', '$2y$10$rSDFKIW9pjeJCOt3gfoAxOrqK615rBMru/ZYRIQjOBZzab.b6/FHa', 'Cliente', '301', ''),
(6, 'Hector', 'Figueroas', 'h@m.com', '$2y$10$./Sl/miFWKq94mZ68QZ.T.ADHZQNm.bjRLWIjvKKdNUeK1mUBtaJ2', 'Cliente', '11545', 0x31393235646637622d346230372d343366382d623031612d6563636461633931303036312e6a706567);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`ID_Plato`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_User`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `ID_Plato` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_User` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
