CREATE DATABASE  IF NOT EXISTS `rede_sensora_pedreira` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `rede_sensora_pedreira`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: rede_sensora_pedreira
-- ------------------------------------------------------
-- Server version	5.6.12-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alarmes_gerados`
--

DROP TABLE IF EXISTS `alarmes_gerados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarmes_gerados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt_hr_alarme` datetime NOT NULL COMMENT 'Data e hora da geração do alarme.',
  `in_alerta` char(1) NOT NULL COMMENT 'Flag para identificar se algum tipo de alerta foi emitido.',
  `id_log` int(11) NOT NULL COMMENT 'Identificador do log que gerou o alarme.',
  `id_condicoes_alarme` int(11) NOT NULL COMMENT 'Identificador único da condição do alarme.',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  PRIMARY KEY (`id`,`dt_hr_alarme`,`id_log`,`id_condicoes_alarme`),
  KEY `fk_alarmes_gerados_logs1` (`id_log`),
  KEY `fk_alarmes_gerados_condicoes_alarme1` (`id_condicoes_alarme`),
  CONSTRAINT `fk_alarmes_gerados_condicoes_alarme1` FOREIGN KEY (`id_condicoes_alarme`) REFERENCES `condicoes_alarme` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alarmes_gerados_logs1` FOREIGN KEY (`id_log`) REFERENCES `logs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alarmes_gerados`
--

