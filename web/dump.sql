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
INSERT INTO `account_credentials` (`account_id`, `password`, `salt`) VALUES ('652566699V','685afa20c0bd3d38ef45ab5d02e50e4b27e1faf2ece42e270f2c9227e3e6a116','KuDH)l:|kw{CRpn5~v*&^#|DFX);=h<a<04}IQAt'),('932503234V','e49612a94db03b386481148e4136734e8f34901fd05ea0499808c54a6fd7ce06','AvA3&MgVS9_6\"</nc]>[T*wpA6Rd{wpoB35Dr}dw'),('971112610V','40459bce4629a194dbc34ef53f21937e419a8575ee129458b28b71b916586f03','gp;?CU8c~,b>7|}@n1X@Vl}95)zPQ87B.ap7.]!b'),('972502456V','e89c9b225422045be22cfe5b0e44cc22f508de7a72b2dfddd31ad4a3891f4d0e','a389E;v:M*gLVv_Rh?.dE#w\\#>5x9(dj7L4Pr6fs'),('978978877V','300c6bdc9a3f1458fa39affd30167c00267e0f0f755cb628cc684690b6d16bac','FP>19di8\\+F>Oy^Mm:vXJREk3>oH9ty0tOA!MD*e'),('administrator','c5e5453bb50d0673615b206fabd904fa6c47a5881857b83512b0cfa74fdc402c','.3.$)8Gj]aB<AS!#*)T4\'}wwTu,i.#ETF;17QB&h'),('credentials_tester','92ca43ba97b4510bc1a985d196fe36130b944e70b82d3cc5415f0c8cd06669a4','RkUdoj!zj3\'LBt{a<?\')phs`MyJ]!,)sYj<Gq\'LL'),('login_service_tester','948d8ba4aa26b9923f323559eb71b8de6d39720f1db28f654ac74aa6b559d8ab','+c(XJ]zuUv*i^O0>Dq\"]\'3I|$eOco>d3.5h4;BYH'),('medCenter','9bfc39e1f4a394156c274082a6682096f8b115d123548fa8a32c209994efab6b','a^UoD[;Pb;5|q(bsZz\"jludK}@=JvQ*:M]w\"9p&S'),('MedCenter23','cc92bd424e4e6a184e51d79eaa4d328c10241415960ab0bcc5e0c916df743716',':J_:T)APMlUlADP[oiCbI,2%Cb{>t9j@{Y-^`qA0'),('medical_center_tester','641c5c3e9352a1a48169511445ba33930a08667b2ddf2ade1c4a234c89153a53','6Hh*x:Ess=GD/=7Y8Lo[TP0G3\'z\'S\":YZ)qI@>\\Q'),('pTest','c4dfdceb710452bda60e33a358ff9b3ede2224e1241debc0d42a5cc87ae9504f','6kE1%H5ja44Lna<0;tl)9*dxF9[79(RO:84sFV#C');
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
INSERT INTO `accounts` (`account_id`, `account_type`) VALUES ('administrator','admin'),('652566699V','doctor'),('932503234V','doctor'),('971112610V','doctor'),('medCenter','med_center'),('MedCenter23','med_center'),('medical_center_tester','med_center'),('972502456V','patient'),('978978877V','patient'),('credentials_tester','tester'),('login_service_tester','tester'),('pTest','tester'),('session_tester','tester');
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
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `doctor_details_slmc_id_uindex` (`slmc_id`),
  UNIQUE KEY `doctor_details_nic_uindex` (`nic`),
  FULLTEXT KEY `doctor_details_slmc_id__display_name_index` (`slmc_id`,`display_name`),
  CONSTRAINT `doctor_details_doctors_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `doctors` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store doctor account details';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_details`
--

