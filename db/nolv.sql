-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 12/04/2022 às 03:03
-- Versão do servidor: 10.4.21-MariaDB
-- Versão do PHP: 7.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `nolv`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `cards`
--

INSERT INTO `cards` (`id`, `client_id`, `token`) VALUES
(1, 1, 'iahspdhaspodhapsod');

-- --------------------------------------------------------

--
-- Estrutura para tabela `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Entretenimento'),
(2, 'Finanças');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiration_time` datetime NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `password`, `expiration_time`, `phone`) VALUES
(1, 'Jonas Silva', 'jonas123@alskla.su', NULL, '2022-03-29 17:30:10', '81993431392'),
(2, 'João Ferreira', 'joao222@asasda.su', NULL, '2022-03-29 17:30:10', '81993431392]'),
(3, 'Luan Silva', 'lsilva@aslkas.su', NULL, '2022-03-29 17:30:10', '81993431392'),
(4, 'Antonio Henry Vinicius Mendes', 'antonio_henry_mendes@directnet.com.br', NULL, '2022-03-29 17:30:10', '(65) 99642 - 2060'),
(5, 'Fabiana Clara Moreira', 'fabiana_clara_moreira@yahool.com', NULL, '2022-03-29 17:30:10', '(98) 98185 - 9693'),
(6, 'Jéssica Carolina Bernardes', 'jessica_carolina_bernardes@comercialmendes.net', NULL, '2022-03-29 17:30:10', '(83) 99868 - 1677'),
(13, 'Murilo Gael Martin da Paz', 'yifakis428@kuruapp.com', '$2y$10$aMA7pa9bMnaSFnB/19XsUOBRF3EdfEY16cI9ssV9uOrBLpKDbU14e', '2022-03-29 21:09:54', '(95) 98726 - 7875'),
(14, 'John Doe', 'nomawa5225@whwow.com', '$2y$10$YzWPfVncH6FV.hFQ2GVUveuQVZzKbhlNxx7GFs9kW5L8noIe.LTBq', '2022-04-09 16:37:16', '(81) 99343 - 1392'),
(15, NULL, 'pobibac550@hhmel.com', '$2y$10$V/lgo.uXvjm64OVEUZ47BuY8FbxJonWQTcDPuj0FK4EGRxdQA0rO.', '2022-04-11 21:56:23', '(81) 99343 - 1392');

-- --------------------------------------------------------

--
-- Estrutura para tabela `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(1, 'pt-br'),
(2, 'en-us'),
(3, 'es');

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `operational_system`
--

CREATE TABLE `operational_system` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `operational_system`
--

INSERT INTO `operational_system` (`id`, `name`) VALUES
(1, 'Linux'),
(2, 'MacOS'),
(3, 'Windows');

-- --------------------------------------------------------

--
-- Estrutura para tabela `paymethods`
--

CREATE TABLE `paymethods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 NOT NULL,
  `active` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `paymethods`
--

