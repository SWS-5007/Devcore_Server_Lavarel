-- MySQL dump 10.13  Distrib 8.0.23, for Linux (x86_64)
--
-- Host: localhost    Database: homestead
-- ------------------------------------------------------
-- Server version	8.0.23-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auth_tokens`
--

DROP TABLE IF EXISTS `auth_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `access_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refresh_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extended_ttl` tinyint(1) NOT NULL DEFAULT '0',
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `revoked_at` datetime DEFAULT NULL,
  `refresh_token_expires_at` datetime DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auth_tokens_access_token_unique` (`access_token`),
  UNIQUE KEY `auth_tokens_refresh_token_unique` (`refresh_token`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_tokens`
--

LOCK TABLES `auth_tokens` WRITE;
/*!40000 ALTER TABLE `auth_tokens` DISABLE KEYS */;
INSERT INTO `auth_tokens` VALUES (1,'IcuSBPG8IU2Bf10CjvcioPCVKMBhhByfZhhgKruYpWa7klQPOuH2rWle2J2PmzzuUuLjUKV27YFJMG6kADN7T8IxbmRqSgevPAQ8','hMHKU02aX2mfUxMZKjcYFy9vNtsgr623cgncTBIEpGmU0GGPAm3IAm0y0uC46W3bLyFiq54XGIMbfw27m4CriD6ppBaGXCpn6No9',0,'user','1','web:1.0','2021-06-05 10:33:48','2021-06-05 09:33:57','2022-06-06 09:33:48','{\"ip\": \"192.168.10.1\", \"user-agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36\"}','2021-06-05 09:33:05','2021-06-05 09:33:57'),(2,'HX723v06dM2Rm9B5tUVLnf1KuDGqZP4iOZSKnYr4DbXcWZxqRjDEaWuvfHfgiBDo7RYs2bHGRMBGz0BPKUArqs8bbdCQJj1fMyRJ','Je2DJTWsHxFikxfUx3Y6oOzGKROVie3OPsvoLoQHX2UNjvxQ3pPpoJwgPVKn2VlZdbKeWBUH6lEVtdR4QCLVOaoxszd3CNkJp9cw',0,'user','2','web:1.0','2021-06-05 10:34:11','2021-06-05 09:34:12','2022-06-06 09:34:11','{\"ip\": \"192.168.10.1\", \"user-agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36\"}','2021-06-05 09:33:57','2021-06-05 09:34:12');
/*!40000 ALTER TABLE `auth_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_lang` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `industry_id` bigint unsigned DEFAULT NULL,
  `currency_code` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT 'EUR',
  `status` enum('ACTIVE','INACTIVE','TRASHED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `companies_industry_id_foreign` (`industry_id`),
  KEY `companies_currency_code_foreign` (`currency_code`),
  KEY `companies_name_index` (`name`),
  KEY `companies_status_index` (`status`),
  CONSTRAINT `companies_currency_code_foreign` FOREIGN KEY (`currency_code`) REFERENCES `currencies` (`code`) ON DELETE SET NULL,
  CONSTRAINT `companies_industry_id_foreign` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'test company',NULL,'en',NULL,'EUR','ACTIVE',NULL,'2021-06-05 09:33:30','2021-06-05 09:33:30');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_roles`
--

DROP TABLE IF EXISTS `company_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint unsigned DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_roles_company_id_name_unique` (`company_id`,`name`),
  CONSTRAINT `company_roles_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_roles`
--

LOCK TABLES `company_roles` WRITE;
/*!40000 ALTER TABLE `company_roles` DISABLE KEYS */;
INSERT INTO `company_roles` VALUES (1,'Management',1,NULL,NULL,'2021-06-05 09:33:30','2021-06-05 09:33:30');
/*!40000 ALTER TABLE `company_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_tool_prices`
--

DROP TABLE IF EXISTS `company_tool_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_tool_prices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_tool_id` bigint unsigned NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `tool_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `price_model` enum('PROJECT','LICENSE','ONE_TIME_PAYMENT') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LICENSE',
  `price_frequency` int unsigned NOT NULL DEFAULT '1',
  `price_interval` enum('MONTH','YEAR') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'YEAR',
  `price` bigint unsigned NOT NULL DEFAULT '1',
  `parent_id` bigint unsigned DEFAULT NULL,
  `parent_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiration` datetime DEFAULT NULL,
  `next_schedule_process` datetime DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_tool_prices_company_tool_id_foreign` (`company_tool_id`),
  KEY `company_tool_prices_company_id_foreign` (`company_id`),
  KEY `company_tool_prices_tool_id_foreign` (`tool_id`),
  KEY `company_tool_prices_parent_id_parent_type_index` (`parent_id`,`parent_type`),
  KEY `company_tool_prices_status_index` (`status`),
  KEY `company_tool_prices_price_model_index` (`price_model`),
  KEY `company_tool_prices_price_interval_index` (`price_interval`),
  CONSTRAINT `company_tool_prices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `company_tool_prices_company_tool_id_foreign` FOREIGN KEY (`company_tool_id`) REFERENCES `company_tools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `company_tool_prices_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_tool_prices`
--

LOCK TABLES `company_tool_prices` WRITE;
/*!40000 ALTER TABLE `company_tool_prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_tool_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_tools`
--

DROP TABLE IF EXISTS `company_tools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_tools` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `company_id` bigint unsigned NOT NULL,
  `tool_id` bigint unsigned NOT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `price_model` enum('PROJECT','LICENSE','ONE_TIME_PAYMENT') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LICENSE',
  `costs` bigint unsigned NOT NULL DEFAULT '0',
  `total_gains` bigint NOT NULL DEFAULT '0',
  `total_losses` bigint NOT NULL DEFAULT '0',
  `consolidated_value` bigint NOT NULL DEFAULT '0',
  `total_evaluations` int unsigned NOT NULL DEFAULT '0',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_tools_parent_id_foreign` (`parent_id`),
  KEY `company_tools_company_id_foreign` (`company_id`),
  KEY `company_tools_tool_id_foreign` (`tool_id`),
  KEY `company_tools_status_index` (`status`),
  KEY `company_tools_price_model_index` (`price_model`),
  KEY `company_tools_project_id_foreign` (`project_id`),
  CONSTRAINT `company_tools_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `company_tools_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `company_tools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `company_tools_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `company_tools_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_tools`
--

LOCK TABLES `company_tools` WRITE;
/*!40000 ALTER TABLE `company_tools` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_tools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currencies` (
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `translations` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`),
  KEY `currencies_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES ('AED','Emirati Dirham','.د.ب',NULL,'2021-06-05 09:29:55','2021-06-05 09:29:55'),('AFN','Afghan Afghani','؋',NULL,'2021-06-05 09:29:55','2021-06-05 09:29:55'),('ALL','Albanian lek','lek',NULL,'2021-06-05 09:29:55','2021-06-05 09:29:55'),('AMD','Armenian dram','',NULL,'2021-06-05 09:29:55','2021-06-05 09:29:55'),('ANG','Dutch Guilder','ƒ',NULL,'2021-06-05 09:29:55','2021-06-05 09:29:55'),('AOA','Angolan Kwanza','Kz',NULL,'2021-06-05 09:29:55','2021-06-05 09:29:55'),('ARS','Argentine peso','$',NULL,'2021-06-05 09:29:55','2021-06-05 09:29:55'),('AUD','Australian Dollar','$',NULL,'2021-06-05 09:29:55','2021-06-05 09:29:55'),('AWG','Arubin florin','ƒ',NULL,'2021-06-05 09:29:55','2021-06-05 09:29:55'),('AZN','Azerbaijani manat','ман',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BAM','Bosnian Convertible Marka','KM',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BBD','Barbadian dollar','$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BDT','Bangladeshi Taka','Tk',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BGN','Bulgarian lev','лв',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BHD','Bahraini Dinar','.د.ب',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BIF','Burundian Franc','',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BMD','Bermudian dollar','$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BND','Bruneian Dollar','$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BOB','Bolivian Boliviano','$b',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BRL','Brazilian real','R$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BSD','Bahamian dollar','B$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BTN','Bhutanese Ngultrum','Nu.',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BWP','Botswana Pula','P',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BYN','Belarusian ruble','р',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('BZD','Belize dollar','BZ$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('CAD','Canadian Dollar','$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('CHF','Swiss Franc','CHF',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('CLP','Chilean Peso','$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('CNY','Yuan or chinese renminbi','¥',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('COP','Colombian peso','$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('CRC','Costa Rican colón','₡',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('CUC','Cuban convertible peso','$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('CUP','Cuban peso','₱',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('CVE','Cape Verdean Escudo','$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('CZK','Czech koruna','Kč',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('DJF','Djiboutian Franc','fdj',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('DKK','Danish krone','kr',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('DOP','Dominican peso','$',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('DZD','Algerian Dinar','جد',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('EGP','Egyptian Pound','£ ',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('ERN','Eritrean nakfa','ናቕፋ',NULL,'2021-06-05 09:29:56','2021-06-05 09:29:56'),('ETB','Ethiopian Birr','Br',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('EUR','Euro','€',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('FJD','Fijian dollar','$',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('FKP','Falkland Island Pound','£',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('GBP','British Pound','£',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('GEL','Georgian lari','ლ',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('GHS','Ghanaian Cedi','GH¢',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('GIP','Gibraltar pound','£',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('GMD','Gambian dalasi','',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('GNF','Guinean Franc','',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('GTQ','Guatemalan Quetzal','Q',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('GYD','Guyanese dollar','$',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('HKD','Hong Kong dollar','HK$',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('HNL','Honduran lempira','L',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('HRK','Croatian kuna','kn',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('HTG','Haitian gourde','G',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('HUF','Hungarian forint','Ft',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('IDR','Indonesian Rupiah','Rp',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('ILS','Israeli Shekel','₪',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('INR','Indian Rupee','₹',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('IQD','Iraqi Dinar','ع.د',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('IRR','Iranian Rial','',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('ISK','Icelandic Krona','kr',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('JMD','Jamaican dollar','J$',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('JOD','Jordanian Dinar','',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('JPY','Japanese yen','¥',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('KES','Kenyan Shilling','KSh',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('KGS','Kyrgyzstani som','лв',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('KHR','Cambodian Riel','៛',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('KMF','Comoran Franc','',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('KPW','North Korean won','₩',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('KRW','South Korean won','₩',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('KWD','Kuwaiti Dinar','ك',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('KYD','Caymanian Dollar','$',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('KZT','Kazakhstani tenge','₸',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('LAK','Lao or Laotian Kip','₭',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('LBP','Lebanese Pound','ل.ل',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('LKR','Sri Lankan Rupee','Rs',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('LRD','Liberian Dollar','$',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('LSL','Lesotho loti','L',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('LTL','Lithuanian litas','Lt',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('LYD','Libyan Dinar',' د.ل',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('MAD','Moroccan Dirham','م.د.',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('MDL','Moldovan Leu','L',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('MGA','Malagasy Ariary','Ar',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('MKD','Macedonian Denar','ден',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('MMK','Burmese Kyat','K',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('MNT','Mongolian Tughrik','₮',NULL,'2021-06-05 09:29:57','2021-06-05 09:29:57'),('MOP','Macau Pataca','MOP$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('MRO','Mauritanian Ouguiya','UM',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('MUR','Mauritian rupee','Rs',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('MVR','Maldivian Rufiyaa','rf',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('MWK','Malawian Kwacha','MK',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('MXN','Mexico Peso','$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('MYR','Malaysian Ringgit','RM',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('MZN','Mozambican Metical','MT',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('NAD','Namibian Dollar','$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('NGN','Nigerian Naira','₦',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('NIO','Nicaraguan córdoba','C$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('NOK','Norwegian krone','kr',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('NPR','Nepalese Rupee','Rs',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('NZD','New Zealand Dollar','$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('OMR','Omani Rial','ع.ر.',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('PAB','Balboa panamérn','B/',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('PEN','Peruvian nuevo sol','S/',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('PGK','Papua New Guinean Kina','K',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('PHP','Philippine Peso','₱',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('PKR','Pakistani Rupee','Rs',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('PLN','Polish złoty','zł',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('PYG','Paraguayan guarani','₲',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('QAR','Qatari Riyal','ق.ر ',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('RON','Romanian leu','lei',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('RSD','Serbian Dinar','РСД',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('RUB','Russian Rouble','₽',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('RWF','Rwandan franc','FRw, RF, R₣',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SAR','Saudi Arabian Riyal','ر.س',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SBD','Solomon Islander Dollar','SI$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SCR','Seychellois Rupee','Rs',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SDG','Sudanese Pound','',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SEK','Swedish krona','kr',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SGD','Singapore Dollar','$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SLL','Sierra Leonean Leone','Le',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SOS','Somali Shilling','S',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SRD','Surinamese dollar','$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SSP','South Sudanese pound','£',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SYP','Syrian Pound','£',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('SZL','Swazi Lilangeni','L',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('THB','Thai Baht','฿',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('TJS','Tajikistani somoni','',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('TMT','Turkmenistan manat','T',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('TND','Tunisian Dinar','',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('TOP','Tongan Pa\'anga','T$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('TRY','Turkish Lira','₺',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('TTD','Trinidadian dollar','TT$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('TWD','Taiwan New Dollar','NT$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('TZS','Tanzanian Shilling','Sh',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('UAH','Ukrainian Hryvnia','₴',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('UGX','Ugandan Shilling','USh',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('USD','US Dollar','$',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('UYU','Uruguayan peso','$U',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('UZS','Uzbekistani som','лв',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('VEF','Venezuelan bolivar','Bs',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('VND','Vietnamese Dong','₫',NULL,'2021-06-05 09:29:58','2021-06-05 09:29:58'),('VUV','Ni-Vanuatu Vatu','VT',NULL,'2021-06-05 09:29:59','2021-06-05 09:29:59'),('WST','Samoan Tālā','$',NULL,'2021-06-05 09:29:59','2021-06-05 09:29:59'),('XCD','East Caribbean dollar','EC$',NULL,'2021-06-05 09:29:59','2021-06-05 09:29:59'),('XOF','CFA Franc','',NULL,'2021-06-05 09:29:59','2021-06-05 09:29:59'),('XPF','CFP Franc','',NULL,'2021-06-05 09:29:59','2021-06-05 09:29:59'),('YER','Yemeni Rial','',NULL,'2021-06-05 09:29:59','2021-06-05 09:29:59'),('ZAR','South African Rand','R',NULL,'2021-06-05 09:29:59','2021-06-05 09:29:59'),('ZMW','Zambian Kwacha','ZMK',NULL,'2021-06-05 09:29:59','2021-06-05 09:29:59'),('ZWD','Zimbabwean Dollar','Z$',NULL,'2021-06-05 09:29:59','2021-06-05 09:29:59');
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `idea_issues`
--

DROP TABLE IF EXISTS `idea_issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `idea_issues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `process_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `idea_id` bigint unsigned NOT NULL,
  `project_stage_id` bigint unsigned DEFAULT NULL,
  `parent_id` bigint unsigned NOT NULL,
  `parent_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('PROBLEM','IMPROVEMENT') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IMPROVEMENT',
  `unit` enum('MONEY','TIME') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MONEY',
  `dimension` enum('WEEK','MONTH','TOTAL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TOTAL',
  `value` bigint NOT NULL DEFAULT '0',
  `value_money` bigint NOT NULL DEFAULT '0',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `anonymous_idea` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idea_issues_uuid_unique` (`uuid`),
  KEY `idea_issues_company_id_foreign` (`company_id`),
  KEY `idea_issues_author_id_foreign` (`author_id`),
  KEY `idea_issues_process_id_foreign` (`process_id`),
  KEY `idea_issues_project_id_foreign` (`project_id`),
  KEY `idea_issues_idea_id_foreign` (`idea_id`),
  KEY `idea_issues_project_stage_id_foreign` (`project_stage_id`),
  KEY `idea_issues_title_index` (`title`),
  KEY `idea_issues_type_index` (`type`),
  KEY `idea_issues_unit_index` (`unit`),
  KEY `idea_issues_dimension_index` (`dimension`),
  CONSTRAINT `idea_issues_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `idea_issues_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `idea_issues_idea_id_foreign` FOREIGN KEY (`idea_id`) REFERENCES `ideas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `idea_issues_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `idea_issues_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `idea_issues_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `idea_issues`
--

LOCK TABLES `idea_issues` WRITE;
/*!40000 ALTER TABLE `idea_issues` DISABLE KEYS */;
/*!40000 ALTER TABLE `idea_issues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ideas`
--

DROP TABLE IF EXISTS `ideas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ideas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `company_tool_id` bigint unsigned DEFAULT NULL,
  `process_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `parent_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_id` bigint unsigned DEFAULT NULL,
  `source_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('NEW','TESTING','ADOPTED','ARCHIVED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NEW',
  `type` enum('PROCESS','TOOL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PROCESS',
  `total_gains` bigint NOT NULL DEFAULT '0',
  `total_losses` bigint NOT NULL DEFAULT '0',
  `consolidated_value` bigint NOT NULL DEFAULT '0',
  `total_evaluations` int unsigned NOT NULL DEFAULT '0',
  `properties` json DEFAULT NULL,
  `version` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `project_stage_id` bigint unsigned DEFAULT NULL,
  `anonymous_idea` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ideas_uuid_unique` (`uuid`),
  KEY `ideas_company_id_foreign` (`company_id`),
  KEY `ideas_author_id_foreign` (`author_id`),
  KEY `ideas_company_tool_id_foreign` (`company_tool_id`),
  KEY `ideas_process_id_foreign` (`process_id`),
  KEY `ideas_parent_type_parent_id_index` (`parent_type`,`parent_id`),
  KEY `ideas_source_id_source_type_index` (`source_id`,`source_type`),
  KEY `ideas_title_index` (`title`),
  KEY `ideas_status_index` (`status`),
  KEY `ideas_type_index` (`type`),
  KEY `ideas_project_id_foreign` (`project_id`),
  KEY `ideas_project_stage_id_foreign` (`project_stage_id`),
  CONSTRAINT `ideas_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ideas_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ideas_company_tool_id_foreign` FOREIGN KEY (`company_tool_id`) REFERENCES `company_tools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ideas_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ideas_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ideas_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ideas`
--

LOCK TABLES `ideas` WRITE;
/*!40000 ALTER TABLE `ideas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ideas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `industries`
--

DROP TABLE IF EXISTS `industries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `industries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `translations` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `industries`
--

LOCK TABLES `industries` WRITE;
/*!40000 ALTER TABLE `industries` DISABLE KEYS */;
/*!40000 ALTER TABLE `industries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issues`
--

DROP TABLE IF EXISTS `issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `issues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `process_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `project_stage_id` bigint unsigned DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `parent_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('ISSUE','IMPROVEMENT') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IMPROVEMENT',
  `time_unit` enum('WEEK','MONTH','TOTAL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TOTAL',
  `time_value` bigint NOT NULL DEFAULT '0',
  `time_total` bigint NOT NULL DEFAULT '0',
  `money_unit` enum('WEEK','MONTH','TOTAL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TOTAL',
  `money_value` bigint NOT NULL DEFAULT '0',
  `money_total` bigint NOT NULL DEFAULT '0',
  `total_value` bigint NOT NULL DEFAULT '0',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `anonymous_idea` tinyint(1) NOT NULL DEFAULT '0',
  `checked_issue` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `issues_uuid_unique` (`uuid`),
  KEY `issues_company_id_foreign` (`company_id`),
  KEY `issues_author_id_foreign` (`author_id`),
  KEY `issues_process_id_foreign` (`process_id`),
  KEY `issues_project_id_foreign` (`project_id`),
  KEY `issues_project_stage_id_foreign` (`project_stage_id`),
  KEY `issues_type_index` (`type`),
  CONSTRAINT `issues_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `issues_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `issues_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `issues_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `issues_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issues`
--

LOCK TABLES `issues` WRITE;
/*!40000 ALTER TABLE `issues` DISABLE KEYS */;
/*!40000 ALTER TABLE `issues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_11_000000_create_currencies_table',1),(2,'2014_10_11_000000_create_industries_table',1),(3,'2014_10_11_000002_create_tools_table',1),(4,'2014_10_12_000000_create_companies_table',1),(5,'2014_10_12_000001_create_company_roles_table',1),(6,'2018_00_00_00001_create_resources_tables',1),(7,'2018_08_08_100000_create_telescope_entries_table',1),(8,'2019_08_19_000000_create_failed_jobs_table',1),(9,'2019_08_19_000000_create_password_resets_table',1),(10,'2019_08_20_000000_create_users_table',1),(11,'2019_11_09_020803_create_auth_tokens',1),(12,'2020_02_19_174843_create_websockets_statistics_entries_table',1),(13,'2020_02_19_183324_create_jobs_table',1),(14,'2020_02_24_012412_create_processes_table',1),(15,'2020_02_24_012511_create_process_stages_table',1),(16,'2020_02_24_012642_create_process_operations_table',1),(17,'2020_02_24_013221_create_process_phases_table',1),(18,'2020_02_24_013531_create_company_tools_table',1),(19,'2020_02_24_013532_create_company_tools_prices_table',1),(20,'2020_02_24_013613_create_ideas_table',1),(21,'2020_02_24_013613_create_projects_table',1),(22,'2020_02_24_013614_create_project_stages_table',1),(23,'2020_02_24_013614_create_project_tools_table',1),(24,'2020_02_24_013614_create_project_users_table',1),(25,'2020_02_24_013615_create_project_ideas_table',1),(26,'2020_03_07_033558_create_permission_tables',1),(27,'2020_03_07_165021_create_tokens_table',1),(28,'2020_03_07_180933_create_sessions_table',1),(29,'2020_03_27_032345_create_project_evaluation_instances_table',1),(30,'2020_03_27_033528_create_project_evaluation_records_table',1),(31,'2020_03_27_041935_create_issues_table',1),(32,'2020_03_27_061720_create_model_has_company_roles_table',1),(33,'2020_04_07_072133_create_idea_issues_table',1),(34,'2020_08_24_122602_create_users_devices',1),(35,'2020_09_12_111250_update_resource_mime_length',1),(36,'2021_03_17_065541_remove_title_from_issue',1),(37,'2021_03_22_145403_drop_unique_project_id_constraint_from_project_ideas',1),(38,'2021_03_23_130618_create_notifications_table',1),(39,'2021_04_08_193938_ideas_anonymous_ideas',1),(40,'2021_04_09_161816_ideas_issues_anonymous_ideas',1),(41,'2021_04_09_174900_issues_anonymous_ideas',1),(42,'2021_04_17_174614_user_notification_preferences',1),(43,'2021_05_05_044812_create_issues_checked',1),(44,'2021_05_28_072620_create_projects_issue_evaluation_users',1),(45,'2021_06_02_035633_create_milestones_table',1),(46,'2021_06_02_075424_create_milestone_users_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `milestone_users`
--

DROP TABLE IF EXISTS `milestone_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `milestone_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `engage_score` bigint NOT NULL DEFAULT '0',
  `milestone_id` bigint unsigned DEFAULT NULL,
  `rewarded` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `milestone_users_user_id_unique` (`user_id`),
  KEY `milestone_users_company_id_foreign` (`company_id`),
  KEY `milestone_users_milestone_id_foreign` (`milestone_id`),
  CONSTRAINT `milestone_users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `milestone_users_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `milestone_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `milestone_users`
--

LOCK TABLES `milestone_users` WRITE;
/*!40000 ALTER TABLE `milestone_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `milestone_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `milestones`
--

DROP TABLE IF EXISTS `milestones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `milestones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `reward` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required_score` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `milestones_company_id_foreign` (`company_id`),
  KEY `milestones_title_index` (`title`),
  KEY `milestones_reward_index` (`reward`),
  CONSTRAINT `milestones_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `milestones`
--

LOCK TABLES `milestones` WRITE;
/*!40000 ALTER TABLE `milestones` DISABLE KEYS */;
/*!40000 ALTER TABLE `milestones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_company_roles`
--

DROP TABLE IF EXISTS `model_has_company_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_company_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `model_has_company_roles_uq_owner` (`model_type`,`model_id`,`company_role_id`),
  KEY `model_has_company_roles_company_role_id_foreign` (`company_role_id`),
  CONSTRAINT `model_has_company_roles_company_role_id_foreign` FOREIGN KEY (`company_role_id`) REFERENCES `company_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_company_roles`
--

LOCK TABLES `model_has_company_roles` WRITE;
/*!40000 ALTER TABLE `model_has_company_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_company_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'user',1),(2,'user',2);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'core/industry/create','web','2021-06-05 09:29:37','2021-06-05 09:29:37'),(2,'core/industry/update','web','2021-06-05 09:29:37','2021-06-05 09:29:37'),(3,'core/industry/delete','web','2021-06-05 09:29:37','2021-06-05 09:29:37'),(4,'core/industry/read','web','2021-06-05 09:29:38','2021-06-05 09:29:38'),(5,'core/industry/readAll','web','2021-06-05 09:29:38','2021-06-05 09:29:38'),(6,'core/industry/manage','web','2021-06-05 09:29:38','2021-06-05 09:29:38'),(7,'core/currency/create','web','2021-06-05 09:29:39','2021-06-05 09:29:39'),(8,'core/currency/update','web','2021-06-05 09:29:39','2021-06-05 09:29:39'),(9,'core/currency/delete','web','2021-06-05 09:29:39','2021-06-05 09:29:39'),(10,'core/currency/read','web','2021-06-05 09:29:39','2021-06-05 09:29:39'),(11,'core/currency/readAll','web','2021-06-05 09:29:39','2021-06-05 09:29:39'),(12,'core/currency/manage','web','2021-06-05 09:29:40','2021-06-05 09:29:40'),(13,'core/tool/create','web','2021-06-05 09:29:40','2021-06-05 09:29:40'),(14,'core/tool/update','web','2021-06-05 09:29:40','2021-06-05 09:29:40'),(15,'core/tool/delete','web','2021-06-05 09:29:41','2021-06-05 09:29:41'),(16,'core/tool/read','web','2021-06-05 09:29:41','2021-06-05 09:29:41'),(17,'core/tool/readAll','web','2021-06-05 09:29:41','2021-06-05 09:29:41'),(18,'core/tool/manage','web','2021-06-05 09:29:42','2021-06-05 09:29:42'),(19,'auth/role/create','web','2021-06-05 09:29:42','2021-06-05 09:29:42'),(20,'auth/role/update','web','2021-06-05 09:29:42','2021-06-05 09:29:42'),(21,'auth/role/delete','web','2021-06-05 09:29:42','2021-06-05 09:29:42'),(22,'auth/role/read','web','2021-06-05 09:29:42','2021-06-05 09:29:42'),(23,'auth/role/readAll','web','2021-06-05 09:29:42','2021-06-05 09:29:42'),(24,'auth/role/manage','web','2021-06-05 09:29:43','2021-06-05 09:29:43'),(25,'auth/user/create','web','2021-06-05 09:29:43','2021-06-05 09:29:43'),(26,'auth/user/update','web','2021-06-05 09:29:43','2021-06-05 09:29:43'),(27,'auth/user/delete','web','2021-06-05 09:29:43','2021-06-05 09:29:43'),(28,'auth/user/read','web','2021-06-05 09:29:43','2021-06-05 09:29:43'),(29,'auth/user/readAll','web','2021-06-05 09:29:43','2021-06-05 09:29:43'),(30,'auth/user/manage','web','2021-06-05 09:29:44','2021-06-05 09:29:44'),(31,'auth/user/reset_password','web','2021-06-05 09:29:44','2021-06-05 09:29:44'),(32,'auth/user/edit_my_company','web','2021-06-05 09:29:44','2021-06-05 09:29:44'),(33,'core/companyRole/create','web','2021-06-05 09:29:44','2021-06-05 09:29:44'),(34,'core/companyRole/update','web','2021-06-05 09:29:44','2021-06-05 09:29:44'),(35,'core/companyRole/delete','web','2021-06-05 09:29:44','2021-06-05 09:29:44'),(36,'core/companyRole/read','web','2021-06-05 09:29:44','2021-06-05 09:29:44'),(37,'core/companyRole/readAll','web','2021-06-05 09:29:45','2021-06-05 09:29:45'),(38,'core/companyRole/manage','web','2021-06-05 09:29:45','2021-06-05 09:29:45'),(39,'core/companyTool/create','web','2021-06-05 09:29:45','2021-06-05 09:29:45'),(40,'core/companyTool/update','web','2021-06-05 09:29:45','2021-06-05 09:29:45'),(41,'core/companyTool/delete','web','2021-06-05 09:29:46','2021-06-05 09:29:46'),(42,'core/companyTool/read','web','2021-06-05 09:29:46','2021-06-05 09:29:46'),(43,'core/companyTool/readAll','web','2021-06-05 09:29:46','2021-06-05 09:29:46'),(44,'core/companyTool/manage','web','2021-06-05 09:29:47','2021-06-05 09:29:47'),(45,'process/process/create','web','2021-06-05 09:29:47','2021-06-05 09:29:47'),(46,'process/process/update','web','2021-06-05 09:29:47','2021-06-05 09:29:47'),(47,'process/process/delete','web','2021-06-05 09:29:47','2021-06-05 09:29:47'),(48,'process/process/read','web','2021-06-05 09:29:48','2021-06-05 09:29:48'),(49,'process/process/readAll','web','2021-06-05 09:29:48','2021-06-05 09:29:48'),(50,'process/process/manage','web','2021-06-05 09:29:48','2021-06-05 09:29:48'),(51,'improve/idea/create','web','2021-06-05 09:29:49','2021-06-05 09:29:49'),(52,'improve/idea/update','web','2021-06-05 09:29:49','2021-06-05 09:29:49'),(53,'improve/idea/delete','web','2021-06-05 09:29:49','2021-06-05 09:29:49'),(54,'improve/idea/read','web','2021-06-05 09:29:49','2021-06-05 09:29:49'),(55,'improve/idea/readAll','web','2021-06-05 09:29:50','2021-06-05 09:29:50'),(56,'improve/idea/manage','web','2021-06-05 09:29:51','2021-06-05 09:29:51'),(57,'core/project/create','web','2021-06-05 09:29:51','2021-06-05 09:29:51'),(58,'core/project/update','web','2021-06-05 09:29:52','2021-06-05 09:29:52'),(59,'core/project/delete','web','2021-06-05 09:29:52','2021-06-05 09:29:52'),(60,'core/project/read','web','2021-06-05 09:29:52','2021-06-05 09:29:52'),(61,'core/project/readAll','web','2021-06-05 09:29:53','2021-06-05 09:29:53'),(62,'core/project/manage','web','2021-06-05 09:29:53','2021-06-05 09:29:53'),(63,'core/company/create','web','2021-06-05 09:29:54','2021-06-05 09:29:54'),(64,'core/company/update','web','2021-06-05 09:29:54','2021-06-05 09:29:54'),(65,'core/company/delete','web','2021-06-05 09:29:54','2021-06-05 09:29:54'),(66,'core/company/read','web','2021-06-05 09:29:54','2021-06-05 09:29:54'),(67,'core/company/readAll','web','2021-06-05 09:29:55','2021-06-05 09:29:55'),(68,'core/company/manage','web','2021-06-05 09:29:55','2021-06-05 09:29:55');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process_operations`
--

DROP TABLE IF EXISTS `process_operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `process_operations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `process_id` bigint unsigned NOT NULL,
  `stage_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `d_order` int unsigned NOT NULL DEFAULT '1',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `process_operations_company_id_foreign` (`company_id`),
  KEY `process_operations_author_id_foreign` (`author_id`),
  KEY `process_operations_process_id_foreign` (`process_id`),
  KEY `process_operations_stage_id_foreign` (`stage_id`),
  KEY `process_operations_title_index` (`title`),
  CONSTRAINT `process_operations_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `process_operations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `process_operations_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `process_operations_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `process_stages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process_operations`
--

LOCK TABLES `process_operations` WRITE;
/*!40000 ALTER TABLE `process_operations` DISABLE KEYS */;
/*!40000 ALTER TABLE `process_operations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process_phases`
--

DROP TABLE IF EXISTS `process_phases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `process_phases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `process_id` bigint unsigned NOT NULL,
  `operation_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `d_order` int unsigned NOT NULL DEFAULT '1',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `process_phases_company_id_foreign` (`company_id`),
  KEY `process_phases_author_id_foreign` (`author_id`),
  KEY `process_phases_process_id_foreign` (`process_id`),
  KEY `process_phases_operation_id_foreign` (`operation_id`),
  KEY `process_phases_title_index` (`title`),
  CONSTRAINT `process_phases_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `process_phases_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `process_phases_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `process_operations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `process_phases_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process_phases`
--

LOCK TABLES `process_phases` WRITE;
/*!40000 ALTER TABLE `process_phases` DISABLE KEYS */;
/*!40000 ALTER TABLE `process_phases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process_stages`
--

DROP TABLE IF EXISTS `process_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `process_stages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `process_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `d_order` int unsigned NOT NULL DEFAULT '1',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `process_stages_company_id_foreign` (`company_id`),
  KEY `process_stages_author_id_foreign` (`author_id`),
  KEY `process_stages_process_id_foreign` (`process_id`),
  KEY `process_stages_title_index` (`title`),
  CONSTRAINT `process_stages_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `process_stages_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `process_stages_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process_stages`
--

LOCK TABLES `process_stages` WRITE;
/*!40000 ALTER TABLE `process_stages` DISABLE KEYS */;
/*!40000 ALTER TABLE `process_stages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `processes`
--

DROP TABLE IF EXISTS `processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `processes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `d_order` int unsigned NOT NULL DEFAULT '1',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `processes_title_company_id_unique` (`title`,`company_id`),
  KEY `processes_company_id_foreign` (`company_id`),
  KEY `processes_author_id_foreign` (`author_id`),
  KEY `processes_title_index` (`title`),
  CONSTRAINT `processes_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `processes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `processes`
--

LOCK TABLES `processes` WRITE;
/*!40000 ALTER TABLE `processes` DISABLE KEYS */;
/*!40000 ALTER TABLE `processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_evaluation_instances`
--

DROP TABLE IF EXISTS `project_evaluation_instances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_evaluation_instances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `process_id` bigint unsigned DEFAULT NULL,
  `project_id` bigint unsigned NOT NULL,
  `project_stage_id` bigint unsigned NOT NULL,
  `status` enum('OPEN','CLOSED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OPEN',
  `started_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ends_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `evaluation_period_start` datetime DEFAULT NULL,
  `evaluation_period_end` datetime DEFAULT NULL,
  `total_gains` bigint NOT NULL DEFAULT '0',
  `total_losses` bigint NOT NULL DEFAULT '0',
  `consolidated_value` bigint NOT NULL DEFAULT '0',
  `total_evaluations` int unsigned NOT NULL DEFAULT '0',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_evaluation_instances_company_id_foreign` (`company_id`),
  KEY `project_evaluation_instances_process_id_foreign` (`process_id`),
  KEY `project_evaluation_instances_project_id_foreign` (`project_id`),
  KEY `project_evaluation_instances_project_stage_id_foreign` (`project_stage_id`),
  CONSTRAINT `project_evaluation_instances_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_instances_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_instances_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_instances_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_evaluation_instances`
--

LOCK TABLES `project_evaluation_instances` WRITE;
/*!40000 ALTER TABLE `project_evaluation_instances` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_evaluation_instances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_evaluation_records`
--

DROP TABLE IF EXISTS `project_evaluation_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_evaluation_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `process_id` bigint unsigned NOT NULL,
  `process_stage_id` bigint unsigned NOT NULL,
  `project_idea_id` bigint unsigned NOT NULL,
  `project_user_id` bigint unsigned NOT NULL,
  `idea_id` bigint unsigned NOT NULL,
  `evaluation_instance_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned NOT NULL,
  `project_stage_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `status` enum('PENDING','COMPLETED','SKIPPED','MISSING') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `time_unit` enum('WEEK','MONTH','TOTAL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TOTAL',
  `time_value` bigint NOT NULL DEFAULT '0',
  `time_total` bigint NOT NULL DEFAULT '0',
  `money_unit` enum('WEEK','MONTH','TOTAL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TOTAL',
  `money_value` bigint NOT NULL DEFAULT '0',
  `money_total` bigint NOT NULL DEFAULT '0',
  `total_value` bigint NOT NULL DEFAULT '0',
  `author_yearly_costs` bigint NOT NULL DEFAULT '0',
  `yearly_costs_money` bigint NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `properties` json DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_evaluation_records_company_id_foreign` (`company_id`),
  KEY `project_evaluation_records_process_id_foreign` (`process_id`),
  KEY `project_evaluation_records_process_stage_id_foreign` (`process_stage_id`),
  KEY `project_evaluation_records_project_idea_id_foreign` (`project_idea_id`),
  KEY `project_evaluation_records_project_user_id_foreign` (`project_user_id`),
  KEY `project_evaluation_records_idea_id_foreign` (`idea_id`),
  KEY `project_evaluation_records_evaluation_instance_id_foreign` (`evaluation_instance_id`),
  KEY `project_evaluation_records_project_id_foreign` (`project_id`),
  KEY `project_evaluation_records_project_stage_id_foreign` (`project_stage_id`),
  KEY `project_evaluation_records_author_id_foreign` (`author_id`),
  KEY `project_evaluation_records_status_index` (`status`),
  CONSTRAINT `project_evaluation_records_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_records_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_records_evaluation_instance_id_foreign` FOREIGN KEY (`evaluation_instance_id`) REFERENCES `project_evaluation_instances` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_records_idea_id_foreign` FOREIGN KEY (`idea_id`) REFERENCES `ideas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_records_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_records_process_stage_id_foreign` FOREIGN KEY (`process_stage_id`) REFERENCES `process_stages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_records_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_records_project_idea_id_foreign` FOREIGN KEY (`project_idea_id`) REFERENCES `project_ideas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_records_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_evaluation_records_project_user_id_foreign` FOREIGN KEY (`project_user_id`) REFERENCES `project_users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_evaluation_records`
--

LOCK TABLES `project_evaluation_records` WRITE;
/*!40000 ALTER TABLE `project_evaluation_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_evaluation_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_ideas`
--

DROP TABLE IF EXISTS `project_ideas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_ideas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned NOT NULL,
  `project_stage_id` bigint unsigned NOT NULL,
  `idea_id` bigint unsigned NOT NULL,
  `process_id` bigint unsigned NOT NULL,
  `process_stage_id` bigint unsigned NOT NULL,
  `total_gains` bigint NOT NULL DEFAULT '0',
  `total_losses` bigint NOT NULL DEFAULT '0',
  `consolidated_value` bigint NOT NULL DEFAULT '0',
  `total_evaluations` bigint NOT NULL DEFAULT '0',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_ideas_company_id_foreign` (`company_id`),
  KEY `project_ideas_project_stage_id_foreign` (`project_stage_id`),
  KEY `project_ideas_idea_id_foreign` (`idea_id`),
  KEY `project_ideas_process_id_foreign` (`process_id`),
  KEY `project_ideas_process_stage_id_foreign` (`process_stage_id`),
  CONSTRAINT `project_ideas_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_ideas_idea_id_foreign` FOREIGN KEY (`idea_id`) REFERENCES `ideas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_ideas_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_ideas_process_stage_id_foreign` FOREIGN KEY (`process_stage_id`) REFERENCES `process_stages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_ideas_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_ideas`
--

LOCK TABLES `project_ideas` WRITE;
/*!40000 ALTER TABLE `project_ideas` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_ideas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_stages`
--

DROP TABLE IF EXISTS `project_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_stages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `process_id` bigint unsigned NOT NULL,
  `stage_id` bigint unsigned NOT NULL,
  `d_order` bigint unsigned NOT NULL,
  `status` enum('NOT_STARTED','STARTED','EVALUATIONS_PENDING','FINISHED','TRASHED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'STARTED',
  `next_schedule_process` datetime DEFAULT NULL,
  `total_gains` bigint NOT NULL DEFAULT '0',
  `total_losses` bigint NOT NULL DEFAULT '0',
  `consolidated_value` bigint NOT NULL DEFAULT '0',
  `total_evaluations` bigint NOT NULL DEFAULT '0',
  `started_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_stages_project_id_foreign` (`project_id`),
  KEY `project_stages_process_id_foreign` (`process_id`),
  KEY `project_stages_stage_id_foreign` (`stage_id`),
  CONSTRAINT `project_stages_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_stages_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_stages_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `process_stages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_stages`
--

LOCK TABLES `project_stages` WRITE;
/*!40000 ALTER TABLE `project_stages` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_stages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_tools`
--

DROP TABLE IF EXISTS `project_tools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_tools` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `tool_id` bigint unsigned NOT NULL,
  `project_stage_id` bigint unsigned NOT NULL,
  `company_tool_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned NOT NULL,
  `total_gains` bigint NOT NULL DEFAULT '0',
  `total_losses` bigint NOT NULL DEFAULT '0',
  `consolidated_value` bigint NOT NULL DEFAULT '0',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_tools_company_id_foreign` (`company_id`),
  KEY `project_tools_tool_id_foreign` (`tool_id`),
  KEY `project_tools_project_stage_id_foreign` (`project_stage_id`),
  KEY `project_tools_company_tool_id_foreign` (`company_tool_id`),
  KEY `project_tools_project_id_foreign` (`project_id`),
  CONSTRAINT `project_tools_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_tools_company_tool_id_foreign` FOREIGN KEY (`company_tool_id`) REFERENCES `company_tools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_tools_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_tools_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_tools_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_tools`
--

LOCK TABLES `project_tools` WRITE;
/*!40000 ALTER TABLE `project_tools` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_tools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_users`
--

DROP TABLE IF EXISTS `project_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `project_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `total_gains` bigint NOT NULL DEFAULT '0',
  `total_losses` bigint NOT NULL DEFAULT '0',
  `consolidated_value` bigint NOT NULL DEFAULT '0',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_users_project_id_user_id_unique` (`project_id`,`user_id`),
  KEY `project_users_company_id_foreign` (`company_id`),
  KEY `project_users_user_id_foreign` (`user_id`),
  CONSTRAINT `project_users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_users_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_users`
--

LOCK TABLES `project_users` WRITE;
/*!40000 ALTER TABLE `project_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  `process_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `budget` bigint unsigned DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL,
  `evaluation_type` enum('STAGE_FINISH','PERIODIC') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'STAGE_FINISH',
  `evaluation_interval_amount` int unsigned DEFAULT NULL,
  `evaluation_interval_unit` enum('DAYS','WEEKS','MONTHS','YEARS') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('NORMAL','ON_GOING') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NORMAL',
  `status` enum('NOT_STARTED','STARTED','EVALUATIONS_PENDING','FINISHED','TRASHED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NOT_STARTED',
  `total_gains` bigint NOT NULL DEFAULT '0',
  `total_losses` bigint NOT NULL DEFAULT '0',
  `consolidated_value` bigint NOT NULL DEFAULT '0',
  `total_evaluations` bigint NOT NULL DEFAULT '0',
  `d_order` int unsigned NOT NULL DEFAULT '1',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `issue_evaluation_roles` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_process_id_name_unique` (`process_id`,`name`),
  KEY `projects_company_id_foreign` (`company_id`),
  KEY `projects_author_id_foreign` (`author_id`),
  KEY `projects_name_index` (`name`),
  KEY `projects_evaluation_type_index` (`evaluation_type`),
  KEY `projects_type_index` (`type`),
  KEY `projects_status_index` (`status`),
  CONSTRAINT `projects_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `projects_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `projects_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resource_collections`
--

DROP TABLE IF EXISTS `resource_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_collections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `collection_type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resource_collections_uuid_unique` (`uuid`),
  KEY `resource_collections_owner_type_owner_id_index` (`owner_type`,`owner_id`),
  KEY `resource_collections_collection_type_index` (`collection_type`),
  KEY `resource_collections_owner_type_index` (`owner_type`),
  KEY `resource_collections_owner_id_index` (`owner_id`),
  KEY `resource_collections_section_id_index` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resource_collections`
--

LOCK TABLES `resource_collections` WRITE;
/*!40000 ALTER TABLE `resource_collections` DISABLE KEYS */;
/*!40000 ALTER TABLE `resource_collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_type` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_id` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `size` bigint NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `visibility` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resources_uuid_unique` (`uuid`),
  KEY `resources_owner_type_owner_id_index` (`owner_type`,`owner_id`),
  KEY `resources_type_index` (`type`),
  KEY `resources_display_type_index` (`display_type`),
  KEY `resources_owner_type_index` (`owner_type`),
  KEY `resources_owner_id_index` (`owner_id`),
  KEY `resources_mime_type_index` (`mime_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resources`
--

LOCK TABLES `resources` WRITE;
/*!40000 ALTER TABLE `resources` DISABLE KEYS */;
/*!40000 ALTER TABLE `resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(16,2),(17,2),(18,2),(22,2),(23,2),(25,2),(26,2),(27,2),(28,2),(29,2),(30,2),(31,2),(32,2),(33,2),(34,2),(35,2),(36,2),(37,2),(38,2),(39,2),(40,2),(41,2),(42,2),(43,2),(44,2),(45,2),(46,2),(47,2),(48,2),(49,2),(50,2),(51,2),(52,2),(53,2),(54,2),(55,2),(56,2),(57,2),(58,2),(59,2),(60,2),(61,2),(62,2),(1,3),(2,3),(3,3),(4,3),(5,3),(6,3),(7,3),(8,3),(9,3),(10,3),(11,3),(12,3),(13,3),(14,3),(15,3),(16,3),(17,3),(18,3),(22,3),(23,3),(25,3),(26,3),(27,3),(28,3),(29,3),(31,3),(33,3),(34,3),(35,3),(36,3),(37,3),(39,3),(40,3),(41,3),(42,3),(43,3),(44,3),(45,3),(46,3),(47,3),(48,3),(49,3),(50,3),(51,3),(52,3),(53,3),(54,3),(55,3),(56,3),(57,3),(58,3),(59,3),(60,3),(61,3),(62,3),(1,4),(2,4),(3,4),(4,4),(5,4),(6,4),(7,4),(8,4),(9,4),(10,4),(11,4),(12,4),(13,4),(14,4),(15,4),(16,4),(17,4),(18,4),(22,4),(23,4),(28,4),(29,4),(36,4),(37,4),(42,4),(43,4),(48,4),(49,4),(51,4),(52,4),(54,4),(55,4),(56,4),(60,4),(61,4);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','web','2021-06-05 09:29:37','2021-06-05 09:29:37'),(2,'Company Admin','web','2021-06-05 09:29:37','2021-06-05 09:29:37'),(3,'Company Manager','web','2021-06-05 09:29:37','2021-06-05 09:29:37'),(4,'User','web','2021-06-05 09:29:37','2021-06-05 09:29:37');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` json DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_entries`
--

DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries` (
  `sequence` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_entries`
--

LOCK TABLES `telescope_entries` WRITE;
/*!40000 ALTER TABLE `telescope_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_entries_tags`
--

DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_entries_tags`
--

LOCK TABLES `telescope_entries_tags` WRITE;
/*!40000 ALTER TABLE `telescope_entries_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_monitoring`
--

DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_monitoring`
--

LOCK TABLES `telescope_monitoring` WRITE;
/*!40000 ALTER TABLE `telescope_monitoring` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_monitoring` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `properties` json DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tokens`
--

LOCK TABLES `tokens` WRITE;
/*!40000 ALTER TABLE `tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tools`
--

DROP TABLE IF EXISTS `tools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tools` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `translations` json DEFAULT NULL,
  `type` enum('TOOL','MODULE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tool_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tools_tool_id_foreign` (`tool_id`),
  KEY `tools_type_index` (`type`),
  CONSTRAINT `tools_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tools`
--

LOCK TABLES `tools` WRITE;
/*!40000 ALTER TABLE `tools` DISABLE KEYS */;
/*!40000 ALTER TABLE `tools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_devices`
--

DROP TABLE IF EXISTS `user_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_devices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('android','ios','web') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_devices_user_id_foreign` (`user_id`),
  CONSTRAINT `user_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_devices`
--

LOCK TABLES `user_devices` WRITE;
/*!40000 ALTER TABLE `user_devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `status` enum('PENDING','ACTIVE','INACTIVE','TRASHED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '0',
  `must_change_password` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint unsigned DEFAULT NULL,
  `company_role_id` bigint unsigned DEFAULT NULL,
  `yearly_costs` bigint unsigned NOT NULL DEFAULT '0',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `total_gains` bigint NOT NULL DEFAULT '0',
  `total_losses` bigint NOT NULL DEFAULT '0',
  `consolidated_value` bigint NOT NULL DEFAULT '0',
  `total_evaluations` int unsigned NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notifications` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_company_id_foreign` (`company_id`),
  KEY `users_company_role_id_foreign` (`company_role_id`),
  KEY `users_first_name_index` (`first_name`),
  KEY `users_last_name_index` (`last_name`),
  KEY `users_status_index` (`status`),
  CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_company_role_id_foreign` FOREIGN KEY (`company_role_id`) REFERENCES `company_roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','Admin','root@devcore.test',NULL,'en','PENDING',1,0,'2021-06-05 09:29:59',NULL,'$2y$10$kTUA2k6YGkkaBLmQTwGjZOEMfO4ypO8yxxM8.rZ4jJep6W3BEMeIK',NULL,NULL,0,NULL,NULL,0,0,0,0,'u1XgS8Vryc','2021-06-05 09:29:59','2021-06-05 09:29:59',1),(2,'test company','Administrator','kristiankcodes@gmail.com',NULL,'en','PENDING',0,0,NULL,NULL,'$2y$10$9aWzm6EXLMDjOGCxWiBFreNtcLEDQs4sH5hqU2KOEECETIvihNduW',1,1,0,NULL,NULL,0,0,0,0,NULL,'2021-06-05 09:33:30','2021-06-05 09:34:05',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `websockets_statistics_entries`
--

DROP TABLE IF EXISTS `websockets_statistics_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `websockets_statistics_entries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peak_connection_count` int NOT NULL,
  `websocket_message_count` int NOT NULL,
  `api_message_count` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `websockets_statistics_entries`
--

LOCK TABLES `websockets_statistics_entries` WRITE;
/*!40000 ALTER TABLE `websockets_statistics_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `websockets_statistics_entries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-05  9:50:53
