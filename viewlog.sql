-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 25-Jan-2018 às 21:19
-- Versão do servidor: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `viewlog`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `name` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `report`
--

INSERT INTO `report` (`id`, `owner`, `name`) VALUES
(0, 1000, 'BD Restantes'),
(0, 32114, 'Simulador Avaliação Excel'),
(0, 41263, 'RESTANTE'),
(0, 43294, 'Serviços sem Microáreas'),
(1, 1000, 'BD Indicadores'),
(1, 32114, 'MONITOR GAM'),
(2, 1000, 'TA Indicadores'),
(2, 32114, 'TOA Tempo Rota SPI'),
(3, 1000, 'TA Restantes'),
(3, 32114, 'Volumetria e SLA SPI'),
(4, 32114, 'Volumetria e SLA NO'),
(5, 32114, 'Volumetria e SLA CO'),
(6, 32114, 'Preventiva VS Corretiva'),
(7, 32114, 'Dash Diario SPI'),
(8, 32114, 'Dash Diario NO'),
(9, 32114, 'Dash Diario CO'),
(10, 32114, 'Dash Mensal SPI'),
(11, 32114, 'Dash Mensal NO'),
(12, 32114, 'Dash Mensal CO'),
(13, 32114, 'GERENCIAL GAM SPI'),
(14, 32114, 'GERENCIAL GAM NO'),
(15, 32114, 'GERENCIAL GAM CO'),
(16, 32114, 'GERENCIAL GAM SPI Mês Anterior'),
(17, 32114, 'GERENCIAL GAM NO Mês Anterior'),
(18, 32114, 'GERENCIAL GAM CO Mês Anterior'),
(19, 32114, 'BD Restantes'),
(20, 32114, 'BD Indicadores'),
(21, 32114, 'TA Indicadores'),
(22, 32114, 'TA Restante'),
(23, 32114, 'NO Volumetria por UF'),
(24, 32114, 'Preventivas SPI Diário'),
(25, 32114, 'NC Pendentes');

-- --------------------------------------------------------

--
-- Estrutura da tabela `share`
--

CREATE TABLE IF NOT EXISTS `share` (
  `owner_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `share`
--

INSERT INTO `share` (`owner_id`, `report_id`, `usr_id`) VALUES
(1000, 0, 32114),
(1000, 1, 32114),
(1000, 2, 32114),
(1000, 3, 32114);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usr`
--

CREATE TABLE IF NOT EXISTS `usr` (
  `uid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usr`
--

INSERT INTO `usr` (`uid`, `name`) VALUES
(1000, 'Rede Fixa'),
(32114, 'Paulo Cézar'),
(41263, 'Denis Fidelis'),
(43294, 'Natanael');

-- --------------------------------------------------------

--
-- Estrutura da tabela `viewlog`
--

CREATE TABLE IF NOT EXISTS `viewlog` (
  `id` int(11) NOT NULL,
  `ref` varchar(500) NOT NULL,
  `dt` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `gccr_id` int(11) NOT NULL,
  `gccr_name` varchar(200) NOT NULL,
  `rep_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`,`owner`);

--
-- Indexes for table `share`
--
ALTER TABLE `share`
  ADD PRIMARY KEY (`report_id`,`owner_id`,`usr_id`);

--
-- Indexes for table `usr`
--
ALTER TABLE `usr`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `viewlog`
--
ALTER TABLE `viewlog`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `viewlog`
--
ALTER TABLE `viewlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
