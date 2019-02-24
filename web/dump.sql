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
  PRIMARY KEY (`account_id`),
  CONSTRAINT `account_credentials_accounts_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store user ids and passwords';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_credentials`
--

LOCK TABLES `account_credentials` WRITE;
/*!40000 ALTER TABLE `account_credentials` DISABLE KEYS */;
INSERT INTO `account_credentials` (`account_id`, `password`, `salt`) VALUES ('652566699V','d3ff17724caf43e4ce0c65cf9b0b1f08ff117e62f36963c75f902f320e6b8b9c','g~`jBa}7Ji~L.4`t%P+%q=v07#IHypf,7U.0HT5H'),('administrator','c5e5453bb50d0673615b206fabd904fa6c47a5881857b83512b0cfa74fdc402c','.3.$)8Gj]aB<AS!#*)T4\'}wwTu,i.#ETF;17QB&h'),('credentials_tester','f6be14397ba3cbd16ccaabdc5f7f8d5599a97a9a9e8872ef180fb953caa0ea1f','JGBYBU;vB+KqKs}7PRR8aCgkj1I||x0Hgg:3/Uy&'),('login_service_tester','61ebf2d0bbf3aea542e24346763ae70683a9013585560ef69ad9f6ce27a6c382','1HARW\'LL{m,q*.$+jABr:B]6UA{D\\co+vC*3;L{v'),('medCenter','9bfc39e1f4a394156c274082a6682096f8b115d123548fa8a32c209994efab6b','a^UoD[;Pb;5|q(bsZz\"jludK}@=JvQ*:M]w\"9p&S'),('medCenter2','055f20a6f3310aa925987c53f14b1db168ba59b7b1cfb7e4b4259ba99941507d','RykV$%2Y]0:fm6U6VjN#6!6J[`H\\o9VgIm/:I~^4'),('medical_center_tester','72faadc045023b6dc3ddd1e2cd9e71b98fb3fc4add360830ebe638fea08eed5d',']07D_e~6_EMS+U8Cz(/3#<(M3)\'p87cu;qV^qU(T'),('pTest','c4dfdceb710452bda60e33a358ff9b3ede2224e1241debc0d42a5cc87ae9504f','6kE1%H5ja44Lna<0;tl)9*dxF9[79(RO:84sFV#C');
/*!40000 ALTER TABLE `account_credentials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_types`
--

DROP TABLE IF EXISTS `account_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_types` (
  `id` varchar(10) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store User Types';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_types`
--

LOCK TABLES `account_types` WRITE;
/*!40000 ALTER TABLE `account_types` DISABLE KEYS */;
INSERT INTO `account_types` (`id`, `description`) VALUES ('admin','Admin User'),('doctor','Doctor'),('med_center','Medical Center'),('patient','Patient'),('tester','Testing Agent');
/*!40000 ALTER TABLE `account_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `account_id` varchar(32) NOT NULL,
  `account_type` varchar(10) NOT NULL,
  PRIMARY KEY (`account_id`),
  KEY `users_user_types_id_fk` (`account_type`),
  CONSTRAINT `users_user_types_id_fk` FOREIGN KEY (`account_type`) REFERENCES `account_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store all user details';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`account_id`, `account_type`) VALUES ('administrator','admin'),('652566699V','doctor'),('medCenter','med_center'),('medCenter2','med_center'),('medical_center_tester','med_center'),('non_exisiting_user','med_center'),('credentials_tester','tester'),('login_service_tester','tester'),('pTest','tester'),('session_tester','tester');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 COMMENT='Table to save user agents';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `browser_agents`
--

LOCK TABLES `browser_agents` WRITE;
/*!40000 ALTER TABLE `browser_agents` DISABLE KEYS */;
INSERT INTO `browser_agents` (`id`, `browser`, `hash`) VALUES (9,'UNKNOWN','25ba44ec3b391ba4ce5fbbd2979635e254775e7d'),(11,'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Mobile Safari/537.36','2d13425a6c604c6a91f6ffedec343a81e891b535'),(12,'PostmanRuntime/7.6.0','7c437b88e1cb7d09660f2b2ace2f865fb3fd6fb6'),(13,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Safari/537.36','064e005501a2a0d56363299e04b6b77a3ce5f7a5'),(14,'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Mobile Safari/537.36','1a9ef466e44b70dd24f39773687729521ea69bee'),(15,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Safari/537.36','13330a7d3aaee1569dd7ceebc04360c8b673fb08'),(16,'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Mobile Safari/537.36','0441391429577f903a877c9f7e215b8406705076'),(17,'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Mobile Safari/537.36','c9ca92194d513cd22f74a1cbc83dcb520058797e'),(18,'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1','7dcbae1a808f5601d4739f363f6008bc7140ca72'),(19,'Mozilla/5.0 (iPad; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.34 (KHTML, like Gecko) Version/11.0 Mobile/15A5341f Safari/604.1','aeaff6a1fa9f5f34bf37ab8808b0768d4ce43171'),(20,'Mozilla/5.0 (Linux; Android 8.1.0; SM-G610F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.105 Mobile Safari/537.36','0db45c9000667a146c934f16ad4b1775c4d708fe'),(21,'Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Mobile Safari/537.36','2a0761fbb154bfe6054883f3b2f445c1f52621ce');
/*!40000 ALTER TABLE `browser_agents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_details`
--

DROP TABLE IF EXISTS `doctor_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor_details` (
  `account_id` varchar(32) NOT NULL,
  `nic` varchar(16) NOT NULL,
  `full_name` text NOT NULL,
  `display_name` text NOT NULL,
  `category` varchar(10) DEFAULT NULL,
  `slmc_id` varchar(16) NOT NULL,
  `email` varchar(320) NOT NULL,
  `phone_number` varchar(32) NOT NULL,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `doctor_details_slmc_id_uindex` (`slmc_id`),
  UNIQUE KEY `doctor_details_nic_uindex` (`nic`),
  CONSTRAINT `doctor_details_doctors_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `doctors` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store doctor account details';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_details`
--

LOCK TABLES `doctor_details` WRITE;
/*!40000 ALTER TABLE `doctor_details` DISABLE KEYS */;
INSERT INTO `doctor_details` (`account_id`, `nic`, `full_name`, `display_name`, `category`, `slmc_id`, `email`, `phone_number`) VALUES ('652566699V','652566699V','Medical Center Tester','Tester','opd','1023136','tester@doctor.org','0342225658');
/*!40000 ALTER TABLE `doctor_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctors` (
  `account_id` varchar(32) NOT NULL,
  `default_password` varchar(16) NOT NULL,
  PRIMARY KEY (`account_id`),
  CONSTRAINT `doctors_accounts_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Rable to store all doctors';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctors`
--

LOCK TABLES `doctors` WRITE;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
INSERT INTO `doctors` (`account_id`, `default_password`) VALUES ('652566699V','zwkichmyxgvhfgvu');
/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medical_center_details`
--

DROP TABLE IF EXISTS `medical_center_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medical_center_details` (
  `account_id` varchar(32) NOT NULL,
  `name` text NOT NULL,
  `phsrc` varchar(32) NOT NULL,
  `email` varchar(320) DEFAULT NULL,
  `fax` varchar(32) DEFAULT NULL,
  `phone_number` varchar(32) DEFAULT NULL,
  `address` text NOT NULL,
  `postal_code` int(11) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `medical_centers_phsrc_id_uindex` (`phsrc`),
  CONSTRAINT `medical_center_details_medical_centers_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `medical_centers` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medical_center_details`
--

LOCK TABLES `medical_center_details` WRITE;
/*!40000 ALTER TABLE `medical_center_details` DISABLE KEYS */;
INSERT INTO `medical_center_details` (`account_id`, `name`, `phsrc`, `email`, `fax`, `phone_number`, `address`, `postal_code`, `creation_date`) VALUES ('medCenter','My Medical Center','PHSRC/DEMO/001','demo@gmail.com','01122334455','8886654533','No 344/1, Moonamalgahawatta, Duwa Temple Road',12000,'2019-02-24 11:50:14'),('medCenter2','My Medical Center 2','PHSRC/DEMO/002','kdsuneraavinash@gmail.com','','8886654533','No 344/1, Moonamalgahawatta, Duwa Temple Road',12000,'2019-02-24 11:50:14'),('medical_center_tester','Medical Center Tester','PHSRC/TEST/001','tester@medical.center','0102313546','07655667890','Fake Number, Fake Street, Fake City, Fake Province.',99999,'2019-02-24 17:41:55'),('non_exisiting_user','Medical Center Tester','PHSRC/INVALID/0111','tester@medical.center','0102313546','07655667890','Fake Number, Fake Street, Fake City, Fake Province.',99999,'2019-02-24 11:50:14');
/*!40000 ALTER TABLE `medical_center_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medical_center_verification`
--

DROP TABLE IF EXISTS `medical_center_verification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medical_center_verification` (
  `id` tinyint(1) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store medical center verification states';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medical_center_verification`
--

LOCK TABLES `medical_center_verification` WRITE;
/*!40000 ALTER TABLE `medical_center_verification` DISABLE KEYS */;
INSERT INTO `medical_center_verification` (`id`, `description`) VALUES (0,'Default'),(1,'Verified'),(2,'Rejected');
/*!40000 ALTER TABLE `medical_center_verification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medical_centers`
--

DROP TABLE IF EXISTS `medical_centers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medical_centers` (
  `account_id` varchar(32) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_id`),
  KEY `medical_centers_medical_center_verification_id_fk` (`verified`),
  CONSTRAINT `medical_centers_accounts_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `medical_centers_medical_center_verification_id_fk` FOREIGN KEY (`verified`) REFERENCES `medical_center_verification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medical_centers`
--

LOCK TABLES `medical_centers` WRITE;
/*!40000 ALTER TABLE `medical_centers` DISABLE KEYS */;
INSERT INTO `medical_centers` (`account_id`, `verified`) VALUES ('medCenter',0),('medical_center_tester',0),('medCenter2',2),('non_exisiting_user',2);
/*!40000 ALTER TABLE `medical_centers` ENABLE KEYS */;
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
  CONSTRAINT `sessions_accounts_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sessions_user_agents_id_fk` FOREIGN KEY (`browser_agent`) REFERENCES `browser_agents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store sessions of all users';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`account_id`, `ip_address`, `browser_agent`, `created`, `expires`, `session_key`) VALUES ('medCenter','127.0.0.1',15,'2019-02-24 17:50:16','2019-02-25 17:50:16','6a60540899c4ae0a79dbb855537dbcba10db7a32'),('medCenter2','127.0.0.1',15,'2019-02-24 16:41:05','2019-02-25 16:41:05','321ff335f4bb84f27c90f739450076f67dc49638');
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-24 17:53:57
