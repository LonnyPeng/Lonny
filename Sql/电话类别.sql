-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: db_data
-- ------------------------------------------------------
-- Server version	5.7.18-log

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
-- Table structure for table `tel_name`
--

DROP TABLE IF EXISTS `tel_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tel_name` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` char(3) NOT NULL,
  `name` varchar(200) NOT NULL COMMENT '名称',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `index_filed` (`code`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1043 DEFAULT CHARSET=utf8 COMMENT='电话公司';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tel_name`
--

LOCK TABLES `tel_name` WRITE;
/*!40000 ALTER TABLE `tel_name` DISABLE KEYS */;
INSERT INTO `tel_name` VALUES (1000,'134','中国移动',1),(1001,'135','中国移动',1),(1002,'136','中国移动',1),(1003,'137','中国移动',1),(1004,'138','中国移动',1),(1005,'139','中国移动',1),(1006,'150','中国移动',1),(1007,'151','中国移动',1),(1008,'152','中国移动',1),(1009,'157','中国移动',1),(1010,'158','中国移动',1),(1011,'159','中国移动',1),(1012,'147','中国移动',1),(1013,'182','中国移动',1),(1014,'183','中国移动',1),(1015,'184','中国移动',1),(1016,'187','中国移动',1),(1017,'188','中国移动',1),(1018,'130','中国联通',1),(1019,'131','中国联通',1),(1020,'132','中国联通',1),(1021,'145','中国联通',1),(1022,'155','中国联通',1),(1023,'156','中国联通',1),(1024,'185','中国联通',1),(1025,'186','中国联通',1),(1026,'133','中国电信',1),(1027,'153','中国电信',1),(1028,'180','中国电信',1),(1029,'181','中国电信',1),(1030,'189','中国电信',1),(1031,'170','虚拟运营商',1),(1032,'149','中国电信',1),(1033,'173','中国电信',1),(1034,'177','中国电信',1),(1035,'199','中国电信',1),(1036,'166','中国联通',1),(1037,'171','中国联通',1),(1038,'175','中国联通',1),(1039,'176','中国联通',1),(1040,'172','中国移动',1),(1041,'178','中国移动',1),(1042,'198','中国移动',1);
/*!40000 ALTER TABLE `tel_name` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-28 17:36:11
