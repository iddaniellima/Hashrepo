-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Tempo de geração: 31/03/2019 às 11:14
-- Versão do servidor: 5.7.23-0ubuntu0.16.04.1
-- Versão do PHP: 7.2.11-2+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `hashrepo`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `hashrepo_accounts_activators`
--

CREATE TABLE `hashrepo_accounts_activators` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `expiresIn` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `hashrepo_activators`
--

CREATE TABLE `hashrepo_activators` (
  `id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(30) CHARACTER SET utf8 NOT NULL,
  `ExpiresIn` varchar(30) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Fazendo dump de dados para tabela `hashrepo_activators`
--

INSERT INTO `hashrepo_activators` (`id`, `device_id`, `user_id`, `code`, `ExpiresIn`) VALUES
(3, 18, 1, 'cab2b9', '1553067613'),
(4, 19, 1, '28fa11', '1553069648'),
(5, 20, 1, '4e0ff4', '1553069703'),
(6, 21, 1, '0f5c83', '1553069814'),
(7, 22, 1, '9c4fb3', '1553069853'),
(8, 23, 1, '0a1304', '1553069861'),
(9, 24, 1, 'cc0e78', '1553070148');

-- --------------------------------------------------------

--
-- Estrutura para tabela `hashrepo_clients`
--

CREATE TABLE `hashrepo_clients` (
  `id` int(11) NOT NULL,
  `clientID` varchar(40) NOT NULL,
  `deviceType` varchar(15) NOT NULL,
  `SystemName` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `hashrepo_clients`
--

INSERT INTO `hashrepo_clients` (`id`, `clientID`, `deviceType`, `SystemName`) VALUES
(1, '78d06ef5049f1567ae66a495a199f5e5', 'Mobile', 'Android'),
(3, '24e95dd9d3778af5bff7148110a82de6', 'Desktop', 'Windows');

-- --------------------------------------------------------

--
-- Estrutura para tabela `hashrepo_devices`
--

CREATE TABLE `hashrepo_devices` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `hash` text NOT NULL,
  `clientID` varchar(40) NOT NULL,
  `deviceID` varchar(30) NOT NULL,
  `deviceName` varchar(30) NOT NULL,
  `deviceModel` varchar(30) NOT NULL,
  `deviceSystem` varchar(30) NOT NULL,
  `deviceLastIP` varchar(25) NOT NULL,
  `Status` varchar(25) NOT NULL,
  `ExpiresIn` varchar(35) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `hashrepo_devices`
--

INSERT INTO `hashrepo_devices` (`id`, `uid`, `hash`, `clientID`, `deviceID`, `deviceName`, `deviceModel`, `deviceSystem`, `deviceLastIP`, `Status`, `ExpiresIn`) VALUES
(24, 1, '24d4f5d864fc3c5e01c7a1578279fc76e5d1ab0789d6627b278fba30f3fc272c760bca7b22f85736de2f8d75a876bc26b58a2d1d4dfb743e18371e644d79595c', '78d06ef5049f1567ae66a495a199f5e5', '60-F7-2D-E4-7C-09', 'Asus', 'XS18589', 'Android', '50.16.249.180', 'Waiting Confirmation', '1555661248'),
(23, 1, 'd52b4b36b427ccf34c834272a9cdee4287106ce0fb563ad50e3bfba5403806a1ed7906928cf23b36d38bccf8cc71cd3b7512f4c786beedc8e5d00eeea03b3287', '78d06ef5049f1567ae66a495a199f5e5', '60-F7-2D-E4-7C-09', 'Asus', 'XS18589', 'Android', '50.17.187.84', 'Waiting Confirmation', '1555660961'),
(22, 1, '130d5000f8db645a417483a39df6a24d9f99df77e981651072b096caa2e93b6f4e2b0f0f4fcbc28871dff337eee6237adaa5e826c5367f1086544f6255bdd444', '78d06ef5049f1567ae66a495a199f5e5', '60-F7-2D-E4-7C-09', 'Asus', 'XS18589', 'Android', '50.17.187.84', 'Waiting Confirmation', '1555660953'),
(21, 1, 'ddb8255f3af4379b7e237aeed77fef3ee7309040cc14e2cc9fcab5712adff97038f28201efd5e743fffc984da7ce6ceefc084cb15f5bc67fd7566a16cc44345a', '78d06ef5049f1567ae66a495a199f5e5', '60-F7-2D-E4-7C-09', 'Asus', 'XS18589', 'Android', '50.16.249.180', 'Waiting Confirmation', '1555660914'),
(20, 1, '9c5979559ed412776eab0171402d40a84f227cd8d311bf6453bab739852e8f26573e958373e610d70d36421e68726871d0c98e1bad063bafb189b4481a5a9dc9', '78d06ef5049f1567ae66a495a199f5e5', '60-F7-2D-E4-7C-09', 'Asus', 'XS18589', 'Android', '179.198.182.247', 'Waiting Confirmation', '1555660803'),
(19, 1, '02dfbb3a2bf5b3e5adb07875a9a263cfbb65db5cceabdaac6e1fee223d7ef86fa3abf263c393c94f5ffa2cf1e119d99ccf3fdc66922a2c4f4dc5d16b215dc3e5', '78d06ef5049f1567ae66a495a199f5e5', '60-F7-2D-E4-7C-09', 'Asus', 'XS18589', 'Android', '179.198.182.247', 'Waiting Confirmation', '1555660748'),
(18, 1, 'b6167c186b684dc3ee8485da6bb2be04f258fa0e164b41cf7e3ebddb89551ace11d74f1a8be53e3f0e5fb6f909b46087656592379f3c2b4849a761bf687394d3', '78d06ef5049f1567ae66a495a199f5e5', '60-F7-2D-E4-7C-09', 'Asus', 'XS18589', 'Android', '179.198.182.247', 'Waiting Confirmation', '1555658713');

-- --------------------------------------------------------

--
-- Estrutura para tabela `hashrepo_users`
--

CREATE TABLE `hashrepo_users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` text NOT NULL,
  `security_questions` text NOT NULL,
  `security_answers` text NOT NULL,
  `account_status` varchar(30) NOT NULL,
  `created` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `hashrepo_users`
--

INSERT INTO `hashrepo_users` (`id`, `firstname`, `surname`, `email`, `password`, `security_questions`, `security_answers`, `account_status`, `created`) VALUES
(1, 'Daniel', 'Lima', 'iddaniellima@outlook.com', '$2y$10$mF5OTtC.JRQRKKZh5lZDu.QMPGUhek4iPzmRuK7zd/kF9FbgsPmy.', '', '', '', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `SystemOperations`
--

CREATE TABLE `SystemOperations` (
  `OPERATION_ID` int(11) NOT NULL,
  `OPERATION` varchar(100) NOT NULL,
  `USER_IP` varchar(20) NOT NULL,
  `OPERATION_TIME` varchar(30) NOT NULL,
  `OPERATION_ADDITIONAL_DATA` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `SystemOperations`
--

INSERT INTO `SystemOperations` (`OPERATION_ID`, `OPERATION`, `USER_IP`, `OPERATION_TIME`, `OPERATION_ADDITIONAL_DATA`) VALUES
(126, 'Activation code created. With id 9 for device id 24', '50.16.249.180', '1553069248', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(125, 'Device registered. With ID 24', '50.16.249.180', '1553069248', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(124, 'Validated credentials', '50.16.249.180', '1553069248', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(123, 'Decoding Header Petition', '50.16.249.180', '1553069248', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(122, 'Decoding Header Petition', '50.17.187.84', '1553069201', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(121, 'Activation code created. With id 8 for device id 23', '50.17.187.84', '1553068961', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(120, 'Device registered. With ID 23', '50.17.187.84', '1553068961', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(119, 'Validated credentials', '50.17.187.84', '1553068961', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(118, 'Decoding Header Petition', '50.17.187.84', '1553068960', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(117, 'Activation code created. With id 7 for device id 22', '50.17.187.84', '1553068953', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(116, 'Device registered. With ID 22', '50.17.187.84', '1553068953', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(115, 'Validated credentials', '50.17.187.84', '1553068953', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(114, 'Decoding Header Petition', '50.17.187.84', '1553068953', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(113, 'Activation code created. With id 6 for device id 21', '50.16.249.180', '1553068914', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(112, 'Device registered. With ID 21', '50.16.249.180', '1553068914', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(106, 'Decoding Header Petition', '179.198.182.247', '1553068802', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(111, 'Validated credentials', '50.16.249.180', '1553068914', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(110, 'Decoding Header Petition', '50.16.249.180', '1553068914', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(109, 'Activation code created. With id 5 for device id 20', '179.198.182.247', '1553068803', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(108, 'Device registered. With ID 20', '179.198.182.247', '1553068803', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}'),
(107, 'Validated credentials', '179.198.182.247', '1553068803', '{"ClientID":"78d06ef5049f1567ae66a495a199f5e5","credentials":{"identifier":"iddaniellima@outlook.com"},"deviceData":{"deviceID":"60-F7-2D-E4-7C-09","deviceName":"Asus","deviceModel":"XS18589","deviceSystem":"Android"}}');

-- --------------------------------------------------------

--
-- Estrutura para tabela `teste`
--

CREATE TABLE `teste` (
  `id` int(11) NOT NULL,
  `dar` varchar(123) NOT NULL,
  `sa` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `teste`
--

INSERT INTO `teste` (`id`, `dar`, `sa`) VALUES
(1, 'daniel', 'sa\r\n');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `hashrepo_accounts_activators`
--
ALTER TABLE `hashrepo_accounts_activators`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `hashrepo_activators`
--
ALTER TABLE `hashrepo_activators`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `hashrepo_clients`
--
ALTER TABLE `hashrepo_clients`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `hashrepo_devices`
--
ALTER TABLE `hashrepo_devices`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `hashrepo_users`
--
ALTER TABLE `hashrepo_users`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `SystemOperations`
--
ALTER TABLE `SystemOperations`
  ADD PRIMARY KEY (`OPERATION_ID`);

--
-- Índices de tabela `teste`
--
ALTER TABLE `teste`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `hashrepo_accounts_activators`
--
ALTER TABLE `hashrepo_accounts_activators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `hashrepo_activators`
--
ALTER TABLE `hashrepo_activators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de tabela `hashrepo_clients`
--
ALTER TABLE `hashrepo_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `hashrepo_devices`
--
ALTER TABLE `hashrepo_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT de tabela `hashrepo_users`
--
ALTER TABLE `hashrepo_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `SystemOperations`
--
ALTER TABLE `SystemOperations`
  MODIFY `OPERATION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;
--
-- AUTO_INCREMENT de tabela `teste`
--
ALTER TABLE `teste`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
