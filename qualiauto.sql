-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 20, 2026 at 11:08 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qualiauto`
--

-- --------------------------------------------------------

--
-- Table structure for table `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id` int NOT NULL,
  `username` varchar(500) NOT NULL,
  `password` varchar(255) NOT NULL,
  `perfil` enum('admin','user') NOT NULL DEFAULT 'user',
  `data_registo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `utilizadores`
--

INSERT INTO `utilizadores` (`id`, `username`, `password`, `perfil`, `data_registo`) VALUES
(1, 'admin', '$2y$10$s/Z80QLFZznSQ99CSqHWn.VzqZrJx6uHDfVlRcslL3GrKMSQ236Ty', 'admin', '2026-02-19 09:48:19'), -- pass: 1234
(2, 'dinis', '$2y$10$rBqyPmVoAZ9W.1uAV3VHZejf2huIbNkFZ3XqyrEDcj0rKWsleQqyG', 'user', '2026-02-19 10:21:44'); -- pass: 1234

-- --------------------------------------------------------

--
-- Table structure for table `viaturas`
--

CREATE TABLE `viaturas` (
  `id` int NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `ano` int NOT NULL,
  `imagem` varchar(500) NOT NULL DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `viaturas`
--

INSERT INTO `viaturas` (`id`, `modelo`, `preco`, `ano`, `imagem`) VALUES
(4, 'Tesla | Model 3', 24999.99, 2024, '6996f0ee8457f.png'),
(5, 'Tesla | Model Y', 29999.99, 2024, '6996f1174d986.png'),
(6, 'Tesla | Model Y', 18999.99, 2023, '6996f12a3433d.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `viaturas`
--
ALTER TABLE `viaturas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `viaturas`
--
ALTER TABLE `viaturas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
