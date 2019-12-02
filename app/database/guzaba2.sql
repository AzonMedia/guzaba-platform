-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 19, 2019 at 12:22 PM
-- Server version: 8.0.17
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `guzaba2`
--

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_acl_permissions`
--

CREATE TABLE `guzaba_acl_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `class_name` varchar(200) NOT NULL,
  `object_id` bigint(20) UNSIGNED DEFAULT '0',
  `action_name` varchar(200) NOT NULL,
  `permission_description` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_categories`
--

CREATE TABLE `guzaba_categories` (
  `guzaba_categories_id` int(10) UNSIGNED NOT NULL,
  `guzaba_categories_lang_id` tinyint(3) UNSIGNED NOT NULL,
  `guzaba_categories_is_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_components`
--

CREATE TABLE `guzaba_components` (
  `component_id` bigint(20) UNSIGNED NOT NULL,
  `component_name` varchar(255) NOT NULL,
  `component_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_controllers`
--

CREATE TABLE `guzaba_controllers` (
  `controller_id` bigint(20) UNSIGNED NOT NULL,
  `controller_name` varchar(200) NOT NULL,
  `controller_description` varchar(200) NOT NULL,
  `controller_class` varchar(200) NOT NULL,
  `controller_routes` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_log_entries`
--

CREATE TABLE `guzaba_log_entries` (
  `log_entry_id` bigint(20) UNSIGNED NOT NULL,
  `log_entry_content` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_object_meta`
--

CREATE TABLE `guzaba_object_meta` (
  `meta_object_uuid_binary` binary(16) NOT NULL,
  `meta_object_uuid` char(36) GENERATED ALWAYS AS (bin_to_uuid(`meta_object_uuid_binary`)) VIRTUAL NOT NULL,
  `meta_class_name` varchar(255) NOT NULL,
  `meta_object_id` bigint(20) UNSIGNED NOT NULL,
  `meta_object_create_microtime` bigint(16) UNSIGNED NOT NULL,
  `meta_object_last_update_microtime` bigint(16) UNSIGNED NOT NULL,
  `meta_object_create_transaction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_object_last_update_transction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_object_meta_new`
--

CREATE TABLE `guzaba_object_meta_new` (
  `meta_object_uuid_binary` binary(16) NOT NULL,
  `meta_class_name` varchar(255) NOT NULL,
  `meta_object_id` bigint(20) UNSIGNED NOT NULL,
  `meta_object_create_microtime` bigint(16) UNSIGNED NOT NULL,
  `meta_object_last_update_microtime` bigint(16) UNSIGNED NOT NULL,
  `meta_object_create_transaction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_object_last_update_transction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_object_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_object_meta_test`
--

CREATE TABLE `guzaba_object_meta_test` (
  `meta_object_uuid_binary` binary(16) NOT NULL,
  `meta_class_name` varchar(255) NOT NULL,
  `meta_object_id` bigint(20) UNSIGNED NOT NULL,
  `meta_object_create_microtime` bigint(16) UNSIGNED NOT NULL,
  `meta_object_last_update_microtime` bigint(16) UNSIGNED NOT NULL,
  `meta_object_create_transaction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_object_last_update_transction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_object_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci GENERATED ALWAYS AS (bin_to_uuid(`object_uuid_binary`)) VIRTUAL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_rbac_operations`
--

CREATE TABLE `guzaba_rbac_operations` (
  `operation_id` bigint(20) NOT NULL,
  `action_name` varchar(200) NOT NULL,
  `class_name` varchar(200) NOT NULL,
  `object_id` bigint(20) DEFAULT '0' COMMENT 'object_id === NULL means operation on any object of class_name (like a privilege)',
  `operation_description` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_rbac_permissions`
--

CREATE TABLE `guzaba_rbac_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `permission_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_rbac_permissions_operations`
--

CREATE TABLE `guzaba_rbac_permissions_operations` (
  `permission_operation_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `operation_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_rbac_roles_permissions`
--

CREATE TABLE `guzaba_rbac_roles_permissions` (
  `role_permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_roles`
--

CREATE TABLE `guzaba_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_roles_hierarchy`
--

CREATE TABLE `guzaba_roles_hierarchy` (
  `role_hierarchy_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `inherited_role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_tests`
--

CREATE TABLE `guzaba_tests` (
  `test_id` bigint(20) UNSIGNED NOT NULL,
  `test_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_tokens`
--

CREATE TABLE `guzaba_tokens` (
  `token_id` bigint(20) UNSIGNED NOT NULL,
  `token_uuid` binary(16) NOT NULL,
  `token_string` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token_expiration_time` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_users`
--

CREATE TABLE `guzaba_users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_uuid` binary(16) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(64) NOT NULL,
  `role_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_users_test`
--

CREATE TABLE `guzaba_users_test` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guzaba_acl_permissions`
--
ALTER TABLE `guzaba_acl_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `guzaba_categories`
--
ALTER TABLE `guzaba_categories`
  ADD PRIMARY KEY (`guzaba_categories_id`),
  ADD KEY `guzaba_categories_lang_id` (`guzaba_categories_lang_id`),
  ADD KEY `guzaba_categories_is_active` (`guzaba_categories_is_active`);

--
-- Indexes for table `guzaba_components`
--
ALTER TABLE `guzaba_components`
  ADD PRIMARY KEY (`component_id`);

--
-- Indexes for table `guzaba_controllers`
--
ALTER TABLE `guzaba_controllers`
  ADD PRIMARY KEY (`controller_id`),
  ADD UNIQUE KEY `controller_class` (`controller_class`);

--
-- Indexes for table `guzaba_log_entries`
--
ALTER TABLE `guzaba_log_entries`
  ADD PRIMARY KEY (`log_entry_id`);

--
-- Indexes for table `guzaba_object_meta`
--
ALTER TABLE `guzaba_object_meta`
  ADD PRIMARY KEY (`object_uuid_binary`),
  ADD UNIQUE KEY `class_name` (`class_name`,`object_id`);

--
-- Indexes for table `guzaba_object_meta_new`
--
ALTER TABLE `guzaba_object_meta_new`
  ADD PRIMARY KEY (`object_uuid_binary`),
  ADD UNIQUE KEY `class_name` (`class_name`,`object_id`);

--
-- Indexes for table `guzaba_object_meta_test`
--
ALTER TABLE `guzaba_object_meta_test`
  ADD PRIMARY KEY (`object_uuid_binary`),
  ADD UNIQUE KEY `class_name` (`class_name`,`object_id`);

--
-- Indexes for table `guzaba_rbac_operations`
--
ALTER TABLE `guzaba_rbac_operations`
  ADD PRIMARY KEY (`operation_id`);

--
-- Indexes for table `guzaba_rbac_permissions`
--
ALTER TABLE `guzaba_rbac_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `guzaba_rbac_permissions_operations`
--
ALTER TABLE `guzaba_rbac_permissions_operations`
  ADD PRIMARY KEY (`permission_operation_id`);

--
-- Indexes for table `guzaba_rbac_roles_permissions`
--
ALTER TABLE `guzaba_rbac_roles_permissions`
  ADD PRIMARY KEY (`role_permission_id`);

--
-- Indexes for table `guzaba_roles`
--
ALTER TABLE `guzaba_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `guzaba_roles_hierarchy`
--
ALTER TABLE `guzaba_roles_hierarchy`
  ADD PRIMARY KEY (`role_hierarchy_id`),
  ADD KEY `inherited_role_id_FK` (`inherited_role_id`);

--
-- Indexes for table `guzaba_tests`
--
ALTER TABLE `guzaba_tests`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `guzaba_tokens`
--
ALTER TABLE `guzaba_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD UNIQUE KEY `token_string` (`token_string`) USING BTREE,
  ADD KEY `user_id` (`user_id`),
  ADD KEY `token_expiration_time` (`token_expiration_time`) USING BTREE;

--
-- Indexes for table `guzaba_users`
--
ALTER TABLE `guzaba_users`
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `guzaba_users_test`
--
ALTER TABLE `guzaba_users_test`
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `guzaba_acl_permissions`
--
ALTER TABLE `guzaba_acl_permissions`
  MODIFY `permission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_categories`
--
ALTER TABLE `guzaba_categories`
  MODIFY `guzaba_categories_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_components`
--
ALTER TABLE `guzaba_components`
  MODIFY `component_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_controllers`
--
ALTER TABLE `guzaba_controllers`
  MODIFY `controller_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_log_entries`
--
ALTER TABLE `guzaba_log_entries`
  MODIFY `log_entry_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_rbac_operations`
--
ALTER TABLE `guzaba_rbac_operations`
  MODIFY `operation_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_rbac_permissions`
--
ALTER TABLE `guzaba_rbac_permissions`
  MODIFY `permission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_rbac_permissions_operations`
--
ALTER TABLE `guzaba_rbac_permissions_operations`
  MODIFY `permission_operation_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_rbac_roles_permissions`
--
ALTER TABLE `guzaba_rbac_roles_permissions`
  MODIFY `role_permission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_roles`
--
ALTER TABLE `guzaba_roles`
  MODIFY `role_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_roles_hierarchy`
--
ALTER TABLE `guzaba_roles_hierarchy`
  MODIFY `role_hierarchy_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_tests`
--
ALTER TABLE `guzaba_tests`
  MODIFY `test_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_tokens`
--
ALTER TABLE `guzaba_tokens`
  MODIFY `token_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_users`
--
ALTER TABLE `guzaba_users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_users_test`
--
ALTER TABLE `guzaba_users_test`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `guzaba_roles_hierarchy`
--
ALTER TABLE `guzaba_roles_hierarchy`
  ADD CONSTRAINT `inherited_role_id_FK` FOREIGN KEY (`inherited_role_id`) REFERENCES `guzaba_roles` (`role_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `guzaba_tokens`
--
ALTER TABLE `guzaba_tokens`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `guzaba_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
