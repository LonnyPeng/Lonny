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
-- Table structure for table `t_provinces`
--

DROP TABLE IF EXISTS `t_provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_provinces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `continent_id` int(11) NOT NULL COMMENT '大陆ID',
  `country_id` int(11) NOT NULL COMMENT '国家ID',
  `code` varchar(30) NOT NULL COMMENT 'CODE',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `note` varchar(255) DEFAULT NULL,
  `latlng` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`,`continent_id`,`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8 COMMENT='省份';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_provinces`
--

LOCK TABLES `t_provinces` WRITE;
/*!40000 ALTER TABLE `t_provinces` DISABLE KEYS */;
INSERT INTO `t_provinces` VALUES (1,1,1,'HBS','河北省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/13.html','38.037953,114.530323'),(2,1,1,'SDS','山东省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/37.html','36.671338,117.019946'),(3,1,1,'LNS','辽宁省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/21.html','41.836256,123.431382'),(4,1,1,'HLJS','黑龙江省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/23.html','45.742384,126.661905'),(5,1,1,'JLS','吉林省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/22.html','43.897077,125.325647'),(6,1,1,'GSS','甘肃省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/62.html','36.060592,103.826662'),(7,1,1,'QHS','青海省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/63.html','36.621168,101.780406'),(8,1,1,'HNS','河南省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/41.html','34.767146,113.753017'),(9,1,1,'JSS','江苏省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/32.html','32.061489,118.763522'),(10,1,1,'JXS','江西省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/36.html','28.675889,115.909126'),(11,1,1,'ZJS','浙江省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/33.html','30.266964,120.152512'),(12,1,1,'GDS','广东省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/44.html','23.132438,113.26631'),(13,1,1,'YNS','云南省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/53.html','25.046603,102.71027'),(14,1,1,'FJS','福建省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/35.html','26.10035,119.295176'),(15,1,1,'TWS','台湾省',2,NULL,'25.058004,121.53069'),(16,1,1,'SXS','山西省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/14.html','37.813631,112.578776'),(17,1,1,'SCS','四川省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/51.html','30.651309,104.076167'),(18,1,1,'GZS','贵州省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/52.html','26.600688,106.705178'),(19,1,1,'AHS','安徽省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/34.html','31.734714,117.330064'),(20,1,1,'ZQS','重庆市',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/50.html','29.564064,106.550301'),(21,1,1,'BJS','北京市',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/11.html','39.9042,116.407399'),(22,1,1,'SHS','上海市',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/31.html','31.230421,121.473744'),(23,1,1,'TJS','天津市',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/12.html','39.085428,117.201498'),(24,1,1,'GXZZZZQ','广西壮族自治区',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/45.html','22.815943,108.3279'),(25,1,1,'NMGZZQ','内蒙古自治区',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/15.html','40.818277,111.765241'),(26,1,1,'XCZZQ','西藏自治区',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/54.html','29.648903,91.117367'),(27,1,1,'XJWWEZZQ','新疆维吾尔自治区',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/65.html','43.825799,87.61683'),(28,1,1,'NXHZZZQ','宁夏回族自治区',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/64.html','38.472653,106.258818'),(29,1,1,'AMTBXZQ','澳门特别行政区',2,NULL,'22.148699,113.56895'),(30,1,1,'XGTBXZQ','香港特别行政区',2,NULL,'22.264558,114.204698'),(31,1,2,'BHD','北海道',1,NULL,NULL),(32,1,2,'QSX','青森县',1,NULL,NULL),(33,1,2,'YSX','岩手县',1,NULL,NULL),(34,1,2,'GCX','宫城县',1,NULL,NULL),(35,1,2,'QTX','秋田县',1,NULL,NULL),(36,1,2,'SXX','山形县',1,NULL,NULL),(37,1,2,'FDX','福岛县',1,NULL,NULL),(38,1,2,'DJD','东京都',1,NULL,NULL),(39,1,2,'CCX','茨城县',1,NULL,NULL),(40,1,2,'MX','栃木县',1,NULL,NULL),(41,1,2,'QMX','群马县',1,NULL,NULL),(42,1,2,'QYX','崎玉县',1,NULL,NULL),(43,1,2,'SNCX','神奈川县',1,NULL,NULL),(44,1,2,'SLX','山梨县',1,NULL,NULL),(45,1,2,'XXX','新潟县',1,NULL,NULL),(46,1,2,'FSX','富山县',1,NULL,NULL),(47,1,2,'SCX','石川县',1,NULL,NULL),(48,1,2,'FJX','福井县',1,NULL,NULL),(49,1,2,'ZYX','长野县',1,NULL,NULL),(50,1,2,'QFX','岐阜县',1,NULL,NULL),(51,1,2,'JGX','静冈县',1,NULL,NULL),(52,1,2,'AZX','爱知县',1,NULL,NULL),(53,1,2,'SZX','三重县',1,NULL,NULL),(54,1,2,'JDF','京都府',1,NULL,NULL),(55,1,2,'DBF','大阪府',1,NULL,NULL),(56,1,2,'ZHX','滋贺县',1,NULL,NULL),(57,1,2,'BKX','兵库县',1,NULL,NULL),(58,1,2,'NLX','奈良县',1,NULL,NULL),(59,1,2,'HGSX','和歌山县',1,NULL,NULL),(60,1,2,'NQX','鸟取县',1,NULL,NULL),(61,1,2,'DGX','岛根县',1,NULL,NULL),(62,1,2,'GSX','冈山县',1,NULL,NULL),(63,1,2,'GDX','广岛县',1,NULL,NULL),(64,1,2,'SKX','山口县',1,NULL,NULL),(65,1,2,'DDX','德岛县',1,NULL,NULL),(66,1,2,'XCX','香川县',1,NULL,NULL),(67,1,2,'AYX','爱媛县',1,NULL,NULL),(68,1,2,'GZX','高知县',1,NULL,NULL),(69,1,2,'FGX','福冈县',1,NULL,NULL),(70,1,2,'ZQX','长崎县',1,NULL,NULL),(71,1,2,'XBX','熊本县',1,NULL,NULL),(72,1,2,'DFX','大分县',1,NULL,NULL),(73,1,2,'GQX','宫崎县',1,NULL,NULL),(74,1,2,'LÉDX','鹿児岛县',1,NULL,NULL),(75,1,2,'CSX','冲绳县',1,NULL,NULL),(76,1,3,'LJXFS','罗津先锋市',1,NULL,NULL),(77,1,3,'PRTBS','平壤特别市',1,NULL,NULL),(78,1,3,'XYZTBXZQ','新义州特别行政区',1,NULL,NULL),(79,1,3,'KCGYDQ','开城工业地区',1,NULL,NULL),(80,1,3,'JGSGGDQ','金刚山观光地区',1,NULL,NULL),(81,1,3,'PABD','平安北道',1,NULL,NULL),(82,1,3,'PAND','平安南道',1,NULL,NULL),(83,1,3,'LJD','两江道',1,NULL,NULL),(84,1,3,'CJD','慈江道',1,NULL,NULL),(85,1,3,'XJBD','咸镜北道',1,NULL,NULL),(86,1,3,'XJND','咸镜南道',1,NULL,NULL),(87,1,3,'HHBD','黄海北道',1,NULL,NULL),(88,1,3,'HHND','黄海南道',1,NULL,NULL),(89,1,3,'JYBD','江原北道',1,NULL,NULL),(90,1,4,'SETBS','首尔特别市',1,NULL,NULL),(91,1,4,'FSGYS','釜山广域市',1,NULL,NULL),(92,1,4,'DQGYS','大邱广域市',1,NULL,NULL),(93,1,4,'RCGYS','仁川广域市',1,NULL,NULL),(94,1,4,'GZGYS','光州广域市',1,NULL,NULL),(95,1,4,'DTGYS','大田广域市',1,NULL,NULL),(96,1,4,'WSGYS','蔚山广域市',1,NULL,NULL),(97,1,4,'JJD','京畿道',1,NULL,NULL),(98,1,4,'JYD','江原道',1,NULL,NULL),(99,1,4,'ZQBD','忠清北道',1,NULL,NULL),(100,1,4,'ZQND','忠清南道',1,NULL,NULL),(101,1,4,'QLBD','全罗北道',1,NULL,NULL),(102,1,4,'QLND','全罗南道',1,NULL,NULL),(103,1,4,'QSBD','庆尚北道',1,NULL,NULL),(104,1,4,'QSND','庆尚南道',1,NULL,NULL),(105,1,4,'JZD','济州道',1,NULL,NULL),(106,5,163,'GLBYTQ','哥伦比亚特区',1,NULL,NULL),(107,5,163,'YLBMZ','亚拉巴马州',1,NULL,NULL),(108,5,163,'ALSJZ','阿拉斯加州',1,NULL,NULL),(109,5,163,'YLSNZ','亚利桑那州',1,NULL,NULL),(110,5,163,'AKSZ','阿肯色州',1,NULL,NULL),(111,5,163,'JLFNYZ','加利福尼亚州',1,NULL,NULL),(112,5,163,'KLLDZ','科罗拉多州',1,NULL,NULL),(113,5,163,'KNDGZ','康涅狄格州',1,NULL,NULL),(114,5,163,'TLHZ','特拉华州',1,NULL,NULL),(115,5,163,'FLLDZ','佛罗里达州',1,NULL,NULL),(116,5,163,'ZZYZ','佐治亚州',1,NULL,NULL),(117,5,163,'XWYZ','夏威夷州',1,NULL,NULL),(118,5,163,'ADHZ','爱达荷州',1,NULL,NULL),(119,5,163,'YLNYZ','伊利诺伊州',1,NULL,NULL),(120,5,163,'YDANZ','印第安纳州',1,NULL,NULL),(121,5,163,'AAWZ','艾奥瓦州',1,NULL,NULL),(122,5,163,'KSSZ','堪萨斯州',1,NULL,NULL),(123,5,163,'KTJZ','肯塔基州',1,NULL,NULL),(124,5,163,'LYSANZ','路易斯安那州',1,NULL,NULL),(125,5,163,'MYZ','缅因州',1,NULL,NULL),(126,5,163,'MLLZ','马里兰州',1,NULL,NULL),(127,5,163,'MSZSZ','马萨诸塞州',1,NULL,NULL),(128,5,163,'MXGZ','密歇根州',1,NULL,NULL),(129,5,163,'MNSDZ','明尼苏达州',1,NULL,NULL),(130,5,163,'MXXBZ','密西西比州',1,NULL,NULL),(131,5,163,'MSLZ','密苏里州',1,NULL,NULL),(132,5,163,'MDNZ','蒙大拿州',1,NULL,NULL),(133,5,163,'NBLSJZ','内布拉斯加州',1,NULL,NULL),(134,5,163,'NHDZ','内华达州',1,NULL,NULL),(135,5,163,'XHBSEZ','新罕布什尔州',1,NULL,NULL),(136,5,163,'XZXZ','新泽西州',1,NULL,NULL),(137,5,163,'XMXGZ','新墨西哥州',1,NULL,NULL),(138,5,163,'NYZ','纽约州',1,NULL,NULL),(139,5,163,'BKLLNZ','北卡罗来纳州',1,NULL,NULL),(140,5,163,'BDKTZ','北达科他州',1,NULL,NULL),(141,5,163,'EHEZ','俄亥俄州',1,NULL,NULL),(142,5,163,'EKLHMZ','俄克拉何马州',1,NULL,NULL),(143,5,163,'ELGZ','俄勒冈州',1,NULL,NULL),(144,5,163,'BXFNYZ','宾夕法尼亚州',1,NULL,NULL),(145,5,163,'LDDZ','罗得岛州',1,NULL,NULL),(146,5,163,'NKLLNZ','南卡罗来纳州',1,NULL,NULL),(147,5,163,'NDKTZ','南达科他州',1,NULL,NULL),(148,5,163,'TNXZ','田纳西州',1,NULL,NULL),(149,5,163,'DKSSZ','得克萨斯州',1,NULL,NULL),(150,5,163,'YTZ','犹他州',1,NULL,NULL),(151,5,163,'FMTZ','佛蒙特州',1,NULL,NULL),(152,5,163,'FJNYZ','弗吉尼亚州',1,NULL,NULL),(153,5,163,'HSDZ','华盛顿州',1,NULL,NULL),(154,5,163,'XFJNYZ','西弗吉尼亚州',1,NULL,NULL),(155,5,163,'WSKXZ','威斯康星州',1,NULL,NULL),(156,5,163,'HEMZ','怀俄明州',1,NULL,NULL),(158,1,1,'HBS','湖北省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/42.html','30.546378,114.341587'),(159,1,1,'HNS','湖南省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/43.html','28.116324,112.982877'),(160,1,1,'HNS','海南省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/46.html','20.019222,110.348688'),(161,1,1,'SXS','陕西省',2,'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/61.html','34.265344,108.954126');
/*!40000 ALTER TABLE `t_provinces` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-28 17:36:10