INSERT INTO `paymethods` (`id`, `name`, `type`, `active`) VALUES
(1, 'Cartão de crédito', 'card', 1),
(2, 'Boleto bancário', 'boleto', 0),
(3, 'Pix', 'pix', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `price` double DEFAULT 0,
  `file_path` varchar(255) CHARACTER SET utf8 NOT NULL,
  `operational_system_id` int(11) NOT NULL,
  `language_id` int(11) DEFAULT NULL,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `products`
--

INSERT INTO `products` (`id`, `store_id`, `category_id`, `name`, `price`, `file_path`, `operational_system_id`, `language_id`, `version`, `description`, `main_image_path`) VALUES
(13, 1, 1, 'Sistema de gerenciamento de pizzaria acbdef abcdef acdef', 187.99, '/arquivo.txt', 1, 1, '1.0.0', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aliquet nulla porta tellus malesuada, at commodo ante viverra. Quisque nisl orci, accumsan sit amet leo eget, fringilla suscipit neque. Vivamus in auctor tellus, non ornare orci. Integer maximus vehicula sollicitudin. Integer congue tempor tempor. Nulla iaculis mi sagittis mi tincidunt, at consequat metus tincidunt. Nam in condimentum sapien.\n\nMaecenas vehicula mi in ex laoreet mattis. Aliquam augue lorem, finibus quis ultricies a, congue sit amet velit. Maecenas gravida elit non sem sagittis luctus. Aliquam nisl sapien, viverra laoreet suscipit quis, iaculis non turpis. Nunc iaculis finibus eleifend. Fusce ut efficitur magna. Pellentesque quis mattis nisl, porttitor mollis tellus. Cras ornare id lacus eget maximus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.', 'https://picsum.photos/600'),
(14, 1, 1, 'Gestão fácil', 89.54, '/arquivo.txt', 2, 1, '0.0.5', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aliquet nulla porta tellus malesuada, at commodo ante viverra. Quisque nisl orci, accumsan sit amet leo eget, fringilla suscipit neque. Vivamus in auctor tellus, non ornare orci. Integer maximus vehicula sollicitudin. Integer congue tempor tempor. Nulla iaculis mi sagittis mi tincidunt, at consequat metus tincidunt. Nam in condimentum sapien.\n\nMaecenas vehicula mi in ex laoreet mattis. Aliquam augue lorem, finibus quis ultricies a, congue sit amet velit. Maecenas gravida elit non sem sagittis luctus. Aliquam nisl sapien, viverra laoreet suscipit quis, iaculis non turpis. Nunc iaculis finibus eleifend. Fusce ut efficitur magna. Pellentesque quis mattis nisl, porttitor mollis tellus. Cras ornare id lacus eget maximus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.', 'https://picsum.photos/600'),
(15, 1, 1, 'Padafácil', 9.99, '/arquivo.txt', 3, 2, '2.1.19', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aliquet nulla porta tellus malesuada, at commodo ante viverra. Quisque nisl orci, accumsan sit amet leo eget, fringilla suscipit neque. Vivamus in auctor tellus, non ornare orci. Integer maximus vehicula sollicitudin. Integer congue tempor tempor. Nulla iaculis mi sagittis mi tincidunt, at consequat metus tincidunt. Nam in condimentum sapien.\n\nMaecenas vehicula mi in ex laoreet mattis. Aliquam augue lorem, finibus quis ultricies a, congue sit amet velit. Maecenas gravida elit non sem sagittis luctus. Aliquam nisl sapien, viverra laoreet suscipit quis, iaculis non turpis. Nunc iaculis finibus eleifend. Fusce ut efficitur magna. Pellentesque quis mattis nisl, porttitor mollis tellus. Cras ornare id lacus eget maximus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.', 'https://picsum.photos/600'),
(16, 1, 1, 'Farmapédia', 3.46, '/arquivo.txt', 1, 2, '2.55.0', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aliquet nulla porta tellus malesuada, at commodo ante viverra. Quisque nisl orci, accumsan sit amet leo eget, fringilla suscipit neque. Vivamus in auctor tellus, non ornare orci. Integer maximus vehicula sollicitudin. Integer congue tempor tempor. Nulla iaculis mi sagittis mi tincidunt, at consequat metus tincidunt. Nam in condimentum sapien.\n\nMaecenas vehicula mi in ex laoreet mattis. Aliquam augue lorem, finibus quis ultricies a, congue sit amet velit. Maecenas gravida elit non sem sagittis luctus. Aliquam nisl sapien, viverra laoreet suscipit quis, iaculis non turpis. Nunc iaculis finibus eleifend. Fusce ut efficitur magna. Pellentesque quis mattis nisl, porttitor mollis tellus. Cras ornare id lacus eget maximus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.', 'https://picsum.photos/600'),
(17, 1, 1, 'Opera PIP', 10, '/arquivo.txt', 2, 3, '5.0.0', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aliquet nulla porta tellus malesuada, at commodo ante viverra. Quisque nisl orci, accumsan sit amet leo eget, fringilla suscipit neque. Vivamus in auctor tellus, non ornare orci. Integer maximus vehicula sollicitudin. Integer congue tempor tempor. Nulla iaculis mi sagittis mi tincidunt, at consequat metus tincidunt. Nam in condimentum sapien.\n\nMaecenas vehicula mi in ex laoreet mattis. Aliquam augue lorem, finibus quis ultricies a, congue sit amet velit. Maecenas gravida elit non sem sagittis luctus. Aliquam nisl sapien, viverra laoreet suscipit quis, iaculis non turpis. Nunc iaculis finibus eleifend. Fusce ut efficitur magna. Pellentesque quis mattis nisl, porttitor mollis tellus. Cras ornare id lacus eget maximus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.', 'https://picsum.photos/600');

-- --------------------------------------------------------

--
-- Estrutura para tabela `products_sales`
--

CREATE TABLE `products_sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `path` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `path`) VALUES
(3, 13, 'https://picsum.photos/600'),
(4, 13, 'https://picsum.photos/600'),
(5, 14, 'https://picsum.photos/600'),
(6, 14, 'https://picsum.photos/600'),
(7, 15, 'https://picsum.photos/600'),
(8, 15, 'https://picsum.photos/600'),
(9, 16, 'https://picsum.photos/600'),
(10, 16, 'https://picsum.photos/600'),
(11, 17, 'https://picsum.photos/600'),
(12, 17, 'https://picsum.photos/600');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT 0,
  `client_id` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `ratings`
--

INSERT INTO `ratings` (`id`, `product_id`, `rating`, `client_id`, `comment`, `created_at`, `updated_at`) VALUES
(2, 13, 5, 1, 'Esse produto é muito bom', '2022-01-31 00:00:00', '0000-00-00 00:00:00'),
(3, 13, 3, 2, 'Esse produto é meh', '2022-02-10 00:00:00', '0000-00-00 00:00:00'),
(4, 13, 1, 3, 'esse produto é ruim', '2022-02-07 00:00:00', '0000-00-00 00:00:00'),
(5, 13, 4, 1, 'Esse produto é bom mas não é perfeito', '2022-02-25 17:02:49', '2022-02-25 17:02:49'),
(6, 13, 5, 1, 'Asd comentário gg aslkdlask', '2022-02-28 11:20:49', '2022-02-28 11:20:49'),
(7, 13, 5, 1, 'Comentário atual 123 123', '2022-02-28 11:23:57', '2022-02-28 11:23:57'),
(8, 13, 4, 1, 'comentário gg', '2022-02-28 11:24:17', '2022-02-28 11:24:17'),
(9, 13, 4, 1, 'comentário gg asdasd', '2022-02-28 11:26:22', '2022-02-28 11:26:22'),
(10, 13, 4, 1, 'comentário gg asdasd asd', '2022-02-28 11:26:48', '2022-02-28 11:26:48'),
(11, 13, 2, 1, 'comentário gg asdasd asd ddd', '2022-02-28 11:26:52', '2022-02-28 11:26:52'),
(12, 13, 5, 1, 'qweqwe 123123', '2022-02-28 11:27:02', '2022-02-28 11:27:02'),
(13, 13, 5, 1, 'qweqwe 123123 3333', '2022-02-28 11:27:18', '2022-02-28 11:27:18'),
(14, 13, 2, 1, 'asddde eeee', '2022-02-28 11:29:36', '2022-02-28 11:29:36'),
(15, 13, 5, 1, 'New ratting 123', '2022-02-28 11:31:12', '2022-02-28 11:31:12'),
(16, 13, 3, 1, 'asdasd 12323', '2022-02-28 11:33:09', '2022-02-28 11:33:09'),
(17, 13, 5, 1, '123123 asdasd 123123 asdasd', '2022-02-28 11:33:27', '2022-02-28 11:33:27'),
(18, 13, 4, 1, 'abcdefg 123', '2022-02-28 11:34:28', '2022-02-28 11:34:28'),
(19, 13, 5, 1, '', '2022-02-28 16:33:08', '2022-02-28 16:33:08'),
(20, 13, 5, 1, '', '2022-02-28 16:33:10', '2022-02-28 16:33:10'),
(21, 14, 5, 1, 'asdasdasdasd', '2022-03-01 10:08:43', '2022-03-01 10:08:43'),
(22, 14, 1, 1, 'asdasd', '2022-03-01 10:08:51', '2022-03-01 10:08:51'),
(23, 14, 1, 1, '', '2022-03-01 10:08:57', '2022-03-01 10:08:57'),
(24, 14, 4, 1, '', '2022-03-01 10:09:07', '2022-03-01 10:09:07'),
(25, 14, 5, 1, '', '2022-03-01 10:09:12', '2022-03-01 10:09:12'),
(26, 14, 5, 1, 'asdasd', '2022-03-01 10:09:17', '2022-03-01 10:09:17'),
(27, 14, 5, 1, 'asdasd', '2022-03-01 10:09:19', '2022-03-01 10:09:19'),
(28, 14, 5, 1, 'asdasd', '2022-03-01 10:09:21', '2022-03-01 10:09:21'),
(29, 14, 5, 1, 'asdasd', '2022-03-01 10:09:24', '2022-03-01 10:09:24'),
(30, 14, 5, 1, 'asdasdasd', '2022-03-01 10:20:28', '2022-03-01 10:20:28'),
(31, 14, 5, 1, 'asdasdasd', '2022-03-01 10:20:30', '2022-03-01 10:20:30'),
(32, 14, 5, 1, 'asdasd', '2022-03-01 10:20:33', '2022-03-01 10:20:33'),
(33, 14, 5, 1, 'asdasdasd', '2022-03-01 10:20:35', '2022-03-01 10:20:35'),
(34, 14, 1, 1, 'asdasd', '2022-03-01 10:20:41', '2022-03-01 10:20:41'),
(35, 14, 1, 1, 'asdasd', '2022-03-01 10:20:43', '2022-03-01 10:20:43'),
(36, 14, 1, 1, 'asdasd', '2022-03-01 10:20:45', '2022-03-01 10:20:45'),
(37, 14, 1, 1, 'asdasd', '2022-03-01 10:20:47', '2022-03-01 10:20:47'),
(38, 14, 1, 1, 'adsasd', '2022-03-01 10:20:49', '2022-03-01 10:20:49'),
(39, 14, 1, 1, 'asdasd', '2022-03-01 10:20:51', '2022-03-01 10:20:51'),
(40, 14, 1, 1, 'asdasd', '2022-03-01 10:20:53', '2022-03-01 10:20:53'),
(41, 14, 1, 1, 'asdasd', '2022-03-01 10:20:55', '2022-03-01 10:20:55'),
(42, 14, 1, 1, 'asdasd', '2022-03-01 10:20:56', '2022-03-01 10:20:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `paymethod_id` int(11) NOT NULL,
  `total` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `sales`
--

INSERT INTO `sales` (`id`, `client_id`, `paymethod_id`, `total`) VALUES
(1, 1, 1, 29.49);

-- --------------------------------------------------------

--
-- Estrutura para tabela `status_tickets`
--

CREATE TABLE `status_tickets` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `status_tickets`
--

INSERT INTO `status_tickets` (`id`, `name`) VALUES
(1, 'Aberto'),
(2, 'Respondido'),
(3, 'Finalizado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `balance` double DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `stores`
--

INSERT INTO `stores` (`id`, `client_id`, `name`, `balance`) VALUES
(1, 3, 'Demais Eletronicos', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `message` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status_tickets_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tickets`
--

INSERT INTO `tickets` (`id`, `client_id`, `store_id`, `message`, `status_tickets_id`) VALUES
(1, 1, 1, 'Deu problema com produto', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cards_FK` (`client_id`);

--
-- Índices de tabela `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `operational_system`
--
ALTER TABLE `operational_system`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `paymethods`
--
ALTER TABLE `paymethods`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_FK` (`store_id`),
  ADD KEY `products_FK_1` (`category_id`),
  ADD KEY `products_FK_3` (`language_id`),
  ADD KEY `products_FK_2` (`operational_system_id`);

--
-- Índices de tabela `products_sales`
--
ALTER TABLE `products_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_sales_FK` (`product_id`),
  ADD KEY `products_sales_FK_1` (`sales_id`);

--
-- Índices de tabela `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_FK` (`product_id`);

--
-- Índices de tabela `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ratings_FK` (`product_id`),
  ADD KEY `ratings_FK_1` (`client_id`);

--
-- Índices de tabela `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_FK` (`client_id`),
  ADD KEY `sales_FK_1` (`paymethod_id`);

--
-- Índices de tabela `status_tickets`
--
ALTER TABLE `status_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stores_FK` (`client_id`);

--
-- Índices de tabela `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_FK` (`client_id`),
  ADD KEY `tickets_FK_1` (`store_id`),
  ADD KEY `tickets_FK_2` (`status_tickets_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `operational_system`
--
ALTER TABLE `operational_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `paymethods`
--
ALTER TABLE `paymethods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `products_sales`
--
ALTER TABLE `products_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de tabela `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `status_tickets`
--
ALTER TABLE `status_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_FK` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_FK` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_FK_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_FK_2` FOREIGN KEY (`operational_system_id`) REFERENCES `operational_system` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_FK_3` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`);

--
-- Restrições para tabelas `products_sales`
--
ALTER TABLE `products_sales`
  ADD CONSTRAINT `products_sales_FK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_sales_FK_1` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_FK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_FK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_FK_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_FK` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_FK_1` FOREIGN KEY (`paymethod_id`) REFERENCES `paymethods` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `stores_FK` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_FK` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_FK_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_FK_2` FOREIGN KEY (`status_tickets_id`) REFERENCES `status_tickets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