LOCK TABLES `alarmes_gerados` WRITE;
/*!40000 ALTER TABLE `alarmes_gerados` DISABLE KEYS */;
/*!40000 ALTER TABLE `alarmes_gerados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cidades`
--

DROP TABLE IF EXISTS `cidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único',
  `nm_cidade` varchar(256) NOT NULL COMMENT 'Nome da cidade',
  `sg_estado` varchar(2) NOT NULL COMMENT 'Sigla do estado ao qual pertence',
  `in_ativo` char(1) NOT NULL DEFAULT 'S' COMMENT 'alert-success',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data de inclusao do registro no sistema',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'IP da máquina responsável pela transação.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cidades`
--

LOCK TABLES `cidades` WRITE;
/*!40000 ALTER TABLE `cidades` DISABLE KEYS */;
INSERT INTO `cidades` VALUES (4,'Pedreira','SP','S','2014-03-21','127.0.0.1');
/*!40000 ALTER TABLE `cidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coleta_evento`
--

DROP TABLE IF EXISTS `coleta_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coleta_evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nm_evento` varchar(256) NOT NULL COMMENT 'Nome do evento',
  `ds_evento` varchar(256) DEFAULT NULL COMMENT 'Descrição da coleta por evento',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coleta_evento`
--

LOCK TABLES `coleta_evento` WRITE;
/*!40000 ALTER TABLE `coleta_evento` DISABLE KEYS */;
/*!40000 ALTER TABLE `coleta_evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coleta_tempo`
--

DROP TABLE IF EXISTS `coleta_tempo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coleta_tempo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `in_coleta` int(11) NOT NULL COMMENT 'Intervalo para coleta em segundos',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coleta_tempo`
--

LOCK TABLES `coleta_tempo` WRITE;
/*!40000 ALTER TABLE `coleta_tempo` DISABLE KEYS */;
/*!40000 ALTER TABLE `coleta_tempo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condicao`
--

DROP TABLE IF EXISTS `condicao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `condicao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operador` varchar(2) NOT NULL COMMENT 'O operador lógico utilizado para comparação (>, <, =, >=, <=, !=).',
  `limiar` varchar(5) NOT NULL COMMENT 'Valor de limite.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'IP da máquina responsável pela inserção no sistema\n',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data de inserção do registro no sistema',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condicao`
--

LOCK TABLES `condicao` WRITE;
/*!40000 ALTER TABLE `condicao` DISABLE KEYS */;
/*!40000 ALTER TABLE `condicao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condicoes_politica`
--

DROP TABLE IF EXISTS `condicoes_politica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `condicoes_politica` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único\n',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'IP da máquina responsável pela alteração',
  `dt_inclusao` date DEFAULT NULL COMMENT 'data de inclusao do registro no sistema',
  `id_condicao` int(11) NOT NULL COMMENT 'Identificador único da condição\n',
  `id_politica` int(11) NOT NULL COMMENT 'Identificador único da política',
  PRIMARY KEY (`id`,`id_condicao`,`id_politica`),
  KEY `fk_condicoes_politica_condicao1_idx` (`id_condicao`),
  KEY `fk_condicoes_politica_politica1_idx` (`id_politica`),
  CONSTRAINT `fk_condicoes_politica_condicao1` FOREIGN KEY (`id_condicao`) REFERENCES `condicao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_condicoes_politica_politica1` FOREIGN KEY (`id_politica`) REFERENCES `politica` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condicoes_politica`
--

LOCK TABLES `condicoes_politica` WRITE;
/*!40000 ALTER TABLE `condicoes_politica` DISABLE KEYS */;
/*!40000 ALTER TABLE `condicoes_politica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gateways`
--

DROP TABLE IF EXISTS `gateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do gateway.',
  `nm_gateway` varchar(256) DEFAULT NULL COMMENT 'Nome opcional para identificar o gateway',
  `end_ip` varchar(45) NOT NULL COMMENT 'Endereço IP do gateway.',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data de inclusão do registro no sistema',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'ip da máquina responsável pela alteração.',
  `id_secao` int(11) NOT NULL COMMENT 'Identificador único da seção.',
  `login` varchar(256) DEFAULT NULL COMMENT 'Login para autenticação HTTP. Alguns dispositivos utilizam esse tipo de controle de acesso.',
  `senha` varchar(256) DEFAULT NULL,
  `opt` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`,`id_secao`),
  KEY `fk_gateways_secoes1` (`id_secao`),
  CONSTRAINT `fk_gateways_secoes1` FOREIGN KEY (`id_secao`) REFERENCES `secoes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gateways`
--

LOCK TABLES `gateways` WRITE;
/*!40000 ALTER TABLE `gateways` DISABLE KEYS */;
INSERT INTO `gateways` VALUES (22,'Gateway ZigBee','192.168.15.149','2014-03-21','127.0.0.1',27,'','',NULL);
/*!40000 ALTER TABLE `gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do grupo.',
  `nm_grupo` varchar(256) NOT NULL COMMENT 'Nome do grupo.',
  `in_ativo` char(1) NOT NULL DEFAULT 'S' COMMENT 'Identificador de status',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
INSERT INTO `grupos` VALUES (3,'Administradores','S','127.0.0.1','2014-03-21');
/*!40000 ALTER TABLE `grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locais`
--

DROP TABLE IF EXISTS `locais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vl_latitude` varchar(256) DEFAULT NULL COMMENT 'Latitude da localização (torre, escola, hospital, etc).Pode ser utilizado para aplicação com maps.',
  `vl_longitude` varchar(256) DEFAULT NULL COMMENT 'Longitude da localização (torre, escola, hospital, etc). Pode ser utilizado para aplicação com maps.',
  `ds_local` varchar(512) DEFAULT NULL,
  `nm_local` varchar(256) NOT NULL COMMENT 'Nome de identificação do local.',
  `endereco` varchar(512) NOT NULL COMMENT 'Endereço da localização.',
  `cep` varchar(256) DEFAULT NULL COMMENT 'Cep da localização.',
  `in_ativo` char(1) NOT NULL DEFAULT 'S' COMMENT 'Status do local',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'ip da máquina responsável pela transação',
  `id_cidade` int(11) NOT NULL COMMENT 'Identificador único da cidade.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_locais_cidades1` (`id_cidade`),
  CONSTRAINT `fk_locais_cidades1` FOREIGN KEY (`id_cidade`) REFERENCES `cidades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locais`
--

LOCK TABLES `locais` WRITE;
/*!40000 ALTER TABLE `locais` DISABLE KEYS */;
INSERT INTO `locais` VALUES (28,'-22.746907210468656','-46.90188869833946','Endereco da prefeitura','Um local de teste','Rua Adriano Corsi, São Paulo, Brasil',NULL,'S','2014-03-21','127.0.0.1',4),(29,'-22.746907210468656','-46.90188869833946','Endereco da prefeitura','Um local de teste','Rua Adriano Corsi, São Paulo, Brasil',NULL,'S','2014-03-21','127.0.0.1',4);
/*!40000 ALTER TABLE `locais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vl_dado` varchar(256) NOT NULL COMMENT 'Valor do dado coletado.',
  `dt_hr_registro` datetime NOT NULL COMMENT 'data e hora de registro do log',
  `id_radio` int(11) NOT NULL COMMENT 'identificador único radios',
  `id_sensor` int(11) NOT NULL COMMENT 'identificador único sensores',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  PRIMARY KEY (`id`,`id_radio`,`id_sensor`),
  KEY `fk_dados_radio1` (`id_radio`),
  KEY `fk_dados_sensor1` (`id_sensor`),
  CONSTRAINT `fk_dados_radio1` FOREIGN KEY (`id_radio`) REFERENCES `radios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dados_sensor1` FOREIGN KEY (`id_sensor`) REFERENCES `sensores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (3,'21.0','2014-03-25 14:26:27',23,3,'2014-03-25','192.168.15.149'),(4,'21.0','2014-03-25 14:26:42',26,17,'2014-03-25','192.168.15.149'),(5,'21.0','2014-03-25 14:31:27',23,3,'2014-03-25','192.168.15.149'),(6,'22.0','2014-03-25 14:31:42',26,17,'2014-03-25','192.168.15.149'),(7,'22.0','2014-03-25 14:36:28',23,3,'2014-03-25','192.168.15.149'),(8,'21.0','2014-03-25 14:36:42',26,17,'2014-03-25','192.168.15.149'),(9,'21.0','2014-03-25 14:41:28',23,3,'2014-03-25','192.168.15.149'),(10,'21.0','2014-03-25 14:41:43',26,17,'2014-03-25','192.168.15.149'),(11,'21.0','2014-03-25 14:46:28',23,3,'2014-03-25','192.168.15.149'),(12,'21.0','2014-03-25 14:46:43',26,17,'2014-03-25','192.168.15.149'),(13,'21.0','2014-03-25 14:51:29',23,3,'2014-03-25','192.168.15.149'),(14,'21.0','2014-03-25 14:51:43',26,17,'2014-03-25','192.168.15.149'),(15,'21.0','2014-03-25 14:56:29',23,3,'2014-03-25','192.168.15.149'),(16,'21.0','2014-03-25 14:56:43',26,17,'2014-03-25','192.168.15.149'),(17,'21.0','2014-03-25 15:01:29',23,3,'2014-03-25','192.168.15.149'),(18,'21.0','2014-03-25 15:01:43',26,17,'2014-03-25','192.168.15.149'),(19,'22.0','2014-03-25 15:06:30',23,3,'2014-03-25','192.168.15.149'),(20,'22.0','2014-03-25 15:06:43',26,17,'2014-03-25','192.168.15.149'),(21,'22.0','2014-03-25 15:11:30',23,3,'2014-03-25','192.168.15.149'),(22,'22.0','2014-03-25 15:11:44',26,17,'2014-03-25','192.168.15.149'),(23,'21.0','2014-03-25 15:16:30',23,3,'2014-03-25','192.168.15.149'),(24,'20.0','2014-03-25 15:16:55',26,17,'2014-03-25','192.168.15.149'),(25,'21.0','2014-03-25 15:21:31',23,3,'2014-03-25','192.168.15.149'),(26,'22.0','2014-03-25 15:21:44',26,17,'2014-03-25','192.168.15.149'),(27,'21.0','2014-03-25 15:26:31',23,3,'2014-03-25','192.168.15.149'),(28,'22.0','2014-03-25 15:26:44',26,17,'2014-03-25','192.168.15.149'),(29,'22.0','2014-03-25 15:41:31',23,3,'2014-03-25','192.168.15.149'),(30,'22.0','2014-03-25 15:41:44',26,17,'2014-03-25','192.168.15.149'),(31,'22.0','2014-03-25 15:46:32',23,3,'2014-03-25','192.168.15.149'),(32,'22.0','2014-03-25 15:46:45',26,17,'2014-03-25','192.168.15.149'),(33,'22.0','2014-03-25 15:51:32',23,3,'2014-03-25','192.168.15.149'),(34,'22.0','2014-03-25 15:51:45',26,17,'2014-03-25','192.168.15.149'),(35,'22.0','2014-03-25 15:56:32',23,3,'2014-03-25','192.168.15.149'),(36,'23.0','2014-03-25 15:56:45',26,17,'2014-03-25','192.168.15.149'),(37,'22.0','2014-03-25 16:01:33',23,3,'2014-03-25','192.168.15.149'),(38,'22.0','2014-03-25 16:01:45',26,17,'2014-03-25','192.168.15.149');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs_alerta`
--

DROP TABLE IF EXISTS `logs_alerta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs_alerta` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único\n',
  `ds_log_alerta` varchar(256) NOT NULL COMMENT 'Mensagem de identificação do log. Pode ser, falha de comunicação com o gateway x, ou retomada de comunicação com o gateway Y.\n\n',
  `dt_hr_registro` datetime NOT NULL COMMENT 'Data e hora do registro no sistema.',
  `cd_alerta` varchar(45) DEFAULT NULL COMMENT 'Alguma espécie de código para identificar o alerta.\n',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável por realizar a alteração',
  `id_gateway` int(11) NOT NULL COMMENT 'Identificador único do gateway ao qual está vinculado.',
  PRIMARY KEY (`id`,`id_gateway`),
  KEY `fk_logs_alerta_gateways1_idx` (`id_gateway`),
  CONSTRAINT `fk_logs_alerta_gateways1` FOREIGN KEY (`id_gateway`) REFERENCES `gateways` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs_alerta`
--

LOCK TABLES `logs_alerta` WRITE;
/*!40000 ALTER TABLE `logs_alerta` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs_alerta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs_utilizacao`
--

DROP TABLE IF EXISTS `logs_utilizacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs_utilizacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único.',
  `dt_entrada` datetime NOT NULL COMMENT 'Data de log no sistema',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data de inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela alteração.',
  `pagina` varchar(256) NOT NULL DEFAULT 'index' COMMENT 'Página que o usuário acessou.',
  `id_usuario` int(11) NOT NULL COMMENT 'Identificador único do usuário',
  `operacao` varchar(45) NOT NULL DEFAULT 'entrada',
  PRIMARY KEY (`id`,`id_usuario`),
  KEY `fk_logs_utilizacao_usuarios1` (`id_usuario`),
  CONSTRAINT `fk_logs_utilizacao_usuarios1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs_utilizacao`
--

LOCK TABLES `logs_utilizacao` WRITE;
/*!40000 ALTER TABLE `logs_utilizacao` DISABLE KEYS */;
INSERT INTO `logs_utilizacao` VALUES (181,'2014-03-21 16:44:16','2014-03-21','127.0.0.1','login',6,'entrada'),(182,'2014-03-21 17:44:11','2014-03-21','127.0.0.1','login',6,'saida'),(185,'2014-03-25 14:18:10','2014-03-25','127.0.0.1','login',6,'entrada'),(186,'2014-03-25 14:22:23','2014-03-25','127.0.0.1','login',6,'saida'),(187,'2014-03-25 14:24:58','2014-03-25','127.0.0.1','login',6,'entrada'),(188,'2014-03-25 15:36:13','2014-03-25','127.0.0.1','login',6,'entrada'),(190,'2014-03-25 15:55:43','2014-03-25','127.0.0.1','login',6,'entrada');
/*!40000 ALTER TABLE `logs_utilizacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `politica`
--

DROP TABLE IF EXISTS `politica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `politica` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único no banco de dados da política\n',
  `nome` varchar(45) NOT NULL COMMENT 'Nome que será utilizado no arquivo XML',
  `identificador` varchar(45) NOT NULL COMMENT 'Uma string única para identificar a política\n',
  `tipo` varchar(45) NOT NULL COMMENT 'O tipo da política. Valores default serão alarme e coletaDados',
  `politicaLocal` varchar(2) NOT NULL COMMENT 'Indica se a política deve ser avaliada no servidor ou no gateway.\n\nS = Servidor\nN = Gateway',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Endereço IP da máquina responsável pela inserção do registro\n',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data de inclusão do registro no sistema',
  `id_radio` int(11) NOT NULL COMMENT 'Identificador do dispositivo ao qual a política está vinculada\n',
  `acao` varchar(100) DEFAULT NULL COMMENT 'Tipo de acao que deve ser realizada.\n\nAte o momento são alarme e email.',
  PRIMARY KEY (`id`,`id_radio`),
  KEY `fk_politica_radios1_idx` (`id_radio`),
  CONSTRAINT `fk_politica_radios1` FOREIGN KEY (`id_radio`) REFERENCES `radios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `politica`
--

LOCK TABLES `politica` WRITE;
/*!40000 ALTER TABLE `politica` DISABLE KEYS */;
/*!40000 ALTER TABLE `politica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `politica_gateway`
--

DROP TABLE IF EXISTS `politica_gateway`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `politica_gateway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'IP da máquina responsável pela inserção do registro.',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data de inclusão do registro no sistema\n',
  `id_gateway` int(11) NOT NULL COMMENT 'Identificador único do gateway',
  `id_politica` int(11) NOT NULL COMMENT 'Identificador único da política',
  PRIMARY KEY (`id`,`id_politica`),
  KEY `fk_politica_gateway_gateways1_idx` (`id_gateway`),
  KEY `fk_politica_gateway_politica1_idx` (`id_politica`),
  CONSTRAINT `fk_politica_gateway_gateways1` FOREIGN KEY (`id_gateway`) REFERENCES `gateways` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_politica_gateway_politica1` FOREIGN KEY (`id_politica`) REFERENCES `politica` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `politica_gateway`
--

LOCK TABLES `politica_gateway` WRITE;
/*!40000 ALTER TABLE `politica_gateway` DISABLE KEYS */;
/*!40000 ALTER TABLE `politica_gateway` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radio_endpoint`
--

DROP TABLE IF EXISTS `radio_endpoint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radio_endpoint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nr_endpoint` int(11) NOT NULL COMMENT 'Um dos 240 endereços de endpoint possíveis',
  `nm_aplicacao` varchar(256) DEFAULT NULL COMMENT 'Nome ',
  `id_radio` int(11) NOT NULL COMMENT 'Identificador único do rádio',
  `in_ativo` char(1) NOT NULL DEFAULT 'S' COMMENT 'Identificador de status',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  PRIMARY KEY (`id`,`id_radio`),
  KEY `fk_radio_endpoint_radio1` (`id_radio`),
  CONSTRAINT `fk_radio_endpoint_radio1` FOREIGN KEY (`id_radio`) REFERENCES `radios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radio_endpoint`
--

LOCK TABLES `radio_endpoint` WRITE;
/*!40000 ALTER TABLE `radio_endpoint` DISABLE KEYS */;
/*!40000 ALTER TABLE `radio_endpoint` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radio_sensores`
--

DROP TABLE IF EXISTS `radio_sensores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radio_sensores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_radio` int(11) NOT NULL COMMENT 'Identificador único radios',
  `id_sensor` int(11) NOT NULL COMMENT 'identificador único sensores',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  PRIMARY KEY (`id`,`id_radio`,`id_sensor`),
  KEY `fk_radio_sensores_radio1` (`id_radio`),
  KEY `fk_radio_sensores_sensor1` (`id_sensor`),
  CONSTRAINT `fk_radio_sensores_radio1` FOREIGN KEY (`id_radio`) REFERENCES `radios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_radio_sensores_sensor1` FOREIGN KEY (`id_sensor`) REFERENCES `sensores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radio_sensores`
--

LOCK TABLES `radio_sensores` WRITE;
/*!40000 ALTER TABLE `radio_sensores` DISABLE KEYS */;
INSERT INTO `radio_sensores` VALUES (1,23,3,'127.0.0.1','2014-03-25'),(2,26,17,'127.0.0.1','2014-03-25');
/*!40000 ALTER TABLE `radio_sensores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radios`
--

DROP TABLE IF EXISTS `radios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pan_id` varchar(256) NOT NULL COMMENT 'Endereço da rede a qual o rádio está vinculado.',
  `nm_radio` varchar(256) NOT NULL COMMENT 'String identificadora do rádio. Nome amigável',
  `ds_funcao` varchar(1024) NOT NULL COMMENT 'Descritor da função do nó: roteador, coordenador ou enddevice',
  `net_id` varchar(256) NOT NULL COMMENT 'Endereço de 16bits único na rede.',
  `high_id` varchar(256) NOT NULL COMMENT 'Endereço global único do nó (MAC). High Address.',
  `dest_addr` varchar(45) DEFAULT NULL COMMENT 'Endereço do rádio de destino do nó, ou seja, para quem ele está enviando dados. Caso o valor seja 00:00:00:00:00:00:00:00 o destino é o coordenador.',
  `in_ativo` char(1) NOT NULL DEFAULT 'S' COMMENT 'Status do rádio.',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  `id_coleta_tempo` int(11) DEFAULT NULL COMMENT 'Identificador único da coleta por tempo',
  `id_coleta_evento` int(11) DEFAULT NULL COMMENT 'Identificador único da coleta por evento',
  PRIMARY KEY (`id`),
  KEY `fk_radios_coleta_tempo1` (`id_coleta_tempo`),
  KEY `fk_radios_coleta_evento1` (`id_coleta_evento`),
  CONSTRAINT `fk_radios_coleta_evento1` FOREIGN KEY (`id_coleta_evento`) REFERENCES `coleta_evento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_radios_coleta_tempo1` FOREIGN KEY (`id_coleta_tempo`) REFERENCES `coleta_tempo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radios`
--

LOCK TABLES `radios` WRITE;
/*!40000 ALTER TABLE `radios` DISABLE KEYS */;
INSERT INTO `radios` VALUES (1,'0xc634','Gateway Zigbee','Coordenador','0x0','00:13:a2:00:40:64:8c:42!',NULL,'s','2014-03-25','127.0.0.1',NULL,NULL),(23,'0xc634','Router 01','Roteador','0xcb0d','00:13:a2:00:40:ac:6c:3b!',NULL,'s','2014-03-25','127.0.0.1',NULL,NULL),(26,'0xc634','Router 02','Roteador','0x2e3','00:13:a2:00:40:ac:6a:af!',NULL,'s','2014-03-25','127.0.0.1',NULL,NULL);
/*!40000 ALTER TABLE `radios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radios_secao`
--

DROP TABLE IF EXISTS `radios_secao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radios_secao` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do rádio',
  `id_secao` int(11) NOT NULL COMMENT 'Identificador único da seção.',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  `id_radio` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_radio`),
  KEY `fk_radios_secao_secao1` (`id_secao`),
  KEY `fk_radios_local_radio1` (`id`),
  KEY `fk_radios_secao_radios1` (`id_radio`),
  CONSTRAINT `fk_radios_secao_radios1` FOREIGN KEY (`id_radio`) REFERENCES `radios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_radios_secao_secao1` FOREIGN KEY (`id_secao`) REFERENCES `secoes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radios_secao`
--

LOCK TABLES `radios_secao` WRITE;
/*!40000 ALTER TABLE `radios_secao` DISABLE KEYS */;
INSERT INTO `radios_secao` VALUES (1,27,'2014-03-25','127.0.0.1',1),(2,27,'2014-03-25','127.0.0.1',26),(3,27,'2014-03-25','127.0.0.1',23);
/*!40000 ALTER TABLE `radios_secao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secoes`
--

DROP TABLE IF EXISTS `secoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nm_secao` varchar(256) NOT NULL COMMENT 'Descrição da secao onde há sensores instalados.',
  `ds_secao` varchar(1024) DEFAULT NULL COMMENT 'Descrição opcional da seção, como espaço físico, pontos de referência, etc.',
  `qtd_sensores` int(11) NOT NULL DEFAULT '0' COMMENT 'Contador da quantidade de sensores na seção.',
  `in_ativo` char(1) NOT NULL DEFAULT 'S' COMMENT 'alert-success',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  `tp_secao` varchar(256) DEFAULT 'Sala',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secoes`
--

LOCK TABLES `secoes` WRITE;
/*!40000 ALTER TABLE `secoes` DISABLE KEYS */;
INSERT INTO `secoes` VALUES (27,'Seção genérica','Descrição genérica',3,'S','2014-03-25','127.0.0.1','Sala');
/*!40000 ALTER TABLE `secoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secoes_local`
--

DROP TABLE IF EXISTS `secoes_local`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secoes_local` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_secao` int(11) NOT NULL COMMENT 'Identificador único da seção.',
  `id_local` int(11) NOT NULL COMMENT 'Identificador único do local.',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  PRIMARY KEY (`id`,`id_secao`,`id_local`),
  KEY `fk_secao_local_secao1` (`id_secao`),
  KEY `fk_secao_local_local1` (`id_local`),
  CONSTRAINT `fk_secao_local_local1` FOREIGN KEY (`id_local`) REFERENCES `locais` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_secao_local_secao1` FOREIGN KEY (`id_secao`) REFERENCES `secoes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secoes_local`
--

LOCK TABLES `secoes_local` WRITE;
/*!40000 ALTER TABLE `secoes_local` DISABLE KEYS */;
INSERT INTO `secoes_local` VALUES (23,27,29,'2014-03-21','127.0.0.1');
/*!40000 ALTER TABLE `secoes_local` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sensores`
--

DROP TABLE IF EXISTS `sensores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sensores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nm_sensor` varchar(256) NOT NULL COMMENT 'Nome do sensor.	',
  `ds_sensor` varchar(1024) NOT NULL COMMENT 'Descrição do sensor.',
  `fl_unidade_medida` varchar(3) NOT NULL COMMENT 'Valor real da unidade de medida do sensor.',
  `nm_unidade_medida` varchar(45) NOT NULL COMMENT 'Nome da unidade de medida.',
  `in_ativo` char(1) NOT NULL DEFAULT 'S' COMMENT 'Identificador de status',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  `get_url` varchar(256) DEFAULT NULL COMMENT 'URL para onde devem ser enviadas as informações de log.',
  `in_webservice` varchar(2) DEFAULT 'S',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COMMENT='	';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sensores`
--

LOCK TABLES `sensores` WRITE;
/*!40000 ALTER TABLE `sensores` DISABLE KEYS */;
INSERT INTO `sensores` VALUES (3,'Temperatura','Sensor para coleta de temperatura','C','Celsius','S','2014-03-25','127.0.0.1','http://(server)/logs/gravarlog/idradio/2/idsensor/1/valor/','S'),(17,'Temperatura','Sensor para coleta de informações de temperatura','C','Celsius','S','2014-03-25','127.0.0.1','http://(server)/logs/gravarlog/idradio/3/idsensor/2/valor/','S');
/*!40000 ALTER TABLE `sensores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_grupos`
--

DROP TABLE IF EXISTS `usuario_grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_grupos`
--

LOCK TABLES `usuario_grupos` WRITE;
/*!40000 ALTER TABLE `usuario_grupos` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(256) NOT NULL COMMENT 'Login de acesso ao sistema',
  `senha` varchar(256) NOT NULL COMMENT 'Senha de acesso ao sistema',
  `nm_usuario` varchar(512) NOT NULL COMMENT 'Nome da pessoa física.',
  `ds_email` varchar(256) NOT NULL COMMENT 'Endereço de email do usuário.',
  `in_genero` char(1) NOT NULL COMMENT 'Gênero do usuário',
  `in_ativo` char(1) NOT NULL DEFAULT 'S' COMMENT 'Identificador de status',
  `ds_telefone` varchar(100) DEFAULT NULL COMMENT 'Telefone do usuário',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  `id_grupo` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_grupo`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_usuarios_grupos1_idx` (`id_grupo`),
  CONSTRAINT `fk_usuarios_grupos1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (6,'email@mail.com','e10adc3949ba59abbe56e057f20f883e','Rodrigo Morbach','email@gmail.com','M','S','92399960','2014-03-21','127.0.0.1',3);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios_local`
--

DROP TABLE IF EXISTS `usuarios_local`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios_local` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único.',
  `id_usuario` int(11) NOT NULL COMMENT 'Identificador único do usuário.',
  `id_local` int(11) NOT NULL COMMENT 'Identificador único do local.',
  `dt_inclusao` date DEFAULT NULL COMMENT 'Data da inclusão do registro no sistema.',
  `nr_ip_terminal` varchar(45) DEFAULT NULL COMMENT 'Ip da máquina responsável pela transação.',
  PRIMARY KEY (`id`,`id_usuario`,`id_local`),
  KEY `fk_usuarios_local_usuario1` (`id_usuario`),
  KEY `fk_usuarios_local_local1` (`id_local`),
  CONSTRAINT `fk_usuarios_local_local1` FOREIGN KEY (`id_local`) REFERENCES `locais` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_usuarios_local_usuario1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_local`
--

LOCK TABLES `usuarios_local` WRITE;
/*!40000 ALTER TABLE `usuarios_local` DISABLE KEYS */;
INSERT INTO `usuarios_local` VALUES (5,6,29,'2014-03-21','127.0.0.1');
/*!40000 ALTER TABLE `usuarios_local` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'rede_sensora_pedreira'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-25 16:02:53
