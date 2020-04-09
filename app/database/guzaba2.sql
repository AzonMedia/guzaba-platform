-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: mysqlhost
-- Generation Time: Apr 09, 2020 at 03:54 PM
-- Server version: 8.0.18
-- PHP Version: 7.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

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
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `object_id` bigint(20) UNSIGNED DEFAULT '0',
  `action_name` varchar(200) NOT NULL,
  `permission_description` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_acl_permissions`
--

INSERT INTO `guzaba_acl_permissions` (`permission_id`, `role_id`, `class_id`, `object_id`, `action_name`, `permission_description`) VALUES
(1, 1, 2, NULL, 'crud_action_read', ''),
(2, 1, 2, NULL, 'crud_action_create', ''),
(3, 1, 2, NULL, 'crud_action_update', ''),
(4, 1, 2, NULL, 'crud_action_delete', ''),
(5, 1, 2, NULL, 'options', ''),
(7, 1, 20, 1, 'read', ''),
(8, 1, 4, 1, 'read', ''),
(57, 1, 16, NULL, 'main', ''),
(150, 1, 17, NULL, 'create', ''),
(151, 1, 17, NULL, 'read', ''),
(152, 1, 17, NULL, 'write', ''),
(153, 1, 17, NULL, 'grant_permission', ''),
(174, 1, 12, 88, 'write', ''),
(177, 1, 12, 88, 'create', ''),
(179, 2, 12, 88, 'create', ''),
(181, 1, 17, NULL, 'delete', '');

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
-- Table structure for table `guzaba_classes`
--

CREATE TABLE `guzaba_classes` (
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `class_uuid_binary` binary(16) NOT NULL,
  `class_uuid` char(36) GENERATED ALWAYS AS (bin_to_uuid(`class_uuid_binary`)) VIRTUAL NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `class_table` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_classes`
--

INSERT INTO `guzaba_classes` (`class_id`, `class_uuid_binary`, `class_name`, `class_table`) VALUES
(1, 0x6a91ef89333c4f5dac2dbbdcf2ad20a1, 'Guzaba2\\Mvc\\ActiveRecordController', 'controllers'),
(2, 0xf99ac47aedf64884b16bec9010e2ed48, 'Guzaba2\\Orm\\ActiveRecordDefaultController', 'controllers'),
(3, 0xf01627d2017e4af193559ba7738a5948, 'Guzaba2\\Authorization\\User', 'users'),
(4, 0x049c6a756998442c8f7ad67e71ebe7b7, 'Guzaba2\\Authorization\\Role', 'roles'),
(5, 0x9d8bfae81a604ed6ba9309a52bac27e8, 'Guzaba2\\Authorization\\Rbac\\Operation', 'rbac_operations'),
(6, 0xb8e2dbdabc434be581f9d3bc51e57b73, 'Guzaba2\\Authorization\\Rbac\\PermissionOperation', 'rbac_permissions_operations'),
(7, 0x049f8f3420924787b8d1819e1ee10ff3, 'Guzaba2\\Authorization\\Rbac\\Permission', 'rbac_operations'),
(8, 0x8535d5ec90e24ac6bf53fb7054e3512a, 'Guzaba2\\Authorization\\Rbac\\RolePermission', 'rbac_roles_permissions'),
(9, 0x3c59cebe7d2545d69a99b4fbac6010ca, 'Guzaba2\\Authorization\\Acl\\Permission', 'acl_permissions'),
(10, 0x327cd295405d4d7581a2f8fcc589b650, 'Guzaba2\\Authorization\\RolesHierarchy', 'roles_hierarchy'),
(11, 0x9d7a99dae9a0474b9065c8ac7e8df3d5, 'Guzaba2\\Log\\LogEntry', 'logs'),
(12, 0xd4778fd1807d40e4b645849d68a74a2d, 'GuzabaPlatform\\Platform\\Tests\\Models\\Test', 'tests'),
(13, 0x068d50d51e42452184ba19f2920afbff, 'GuzabaPlatform\\Platform\\Tests\\Controllers\\Test', 'controllers'),
(14, 0x9bf52c03a86a422f9628bb2a7e239114, 'GuzabaPlatform\\Platform\\Admin\\Controllers\\Home', 'controllers'),
(15, 0xe944aa6791454d2282710b15da012aaf, 'GuzabaPlatform\\Platform\\Admin\\Controllers\\Navigation', 'controllers'),
(16, 0xdd83a87bd0b4432cbc484276ace3e85c, 'GuzabaPlatform\\Platform\\Home\\Controllers\\Home', 'controllers'),
(17, 0x25e4b745691e40178444cbb7927d630f, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 'components'),
(18, 0xbb131375941143f9bf43eb5f93b7c7df, 'GuzabaPlatform\\Platform\\Components\\Controllers\\Components', 'controllers'),
(19, 0xaa35d83f125d4a74bcf6b0fc31b246ee, 'GuzabaPlatform\\Platform\\Authentication\\Models\\Token', 'tokens'),
(20, 0xf68ff11ff52f45fab855b230bebfa64e, 'GuzabaPlatform\\Platform\\Authentication\\Models\\User', 'users'),
(21, 0x0ce1254fb9674bc2906a0d536e492834, 'GuzabaPlatform\\Platform\\Authentication\\Controllers\\ManageProfile', 'controllers'),
(22, 0x1aaf21b909db4a0baa3f87e67a7fecd3, 'GuzabaPlatform\\Platform\\Authentication\\Controllers\\PasswordReset', 'controllers'),
(23, 0xde90dc29f2b3432f97511944a08d8123, 'GuzabaPlatform\\Platform\\Authentication\\Controllers\\Auth', 'controllers'),
(24, 0xcd2f5d6f859a433aa0e18cb43efe79eb, 'GuzabaPlatform\\Platform\\Authentication\\Controllers\\Login', 'controllers'),
(25, 0x0508d893ada2449d80b910ba56ebf59a, 'GuzabaPlatform\\RequestCaching\\Controllers\\Admin', 'controllers'),
(26, 0x04056e84cfa54338a81044609104acc0, 'GuzabaPlatform\\Crud\\Controllers\\Permissions', 'controllers'),
(27, 0x7ed966274f4a4269b69404975f575377, 'GuzabaPlatform\\Crud\\Controllers\\Crud', 'controllers'),
(28, 0x14bd2fdfbcdc4cff9f8a9eb629b53379, 'GuzabaPlatform\\Classes\\Controllers\\Classes', 'controllers'),
(29, 0x5c1ddfda16c0437087975a1fb2564ec8, 'GuzabaPlatform\\Classes\\Controllers\\Permissions', 'controllers'),
(30, 0xceb19fed23a7420989d4efd8b607c517, 'GuzabaPlatform\\Controllers\\Controllers\\Controllers', 'controllers'),
(31, 0xe6e39d603acf409c8879a0a9710d6e49, 'GuzabaPlatform\\Controllers\\Controllers\\Permissions', 'controllers'),
(32, 0x5d0af7ceb4d24e8a8955e3df3c080f4a, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 'navigation_links'),
(33, 0x14f0dafe450243cc84701bac45eadd7c, 'GuzabaPlatform\\Navigation\\Controllers\\NavigationLink', 'controllers'),
(34, 0x30505abf4eb049b781e3a8e44ebbb812, 'GuzabaPlatform\\Navigation\\Controllers\\Navigation', 'controllers'),
(35, 0xd0ed07e1dd264fde8c98841158f7616b, 'GuzabaPlatform\\Assets\\Controllers\\Assets', 'controllers'),
(36, 0x188b27868fed43b187e054af16a101d0, 'GuzabaPlatform\\Platform\\Base\\Controllers\\Controllers', 'controllers'),
(37, 0x3cb908b13cc4497397e960ae3518ee97, 'GuzabaPlatform\\Platform\\Base\\Controllers\\Models', 'controllers'),
(38, 0x114316ed1be34bbaaa87ae7382bca163, 'GuzabaPlatform\\Navigation\\Controllers\\StaticContent', 'controllers'),
(39, 0x3e6528e20c5f49a2b1f8542a67fb1b70, 'GuzabaPlatform\\Navigation\\Controllers\\FrontendRoutes', 'controllers'),
(40, 0x1315d5441552457ca1d727c879ec4f74, 'GuzabaPlatform\\Navigation\\Controllers\\BackendRoutes', 'controllers'),
(41, 0x0c7c0f41230c46fa9790d6278a66691c, 'GuzabaPlatform\\Platform\\Tests\\Models\\TestHistory', 'tests_history'),
(42, 0x1f8a4ab97153481e8b8912d7588fdc7e, 'GuzabaPlatform\\Platform\\Tests\\Models\\TestTemporal', 'tests_temporal'),
(43, 0xf6452af0a249461da4671e536e0d39a5, 'GuzabaPlatform\\Platform\\Tests\\Controllers\\TestOrmTransactions', 'controllers'),
(44, 0x55710f28f02a468fba29e27d92f6d964, 'GuzabaPlatform\\Platform\\Tests\\Controllers\\TestTransactionDestruct', 'controllers'),
(45, 0x725b461d9a0e4e1cafce23458a8a6922, 'GuzabaPlatform\\Platform\\Application\\BaseTestController', 'controllers'),
(46, 0x4d769adcb7204b258c8c5b78bac1a149, 'GuzabaPlatform\\Tests\\Controllers\\TestTransactionDestruct', 'controllers'),
(47, 0x8d967a70a8bc44b69aaa143fa074cf8e, 'GuzabaPlatform\\Tests\\Controllers\\TestCompositeTransactionDestruct', 'controllers'),
(48, 0xab40224abf86443496aa8732dd9a770c, 'GuzabaPlatform\\Tests\\Controllers\\TestTransactionRollbackReason', 'controllers'),
(49, 0x3b3d16e2b4c946ce897f37043a5a57da, 'GuzabaPlatform\\Users\\Controllers\\Users', 'controllers'),
(50, 0x5e83e54f67a1464390f725e863c312f4, 'GuzabaPlatform\\Users\\Controllers\\User', 'controllers');

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_components`
--

CREATE TABLE `guzaba_components` (
  `component_id` bigint(20) UNSIGNED NOT NULL,
  `component_name` varchar(255) NOT NULL,
  `component_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'The source/installation URL',
  `component_namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Should be enough to identify all of the component files'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_components`
--

INSERT INTO `guzaba_components` (`component_id`, `component_name`, `component_url`, `component_namespace`) VALUES
(1, 'adf', 'adsf', 'asdf');

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
-- Table structure for table `guzaba_files`
--

