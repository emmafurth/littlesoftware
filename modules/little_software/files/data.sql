-- MySQL dump 10.13  Distrib 5.1.69, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: littlesoftware
-- ------------------------------------------------------
-- Server version	5.1.69-0ubuntu0.10.04.1

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
-- Table structure for table `little_applications`
--

DROP TABLE IF EXISTS `little_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `little_applications` (
  `ApplicationKey` int(11) NOT NULL AUTO_INCREMENT,
  `ApplicationId` char(36) NOT NULL,
  `ApplicationName` char(255) NOT NULL,
  `ApplicationRecieving` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ApplicationKey`),
  UNIQUE KEY `ApplicationId` (`ApplicationId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `little_applications`
--

LOCK TABLES `little_applications` WRITE;
/*!40000 ALTER TABLE `little_applications` DISABLE KEYS */;
INSERT INTO `little_applications` VALUES (1,'1b72d0c14582a016a1f672cd0be14e47','Test App',1);
/*!40000 ALTER TABLE `little_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `little_events`
--

DROP TABLE IF EXISTS `little_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `little_events` (
  `EventId` int(11) NOT NULL AUTO_INCREMENT,
  `EventCode` char(20) NOT NULL,
  `EventCategory` char(50) DEFAULT NULL,
  `EventName` char(50) DEFAULT NULL,
  `EventValue` char(50) DEFAULT NULL,
  `EventDuration` int(11) DEFAULT NULL,
  `EventCompleted` tinyint(1) DEFAULT NULL,
  `LogMessage` char(255) DEFAULT NULL,
  `EventCustomName` char(50) DEFAULT NULL,
  `EventCustomValue` char(50) DEFAULT NULL,
  `ExceptionMsg` char(255) DEFAULT NULL,
  `ExceptionStackTrace` text,
  `ExceptionSource` char(255) DEFAULT NULL,
  `ExceptionTargetSite` char(255) DEFAULT NULL,
  `SessionId` char(36) NOT NULL,
  `UtcTimestamp` int(11) NOT NULL,
  `ApplicationId` char(36) NOT NULL,
  `ApplicationVersion` char(50) NOT NULL,
  PRIMARY KEY (`EventId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `little_events`
--

LOCK TABLES `little_events` WRITE;
/*!40000 ALTER TABLE `little_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `little_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `little_locales`
--

DROP TABLE IF EXISTS `little_locales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `little_locales` (
  `LCID` int(11) NOT NULL,
  `DisplayName` varchar(100) NOT NULL,
  `ShortCode` varchar(50) NOT NULL,
  PRIMARY KEY (`LCID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `little_locales`
--

LOCK TABLES `little_locales` WRITE;
/*!40000 ALTER TABLE `little_locales` DISABLE KEYS */;
INSERT INTO `little_locales` VALUES (4,'Chinese ','zh '),(9,'English ','en '),(1025,'Arabic - Saudia Arabia ','ar-sa '),(1026,'Bulgarian ','bg '),(1027,'Catalan ','ca '),(1028,'Chinese - Taiwan ','zh-tw '),(1029,'Czech ','cs '),(1030,'Danish ','da '),(1031,'German - Standard ','de '),(1032,'Greek ','el '),(1033,'English - United States ','en-us '),(1034,'Spanish - Standard ','es '),(1035,'Finnish ','fi '),(1036,'French - Standard ','fr'),(1037,'Hebrew ','he '),(1038,'Hungarian ','hu '),(1039,'Icelandic ','is '),(1040,'Italian - Standard ','it '),(1041,'Japanese ','ja '),(1042,'Korean ','ko '),(1043,'Dutch ','nl '),(1044,'Norwegian - Bokm?l ','no '),(1045,'Polish ','pl '),(1046,'Portuguese - Brazil ','pt-br '),(1047,'Raeto-Romance ','rm '),(1048,'Romanian ','ro '),(1049,'Russian ','ru '),(1050,'Croatian ','hr '),(1051,'Slovak ','sk '),(1052,'Albanian ','sq '),(1053,'Swedish ','sv '),(1054,'Thai ','th '),(1055,'Turkish ','tr '),(1056,'Urdu - Pakistan ','ur '),(1057,'Indonesian ','in '),(1058,'Ukranian ','uk '),(1059,'Belarusian ','be '),(1060,'Slovenian ','sl '),(1061,'Estonian ','et '),(1062,'Latvian ','lv '),(1063,'Lithuanian ','lt '),(1065,'Farsi ','fa '),(1066,'Vietnamese ','vi '),(1069,'Basque ','eu '),(1070,'Sorbian ','sb '),(1071,'Macedonian ','mk '),(1072,'Sutu ','sx '),(1073,'Tsonga ','ts '),(1074,'Setsuana ','tn '),(1076,'Xhosa ','xh '),(1077,'Zulu ','zu '),(1078,'Afrikaans ','af '),(1080,'Faeroese ','fo '),(1081,'Hindi ','hi '),(1082,'Maltese ','mt '),(1084,'Gaelic - Scotland ','gd '),(1085,'Yiddish ','ji '),(1086,'Malay - Malaysia ','ms '),(2049,'Arabic - Iraq ','ar-iq '),(2052,'Chinese - PRC ','zh-cn '),(2055,'German - Switzerland ','de-ch '),(2057,'English - United Kingdom ','en-gb '),(2058,'Spanish - Mexico ','es-mx '),(2060,'French - Belgium ','fr-be '),(2064,'Italian - Switzerland ','it-ch '),(2067,'Dutch - Belgium ','nl-be '),(2070,'Portuguese - Standard ','pt '),(2072,'Romanian - Moldova ','ro-mo '),(2073,'Russian - Moldova ','ru-mo '),(2077,'Swedish - Finland ','sv-fi '),(3073,'Arabic - Egypt ','ar-eg '),(3076,'Chinese - Hong Kong ','zh-hk '),(3079,'German - Austrian ','de-at '),(3081,'English - Australia ','en-au '),(3084,'French - Canada ','fr-ca '),(3098,'Serbian - Cyrillic ','sr '),(4097,'Arabic - Libya ','ar-ly '),(4100,'Chinese - Singapore ','zh-sg '),(4103,'German - Luxembourg ','de-lu '),(4105,'English - Canada ','en-ca '),(4106,'Spanish - Guatemala ','es-gt '),(4108,'French - Switzerland ','fr-ch '),(5121,'Arabic - Algeria ','ar-dz '),(5127,'German - Lichtenstein ','de-li '),(5129,'English - New Zealand ','en-nz '),(5130,'Spanish - Costa Rica ','es-cr '),(5132,'French - Luxembourg ','fr-lu '),(6145,'Arabic - Morocco ','ar-ma '),(6153,'English - Ireland ','en-ie '),(6154,'Spanish - Panama ','es-pa '),(7169,'Arabic - Tunisia ','ar-tn '),(7177,'English - South Africa ','en-za '),(7178,'Spanish - Dominican Republic ','es-do '),(8193,'Arabic - Oman ','ar-om '),(8201,'English - Jamaica ','en-jm '),(8202,'Spanish - Venezuela ','es-ve '),(9217,'Arabic - Yemen ','ar-ye '),(9226,'Spanish - Columbia ','es-co '),(10241,'Arabic - Syria ','ar-sy '),(10249,'English - Belize ','en-bz '),(10250,'Spanish - Peru ','es-pe '),(11265,'Arabic - Jordan ','ar-jo '),(11273,'English - Trinidad ','en-tt '),(11274,'Spanish - Argentina ','es-ar '),(12289,'Arabic - Lebanon ','ar-lb '),(12298,'Spanish - Ecuador ','es-ec '),(13313,'Arabic - Kuwait ','ar-kw '),(13322,'Spanish - Chile ','es-cl '),(14337,'Arabic - U.A.E. ','ar-ae '),(14346,'Spanish - Uruguay ','es-uy '),(15361,'Arabic - Bahrain ','ar-bh '),(15370,'Spanish - Paraguay ','es-py '),(16385,'Arabic - Qatar ','ar-qa '),(16394,'Spanish - Bolivia ','es-bo '),(17418,'Spanish - El Salvador ','es-sv '),(18442,'Spanish - Honduras ','es-hn '),(19466,'Spanish - Nicaragua ','es-ni '),(20490,'Spanish - Puerto Rico ','es-pr ');
/*!40000 ALTER TABLE `little_locales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `little_options`
--

DROP TABLE IF EXISTS `little_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `little_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `little_options`
--

LOCK TABLES `little_options` WRITE;
/*!40000 ALTER TABLE `little_options` DISABLE KEYS */;
INSERT INTO `little_options` VALUES (1,'site_adminemail','foo@bar.com'),(2,'site_rewrite','false'),(3,'recaptcha_enabled','false'),(4,'recaptcha_public_key',''),(5,'recaptcha_private_key',''),(6,'mail_protocol','mail'),(7,'mail_smtp_server','localhost'),(8,'mail_smtp_port','25'),(9,'mail_smtp_username','joe.blow'),(10,'mail_smtp_password','password'),(11,'mail_sendmail_path','/usr/sbin/sendmail'),(12,'geoips_service','database'),(13,'geoips_api_key',''),(14,'geoips_database','/opt/little-software/inc/GeoIP.dat'),(15,'geoips_database_version','2012-02-07'),(16,'geoips_database_update_url','http://little-software-stats.com/geolite.xml'),(17,'geoips_database_checked','2013-06-06');
/*!40000 ALTER TABLE `little_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `little_sessions`
--

DROP TABLE IF EXISTS `little_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `little_sessions` (
  `SessionKey` int(11) NOT NULL AUTO_INCREMENT,
  `SessionId` char(36) NOT NULL,
  `UniqueUserId` char(36) NOT NULL,
  `StartApp` int(11) NOT NULL,
  `StopApp` int(11) NOT NULL,
  `ApplicationId` char(36) NOT NULL,
  `ApplicationVersion` char(50) NOT NULL,
  PRIMARY KEY (`SessionKey`),
  UNIQUE KEY `SessionId` (`SessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `little_sessions`
--

LOCK TABLES `little_sessions` WRITE;
/*!40000 ALTER TABLE `little_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `little_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `little_uniqueusers`
--

DROP TABLE IF EXISTS `little_uniqueusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `little_uniqueusers` (
  `UniqueUserKey` int(11) NOT NULL AUTO_INCREMENT,
  `UniqueUserId` char(36) NOT NULL,
  `Created` int(11) NOT NULL,
  `LastRecieved` int(11) NOT NULL,
  `IPAddress` char(20) NOT NULL,
  `Country` char(50) NOT NULL,
  `CountryCode` varchar(3) NOT NULL,
  `OSVersion` char(100) DEFAULT NULL,
  `OSServicePack` int(11) DEFAULT NULL,
  `OSArchitecture` int(11) DEFAULT NULL,
  `JavaVer` char(50) DEFAULT NULL,
  `NetVer` char(50) DEFAULT NULL,
  `NetSP` int(11) DEFAULT NULL,
  `LangID` int(11) DEFAULT NULL,
  `ScreenRes` char(20) DEFAULT NULL,
  `CPUName` char(40) DEFAULT NULL,
  `CPUBrand` char(20) DEFAULT NULL,
  `CPUFreq` int(11) DEFAULT NULL,
  `CPUCores` int(11) DEFAULT NULL,
  `CPUArch` int(11) DEFAULT NULL,
  `MemTotal` int(11) DEFAULT NULL,
  `MemFree` int(11) DEFAULT NULL,
  `DiskTotal` int(11) DEFAULT NULL,
  `DiskFree` int(11) DEFAULT NULL,
  PRIMARY KEY (`UniqueUserKey`),
  UNIQUE KEY `UniqueUserId` (`UniqueUserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `little_uniqueusers`
--

LOCK TABLES `little_uniqueusers` WRITE;
/*!40000 ALTER TABLE `little_uniqueusers` DISABLE KEYS */;
/*!40000 ALTER TABLE `little_uniqueusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `little_users`
--

DROP TABLE IF EXISTS `little_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `little_users` (
  `UserId` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` char(8) NOT NULL,
  `UserEmail` char(30) NOT NULL,
  `UserPass` varchar(83) NOT NULL,
  `ActivateKey` char(21) DEFAULT NULL,
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `UserName` (`UserName`,`UserEmail`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `little_users`
--

LOCK TABLES `little_users` WRITE;
/*!40000 ALTER TABLE `little_users` DISABLE KEYS */;
INSERT INTO `little_users` VALUES (1,'foobar','foo@bar.com','$2a$08$pKB52pLlMExlwsS2p9536.biX34PyJexK3qMsWEFstWGqf.kXMR8i',NULL);
/*!40000 ALTER TABLE `little_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-06-06  3:45:58
