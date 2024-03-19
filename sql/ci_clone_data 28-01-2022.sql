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

 Date: 28/01/2022 16:17:57
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
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of login_attempts
-- ----------------------------
INSERT INTO `login_attempts` VALUES (2, '127.0.0.1', 'admin@admin.com', 1643265337);
INSERT INTO `login_attempts` VALUES (3, '127.0.0.1', 'ahmad-zaki', 1643265938);
INSERT INTO `login_attempts` VALUES (4, '127.0.0.1', 'ahmad-zaki', 1643265950);
INSERT INTO `login_attempts` VALUES (5, '127.0.0.1', 'lefi.andri@kokek.com', 1643332706);

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
  `table_identity` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `manage_survey_ibfk_1`(`id_user`) USING BTREE,
  CONSTRAINT `manage_survey_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of manage_survey
-- ----------------------------
INSERT INTO `manage_survey` VALUES (48, '47d99795-4d48-45bd-9d05-b852ecd69468', 3, 'SKM Kota Jakarta Tahun 2022', 'skm-kota-jakarta-tahun-2022', 2022, 'Survey Kepuasan Masyarakat', '2022-01-01', '2022-01-30', 1, 'cst48', NULL, NULL, NULL);
INSERT INTO `manage_survey` VALUES (49, '15dc171c-0841-4c77-96b2-b7b4a1151c56', 3, 'SKM Kabupaten Sidoarjo Tahun 2022', 'skm-kabupaten-sidoarjo-tahun-2022', 2022, 'Survey Kepuasan Masyarakat', '2022-01-01', '2022-01-30', 1, 'cst49', NULL, NULL, NULL);
INSERT INTO `manage_survey` VALUES (50, 'ab1e5c01-ce0a-4a04-b5e8-97298f670c18', 3, 'Survey Kepuasan Masyarakat Coba', 'survey-kepuasan-masyarakat-coba', 2022, 'Survey Kepuasan Masyarakat', '2022-01-01', '2022-01-23', 2, 'cst50', NULL, NULL, NULL);

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
-- Table structure for responden_cst48
-- ----------------------------
DROP TABLE IF EXISTS `responden_cst48`;
CREATE TABLE `responden_cst48`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_kota_kabupaten` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `id_provinsi` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_lokasi` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_barang_jasa` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_jenis_kelamin` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_umur` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_pendidikan_akhir` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_pekerjaan_utama` int(10) UNSIGNED NULL DEFAULT NULL,
  `pekerjaan_lainnya` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_pendapatan_per_bulan` int(10) UNSIGNED NULL DEFAULT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `handphone` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `file_signature` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_kota_kabupaten` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of responden_cst48
-- ----------------------------

-- ----------------------------
-- Table structure for responden_cst49
-- ----------------------------
DROP TABLE IF EXISTS `responden_cst49`;
CREATE TABLE `responden_cst49`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_kota_kabupaten` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `id_provinsi` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_lokasi` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_barang_jasa` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_jenis_kelamin` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_umur` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_pendidikan_akhir` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_pekerjaan_utama` int(10) UNSIGNED NULL DEFAULT NULL,
  `pekerjaan_lainnya` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_pendapatan_per_bulan` int(10) UNSIGNED NULL DEFAULT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `handphone` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `file_signature` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_kota_kabupaten` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of responden_cst49
-- ----------------------------

-- ----------------------------
-- Table structure for responden_cst50
-- ----------------------------
DROP TABLE IF EXISTS `responden_cst50`;
CREATE TABLE `responden_cst50`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_kota_kabupaten` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `id_provinsi` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_lokasi` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_barang_jasa` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_jenis_kelamin` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_umur` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_pendidikan_akhir` int(10) UNSIGNED NULL DEFAULT NULL,
  `id_pekerjaan_utama` int(10) UNSIGNED NULL DEFAULT NULL,
  `pekerjaan_lainnya` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_pendapatan_per_bulan` int(10) UNSIGNED NULL DEFAULT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `handphone` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `file_signature` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_kota_kabupaten` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of responden_cst50
-- ----------------------------

-- ----------------------------
-- Table structure for survey_cst48
-- ----------------------------
DROP TABLE IF EXISTS `survey_cst48`;
CREATE TABLE `survey_cst48`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_responden` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `id_surveyor` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `id_kuesioner` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `waktu_isi` datetime(0) NULL DEFAULT NULL,
  `foto_selfie` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `foto_ktp` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `is_input_surveyor` int(5) NULL DEFAULT NULL,
  `is_testing` int(5) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `survey_cst48_ibfk_1`(`id_responden`) USING BTREE,
  CONSTRAINT `survey_cst48_ibfk_1` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst48` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of survey_cst48
-- ----------------------------

-- ----------------------------
-- Table structure for survey_cst49
-- ----------------------------
DROP TABLE IF EXISTS `survey_cst49`;
CREATE TABLE `survey_cst49`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_responden` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `id_surveyor` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `id_kuesioner` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `waktu_isi` datetime(0) NULL DEFAULT NULL,
  `foto_selfie` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `foto_ktp` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `is_input_surveyor` int(5) NULL DEFAULT NULL,
  `is_testing` int(5) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `survey_cst49_ibfk_1`(`id_responden`) USING BTREE,
  CONSTRAINT `survey_cst49_ibfk_1` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst49` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of survey_cst49
-- ----------------------------

-- ----------------------------
-- Table structure for survey_cst50
-- ----------------------------
DROP TABLE IF EXISTS `survey_cst50`;
CREATE TABLE `survey_cst50`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_responden` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `id_surveyor` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `id_kuesioner` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `waktu_isi` datetime(0) NULL DEFAULT NULL,
  `foto_selfie` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `foto_ktp` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `is_input_surveyor` int(5) NULL DEFAULT NULL,
  `is_testing` int(5) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `survey_cst50_ibfk_1`(`id_responden`) USING BTREE,
  CONSTRAINT `survey_cst50_ibfk_1` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst50` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of survey_cst50
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
INSERT INTO `users` VALUES (3, '127.0.0.1', 'dpmptsp-kota-pekanbaru', '$2y$10$dy415JTHdDF.OCCndOGtw.PzkCQEvGlCPheqh/iOlC5/v/KmPZoh2', 'ahmad.zaki@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1643265886, 1643350276, 1, 'Ahmad', 'Zaki', 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Pekanbaru', '12345678');

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
