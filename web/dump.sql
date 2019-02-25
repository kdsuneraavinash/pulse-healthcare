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
INSERT INTO `account_credentials` (`account_id`, `password`, `salt`) VALUES ('652566699V','1c23f2ef1abdae6340bc39934aca90c6b9ad7f9ea7e9ddbd6564126fea803c6a','NSnYJ!eLW\"5,0h/d/2q+uY6,G|7L\'pb8@@*y|qrU'),('administrator','c5e5453bb50d0673615b206fabd904fa6c47a5881857b83512b0cfa74fdc402c','.3.$)8Gj]aB<AS!#*)T4\'}wwTu,i.#ETF;17QB&h'),('credentials_tester','885605b2a8931c47893d7f56741c11815b3ee0895aadf89eb3214977c232e675','TJiYj7^9%=+WE`!Yz-*sRq=:c:vl#~%U,un?9&1j'),('login_service_tester','69a2c411d5026d68326c0878f955fb17426d3be3a64eef01fa4996cd2b85aecd','Jpx3hY{hr:C@/C+&tJR_YmbBMqNhRU;/g&H>n~{8'),('medCenter','9bfc39e1f4a394156c274082a6682096f8b115d123548fa8a32c209994efab6b','a^UoD[;Pb;5|q(bsZz\"jludK}@=JvQ*:M]w\"9p&S'),('medical_center_tester','dbeee500569b3834643be15081f43a241ae18027b404dfcf13c9f2ed6d21598c','yCIg&@Ac8DAWID+-RnXwG62;8ZC2[)X\"YQP1Dk^4'),('pTest','c4dfdceb710452bda60e33a358ff9b3ede2224e1241debc0d42a5cc87ae9504f','6kE1%H5ja44Lna<0;tl)9*dxF9[79(RO:84sFV#C');
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
INSERT INTO `accounts` (`account_id`, `account_type`) VALUES ('administrator','admin'),('652566699V','doctor'),('medCenter','med_center'),('medical_center_tester','med_center'),('credentials_tester','tester'),('login_service_tester','tester'),('pTest','tester'),('session_tester','tester');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
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
INSERT INTO `doctors` (`account_id`, `default_password`) VALUES ('652566699V','jxypfwcaxrejroon');
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
INSERT INTO `medical_center_details` (`account_id`, `name`, `phsrc`, `email`, `fax`, `phone_number`, `address`, `postal_code`, `creation_date`) VALUES ('medCenter','My Medical Center','PHSRC/DEMO/001','demo@gmail.com','01122334455','8886654533','No 344/1, Moonamalgahawatta, Duwa Temple Road',12000,'2019-02-24 11:50:14'),('medical_center_tester','Medical Center Tester','PHSRC/TEST/001','tester@medical.center','0102313546','07655667890','Fake Number, Fake Street, Fake City, Fake Province.',99999,'2019-02-25 21:47:19');
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
INSERT INTO `medical_centers` (`account_id`, `verified`) VALUES ('medCenter',0),('medical_center_tester',0);
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
  `created` datetime NOT NULL,
  `expires` datetime NOT NULL,
  `session_key` char(40) NOT NULL,
  PRIMARY KEY (`account_id`,`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store sessions of all users';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`account_id`, `ip_address`, `created`, `expires`, `session_key`) VALUES ('medCenter','127.0.0.1','2019-02-25 21:48:06','2019-02-26 21:48:06','3d610a5d46e2ba9f7aa89c4a20face9891549994');
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

-- Dump completed on 2019-02-25 21:51:39
