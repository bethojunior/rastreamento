-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 08/03/2015 às 19h36min
-- Versão do Servidor: 5.5.41
-- Versão do PHP: 5.3.10-1ubuntu3.16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `tracker`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `bem`
--

CREATE TABLE IF NOT EXISTS `bem` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `imei` varchar(17) NOT NULL,
  `name` varchar(45) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `identificacao` varchar(20) DEFAULT NULL,
  `cliente` int(11) unsigned DEFAULT NULL,
  `activated` char(1) NOT NULL DEFAULT 'S',
  `modo_operacao` varchar(4) NOT NULL DEFAULT 'GPRS' COMMENT 'Indica o modo atual',
  `porta` int(5) DEFAULT NULL,
  `liberado` char(1) NOT NULL DEFAULT 'N' COMMENT 'Indica se esta liberado para rastrear',
  `status_sinal` char(1) DEFAULT 'D' COMMENT 'Indica o status do aparelho. R=rastreando;S=sem sinal gps;D=desligado',
  `cor_grafico` char(6) DEFAULT NULL COMMENT 'Indica a cor no grafico de estatistica',
  `id_admin` int(10) unsigned NOT NULL DEFAULT '0',
  `tipo` varchar(20) NOT NULL,
  `movimento` varchar(1) NOT NULL DEFAULT 'N',
  `hodometro` int(11) NOT NULL DEFAULT '0',
  `hod_dtalter` date NOT NULL,
  `envia_sms` char(1) DEFAULT 'N',
  `modelo` varchar(30) DEFAULT NULL,
  `alerta_hodometro` int(11) NOT NULL DEFAULT '0',
  `alerta_hodometro_saldo` int(11) NOT NULL DEFAULT '0',
  `marca` varchar(30) DEFAULT NULL,
  `cor` varchar(30) DEFAULT NULL,
  `ano` varchar(4) DEFAULT NULL,
  `operadora` varchar(15) DEFAULT NULL,
  `dt_recarga` varchar(10) DEFAULT NULL,
  `cidade` varchar(60) NOT NULL,
  `ligado` varchar(1) DEFAULT 'N',
  `responsible` varchar(255) DEFAULT NULL,
  `bloqueado` char(1) DEFAULT 'N',
  `modelo_rastreador` varchar(20) DEFAULT 'tk',
  `serial_tracker` int(11) DEFAULT NULL COMMENT 'Serial do rastreador gt06',
  `apelido` varchar(30) DEFAULT NULL,
  `limite_velocidade` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `imei` (`imei`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=120 ;

--
-- Extraindo dados da tabela `bem`
--

INSERT INTO `bem` (`id`, `imei`, `name`, `date`, `identificacao`, `cliente`, `activated`, `modo_operacao`, `porta`, `liberado`, `status_sinal`, `cor_grafico`, `id_admin`, `tipo`, `movimento`, `hodometro`, `hod_dtalter`, `envia_sms`, `modelo`, `alerta_hodometro`, `alerta_hodometro_saldo`, `marca`, `cor`, `ano`, `operadora`, `dt_recarga`, `cidade`, `ligado`, `responsible`, `bloqueado`, `modelo_rastreador`, `serial_tracker`, `apelido`, `limite_velocidade`) VALUES
(119, '355879053548531', '355879053548531', '2015-03-08 11:33:28', '6499569984', 427, 'S', 'GPRS', NULL, 'S', 'D', NULL, 0, 'CARRO', 'N', 20000, '0000-00-00', 'S', 'teste', 30000, 30000, 'teste', 'teste', 'test', '', '2015-12-29', '', 'N', NULL, 'S', 'tk103', NULL, '355879053548531', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `apelido` varchar(30) DEFAULT NULL,
  `senha` text,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `data_inativacao` date DEFAULT NULL,
  `observacao` text,
  `master` char(1) NOT NULL DEFAULT 'N' COMMENT 'Indica se eh gerenciador do sistema',
  `admin` char(1) NOT NULL DEFAULT 'N',
  `representante` char(1) DEFAULT 'N' COMMENT 'O usuÃ¡rio Ã© representante de vendas?',
  `id_admin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Informa o administrador do cliente',
  `celular` varchar(15) DEFAULT NULL,
  `dt_ultm_sms` datetime DEFAULT NULL,
  `envia_sms` char(1) DEFAULT 'N',
  `sms_acada` int(11) DEFAULT '60',
  `cpf` varchar(14) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(80) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `tipo_plano` varchar(15) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `data_contrato` date DEFAULT NULL,
  `configuracoes` text,
  `dia_vencimento` int(11) DEFAULT NULL,
  `tipo_pessoa` char(1) NOT NULL COMMENT 'F - FÃ­sica ou J - JurÃ­dica',
  `rg` varchar(15) NOT NULL,
  `nacionalidade` varchar(25) NOT NULL,
  `valor_adesao` decimal(7,2) NOT NULL,
  `valor_mensalidade` decimal(7,2) NOT NULL,
  PRIMARY KEY (`id`,`email`),
  UNIQUE KEY `email_unq` (`email`),
  UNIQUE KEY `apelido_unq` (`apelido`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=428 ;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`id`, `email`, `nome`, `apelido`, `senha`, `ativo`, `data_inativacao`, `observacao`, `master`, `admin`, `representante`, `id_admin`, `celular`, `dt_ultm_sms`, `envia_sms`, `sms_acada`, `cpf`, `endereco`, `bairro`, `cidade`, `estado`, `cep`, `tipo_plano`, `telefone1`, `telefone2`, `data_contrato`, `configuracoes`, `dia_vencimento`, `tipo_pessoa`, `rg`, `nacionalidade`, `valor_adesao`, `valor_mensalidade`) VALUES
(0000000412, 'otagomes@hotmail.com', 'Sistema de Rastreamento', 'adm', 'e10adc3949ba59abbe56e057f20f883e', 'S', NULL, NULL, 'S', 'S', 'N', 0, '88611304', NULL, 'N', 60, '04512159000151', 'Rua Major Izidoro JerÃŽnimo da Rocha', 'Jacarecica', 'MaceiÃ³', 'AL', '57038600', NULL, '33555009', '', NULL, '{"comandos":true,"grupos":true,"dados":true,"despesas":true,"cerca":true,"rota":true,"hodometro":true,"logo":true,"senha":true}', NULL, '', '00002222', '', 0.00, 0.00),
(0000000427, 'jrdiniz_@hotmail.com', 'Junior', 'junior', 'f6ee6d0fc1c542dac10684d70c3fa0f9', 'S', NULL, NULL, 'N', 'N', 'N', 412, '0000000', NULL, 'S', 1, '111111', '', '', '', '', '00000-000', NULL, '', '', '2015-03-07', NULL, 1, 'F', '11111', 'Brasileiro', 0.00, 0.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente_log`
--

CREATE TABLE IF NOT EXISTS `cliente_log` (
  `id` int(10) unsigned NOT NULL,
  `data_logon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `cliente_log`
--

INSERT INTO `cliente_log` (`id`, `data_logon`, `ip`) VALUES
(413, '2014-10-29 13:20:53', '187.20.215.51'),
(412, '2015-02-11 17:10:05', '191.205.105.81'),
(418, '2015-02-11 17:13:24', '191.225.34.10'),
(412, '2015-02-11 17:17:36', '187.59.218.89'),
(412, '2015-03-07 17:30:25', '187.121.148.154'),
(412, '2015-03-07 19:19:07', '187.121.148.154'),
(412, '2015-03-07 19:24:44', '187.121.148.154'),
(412, '2015-03-07 19:26:33', '187.114.33.228'),
(412, '2015-03-07 19:26:53', '187.121.148.154'),
(412, '2015-03-07 19:48:17', '187.121.148.154'),
(427, '2015-03-07 20:07:23', '187.121.148.154'),
(427, '2015-03-07 20:09:28', '187.114.33.228'),
(427, '2015-03-07 20:21:43', '177.84.238.234'),
(427, '2015-03-08 00:41:39', '177.84.239.195'),
(427, '2015-03-08 11:31:31', '177.84.239.66'),
(427, '2015-03-08 12:34:00', '177.84.239.180'),
(427, '2015-03-08 12:34:26', '177.84.239.180'),
(427, '2015-03-08 18:03:14', '177.84.238.234');

-- --------------------------------------------------------

--
-- Estrutura da tabela `command`
--

CREATE TABLE IF NOT EXISTS `command` (
  `imei` varchar(17) NOT NULL,
  `command` varchar(45) NOT NULL,
  `userid` varchar(45) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`imei`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Guarda os comandos que serão enviados ao gps';

--
-- Extraindo dados da tabela `command`
--

INSERT INTO `command` (`imei`, `command`, `userid`, `date`) VALUES
('355488000041697', '**,imei:355488000041697,C,30s', '418', '2015-02-04 04:18:13'),
('355879053548531', '**,imei:355879053548531,J', '427', '2015-03-08 11:33:28'),
('358899052526129', 'DYD#', '', '2015-02-10 19:02:51'),
('359710040860042', '**,imei:359710040860042,J', '418', '2015-02-10 23:07:14'),
('359710041358145', '**,imei:359710041358145,C,30s', '', '2014-11-24 20:17:35'),
('359710041548174', '**,imei:359710041548174,G', '418', '2015-02-09 20:53:57'),
('359710043050674', '**,imei:359710043050674,C,15s', '417', '2014-10-28 19:04:49'),
('359710044171255', '**,imei:359710044171255,C,15s', '423', '2015-02-10 16:29:04'),
('359710046163938', '**,imei:359710046163938,E', '422', '2015-02-11 02:27:22'),
('400637', 'HFYD#', '', '2015-02-10 01:28:44'),
('800322', 'DYD#', '', '2015-02-06 00:24:28'),
('860058010450044', '', '', '2015-02-07 03:39:41'),
('grupo_1', '**,imei:grupo_1,B', '413', '2014-11-12 22:48:48'),
('grupo_3', '**,imei:grupo_3,B', '413', '2014-11-28 21:54:27'),
('grupo_5', '**,imei:grupo_5,G', '418', '2015-01-23 18:10:28'),
('grupo_6', '**,imei:grupo_6,J', '418', '2015-02-05 23:45:06'),
('grupo_7', '**,imei:grupo_7,J', '418', '2015-01-27 12:31:27'),
('grupo_8', '**,imei:grupo_8,G', '418', '2015-02-01 03:40:01');

-- --------------------------------------------------------

--
-- Estrutura da tabela `command_log`
--

CREATE TABLE IF NOT EXISTS `command_log` (
  `imei` varchar(17) NOT NULL,
  `command` varchar(45) NOT NULL,
  `cliente` int(10) unsigned NOT NULL,
  `ip` varchar(45) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `Index_1` (`data`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `command_log`
--

INSERT INTO `command_log` (`imei`, `command`, `cliente`, `ip`, `data`) VALUES
('359710041358145', '**,imei:359710041358145,J', 413, '200.158.0.135', '2014-10-30 03:24:39'),
('359710041358145', '**,imei:359710041358145,J', 413, '200.158.0.135', '2014-10-30 03:24:42'),
('359710041358145', '**,imei:359710041358145,J', 413, '177.98.174.115', '2014-10-31 21:49:35'),
('359710041358145', '**,imei:359710041358145,J', 413, '189.27.165.10', '2014-11-12 23:10:36'),
('359710041358145', '**,imei:359710041358145,J', 413, '2.83.37.137', '2014-11-13 00:17:46'),
('359710041358145', '**,imei:359710041358145,J', 413, '2.83.37.137', '2014-11-13 00:31:48'),
('359710041358145', '**,imei:359710041358145,J', 413, '189.27.165.10', '2014-11-13 02:27:40'),
('359710041548174', '**,imei:359710041548174,J', 418, '187.54.105.200', '2015-01-22 17:21:17'),
('359710040860042', '**,imei:359710040860042,J', 418, '201.88.32.53', '2015-01-23 15:56:09'),
('355488000041697', '**,imei:355488000041697,J', 418, '177.86.181.206', '2015-01-25 00:12:49'),
('355488000041697', '**,imei:355488000041697,J', 418, '186.213.30.88', '2015-01-26 04:54:00'),
('359710041548174', '**,imei:359710041548174,J', 418, '189.48.252.170', '2015-01-26 05:40:26'),
('359710041548174', '**,imei:359710041548174,J', 418, '189.48.252.170', '2015-01-26 05:40:33'),
('359710041548174', '**,imei:359710041548174,J', 418, '189.48.252.170', '2015-01-26 05:40:38'),
('359710041548174', '**,imei:359710041548174,J', 418, '189.48.252.170', '2015-01-26 05:40:39'),
('359710041548174', '**,imei:359710041548174,J', 418, '189.48.252.170', '2015-01-26 05:40:41'),
('359710041548174', '**,imei:359710041548174,J', 418, '131.221.131.13', '2015-01-27 04:14:10'),
('359710041548174', '**,imei:359710041548174,J', 418, '131.221.131.13', '2015-01-27 04:14:12'),
('grupo_7', '**,imei:grupo_7,J', 418, '189.110.164.238', '2015-01-27 12:31:27'),
('grupo_6', '**,imei:grupo_6,J', 418, '131.72.34.107', '2015-01-29 00:22:18'),
('359710041548174', '**,imei:359710041548174,J', 418, '189.72.203.21', '2015-01-29 00:31:28'),
('359710040860042', '**,imei:359710040860042,J', 418, '201.50.148.178', '2015-01-29 01:47:24'),
('359710040860042', '**,imei:359710040860042,J', 418, '201.50.148.178', '2015-01-29 01:47:27'),
('359710040860042', '**,imei:359710040860042,J', 418, '201.50.148.178', '2015-01-29 01:52:17'),
('359710040860042', '**,imei:359710040860042,J', 418, '177.138.93.14', '2015-01-31 17:09:41'),
('359710041548174', '**,imei:359710041548174,J', 0, '187.126.150.231', '2015-02-01 03:59:54'),
('359710040860042', '**,imei:359710040860042,J', 418, '186.209.62.244', '2015-02-04 01:19:47'),
('113456', 'DYD#', 418, '201.86.138.231', '2015-02-05 01:35:30'),
('359710041548174', '**,imei:359710041548174,J', 0, '201.67.93.59', '2015-02-05 02:06:37'),
('359710041548174', '**,imei:359710041548174,J', 0, '201.67.93.59', '2015-02-05 02:07:21'),
('359710040860042', '**,imei:359710040860042,J', 418, '179.192.8.202', '2015-02-05 04:55:27'),
('113456', 'DYD#', 418, '201.13.149.127', '2015-02-05 18:02:08'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-05 18:13:20'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-05 18:14:05'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-05 18:14:26'),
('359710040860042', '**,imei:359710040860042,J', 418, '177.40.178.239', '2015-02-05 19:43:14'),
('359710040860042', '**,imei:359710040860042,J', 418, '177.40.178.239', '2015-02-05 19:43:21'),
('grupo_6', '**,imei:grupo_6,J', 418, '179.252.45.206', '2015-02-05 23:45:06'),
('359710041548174', '**,imei:359710041548174,J', 418, '179.252.45.206', '2015-02-05 23:49:35'),
('800322', 'DYD#', 418, '179.252.45.206', '2015-02-06 00:24:28'),
('400637', 'DYD#', 418, '179.252.45.206', '2015-02-06 00:24:56'),
('359710040860042', '**,imei:359710040860042,J', 418, '179.186.184.72', '2015-02-08 19:21:36'),
('359710040860042', '**,imei:359710040860042,J', 418, '189.59.172.200', '2015-02-09 00:32:31'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-09 16:38:10'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-09 16:38:43'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-09 16:48:18'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-09 16:48:20'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-09 16:48:21'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-09 16:48:22'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-09 16:48:22'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-09 16:48:23'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-09 16:48:26'),
('113456', 'DYD#', 418, '177.64.10.39', '2015-02-09 16:48:27'),
('grupo_6', '**,imei:grupo_6,J', 418, '191.181.176.59', '2015-02-10 01:10:06'),
('grupo_6', '**,imei:grupo_6,J', 418, '191.181.176.59', '2015-02-10 01:10:07'),
('359710046163938', '**,imei:359710046163938,J', 422, '177.151.135.229', '2015-02-10 15:47:53'),
('358899052526129', 'DYD#', 418, '201.67.90.195', '2015-02-10 19:02:51'),
('359710040860042', '**,imei:359710040860042,J', 418, '189.83.39.243', '2015-02-10 23:07:14'),
('355879053548531', '**,imei:355879053548531,J', 427, '177.84.239.66', '2015-03-08 11:33:27');

-- --------------------------------------------------------

--
-- Estrutura da tabela `controle`
--

CREATE TABLE IF NOT EXISTS `controle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texto` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `despesas`
--

CREATE TABLE IF NOT EXISTS `despesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produto` varchar(150) NOT NULL,
  `quantidade` decimal(12,2) NOT NULL,
  `vl_unitario` decimal(12,2) NOT NULL,
  `vl_total` decimal(12,2) NOT NULL,
  `data` date NOT NULL,
  `cliente` int(11) NOT NULL,
  `bem` int(11) NOT NULL,
  `quilometragem` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `est_quantidade_coordenadas`
--

CREATE TABLE IF NOT EXISTS `est_quantidade_coordenadas` (
  `imei` varchar(15) NOT NULL,
  `data` date NOT NULL,
  `quantidade` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`imei`,`data`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `geo_distance`
--

CREATE TABLE IF NOT EXISTS `geo_distance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bem` int(11) NOT NULL,
  `latitudeDecimalDegrees` varchar(12) NOT NULL,
  `latitudeHemisphere` char(1) NOT NULL,
  `longitudeDecimalDegrees` varchar(12) NOT NULL,
  `longitudeHemisphere` char(1) NOT NULL,
  `tipo` char(1) NOT NULL,
  `parou` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bem` (`bem`,`tipo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Guarda o inicio e fim do trajeto para que possa ser calculada a distancia' AUTO_INCREMENT=565 ;

--
-- Extraindo dados da tabela `geo_distance`
--

INSERT INTO `geo_distance` (`id`, `bem`, `latitudeDecimalDegrees`, `latitudeHemisphere`, `longitudeDecimalDegrees`, `longitudeHemisphere`, `tipo`, `parou`) VALUES
(547, 102, '2031.9625', 'S', '04723.4090', 'W', 'I', 'S'),
(548, 102, '', '', '', '', 'F', ''),
(549, 101, '2040.4432', 'S', '04346.9401', 'W', 'I', 'S'),
(550, 101, '', '', '', '', 'F', ''),
(551, 103, '1956.7091', 'S', '04402.4519', 'W', 'I', 'S'),
(552, 103, '', '', '', '', 'F', ''),
(553, 105, '1955.7995', 'S', '04404.9036', 'W', 'I', 'S'),
(554, 105, '', '', '', '', 'F', ''),
(555, 108, '-19.89968611', 'S', '-44.02109444', 'W', 'I', 'S'),
(556, 108, '', '', '', '', 'F', ''),
(557, 110, '01956.7126', 'S', '04402.4527', 'W', 'I', 'S'),
(558, 110, '', '', '', '', 'F', ''),
(559, 114, '1955.8447', 'S', '04404.8518', 'W', 'I', 'S'),
(560, 114, '', '', '', '', 'F', ''),
(561, 115, '2526.0942', 'S', '04916.0411', 'W', 'I', 'N'),
(562, 115, '', '', '', '', 'F', ''),
(563, 117, '01955.4978', 'S', '04404.3037', 'W', 'I', 'N'),
(564, 117, '', '', '', '', 'F', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `geo_fence`
--

CREATE TABLE IF NOT EXISTS `geo_fence` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `tipo` int(11) NOT NULL,
  `imei` varchar(17) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `dt_incao` varchar(20) NOT NULL,
  `dt_altao` varchar(20) NOT NULL,
  `coordenadas` text NOT NULL,
  `tipoEnvio` char(1) NOT NULL,
  `disp` varchar(1) NOT NULL,
  `tipoAcao` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Extraindo dados da tabela `geo_fence`
--

INSERT INTO `geo_fence` (`id`, `tipo`, `imei`, `nome`, `dt_incao`, `dt_altao`, `coordenadas`, `tipoEnvio`, `disp`, `tipoAcao`) VALUES
(00000000043, 0, '355488000041697', 'teste', '06/02/2015 18:58:00', '06/02/2015 23:31:17', '-23.638312651090683,-46.52890205383301|-23.631727302617286,-46.52836561203003|-23.62954521857689,-46.50928974151611|-23.63845025185409,-46.50107145309448|-23.653644417559743,-46.51070594787598|-23.654430613568298,-46.53014659881592', '0', 'S', '0'),
(00000000044, 0, '359710040860042', 'CASA', '07/02/2015 09:23:34', '11/02/2015 12:54:34', '-19.917113094136152,-44.082770347595215|-19.92663503543234,-44.09641742706299|-19.943902177174976,-44.07959461212158|-19.94240953151248,-44.067020416259766|-19.923952004077837,-44.06287908554077', '0', 'S', '0'),
(00000000038, 0, '354188030037127', 'cerca', '27/01/2015 16:19:58', '28/01/2015 21:43:35', '-19.943176026993477,-44.07289981842041|-19.913017676082422,-44.041550159454346|-19.936116059526437,-44.019126892089844|-19.961772487279656,-44.01123046875', '0', 'S', '0'),
(00000000045, 0, '359710044171255', 'TRABALHO', '09/02/2015 23:13:49', '09/02/2015 23:15:23', '-19.88523462805237,-43.96402359008789|-19.883458929823895,-43.96908760070801|-19.88652603244354,-43.976383209228516|-19.896211229730294,-43.987369537353516|-19.9010536062052,-43.997840881347656|-19.899116673391834,-44.04517650604248|-19.91138351415554,-44.09045219421387|-19.94366012748551,-44.05985355377197|-19.942369189542,-44.034082889556885|-19.928168175757264,-44.01105880737305|-19.912392258080015,-43.98942947387695|-19.900327259177935,-43.978614807128906', '0', 'S', '0'),
(00000000046, 0, '800322', 'teste', '11/02/2015 12:45:35', '11/02/2015 12:54:17', '-23.531963061475846,-46.38734579086304|-23.523503314342346,-46.36953592300415|-23.525274004091123,-46.36447191238403|-23.53408774971946,-46.353442668914795|-23.53884850063718,-46.36322736740112|-23.53542549878746,-46.374385356903076', '0', 'S', '0');

-- --------------------------------------------------------

--
-- Estrutura da tabela `gprmc`
--

CREATE TABLE IF NOT EXISTS `gprmc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `imei` varchar(25) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `satelliteFixStatus` char(1) NOT NULL,
  `latitudeDecimalDegrees` varchar(12) NOT NULL,
  `latitudeHemisphere` char(1) NOT NULL,
  `longitudeDecimalDegrees` varchar(12) NOT NULL,
  `longitudeHemisphere` char(1) NOT NULL,
  `speed` float NOT NULL,
  `gpsSignalIndicator` char(1) NOT NULL,
  `infotext` text,
  `address` text,
  `km_rodado` float(12,5) NOT NULL DEFAULT '0.00000',
  `converte` int(11) DEFAULT '0',
  `ligado` char(1) NOT NULL DEFAULT 'N',
  `voltagem_bateria` char(1) DEFAULT NULL,
  `carregamento` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imei` (`imei`),
  KEY `signalIndicator` (`gpsSignalIndicator`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1342190 ;

--
-- Extraindo dados da tabela `gprmc`
--

INSERT INTO `gprmc` (`id`, `date`, `imei`, `phone`, `satelliteFixStatus`, `latitudeDecimalDegrees`, `latitudeHemisphere`, `longitudeDecimalDegrees`, `longitudeHemisphere`, `speed`, `gpsSignalIndicator`, `infotext`, `address`, `km_rodado`, `converte`, `ligado`, `voltagem_bateria`, `carregamento`) VALUES
(1342186, '2015-03-07 20:12:52', '355879053548531', '', 'A', '1657.8842', 'S', '05148.8672', 'W', 0, 'F', 'gprmc', 'Rua Antônio Granja, 663-769 - Setor Nova Caiaponia, Caiapônia - GO, 75850-000, Brazil', 0.00000, 1, 'N', NULL, NULL),
(1342187, '2015-03-07 20:12:52', '355879053548531', '', 'A', '1657.8842', 'S', '05148.8672', 'W', 0, 'F', 'gprmc', 'Rua Antônio Granja, 663-769 - Setor Nova Caiaponia, Caiapônia - GO, 75850-000, Brazil', 0.00000, 1, 'N', NULL, NULL),
(1342188, '2015-03-07 20:13:02', '355879053548531', '', 'A', '1657.8842', 'S', '05148.8672', 'W', 0, 'F', 'gprmc', 'Rua Antônio Granja, 663-769 - Setor Nova Caiaponia, Caiapônia - GO, 75850-000, Brazil', 0.00000, 1, 'N', NULL, NULL),
(1342189, '2015-03-07 20:13:22', '355879053548531', '', 'A', '1657.8842', 'S', '05148.8672', 'W', 0, 'F', 'gprmc', 'Rua Antônio Granja, 663-769 - Setor Nova Caiaponia, Caiapônia - GO, 75850-000, Brazil', 0.00000, 1, 'N', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupo`
--

CREATE TABLE IF NOT EXISTS `grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `cliente` int(11) NOT NULL,
  `grupo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_nome` (`nome`),
  KEY `nome` (`nome`),
  KEY `cliente` (`cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Guarda os grupos dos clientes' AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `grupo`
--

INSERT INTO `grupo` (`id`, `nome`, `senha`, `cliente`, `grupo`) VALUES
(3, 'teste', '698dc19d489c4e4db73e28a713eab07b', 413, 0),
(5, 'Teste1', 'e959088c6049f1104c84c9bde5560a13', 418, 0),
(6, 'NILSON', '81dc9bdb52d04dc20036dbd8313ed055', 418, 0),
(8, 'Teste 2', 'd41d8cd98f00b204e9800998ecf8427e', 418, 0),
(9, 'teste alex', '698dc19d489c4e4db73e28a713eab07b', 418, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupo_bem`
--

CREATE TABLE IF NOT EXISTS `grupo_bem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bem` int(11) NOT NULL,
  `cliente` int(11) NOT NULL,
  `imei` varchar(20) NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `grupo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_bem` (`bem`,`grupo`),
  KEY `bem` (`bem`),
  KEY `cliente` (`cliente`),
  KEY `imei` (`imei`),
  KEY `grupo` (`grupo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Guarda os bens dos grupos' AUTO_INCREMENT=46 ;

--
-- Extraindo dados da tabela `grupo_bem`
--

INSERT INTO `grupo_bem` (`id`, `bem`, `cliente`, `imei`, `descricao`, `grupo`) VALUES
(1, 101, 413, '359710041358145', 'teste', 3),
(36, 106, 418, '400637', 'SUNTECK', 8),
(37, 110, 418, '359710040860042', 'GOL NILSON', 8),
(38, 111, 418, '113456', 'SÃ©rgio Magina', 8),
(39, 104, 418, '860058010450044', 'BRUNO', 9),
(41, 105, 418, '359710041548174', 'ORE2615', 5),
(43, 105, 418, '359710041548174', 'ORE2615', 6),
(44, 110, 418, '359710040860042', 'HCL2969', 6),
(45, 114, 418, '359710044171255', 'PUNTO', 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `loc_atual`
--

CREATE TABLE IF NOT EXISTS `loc_atual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `imei` varchar(25) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `satelliteFixStatus` char(1) NOT NULL,
  `latitudeDecimalDegrees` varchar(12) NOT NULL,
  `latitudeHemisphere` char(1) NOT NULL,
  `longitudeDecimalDegrees` varchar(12) NOT NULL,
  `longitudeHemisphere` char(1) NOT NULL,
  `coordenada_antiga` varchar(25) DEFAULT '0|0' COMMENT 'Guarda a latitude e longitude anterior a posiÃ§Ã£o atual',
  `speed` float NOT NULL,
  `gpsSignalIndicator` char(1) NOT NULL,
  `infotext` text,
  `address` text,
  `converte` int(11) DEFAULT '0',
  `ligado` char(1) DEFAULT 'N',
  `voltagem_bateria` char(1) DEFAULT NULL,
  `carregamento` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imei` (`imei`),
  KEY `signalIndicator` (`gpsSignalIndicator`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=371 ;

--
-- Extraindo dados da tabela `loc_atual`
--

INSERT INTO `loc_atual` (`id`, `date`, `imei`, `phone`, `satelliteFixStatus`, `latitudeDecimalDegrees`, `latitudeHemisphere`, `longitudeDecimalDegrees`, `longitudeHemisphere`, `coordenada_antiga`, `speed`, `gpsSignalIndicator`, `infotext`, `address`, `converte`, `ligado`, `voltagem_bateria`, `carregamento`) VALUES
(79, '2014-12-01 19:43:15', '58010450044', '000', '2', '17', '0', '01', 'C', '0|0', 154306, '0', 'S1953.3833', NULL, 1, 'N', NULL, NULL),
(81, '2014-11-29 00:32:14', '0040860042;imei:359710040', '', 'A', '1955.8078', 'S', '04404.9347', 'W', '0|0', 0, 'F', 'tracker', NULL, 1, 'N', NULL, NULL),
(369, '2015-02-11 01:14:47', '0044171255;imei:359710044', '', 'A', '1955.0324', 'S', '04403.2833', 'W', '0|0', 33.74, 'F', 'tracker', NULL, 1, 'N', NULL, NULL),
(370, '2015-03-07 20:13:20', '355879053548531', '', 'A', '1657.8842', 'S', '05148.8672', 'W', '0|0', 0, 'F', 'gprmc', NULL, 1, 'N', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `imei` varchar(17) NOT NULL,
  `message` varchar(30) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `viewed` char(1) NOT NULL DEFAULT 'N',
  `viewed_adm` char(1) DEFAULT 'N',
  PRIMARY KEY (`imei`,`message`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Guarda os alertas enviados pelo gps';

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamento`
--

CREATE TABLE IF NOT EXISTS `pagamento` (
  `cliente` int(10) unsigned NOT NULL,
  `ano` int(4) unsigned NOT NULL,
  `jane` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `feve` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `marc` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `abri` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `maio` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `junh` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `julh` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `agos` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `sete` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `outu` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `nove` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  `deze` char(1) NOT NULL DEFAULT 'F' COMMENT 'F=falta informar; N=Nao pagou;S=pagou',
  PRIMARY KEY (`cliente`,`ano`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `preferencias`
--

CREATE TABLE IF NOT EXISTS `preferencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `valor` varchar(255) DEFAULT NULL,
  `smtp_host` varchar(45) DEFAULT NULL,
  `smtp_auten` varchar(45) DEFAULT NULL,
  `smtp_user` varchar(45) DEFAULT NULL,
  `smtp_passwd` varchar(45) DEFAULT NULL,
  `estilo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `preferencias`
--

INSERT INTO `preferencias` (`id`, `nome`, `valor`, `smtp_host`, `smtp_auten`, `smtp_user`, `smtp_passwd`, `estilo`) VALUES
(1, '', '', 'smtp.gmail.com', 'S', 'seuemail@gmail.com', 'mudar123456', 'bootstrap1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `rastreadores`
--

CREATE TABLE IF NOT EXISTS `rastreadores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rastreador` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Extraindo dados da tabela `rastreadores`
--

INSERT INTO `rastreadores` (`id`, `rastreador`) VALUES
(15, 'TK102'),
(16, 'TK103'),
(17, 'TK104'),
(18, 'TK303'),
(19, 'CRX1'),
(20, 'GT06'),
(21, 'GT06N'),
(22, 'H02'),
(23, 'H08'),
(24, 'SUNTECH'),
(25, 'XT009'),
(26, 'TK06A');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
