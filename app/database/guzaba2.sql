-- MySQL dump 10.13  Distrib 8.0.19, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: guzaba2
-- ------------------------------------------------------
-- Server version	8.0.18

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
-- Table structure for table `guzaba_acl_permissions`
--

DROP TABLE IF EXISTS `guzaba_acl_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_acl_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `class_name` varchar(200) NOT NULL,
  `object_id` bigint(20) unsigned DEFAULT '0',
  `action_name` varchar(200) NOT NULL,
  `permission_description` varchar(2000) NOT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_categories`
--

DROP TABLE IF EXISTS `guzaba_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_categories` (
  `guzaba_categories_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guzaba_categories_lang_id` tinyint(3) unsigned NOT NULL,
  `guzaba_categories_is_active` tinyint(4) NOT NULL,
  PRIMARY KEY (`guzaba_categories_id`),
  KEY `guzaba_categories_lang_id` (`guzaba_categories_lang_id`),
  KEY `guzaba_categories_is_active` (`guzaba_categories_is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_components`
--

DROP TABLE IF EXISTS `guzaba_components`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_components` (
  `component_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `component_name` varchar(255) NOT NULL,
  `component_url` varchar(255) NOT NULL,
  PRIMARY KEY (`component_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_controllers`
--

DROP TABLE IF EXISTS `guzaba_controllers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_controllers` (
  `controller_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `controller_name` varchar(200) NOT NULL,
  `controller_description` varchar(200) NOT NULL,
  `controller_class` varchar(200) NOT NULL,
  `controller_routes` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`controller_id`),
  UNIQUE KEY `controller_class` (`controller_class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_log_entries`
--

DROP TABLE IF EXISTS `guzaba_log_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_log_entries` (
  `log_entry_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_entry_content` varchar(2000) NOT NULL,
  PRIMARY KEY (`log_entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_logs`
--

DROP TABLE IF EXISTS `guzaba_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_logs` (
  `log_id` bigint(20) unsigned NOT NULL,
  `log_content` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_object_meta`
--

DROP TABLE IF EXISTS `guzaba_object_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_object_meta` (
  `meta_object_uuid_binary` binary(16) NOT NULL,
  `meta_object_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci GENERATED ALWAYS AS (bin_to_uuid(`meta_object_uuid_binary`)) VIRTUAL NOT NULL,
  `meta_class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `meta_object_id` bigint(20) unsigned NOT NULL,
  `meta_object_create_microtime` bigint(16) unsigned NOT NULL,
  `meta_object_last_update_microtime` bigint(16) unsigned NOT NULL,
  `meta_object_create_transaction_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_object_last_update_transaction_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`meta_object_uuid_binary`),
  UNIQUE KEY `class_name` (`meta_class_name`,`meta_object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_object_meta_new`
--

DROP TABLE IF EXISTS `guzaba_object_meta_new`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_object_meta_new` (
  `object_uuid_binary` binary(16) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `object_id` bigint(20) unsigned NOT NULL,
  `object_create_microtime` bigint(16) unsigned NOT NULL,
  `object_last_update_microtime` bigint(16) unsigned NOT NULL,
  `object_create_transaction_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `object_last_update_transction_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `object_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`object_uuid_binary`),
  UNIQUE KEY `class_name` (`class_name`,`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_object_meta_test`
--

DROP TABLE IF EXISTS `guzaba_object_meta_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_object_meta_test` (
  `object_uuid_binary` binary(16) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `object_id` bigint(20) unsigned NOT NULL,
  `object_create_microtime` bigint(16) unsigned NOT NULL,
  `object_last_update_microtime` bigint(16) unsigned NOT NULL,
  `object_create_transaction_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `object_last_update_transction_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `object_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci GENERATED ALWAYS AS (bin_to_uuid(`object_uuid_binary`)) VIRTUAL NOT NULL,
  PRIMARY KEY (`object_uuid_binary`),
  UNIQUE KEY `class_name` (`class_name`,`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_rbac_operations`
--

DROP TABLE IF EXISTS `guzaba_rbac_operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_rbac_operations` (
  `operation_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `action_name` varchar(200) NOT NULL,
  `class_name` varchar(200) NOT NULL,
  `object_id` bigint(20) DEFAULT '0' COMMENT 'object_id === NULL means operation on any object of class_name (like a privilege)',
  `operation_description` varchar(2000) NOT NULL,
  PRIMARY KEY (`operation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_rbac_permissions`
--

DROP TABLE IF EXISTS `guzaba_rbac_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_rbac_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(200) NOT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_rbac_permissions_operations`
--

DROP TABLE IF EXISTS `guzaba_rbac_permissions_operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_rbac_permissions_operations` (
  `permission_operation_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) unsigned NOT NULL,
  `operation_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_operation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_rbac_roles_permissions`
--

DROP TABLE IF EXISTS `guzaba_rbac_roles_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_rbac_roles_permissions` (
  `role_permission_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_roles`
--

DROP TABLE IF EXISTS `guzaba_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_roles` (
  `role_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(200) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_roles_hierarchy`
--

DROP TABLE IF EXISTS `guzaba_roles_hierarchy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_roles_hierarchy` (
  `role_hierarchy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `inherited_role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_hierarchy_id`),
  KEY `inherited_role_id_FK` (`inherited_role_id`),
  CONSTRAINT `inherited_role_id_FK` FOREIGN KEY (`inherited_role_id`) REFERENCES `guzaba_roles` (`role_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_tests`
--

DROP TABLE IF EXISTS `guzaba_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_tests` (
  `test_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `test_name` varchar(200) NOT NULL,
  PRIMARY KEY (`test_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_tokens`
--

DROP TABLE IF EXISTS `guzaba_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_tokens` (
  `token_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `token_uuid` binary(16) NOT NULL,
  `token_string` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `token_expiration_time` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`token_id`),
  UNIQUE KEY `token_string` (`token_string`) USING BTREE,
  KEY `user_id` (`user_id`),
  KEY `token_expiration_time` (`token_expiration_time`) USING BTREE,
  CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `guzaba_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_users`
--

DROP TABLE IF EXISTS `guzaba_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_users` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_uuid` binary(16) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(64) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guzaba_users_test`
--

DROP TABLE IF EXISTS `guzaba_users_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guzaba_users_test` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(64) NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-25  0:49:03
