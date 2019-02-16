-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: pulse
-- ------------------------------------------------------
-- Server version	5.7.25-0ubuntu0.18.04.2

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
-- Table structure for table `account_credentials`
--

DROP TABLE IF EXISTS `account_credentials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_credentials` (
  `account_id` varchar(32) NOT NULL,
  `password` char(64) NOT NULL,
  `salt` char(40) NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store user ids and passwords';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_credentials`
--

LOCK TABLES `account_credentials` WRITE;
/*!40000 ALTER TABLE `account_credentials` DISABLE KEYS */;
INSERT INTO `account_credentials` (`account_id`, `password`, `salt`) VALUES ('credentials_tester','2c7f6ceba7568587e5a1255431b04017d852b44c09f073942b56c4de6a253f3d','bH:{!MZtlBh%Y3sV$,B+xaG2xP#{/h\'_aPK:s%\"u'),('login_service_tester','75989661211f01510511795799a5456d991991d7dd70b92286507d589a6a81b1','n7XZX\'T9xb.\\~F`]63NQ96-X`=kM<Gh\'p@,:{We#'),('pTest','c4dfdceb710452bda60e33a358ff9b3ede2224e1241debc0d42a5cc87ae9504f','6kE1%H5ja44Lna<0;tl)9*dxF9[79(RO:84sFV#C');
/*!40000 ALTER TABLE `account_credentials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `account_id` varchar(32) NOT NULL,
  `user_type` varchar(10) NOT NULL,
  PRIMARY KEY (`account_id`),
  KEY `users_user_types_id_fk` (`user_type`),
  CONSTRAINT `users_user_types_id_fk` FOREIGN KEY (`user_type`) REFERENCES `user_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store all user details';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`account_id`, `user_type`) VALUES ('pTest','patient'),('credentials_tester','tester'),('login_service_tester','tester'),('session_tester','tester');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `browser_agents`
--

DROP TABLE IF EXISTS `browser_agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `browser_agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `browser` text NOT NULL,
  `hash` char(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_agents_hash_uindex` (`hash`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COMMENT='Table to save user agents';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `browser_agents`
--

LOCK TABLES `browser_agents` WRITE;
/*!40000 ALTER TABLE `browser_agents` DISABLE KEYS */;
INSERT INTO `browser_agents` (`id`, `browser`, `hash`) VALUES (9,'UNKNOWN','25ba44ec3b391ba4ce5fbbd2979635e254775e7d'),(11,'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Mobile Safari/537.36','2d13425a6c604c6a91f6ffedec343a81e891b535'),(12,'PostmanRuntime/7.6.0','7c437b88e1cb7d09660f2b2ace2f865fb3fd6fb6'),(13,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Safari/537.36','064e005501a2a0d56363299e04b6b77a3ce5f7a5'),(14,'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Mobile Safari/537.36','1a9ef466e44b70dd24f39773687729521ea69bee'),(15,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Safari/537.36','13330a7d3aaee1569dd7ceebc04360c8b673fb08'),(16,'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Mobile Safari/537.36','0441391429577f903a877c9f7e215b8406705076'),(17,'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Mobile Safari/537.36','c9ca92194d513cd22f74a1cbc83dcb520058797e');
/*!40000 ALTER TABLE `browser_agents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `account_id` varchar(32) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `browser_agent` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `expires` datetime NOT NULL,
  `session_key` char(40) NOT NULL,
  PRIMARY KEY (`account_id`,`ip_address`,`browser_agent`),
  KEY `sessions_user_agents_id_fk` (`browser_agent`),
  CONSTRAINT `sessions_user_agents_id_fk` FOREIGN KEY (`browser_agent`) REFERENCES `browser_agents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store sessions of all users';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `ID` varchar(32) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Password` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
INSERT INTO `test` (`ID`, `LastName`, `FirstName`, `Age`, `Password`) VALUES ('170074','Chamantha','Anju',22,'anju'),('170081','Chandrasiri','Sunera',22,'sunera'),('170109','Udayanga	','Lahiru',22,'lahiru'),('pTest','Doe','John',101,'password');
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_types` (
  `id` varchar(10) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store User Types';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
INSERT INTO `user_types` (`id`, `description`) VALUES ('admin','Admin User'),('doctor','Doctor'),('med_center','Medical Center'),('patient','Patient'),('tester','Testing Agent');
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-16 20:48:25
