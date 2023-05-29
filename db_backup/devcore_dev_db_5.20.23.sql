/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100406 (10.4.6-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : devcore_dev_db

 Target Server Type    : MySQL
 Target Server Version : 100406 (10.4.6-MariaDB)
 File Encoding         : 65001

 Date: 20/05/2023 04:31:04
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for auth_tokens
-- ----------------------------
DROP TABLE IF EXISTS `auth_tokens`;
CREATE TABLE `auth_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `access_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `refresh_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extended_ttl` tinyint(1) NOT NULL DEFAULT 0,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `client` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  `revoked_at` datetime NULL DEFAULT NULL,
  `refresh_token_expires_at` datetime NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `auth_tokens_access_token_unique`(`access_token` ASC) USING BTREE,
  UNIQUE INDEX `auth_tokens_refresh_token_unique`(`refresh_token` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_tokens
-- ----------------------------
INSERT INTO `auth_tokens` VALUES (1, 'IcuSBPG8IU2Bf10CjvcioPCVKMBhhByfZhhgKruYpWa7klQPOuH2rWle2J2PmzzuUuLjUKV27YFJMG6kADN7T8IxbmRqSgevPAQ8', 'hMHKU02aX2mfUxMZKjcYFy9vNtsgr623cgncTBIEpGmU0GGPAm3IAm0y0uC46W3bLyFiq54XGIMbfw27m4CriD6ppBaGXCpn6No9', 0, 'user', '1', 'web:1.0', '2021-06-05 10:33:48', '2021-06-05 09:33:57', '2022-06-06 09:33:48', '{\"ip\": \"192.168.10.1\", \"user-agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36\"}', '2021-06-05 18:33:05', '2021-06-05 18:33:57');
INSERT INTO `auth_tokens` VALUES (2, 'HX723v06dM2Rm9B5tUVLnf1KuDGqZP4iOZSKnYr4DbXcWZxqRjDEaWuvfHfgiBDo7RYs2bHGRMBGz0BPKUArqs8bbdCQJj1fMyRJ', 'Je2DJTWsHxFikxfUx3Y6oOzGKROVie3OPsvoLoQHX2UNjvxQ3pPpoJwgPVKn2VlZdbKeWBUH6lEVtdR4QCLVOaoxszd3CNkJp9cw', 0, 'user', '2', 'web:1.0', '2021-06-05 10:34:11', '2021-06-05 09:34:12', '2022-06-06 09:34:11', '{\"ip\": \"192.168.10.1\", \"user-agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36\"}', '2021-06-05 18:33:57', '2021-06-05 18:34:12');
INSERT INTO `auth_tokens` VALUES (3, 'YoGpn9mvPoqw8sDUpYUPorYKsAWgDgaE2tAaVUmC0CtdVqAx4Ejq2Aarjsya9jX9BVnrCEHlMDZbxxaemsew4my9P5eDHjR4dXXr', '1bMZTV4lwkAyEXCTMJLMwVvrfJGBsp0WwyMrMmtjA9pSZlcbSB9iPaJNel9w5DIlLfMp4yqPTGvUmPFN99EHlpyk34rPKPyU8J6C', 1, 'user', '1', 'mobile:1.0.0', '2023-06-18 19:20:22', NULL, '2024-05-19 19:20:22', '{\"ip\":\"127.0.0.1\",\"user-agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/113.0.0.0 Safari\\/537.36\"}', '2023-05-20 04:20:22', '2023-05-20 04:20:22');
INSERT INTO `auth_tokens` VALUES (4, '1eVs6PhESsFoy1WW8DP3DN78tFwfe0QTCRSzuv1OWX0MJkzC9JyNlhA2JIH2UEC39oWqkVDiSYgy9U6JDgyXe37Ena4u930rr0Lm', 'rl7USvN1uNa65bMU7YbQv0r6EuxJImLbjOWB6LJxfXw8NsfKDXTod2u8mCZ0HGavmfjkrrydrXaJedhvr8fj9Ce3MXndxK1w2WPW', 1, 'user', '1', 'mobile:1.0.0', '2023-06-18 19:21:20', NULL, '2024-05-19 19:21:20', '{\"ip\":\"127.0.0.1\",\"user-agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/113.0.0.0 Safari\\/537.36\"}', '2023-05-20 04:21:20', '2023-05-20 04:21:20');
INSERT INTO `auth_tokens` VALUES (5, 'Jf7bOuPutkdEaGp94wGiDTArY2taCEXHrxqQxl3qAOYpQvSCfbU9GDLVGYt8na4zEU0ri7zYocWrqQmro0lWdKH8kzhjEgFKDN1I', 'yyoLEeQE6u80B6KYgAQ8hS3ZpkrpFofSUpJoZAXksllgvvQZXdRy7CN2BilSPFpQHjq1Hg7r4tQUXayQMqqvpORXKlvojU9a4ZRF', 1, 'user', '1', 'mobile:1.0.0', '2023-06-18 19:25:20', NULL, '2024-05-19 19:25:20', '{\"ip\":\"127.0.0.1\",\"user-agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/113.0.0.0 Safari\\/537.36\"}', '2023-05-20 04:25:20', '2023-05-20 04:25:20');
INSERT INTO `auth_tokens` VALUES (6, 'WnOZc9MvAFtflN5VxxWLvTO55QpaaM3EouBAVKSkp8Fc2IW5DXua9lRptcqtgjS7cSKtqOnrkOhIgEQIuxFLyaNrnLoxaqjf14pT', 'ik9mhEmmFnxhACCz6RrE7jQ1zI6sfycA7SmJtnIpHGpw5rZuahGSjYeC6JKWsE1Ud0mY5xdi13EJunm8TyGWpoHBt3tpqZp1z1iG', 1, 'user', '1', 'mobile:1.0.0', '2023-06-18 19:30:07', NULL, '2024-05-19 19:30:07', '{\"ip\":\"127.0.0.1\",\"user-agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/113.0.0.0 Safari\\/537.36\"}', '2023-05-20 04:29:39', '2023-05-20 04:30:07');

-- ----------------------------
-- Table structure for companies
-- ----------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `default_lang` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `industry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `currency_code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'EUR',
  `status` enum('ACTIVE','INACTIVE','TRASHED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `companies_industry_id_foreign`(`industry_id` ASC) USING BTREE,
  INDEX `companies_currency_code_foreign`(`currency_code` ASC) USING BTREE,
  INDEX `companies_name_index`(`name` ASC) USING BTREE,
  INDEX `companies_status_index`(`status` ASC) USING BTREE,
  CONSTRAINT `companies_currency_code_foreign` FOREIGN KEY (`currency_code`) REFERENCES `currencies` (`code`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `companies_industry_id_foreign` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of companies
-- ----------------------------
INSERT INTO `companies` VALUES (1, 'test company', NULL, 'en', NULL, 'EUR', 'ACTIVE', NULL, '2021-06-05 18:33:30', '2021-06-05 18:33:30');

-- ----------------------------
-- Table structure for company_role_score_instances
-- ----------------------------
DROP TABLE IF EXISTS `company_role_score_instances`;
CREATE TABLE `company_role_score_instances`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_role_id` bigint UNSIGNED NULL DEFAULT NULL,
  `versus_role_id` bigint UNSIGNED NULL DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `consolidated_value` bigint NOT NULL DEFAULT 0,
  `versus_period_start` datetime NULL DEFAULT NULL,
  `versus_period_end` datetime NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of company_role_score_instances
-- ----------------------------

-- ----------------------------
-- Table structure for company_roles
-- ----------------------------
DROP TABLE IF EXISTS `company_roles`;
CREATE TABLE `company_roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint UNSIGNED NULL DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_roles_company_id_name_unique`(`company_id` ASC, `name` ASC) USING BTREE,
  CONSTRAINT `company_roles_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of company_roles
-- ----------------------------
INSERT INTO `company_roles` VALUES (1, 'Management', 1, NULL, NULL, '2021-06-05 18:33:30', '2021-06-05 18:33:30');

-- ----------------------------
-- Table structure for company_tool_prices
-- ----------------------------
DROP TABLE IF EXISTS `company_tool_prices`;
CREATE TABLE `company_tool_prices`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_tool_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `price_model` enum('PROJECT','LICENSE','ONE_TIME_PAYMENT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LICENSE',
  `price_frequency` int UNSIGNED NOT NULL DEFAULT 1,
  `price_interval` enum('MONTH','YEAR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'YEAR',
  `price` bigint UNSIGNED NOT NULL DEFAULT 1,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `parent_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `expiration` datetime NULL DEFAULT NULL,
  `next_schedule_process` datetime NULL DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_tool_prices_company_tool_id_foreign`(`company_tool_id` ASC) USING BTREE,
  INDEX `company_tool_prices_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `company_tool_prices_tool_id_foreign`(`tool_id` ASC) USING BTREE,
  INDEX `company_tool_prices_parent_id_parent_type_index`(`parent_id` ASC, `parent_type` ASC) USING BTREE,
  INDEX `company_tool_prices_status_index`(`status` ASC) USING BTREE,
  INDEX `company_tool_prices_price_model_index`(`price_model` ASC) USING BTREE,
  INDEX `company_tool_prices_price_interval_index`(`price_interval` ASC) USING BTREE,
  CONSTRAINT `company_tool_prices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `company_tool_prices_company_tool_id_foreign` FOREIGN KEY (`company_tool_id`) REFERENCES `company_tools` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `company_tool_prices_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of company_tool_prices
-- ----------------------------

-- ----------------------------
-- Table structure for company_tools
-- ----------------------------
DROP TABLE IF EXISTS `company_tools`;
CREATE TABLE `company_tools`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `status` enum('ACTIVE','INACTIVE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `price_model` enum('PROJECT','LICENSE','ONE_TIME_PAYMENT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LICENSE',
  `costs` bigint UNSIGNED NOT NULL DEFAULT 0,
  `total_gains` bigint NOT NULL DEFAULT 0,
  `total_losses` bigint NOT NULL DEFAULT 0,
  `consolidated_value` bigint NOT NULL DEFAULT 0,
  `total_evaluations` int UNSIGNED NOT NULL DEFAULT 0,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_tools_parent_id_foreign`(`parent_id` ASC) USING BTREE,
  INDEX `company_tools_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `company_tools_tool_id_foreign`(`tool_id` ASC) USING BTREE,
  INDEX `company_tools_status_index`(`status` ASC) USING BTREE,
  INDEX `company_tools_price_model_index`(`price_model` ASC) USING BTREE,
  INDEX `company_tools_project_id_foreign`(`project_id` ASC) USING BTREE,
  CONSTRAINT `company_tools_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `company_tools_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `company_tools` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `company_tools_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `company_tools_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of company_tools
-- ----------------------------

-- ----------------------------
-- Table structure for currencies
-- ----------------------------
DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies`  (
  `code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `translations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`) USING BTREE,
  INDEX `currencies_name_index`(`name` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of currencies
-- ----------------------------
INSERT INTO `currencies` VALUES ('AED', 'Emirati Dirham', '.د.ب', NULL, '2021-06-05 18:29:55', '2021-06-05 18:29:55');
INSERT INTO `currencies` VALUES ('AFN', 'Afghan Afghani', '؋', NULL, '2021-06-05 18:29:55', '2021-06-05 18:29:55');
INSERT INTO `currencies` VALUES ('ALL', 'Albanian lek', 'lek', NULL, '2021-06-05 18:29:55', '2021-06-05 18:29:55');
INSERT INTO `currencies` VALUES ('AMD', 'Armenian dram', '', NULL, '2021-06-05 18:29:55', '2021-06-05 18:29:55');
INSERT INTO `currencies` VALUES ('ANG', 'Dutch Guilder', 'ƒ', NULL, '2021-06-05 18:29:55', '2021-06-05 18:29:55');
INSERT INTO `currencies` VALUES ('AOA', 'Angolan Kwanza', 'Kz', NULL, '2021-06-05 18:29:55', '2021-06-05 18:29:55');
INSERT INTO `currencies` VALUES ('ARS', 'Argentine peso', '$', NULL, '2021-06-05 18:29:55', '2021-06-05 18:29:55');
INSERT INTO `currencies` VALUES ('AUD', 'Australian Dollar', '$', NULL, '2021-06-05 18:29:55', '2021-06-05 18:29:55');
INSERT INTO `currencies` VALUES ('AWG', 'Arubin florin', 'ƒ', NULL, '2021-06-05 18:29:55', '2021-06-05 18:29:55');
INSERT INTO `currencies` VALUES ('AZN', 'Azerbaijani manat', 'ман', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BAM', 'Bosnian Convertible Marka', 'KM', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BBD', 'Barbadian dollar', '$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BDT', 'Bangladeshi Taka', 'Tk', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BGN', 'Bulgarian lev', 'лв', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BHD', 'Bahraini Dinar', '.د.ب', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BIF', 'Burundian Franc', '', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BMD', 'Bermudian dollar', '$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BND', 'Bruneian Dollar', '$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BOB', 'Bolivian Boliviano', '$b', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BRL', 'Brazilian real', 'R$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BSD', 'Bahamian dollar', 'B$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BTN', 'Bhutanese Ngultrum', 'Nu.', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BWP', 'Botswana Pula', 'P', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BYN', 'Belarusian ruble', 'р', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('BZD', 'Belize dollar', 'BZ$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('CAD', 'Canadian Dollar', '$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('CHF', 'Swiss Franc', 'CHF', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('CLP', 'Chilean Peso', '$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('CNY', 'Yuan or chinese renminbi', '¥', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('COP', 'Colombian peso', '$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('CRC', 'Costa Rican colón', '₡', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('CUC', 'Cuban convertible peso', '$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('CUP', 'Cuban peso', '₱', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('CVE', 'Cape Verdean Escudo', '$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('CZK', 'Czech koruna', 'Kč', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('DJF', 'Djiboutian Franc', 'fdj', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('DKK', 'Danish krone', 'kr', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('DOP', 'Dominican peso', '$', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('DZD', 'Algerian Dinar', 'جد', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('EGP', 'Egyptian Pound', '£ ', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('ERN', 'Eritrean nakfa', 'ናቕፋ', NULL, '2021-06-05 18:29:56', '2021-06-05 18:29:56');
INSERT INTO `currencies` VALUES ('ETB', 'Ethiopian Birr', 'Br', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('EUR', 'Euro', '€', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('FJD', 'Fijian dollar', '$', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('FKP', 'Falkland Island Pound', '£', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('GBP', 'British Pound', '£', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('GEL', 'Georgian lari', 'ლ', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('GHS', 'Ghanaian Cedi', 'GH¢', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('GIP', 'Gibraltar pound', '£', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('GMD', 'Gambian dalasi', '', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('GNF', 'Guinean Franc', '', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('GTQ', 'Guatemalan Quetzal', 'Q', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('GYD', 'Guyanese dollar', '$', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('HKD', 'Hong Kong dollar', 'HK$', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('HNL', 'Honduran lempira', 'L', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('HRK', 'Croatian kuna', 'kn', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('HTG', 'Haitian gourde', 'G', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('HUF', 'Hungarian forint', 'Ft', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('IDR', 'Indonesian Rupiah', 'Rp', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('ILS', 'Israeli Shekel', '₪', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('INR', 'Indian Rupee', '₹', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('IQD', 'Iraqi Dinar', 'ع.د', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('IRR', 'Iranian Rial', '', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('ISK', 'Icelandic Krona', 'kr', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('JMD', 'Jamaican dollar', 'J$', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('JOD', 'Jordanian Dinar', '', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('JPY', 'Japanese yen', '¥', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('KES', 'Kenyan Shilling', 'KSh', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('KGS', 'Kyrgyzstani som', 'лв', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('KHR', 'Cambodian Riel', '៛', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('KMF', 'Comoran Franc', '', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('KPW', 'North Korean won', '₩', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('KRW', 'South Korean won', '₩', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('KWD', 'Kuwaiti Dinar', 'ك', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('KYD', 'Caymanian Dollar', '$', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('KZT', 'Kazakhstani tenge', '₸', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('LAK', 'Lao or Laotian Kip', '₭', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('LBP', 'Lebanese Pound', 'ل.ل', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('LKR', 'Sri Lankan Rupee', 'Rs', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('LRD', 'Liberian Dollar', '$', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('LSL', 'Lesotho loti', 'L', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('LTL', 'Lithuanian litas', 'Lt', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('LYD', 'Libyan Dinar', ' د.ل', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('MAD', 'Moroccan Dirham', 'م.د.', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('MDL', 'Moldovan Leu', 'L', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('MGA', 'Malagasy Ariary', 'Ar', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('MKD', 'Macedonian Denar', 'ден', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('MMK', 'Burmese Kyat', 'K', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('MNT', 'Mongolian Tughrik', '₮', NULL, '2021-06-05 18:29:57', '2021-06-05 18:29:57');
INSERT INTO `currencies` VALUES ('MOP', 'Macau Pataca', 'MOP$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('MRO', 'Mauritanian Ouguiya', 'UM', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('MUR', 'Mauritian rupee', 'Rs', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('MVR', 'Maldivian Rufiyaa', 'rf', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('MWK', 'Malawian Kwacha', 'MK', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('MXN', 'Mexico Peso', '$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('MYR', 'Malaysian Ringgit', 'RM', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('MZN', 'Mozambican Metical', 'MT', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('NAD', 'Namibian Dollar', '$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('NGN', 'Nigerian Naira', '₦', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('NIO', 'Nicaraguan córdoba', 'C$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('NOK', 'Norwegian krone', 'kr', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('NPR', 'Nepalese Rupee', 'Rs', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('NZD', 'New Zealand Dollar', '$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('OMR', 'Omani Rial', 'ع.ر.', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('PAB', 'Balboa panamérn', 'B/', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('PEN', 'Peruvian nuevo sol', 'S/', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('PGK', 'Papua New Guinean Kina', 'K', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('PHP', 'Philippine Peso', '₱', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('PKR', 'Pakistani Rupee', 'Rs', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('PLN', 'Polish złoty', 'zł', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('PYG', 'Paraguayan guarani', '₲', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('QAR', 'Qatari Riyal', 'ق.ر ', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('RON', 'Romanian leu', 'lei', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('RSD', 'Serbian Dinar', 'РСД', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('RUB', 'Russian Rouble', '₽', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('RWF', 'Rwandan franc', 'FRw, RF, R₣', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SAR', 'Saudi Arabian Riyal', 'ر.س', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SBD', 'Solomon Islander Dollar', 'SI$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SCR', 'Seychellois Rupee', 'Rs', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SDG', 'Sudanese Pound', '', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SEK', 'Swedish krona', 'kr', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SGD', 'Singapore Dollar', '$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SLL', 'Sierra Leonean Leone', 'Le', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SOS', 'Somali Shilling', 'S', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SRD', 'Surinamese dollar', '$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SSP', 'South Sudanese pound', '£', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SYP', 'Syrian Pound', '£', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('SZL', 'Swazi Lilangeni', 'L', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('THB', 'Thai Baht', '฿', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('TJS', 'Tajikistani somoni', '', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('TMT', 'Turkmenistan manat', 'T', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('TND', 'Tunisian Dinar', '', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('TOP', 'Tongan Pa\'anga', 'T$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('TRY', 'Turkish Lira', '₺', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('TTD', 'Trinidadian dollar', 'TT$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('TWD', 'Taiwan New Dollar', 'NT$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('TZS', 'Tanzanian Shilling', 'Sh', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('UAH', 'Ukrainian Hryvnia', '₴', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('UGX', 'Ugandan Shilling', 'USh', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('USD', 'US Dollar', '$', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('UYU', 'Uruguayan peso', '$U', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('UZS', 'Uzbekistani som', 'лв', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('VEF', 'Venezuelan bolivar', 'Bs', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('VND', 'Vietnamese Dong', '₫', NULL, '2021-06-05 18:29:58', '2021-06-05 18:29:58');
INSERT INTO `currencies` VALUES ('VUV', 'Ni-Vanuatu Vatu', 'VT', NULL, '2021-06-05 18:29:59', '2021-06-05 18:29:59');
INSERT INTO `currencies` VALUES ('WST', 'Samoan Tālā', '$', NULL, '2021-06-05 18:29:59', '2021-06-05 18:29:59');
INSERT INTO `currencies` VALUES ('XCD', 'East Caribbean dollar', 'EC$', NULL, '2021-06-05 18:29:59', '2021-06-05 18:29:59');
INSERT INTO `currencies` VALUES ('XOF', 'CFA Franc', '', NULL, '2021-06-05 18:29:59', '2021-06-05 18:29:59');
INSERT INTO `currencies` VALUES ('XPF', 'CFP Franc', '', NULL, '2021-06-05 18:29:59', '2021-06-05 18:29:59');
INSERT INTO `currencies` VALUES ('YER', 'Yemeni Rial', '', NULL, '2021-06-05 18:29:59', '2021-06-05 18:29:59');
INSERT INTO `currencies` VALUES ('ZAR', 'South African Rand', 'R', NULL, '2021-06-05 18:29:59', '2021-06-05 18:29:59');
INSERT INTO `currencies` VALUES ('ZMW', 'Zambian Kwacha', 'ZMK', NULL, '2021-06-05 18:29:59', '2021-06-05 18:29:59');
INSERT INTO `currencies` VALUES ('ZWD', 'Zimbabwean Dollar', 'Z$', NULL, '2021-06-05 18:29:59', '2021-06-05 18:29:59');

-- ----------------------------
-- Table structure for experience_quests
-- ----------------------------
DROP TABLE IF EXISTS `experience_quests`;
CREATE TABLE `experience_quests`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `user_type` enum('USER','MANAGER','ADMIN') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USER',
  `company_id` bigint UNSIGNED NOT NULL,
  `required_points` bigint NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `experience_quests_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `experience_quests_user_type_index`(`user_type` ASC) USING BTREE,
  CONSTRAINT `experience_quests_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of experience_quests
-- ----------------------------

-- ----------------------------
-- Table structure for experience_users
-- ----------------------------
DROP TABLE IF EXISTS `experience_users`;
CREATE TABLE `experience_users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NULL DEFAULT NULL,
  `experience_points` bigint NOT NULL DEFAULT 1,
  `user_id` bigint UNSIGNED NOT NULL,
  `quest_id` bigint UNSIGNED NOT NULL,
  `level` bigint NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `experience_users_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `experience_users_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `experience_users_quest_id_foreign`(`quest_id` ASC) USING BTREE,
  CONSTRAINT `experience_users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `experience_users_quest_id_foreign` FOREIGN KEY (`quest_id`) REFERENCES `experience_quests` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `experience_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of experience_users
-- ----------------------------

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for idea_issues
-- ----------------------------
DROP TABLE IF EXISTS `idea_issues`;
CREATE TABLE `idea_issues`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `process_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NULL DEFAULT NULL,
  `idea_id` bigint UNSIGNED NOT NULL,
  `project_stage_id` bigint UNSIGNED NULL DEFAULT NULL,
  `parent_id` bigint UNSIGNED NOT NULL,
  `parent_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('PROBLEM','IMPROVEMENT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IMPROVEMENT',
  `unit` enum('MONEY','TIME') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MONEY',
  `dimension` enum('WEEK','MONTH','TOTAL') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TOTAL',
  `value` bigint NOT NULL DEFAULT 0,
  `value_money` bigint NOT NULL DEFAULT 0,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `anonymous_idea` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idea_issues_uuid_unique`(`uuid` ASC) USING BTREE,
  INDEX `idea_issues_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `idea_issues_author_id_foreign`(`author_id` ASC) USING BTREE,
  INDEX `idea_issues_process_id_foreign`(`process_id` ASC) USING BTREE,
  INDEX `idea_issues_project_id_foreign`(`project_id` ASC) USING BTREE,
  INDEX `idea_issues_idea_id_foreign`(`idea_id` ASC) USING BTREE,
  INDEX `idea_issues_project_stage_id_foreign`(`project_stage_id` ASC) USING BTREE,
  INDEX `idea_issues_title_index`(`title` ASC) USING BTREE,
  INDEX `idea_issues_type_index`(`type` ASC) USING BTREE,
  INDEX `idea_issues_unit_index`(`unit` ASC) USING BTREE,
  INDEX `idea_issues_dimension_index`(`dimension` ASC) USING BTREE,
  CONSTRAINT `idea_issues_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `idea_issues_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `idea_issues_idea_id_foreign` FOREIGN KEY (`idea_id`) REFERENCES `ideas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `idea_issues_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `idea_issues_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `idea_issues_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of idea_issues
-- ----------------------------

-- ----------------------------
-- Table structure for ideas
-- ----------------------------
DROP TABLE IF EXISTS `ideas`;
CREATE TABLE `ideas`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `company_tool_id` bigint UNSIGNED NULL DEFAULT NULL,
  `process_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `parent_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `source_id` bigint UNSIGNED NULL DEFAULT NULL,
  `source_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('NEW','TESTING','ADOPTED','ARCHIVED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NEW',
  `type` enum('PROCESS','TOOL') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PROCESS',
  `total_gains` bigint NOT NULL DEFAULT 0,
  `total_losses` bigint NOT NULL DEFAULT 0,
  `consolidated_value` bigint NOT NULL DEFAULT 0,
  `total_evaluations` int UNSIGNED NOT NULL DEFAULT 0,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `version` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint UNSIGNED NULL DEFAULT NULL,
  `project_stage_id` bigint UNSIGNED NULL DEFAULT NULL,
  `anonymous_idea` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `ideas_uuid_unique`(`uuid` ASC) USING BTREE,
  INDEX `ideas_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `ideas_author_id_foreign`(`author_id` ASC) USING BTREE,
  INDEX `ideas_company_tool_id_foreign`(`company_tool_id` ASC) USING BTREE,
  INDEX `ideas_process_id_foreign`(`process_id` ASC) USING BTREE,
  INDEX `ideas_parent_type_parent_id_index`(`parent_type` ASC, `parent_id` ASC) USING BTREE,
  INDEX `ideas_source_id_source_type_index`(`source_id` ASC, `source_type` ASC) USING BTREE,
  INDEX `ideas_title_index`(`title` ASC) USING BTREE,
  INDEX `ideas_status_index`(`status` ASC) USING BTREE,
  INDEX `ideas_type_index`(`type` ASC) USING BTREE,
  INDEX `ideas_project_id_foreign`(`project_id` ASC) USING BTREE,
  INDEX `ideas_project_stage_id_foreign`(`project_stage_id` ASC) USING BTREE,
  CONSTRAINT `ideas_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ideas_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ideas_company_tool_id_foreign` FOREIGN KEY (`company_tool_id`) REFERENCES `company_tools` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ideas_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ideas_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `ideas_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ideas
-- ----------------------------

-- ----------------------------
-- Table structure for industries
-- ----------------------------
DROP TABLE IF EXISTS `industries`;
CREATE TABLE `industries`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `translations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of industries
-- ----------------------------

-- ----------------------------
-- Table structure for issues
-- ----------------------------
DROP TABLE IF EXISTS `issues`;
CREATE TABLE `issues`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `process_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NULL DEFAULT NULL,
  `project_stage_id` bigint UNSIGNED NULL DEFAULT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `parent_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('ISSUE','IMPROVEMENT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IMPROVEMENT',
  `time_unit` enum('WEEK','MONTH','TOTAL') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TOTAL',
  `time_value` bigint NOT NULL DEFAULT 0,
  `time_total` bigint NOT NULL DEFAULT 0,
  `money_unit` enum('WEEK','MONTH','TOTAL') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TOTAL',
  `money_value` bigint NOT NULL DEFAULT 0,
  `money_total` bigint NOT NULL DEFAULT 0,
  `total_value` bigint NOT NULL DEFAULT 0,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `anonymous_idea` tinyint(1) NOT NULL DEFAULT 0,
  `checked_issue` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `issues_uuid_unique`(`uuid` ASC) USING BTREE,
  INDEX `issues_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `issues_author_id_foreign`(`author_id` ASC) USING BTREE,
  INDEX `issues_process_id_foreign`(`process_id` ASC) USING BTREE,
  INDEX `issues_project_id_foreign`(`project_id` ASC) USING BTREE,
  INDEX `issues_project_stage_id_foreign`(`project_stage_id` ASC) USING BTREE,
  INDEX `issues_type_index`(`type` ASC) USING BTREE,
  CONSTRAINT `issues_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `issues_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `issues_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `issues_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `issues_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of issues
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_11_000000_create_currencies_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_11_000000_create_industries_table', 1);
INSERT INTO `migrations` VALUES (3, '2014_10_11_000002_create_tools_table', 1);
INSERT INTO `migrations` VALUES (4, '2014_10_12_000000_create_companies_table', 1);
INSERT INTO `migrations` VALUES (5, '2014_10_12_000001_create_company_roles_table', 1);
INSERT INTO `migrations` VALUES (6, '2018_00_00_00001_create_resources_tables', 1);
INSERT INTO `migrations` VALUES (7, '2018_08_08_100000_create_telescope_entries_table', 1);
INSERT INTO `migrations` VALUES (8, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (9, '2019_08_19_000000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (10, '2019_08_20_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (11, '2019_11_09_020803_create_auth_tokens', 1);
INSERT INTO `migrations` VALUES (12, '2020_02_19_174843_create_websockets_statistics_entries_table', 1);
INSERT INTO `migrations` VALUES (13, '2020_02_19_183324_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (14, '2020_02_24_012412_create_processes_table', 1);
INSERT INTO `migrations` VALUES (15, '2020_02_24_012511_create_process_stages_table', 1);
INSERT INTO `migrations` VALUES (16, '2020_02_24_012642_create_process_operations_table', 1);
INSERT INTO `migrations` VALUES (17, '2020_02_24_013221_create_process_phases_table', 1);
INSERT INTO `migrations` VALUES (18, '2020_02_24_013531_create_company_tools_table', 1);
INSERT INTO `migrations` VALUES (19, '2020_02_24_013532_create_company_tools_prices_table', 1);
INSERT INTO `migrations` VALUES (20, '2020_02_24_013613_create_ideas_table', 1);
INSERT INTO `migrations` VALUES (21, '2020_02_24_013613_create_projects_table', 1);
INSERT INTO `migrations` VALUES (22, '2020_02_24_013614_create_project_stages_table', 1);
INSERT INTO `migrations` VALUES (23, '2020_02_24_013614_create_project_tools_table', 1);
INSERT INTO `migrations` VALUES (24, '2020_02_24_013614_create_project_users_table', 1);
INSERT INTO `migrations` VALUES (25, '2020_02_24_013615_create_project_ideas_table', 1);
INSERT INTO `migrations` VALUES (26, '2020_03_07_033558_create_permission_tables', 1);
INSERT INTO `migrations` VALUES (27, '2020_03_07_165021_create_tokens_table', 1);
INSERT INTO `migrations` VALUES (28, '2020_03_07_180933_create_sessions_table', 1);
INSERT INTO `migrations` VALUES (29, '2020_03_27_032345_create_project_evaluation_instances_table', 1);
INSERT INTO `migrations` VALUES (30, '2020_03_27_033528_create_project_evaluation_records_table', 1);
INSERT INTO `migrations` VALUES (31, '2020_03_27_041935_create_issues_table', 1);
INSERT INTO `migrations` VALUES (32, '2020_03_27_061720_create_model_has_company_roles_table', 1);
INSERT INTO `migrations` VALUES (33, '2020_04_07_072133_create_idea_issues_table', 1);
INSERT INTO `migrations` VALUES (34, '2020_08_24_122602_create_users_devices', 1);
INSERT INTO `migrations` VALUES (35, '2020_09_12_111250_update_resource_mime_length', 1);
INSERT INTO `migrations` VALUES (36, '2021_03_17_065541_remove_title_from_issue', 1);
INSERT INTO `migrations` VALUES (37, '2021_03_22_145403_drop_unique_project_id_constraint_from_project_ideas', 1);
INSERT INTO `migrations` VALUES (38, '2021_03_23_130618_create_notifications_table', 1);
INSERT INTO `migrations` VALUES (39, '2021_04_08_193938_ideas_anonymous_ideas', 1);
INSERT INTO `migrations` VALUES (40, '2021_04_09_161816_ideas_issues_anonymous_ideas', 1);
INSERT INTO `migrations` VALUES (41, '2021_04_09_174900_issues_anonymous_ideas', 1);
INSERT INTO `migrations` VALUES (42, '2021_04_17_174614_user_notification_preferences', 1);
INSERT INTO `migrations` VALUES (43, '2021_05_05_044812_create_issues_checked', 1);
INSERT INTO `migrations` VALUES (44, '2021_05_28_072620_create_projects_issue_evaluation_users', 1);
INSERT INTO `migrations` VALUES (45, '2021_06_02_035633_create_milestones_table', 1);
INSERT INTO `migrations` VALUES (46, '2021_06_02_075424_create_milestone_users_table', 1);
INSERT INTO `migrations` VALUES (47, '2021_11_10_052125_create_experience_tasks_table', 2);

-- ----------------------------
-- Table structure for milestone_users
-- ----------------------------
DROP TABLE IF EXISTS `milestone_users`;
CREATE TABLE `milestone_users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `engage_score` bigint NOT NULL DEFAULT 0,
  `milestone_id` bigint UNSIGNED NULL DEFAULT NULL,
  `rewarded` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `milestone_users_user_id_unique`(`user_id` ASC) USING BTREE,
  INDEX `milestone_users_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `milestone_users_milestone_id_foreign`(`milestone_id` ASC) USING BTREE,
  CONSTRAINT `milestone_users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `milestone_users_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `milestone_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of milestone_users
-- ----------------------------

-- ----------------------------
-- Table structure for milestones
-- ----------------------------
DROP TABLE IF EXISTS `milestones`;
CREATE TABLE `milestones`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `reward` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `required_score` bigint NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `milestones_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `milestones_title_index`(`title` ASC) USING BTREE,
  INDEX `milestones_reward_index`(`reward` ASC) USING BTREE,
  CONSTRAINT `milestones_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of milestones
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_company_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_company_roles`;
CREATE TABLE `model_has_company_roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `model_has_company_roles_uq_owner`(`model_type` ASC, `model_id` ASC, `company_role_id` ASC) USING BTREE,
  INDEX `model_has_company_roles_company_role_id_foreign`(`company_role_id` ASC) USING BTREE,
  CONSTRAINT `model_has_company_roles_company_role_id_foreign` FOREIGN KEY (`company_role_id`) REFERENCES `company_roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_company_roles
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_permissions_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`  (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_roles_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------
INSERT INTO `model_has_roles` VALUES (1, 'user', 1);
INSERT INTO `model_has_roles` VALUES (2, 'user', 2);

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `notifications_notifiable_type_notifiable_id_index`(`notifiable_type` ASC, `notifiable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notifications
-- ----------------------------

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 69 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 'core/industry/create', 'web', '2021-06-05 18:29:37', '2021-06-05 18:29:37');
INSERT INTO `permissions` VALUES (2, 'core/industry/update', 'web', '2021-06-05 18:29:37', '2021-06-05 18:29:37');
INSERT INTO `permissions` VALUES (3, 'core/industry/delete', 'web', '2021-06-05 18:29:37', '2021-06-05 18:29:37');
INSERT INTO `permissions` VALUES (4, 'core/industry/read', 'web', '2021-06-05 18:29:38', '2021-06-05 18:29:38');
INSERT INTO `permissions` VALUES (5, 'core/industry/readAll', 'web', '2021-06-05 18:29:38', '2021-06-05 18:29:38');
INSERT INTO `permissions` VALUES (6, 'core/industry/manage', 'web', '2021-06-05 18:29:38', '2021-06-05 18:29:38');
INSERT INTO `permissions` VALUES (7, 'core/currency/create', 'web', '2021-06-05 18:29:39', '2021-06-05 18:29:39');
INSERT INTO `permissions` VALUES (8, 'core/currency/update', 'web', '2021-06-05 18:29:39', '2021-06-05 18:29:39');
INSERT INTO `permissions` VALUES (9, 'core/currency/delete', 'web', '2021-06-05 18:29:39', '2021-06-05 18:29:39');
INSERT INTO `permissions` VALUES (10, 'core/currency/read', 'web', '2021-06-05 18:29:39', '2021-06-05 18:29:39');
INSERT INTO `permissions` VALUES (11, 'core/currency/readAll', 'web', '2021-06-05 18:29:39', '2021-06-05 18:29:39');
INSERT INTO `permissions` VALUES (12, 'core/currency/manage', 'web', '2021-06-05 18:29:40', '2021-06-05 18:29:40');
INSERT INTO `permissions` VALUES (13, 'core/tool/create', 'web', '2021-06-05 18:29:40', '2021-06-05 18:29:40');
INSERT INTO `permissions` VALUES (14, 'core/tool/update', 'web', '2021-06-05 18:29:40', '2021-06-05 18:29:40');
INSERT INTO `permissions` VALUES (15, 'core/tool/delete', 'web', '2021-06-05 18:29:41', '2021-06-05 18:29:41');
INSERT INTO `permissions` VALUES (16, 'core/tool/read', 'web', '2021-06-05 18:29:41', '2021-06-05 18:29:41');
INSERT INTO `permissions` VALUES (17, 'core/tool/readAll', 'web', '2021-06-05 18:29:41', '2021-06-05 18:29:41');
INSERT INTO `permissions` VALUES (18, 'core/tool/manage', 'web', '2021-06-05 18:29:42', '2021-06-05 18:29:42');
INSERT INTO `permissions` VALUES (19, 'auth/role/create', 'web', '2021-06-05 18:29:42', '2021-06-05 18:29:42');
INSERT INTO `permissions` VALUES (20, 'auth/role/update', 'web', '2021-06-05 18:29:42', '2021-06-05 18:29:42');
INSERT INTO `permissions` VALUES (21, 'auth/role/delete', 'web', '2021-06-05 18:29:42', '2021-06-05 18:29:42');
INSERT INTO `permissions` VALUES (22, 'auth/role/read', 'web', '2021-06-05 18:29:42', '2021-06-05 18:29:42');
INSERT INTO `permissions` VALUES (23, 'auth/role/readAll', 'web', '2021-06-05 18:29:42', '2021-06-05 18:29:42');
INSERT INTO `permissions` VALUES (24, 'auth/role/manage', 'web', '2021-06-05 18:29:43', '2021-06-05 18:29:43');
INSERT INTO `permissions` VALUES (25, 'auth/user/create', 'web', '2021-06-05 18:29:43', '2021-06-05 18:29:43');
INSERT INTO `permissions` VALUES (26, 'auth/user/update', 'web', '2021-06-05 18:29:43', '2021-06-05 18:29:43');
INSERT INTO `permissions` VALUES (27, 'auth/user/delete', 'web', '2021-06-05 18:29:43', '2021-06-05 18:29:43');
INSERT INTO `permissions` VALUES (28, 'auth/user/read', 'web', '2021-06-05 18:29:43', '2021-06-05 18:29:43');
INSERT INTO `permissions` VALUES (29, 'auth/user/readAll', 'web', '2021-06-05 18:29:43', '2021-06-05 18:29:43');
INSERT INTO `permissions` VALUES (30, 'auth/user/manage', 'web', '2021-06-05 18:29:44', '2021-06-05 18:29:44');
INSERT INTO `permissions` VALUES (31, 'auth/user/reset_password', 'web', '2021-06-05 18:29:44', '2021-06-05 18:29:44');
INSERT INTO `permissions` VALUES (32, 'auth/user/edit_my_company', 'web', '2021-06-05 18:29:44', '2021-06-05 18:29:44');
INSERT INTO `permissions` VALUES (33, 'core/companyRole/create', 'web', '2021-06-05 18:29:44', '2021-06-05 18:29:44');
INSERT INTO `permissions` VALUES (34, 'core/companyRole/update', 'web', '2021-06-05 18:29:44', '2021-06-05 18:29:44');
INSERT INTO `permissions` VALUES (35, 'core/companyRole/delete', 'web', '2021-06-05 18:29:44', '2021-06-05 18:29:44');
INSERT INTO `permissions` VALUES (36, 'core/companyRole/read', 'web', '2021-06-05 18:29:44', '2021-06-05 18:29:44');
INSERT INTO `permissions` VALUES (37, 'core/companyRole/readAll', 'web', '2021-06-05 18:29:45', '2021-06-05 18:29:45');
INSERT INTO `permissions` VALUES (38, 'core/companyRole/manage', 'web', '2021-06-05 18:29:45', '2021-06-05 18:29:45');
INSERT INTO `permissions` VALUES (39, 'core/companyTool/create', 'web', '2021-06-05 18:29:45', '2021-06-05 18:29:45');
INSERT INTO `permissions` VALUES (40, 'core/companyTool/update', 'web', '2021-06-05 18:29:45', '2021-06-05 18:29:45');
INSERT INTO `permissions` VALUES (41, 'core/companyTool/delete', 'web', '2021-06-05 18:29:46', '2021-06-05 18:29:46');
INSERT INTO `permissions` VALUES (42, 'core/companyTool/read', 'web', '2021-06-05 18:29:46', '2021-06-05 18:29:46');
INSERT INTO `permissions` VALUES (43, 'core/companyTool/readAll', 'web', '2021-06-05 18:29:46', '2021-06-05 18:29:46');
INSERT INTO `permissions` VALUES (44, 'core/companyTool/manage', 'web', '2021-06-05 18:29:47', '2021-06-05 18:29:47');
INSERT INTO `permissions` VALUES (45, 'process/process/create', 'web', '2021-06-05 18:29:47', '2021-06-05 18:29:47');
INSERT INTO `permissions` VALUES (46, 'process/process/update', 'web', '2021-06-05 18:29:47', '2021-06-05 18:29:47');
INSERT INTO `permissions` VALUES (47, 'process/process/delete', 'web', '2021-06-05 18:29:47', '2021-06-05 18:29:47');
INSERT INTO `permissions` VALUES (48, 'process/process/read', 'web', '2021-06-05 18:29:48', '2021-06-05 18:29:48');
INSERT INTO `permissions` VALUES (49, 'process/process/readAll', 'web', '2021-06-05 18:29:48', '2021-06-05 18:29:48');
INSERT INTO `permissions` VALUES (50, 'process/process/manage', 'web', '2021-06-05 18:29:48', '2021-06-05 18:29:48');
INSERT INTO `permissions` VALUES (51, 'improve/idea/create', 'web', '2021-06-05 18:29:49', '2021-06-05 18:29:49');
INSERT INTO `permissions` VALUES (52, 'improve/idea/update', 'web', '2021-06-05 18:29:49', '2021-06-05 18:29:49');
INSERT INTO `permissions` VALUES (53, 'improve/idea/delete', 'web', '2021-06-05 18:29:49', '2021-06-05 18:29:49');
INSERT INTO `permissions` VALUES (54, 'improve/idea/read', 'web', '2021-06-05 18:29:49', '2021-06-05 18:29:49');
INSERT INTO `permissions` VALUES (55, 'improve/idea/readAll', 'web', '2021-06-05 18:29:50', '2021-06-05 18:29:50');
INSERT INTO `permissions` VALUES (56, 'improve/idea/manage', 'web', '2021-06-05 18:29:51', '2021-06-05 18:29:51');
INSERT INTO `permissions` VALUES (57, 'core/project/create', 'web', '2021-06-05 18:29:51', '2021-06-05 18:29:51');
INSERT INTO `permissions` VALUES (58, 'core/project/update', 'web', '2021-06-05 18:29:52', '2021-06-05 18:29:52');
INSERT INTO `permissions` VALUES (59, 'core/project/delete', 'web', '2021-06-05 18:29:52', '2021-06-05 18:29:52');
INSERT INTO `permissions` VALUES (60, 'core/project/read', 'web', '2021-06-05 18:29:52', '2021-06-05 18:29:52');
INSERT INTO `permissions` VALUES (61, 'core/project/readAll', 'web', '2021-06-05 18:29:53', '2021-06-05 18:29:53');
INSERT INTO `permissions` VALUES (62, 'core/project/manage', 'web', '2021-06-05 18:29:53', '2021-06-05 18:29:53');
INSERT INTO `permissions` VALUES (63, 'core/company/create', 'web', '2021-06-05 18:29:54', '2021-06-05 18:29:54');
INSERT INTO `permissions` VALUES (64, 'core/company/update', 'web', '2021-06-05 18:29:54', '2021-06-05 18:29:54');
INSERT INTO `permissions` VALUES (65, 'core/company/delete', 'web', '2021-06-05 18:29:54', '2021-06-05 18:29:54');
INSERT INTO `permissions` VALUES (66, 'core/company/read', 'web', '2021-06-05 18:29:54', '2021-06-05 18:29:54');
INSERT INTO `permissions` VALUES (67, 'core/company/readAll', 'web', '2021-06-05 18:29:55', '2021-06-05 18:29:55');
INSERT INTO `permissions` VALUES (68, 'core/company/manage', 'web', '2021-06-05 18:29:55', '2021-06-05 18:29:55');

-- ----------------------------
-- Table structure for process_operations
-- ----------------------------
DROP TABLE IF EXISTS `process_operations`;
CREATE TABLE `process_operations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `process_id` bigint UNSIGNED NOT NULL,
  `stage_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `d_order` int UNSIGNED NOT NULL DEFAULT 1,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `process_operations_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `process_operations_author_id_foreign`(`author_id` ASC) USING BTREE,
  INDEX `process_operations_process_id_foreign`(`process_id` ASC) USING BTREE,
  INDEX `process_operations_stage_id_foreign`(`stage_id` ASC) USING BTREE,
  INDEX `process_operations_title_index`(`title` ASC) USING BTREE,
  CONSTRAINT `process_operations_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `process_operations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `process_operations_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `process_operations_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `process_stages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of process_operations
-- ----------------------------

-- ----------------------------
-- Table structure for process_phases
-- ----------------------------
DROP TABLE IF EXISTS `process_phases`;
CREATE TABLE `process_phases`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `process_id` bigint UNSIGNED NOT NULL,
  `operation_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `d_order` int UNSIGNED NOT NULL DEFAULT 1,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `process_phases_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `process_phases_author_id_foreign`(`author_id` ASC) USING BTREE,
  INDEX `process_phases_process_id_foreign`(`process_id` ASC) USING BTREE,
  INDEX `process_phases_operation_id_foreign`(`operation_id` ASC) USING BTREE,
  INDEX `process_phases_title_index`(`title` ASC) USING BTREE,
  CONSTRAINT `process_phases_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `process_phases_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `process_phases_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `process_operations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `process_phases_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of process_phases
-- ----------------------------

-- ----------------------------
-- Table structure for process_stages
-- ----------------------------
DROP TABLE IF EXISTS `process_stages`;
CREATE TABLE `process_stages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `process_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `d_order` int UNSIGNED NOT NULL DEFAULT 1,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `process_stages_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `process_stages_author_id_foreign`(`author_id` ASC) USING BTREE,
  INDEX `process_stages_process_id_foreign`(`process_id` ASC) USING BTREE,
  INDEX `process_stages_title_index`(`title` ASC) USING BTREE,
  CONSTRAINT `process_stages_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `process_stages_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `process_stages_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of process_stages
-- ----------------------------

-- ----------------------------
-- Table structure for processes
-- ----------------------------
DROP TABLE IF EXISTS `processes`;
CREATE TABLE `processes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `d_order` int UNSIGNED NOT NULL DEFAULT 1,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `processes_title_company_id_unique`(`title` ASC, `company_id` ASC) USING BTREE,
  INDEX `processes_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `processes_author_id_foreign`(`author_id` ASC) USING BTREE,
  INDEX `processes_title_index`(`title` ASC) USING BTREE,
  CONSTRAINT `processes_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `processes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of processes
-- ----------------------------

-- ----------------------------
-- Table structure for project_evaluation_instances
-- ----------------------------
DROP TABLE IF EXISTS `project_evaluation_instances`;
CREATE TABLE `project_evaluation_instances`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `process_id` bigint UNSIGNED NULL DEFAULT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `project_stage_id` bigint UNSIGNED NOT NULL,
  `status` enum('OPEN','CLOSED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OPEN',
  `started_at` datetime NOT NULL DEFAULT current_timestamp,
  `ends_at` datetime NULL DEFAULT NULL,
  `closed_at` datetime NULL DEFAULT NULL,
  `evaluation_period_start` datetime NULL DEFAULT NULL,
  `evaluation_period_end` datetime NULL DEFAULT NULL,
  `total_gains` bigint NOT NULL DEFAULT 0,
  `total_losses` bigint NOT NULL DEFAULT 0,
  `consolidated_value` bigint NOT NULL DEFAULT 0,
  `total_evaluations` int UNSIGNED NOT NULL DEFAULT 0,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `project_evaluation_instances_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `project_evaluation_instances_process_id_foreign`(`process_id` ASC) USING BTREE,
  INDEX `project_evaluation_instances_project_id_foreign`(`project_id` ASC) USING BTREE,
  INDEX `project_evaluation_instances_project_stage_id_foreign`(`project_stage_id` ASC) USING BTREE,
  CONSTRAINT `project_evaluation_instances_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_instances_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_instances_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_instances_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of project_evaluation_instances
-- ----------------------------

-- ----------------------------
-- Table structure for project_evaluation_records
-- ----------------------------
DROP TABLE IF EXISTS `project_evaluation_records`;
CREATE TABLE `project_evaluation_records`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `process_id` bigint UNSIGNED NOT NULL,
  `process_stage_id` bigint UNSIGNED NOT NULL,
  `project_idea_id` bigint UNSIGNED NOT NULL,
  `project_user_id` bigint UNSIGNED NOT NULL,
  `idea_id` bigint UNSIGNED NOT NULL,
  `evaluation_instance_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `project_stage_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `status` enum('PENDING','COMPLETED','SKIPPED','MISSING') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `time_unit` enum('WEEK','MONTH','TOTAL') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TOTAL',
  `time_value` bigint NOT NULL DEFAULT 0,
  `time_total` bigint NOT NULL DEFAULT 0,
  `money_unit` enum('WEEK','MONTH','TOTAL') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TOTAL',
  `money_value` bigint NOT NULL DEFAULT 0,
  `money_total` bigint NOT NULL DEFAULT 0,
  `total_value` bigint NOT NULL DEFAULT 0,
  `author_yearly_costs` bigint NOT NULL DEFAULT 0,
  `yearly_costs_money` bigint NOT NULL DEFAULT 0,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `completed_at` datetime NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `project_evaluation_records_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `project_evaluation_records_process_id_foreign`(`process_id` ASC) USING BTREE,
  INDEX `project_evaluation_records_process_stage_id_foreign`(`process_stage_id` ASC) USING BTREE,
  INDEX `project_evaluation_records_project_idea_id_foreign`(`project_idea_id` ASC) USING BTREE,
  INDEX `project_evaluation_records_project_user_id_foreign`(`project_user_id` ASC) USING BTREE,
  INDEX `project_evaluation_records_idea_id_foreign`(`idea_id` ASC) USING BTREE,
  INDEX `project_evaluation_records_evaluation_instance_id_foreign`(`evaluation_instance_id` ASC) USING BTREE,
  INDEX `project_evaluation_records_project_id_foreign`(`project_id` ASC) USING BTREE,
  INDEX `project_evaluation_records_project_stage_id_foreign`(`project_stage_id` ASC) USING BTREE,
  INDEX `project_evaluation_records_author_id_foreign`(`author_id` ASC) USING BTREE,
  INDEX `project_evaluation_records_status_index`(`status` ASC) USING BTREE,
  CONSTRAINT `project_evaluation_records_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_records_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_records_evaluation_instance_id_foreign` FOREIGN KEY (`evaluation_instance_id`) REFERENCES `project_evaluation_instances` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_records_idea_id_foreign` FOREIGN KEY (`idea_id`) REFERENCES `ideas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_records_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_records_process_stage_id_foreign` FOREIGN KEY (`process_stage_id`) REFERENCES `process_stages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_records_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_records_project_idea_id_foreign` FOREIGN KEY (`project_idea_id`) REFERENCES `project_ideas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_records_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_evaluation_records_project_user_id_foreign` FOREIGN KEY (`project_user_id`) REFERENCES `project_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of project_evaluation_records
-- ----------------------------

-- ----------------------------
-- Table structure for project_ideas
-- ----------------------------
DROP TABLE IF EXISTS `project_ideas`;
CREATE TABLE `project_ideas`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `project_stage_id` bigint UNSIGNED NOT NULL,
  `idea_id` bigint UNSIGNED NOT NULL,
  `process_id` bigint UNSIGNED NOT NULL,
  `process_stage_id` bigint UNSIGNED NOT NULL,
  `total_gains` bigint NOT NULL DEFAULT 0,
  `total_losses` bigint NOT NULL DEFAULT 0,
  `consolidated_value` bigint NOT NULL DEFAULT 0,
  `total_evaluations` bigint NOT NULL DEFAULT 0,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `project_ideas_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `project_ideas_project_stage_id_foreign`(`project_stage_id` ASC) USING BTREE,
  INDEX `project_ideas_idea_id_foreign`(`idea_id` ASC) USING BTREE,
  INDEX `project_ideas_process_id_foreign`(`process_id` ASC) USING BTREE,
  INDEX `project_ideas_process_stage_id_foreign`(`process_stage_id` ASC) USING BTREE,
  CONSTRAINT `project_ideas_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_ideas_idea_id_foreign` FOREIGN KEY (`idea_id`) REFERENCES `ideas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_ideas_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_ideas_process_stage_id_foreign` FOREIGN KEY (`process_stage_id`) REFERENCES `process_stages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_ideas_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of project_ideas
-- ----------------------------

-- ----------------------------
-- Table structure for project_stages
-- ----------------------------
DROP TABLE IF EXISTS `project_stages`;
CREATE TABLE `project_stages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `process_id` bigint UNSIGNED NOT NULL,
  `stage_id` bigint UNSIGNED NOT NULL,
  `d_order` bigint UNSIGNED NOT NULL,
  `status` enum('NOT_STARTED','STARTED','EVALUATIONS_PENDING','FINISHED','TRASHED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'STARTED',
  `next_schedule_process` datetime NULL DEFAULT NULL,
  `total_gains` bigint NOT NULL DEFAULT 0,
  `total_losses` bigint NOT NULL DEFAULT 0,
  `consolidated_value` bigint NOT NULL DEFAULT 0,
  `total_evaluations` bigint NOT NULL DEFAULT 0,
  `started_at` datetime NULL DEFAULT NULL,
  `closed_at` datetime NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `project_stages_project_id_foreign`(`project_id` ASC) USING BTREE,
  INDEX `project_stages_process_id_foreign`(`process_id` ASC) USING BTREE,
  INDEX `project_stages_stage_id_foreign`(`stage_id` ASC) USING BTREE,
  CONSTRAINT `project_stages_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_stages_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_stages_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `process_stages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of project_stages
-- ----------------------------

-- ----------------------------
-- Table structure for project_tools
-- ----------------------------
DROP TABLE IF EXISTS `project_tools`;
CREATE TABLE `project_tools`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `project_stage_id` bigint UNSIGNED NOT NULL,
  `company_tool_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `total_gains` bigint NOT NULL DEFAULT 0,
  `total_losses` bigint NOT NULL DEFAULT 0,
  `consolidated_value` bigint NOT NULL DEFAULT 0,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `project_tools_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `project_tools_tool_id_foreign`(`tool_id` ASC) USING BTREE,
  INDEX `project_tools_project_stage_id_foreign`(`project_stage_id` ASC) USING BTREE,
  INDEX `project_tools_company_tool_id_foreign`(`company_tool_id` ASC) USING BTREE,
  INDEX `project_tools_project_id_foreign`(`project_id` ASC) USING BTREE,
  CONSTRAINT `project_tools_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_tools_company_tool_id_foreign` FOREIGN KEY (`company_tool_id`) REFERENCES `company_tools` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_tools_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_tools_project_stage_id_foreign` FOREIGN KEY (`project_stage_id`) REFERENCES `project_stages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_tools_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of project_tools
-- ----------------------------

-- ----------------------------
-- Table structure for project_users
-- ----------------------------
DROP TABLE IF EXISTS `project_users`;
CREATE TABLE `project_users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_gains` bigint NOT NULL DEFAULT 0,
  `total_losses` bigint NOT NULL DEFAULT 0,
  `consolidated_value` bigint NOT NULL DEFAULT 0,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `project_users_project_id_user_id_unique`(`project_id` ASC, `user_id` ASC) USING BTREE,
  INDEX `project_users_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `project_users_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `project_users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_users_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of project_users
-- ----------------------------

-- ----------------------------
-- Table structure for projects
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NULL DEFAULT NULL,
  `process_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `budget` bigint UNSIGNED NULL DEFAULT NULL,
  `started_at` datetime NULL DEFAULT NULL,
  `finished_at` datetime NULL DEFAULT NULL,
  `evaluation_type` enum('STAGE_FINISH','PERIODIC') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'STAGE_FINISH',
  `evaluation_interval_amount` int UNSIGNED NULL DEFAULT NULL,
  `evaluation_interval_unit` enum('DAYS','WEEKS','MONTHS','YEARS') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type` enum('NORMAL','ON_GOING') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NORMAL',
  `status` enum('NOT_STARTED','STARTED','EVALUATIONS_PENDING','FINISHED','TRASHED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NOT_STARTED',
  `total_gains` bigint NOT NULL DEFAULT 0,
  `total_losses` bigint NOT NULL DEFAULT 0,
  `consolidated_value` bigint NOT NULL DEFAULT 0,
  `total_evaluations` bigint NOT NULL DEFAULT 0,
  `d_order` int UNSIGNED NOT NULL DEFAULT 1,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `issue_evaluation_roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `projects_process_id_name_unique`(`process_id` ASC, `name` ASC) USING BTREE,
  INDEX `projects_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `projects_author_id_foreign`(`author_id` ASC) USING BTREE,
  INDEX `projects_name_index`(`name` ASC) USING BTREE,
  INDEX `projects_evaluation_type_index`(`evaluation_type` ASC) USING BTREE,
  INDEX `projects_type_index`(`type` ASC) USING BTREE,
  INDEX `projects_status_index`(`status` ASC) USING BTREE,
  CONSTRAINT `projects_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `projects_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `projects_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of projects
-- ----------------------------

-- ----------------------------
-- Table structure for resource_collections
-- ----------------------------
DROP TABLE IF EXISTS `resource_collections`;
CREATE TABLE `resource_collections`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `collection_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `resource_collections_uuid_unique`(`uuid` ASC) USING BTREE,
  INDEX `resource_collections_owner_type_owner_id_index`(`owner_type` ASC, `owner_id` ASC) USING BTREE,
  INDEX `resource_collections_collection_type_index`(`collection_type` ASC) USING BTREE,
  INDEX `resource_collections_owner_type_index`(`owner_type` ASC) USING BTREE,
  INDEX `resource_collections_owner_id_index`(`owner_id` ASC) USING BTREE,
  INDEX `resource_collections_section_id_index`(`section_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of resource_collections
-- ----------------------------

-- ----------------------------
-- Table structure for resources
-- ----------------------------
DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `display_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `owner_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `owner_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `uri` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `size` bigint NOT NULL DEFAULT 0,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `visibility` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `resources_uuid_unique`(`uuid` ASC) USING BTREE,
  INDEX `resources_owner_type_owner_id_index`(`owner_type` ASC, `owner_id` ASC) USING BTREE,
  INDEX `resources_type_index`(`type` ASC) USING BTREE,
  INDEX `resources_display_type_index`(`display_type` ASC) USING BTREE,
  INDEX `resources_owner_type_index`(`owner_type` ASC) USING BTREE,
  INDEX `resources_owner_id_index`(`owner_id` ASC) USING BTREE,
  INDEX `resources_mime_type_index`(`mime_type` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of resources
-- ----------------------------

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
INSERT INTO `role_has_permissions` VALUES (1, 1);
INSERT INTO `role_has_permissions` VALUES (1, 2);
INSERT INTO `role_has_permissions` VALUES (1, 3);
INSERT INTO `role_has_permissions` VALUES (1, 4);
INSERT INTO `role_has_permissions` VALUES (2, 1);
INSERT INTO `role_has_permissions` VALUES (2, 2);
INSERT INTO `role_has_permissions` VALUES (2, 3);
INSERT INTO `role_has_permissions` VALUES (2, 4);
INSERT INTO `role_has_permissions` VALUES (3, 1);
INSERT INTO `role_has_permissions` VALUES (3, 2);
INSERT INTO `role_has_permissions` VALUES (3, 3);
INSERT INTO `role_has_permissions` VALUES (3, 4);
INSERT INTO `role_has_permissions` VALUES (4, 1);
INSERT INTO `role_has_permissions` VALUES (4, 2);
INSERT INTO `role_has_permissions` VALUES (4, 3);
INSERT INTO `role_has_permissions` VALUES (4, 4);
INSERT INTO `role_has_permissions` VALUES (5, 1);
INSERT INTO `role_has_permissions` VALUES (5, 2);
INSERT INTO `role_has_permissions` VALUES (5, 3);
INSERT INTO `role_has_permissions` VALUES (5, 4);
INSERT INTO `role_has_permissions` VALUES (6, 1);
INSERT INTO `role_has_permissions` VALUES (6, 2);
INSERT INTO `role_has_permissions` VALUES (6, 3);
INSERT INTO `role_has_permissions` VALUES (6, 4);
INSERT INTO `role_has_permissions` VALUES (7, 1);
INSERT INTO `role_has_permissions` VALUES (7, 2);
INSERT INTO `role_has_permissions` VALUES (7, 3);
INSERT INTO `role_has_permissions` VALUES (7, 4);
INSERT INTO `role_has_permissions` VALUES (8, 1);
INSERT INTO `role_has_permissions` VALUES (8, 2);
INSERT INTO `role_has_permissions` VALUES (8, 3);
INSERT INTO `role_has_permissions` VALUES (8, 4);
INSERT INTO `role_has_permissions` VALUES (9, 1);
INSERT INTO `role_has_permissions` VALUES (9, 2);
INSERT INTO `role_has_permissions` VALUES (9, 3);
INSERT INTO `role_has_permissions` VALUES (9, 4);
INSERT INTO `role_has_permissions` VALUES (10, 1);
INSERT INTO `role_has_permissions` VALUES (10, 2);
INSERT INTO `role_has_permissions` VALUES (10, 3);
INSERT INTO `role_has_permissions` VALUES (10, 4);
INSERT INTO `role_has_permissions` VALUES (11, 1);
INSERT INTO `role_has_permissions` VALUES (11, 2);
INSERT INTO `role_has_permissions` VALUES (11, 3);
INSERT INTO `role_has_permissions` VALUES (11, 4);
INSERT INTO `role_has_permissions` VALUES (12, 1);
INSERT INTO `role_has_permissions` VALUES (12, 2);
INSERT INTO `role_has_permissions` VALUES (12, 3);
INSERT INTO `role_has_permissions` VALUES (12, 4);
INSERT INTO `role_has_permissions` VALUES (13, 1);
INSERT INTO `role_has_permissions` VALUES (13, 2);
INSERT INTO `role_has_permissions` VALUES (13, 3);
INSERT INTO `role_has_permissions` VALUES (13, 4);
INSERT INTO `role_has_permissions` VALUES (14, 1);
INSERT INTO `role_has_permissions` VALUES (14, 2);
INSERT INTO `role_has_permissions` VALUES (14, 3);
INSERT INTO `role_has_permissions` VALUES (14, 4);
INSERT INTO `role_has_permissions` VALUES (15, 1);
INSERT INTO `role_has_permissions` VALUES (15, 2);
INSERT INTO `role_has_permissions` VALUES (15, 3);
INSERT INTO `role_has_permissions` VALUES (15, 4);
INSERT INTO `role_has_permissions` VALUES (16, 1);
INSERT INTO `role_has_permissions` VALUES (16, 2);
INSERT INTO `role_has_permissions` VALUES (16, 3);
INSERT INTO `role_has_permissions` VALUES (16, 4);
INSERT INTO `role_has_permissions` VALUES (17, 1);
INSERT INTO `role_has_permissions` VALUES (17, 2);
INSERT INTO `role_has_permissions` VALUES (17, 3);
INSERT INTO `role_has_permissions` VALUES (17, 4);
INSERT INTO `role_has_permissions` VALUES (18, 1);
INSERT INTO `role_has_permissions` VALUES (18, 2);
INSERT INTO `role_has_permissions` VALUES (18, 3);
INSERT INTO `role_has_permissions` VALUES (18, 4);
INSERT INTO `role_has_permissions` VALUES (19, 1);
INSERT INTO `role_has_permissions` VALUES (20, 1);
INSERT INTO `role_has_permissions` VALUES (21, 1);
INSERT INTO `role_has_permissions` VALUES (22, 1);
INSERT INTO `role_has_permissions` VALUES (22, 2);
INSERT INTO `role_has_permissions` VALUES (22, 3);
INSERT INTO `role_has_permissions` VALUES (22, 4);
INSERT INTO `role_has_permissions` VALUES (23, 1);
INSERT INTO `role_has_permissions` VALUES (23, 2);
INSERT INTO `role_has_permissions` VALUES (23, 3);
INSERT INTO `role_has_permissions` VALUES (23, 4);
INSERT INTO `role_has_permissions` VALUES (24, 1);
INSERT INTO `role_has_permissions` VALUES (25, 2);
INSERT INTO `role_has_permissions` VALUES (25, 3);
INSERT INTO `role_has_permissions` VALUES (26, 2);
INSERT INTO `role_has_permissions` VALUES (26, 3);
INSERT INTO `role_has_permissions` VALUES (27, 2);
INSERT INTO `role_has_permissions` VALUES (27, 3);
INSERT INTO `role_has_permissions` VALUES (28, 2);
INSERT INTO `role_has_permissions` VALUES (28, 3);
INSERT INTO `role_has_permissions` VALUES (28, 4);
INSERT INTO `role_has_permissions` VALUES (29, 2);
INSERT INTO `role_has_permissions` VALUES (29, 3);
INSERT INTO `role_has_permissions` VALUES (29, 4);
INSERT INTO `role_has_permissions` VALUES (30, 2);
INSERT INTO `role_has_permissions` VALUES (31, 2);
INSERT INTO `role_has_permissions` VALUES (31, 3);
INSERT INTO `role_has_permissions` VALUES (32, 2);
INSERT INTO `role_has_permissions` VALUES (33, 2);
INSERT INTO `role_has_permissions` VALUES (33, 3);
INSERT INTO `role_has_permissions` VALUES (34, 2);
INSERT INTO `role_has_permissions` VALUES (34, 3);
INSERT INTO `role_has_permissions` VALUES (35, 2);
INSERT INTO `role_has_permissions` VALUES (35, 3);
INSERT INTO `role_has_permissions` VALUES (36, 2);
INSERT INTO `role_has_permissions` VALUES (36, 3);
INSERT INTO `role_has_permissions` VALUES (36, 4);
INSERT INTO `role_has_permissions` VALUES (37, 2);
INSERT INTO `role_has_permissions` VALUES (37, 3);
INSERT INTO `role_has_permissions` VALUES (37, 4);
INSERT INTO `role_has_permissions` VALUES (38, 2);
INSERT INTO `role_has_permissions` VALUES (39, 1);
INSERT INTO `role_has_permissions` VALUES (39, 2);
INSERT INTO `role_has_permissions` VALUES (39, 3);
INSERT INTO `role_has_permissions` VALUES (40, 1);
INSERT INTO `role_has_permissions` VALUES (40, 2);
INSERT INTO `role_has_permissions` VALUES (40, 3);
INSERT INTO `role_has_permissions` VALUES (41, 1);
INSERT INTO `role_has_permissions` VALUES (41, 2);
INSERT INTO `role_has_permissions` VALUES (41, 3);
INSERT INTO `role_has_permissions` VALUES (42, 1);
INSERT INTO `role_has_permissions` VALUES (42, 2);
INSERT INTO `role_has_permissions` VALUES (42, 3);
INSERT INTO `role_has_permissions` VALUES (42, 4);
INSERT INTO `role_has_permissions` VALUES (43, 1);
INSERT INTO `role_has_permissions` VALUES (43, 2);
INSERT INTO `role_has_permissions` VALUES (43, 3);
INSERT INTO `role_has_permissions` VALUES (43, 4);
INSERT INTO `role_has_permissions` VALUES (44, 1);
INSERT INTO `role_has_permissions` VALUES (44, 2);
INSERT INTO `role_has_permissions` VALUES (44, 3);
INSERT INTO `role_has_permissions` VALUES (45, 1);
INSERT INTO `role_has_permissions` VALUES (45, 2);
INSERT INTO `role_has_permissions` VALUES (45, 3);
INSERT INTO `role_has_permissions` VALUES (46, 1);
INSERT INTO `role_has_permissions` VALUES (46, 2);
INSERT INTO `role_has_permissions` VALUES (46, 3);
INSERT INTO `role_has_permissions` VALUES (47, 1);
INSERT INTO `role_has_permissions` VALUES (47, 2);
INSERT INTO `role_has_permissions` VALUES (47, 3);
INSERT INTO `role_has_permissions` VALUES (48, 1);
INSERT INTO `role_has_permissions` VALUES (48, 2);
INSERT INTO `role_has_permissions` VALUES (48, 3);
INSERT INTO `role_has_permissions` VALUES (48, 4);
INSERT INTO `role_has_permissions` VALUES (49, 1);
INSERT INTO `role_has_permissions` VALUES (49, 2);
INSERT INTO `role_has_permissions` VALUES (49, 3);
INSERT INTO `role_has_permissions` VALUES (49, 4);
INSERT INTO `role_has_permissions` VALUES (50, 1);
INSERT INTO `role_has_permissions` VALUES (50, 2);
INSERT INTO `role_has_permissions` VALUES (50, 3);
INSERT INTO `role_has_permissions` VALUES (51, 1);
INSERT INTO `role_has_permissions` VALUES (51, 2);
INSERT INTO `role_has_permissions` VALUES (51, 3);
INSERT INTO `role_has_permissions` VALUES (51, 4);
INSERT INTO `role_has_permissions` VALUES (52, 1);
INSERT INTO `role_has_permissions` VALUES (52, 2);
INSERT INTO `role_has_permissions` VALUES (52, 3);
INSERT INTO `role_has_permissions` VALUES (52, 4);
INSERT INTO `role_has_permissions` VALUES (53, 1);
INSERT INTO `role_has_permissions` VALUES (53, 2);
INSERT INTO `role_has_permissions` VALUES (53, 3);
INSERT INTO `role_has_permissions` VALUES (54, 1);
INSERT INTO `role_has_permissions` VALUES (54, 2);
INSERT INTO `role_has_permissions` VALUES (54, 3);
INSERT INTO `role_has_permissions` VALUES (54, 4);
INSERT INTO `role_has_permissions` VALUES (55, 1);
INSERT INTO `role_has_permissions` VALUES (55, 2);
INSERT INTO `role_has_permissions` VALUES (55, 3);
INSERT INTO `role_has_permissions` VALUES (55, 4);
INSERT INTO `role_has_permissions` VALUES (56, 1);
INSERT INTO `role_has_permissions` VALUES (56, 2);
INSERT INTO `role_has_permissions` VALUES (56, 3);
INSERT INTO `role_has_permissions` VALUES (56, 4);
INSERT INTO `role_has_permissions` VALUES (57, 1);
INSERT INTO `role_has_permissions` VALUES (57, 2);
INSERT INTO `role_has_permissions` VALUES (57, 3);
INSERT INTO `role_has_permissions` VALUES (58, 1);
INSERT INTO `role_has_permissions` VALUES (58, 2);
INSERT INTO `role_has_permissions` VALUES (58, 3);
INSERT INTO `role_has_permissions` VALUES (59, 1);
INSERT INTO `role_has_permissions` VALUES (59, 2);
INSERT INTO `role_has_permissions` VALUES (59, 3);
INSERT INTO `role_has_permissions` VALUES (60, 1);
INSERT INTO `role_has_permissions` VALUES (60, 2);
INSERT INTO `role_has_permissions` VALUES (60, 3);
INSERT INTO `role_has_permissions` VALUES (60, 4);
INSERT INTO `role_has_permissions` VALUES (61, 1);
INSERT INTO `role_has_permissions` VALUES (61, 2);
INSERT INTO `role_has_permissions` VALUES (61, 3);
INSERT INTO `role_has_permissions` VALUES (61, 4);
INSERT INTO `role_has_permissions` VALUES (62, 1);
INSERT INTO `role_has_permissions` VALUES (62, 2);
INSERT INTO `role_has_permissions` VALUES (62, 3);
INSERT INTO `role_has_permissions` VALUES (63, 1);
INSERT INTO `role_has_permissions` VALUES (64, 1);
INSERT INTO `role_has_permissions` VALUES (65, 1);
INSERT INTO `role_has_permissions` VALUES (66, 1);
INSERT INTO `role_has_permissions` VALUES (67, 1);
INSERT INTO `role_has_permissions` VALUES (68, 1);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Super Admin', 'web', '2021-06-05 18:29:37', '2021-06-05 18:29:37');
INSERT INTO `roles` VALUES (2, 'Company Admin', 'web', '2021-06-05 18:29:37', '2021-06-05 18:29:37');
INSERT INTO `roles` VALUES (3, 'Company Manager', 'web', '2021-06-05 18:29:37', '2021-06-05 18:29:37');
INSERT INTO `roles` VALUES (4, 'User', 'web', '2021-06-05 18:29:37', '2021-06-05 18:29:37');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE INDEX `sessions_id_unique`(`id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------

-- ----------------------------
-- Table structure for telescope_entries
-- ----------------------------
DROP TABLE IF EXISTS `telescope_entries`;
CREATE TABLE `telescope_entries`  (
  `sequence` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT 1,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`sequence`) USING BTREE,
  UNIQUE INDEX `telescope_entries_uuid_unique`(`uuid` ASC) USING BTREE,
  INDEX `telescope_entries_batch_id_index`(`batch_id` ASC) USING BTREE,
  INDEX `telescope_entries_family_hash_index`(`family_hash` ASC) USING BTREE,
  INDEX `telescope_entries_created_at_index`(`created_at` ASC) USING BTREE,
  INDEX `telescope_entries_type_should_display_on_index_index`(`type` ASC, `should_display_on_index` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of telescope_entries
-- ----------------------------

-- ----------------------------
-- Table structure for telescope_entries_tags
-- ----------------------------
DROP TABLE IF EXISTS `telescope_entries_tags`;
CREATE TABLE `telescope_entries_tags`  (
  `entry_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  INDEX `telescope_entries_tags_entry_uuid_tag_index`(`entry_uuid` ASC, `tag` ASC) USING BTREE,
  INDEX `telescope_entries_tags_tag_index`(`tag` ASC) USING BTREE,
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of telescope_entries_tags
-- ----------------------------

-- ----------------------------
-- Table structure for telescope_monitoring
-- ----------------------------
DROP TABLE IF EXISTS `telescope_monitoring`;
CREATE TABLE `telescope_monitoring`  (
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of telescope_monitoring
-- ----------------------------

-- ----------------------------
-- Table structure for tokens
-- ----------------------------
DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tokens
-- ----------------------------

-- ----------------------------
-- Table structure for tools
-- ----------------------------
DROP TABLE IF EXISTS `tools`;
CREATE TABLE `tools`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `translations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `type` enum('TOOL','MODULE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tool_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tools_tool_id_foreign`(`tool_id` ASC) USING BTREE,
  INDEX `tools_type_index`(`type` ASC) USING BTREE,
  CONSTRAINT `tools_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tools
-- ----------------------------

-- ----------------------------
-- Table structure for user_devices
-- ----------------------------
DROP TABLE IF EXISTS `user_devices`;
CREATE TABLE `user_devices`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('android','ios','web') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_devices_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `user_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_devices
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `status` enum('PENDING','ACTIVE','INACTIVE','TRASHED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint UNSIGNED NULL DEFAULT NULL,
  `company_role_id` bigint UNSIGNED NULL DEFAULT NULL,
  `yearly_costs` bigint UNSIGNED NOT NULL DEFAULT 0,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `total_gains` bigint NOT NULL DEFAULT 0,
  `total_losses` bigint NOT NULL DEFAULT 0,
  `consolidated_value` bigint NOT NULL DEFAULT 0,
  `total_evaluations` int UNSIGNED NOT NULL DEFAULT 0,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notifications` tinyint(1) NOT NULL DEFAULT 1,
  `user_engage_score` bigint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  INDEX `users_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `users_company_role_id_foreign`(`company_role_id` ASC) USING BTREE,
  INDEX `users_first_name_index`(`first_name` ASC) USING BTREE,
  INDEX `users_last_name_index`(`last_name` ASC) USING BTREE,
  INDEX `users_status_index`(`status` ASC) USING BTREE,
  CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `users_company_role_id_foreign` FOREIGN KEY (`company_role_id`) REFERENCES `company_roles` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Admin', 'Admin', 'root@devcore.test', NULL, 'en', 'PENDING', 1, 0, '2021-06-05 18:29:59', NULL, '$2y$10$kTUA2k6YGkkaBLmQTwGjZOEMfO4ypO8yxxM8.rZ4jJep6W3BEMeIK', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 'u1XgS8Vryc', '2021-06-05 18:29:59', '2021-06-05 18:29:59', 1, NULL);
INSERT INTO `users` VALUES (2, 'test company', 'Administrator', 'kristiankcodes@gmail.com', NULL, 'en', 'PENDING', 0, 0, NULL, NULL, '$2y$10$9aWzm6EXLMDjOGCxWiBFreNtcLEDQs4sH5hqU2KOEECETIvihNduW', 1, 1, 0, NULL, NULL, 0, 0, 0, 0, NULL, '2021-06-05 18:33:30', '2021-06-05 18:34:05', 1, NULL);

-- ----------------------------
-- Table structure for websockets_statistics_entries
-- ----------------------------
DROP TABLE IF EXISTS `websockets_statistics_entries`;
CREATE TABLE `websockets_statistics_entries`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `app_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `peak_connection_count` int NOT NULL,
  `websocket_message_count` int NOT NULL,
  `api_message_count` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of websockets_statistics_entries
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