LOCK TABLES `doctor_details` WRITE;
/*!40000 ALTER TABLE `doctor_details` DISABLE KEYS */;
INSERT INTO `doctor_details` (`account_id`, `nic`, `full_name`, `display_name`, `category`, `slmc_id`, `email`, `phone_number`, `creation_date`) VALUES ('652566699V','652566699V','Medical Center Tester','Tester','opd','1023136','tester@doctor.org','0342225658','2019-03-04 13:52:24'),('932503234V','932503234V','D. J. Saman Kumara','Saman Kumara','opd','111','kdsuneraavinash@gmail.com','8886654533','2019-02-27 11:05:12'),('971112610V','971112610V','D. J. Saman Kumr','Kumar','opd','1112','abc@gmail.com','11122132412','2019-03-07 09:04:20');
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
  `default_password` char(16) NOT NULL,
  PRIMARY KEY (`account_id`),
  CONSTRAINT `doctors_accounts_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Rable to store all doctors';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctors`
--

LOCK TABLES `doctors` WRITE;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
INSERT INTO `doctors` (`account_id`, `default_password`) VALUES ('652566699V','acrjapuqfershyeq'),('932503234V','vpijuqovawotxqnl'),('971112610V','cjdfbftbdrekkpqc');
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
  `postal_code` text NOT NULL,
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
INSERT INTO `medical_center_details` (`account_id`, `name`, `phsrc`, `email`, `fax`, `phone_number`, `address`, `postal_code`, `creation_date`) VALUES ('medCenter','My Medical Center','PHSRC/DEMO/001','demo@gmail.com','01122334455','8886654533','No 344/1, Moonamalgahawatta, Duwa Temple Road','12000','2019-02-24 11:50:14'),('MedCenter23','medicalcenter2','PHSRC/DEMO/002','sunerasocacc@gmail.com','01122334455','8886654533','No 344/1, Moonamalgahawatta, Duwa Temple Road','12000','2019-03-07 09:17:14'),('medical_center_tester','Medical Center Tester','PHSRC/TEST/001','tester@medical.center','0102313546','07655667890','Fake Number, Fake Street, Fake City, Fake Province.','99999','2019-03-04 13:52:24');
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
INSERT INTO `medical_centers` (`account_id`, `verified`) VALUES ('MedCenter23',0),('medCenter',1),('medical_center_tester',1);
/*!40000 ALTER TABLE `medical_centers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_details`
--

DROP TABLE IF EXISTS `patient_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_details` (
  `account_id` varchar(32) NOT NULL,
  `nic` varchar(16) NOT NULL,
  `name` text NOT NULL,
  `phone_number` varchar(32) NOT NULL,
  `email` varchar(320) NOT NULL,
  `address` text NOT NULL,
  `postal_code` text NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `patient_details_nic_uindex` (`nic`),
  CONSTRAINT `patient_details_patients_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `patients` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store details of each patient';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_details`
--

LOCK TABLES `patient_details` WRITE;
/*!40000 ALTER TABLE `patient_details` DISABLE KEYS */;
INSERT INTO `patient_details` (`account_id`, `nic`, `name`, `phone_number`, `email`, `address`, `postal_code`, `creation_date`) VALUES ('972502456V','972502456V','Sunera Avinash','8886654533','kdsuneraavinash@gmail.com','No 344/1, Moonamalgahawatta, Duwa Temple Road','12000','2019-02-26 11:26:21'),('978978877V','978978877V','Patient Tester','07655667890','tester@medical.patient','Fake Number, Fake Street, Fake City, Fake Province.','99999','2019-03-04 13:52:25');
/*!40000 ALTER TABLE `patient_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patients` (
  `account_id` varchar(32) NOT NULL,
  `default_password` char(16) NOT NULL,
  PRIMARY KEY (`account_id`),
  CONSTRAINT `patients_accounts_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to stop patients';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` (`account_id`, `default_password`) VALUES ('972502456V','jpcihpxjjkimtwmt'),('978978877V','pstbllexpmnrcrtt');
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
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
  PRIMARY KEY (`account_id`,`ip_address`),
  CONSTRAINT `sessions_accounts_account_id_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to store sessions of all users';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`account_id`, `ip_address`, `created`, `expires`, `session_key`) VALUES ('971112610V','127.0.0.1','2019-03-10 21:48:37','2019-03-11 21:48:37','959e01cf6bd039cb18ea8944855d497099714ac5');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-11  0:58:09
