-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 06/11/2021 às 14:56
-- Versão do servidor: 10.3.31-MariaDB
-- Versão do PHP: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `nolvcomb_softnew`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_clientes`
--

CREATE TABLE `tbl_clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_compras`
--

CREATE TABLE `tbl_compras` (
  `id` int(11) NOT NULL,
  `preco_total` double NOT NULL,
  `forma_pagamento` varchar(255) NOT NULL,
  `tbl_clientes_id` int(11) NOT NULL,
  `data_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_produtos`
--

CREATE TABLE `tbl_produtos` (
  `id` int(11) NOT NULL,
  `tbl_vendedores_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` double NOT NULL,
  `versao` varchar(45) NOT NULL,
  `tamanho` varchar(255) NOT NULL,
  `url_download` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_produtos_has_tbl_compras`
--

CREATE TABLE `tbl_produtos_has_tbl_compras` (
  `tbl_produtos_id` int(11) NOT NULL,
  `tbl_compras_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_vendedores`
--

CREATE TABLE `tbl_vendedores` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `tbl_clientes_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbl_clientes`
--
ALTER TABLE `tbl_clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `tbl_compras`
--
ALTER TABLE `tbl_compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_compras_clientes` (`tbl_clientes_id`);

--
-- Índices de tabela `tbl_produtos`
--
ALTER TABLE `tbl_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_vendedores_id` (`tbl_vendedores_id`) USING BTREE;

--
-- Índices de tabela `tbl_produtos_has_tbl_compras`
--
ALTER TABLE `tbl_produtos_has_tbl_compras`
  ADD KEY `fk_compras` (`tbl_compras_id`),
  ADD KEY `fk_produtos` (`tbl_produtos_id`);

--
-- Índices de tabela `tbl_vendedores`
--
ALTER TABLE `tbl_vendedores`
  ADD PRIMARY KEY (`id`);

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbl_compras`
--
ALTER TABLE `tbl_compras`
  ADD CONSTRAINT `fk_compras_clientes` FOREIGN KEY (`tbl_clientes_id`) REFERENCES `tbl_clientes` (`id`);

--
-- Restrições para tabelas `tbl_produtos`
--
ALTER TABLE `tbl_produtos`
  ADD CONSTRAINT `fk_produtos_vendedores` FOREIGN KEY (`tbl_vendedores_id`) REFERENCES `tbl_vendedores` (`id`);

--
-- Restrições para tabelas `tbl_produtos_has_tbl_compras`
--
ALTER TABLE `tbl_produtos_has_tbl_compras`
  ADD CONSTRAINT `fk_compras` FOREIGN KEY (`tbl_compras_id`) REFERENCES `tbl_compras` (`id`),
  ADD CONSTRAINT `fk_produtos` FOREIGN KEY (`tbl_produtos_id`) REFERENCES `tbl_produtos` (`id`);

--
-- Restrições para tabelas `tbl_vendedores`
--
ALTER TABLE `tbl_vendedores`
  ADD CONSTRAINT `fk_vendedor_cliente` FOREIGN KEY (`tbl_clientes_id`) REFERENCES `tbl_clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
