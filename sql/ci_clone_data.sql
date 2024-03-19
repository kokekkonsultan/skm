/*
 Navicat Premium Data Transfer

 Source Server         : Maria DB
 Source Server Type    : MariaDB
 Source Server Version : 100310
 Source Host           : 127.0.0.1:3309
 Source Schema         : ci_clone_data

 Target Server Type    : MariaDB
 Target Server Version : 100310
 File Encoding         : 65001

 Date: 27/01/2022 16:56:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES (1, 'admin', 'Administrator');
INSERT INTO `groups` VALUES (2, 'members', 'General User');

-- ----------------------------
-- Table structure for login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `login` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time` int(11) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of login_attempts
-- ----------------------------
INSERT INTO `login_attempts` VALUES (2, '127.0.0.1', 'admin@admin.com', 1643265337);
INSERT INTO `login_attempts` VALUES (3, '127.0.0.1', 'ahmad-zaki', 1643265938);
INSERT INTO `login_attempts` VALUES (4, '127.0.0.1', 'ahmad-zaki', 1643265950);

-- ----------------------------
-- Table structure for manage_survey
-- ----------------------------
DROP TABLE IF EXISTS `manage_survey`;
CREATE TABLE `manage_survey`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `survey_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `survey_year` int(10) NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_start` date NOT NULL COMMENT 'Tanggal Survey Dimulai',
  `survey_end` date NOT NULL COMMENT 'Tanggal Survey Selesai',
  `is_privacy` int(5) NULL DEFAULT NULL COMMENT '1 = Public, 2 = Private',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `manage_survey_ibfk_1`(`id_user`) USING BTREE,
  CONSTRAINT `manage_survey_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of manage_survey
-- ----------------------------
INSERT INTO `manage_survey` VALUES (2, '083f1c94-3e88-46c2-8b6e-683e4f533db3', 2, 'SKM Kota Jakarta Tahun 2022', 'skm-kota-jakarta-tahun-2022', 2022, 'Survey Kepuasan Masyarakat Kota Jakarta', '2022-01-01', '2022-01-30', 1, NULL, NULL, NULL);
INSERT INTO `manage_survey` VALUES (4, '06566d9a-5601-4a62-8fe9-416324007f32', 2, 'SKM Kabupaten Sidoarjo Tahun 2022', 'skm-kabupaten-sidoarjo-tahun-2022', 2022, 'Survey Kepuasan Masyarakat Kabupaten Sidoarjo', '2022-01-01', '2022-01-30', 2, NULL, NULL, NULL);
INSERT INTO `manage_survey` VALUES (5, '7a817907-8485-46e9-bccd-f8cc28063dbf', 3, 'SKM Kota Pekanbaru 2022', 'skm-kota-pekanbaru-2022', 2022, 'Survey Kepuasan Masyarakat', '2022-01-01', '2022-01-30', 1, NULL, NULL, NULL);
INSERT INTO `manage_survey` VALUES (6, '3e25dee5-a4a8-4438-9a92-720f3e034689', 3, 'SKM Kota Pekanbaru 2021', 'skm-kota-pekanbaru-2021', 2022, 'Survey Kepuasan Masyarakat', '2022-01-01', '2022-01-30', 2, NULL, NULL, NULL);
INSERT INTO `manage_survey` VALUES (7, 'a4ec272b-c71f-4519-904e-d5dfcbd6a321', 3, 'SKM Kota Jakarta Tahun 2022', 'skm-kota-jakarta-tahun-2022', 2022, 'Survey Kepuasan Masyarakat', '2022-01-01', '2022-01-30', 1, NULL, NULL, NULL);
INSERT INTO `manage_survey` VALUES (8, '3e1aff49-a55a-4092-8cbc-7a0b8dfca3a2', 3, 'SKM Kota Jakarta Tahun 2015', 'skm-kota-jakarta-tahun-2015', 2022, 'Survey Kepuasan Masyarakat', '2022-01-01', '2022-01-30', 1, NULL, NULL, NULL);
INSERT INTO `manage_survey` VALUES (9, 'f153e3f5-5ac9-4675-b7a9-a35fe39d3336', 3, 'SKM Kota Pekanbaru 2015', 'skm-kota-pekanbaru-2015', 2022, 'Percobaan Survey Kepuasan', '2022-01-01', '2022-01-30', 1, NULL, NULL, NULL);
INSERT INTO `manage_survey` VALUES (10, 'f11d0330-af55-4639-927c-13090d0d3ac1', 3, 'Test 2023', 'test-2023', 2022, 'Percobaan Survey Kepuasan', '2022-01-01', '2022-01-31', 1, NULL, NULL, NULL);
INSERT INTO `manage_survey` VALUES (11, 'a9307c6b-8f69-4c4d-acb6-01fd4eb8d77f', 3, 'Test 2024', 'test-2024', 2022, 'Survey Kepuasan Masyarakat', '2022-01-01', '2022-01-31', 1, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for organisasi
-- ----------------------------
DROP TABLE IF EXISTS `organisasi`;
CREATE TABLE `organisasi`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_parent` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `nama_organisasi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of organisasi
-- ----------------------------

-- ----------------------------
-- Table structure for organisasi_clone
-- ----------------------------
DROP TABLE IF EXISTS `organisasi_clone`;
CREATE TABLE `organisasi_clone`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_parent` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `nama_organisasi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of organisasi_clone
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(254) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `activation_selector` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `activation_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `forgotten_password_selector` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `forgotten_password_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED NULL DEFAULT NULL,
  `remember_selector` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `remember_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED NULL DEFAULT NULL,
  `active` tinyint(1) UNSIGNED NULL DEFAULT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `company` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uc_email`(`email`) USING BTREE,
  UNIQUE INDEX `uc_activation_selector`(`activation_selector`) USING BTREE,
  UNIQUE INDEX `uc_forgotten_password_selector`(`forgotten_password_selector`) USING BTREE,
  UNIQUE INDEX `uc_remember_selector`(`remember_selector`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, '127.0.0.1', 'administrator', '$2y$10$/GpW3KvE.L8iI48NO9WB0OWomEV0qSwbg/VFaXt3xdri.kHR8BHpq', 'admin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1643265960, 1, 'Admin', 'istrator', 'ADMIN', '0');
INSERT INTO `users` VALUES (2, '127.0.0.1', 'lefi-andri', '$2y$10$Wz6BEUy4mJpr.ZsHwdm4xu/UZkyxtFEIi2PHCZMJcTe8Ni6RVB6TS', 'lefi.andri@kokek.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1643102825, 1643268235, 1, 'Lefi', 'Andri', 'Desmalova', '12345678');
INSERT INTO `users` VALUES (3, '127.0.0.1', 'dpmptsp-kota-pekanbaru', '$2y$10$dy415JTHdDF.OCCndOGtw.PzkCQEvGlCPheqh/iOlC5/v/KmPZoh2', 'ahmad.zaki@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1643265886, 1643276261, 1, 'Ahmad', 'Zaki', 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Pekanbaru', '12345678');

-- ----------------------------
-- Table structure for users_groups
-- ----------------------------
DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uc_users_groups`(`user_id`, `group_id`) USING BTREE,
  INDEX `fk_users_groups_users1_idx`(`user_id`) USING BTREE,
  INDEX `fk_users_groups_groups1_idx`(`group_id`) USING BTREE,
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users_groups
-- ----------------------------
INSERT INTO `users_groups` VALUES (6, 1, 1);
INSERT INTO `users_groups` VALUES (7, 2, 2);
INSERT INTO `users_groups` VALUES (8, 3, 2);

SET FOREIGN_KEY_CHECKS = 1;