CREATE TABLE `guzaba_files` (
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `parent_file_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_is_dir` tinyint(1) NOT NULL DEFAULT '0',
  `file_relative_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'The relative path to the store root',
  `file_type` varchar(50) NOT NULL COMMENT 'mime type',
  `file_size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_logs`
--

CREATE TABLE `guzaba_logs` (
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `log_class_id` bigint(20) UNSIGNED NOT NULL,
  `log_object_id` bigint(20) UNSIGNED DEFAULT NULL,
  `log_action` varchar(200) NOT NULL COMMENT 'This would correspond to the method name.',
  `log_content` varchar(2000) NOT NULL,
  `log_create_microtime` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
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
-- Table structure for table `guzaba_navigation_links`
--

CREATE TABLE `guzaba_navigation_links` (
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `parent_link_id` bigint(20) UNSIGNED DEFAULT NULL,
  `link_class_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `link_class_action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'If the link points to a controller this needs to be filled in and the link_object_id must be NULL.',
  `link_object_id` bigint(20) DEFAULT NULL,
  `link_name` varchar(200) NOT NULL,
  `link_order` smallint(5) UNSIGNED NOT NULL,
  `link_redirect` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'A link must point to an object or have a redirect set or neither if it is just a structure holder. The redirect can be a route (path to controller)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='link_class_name is not optimized to link_class_id as there will not be that many links';

--
-- Dumping data for table `guzaba_navigation_links`
--

INSERT INTO `guzaba_navigation_links` (`link_id`, `parent_link_id`, `link_class_name`, `link_class_action`, `link_object_id`, `link_name`, `link_order`, `link_redirect`) VALUES
(7, NULL, NULL, '', NULL, 'Anonymous Navigation', 0, ''),
(24, 7, '', '', NULL, 'Header Holder', 0, ''),
(25, 7, '', '', NULL, 'Footer Holder', 1, ''),
(27, 24, '', '', NULL, 'Home', 0, '/'),
(28, 24, '', '', NULL, 'Github', 2, 'http://github.com'),
(30, 24, '', '', NULL, 'Azonemdia', 1, 'http://azonmedia.com'),
(31, 24, '', '', NULL, 'Guzaba Logo', 3, '/dir1/guzaba.png'),
(32, NULL, '', '', NULL, 'Client Navigation', 1, ''),
(33, 32, '', '', NULL, 'Header Holder', 1, ''),
(34, 32, '', '', NULL, 'Footer Holder', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_navigation_links_b1`
--

CREATE TABLE `guzaba_navigation_links_b1` (
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `parent_link_id` bigint(20) UNSIGNED DEFAULT NULL,
  `link_class_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `link_object_id` bigint(20) DEFAULT NULL,
  `link_name` varchar(200) NOT NULL,
  `link_redirect` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'A link must point to an object or have a redirect set or neither if it is just a structure holder.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_navigation_links_b1`
--

INSERT INTO `guzaba_navigation_links_b1` (`link_id`, `parent_link_id`, `link_class_name`, `link_object_id`, `link_name`, `link_redirect`) VALUES
(1, NULL, '', NULL, 'sdfsdf', NULL),
(2, NULL, '', NULL, 'root2', NULL),
(3, NULL, NULL, NULL, 'root3', NULL),
(4, NULL, '', NULL, 'root4', 'rrred4'),
(5, NULL, '', NULL, '555', '5555'),
(6, NULL, '', NULL, '66', '666'),
(7, NULL, '', NULL, '777', '777'),
(8, NULL, '', NULL, '7778', '7778'),
(9, NULL, '', NULL, '11', '11'),
(10, NULL, '', NULL, '112', '112');

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_object_aliases`
--

CREATE TABLE `guzaba_object_aliases` (
  `alias_object_uuid_binary` binary(16) NOT NULL,
  `alias_object_uuid` char(36) GENERATED ALWAYS AS (bin_to_uuid(`alias_object_uuid_binary`)) VIRTUAL NOT NULL,
  `alias_object_alias` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_object_meta`
--

CREATE TABLE `guzaba_object_meta` (
  `meta_object_uuid_binary` binary(16) NOT NULL,
  `meta_object_uuid` char(36) GENERATED ALWAYS AS (bin_to_uuid(`meta_object_uuid_binary`)) VIRTUAL NOT NULL,
  `meta_class_id` bigint(20) UNSIGNED NOT NULL,
  `meta_object_id` bigint(20) UNSIGNED NOT NULL,
  `meta_object_create_microtime` bigint(16) UNSIGNED NOT NULL,
  `meta_object_last_update_microtime` bigint(16) UNSIGNED NOT NULL,
  `meta_object_create_transaction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_object_last_update_transaction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_object_create_role_id` bigint(20) UNSIGNED NOT NULL,
  `meta_object_last_update_role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_object_meta`
--

INSERT INTO `guzaba_object_meta` (`meta_object_uuid_binary`, `meta_class_id`, `meta_object_id`, `meta_object_create_microtime`, `meta_object_last_update_microtime`, `meta_object_create_transaction_id`, `meta_object_last_update_transaction_id`, `meta_object_create_role_id`, `meta_object_last_update_role_id`) VALUES
(0x00e46915dbbe443e841608549defe952, 9, 181, 1580296427313800, 1580296427313800, 0, 0, 0, 0),
(0x0713568880d34cec92b8fb100561f42d, 10, 5, 1586251167505800, 1586251167505800, 0, 0, 1, 1),
(0x0918bebec91744a7b4ccbd56b4da40bf, 12, 119, 1584355452333700, 1584355452333700, 0, 0, 1, 1),
(0x098beb672dde428e9d70263eb8f9c275, 12, 106, 1584031549288600, 1584031549288600, 0, 0, 0, 0),
(0x0a779402d2354302b6c102f1a344cdb1, 32, 28, 1583487740537700, 1583487740537700, 0, 0, 0, 0),
(0x0e6534fac8e94a37a224839b660b4049, 9, 139, 1580115159971600, 1580115159971600, 0, 0, 0, 0),
(0x0e6ac9873691498e81918a1f9526e4d5, 9, 78, 1576160766412700, 1576160766412700, 0, 0, 0, 0),
(0x1340e04297da42a29516d57198e4b45c, 9, 123, 1579878603442100, 1579878603442100, 0, 0, 0, 0),
(0x14d9ab6d8c9b466bbfecd5e1f16849fe, 9, 148, 1580129196644100, 1580129196644100, 0, 0, 0, 0),
(0x1a9957b645664ffc898394325b42496c, 4, 7, 1574781560074400, 1574781560074400, 0, 0, 0, 0),
(0x1d32624eae8e49d5ad02f27f57a20609, 9, 153, 1580130915840800, 1580130915840800, 0, 0, 0, 0),
(0x1dff36600bee4e878fcb0ff37ca0de2e, 9, 140, 1580115884669700, 1580115884669700, 0, 0, 0, 0),
(0x1e96af48b5e54287b48f3c7e5f173edc, 9, 161, 1580212344925000, 1580212344925000, 0, 0, 0, 0),
(0x26791efdafd9420cba9772d65a1aa967, 9, 166, 1580224298588400, 1580224298588400, 0, 0, 0, 0),
(0x26d470d59ee448b3a47083b0626f0382, 4, 20, 1575022605181800, 1575022605181800, 0, 0, 0, 0),
(0x289f4f31b70745ea9c9e46870bd92c06, 9, 124, 1579878665251900, 1579878665251900, 0, 0, 0, 0),
(0x28d4d3d980a149df9be294d1605a0e49, 20, 29, 1576767837428500, 1576767837428500, 0, 0, 0, 0),
(0x291064968ddc4e2ab7f2efd87ab40509, 9, 118, 1579788073861200, 1579788073861200, 0, 0, 0, 0),
(0x2e5abaee7410420399fdb27ad49776a7, 12, 103, 1584031075547500, 1584031075547500, 0, 0, 0, 0),
(0x2fb778e6fc5546479ada61319c5ba6dd, 12, 109, 1584031755757800, 1584031755757800, 0, 0, 0, 0),
(0x307dfd9850d84521923825ad0815f70a, 12, 108, 1584031675732000, 1584031675732000, 0, 0, 0, 0),
(0x31095d2c5a7d4e5e9d47a0f3cb5e974b, 9, 9, 1574942207509600, 1574942207509600, 0, 0, 0, 0),
(0x323e3207cc8d422da05c51a4e543b89d, 20, 31, 1586251167513700, 1586251167513700, 0, 0, 1, 1),
(0x341c0cf17a1b4805b718e59ada2098a3, 9, 147, 1580129189033200, 1580129189033200, 0, 0, 0, 0),
(0x34abe926db9343baae287150590af69c, 4, 27, 1575299025825700, 1575299025825700, 0, 0, 0, 0),
(0x3595a1dd51b84aa782f6adc9b1283832, 32, 34, 1583487893510400, 1583487893510400, 0, 0, 0, 0),
(0x3870a5be396d4c4a98e299fb9601d4e7, 9, 133, 1579879388046100, 1579879388046100, 0, 0, 0, 0),
(0x3920d0ce68984f1193462120277cf24c, 12, 105, 1584031207142300, 1584031207142300, 0, 0, 0, 0),
(0x3a210e5caf604ebda2c99969f576da98, 9, 88, 1576162542711600, 1576162542711600, 0, 0, 0, 0),
(0x3d10ec9fc3074623b6122cd8d4f87904, 9, 141, 1580115933920300, 1580115933920300, 0, 0, 0, 0),
(0x3e400537a48045ba9898f135204a9df9, 9, 138, 1580114269403300, 1580114269403300, 0, 0, 0, 0),
(0x3f03ea7fc0f84c8187888910a5b506c1, 9, 131, 1579879028352700, 1579879028352700, 0, 0, 0, 0),
(0x3fd95fe88ddb4769b5b49cb13ad9bbd6, 9, 49, 1575386380817900, 1575386380817900, 0, 0, 0, 0),
(0x46e4a7196cf44dacb6db1d50a3c83a1f, 4, 18, 1575021806916100, 1575021806916100, 0, 0, 0, 0),
(0x471ed6f56f9c4d18a2ed9a7cd21a1ffd, 10, 6, 1586354442145500, 1586354442145500, 0, 0, 1, 1),
(0x48fc4d7455e9422c802824f4441a87c2, 9, 125, 1579878829187200, 1579878829187200, 0, 0, 0, 0),
(0x4b8d2ec70e604becb0976acb5c72fa8f, 4, 34, 1585684343667200, 1585684343667200, 0, 0, 1, 1),
(0x4d84856486a64d4da1ab9e93e9587ff0, 9, 89, 1576165911567700, 1576165911567700, 0, 0, 0, 0),
(0x4f01bd44fa0f48c0b49ac36826304b9a, 17, 41, 1573559465388800, 1573559465388800, 0, 0, 0, 0),
(0x4f3ada52166948218131cd159fc91a5e, 4, 16, 1575021365707300, 1575021365707300, 0, 0, 0, 0),
(0x52248c889b984f17a66c134813776999, 17, 1, 1580304302071500, 1580304302071500, 0, 0, 0, 0),
(0x5232ec91b6094edfb90133589a707be6, 20, 27, 1575299025843600, 1575299025843600, 0, 0, 0, 0),
(0x525ec51c071d4ee0b65ea37fdfacc4ee, 32, 7, 1582546822997200, 1582899503000000, 0, 0, 0, 0),
(0x54b96ee0b4a147bdadf3530cfe856d17, 32, 25, 1583487189120400, 1583487826000000, 0, 0, 0, 0),
(0x5695abe233264d1bb18c1a63cbcad50b, 9, 79, 1576160768448500, 1576160768448500, 0, 0, 0, 0),
(0x56e34ae486bc440b9e8dc68f2399c7e3, 17, 26, 1573463793155000, 1573463793155000, 0, 0, 0, 0),
(0x58c9d83181274d2bb977c647286dc06d, 9, 136, 1580113866418300, 1580113866418300, 0, 0, 0, 0),
(0x592ef00f0e89440aae48b3ca7fbbaab3, 20, 6, 1574432282748100, 1574432282748100, 0, 0, 0, 0),
(0x5b20a843d2c043e7b9396035a2d1e337, 9, 149, 1580130280320000, 1580130280320000, 0, 0, 0, 0),
(0x5ba65673103a487bb37c14699e1f49da, 9, 143, 1580119723730000, 1580119723730000, 0, 0, 0, 0),
(0x5c40bffbf97211e98f16002564a26d87, 20, 1, 1567001206088700, 1571384548387700, 0, 0, 0, 0),
(0x5e0c5745543a40d687a15d4ccbd90360, 9, 150, 1580130714189300, 1580130714189300, 0, 0, 0, 0),
(0x5f93a481b910417a8c4a2754ab90803d, 9, 177, 1580293174326300, 1580293174326300, 0, 0, 0, 0),
(0x60d9f56294174833b52986a48ea2ca8f, 4, 17, 1575021543726300, 1575021543726300, 0, 0, 0, 0),
(0x6193763cc49544faa64f56f7863f8830, 4, 25, 1575298789109700, 1575298789109700, 0, 0, 0, 0),
(0x627f5c31bda84a81a670b5ab62b30610, 12, 113, 1584032208826400, 1584032208826400, 0, 0, 0, 0),
(0x632ea5cf14eb47ebb0f536494c1965df, 9, 144, 1580122614743300, 1580122614743300, 0, 0, 0, 0),
(0x63db327d0b1e4319b43f442bdbd91b6e, 4, 29, 1576767837195600, 1576767837195600, 0, 0, 0, 0),
(0x64bd2851c11b4b0b8be2df0245800b37, 9, 170, 1580226913009700, 1580226913009700, 0, 0, 0, 0),
(0x65128a98ee394d9bae8f7e74b118ba0e, 12, 112, 1584031987869500, 1584031987869500, 0, 0, 0, 0),
(0x691cbf09bf8149f997cea9ad7b88c35d, 32, 27, 1583487724712600, 1583487826000000, 0, 0, 0, 0),
(0x69f5da6534e44b13916b8201dff2729a, 9, 57, 1575974333637700, 1575974333637700, 0, 0, 0, 0),
(0x6bac274f500b46a6a37a5c9dd5f286ce, 9, 169, 1580226910266400, 1580226910266400, 0, 0, 0, 0),
(0x725c29bfe55446e1a5bd046f7c4fe9db, 32, 24, 1583487180414000, 1583487826000000, 0, 0, 0, 0),
(0x72fccdae82744fb48a248b744e87ce88, 17, 40, 1573559445188900, 1573559445188900, 0, 0, 0, 0),
(0x758709ae088a403eb4a8ac0b822b42c9, 12, 118, 1584355342299500, 1584355342299500, 0, 0, 1, 1),
(0x75cc6670f235410eaed41a5d48a7df90, 9, 142, 1580116096010100, 1580116096010100, 0, 0, 0, 0),
(0x761104baf9124095b566b781f2b4b1fc, 32, 31, 1583487858788000, 1583487866000000, 0, 0, 0, 0),
(0x76e33d41befa47168cf739825cc1a594, 9, 122, 1579878587607900, 1579878587607900, 0, 0, 0, 0),
(0x78a726f771384ed682225ba1fa2773f9, 9, 179, 1580293262445700, 1580293262445700, 0, 0, 0, 0),
(0x7a2ff9e3d4124178a868baf692760a85, 9, 155, 1580137665053600, 1580137665053600, 0, 0, 0, 0),
(0x7b899f6cfeee4923a2e2a1175a024817, 12, 110, 1584031790596600, 1584031790596600, 0, 0, 0, 0),
(0x7d614fed63914d96a6c0c27306642ec2, 4, 13, 1575020398188900, 1575020398188900, 0, 0, 0, 0),
(0x800b672b4c8343ad9a44a1b723d1e7de, 9, 158, 1580211438027600, 1580211438027600, 0, 0, 0, 0),
(0x81c10622622a46afbefcee61527f6ed2, 9, 135, 1580113858972300, 1580113858972300, 0, 0, 0, 0),
(0x8255c333b7e24655a2933a9a6962eba9, 20, 28, 1575557502973000, 1575640859000000, 0, 0, 0, 0),
(0x84feb123b44e476c848bb11d3e713669, 9, 90, 1576166057301700, 1576166057301700, 0, 0, 0, 0),
(0x85b361eb77244ee8953622aa2675b0d1, 9, 121, 1579878521409100, 1579878521409100, 0, 0, 0, 0),
(0x86cd2d684e534510bc465c5c081ec189, 9, 99, 1576754480232900, 1576754480232900, 0, 0, 0, 0),
(0x87919bba2851429f98f5c73e99b3c956, 9, 84, 1576161286203500, 1576161286203500, 0, 0, 0, 0),
(0x8822ab733e3243629f8a5176962713a1, 4, 39, 1586251167495200, 1586251167495200, 0, 0, 1, 1),
(0x885c8d27a5c34ef6aa7e30b3ad682cb6, 4, 22, 1575023058661900, 1575023058661900, 0, 0, 0, 0),
(0x88a00dcd8ed6422cb868026e9626c79b, 9, 100, 1576754481438700, 1576754481438700, 0, 0, 0, 0),
(0x8bac4926924243f9bc0e7866f4dbf401, 4, 8, 1574840369727300, 1574840369727300, 0, 0, 0, 0),
(0x8e6b110ee36846408f7fb6b659ee44d9, 9, 171, 1580226920812300, 1580226920812300, 0, 0, 0, 0),
(0x8f1cd79e91084e0f88c68fab12042f99, 12, 101, 1576064527330600, 1579695061000000, 0, 0, 0, 0),
(0x9228153aa5cd4f269ccd22319daa7603, 9, 105, 1576755397152500, 1576755397152500, 0, 0, 0, 0),
(0x95311d7dd65a40d98fa45e8c70676dfa, 9, 168, 1580224307187200, 1580224307187200, 0, 0, 0, 0),
(0x962a639336bc45e8998584f7a85f7d8a, 9, 5, 1574940946000800, 1574940946000800, 0, 0, 0, 0),
(0x9d643d8a4f0e4b478d0d1900bbe39202, 12, 121, 1585035176894600, 1585035837000000, 0, 0, 1, 1),
(0x9d8126f3f24644eeb2f5bfba004c0f77, 4, 21, 1575022905299200, 1575022905299200, 0, 0, 0, 0),
(0x9f9a3c1503ef4457abb27eedf918fc44, 9, 146, 1580127489369300, 1580127489369300, 0, 0, 0, 0),
(0xa0223bb4bfff4ad5bd81fa87e69b2afc, 9, 152, 1580130896608200, 1580130896608200, 0, 0, 0, 0),
(0xa0611d008761498da9842257f8004239, 9, 6, 1574941128024000, 1574941128024000, 0, 0, 0, 0),
(0xa192b85fc6fb4a2eb102d57e09fb83e6, 12, 104, 1584031128805300, 1584031128805300, 0, 0, 0, 0),
(0xa5b3581d15ad43ddaac0e3579633cc95, 9, 163, 1580223783594600, 1580223783594600, 0, 0, 0, 0),
(0xa91db400730945989811c48347fef483, 32, 32, 1583487873766200, 1583487873766200, 0, 0, 0, 0),
(0xaaf7079c327e45bb9b9619d594bb0f8c, 9, 132, 1579879109036300, 1579879109036300, 0, 0, 0, 0),
(0xad54145f02e7491daf3c1870f03b3369, 9, 114, 1579788021577100, 1579788021577100, 0, 0, 0, 0),
(0xaf08d9d4cc8a41ae8986161ee3e8e0ab, 9, 156, 1580137667792700, 1580137667792700, 0, 0, 0, 0),
(0xaf2f339842eb4303874f33f9f70bd864, 9, 101, 1576754482568800, 1576754482568800, 0, 0, 0, 0),
(0xb06356d06976406088e1ac7e25f7a03e, 9, 157, 1580211436936500, 1580211436936500, 0, 0, 0, 0),
(0xb0db9c853fae4762b6a054781d1b74bd, 9, 3, 1574940913888200, 1574940913888200, 0, 0, 0, 0),
(0xb37692b7c5364a408e4e0a4e7bc1cccf, 9, 55, 1575467873102600, 1575467873102600, 0, 0, 0, 0),
(0xb48376350505429ca43d3732c448ba69, 9, 8, 1574941737805800, 1574941737805800, 0, 0, 0, 0),
(0xb7e4ec97e8a94179a20126f665c32f7c, 9, 2, 1574940904397800, 1574940904397800, 0, 0, 0, 0),
(0xb7f3708695e34f7d82329a54f7b58a0f, 9, 160, 1580211998997900, 1580211998997900, 0, 0, 0, 0),
(0xba571c13af3142b4ac148d1af61b8456, 9, 165, 1580224279845700, 1580224279845700, 0, 0, 0, 0),
(0xbc218dcbcdf7420ba21f81ce6604b553, 32, 33, 1583487885696600, 1583487885696600, 0, 0, 0, 0),
(0xbec2ed5ec2654fe1bf4227c4d4f2cea1, 9, 83, 1576161283248700, 1576161283248700, 0, 0, 0, 0),
(0xbf44108cd26e410d84659154e1069432, 4, 40, 1586328607677400, 1586328607677400, 0, 0, 1, 1),
(0xbfde8fb76f404d5883e2a5b49eca94b8, 9, 119, 1579878496763800, 1579878496763800, 0, 0, 0, 0),
(0xc33b1566f67540449fadec684a447ff0, 4, 19, 1575021969596600, 1575021969596600, 0, 0, 0, 0),
(0xc37afe2aeda44bfba47bd45114e99380, 4, 15, 1575021274059000, 1575021274059000, 0, 0, 0, 0),
(0xc4aa657dfe324cf3bf5d4dc607140356, 9, 145, 1580127082551700, 1580127082551700, 0, 0, 0, 0),
(0xc4e743e5caca412d905be81ce4f5464a, 9, 162, 1580223781480300, 1580223781480300, 0, 0, 0, 0),
(0xc6067901f9ec492289a3ac50a6f11481, 12, 111, 1584031868407800, 1584031868407800, 0, 0, 0, 0),
(0xc706fd388f2341d485b17fdd04634cc1, 9, 4, 1574940924538000, 1574940924538000, 0, 0, 0, 0),
(0xc7c2073ff6194f2bab6a170e66ea0a1f, 20, 26, 1575023058687400, 1575023058687400, 0, 0, 0, 0),
(0xc8bccf3a4f6e4bdba370c7e4b7b0e3eb, 9, 120, 1579878514083300, 1579878514083300, 0, 0, 0, 0),
(0xcacbe8d2a4de4008bc849f8794d874c9, 9, 71, 1576159297042700, 1576159297042700, 0, 0, 0, 0),
(0xccb1455f82cf4e0694b7a8306f799c0a, 9, 1, 1574940888254500, 1574940888254500, 0, 0, 0, 0),
(0xce1d921698574dcab3aa5ad494ab8c4a, 17, 27, 1573463914101900, 1573463914101900, 0, 0, 0, 0),
(0xd00d296cb9b9499698a284e434f3271f, 4, 2, 1574680680805500, 1574680680805500, 0, 0, 0, 0),
(0xd3b8120d59f944be82d23c49e3a7f216, 12, 88, 1575387121620800, 1580292179000000, 0, 0, 0, 0),
(0xd7de47af445e4e0a82ca00bbe7826650, 12, 122, 1585066533296100, 1585066533296100, 0, 0, 1, 1),
(0xd80b233c7f9c462ebf5e51c9643d38f6, 17, 39, 1573559427441600, 1573559427441600, 0, 0, 0, 0),
(0xd8e38faa060946339597b7d82776cd64, 9, 164, 1580224250931700, 1580224250931700, 0, 0, 0, 0),
(0xd9c9bf1735e645659fa8d864dfe24b09, 9, 174, 1580290009005900, 1580290009005900, 0, 0, 0, 0),
(0xdb62671c4d8c46949c337e55f9869074, 4, 28, 1575557502903100, 1575557502903100, 0, 0, 0, 0),
(0xdca7804446594cbeba74e46292be7696, 4, 41, 1586328628586200, 1586328628586200, 0, 0, 1, 1),
(0xdd56e008b74744248258e1c243d8a1f1, 9, 7, 1574941668477800, 1574941668477800, 0, 0, 0, 0),
(0xdd87b8547d5741d3b6493593feda959e, 4, 14, 1575020615018700, 1575020615018700, 0, 0, 0, 0),
(0xde6743f5001b4d8ba206576733d2fb1d, 12, 99, 1575639047385500, 1575879500000000, 0, 0, 0, 0),
(0xe25cbed238b44cc785bee3100bd5eb59, 20, 30, 1585684343683800, 1585684343683800, 0, 0, 1, 1),
(0xe30db2f71f8f433e8573529984e05c97, 9, 167, 1580224305829800, 1580224305829800, 0, 0, 0, 0),
(0xe75cbd16cf2547799a3fed27d14bce9e, 9, 103, 1576754484563900, 1576754484563900, 0, 0, 0, 0),
(0xe91cd1fe78b04515a7eb8ccc1c8cb7a0, 9, 93, 1576246116937500, 1576246116937500, 0, 0, 0, 0),
(0xeaf0478f01324963b48d3097d460d537, 12, 114, 1584032262467500, 1584032262467500, 0, 0, 0, 0),
(0xee38da63277f4e28a38f4d502ceb1bd1, 4, 26, 1575298968876300, 1575298968876300, 0, 0, 0, 0),
(0xeeb9c43317ae4c378fa62ef93ef0dda8, 4, 1, 1574432282669600, 1574432282669600, 0, 0, 0, 0),
(0xef3516302a0042df8b22d2739aafb725, 9, 151, 1580130760919300, 1580130760919300, 0, 0, 0, 0),
(0xef3e816d2fe74717bb287e0b19a92a10, 12, 107, 1584031591723700, 1584031591723700, 0, 0, 0, 0),
(0xf45028cd80cc4d489760b4d515ff7e7e, 32, 30, 1583487802785000, 1583487826000000, 0, 0, 0, 0),
(0xf7eddf66f90d4e57a1ec446916a10778, 9, 87, 1576162535407600, 1576162535407600, 0, 0, 0, 0),
(0xf8c81a82dfa14551bbae4f516a0ccf77, 9, 102, 1576754483646000, 1576754483646000, 0, 0, 0, 0),
(0xf9d53d57d04645f19d83b50769b1424f, 9, 134, 1579879590315500, 1579879590315500, 0, 0, 0, 0),
(0xfae791433acb44e792c3ec2f8b11e189, 17, 28, 1573463936075300, 1573463936075300, 0, 0, 0, 0),
(0xfcab18345fa8407ea616629e8801042b, 9, 85, 1576161292100500, 1576161292100500, 0, 0, 0, 0),
(0xff81340e1cec4702a4047dcc4e49d72c, 9, 137, 1580114103501300, 1580114103501300, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_object_meta_b1`
--

CREATE TABLE `guzaba_object_meta_b1` (
  `meta_object_uuid_binary` binary(16) NOT NULL,
  `meta_object_uuid` char(36) GENERATED ALWAYS AS (bin_to_uuid(`meta_object_uuid_binary`)) VIRTUAL NOT NULL,
  `meta_class_name` varchar(255) NOT NULL,
  `meta_class_id` bigint(20) UNSIGNED NOT NULL,
  `meta_object_id` bigint(20) UNSIGNED NOT NULL,
  `meta_object_create_microtime` bigint(16) UNSIGNED NOT NULL,
  `meta_object_last_update_microtime` bigint(16) UNSIGNED NOT NULL,
  `meta_object_create_transaction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_object_last_update_transaction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_object_meta_b1`
--

INSERT INTO `guzaba_object_meta_b1` (`meta_object_uuid_binary`, `meta_class_name`, `meta_class_id`, `meta_object_id`, `meta_object_create_microtime`, `meta_object_last_update_microtime`, `meta_object_create_transaction_id`, `meta_object_last_update_transaction_id`) VALUES
(0x00e46915dbbe443e841608549defe952, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 181, 1580296427313800, 1580296427313800, 0, 0),
(0x0e6534fac8e94a37a224839b660b4049, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 139, 1580115159971600, 1580115159971600, 0, 0),
(0x0e6ac9873691498e81918a1f9526e4d5, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 78, 1576160766412700, 1576160766412700, 0, 0),
(0x1340e04297da42a29516d57198e4b45c, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 123, 1579878603442100, 1579878603442100, 0, 0),
(0x14d9ab6d8c9b466bbfecd5e1f16849fe, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 148, 1580129196644100, 1580129196644100, 0, 0),
(0x17502124c28e4e47b9e6f0d0bfcffa35, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 32, 9, 1582546849368600, 1582546849368600, 0, 0),
(0x1a9957b645664ffc898394325b42496c, 'Guzaba2\\Authorization\\Role', 4, 7, 1574781560074400, 1574781560074400, 0, 0),
(0x1d32624eae8e49d5ad02f27f57a20609, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 153, 1580130915840800, 1580130915840800, 0, 0),
(0x1dff36600bee4e878fcb0ff37ca0de2e, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 140, 1580115884669700, 1580115884669700, 0, 0),
(0x1e96af48b5e54287b48f3c7e5f173edc, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 161, 1580212344925000, 1580212344925000, 0, 0),
(0x26791efdafd9420cba9772d65a1aa967, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 166, 1580224298588400, 1580224298588400, 0, 0),
(0x26d470d59ee448b3a47083b0626f0382, 'Guzaba2\\Authorization\\Role', 4, 20, 1575022605181800, 1575022605181800, 0, 0),
(0x289f4f31b70745ea9c9e46870bd92c06, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 124, 1579878665251900, 1579878665251900, 0, 0),
(0x28d4d3d980a149df9be294d1605a0e49, 'GuzabaPlatform\\Platform\\Authentication\\Models\\User', 20, 29, 1576767837428500, 1576767837428500, 0, 0),
(0x291064968ddc4e2ab7f2efd87ab40509, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 118, 1579788073861200, 1579788073861200, 0, 0),
(0x2ba69f3679374bb7a184f5d1ba4317df, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 32, 8, 1582546830631900, 1582546830631900, 0, 0),
(0x31095d2c5a7d4e5e9d47a0f3cb5e974b, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 9, 1574942207509600, 1574942207509600, 0, 0),
(0x341c0cf17a1b4805b718e59ada2098a3, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 147, 1580129189033200, 1580129189033200, 0, 0),
(0x34abe926db9343baae287150590af69c, 'Guzaba2\\Authorization\\Role', 4, 27, 1575299025825700, 1575299025825700, 0, 0),
(0x3870a5be396d4c4a98e299fb9601d4e7, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 133, 1579879388046100, 1579879388046100, 0, 0),
(0x3a210e5caf604ebda2c99969f576da98, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 88, 1576162542711600, 1576162542711600, 0, 0),
(0x3d10ec9fc3074623b6122cd8d4f87904, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 141, 1580115933920300, 1580115933920300, 0, 0),
(0x3e400537a48045ba9898f135204a9df9, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 138, 1580114269403300, 1580114269403300, 0, 0),
(0x3f03ea7fc0f84c8187888910a5b506c1, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 131, 1579879028352700, 1579879028352700, 0, 0),
(0x3fd95fe88ddb4769b5b49cb13ad9bbd6, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 49, 1575386380817900, 1575386380817900, 0, 0),
(0x41a4ce0d815e4c15abaf830d5360ff82, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 32, 10, 1582546882492100, 1582546882492100, 0, 0),
(0x46e4a7196cf44dacb6db1d50a3c83a1f, 'Guzaba2\\Authorization\\Role', 4, 18, 1575021806916100, 1575021806916100, 0, 0),
(0x48fc4d7455e9422c802824f4441a87c2, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 125, 1579878829187200, 1579878829187200, 0, 0),
(0x4cc3179fea5544628bd139212fca5a81, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 32, 6, 1582546786656300, 1582546786656300, 0, 0),
(0x4d84856486a64d4da1ab9e93e9587ff0, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 89, 1576165911567700, 1576165911567700, 0, 0),
(0x4f01bd44fa0f48c0b49ac36826304b9a, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 17, 41, 1573559465388800, 1573559465388800, 0, 0),
(0x4f3ada52166948218131cd159fc91a5e, 'Guzaba2\\Authorization\\Role', 4, 16, 1575021365707300, 1575021365707300, 0, 0),
(0x52248c889b984f17a66c134813776999, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 17, 1, 1580304302071500, 1580304302071500, 0, 0),
(0x5232ec91b6094edfb90133589a707be6, 'GuzabaPlatform\\Platform\\Authentication\\Models\\User', 20, 27, 1575299025843600, 1575299025843600, 0, 0),
(0x525ec51c071d4ee0b65ea37fdfacc4ee, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 32, 7, 1582546822997200, 1582546822997200, 0, 0),
(0x5695abe233264d1bb18c1a63cbcad50b, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 79, 1576160768448500, 1576160768448500, 0, 0),
(0x56e34ae486bc440b9e8dc68f2399c7e3, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 17, 26, 1573463793155000, 1573463793155000, 0, 0),
(0x58c9d83181274d2bb977c647286dc06d, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 136, 1580113866418300, 1580113866418300, 0, 0),
(0x592ef00f0e89440aae48b3ca7fbbaab3, 'GuzabaPlatform\\Platform\\Authentication\\Models\\User', 20, 6, 1574432282748100, 1574432282748100, 0, 0),
(0x5b20a843d2c043e7b9396035a2d1e337, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 149, 1580130280320000, 1580130280320000, 0, 0),
(0x5ba65673103a487bb37c14699e1f49da, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 143, 1580119723730000, 1580119723730000, 0, 0),
(0x5c40bffbf97211e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Authentication\\Models\\User', 20, 1, 1567001206088700, 1571384548387700, 0, 0),
(0x5e0c5745543a40d687a15d4ccbd90360, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 150, 1580130714189300, 1580130714189300, 0, 0),
(0x5f93a481b910417a8c4a2754ab90803d, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 177, 1580293174326300, 1580293174326300, 0, 0),
(0x60d9f56294174833b52986a48ea2ca8f, 'Guzaba2\\Authorization\\Role', 4, 17, 1575021543726300, 1575021543726300, 0, 0),
(0x6193763cc49544faa64f56f7863f8830, 'Guzaba2\\Authorization\\Role', 4, 25, 1575298789109700, 1575298789109700, 0, 0),
(0x632ea5cf14eb47ebb0f536494c1965df, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 144, 1580122614743300, 1580122614743300, 0, 0),
(0x63db327d0b1e4319b43f442bdbd91b6e, 'Guzaba2\\Authorization\\Role', 4, 29, 1576767837195600, 1576767837195600, 0, 0),
(0x64bd2851c11b4b0b8be2df0245800b37, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 170, 1580226913009700, 1580226913009700, 0, 0),
(0x69f5da6534e44b13916b8201dff2729a, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 57, 1575974333637700, 1575974333637700, 0, 0),
(0x6bac274f500b46a6a37a5c9dd5f286ce, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 169, 1580226910266400, 1580226910266400, 0, 0),
(0x72fccdae82744fb48a248b744e87ce88, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 17, 40, 1573559445188900, 1573559445188900, 0, 0),
(0x75cc6670f235410eaed41a5d48a7df90, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 142, 1580116096010100, 1580116096010100, 0, 0),
(0x76e33d41befa47168cf739825cc1a594, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 122, 1579878587607900, 1579878587607900, 0, 0),
(0x78a726f771384ed682225ba1fa2773f9, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 179, 1580293262445700, 1580293262445700, 0, 0),
(0x7a2ff9e3d4124178a868baf692760a85, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 155, 1580137665053600, 1580137665053600, 0, 0),
(0x7d614fed63914d96a6c0c27306642ec2, 'Guzaba2\\Authorization\\Role', 4, 13, 1575020398188900, 1575020398188900, 0, 0),
(0x800b672b4c8343ad9a44a1b723d1e7de, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 158, 1580211438027600, 1580211438027600, 0, 0),
(0x81c10622622a46afbefcee61527f6ed2, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 135, 1580113858972300, 1580113858972300, 0, 0),
(0x8255c333b7e24655a2933a9a6962eba9, 'GuzabaPlatform\\Platform\\Authentication\\Models\\User', 20, 28, 1575557502973000, 1575640859000000, 0, 0),
(0x84feb123b44e476c848bb11d3e713669, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 90, 1576166057301700, 1576166057301700, 0, 0),
(0x85b361eb77244ee8953622aa2675b0d1, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 121, 1579878521409100, 1579878521409100, 0, 0),
(0x86cd2d684e534510bc465c5c081ec189, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 99, 1576754480232900, 1576754480232900, 0, 0),
(0x87919bba2851429f98f5c73e99b3c956, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 84, 1576161286203500, 1576161286203500, 0, 0),
(0x885c8d27a5c34ef6aa7e30b3ad682cb6, 'Guzaba2\\Authorization\\Role', 4, 22, 1575023058661900, 1575023058661900, 0, 0),
(0x88a00dcd8ed6422cb868026e9626c79b, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 100, 1576754481438700, 1576754481438700, 0, 0),
(0x8bac4926924243f9bc0e7866f4dbf401, 'Guzaba2\\Authorization\\Role', 4, 8, 1574840369727300, 1574840369727300, 0, 0),
(0x8e6b110ee36846408f7fb6b659ee44d9, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 171, 1580226920812300, 1580226920812300, 0, 0),
(0x8f1cd79e91084e0f88c68fab12042f99, 'GuzabaPlatform\\Platform\\Tests\\Models\\Test', 12, 101, 1576064527330600, 1579695061000000, 0, 0),
(0x90cf7f90f8cb4edbb4b97dd375a2c1d2, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 32, 2, 1582546593971100, 1582546593971100, 0, 0),
(0x9228153aa5cd4f269ccd22319daa7603, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 105, 1576755397152500, 1576755397152500, 0, 0),
(0x95311d7dd65a40d98fa45e8c70676dfa, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 168, 1580224307187200, 1580224307187200, 0, 0),
(0x962a639336bc45e8998584f7a85f7d8a, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 5, 1574940946000800, 1574940946000800, 0, 0),
(0x9d8126f3f24644eeb2f5bfba004c0f77, 'Guzaba2\\Authorization\\Role', 4, 21, 1575022905299200, 1575022905299200, 0, 0),
(0x9f9a3c1503ef4457abb27eedf918fc44, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 146, 1580127489369300, 1580127489369300, 0, 0),
(0xa0223bb4bfff4ad5bd81fa87e69b2afc, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 152, 1580130896608200, 1580130896608200, 0, 0),
(0xa0611d008761498da9842257f8004239, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 6, 1574941128024000, 1574941128024000, 0, 0),
(0xa5b3581d15ad43ddaac0e3579633cc95, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 163, 1580223783594600, 1580223783594600, 0, 0),
(0xa5b726ec315b4cffa5e5c0e1ad718228, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 32, 4, 1582546676481200, 1582546676481200, 0, 0),
(0xaaf7079c327e45bb9b9619d594bb0f8c, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 132, 1579879109036300, 1579879109036300, 0, 0),
(0xad54145f02e7491daf3c1870f03b3369, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 114, 1579788021577100, 1579788021577100, 0, 0),
(0xaf08d9d4cc8a41ae8986161ee3e8e0ab, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 156, 1580137667792700, 1580137667792700, 0, 0),
(0xaf2f339842eb4303874f33f9f70bd864, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 101, 1576754482568800, 1576754482568800, 0, 0),
(0xb06356d06976406088e1ac7e25f7a03e, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 157, 1580211436936500, 1580211436936500, 0, 0),
(0xb0db9c853fae4762b6a054781d1b74bd, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 3, 1574940913888200, 1574940913888200, 0, 0),
(0xb37692b7c5364a408e4e0a4e7bc1cccf, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 55, 1575467873102600, 1575467873102600, 0, 0),
(0xb48376350505429ca43d3732c448ba69, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 8, 1574941737805800, 1574941737805800, 0, 0),
(0xb7e4ec97e8a94179a20126f665c32f7c, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 2, 1574940904397800, 1574940904397800, 0, 0),
(0xb7f3708695e34f7d82329a54f7b58a0f, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 160, 1580211998997900, 1580211998997900, 0, 0),
(0xba571c13af3142b4ac148d1af61b8456, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 165, 1580224279845700, 1580224279845700, 0, 0),
(0xbec2ed5ec2654fe1bf4227c4d4f2cea1, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 83, 1576161283248700, 1576161283248700, 0, 0),
(0xbfde8fb76f404d5883e2a5b49eca94b8, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 119, 1579878496763800, 1579878496763800, 0, 0),
(0xc33b1566f67540449fadec684a447ff0, 'Guzaba2\\Authorization\\Role', 4, 19, 1575021969596600, 1575021969596600, 0, 0),
(0xc37afe2aeda44bfba47bd45114e99380, 'Guzaba2\\Authorization\\Role', 4, 15, 1575021274059000, 1575021274059000, 0, 0),
(0xc4aa657dfe324cf3bf5d4dc607140356, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 145, 1580127082551700, 1580127082551700, 0, 0),
(0xc4e743e5caca412d905be81ce4f5464a, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 162, 1580223781480300, 1580223781480300, 0, 0),
(0xc706fd388f2341d485b17fdd04634cc1, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 4, 1574940924538000, 1574940924538000, 0, 0),
(0xc7c2073ff6194f2bab6a170e66ea0a1f, 'GuzabaPlatform\\Platform\\Authentication\\Models\\User', 20, 26, 1575023058687400, 1575023058687400, 0, 0),
(0xc8bccf3a4f6e4bdba370c7e4b7b0e3eb, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 120, 1579878514083300, 1579878514083300, 0, 0),
(0xcacbe8d2a4de4008bc849f8794d874c9, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 71, 1576159297042700, 1576159297042700, 0, 0),
(0xccb1455f82cf4e0694b7a8306f799c0a, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 1, 1574940888254500, 1574940888254500, 0, 0),
(0xce1d921698574dcab3aa5ad494ab8c4a, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 17, 27, 1573463914101900, 1573463914101900, 0, 0),
(0xd00d296cb9b9499698a284e434f3271f, 'Guzaba2\\Authorization\\Role', 4, 2, 1574680680805500, 1574680680805500, 0, 0),
(0xd1e902f48f8c4e9cbec292a0a6b2a602, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 32, 1, 1582546455355000, 1582546455355000, 0, 0),
(0xd322354a58f941539bcf469866fe5fd8, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 32, 3, 1582546644161200, 1582546644161200, 0, 0),
(0xd3b8120d59f944be82d23c49e3a7f216, 'GuzabaPlatform\\Platform\\Tests\\Models\\Test', 12, 88, 1575387121620800, 1580292179000000, 0, 0),
(0xd80b233c7f9c462ebf5e51c9643d38f6, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 17, 39, 1573559427441600, 1573559427441600, 0, 0),
(0xd8e38faa060946339597b7d82776cd64, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 164, 1580224250931700, 1580224250931700, 0, 0),
(0xd9c9bf1735e645659fa8d864dfe24b09, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 174, 1580290009005900, 1580290009005900, 0, 0),
(0xdb62671c4d8c46949c337e55f9869074, 'Guzaba2\\Authorization\\Role', 4, 28, 1575557502903100, 1575557502903100, 0, 0),
(0xdd56e008b74744248258e1c243d8a1f1, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 7, 1574941668477800, 1574941668477800, 0, 0),
(0xdd87b8547d5741d3b6493593feda959e, 'Guzaba2\\Authorization\\Role', 4, 14, 1575020615018700, 1575020615018700, 0, 0),
(0xde6743f5001b4d8ba206576733d2fb1d, 'GuzabaPlatform\\Platform\\Tests\\Models\\Test', 12, 99, 1575639047385500, 1575879500000000, 0, 0),
(0xe30db2f71f8f433e8573529984e05c97, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 167, 1580224305829800, 1580224305829800, 0, 0),
(0xe75cbd16cf2547799a3fed27d14bce9e, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 103, 1576754484563900, 1576754484563900, 0, 0),
(0xe91cd1fe78b04515a7eb8ccc1c8cb7a0, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 93, 1576246116937500, 1576246116937500, 0, 0),
(0xee38da63277f4e28a38f4d502ceb1bd1, 'Guzaba2\\Authorization\\Role', 4, 26, 1575298968876300, 1575298968876300, 0, 0),
(0xeeb9c43317ae4c378fa62ef93ef0dda8, 'Guzaba2\\Authorization\\Role', 4, 1, 1574432282669600, 1574432282669600, 0, 0),
(0xef3516302a0042df8b22d2739aafb725, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 151, 1580130760919300, 1580130760919300, 0, 0),
(0xf4502bdf6f2748ed84321b2d1b846815, 'GuzabaPlatform\\Navigation\\Models\\NavigationLink', 32, 5, 1582546720542700, 1582546720542700, 0, 0),
(0xf7eddf66f90d4e57a1ec446916a10778, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 87, 1576162535407600, 1576162535407600, 0, 0),
(0xf8c81a82dfa14551bbae4f516a0ccf77, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 102, 1576754483646000, 1576754483646000, 0, 0),
(0xf9d53d57d04645f19d83b50769b1424f, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 134, 1579879590315500, 1579879590315500, 0, 0),
(0xfae791433acb44e792c3ec2f8b11e189, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 17, 28, 1573463936075300, 1573463936075300, 0, 0),
(0xfcab18345fa8407ea616629e8801042b, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 85, 1576161292100500, 1576161292100500, 0, 0),
(0xff81340e1cec4702a4047dcc4e49d72c, 'Guzaba2\\Authorization\\Acl\\Permission', 9, 137, 1580114103501300, 1580114103501300, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_object_meta_new`
--

CREATE TABLE `guzaba_object_meta_new` (
  `object_uuid_binary` binary(16) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `object_id` bigint(20) UNSIGNED NOT NULL,
  `object_create_microtime` bigint(16) UNSIGNED NOT NULL,
  `object_last_update_microtime` bigint(16) UNSIGNED NOT NULL,
  `object_create_transaction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `object_last_update_transction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `object_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_object_meta_new`
--

INSERT INTO `guzaba_object_meta_new` (`object_uuid_binary`, `class_name`, `object_id`, `object_create_microtime`, `object_last_update_microtime`, `object_create_transaction_id`, `object_last_update_transction_id`, `object_uuid`) VALUES
(0x00a739e0f4a911e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 37, 1571734230909100, 1571734230909100, 0, 0, '00a739e0-f4a9-11e9-9a55-002564a26d87'),
(0x043fa54af72111e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 54, 1572005678873500, 1572005678873500, 0, 0, '043fa54a-f721-11e9-9a55-002564a26d87'),
(0x0630f68ef40c11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 29, 1571666809214400, 1571735738131500, 0, 0, '0630f68e-f40c-11e9-9a55-002564a26d87'),
(0x0ff15c8af1ae11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 19, 1571406550561300, 1571406550561300, 0, 0, '0ff15c8a-f1ae-11e9-9a55-002564a26d87'),
(0x14856bb560065ee8868c084ce0c0b63c, 'Azonmedia\\Glog\\Home\\Models\\User_test', 3, 1571311649862900, 1571822453424300, 0, 0, '14856bb5-6006-5ee8-868c-084ce0c0b63c'),
(0x1847eb8df72011e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 50, 1572005282985700, 1572005282985700, 0, 0, '1847eb8d-f720-11e9-9a55-002564a26d87'),
(0x22f81552fb095e7282b9c1fb505c9f2e, 'Azonmedia\\Glog\\Home\\Models\\User_test', 4, 1571312953172200, 1571312953172200, 0, 0, '22f81552-fb09-5e72-82b9-c1fb505c9f2e'),
(0x253b4a4cf40d11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 30, 1571667290788100, 1571667290788100, 0, 0, '253b4a4c-f40d-11e9-9a55-002564a26d87'),
(0x261b5946f72111e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 55, 1572005735678400, 1572005735678400, 0, 0, '261b5946-f721-11e9-9a55-002564a26d87'),
(0x3655bf32f3fb11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 26, 1571659588541700, 1571659588541700, 0, 0, '3655bf32-f3fb-11e9-9a55-002564a26d87'),
(0x3a520d3df0e611e9bacb002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 8, 1571320722309700, 1571320722309700, 0, 0, '3a520d3d-f0e6-11e9-bacb-002564a26d87'),
(0x3c0e96a4f71711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 47, 1572001477538000, 1572001477538000, 0, 0, '3c0e96a4-f717-11e9-9a55-002564a26d87'),
(0x3d83ebc7f99711e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 6, 1572276357893500, 1572276357893500, 0, 0, '3d83ebc7-f997-11e9-8f16-002564a26d87'),
(0x41e4c247f99711e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 7, 1572276365239300, 1572276365239300, 0, 0, '41e4c247-f997-11e9-8f16-002564a26d87'),
(0x443d7158f98c11e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 4, 1572271644711800, 1572271644711800, 0, 0, '443d7158-f98c-11e9-8f16-002564a26d87'),
(0x45c77823f99711e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 8, 1572276371758100, 1572276371758100, 0, 0, '45c77823-f997-11e9-8f16-002564a26d87'),
(0x46345473f0e611e9bacb002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 9, 1571320742250000, 1571320742250000, 0, 0, '46345473-f0e6-11e9-bacb-002564a26d87'),
(0x46bde4cbf4a811e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 35, 1571733919001500, 1571733919001500, 0, 0, '46bde4cb-f4a8-11e9-9a55-002564a26d87'),
(0x4d17b547f99711e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 9, 1572276384027900, 1572276384027900, 0, 0, '4d17b547-f997-11e9-8f16-002564a26d87'),
(0x4e1167a0df335187885d6f9100d8fd90, 'Azonmedia\\Glog\\Home\\Models\\User_test', 1, 1571311102942100, 1571311102942100, 0, 0, '4e1167a0-df33-5187-885d-6f9100d8fd90'),
(0x4f58bbbcf1a311e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 14, 1571401932470900, 1571401932470900, 0, 0, '4f58bbbc-f1a3-11e9-9a55-002564a26d87'),
(0x5349582afb2611e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 15, 1572447633090800, 1572447633090800, 0, 0, '5349582a-fb26-11e9-b9ab-002564a26d87'),
(0x55d0fdb2f72711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 60, 1572008392701700, 1572008392701700, 0, 0, '55d0fdb2-f727-11e9-9a55-002564a26d87'),
(0x57c37079f71711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 48, 1572001524021700, 1572001524021700, 0, 0, '57c37079-f717-11e9-9a55-002564a26d87'),
(0x5cbda61ef40711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 27, 1571664806937000, 1571664806937000, 0, 0, '5cbda61e-f407-11e9-9a55-002564a26d87'),
(0x5d66090a0b6d4ca083ff3e11fd5958d3, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 22, 1572507403121200, 1572507403121200, 0, 0, '5d66090a-0b6d-4ca0-83ff-3e11fd5958d3'),
(0x6187218df41111e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 33, 1571669109935100, 1571669109935100, 0, 0, '6187218d-f411-11e9-9a55-002564a26d87'),
(0x65b1b595f40e11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 32, 1571667828435000, 1571667828435000, 0, 0, '65b1b595-f40e-11e9-9a55-002564a26d87'),
(0x65cdb63ef3f811e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 24, 1571658379690400, 1571658379690400, 0, 0, '65cdb63e-f3f8-11e9-9a55-002564a26d87'),
(0x6ae05840f72111e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 56, 1572005851054400, 1572005851054400, 0, 0, '6ae05840-f721-11e9-9a55-002564a26d87'),
(0x6c4b1468f4ac11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 44, 1571735699989300, 1571735699989300, 0, 0, '6c4b1468-f4ac-11e9-9a55-002564a26d87'),
(0x6c733afef72211e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 57, 1572006283191200, 1572006283191200, 0, 0, '6c733afe-f722-11e9-9a55-002564a26d87'),
(0x6e95f8b7f98a11e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 1, 1572270856762900, 1572270856762900, 0, 0, '6e95f8b7-f98a-11e9-8f16-002564a26d87'),
(0x6f69c407f4ab11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 42, 1571735275726800, 1571735275726800, 0, 0, '6f69c407-f4ab-11e9-9a55-002564a26d87'),
(0x74709265f98a11e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 2, 1572270866584100, 1572270866584100, 0, 0, '74709265-f98a-11e9-8f16-002564a26d87'),
(0x77206bbdf40d11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 31, 1571667428185200, 1571667428185200, 0, 0, '77206bbd-f40d-11e9-9a55-002564a26d87'),
(0x782945adf98a11e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 3, 1572270872827700, 1572270872827700, 0, 0, '782945ad-f98a-11e9-8f16-002564a26d87'),
(0x78b85f5828bb43d4bd92b93f8efa9b9b, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 23, 1572507445611500, 1572507445611500, 0, 0, '78b85f58-28bb-43d4-bd92-b93f8efa9b9b'),
(0x7998a31cf40711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 28, 1571664855348500, 1571664855348500, 0, 0, '7998a31c-f407-11e9-9a55-002564a26d87'),
(0x7efba112f17b11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 11, 1571384832518000, 1571384832518000, 0, 0, '7efba112-f17b-11e9-9a55-002564a26d87'),
(0x812574e1fb2611e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 16, 1572447710029800, 1572447710029800, 0, 0, '812574e1-fb26-11e9-b9ab-002564a26d87'),
(0x82de7095f4ac11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 45, 1571735737864800, 1571735737864800, 0, 0, '82de7095-f4ac-11e9-9a55-002564a26d87'),
(0x865bb417f4a811e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 36, 1571734025732300, 1571734025732300, 0, 0, '865bb417-f4a8-11e9-9a55-002564a26d87'),
(0x87cf6f68fb1611e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 10, 1572440849341400, 1572440849341400, 0, 0, '87cf6f68-fb16-11e9-b9ab-002564a26d87'),
(0x886eb283f3ee11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 22, 1571654142819500, 1571654142819500, 0, 0, '886eb283-f3ee-11e9-9a55-002564a26d87'),
(0x8a8a668cf1a711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 16, 1571403749769000, 1571403749769000, 0, 0, '8a8a668c-f1a7-11e9-9a55-002564a26d87'),
(0x8dc6ffdff1b411e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 20, 1571409338656900, 1571409338656900, 0, 0, '8dc6ffdf-f1b4-11e9-9a55-002564a26d87'),
(0x90e851fffb2811e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 18, 1572448595455800, 1572448595455800, 0, 0, '90e851ff-fb28-11e9-b9ab-002564a26d87'),
(0x94fcb79df4aa11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 41, 1571734909268700, 1571734909268700, 0, 0, '94fcb79d-f4aa-11e9-9a55-002564a26d87'),
(0x9808554e78ef5229b798004bcd12b5bf, 'Azonmedia\\Glog\\Home\\Models\\User_test', 2, 1571311105041200, 1571735738014800, 0, 0, '9808554e-78ef-5229-b798-004bcd12b5bf'),
(0x9ce75b8bf4ab11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 43, 1571735352047200, 1571735352047200, 0, 0, '9ce75b8b-f4ab-11e9-9a55-002564a26d87'),
(0xa1298d16fb1611e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 11, 1572440891875900, 1572440891875900, 0, 0, 'a1298d16-fb16-11e9-b9ab-002564a26d87'),
(0xa2b3dddbf71e11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 49, 1572004656225200, 1572004656225200, 0, 0, 'a2b3dddb-f71e-11e9-9a55-002564a26d87'),
(0xa426e3d6f72211e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 58, 1572006376643400, 1572006376643400, 0, 0, 'a426e3d6-f722-11e9-9a55-002564a26d87'),
(0xa559050efb1711e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 12, 1572441328389500, 1572441328389500, 0, 0, 'a559050e-fb17-11e9-b9ab-002564a26d87'),
(0xa640dd69f41111e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 34, 1571669225237400, 1571669225237400, 0, 0, 'a640dd69-f411-11e9-9a55-002564a26d87'),
(0xac8990c0f99611e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Authentication\\Models\\User', 2, 1572276114658400, 1572276114658400, 0, 0, 'ac8990c0-f996-11e9-8f16-002564a26d87'),
(0xb1b610a6f3ed11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 21, 1571653782577100, 1571653782577100, 0, 0, 'b1b610a6-f3ed-11e9-9a55-002564a26d87'),
(0xb5541e41f4a911e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 38, 1571734534032000, 1571734534032000, 0, 0, 'b5541e41-f4a9-11e9-9a55-002564a26d87'),
(0xb56b1cf3f17a11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 10, 1571384494349700, 1571384494349700, 0, 0, 'b56b1cf3-f17a-11e9-9a55-002564a26d87'),
(0xb6b554f7fb2211e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 13, 1572446212427400, 1572446212427400, 0, 0, 'b6b554f7-fb22-11e9-b9ab-002564a26d87'),
(0xb9d77a29fb2811e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 19, 1572448664131200, 1572448664131200, 0, 0, 'b9d77a29-fb28-11e9-b9ab-002564a26d87'),
(0xbd9c1bc3f1a511e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 15, 1571402976455800, 1571402976455800, 0, 0, 'bd9c1bc3-f1a5-11e9-9a55-002564a26d87'),
(0xbe56c21ef72011e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 51, 1572005561584400, 1572005561584400, 0, 0, 'be56c21e-f720-11e9-9a55-002564a26d87'),
(0xbe5be87afb2211e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 14, 1572446225263200, 1572446225263200, 0, 0, 'be5be87a-fb22-11e9-b9ab-002564a26d87'),
(0xbed95786f3f011e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 23, 1571655093109100, 1571655093109100, 0, 0, 'bed95786-f3f0-11e9-9a55-002564a26d87'),
(0xbf9a1275fb2611e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 17, 1572447814811700, 1572447814811700, 0, 0, 'bf9a1275-fb26-11e9-b9ab-002564a26d87'),
(0xc232049cf3fa11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 25, 1571659393691800, 1571659393691800, 0, 0, 'c232049c-f3fa-11e9-9a55-002564a26d87'),
(0xc34e9a62f1ad11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 18, 1571406421987600, 1571406421987600, 0, 0, 'c34e9a62-f1ad-11e9-9a55-002564a26d87'),
(0xc6b33dddf98c11e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 5, 1572271863587600, 1572271863587600, 0, 0, 'c6b33ddd-f98c-11e9-8f16-002564a26d87'),
(0xd5e85b1df1a811e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 17, 1571404305710600, 1571404305710600, 0, 0, 'd5e85b1d-f1a8-11e9-9a55-002564a26d87'),
(0xd6977957f0e511e9bacb002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 7, 1571320554992400, 1571320554992400, 0, 0, 'd6977957-f0e5-11e9-bacb-002564a26d87'),
(0xdee0cb02f4a911e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 39, 1571734603740500, 1571734603740500, 0, 0, 'dee0cb02-f4a9-11e9-9a55-002564a26d87'),
(0xe1e0473ef72011e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 52, 1572005621206100, 1572005621206100, 0, 0, 'e1e0473e-f720-11e9-9a55-002564a26d87'),
(0xe3dbffadfb2811e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 20, 1572448734624400, 1572448734624400, 0, 0, 'e3dbffad-fb28-11e9-b9ab-002564a26d87'),
(0xe440d8faf17c11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 12, 1571385431918400, 1571385431918400, 0, 0, 'e440d8fa-f17c-11e9-9a55-002564a26d87'),
(0xf6af6664f72011e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 53, 1572005656117900, 1572005656117900, 0, 0, 'f6af6664-f720-11e9-9a55-002564a26d87'),
(0xf8572c7df4a911e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 40, 1571734646459400, 1571734646459400, 0, 0, 'f8572c7d-f4a9-11e9-9a55-002564a26d87'),
(0xfa057a09f72311e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 59, 1572006950205200, 1572006950205200, 0, 0, 'fa057a09-f723-11e9-9a55-002564a26d87'),
(0xff2e5732f66c11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 46, 1571928360960600, 1571928497292200, 0, 0, 'ff2e5732-f66c-11e9-9a55-002564a26d87');

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_object_meta_test`
--

CREATE TABLE `guzaba_object_meta_test` (
  `object_uuid_binary` binary(16) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `object_id` bigint(20) UNSIGNED NOT NULL,
  `object_create_microtime` bigint(16) UNSIGNED NOT NULL,
  `object_last_update_microtime` bigint(16) UNSIGNED NOT NULL,
  `object_create_transaction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `object_last_update_transction_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `object_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci GENERATED ALWAYS AS (bin_to_uuid(`object_uuid_binary`)) VIRTUAL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_object_meta_test`
--

INSERT INTO `guzaba_object_meta_test` (`object_uuid_binary`, `class_name`, `object_id`, `object_create_microtime`, `object_last_update_microtime`, `object_create_transaction_id`, `object_last_update_transction_id`) VALUES
(0x00a739e0f4a911e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 37, 1571734230909100, 1571734230909100, 0, 0),
(0x043fa54af72111e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 54, 1572005678873500, 1572005678873500, 0, 0),
(0x0630f68ef40c11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 29, 1571666809214400, 1571735738131500, 0, 0),
(0x0ff15c8af1ae11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 19, 1571406550561300, 1571406550561300, 0, 0),
(0x14856bb560065ee8868c084ce0c0b63c, 'Azonmedia\\Glog\\Home\\Models\\User_test', 3, 1571311649862900, 1571822453424300, 0, 0),
(0x1847eb8df72011e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 50, 1572005282985700, 1572005282985700, 0, 0),
(0x22f81552fb095e7282b9c1fb505c9f2e, 'Azonmedia\\Glog\\Home\\Models\\User_test', 4, 1571312953172200, 1571312953172200, 0, 0),
(0x253b4a4cf40d11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 30, 1571667290788100, 1571667290788100, 0, 0),
(0x261b5946f72111e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 55, 1572005735678400, 1572005735678400, 0, 0),
(0x3655bf32f3fb11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 26, 1571659588541700, 1571659588541700, 0, 0),
(0x3a520d3df0e611e9bacb002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 8, 1571320722309700, 1571320722309700, 0, 0),
(0x3c0e96a4f71711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 47, 1572001477538000, 1572001477538000, 0, 0),
(0x3d83ebc7f99711e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 6, 1572276357893500, 1572276357893500, 0, 0),
(0x41e4c247f99711e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 7, 1572276365239300, 1572276365239300, 0, 0),
(0x443d7158f98c11e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 4, 1572271644711800, 1572271644711800, 0, 0),
(0x45c77823f99711e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 8, 1572276371758100, 1572276371758100, 0, 0),
(0x46345473f0e611e9bacb002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 9, 1571320742250000, 1571320742250000, 0, 0),
(0x46bde4cbf4a811e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 35, 1571733919001500, 1571733919001500, 0, 0),
(0x4d17b547f99711e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 9, 1572276384027900, 1572276384027900, 0, 0),
(0x4e1167a0df335187885d6f9100d8fd90, 'Azonmedia\\Glog\\Home\\Models\\User_test', 1, 1571311102942100, 1571311102942100, 0, 0),
(0x4f58bbbcf1a311e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 14, 1571401932470900, 1571401932470900, 0, 0),
(0x5349582afb2611e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 15, 1572447633090800, 1572447633090800, 0, 0),
(0x55d0fdb2f72711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 60, 1572008392701700, 1572008392701700, 0, 0),
(0x57c37079f71711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 48, 1572001524021700, 1572001524021700, 0, 0),
(0x5cbda61ef40711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 27, 1571664806937000, 1571664806937000, 0, 0),
(0x6187218df41111e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 33, 1571669109935100, 1571669109935100, 0, 0),
(0x65b1b595f40e11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 32, 1571667828435000, 1571667828435000, 0, 0),
(0x65cdb63ef3f811e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 24, 1571658379690400, 1571658379690400, 0, 0),
(0x6ae05840f72111e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 56, 1572005851054400, 1572005851054400, 0, 0),
(0x6c4b1468f4ac11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 44, 1571735699989300, 1571735699989300, 0, 0),
(0x6c733afef72211e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 57, 1572006283191200, 1572006283191200, 0, 0),
(0x6e95f8b7f98a11e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 1, 1572270856762900, 1572270856762900, 0, 0),
(0x6f69c407f4ab11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 42, 1571735275726800, 1571735275726800, 0, 0),
(0x74709265f98a11e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 2, 1572270866584100, 1572270866584100, 0, 0),
(0x77206bbdf40d11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 31, 1571667428185200, 1571667428185200, 0, 0),
(0x782945adf98a11e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 3, 1572270872827700, 1572270872827700, 0, 0),
(0x7998a31cf40711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 28, 1571664855348500, 1571664855348500, 0, 0),
(0x7efba112f17b11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 11, 1571384832518000, 1571384832518000, 0, 0),
(0x812574e1fb2611e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 16, 1572447710029800, 1572447710029800, 0, 0),
(0x82de7095f4ac11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 45, 1571735737864800, 1571735737864800, 0, 0),
(0x865bb417f4a811e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 36, 1571734025732300, 1571734025732300, 0, 0),
(0x87cf6f68fb1611e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 10, 1572440849341400, 1572440849341400, 0, 0),
(0x886eb283f3ee11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 22, 1571654142819500, 1571654142819500, 0, 0),
(0x8a8a668cf1a711e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 16, 1571403749769000, 1571403749769000, 0, 0),
(0x8dc6ffdff1b411e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 20, 1571409338656900, 1571409338656900, 0, 0),
(0x90e851fffb2811e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 18, 1572448595455800, 1572448595455800, 0, 0),
(0x94fcb79df4aa11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 41, 1571734909268700, 1571734909268700, 0, 0),
(0x9808554e78ef5229b798004bcd12b5bf, 'Azonmedia\\Glog\\Home\\Models\\User_test', 2, 1571311105041200, 1571735738014800, 0, 0),
(0x9ce75b8bf4ab11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 43, 1571735352047200, 1571735352047200, 0, 0),
(0xa1298d16fb1611e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 11, 1572440891875900, 1572440891875900, 0, 0),
(0xa2b3dddbf71e11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 49, 1572004656225200, 1572004656225200, 0, 0),
(0xa426e3d6f72211e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 58, 1572006376643400, 1572006376643400, 0, 0),
(0xa559050efb1711e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 12, 1572441328389500, 1572441328389500, 0, 0),
(0xa640dd69f41111e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 34, 1571669225237400, 1571669225237400, 0, 0),
(0xac8990c0f99611e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Authentication\\Models\\User', 2, 1572276114658400, 1572276114658400, 0, 0),
(0xb1b610a6f3ed11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 21, 1571653782577100, 1571653782577100, 0, 0),
(0xb5541e41f4a911e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 38, 1571734534032000, 1571734534032000, 0, 0),
(0xb56b1cf3f17a11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 10, 1571384494349700, 1571384494349700, 0, 0),
(0xb6b554f7fb2211e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 13, 1572446212427400, 1572446212427400, 0, 0),
(0xb9d77a29fb2811e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 19, 1572448664131200, 1572448664131200, 0, 0),
(0xbd9c1bc3f1a511e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 15, 1571402976455800, 1571402976455800, 0, 0),
(0xbe56c21ef72011e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 51, 1572005561584400, 1572005561584400, 0, 0),
(0xbe5be87afb2211e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 14, 1572446225263200, 1572446225263200, 0, 0),
(0xbed95786f3f011e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 23, 1571655093109100, 1571655093109100, 0, 0),
(0xbf9a1275fb2611e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 17, 1572447814811700, 1572447814811700, 0, 0),
(0xc232049cf3fa11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 25, 1571659393691800, 1571659393691800, 0, 0),
(0xc34e9a62f1ad11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 18, 1571406421987600, 1571406421987600, 0, 0),
(0xc6b33dddf98c11e98f16002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 5, 1572271863587600, 1572271863587600, 0, 0),
(0xd5e85b1df1a811e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 17, 1571404305710600, 1571404305710600, 0, 0),
(0xd6977957f0e511e9bacb002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 7, 1571320554992400, 1571320554992400, 0, 0),
(0xdee0cb02f4a911e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 39, 1571734603740500, 1571734603740500, 0, 0),
(0xe1e0473ef72011e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 52, 1572005621206100, 1572005621206100, 0, 0),
(0xe3dbffadfb2811e9b9ab002564a26d87, 'GuzabaPlatform\\Platform\\Components\\Models\\Component', 20, 1572448734624400, 1572448734624400, 0, 0),
(0xe440d8faf17c11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 12, 1571385431918400, 1571385431918400, 0, 0),
(0xf6af6664f72011e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 53, 1572005656117900, 1572005656117900, 0, 0),
(0xf8572c7df4a911e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 40, 1571734646459400, 1571734646459400, 0, 0),
(0xfa057a09f72311e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 59, 1572006950205200, 1572006950205200, 0, 0),
(0xff2e5732f66c11e99a55002564a26d87, 'Azonmedia\\Glog\\Home\\Models\\User_test', 46, 1571928360960600, 1571928497292200, 0, 0);

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
  `role_name` varchar(200) NOT NULL,
  `role_is_user` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_roles`
--

INSERT INTO `guzaba_roles` (`role_id`, `role_name`, `role_is_user`) VALUES
(1, 'ANONYMOUS', 0),
(2, 'mario', 1),
(7, 'ivo4', 1),
(8, 'ivo5', 1),
(13, 'ivo', 1),
(14, 'ivo3213', 1),
(15, 'a1', 1),
(16, 'a2', 1),
(17, 'ivo3', 1),
(18, 'ivo4', 1),
(19, 'ivo5', 1),
(20, 'ivo6', 1),
(21, 'ivo8', 1),
(22, 'gsd', 1),
(23, 'dubai', 1),
(24, 'dubai2', 1),
(25, 'dubai3', 1),
(26, 'dubai1', 1),
(27, 'dubai2', 1),
(28, 'mario', 1),
(29, 'ttt', 1),
(34, 'vesko2', 1),
(39, 'vesko', 1),
(40, 'ADMINISTRATOR', 0),
(41, 'CLIENT', 0);

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_roles_hierarchy`
--

CREATE TABLE `guzaba_roles_hierarchy` (
  `role_hierarchy_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `inherited_role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_roles_hierarchy`
--

INSERT INTO `guzaba_roles_hierarchy` (`role_hierarchy_id`, `role_id`, `inherited_role_id`) VALUES
(5, 39, 1),
(6, 39, 41);

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_tests`
--

CREATE TABLE `guzaba_tests` (
  `test_id` bigint(20) UNSIGNED NOT NULL,
  `test_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_tests`
--

INSERT INTO `guzaba_tests` (`test_id`, `test_name`) VALUES
(88, 'some test value 2 666'),
(99, 'test 99'),
(101, 'test 101 ggg'),
(103, ''),
(104, 'some test value 333'),
(105, 'some test value 333'),
(106, 'some test value 33355'),
(107, 'some test value 33355'),
(108, 'some test value 33355'),
(109, 'some test value 33355'),
(110, 'some test value 33355'),
(111, 'some test value 33355'),
(112, 'some test value 33355'),
(113, 'some test value 33355'),
(114, 'some test value 33355'),
(115, 'some test value 333'),
(116, 'some test value 333'),
(117, 'some test value 333'),
(118, 'some test value 333'),
(119, 'some test value 333'),
(121, 'asdasd 4445 66'),
(122, 'some test value 333');

-- --------------------------------------------------------

--
-- Table structure for table `guzaba_tests_temporal`
--

CREATE TABLE `guzaba_tests_temporal` (
  `temporal_record_id` bigint(20) UNSIGNED NOT NULL,
  `temporal_record_from_microtime` bigint(20) UNSIGNED NOT NULL,
  `temporal_record_to_microtime` bigint(20) UNSIGNED NOT NULL,
  `temporal_record_role_id` bigint(20) UNSIGNED NOT NULL,
  `test_id` bigint(20) UNSIGNED NOT NULL,
  `test_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_tests_temporal`
--

INSERT INTO `guzaba_tests_temporal` (`temporal_record_id`, `temporal_record_from_microtime`, `temporal_record_to_microtime`, `temporal_record_role_id`, `test_id`, `test_name`) VALUES
(1, 1584031675000000, 0, 1, 108, 'some test value 33355'),
(2, 1584031755000000, 0, 1, 109, 'some test value 33355'),
(3, 1584031790000000, 0, 1, 110, 'some test value 33355'),
(4, 1584031868000000, 0, 1, 111, 'some test value 33355'),
(5, 1584031988000000, 0, 1, 112, 'some test value 33355'),
(6, 1584032208000000, 0, 1, 113, 'some test value 33355'),
(7, 1584032262000000, 0, 1, 114, 'some test value 33355'),
(8, 1584355519523450, 1584360055459113, 1, 119, 'some test value 333'),
(9, 1584360055459113, 1584360087840650, 1, 119, 'another test value 446'),
(10, 1584360087840650, 1584360314128714, 1, 119, 'another test value 4467'),
(11, 1585035176958310, 1585035206904845, 1, 121, 'some test value 333'),
(12, 1585035206904845, 1585035366066189, 1, 121, 'updated test value 1'),
(13, 1585035366066189, 1585035392746375, 1, 121, 'asdasd'),
(14, 1585035392746375, 1585035491824586, 1, 121, 'asdasd22 2 22'),
(15, 1585035491824586, 1585035837737211, 1, 121, 'asdasd 4445'),
(16, 1585035837737211, 0, 1, 121, 'asdasd 4445 66'),
(17, 1585066533656119, 0, 1, 122, 'some test value 333');

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
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(64) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `user_is_disabled` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guzaba_users`
--

INSERT INTO `guzaba_users` (`user_id`, `user_name`, `user_email`, `user_password`, `role_id`, `user_is_disabled`) VALUES
(1, 'anonymous', 'anonymous@anonymous.none', '', 1, 0),
(2, 'test', 'test@test.test', '$2y$12$janKBnkOSU.SnlOZmBeAmO.pnpCITf3QEl7Shu3LyCab.eVpSrGSu', 1, 0),
(6, 'ivo', 'ivo@azonmedia.com', '$2y$12$RLZtX4uufk7zTnAnEN7LlO4YKIx2khoYEl/2OhSO/Dd.zpA3Dy3xy', 1, 0),
(26, 'gsd', 'ivo@azonmedia.com44', '$2y$12$nn9r2oI2Yv0LDdI1igOxAOde5KRR2yU0RAYQGClLhGRSVTgCm8dH2', 22, 0),
(27, 'dubai2', 'dubai2@azonmedia.com', '$2y$12$VYaCGsVo9mEfEmt/719gTurhQrPv.uiYT5rn/5S9vKtowrpoMeQd6', 27, 0),
(28, 'mario', 'mario@azonmedia.com', '$2y$12$R1/EuwjOYKkDuigVRn1cfuy6bGLKgj5fW5DfIS.v7EzcBBIuutLCG', 28, 0),
(29, 'ttt', 'ttt@ttt.ttt', '$2y$12$UEFbtlxNey/aqNSLOcrrGesRft8IHSLJRX74bGzQX3dqZxTK6CBjS', 29, 0),
(30, 'vesko2', 'vesko2@azonmedia.com', '', 34, 0),
(31, 'vesko', 'vesko@azonmedia.com', '$2y$12$C9iwpCpwv/UPgMtvKpgVi.4x7ioKZxMY9eJ5u2q22LHMsfdjniZGC', 39, 0);

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
-- Dumping data for table `guzaba_users_test`
--

INSERT INTO `guzaba_users_test` (`user_id`, `user_name`, `user_email`, `user_password`) VALUES
(1, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(2, 'test aaa EDITED1571735737', 'aaaa@azonmedia.com', 'aaa123'),
(3, 'test test1571822453', 'aaaa@azonmedia.com', 'aaa123'),
(4, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(5, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(6, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(7, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(8, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(9, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(10, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(11, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(12, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(13, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(14, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(15, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(16, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(17, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(18, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(19, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(20, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(21, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(22, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(23, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(24, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(25, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(26, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(27, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(28, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(29, 'selected by uuid1571735738', 'aaaa@azonmedia.com', 'aaa123'),
(30, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(31, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(32, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(33, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(34, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(35, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(36, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(37, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(38, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(39, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(40, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(41, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(42, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(43, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(44, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(45, 'test aaa', 'aaaa@azonmedia.com', 'aaa123'),
(46, 'edited1571928497', 'aa23423a@azonmedia.com', 'aaa123234'),
(47, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(48, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(49, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(50, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(51, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(52, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(53, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(54, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(55, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(56, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(57, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(58, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(59, 'test name', 'aa23423a@azonmedia.com', 'aaa123234'),
(60, 'test name', 'aa23423a@azonmedia.com', 'aaa123234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guzaba_acl_permissions`
--
ALTER TABLE `guzaba_acl_permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD UNIQUE KEY `unique_permission` (`role_id`,`class_id`,`object_id`,`action_name`) USING BTREE,
  ADD KEY `class_id` (`class_id`,`object_id`),
  ADD KEY `action_name` (`action_name`);

--
-- Indexes for table `guzaba_categories`
--
ALTER TABLE `guzaba_categories`
  ADD PRIMARY KEY (`guzaba_categories_id`),
  ADD KEY `guzaba_categories_lang_id` (`guzaba_categories_lang_id`),
  ADD KEY `guzaba_categories_is_active` (`guzaba_categories_is_active`);

--
-- Indexes for table `guzaba_classes`
--
ALTER TABLE `guzaba_classes`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `class_uuid_binary` (`class_uuid_binary`),
  ADD UNIQUE KEY `class_name` (`class_name`),
  ADD UNIQUE KEY `class_uuid` (`class_uuid`);

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
-- Indexes for table `guzaba_files`
--
ALTER TABLE `guzaba_files`
  ADD PRIMARY KEY (`file_id`),
  ADD UNIQUE KEY `file_relative_path` (`file_relative_path`),
  ADD KEY `parent_file_id_FK` (`parent_file_id`);

--
-- Indexes for table `guzaba_logs`
--
ALTER TABLE `guzaba_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `logs_class_id_FK` (`log_class_id`),
  ADD KEY `logs_role_id_FK` (`role_id`);

--
-- Indexes for table `guzaba_log_entries`
--
ALTER TABLE `guzaba_log_entries`
  ADD PRIMARY KEY (`log_entry_id`);

--
-- Indexes for table `guzaba_navigation_links`
--
ALTER TABLE `guzaba_navigation_links`
  ADD PRIMARY KEY (`link_id`);

--
-- Indexes for table `guzaba_navigation_links_b1`
--
ALTER TABLE `guzaba_navigation_links_b1`
  ADD PRIMARY KEY (`link_id`);

--
-- Indexes for table `guzaba_object_aliases`
--
ALTER TABLE `guzaba_object_aliases`
  ADD UNIQUE KEY `alias_object_uuid_binary` (`alias_object_uuid_binary`,`alias_object_alias`);

--
-- Indexes for table `guzaba_object_meta`
--
ALTER TABLE `guzaba_object_meta`
  ADD PRIMARY KEY (`meta_object_uuid_binary`),
  ADD KEY `meta_class_id_FK` (`meta_class_id`);

--
-- Indexes for table `guzaba_object_meta_b1`
--
ALTER TABLE `guzaba_object_meta_b1`
  ADD PRIMARY KEY (`meta_object_uuid_binary`);

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
  ADD UNIQUE KEY `unique_role_relation` (`role_id`,`inherited_role_id`),
  ADD KEY `inherited_role_id_FK` (`inherited_role_id`);

--
-- Indexes for table `guzaba_tests`
--
ALTER TABLE `guzaba_tests`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `guzaba_tests_temporal`
--
ALTER TABLE `guzaba_tests_temporal`
  ADD PRIMARY KEY (`temporal_record_id`),
  ADD KEY `test_id` (`test_id`);

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
  MODIFY `permission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `guzaba_categories`
--
ALTER TABLE `guzaba_categories`
  MODIFY `guzaba_categories_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_classes`
--
ALTER TABLE `guzaba_classes`
  MODIFY `class_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `guzaba_components`
--
ALTER TABLE `guzaba_components`
  MODIFY `component_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `guzaba_controllers`
--
ALTER TABLE `guzaba_controllers`
  MODIFY `controller_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_files`
--
ALTER TABLE `guzaba_files`
  MODIFY `file_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_logs`
--
ALTER TABLE `guzaba_logs`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_log_entries`
--
ALTER TABLE `guzaba_log_entries`
  MODIFY `log_entry_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_navigation_links`
--
ALTER TABLE `guzaba_navigation_links`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `guzaba_navigation_links_b1`
--
ALTER TABLE `guzaba_navigation_links_b1`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `role_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `guzaba_roles_hierarchy`
--
ALTER TABLE `guzaba_roles_hierarchy`
  MODIFY `role_hierarchy_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `guzaba_tests`
--
ALTER TABLE `guzaba_tests`
  MODIFY `test_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `guzaba_tests_temporal`
--
ALTER TABLE `guzaba_tests_temporal`
  MODIFY `temporal_record_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `guzaba_tokens`
--
ALTER TABLE `guzaba_tokens`
  MODIFY `token_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guzaba_users`
--
ALTER TABLE `guzaba_users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `guzaba_users_test`
--
ALTER TABLE `guzaba_users_test`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `guzaba_acl_permissions`
--
ALTER TABLE `guzaba_acl_permissions`
  ADD CONSTRAINT `class_id_FK` FOREIGN KEY (`class_id`) REFERENCES `guzaba_classes` (`class_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `guzaba_files`
--
ALTER TABLE `guzaba_files`
  ADD CONSTRAINT `parent_file_id_FK` FOREIGN KEY (`parent_file_id`) REFERENCES `guzaba_files` (`file_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `guzaba_logs`
--
ALTER TABLE `guzaba_logs`
  ADD CONSTRAINT `logs_class_id_FK` FOREIGN KEY (`log_class_id`) REFERENCES `guzaba_classes` (`class_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `logs_role_id_FK` FOREIGN KEY (`role_id`) REFERENCES `guzaba_roles` (`role_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `guzaba_object_aliases`
--
ALTER TABLE `guzaba_object_aliases`
  ADD CONSTRAINT `object_aliases_FK1` FOREIGN KEY (`alias_object_uuid_binary`) REFERENCES `guzaba_object_meta` (`meta_object_uuid_binary`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `guzaba_object_meta`
--
ALTER TABLE `guzaba_object_meta`
  ADD CONSTRAINT `meta_class_id_FK` FOREIGN KEY (`meta_class_id`) REFERENCES `guzaba_classes` (`class_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `guzaba_roles_hierarchy`
--
ALTER TABLE `guzaba_roles_hierarchy`
  ADD CONSTRAINT `inherited_role_id_FK` FOREIGN KEY (`inherited_role_id`) REFERENCES `guzaba_roles` (`role_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `role_id_FK` FOREIGN KEY (`role_id`) REFERENCES `guzaba_roles` (`role_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `guzaba_tokens`
--
ALTER TABLE `guzaba_tokens`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `guzaba_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
