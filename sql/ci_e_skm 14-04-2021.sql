/*
 Navicat Premium Data Transfer

 Source Server         : MYSQL8
 Source Server Type    : MySQL
 Source Server Version : 80028
 Source Host           : localhost:3308
 Source Schema         : ci_e_skm

 Target Server Type    : MySQL
 Target Server Version : 80028
 File Encoding         : 65001

 Date: 14/04/2022 16:56:23
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for berlangganan
-- ----------------------------
DROP TABLE IF EXISTS `berlangganan`;
CREATE TABLE `berlangganan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` bigint UNSIGNED NULL DEFAULT NULL,
  `id_paket` bigint UNSIGNED NULL DEFAULT NULL,
  `id_status_berlangganan` bigint UNSIGNED NULL DEFAULT NULL,
  `id_metode_pembayaran` bigint UNSIGNED NULL DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NULL DEFAULT NULL,
  `kode_lisensi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `berlangganan_id_user_foreign`(`id_user` ASC) USING BTREE,
  INDEX `berlangganan_id_paket_foreign`(`id_paket` ASC) USING BTREE,
  INDEX `berlangganan_id_status_berlangganan_foreign`(`id_status_berlangganan` ASC) USING BTREE,
  INDEX `berlangganan_id_metode_pembayaran_foreign`(`id_metode_pembayaran` ASC) USING BTREE,
  CONSTRAINT `berlangganan_id_metode_pembayaran_foreign` FOREIGN KEY (`id_metode_pembayaran`) REFERENCES `metode_pembayaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `berlangganan_id_paket_foreign` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `berlangganan_id_status_berlangganan_foreign` FOREIGN KEY (`id_status_berlangganan`) REFERENCES `status_berlangganan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `berlangganan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of berlangganan
-- ----------------------------
INSERT INTO `berlangganan` VALUES (2, 4, 1, 1, 1, '2022-04-08', '2023-04-08', '9JK1-LKVX-4WC1-44LU');
INSERT INTO `berlangganan` VALUES (3, 6, 1, 1, 1, '2022-04-08', '2023-04-08', '00WL-45YU-U86C-R0Q5');
INSERT INTO `berlangganan` VALUES (4, 6, 1, 1, 1, '2023-04-08', '2024-04-07', '0WNY-XH1U-21M1-9Q2U');

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions`  (
  `id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` int UNSIGNED NOT NULL,
  `data` blob NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ci_sessions
-- ----------------------------

-- ----------------------------
-- Table structure for data_prospek_survey
-- ----------------------------
DROP TABLE IF EXISTS `data_prospek_survey`;
CREATE TABLE `data_prospek_survey`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_manage_survey` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `telepon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_surveyor` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `data_prospek_survey_ibfk_1`(`id_manage_survey` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of data_prospek_survey
-- ----------------------------

-- ----------------------------
-- Table structure for data_prospek_survey_cst17
-- ----------------------------
DROP TABLE IF EXISTS `data_prospek_survey_cst17`;
CREATE TABLE `data_prospek_survey_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_manage_survey` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `telepon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_user` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `data_prospek_survey_ibfk_1`(`id_manage_survey` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of data_prospek_survey_cst17
-- ----------------------------
INSERT INTO `data_prospek_survey_cst17` VALUES (2, 0, 'hanif', 'axax', 'axax', 'axax', 'programmer@kokek.com', 10);
INSERT INTO `data_prospek_survey_cst17` VALUES (3, 0, 'hanifsscscscs', 'axax', 'axax', 'axax', 'abdurrahmanhanif85@gmail.com', 10);

-- ----------------------------
-- Table structure for data_prospek_survey_cst24
-- ----------------------------
DROP TABLE IF EXISTS `data_prospek_survey_cst24`;
CREATE TABLE `data_prospek_survey_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_manage_survey` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `telepon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_surveyor` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `data_prospek_survey_ibfk_1`(`id_manage_survey` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of data_prospek_survey_cst24
-- ----------------------------

-- ----------------------------
-- Table structure for files
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of files
-- ----------------------------
INSERT INTO `files` VALUES (1, 'filecst7.docx');

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES (1, 'admin', 'Administrator');
INSERT INTO `groups` VALUES (2, 'client', 'Akun Klien');
INSERT INTO `groups` VALUES (3, 'surveyor', 'Akun Surveyor');

-- ----------------------------
-- Table structure for isi_pertanyaan_ganda
-- ----------------------------
DROP TABLE IF EXISTS `isi_pertanyaan_ganda`;
CREATE TABLE `isi_pertanyaan_ganda`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_perincian_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `pertanyaan_ganda` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dengan_isian_lainnya` int NOT NULL,
  `nilai_pertanyaan_ganda` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `isi_pertanyaan_ganda_id_perincian_pertanyaan_terbuka_foreign`(`id_perincian_pertanyaan_terbuka` ASC) USING BTREE,
  CONSTRAINT `isi_pertanyaan_ganda_id_perincian_pertanyaan_terbuka_foreign` FOREIGN KEY (`id_perincian_pertanyaan_terbuka`) REFERENCES `perincian_pertanyaan_terbuka` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of isi_pertanyaan_ganda
-- ----------------------------
INSERT INTO `isi_pertanyaan_ganda` VALUES (3, 3, 'Tidak Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda` VALUES (4, 3, 'Kurang Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda` VALUES (5, 3, 'Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda` VALUES (6, 3, 'Sangat Baik', 2, 0);

-- ----------------------------
-- Table structure for isi_pertanyaan_ganda_cst17
-- ----------------------------
DROP TABLE IF EXISTS `isi_pertanyaan_ganda_cst17`;
CREATE TABLE `isi_pertanyaan_ganda_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_perincian_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `pertanyaan_ganda` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dengan_isian_lainnya` int NOT NULL,
  `nilai_pertanyaan_ganda` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `isi_pertanyaan_ganda_cst17_ibfk_2`(`id_perincian_pertanyaan_terbuka` ASC) USING BTREE,
  CONSTRAINT `isi_pertanyaan_ganda_cst17_ibfk_2` FOREIGN KEY (`id_perincian_pertanyaan_terbuka`) REFERENCES `perincian_pertanyaan_terbuka_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of isi_pertanyaan_ganda_cst17
-- ----------------------------
INSERT INTO `isi_pertanyaan_ganda_cst17` VALUES (3, 3, 'Tidak Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda_cst17` VALUES (4, 3, 'Kurang Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda_cst17` VALUES (5, 3, 'Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda_cst17` VALUES (6, 3, 'Sangat Baik', 2, 0);

-- ----------------------------
-- Table structure for isi_pertanyaan_ganda_cst24
-- ----------------------------
DROP TABLE IF EXISTS `isi_pertanyaan_ganda_cst24`;
CREATE TABLE `isi_pertanyaan_ganda_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_perincian_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `pertanyaan_ganda` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dengan_isian_lainnya` int NOT NULL,
  `nilai_pertanyaan_ganda` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `isi_pertanyaan_ganda_cst24_ibfk_2`(`id_perincian_pertanyaan_terbuka` ASC) USING BTREE,
  CONSTRAINT `isi_pertanyaan_ganda_cst24_ibfk_2` FOREIGN KEY (`id_perincian_pertanyaan_terbuka`) REFERENCES `perincian_pertanyaan_terbuka_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of isi_pertanyaan_ganda_cst24
-- ----------------------------
INSERT INTO `isi_pertanyaan_ganda_cst24` VALUES (3, 3, 'Tidak Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda_cst24` VALUES (4, 3, 'Kurang Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda_cst24` VALUES (5, 3, 'Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda_cst24` VALUES (6, 3, 'Sangat Baik', 2, 0);

-- ----------------------------
-- Table structure for isi_pertanyaan_ganda_cst7
-- ----------------------------
DROP TABLE IF EXISTS `isi_pertanyaan_ganda_cst7`;
CREATE TABLE `isi_pertanyaan_ganda_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_perincian_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `pertanyaan_ganda` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dengan_isian_lainnya` int NOT NULL,
  `nilai_pertanyaan_ganda` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `isi_pertanyaan_ganda_cst7_ibfk_2`(`id_perincian_pertanyaan_terbuka` ASC) USING BTREE,
  CONSTRAINT `isi_pertanyaan_ganda_cst7_ibfk_2` FOREIGN KEY (`id_perincian_pertanyaan_terbuka`) REFERENCES `perincian_pertanyaan_terbuka_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of isi_pertanyaan_ganda_cst7
-- ----------------------------
INSERT INTO `isi_pertanyaan_ganda_cst7` VALUES (3, 3, 'Tidak Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda_cst7` VALUES (4, 3, 'Kurang Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda_cst7` VALUES (5, 3, 'Baik', 2, 0);
INSERT INTO `isi_pertanyaan_ganda_cst7` VALUES (6, 3, 'Sangat Baik', 2, 0);

-- ----------------------------
-- Table structure for jawaban_pertanyaan_harapan
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_harapan`;
CREATE TABLE `jawaban_pertanyaan_harapan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `skor_jawaban` double(8, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_harapan_id_responden_foreign`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_harapan_id_responden_foreign` FOREIGN KEY (`id_responden`) REFERENCES `responden` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_harapan
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_harapan_cst17
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_harapan_cst17`;
CREATE TABLE `jawaban_pertanyaan_harapan_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `skor_jawaban` double NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_harapan_cst17_ibfk_3`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_harapan_cst17_ibfk_3` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_harapan_cst17
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_harapan_cst24
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_harapan_cst24`;
CREATE TABLE `jawaban_pertanyaan_harapan_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `skor_jawaban` double NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_harapan_cst24_ibfk_3`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_harapan_cst24_ibfk_3` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_harapan_cst24
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_harapan_cst7
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_harapan_cst7`;
CREATE TABLE `jawaban_pertanyaan_harapan_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `skor_jawaban` double NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_harapan_cst7_ibfk_3`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_harapan_cst7_ibfk_3` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_harapan_cst7
-- ----------------------------
INSERT INTO `jawaban_pertanyaan_harapan_cst7` VALUES (49, 13, 2, NULL);
INSERT INTO `jawaban_pertanyaan_harapan_cst7` VALUES (50, 13, 3, NULL);
INSERT INTO `jawaban_pertanyaan_harapan_cst7` VALUES (51, 13, 4, NULL);
INSERT INTO `jawaban_pertanyaan_harapan_cst7` VALUES (52, 13, 5, NULL);
INSERT INTO `jawaban_pertanyaan_harapan_cst7` VALUES (53, 14, 2, 3);
INSERT INTO `jawaban_pertanyaan_harapan_cst7` VALUES (54, 14, 3, 3);
INSERT INTO `jawaban_pertanyaan_harapan_cst7` VALUES (55, 14, 4, 3);
INSERT INTO `jawaban_pertanyaan_harapan_cst7` VALUES (56, 14, 5, 3);

-- ----------------------------
-- Table structure for jawaban_pertanyaan_kualitatif
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_kualitatif`;
CREATE TABLE `jawaban_pertanyaan_kualitatif`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_kualitatif` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_jawaban_kualitatif` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_kualitatif_id_responden_foreign`(`id_responden` ASC) USING BTREE,
  INDEX `jawaban_pertanyaan_kualitatif_id_pertanyaan_kualitatif_foreign`(`id_pertanyaan_kualitatif` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_kualitatif_id_pertanyaan_kualitatif_foreign` FOREIGN KEY (`id_pertanyaan_kualitatif`) REFERENCES `pertanyaan_kualitatif` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jawaban_pertanyaan_kualitatif_id_responden_foreign` FOREIGN KEY (`id_responden`) REFERENCES `responden` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_kualitatif
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_kualitatif_cst17
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_kualitatif_cst17`;
CREATE TABLE `jawaban_pertanyaan_kualitatif_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_kualitatif` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_jawaban_kualitatif` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_kualitatif_cst17_ibfk_2`(`id_responden` ASC) USING BTREE,
  INDEX `pertanyaan_kualitatif_cst17_ibfk_2`(`id_pertanyaan_kualitatif` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_kualitatif_cst17_ibfk_2` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pertanyaan_kualitatif_cst17_ibfk_2` FOREIGN KEY (`id_pertanyaan_kualitatif`) REFERENCES `pertanyaan_kualitatif_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_kualitatif_cst17
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_kualitatif_cst24
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_kualitatif_cst24`;
CREATE TABLE `jawaban_pertanyaan_kualitatif_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_kualitatif` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_jawaban_kualitatif` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_kualitatif_cst24_ibfk_2`(`id_responden` ASC) USING BTREE,
  INDEX `pertanyaan_kualitatif_cst24_ibfk_2`(`id_pertanyaan_kualitatif` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_kualitatif_cst24_ibfk_2` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pertanyaan_kualitatif_cst24_ibfk_2` FOREIGN KEY (`id_pertanyaan_kualitatif`) REFERENCES `pertanyaan_kualitatif_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_kualitatif_cst24
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_kualitatif_cst7
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_kualitatif_cst7`;
CREATE TABLE `jawaban_pertanyaan_kualitatif_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_kualitatif` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_jawaban_kualitatif` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_kualitatif_cst7_ibfk_2`(`id_responden` ASC) USING BTREE,
  INDEX `pertanyaan_kualitatif_cst7_ibfk_2`(`id_pertanyaan_kualitatif` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_kualitatif_cst7_ibfk_2` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pertanyaan_kualitatif_cst7_ibfk_2` FOREIGN KEY (`id_pertanyaan_kualitatif`) REFERENCES `pertanyaan_kualitatif_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_kualitatif_cst7
-- ----------------------------
INSERT INTO `jawaban_pertanyaan_kualitatif_cst7` VALUES (13, 13, 1, NULL, 1);
INSERT INTO `jawaban_pertanyaan_kualitatif_cst7` VALUES (14, 14, 1, 'tes', 1);

-- ----------------------------
-- Table structure for jawaban_pertanyaan_terbuka
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_terbuka`;
CREATE TABLE `jawaban_pertanyaan_terbuka`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `jawaban` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_terbuka_id_responden_foreign`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_terbuka_id_responden_foreign` FOREIGN KEY (`id_responden`) REFERENCES `responden` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_terbuka
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_terbuka_cst17
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_terbuka_cst17`;
CREATE TABLE `jawaban_pertanyaan_terbuka_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `jawaban` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_terbuka_cst17_ibfk_2`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_terbuka_cst17_ibfk_2` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_terbuka_cst17
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_terbuka_cst24
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_terbuka_cst24`;
CREATE TABLE `jawaban_pertanyaan_terbuka_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `jawaban` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_terbuka_cst24_ibfk_2`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_terbuka_cst24_ibfk_2` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_terbuka_cst24
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_terbuka_cst7
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_terbuka_cst7`;
CREATE TABLE `jawaban_pertanyaan_terbuka_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `jawaban` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_terbuka_cst7_ibfk_2`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_terbuka_cst7_ibfk_2` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_terbuka_cst7
-- ----------------------------
INSERT INTO `jawaban_pertanyaan_terbuka_cst7` VALUES (25, 13, 3, NULL, NULL);
INSERT INTO `jawaban_pertanyaan_terbuka_cst7` VALUES (26, 13, 5, NULL, NULL);
INSERT INTO `jawaban_pertanyaan_terbuka_cst7` VALUES (27, 14, 3, 'Baik', 1);
INSERT INTO `jawaban_pertanyaan_terbuka_cst7` VALUES (28, 14, 5, 's', 1);

-- ----------------------------
-- Table structure for jawaban_pertanyaan_unsur
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_unsur`;
CREATE TABLE `jawaban_pertanyaan_unsur`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `skor_jawaban` double(8, 2) NOT NULL,
  `alasan_pilih_jawaban` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_unsur_id_responden_foreign`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_unsur_id_responden_foreign` FOREIGN KEY (`id_responden`) REFERENCES `responden` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_unsur
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_unsur_cst17
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_unsur_cst17`;
CREATE TABLE `jawaban_pertanyaan_unsur_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `skor_jawaban` double NULL DEFAULT NULL,
  `alasan_pilih_jawaban` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_unsur_cst17_ibfk_2`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_unsur_cst17_ibfk_2` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_unsur_cst17
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_unsur_cst24
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_unsur_cst24`;
CREATE TABLE `jawaban_pertanyaan_unsur_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `skor_jawaban` double NULL DEFAULT NULL,
  `alasan_pilih_jawaban` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_unsur_cst24_ibfk_2`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_unsur_cst24_ibfk_2` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_unsur_cst24
-- ----------------------------

-- ----------------------------
-- Table structure for jawaban_pertanyaan_unsur_cst7
-- ----------------------------
DROP TABLE IF EXISTS `jawaban_pertanyaan_unsur_cst7`;
CREATE TABLE `jawaban_pertanyaan_unsur_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `skor_jawaban` double NULL DEFAULT NULL,
  `alasan_pilih_jawaban` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jawaban_pertanyaan_unsur_cst7_ibfk_2`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `jawaban_pertanyaan_unsur_cst7_ibfk_2` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jawaban_pertanyaan_unsur_cst7
-- ----------------------------
INSERT INTO `jawaban_pertanyaan_unsur_cst7` VALUES (49, 13, 2, NULL, NULL, NULL);
INSERT INTO `jawaban_pertanyaan_unsur_cst7` VALUES (50, 13, 3, NULL, NULL, NULL);
INSERT INTO `jawaban_pertanyaan_unsur_cst7` VALUES (51, 13, 4, NULL, NULL, NULL);
INSERT INTO `jawaban_pertanyaan_unsur_cst7` VALUES (52, 13, 5, NULL, NULL, NULL);
INSERT INTO `jawaban_pertanyaan_unsur_cst7` VALUES (53, 14, 2, 3, '', 1);
INSERT INTO `jawaban_pertanyaan_unsur_cst7` VALUES (54, 14, 3, 3, '', 1);
INSERT INTO `jawaban_pertanyaan_unsur_cst7` VALUES (55, 14, 4, 3, '', 1);
INSERT INTO `jawaban_pertanyaan_unsur_cst7` VALUES (56, 14, 5, 3, '', 1);

-- ----------------------------
-- Table structure for jenis_kelamin
-- ----------------------------
DROP TABLE IF EXISTS `jenis_kelamin`;
CREATE TABLE `jenis_kelamin`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `jenis_kelamin_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jenis_kelamin
-- ----------------------------
INSERT INTO `jenis_kelamin` VALUES (1, 'Laki-laki');
INSERT INTO `jenis_kelamin` VALUES (2, 'Perempuan');

-- ----------------------------
-- Table structure for jenis_pelayanan
-- ----------------------------
DROP TABLE IF EXISTS `jenis_pelayanan`;
CREATE TABLE `jenis_pelayanan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_klasifikasi_survei` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_jenis_pelayanan_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jenis_pelayanan_id_klasifikasi_survei_foreign`(`id_klasifikasi_survei` ASC) USING BTREE,
  CONSTRAINT `jenis_pelayanan_id_klasifikasi_survei_foreign` FOREIGN KEY (`id_klasifikasi_survei`) REFERENCES `klasifikasi_survei` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jenis_pelayanan
-- ----------------------------
INSERT INTO `jenis_pelayanan` VALUES (1, 1, 'Umum');
INSERT INTO `jenis_pelayanan` VALUES (2, 1, 'Pelayanan Hemodialisa (HD) / Cuci Darah');

-- ----------------------------
-- Table structure for jenis_pilihan_jawaban
-- ----------------------------
DROP TABLE IF EXISTS `jenis_pilihan_jawaban`;
CREATE TABLE `jenis_pilihan_jawaban`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_jenis_pilihan_jawaban` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jenis_pilihan_jawaban
-- ----------------------------
INSERT INTO `jenis_pilihan_jawaban` VALUES (1, 'Pilihan Ganda');
INSERT INTO `jenis_pilihan_jawaban` VALUES (2, 'Jawaban Singkat');

-- ----------------------------
-- Table structure for jumlah_kunjungan
-- ----------------------------
DROP TABLE IF EXISTS `jumlah_kunjungan`;
CREATE TABLE `jumlah_kunjungan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `banyak_kunjungan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jumlah_kunjungan
-- ----------------------------
INSERT INTO `jumlah_kunjungan` VALUES (1, '1-2 Kali');
INSERT INTO `jumlah_kunjungan` VALUES (2, '3-4 Kali');
INSERT INTO `jumlah_kunjungan` VALUES (3, 'Lebih dari 5 Kali');

-- ----------------------------
-- Table structure for kategori_pertanyaan_terbuka
-- ----------------------------
DROP TABLE IF EXISTS `kategori_pertanyaan_terbuka`;
CREATE TABLE `kategori_pertanyaan_terbuka`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_kategori_pertanyaan_terbuka` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `kategori_pertanyaan_terbuka_id_pertanyaan_terbuka_foreign`(`id_pertanyaan_terbuka` ASC) USING BTREE,
  CONSTRAINT `kategori_pertanyaan_terbuka_id_pertanyaan_terbuka_foreign` FOREIGN KEY (`id_pertanyaan_terbuka`) REFERENCES `pertanyaan_terbuka` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kategori_pertanyaan_terbuka
-- ----------------------------

-- ----------------------------
-- Table structure for kategori_unsur_pelayanan
-- ----------------------------
DROP TABLE IF EXISTS `kategori_unsur_pelayanan`;
CREATE TABLE `kategori_unsur_pelayanan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `nomor_kategori_unsur_pelayanan` int NOT NULL,
  `nama_kategori_unsur_pelayanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `kategori_unsur_pelayanan_id_unsur_pelayanan_foreign`(`id_unsur_pelayanan` ASC) USING BTREE,
  INDEX `kategori_unsur_pelayanan_id_pertanyaan_unsur_foreign`(`id_pertanyaan_unsur` ASC) USING BTREE,
  CONSTRAINT `kategori_unsur_pelayanan_id_pertanyaan_unsur_foreign` FOREIGN KEY (`id_pertanyaan_unsur`) REFERENCES `pertanyaan_unsur_pelayanan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `kategori_unsur_pelayanan_id_unsur_pelayanan_foreign` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kategori_unsur_pelayanan
-- ----------------------------
INSERT INTO `kategori_unsur_pelayanan` VALUES (5, 4, 2, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (6, 4, 2, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (7, 4, 2, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (8, 4, 2, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (9, 6, 3, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (10, 6, 3, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (11, 6, 3, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (12, 6, 3, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (13, 7, 4, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (14, 7, 4, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (15, 7, 4, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (16, 7, 4, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (17, 8, 5, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (18, 8, 5, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (19, 8, 5, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan` VALUES (20, 8, 5, 4, 'Sangat Baik');

-- ----------------------------
-- Table structure for kategori_unsur_pelayanan_cst17
-- ----------------------------
DROP TABLE IF EXISTS `kategori_unsur_pelayanan_cst17`;
CREATE TABLE `kategori_unsur_pelayanan_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `nomor_kategori_unsur_pelayanan` int NOT NULL,
  `nama_kategori_unsur_pelayanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `kategori_unsur_pelayanan_id_pertanyaan_unsur_foreign`(`id_pertanyaan_unsur` ASC) USING BTREE,
  INDEX `kategori_unsur_pelayanan_cst17_ibfk_1`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `kategori_unsur_pelayanan_cst17_ibfk_1` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kategori_unsur_pelayanan_cst17
-- ----------------------------
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (5, 4, 2, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (6, 4, 2, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (7, 4, 2, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (8, 4, 2, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (9, 6, 3, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (10, 6, 3, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (11, 6, 3, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (12, 6, 3, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (13, 7, 4, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (14, 7, 4, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (15, 7, 4, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (16, 7, 4, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (17, 8, 5, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (18, 8, 5, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (19, 8, 5, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst17` VALUES (20, 8, 5, 4, 'Sangat Baik');

-- ----------------------------
-- Table structure for kategori_unsur_pelayanan_cst24
-- ----------------------------
DROP TABLE IF EXISTS `kategori_unsur_pelayanan_cst24`;
CREATE TABLE `kategori_unsur_pelayanan_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `nomor_kategori_unsur_pelayanan` int NOT NULL,
  `nama_kategori_unsur_pelayanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `kategori_unsur_pelayanan_id_pertanyaan_unsur_foreign`(`id_pertanyaan_unsur` ASC) USING BTREE,
  INDEX `kategori_unsur_pelayanan_cst24_ibfk_1`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `kategori_unsur_pelayanan_cst24_ibfk_1` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kategori_unsur_pelayanan_cst24
-- ----------------------------
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (5, 4, 2, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (6, 4, 2, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (7, 4, 2, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (8, 4, 2, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (9, 6, 3, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (10, 6, 3, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (11, 6, 3, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (12, 6, 3, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (13, 7, 4, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (14, 7, 4, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (15, 7, 4, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (16, 7, 4, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (17, 8, 5, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (18, 8, 5, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (19, 8, 5, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst24` VALUES (20, 8, 5, 4, 'Sangat Baik');

-- ----------------------------
-- Table structure for kategori_unsur_pelayanan_cst7
-- ----------------------------
DROP TABLE IF EXISTS `kategori_unsur_pelayanan_cst7`;
CREATE TABLE `kategori_unsur_pelayanan_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `id_pertanyaan_unsur` bigint UNSIGNED NULL DEFAULT NULL,
  `nomor_kategori_unsur_pelayanan` int NOT NULL,
  `nama_kategori_unsur_pelayanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `kategori_unsur_pelayanan_id_pertanyaan_unsur_foreign`(`id_pertanyaan_unsur` ASC) USING BTREE,
  INDEX `kategori_unsur_pelayanan_cst7_ibfk_1`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `kategori_unsur_pelayanan_cst7_ibfk_1` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kategori_unsur_pelayanan_cst7
-- ----------------------------
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (5, 4, 2, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (6, 4, 2, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (7, 4, 2, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (8, 4, 2, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (9, 6, 3, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (10, 6, 3, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (11, 6, 3, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (12, 6, 3, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (13, 7, 4, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (14, 7, 4, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (15, 7, 4, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (16, 7, 4, 4, 'Sangat Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (17, 8, 5, 1, 'Tidak Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (18, 8, 5, 2, 'Kurang Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (19, 8, 5, 3, 'Baik');
INSERT INTO `kategori_unsur_pelayanan_cst7` VALUES (20, 8, 5, 4, 'Sangat Baik');

-- ----------------------------
-- Table structure for klasifikasi_survei
-- ----------------------------
DROP TABLE IF EXISTS `klasifikasi_survei`;
CREATE TABLE `klasifikasi_survei`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_klasifikasi_survei` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of klasifikasi_survei
-- ----------------------------
INSERT INTO `klasifikasi_survei` VALUES (1, 'RSUD', 'rsud');
INSERT INTO `klasifikasi_survei` VALUES (2, 'Capil', 'capil');
INSERT INTO `klasifikasi_survei` VALUES (3, 'BKPSDM', 'bkpsdm');
INSERT INTO `klasifikasi_survei` VALUES (4, 'Kemensos', 'kemensos');
INSERT INTO `klasifikasi_survei` VALUES (5, 'DPMPTSP', 'dpmptsp');
INSERT INTO `klasifikasi_survei` VALUES (6, 'Puskesmas', 'puskesmas');

-- ----------------------------
-- Table structure for login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of login_attempts
-- ----------------------------
INSERT INTO `login_attempts` VALUES (13, '192.168.1.59', 'lefi-andri', 1649914136);

-- ----------------------------
-- Table structure for manage_survey
-- ----------------------------
DROP TABLE IF EXISTS `manage_survey`;
CREATE TABLE `manage_survey`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` bigint UNSIGNED NULL DEFAULT NULL,
  `id_template` bigint UNSIGNED NULL DEFAULT NULL,
  `id_sampling` bigint UNSIGNED NULL DEFAULT NULL,
  `id_jenis_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `survey_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `survey_year` int NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `survey_start` date NOT NULL,
  `survey_end` date NOT NULL,
  `is_privacy` int NOT NULL,
  `table_identity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kuesioner_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_survey` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_populasi` int NOT NULL,
  `deskripsi_tunda` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_question` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_profil_organisasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_struktur_organisasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_sertifikat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_sertifikat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `manage_survey_id_template_foreign`(`id_template` ASC) USING BTREE,
  INDEX `manage_survey_id_user_foreign`(`id_user` ASC) USING BTREE,
  INDEX `manage_survey_id_sampling_foreign`(`id_sampling` ASC) USING BTREE,
  INDEX `manage_survey_id_jenis_pelayanan_foreign`(`id_jenis_pelayanan` ASC) USING BTREE,
  CONSTRAINT `manage_survey_id_jenis_pelayanan_foreign` FOREIGN KEY (`id_jenis_pelayanan`) REFERENCES `jenis_pelayanan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `manage_survey_id_sampling_foreign` FOREIGN KEY (`id_sampling`) REFERENCES `sampling` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `manage_survey_id_template_foreign` FOREIGN KEY (`id_template`) REFERENCES `template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `manage_survey_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of manage_survey
-- ----------------------------
INSERT INTO `manage_survey` VALUES (7, '0857bf65-5f4c-4d95-bb9e-370d63d05313', 4, 1, 1, 1, 'Pengaduan Masyarakat', 'pengaduan-masyarakat', 2022, 'Tes Survey', '2022-04-06', '2022-04-22', 1, 'cst7', '', 'logo_1649735868.png', 100000, 'Survey akan dilanjutkan kembali pada', 2, NULL, NULL, 'profil_organisasi_cst7.docx', '', '0007/SKM/4/IV/2022', 'desain-2.jpg');
INSERT INTO `manage_survey` VALUES (17, '7ac4935d-ae01-458f-bfe7-f155bbdab9d8', 4, 1, 1, 1, 'masyarakat', 'masyarakat', 2022, 'Tes Survey', '2022-04-05', '2022-04-16', 1, 'cst17', '', '', 1000000, 'Survey akan dilanjutkan kembali pada', 2, NULL, NULL, '', '', '', NULL);
INSERT INTO `manage_survey` VALUES (24, '65f095a5-e65d-45a9-9ede-bf810709bee2', 6, 1, 1, 1, 'SKM RSUD Sidoarjo 2022', 'skm-rsud-sidoarjo-2022', 2022, 'fghfh', '2022-04-01', '2022-04-30', 1, 'cst24', '', '', 0, 'Survey akan dilanjutkan kembali pada', 1, NULL, NULL, '', '', '', NULL);

-- ----------------------------
-- Table structure for metode_pembayaran
-- ----------------------------
DROP TABLE IF EXISTS `metode_pembayaran`;
CREATE TABLE `metode_pembayaran`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_metode_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of metode_pembayaran
-- ----------------------------
INSERT INTO `metode_pembayaran` VALUES (1, 'Transfer');
INSERT INTO `metode_pembayaran` VALUES (2, 'Reg');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 78 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (2, '2022_04_04_011403_create_manage_survey_table', 1);
INSERT INTO `migrations` VALUES (3, '2022_04_04_013438_create_users_table', 1);
INSERT INTO `migrations` VALUES (4, '2022_04_04_013543_create_groups_table', 1);
INSERT INTO `migrations` VALUES (5, '2022_04_04_013658_create_users_groups_table', 1);
INSERT INTO `migrations` VALUES (6, '2022_04_04_013942_add_user_id_to_users_groups', 1);
INSERT INTO `migrations` VALUES (7, '2022_04_04_014123_add_group_id_to_users_groups', 1);
INSERT INTO `migrations` VALUES (8, '2022_04_04_014409_create_login_attempts_table', 1);
INSERT INTO `migrations` VALUES (9, '2022_04_04_014453_create_ci_sessions_table', 1);
INSERT INTO `migrations` VALUES (10, '2022_04_04_014658_create_surveyor_table', 1);
INSERT INTO `migrations` VALUES (11, '2022_04_04_014908_add_id_user_to_surveyor', 1);
INSERT INTO `migrations` VALUES (12, '2022_04_04_015105_add_id_manage_survey_to_surveyor', 1);
INSERT INTO `migrations` VALUES (13, '2022_04_04_015417_create_klasifikasi_survei_table', 1);
INSERT INTO `migrations` VALUES (14, '2022_04_04_015439_create_jenis_pelayanan_table', 1);
INSERT INTO `migrations` VALUES (15, '2022_04_04_015825_add_id_klasifikasi_survei_to_jenis_pelayanan', 1);
INSERT INTO `migrations` VALUES (16, '2022_04_04_020323_create_unsur_pelayanan_table', 1);
INSERT INTO `migrations` VALUES (17, '2022_04_04_020905_add_id_jenis_pelayanan_to_unsur_pelayanan', 1);
INSERT INTO `migrations` VALUES (18, '2022_04_04_021251_create_kategori_unsur_pelayanan_table', 1);
INSERT INTO `migrations` VALUES (19, '2022_04_04_021524_add_id_unsur_pelayanan_to_kategori_unsur_pelayanan', 1);
INSERT INTO `migrations` VALUES (20, '2022_04_04_021953_create_pertanyaan_unsur_pelayanan_table', 1);
INSERT INTO `migrations` VALUES (21, '2022_04_04_022114_add_id_unsur_pelayanan_to_pertanyaan_unsur_pelayanan', 1);
INSERT INTO `migrations` VALUES (22, '2022_04_04_022419_add_id_pertanyaan_unsur_to_kategori_unsur_pelayanan', 1);
INSERT INTO `migrations` VALUES (23, '2022_04_04_022716_create_nilai_tingkat_kepentingan_table', 1);
INSERT INTO `migrations` VALUES (24, '2022_04_04_022827_add_id_pertanyaan_unsur_pelayanan_to_nilai_tingkat_kepentingan', 1);
INSERT INTO `migrations` VALUES (25, '2022_04_04_023213_create_pertanyaan_terbuka_table', 1);
INSERT INTO `migrations` VALUES (26, '2022_04_04_023312_add_id_unsur_pelayanan_to_pertanyaan_terbuka', 1);
INSERT INTO `migrations` VALUES (27, '2022_04_04_023638_create_kategori_pertanyaan_terbuka_table', 1);
INSERT INTO `migrations` VALUES (28, '2022_04_04_023734_add_id_pertanyaan_terbuka_to_kategori_pertanyaan_terbuka', 1);
INSERT INTO `migrations` VALUES (29, '2022_04_04_024126_create_perincian_pertanyaan_terbuka_table', 1);
INSERT INTO `migrations` VALUES (30, '2022_04_04_024232_add_id_pertanyaan_terbuka_to_perincian_pertanyaan_terbuka', 1);
INSERT INTO `migrations` VALUES (31, '2022_04_04_025646_create_isi_pertanyaan_ganda_table', 1);
INSERT INTO `migrations` VALUES (32, '2022_04_04_025745_add_id_perincian_pertanyaan_terbuka_to_isi_pertanyaan_ganda', 1);
INSERT INTO `migrations` VALUES (33, '2022_04_04_031421_create_survey_table', 1);
INSERT INTO `migrations` VALUES (34, '2022_04_04_031443_create_responden_table', 1);
INSERT INTO `migrations` VALUES (35, '2022_04_04_032839_add_id_responden_to_survey', 1);
INSERT INTO `migrations` VALUES (36, '2022_04_04_033839_create_jawaban_pertanyaan_unsur_table', 1);
INSERT INTO `migrations` VALUES (37, '2022_04_04_033941_add_id_responden_to_jawaban_pertanyaan_unsur', 1);
INSERT INTO `migrations` VALUES (38, '2022_04_04_034430_create_jawaban_pertanyaan_kualitatif_table', 1);
INSERT INTO `migrations` VALUES (39, '2022_04_04_034536_add_id_responden_to_jawaban_pertanyaan_kualitatif', 1);
INSERT INTO `migrations` VALUES (40, '2022_04_04_035024_create_pertanyaan_kualitatif_table', 1);
INSERT INTO `migrations` VALUES (41, '2022_04_04_035121_add_id_pertanyaan_kualitatif_to_jawaban_pertanyaan_kualitatif', 1);
INSERT INTO `migrations` VALUES (42, '2022_04_04_035437_create_jawaban_pertanyaan_terbuka_table', 1);
INSERT INTO `migrations` VALUES (43, '2022_04_04_035532_add_id_responden_to_jawaban_pertanyaan_terbuka', 1);
INSERT INTO `migrations` VALUES (44, '2022_04_04_035558_create_jawaban_pertanyaan_harapan_table', 1);
INSERT INTO `migrations` VALUES (45, '2022_04_04_035642_add_id_responden_to_jawaban_pertanyaan_harapan', 1);
INSERT INTO `migrations` VALUES (46, '2022_04_04_040447_create_template_table', 1);
INSERT INTO `migrations` VALUES (47, '2022_04_04_040538_add_id_template_to_manage_survey', 1);
INSERT INTO `migrations` VALUES (48, '2022_04_04_041257_add_id_user_to_manage_survey', 1);
INSERT INTO `migrations` VALUES (49, '2022_04_04_041757_create_jenis_pilihan_jawaban_table', 1);
INSERT INTO `migrations` VALUES (50, '2022_04_04_042020_add_id_jenis_pilihan_jawaban_to_perincian_pertanyaan_terbuka', 1);
INSERT INTO `migrations` VALUES (51, '2022_04_04_042752_create_nilai_skm_table', 1);
INSERT INTO `migrations` VALUES (52, '2022_04_04_042810_create_jenis_kelamin_table', 1);
INSERT INTO `migrations` VALUES (53, '2022_04_04_042829_create_pendidikan_terakhir_table', 1);
INSERT INTO `migrations` VALUES (54, '2022_04_04_042847_create_organisasi_table', 1);
INSERT INTO `migrations` VALUES (55, '2022_04_04_042904_create_pekerjaan_utama_table', 1);
INSERT INTO `migrations` VALUES (56, '2022_04_04_042929_create_pembiayaan_table', 1);
INSERT INTO `migrations` VALUES (57, '2022_04_04_042943_create_umur_table', 1);
INSERT INTO `migrations` VALUES (58, '2022_04_04_043000_create_status_responden_table', 1);
INSERT INTO `migrations` VALUES (59, '2022_04_04_043014_create_sampling_table', 1);
INSERT INTO `migrations` VALUES (60, '2022_04_04_043032_create_quality_control_table', 1);
INSERT INTO `migrations` VALUES (61, '2022_04_04_044422_add_id_sampling_to_manage_survey', 1);
INSERT INTO `migrations` VALUES (62, '2022_04_04_044930_create_berlangganan_table', 1);
INSERT INTO `migrations` VALUES (63, '2022_04_04_044949_create_paket_table', 1);
INSERT INTO `migrations` VALUES (64, '2022_04_04_045007_create_metode_pembayaran_table', 1);
INSERT INTO `migrations` VALUES (65, '2022_04_04_045027_create_status_berlangganan_table', 1);
INSERT INTO `migrations` VALUES (66, '2022_04_04_045447_add_id_user_to_berlangganan', 1);
INSERT INTO `migrations` VALUES (67, '2022_04_04_045505_add_id_paket_to_berlangganan', 1);
INSERT INTO `migrations` VALUES (68, '2022_04_04_045547_add_id_status_berlangganan_to_berlangganan', 1);
INSERT INTO `migrations` VALUES (69, '2022_04_04_045604_add_id_metode_pembayaran_to_berlangganan', 1);
INSERT INTO `migrations` VALUES (70, '2022_04_04_064436_add_id_klasifikasi_survei_to_users', 1);
INSERT INTO `migrations` VALUES (71, '2022_04_05_073213_create_web_settings_table', 1);
INSERT INTO `migrations` VALUES (72, '2022_04_06_012150_create_jumlah_kunjungan_table', 1);
INSERT INTO `migrations` VALUES (73, '2022_04_06_012415_create_pilihan_jawaban_pertanyaan_harapan_table', 1);
INSERT INTO `migrations` VALUES (74, '2022_04_06_012701_create_mst_profil_responden_kuesioner_table', 1);
INSERT INTO `migrations` VALUES (75, '2022_04_06_012832_create_profil_responden_kuesioner_table', 1);
INSERT INTO `migrations` VALUES (76, '2022_04_06_013116_add_id_klasifikasi_survey_to_profil_responden_kuesioner', 1);
INSERT INTO `migrations` VALUES (77, '2022_04_06_013233_add_id_mst_profil_responden_to_profil_responden_kuesioner', 1);
INSERT INTO `migrations` VALUES (78, '2022_04_07_043740_add_id_jenis_pelayanan_to_manage_survey', 1);

-- ----------------------------
-- Table structure for mst_profil_responden_kuesioner
-- ----------------------------
DROP TABLE IF EXISTS `mst_profil_responden_kuesioner`;
CREATE TABLE `mst_profil_responden_kuesioner`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_mst_profil_responden` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mst_profil_responden_kuesioner
-- ----------------------------
INSERT INTO `mst_profil_responden_kuesioner` VALUES (1, 'Jenis Kelamin', 1);
INSERT INTO `mst_profil_responden_kuesioner` VALUES (2, 'Umur', 1);
INSERT INTO `mst_profil_responden_kuesioner` VALUES (3, 'Pendidikan Terakhir', 1);
INSERT INTO `mst_profil_responden_kuesioner` VALUES (4, 'Pekerjaan Utama', 1);
INSERT INTO `mst_profil_responden_kuesioner` VALUES (5, 'Pembiayaan', 1);
INSERT INTO `mst_profil_responden_kuesioner` VALUES (6, ' Status Responden', 1);
INSERT INTO `mst_profil_responden_kuesioner` VALUES (7, 'Jumlah Kunjungan', 1);
INSERT INTO `mst_profil_responden_kuesioner` VALUES (8, 'Lama Bekerja', 1);

-- ----------------------------
-- Table structure for nilai_skm
-- ----------------------------
DROP TABLE IF EXISTS `nilai_skm`;
CREATE TABLE `nilai_skm`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nilai_persepsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_interval_min` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_interval_max` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_interval_konversi_min` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_interval_konversi_max` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mutu_pelayanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kinerja_unit_pelayanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nilai_skm
-- ----------------------------
INSERT INTO `nilai_skm` VALUES (1, '1', '1,00', '2,5996', '25,00', '64,99', 'D', 'Tidak baik');
INSERT INTO `nilai_skm` VALUES (2, '2', '2,60', '3,064', '65,00', '76,60', 'C', 'Kurang baik');
INSERT INTO `nilai_skm` VALUES (3, '3', '3,0644', '3,532', '76,61', '88,30', 'B', 'Baik');
INSERT INTO `nilai_skm` VALUES (4, '4', '3,5324', '4,00', '88,31', '100,00', 'A', 'Sangat baik');

-- ----------------------------
-- Table structure for nilai_tingkat_kepentingan
-- ----------------------------
DROP TABLE IF EXISTS `nilai_tingkat_kepentingan`;
CREATE TABLE `nilai_tingkat_kepentingan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pertanyaan_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nomor_tingkat_kepentingan` int NOT NULL,
  `nama_tingkat_kepentingan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `nilai_tingkat_kepentingan_id_pertanyaan_unsur_pelayanan_foreign`(`id_pertanyaan_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `nilai_tingkat_kepentingan_id_pertanyaan_unsur_pelayanan_foreign` FOREIGN KEY (`id_pertanyaan_unsur_pelayanan`) REFERENCES `pertanyaan_unsur_pelayanan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nilai_tingkat_kepentingan
-- ----------------------------
INSERT INTO `nilai_tingkat_kepentingan` VALUES (1, 2, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (2, 2, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (3, 2, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (4, 2, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (5, 3, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (6, 3, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (7, 3, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (8, 3, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (9, 4, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (10, 4, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (11, 4, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (12, 4, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (13, 5, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (14, 5, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (15, 5, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan` VALUES (16, 5, 4, 'Sangat Penting');

-- ----------------------------
-- Table structure for nilai_tingkat_kepentingan_cst17
-- ----------------------------
DROP TABLE IF EXISTS `nilai_tingkat_kepentingan_cst17`;
CREATE TABLE `nilai_tingkat_kepentingan_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pertanyaan_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nomor_tingkat_kepentingan` int NOT NULL,
  `nama_tingkat_kepentingan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `nilai_tingkat_kepentingan_cst17_ibfk_3`(`id_pertanyaan_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `nilai_tingkat_kepentingan_cst17_ibfk_3` FOREIGN KEY (`id_pertanyaan_unsur_pelayanan`) REFERENCES `pertanyaan_unsur_pelayanan_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nilai_tingkat_kepentingan_cst17
-- ----------------------------
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (1, 2, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (2, 2, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (3, 2, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (4, 2, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (5, 3, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (6, 3, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (7, 3, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (8, 3, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (9, 4, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (10, 4, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (11, 4, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (12, 4, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (13, 5, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (14, 5, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (15, 5, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst17` VALUES (16, 5, 4, 'Sangat Penting');

-- ----------------------------
-- Table structure for nilai_tingkat_kepentingan_cst24
-- ----------------------------
DROP TABLE IF EXISTS `nilai_tingkat_kepentingan_cst24`;
CREATE TABLE `nilai_tingkat_kepentingan_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pertanyaan_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nomor_tingkat_kepentingan` int NOT NULL,
  `nama_tingkat_kepentingan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `nilai_tingkat_kepentingan_cst24_ibfk_3`(`id_pertanyaan_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `nilai_tingkat_kepentingan_cst24_ibfk_3` FOREIGN KEY (`id_pertanyaan_unsur_pelayanan`) REFERENCES `pertanyaan_unsur_pelayanan_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nilai_tingkat_kepentingan_cst24
-- ----------------------------
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (1, 2, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (2, 2, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (3, 2, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (4, 2, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (5, 3, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (6, 3, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (7, 3, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (8, 3, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (9, 4, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (10, 4, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (11, 4, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (12, 4, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (13, 5, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (14, 5, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (15, 5, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst24` VALUES (16, 5, 4, 'Sangat Penting');

-- ----------------------------
-- Table structure for nilai_tingkat_kepentingan_cst7
-- ----------------------------
DROP TABLE IF EXISTS `nilai_tingkat_kepentingan_cst7`;
CREATE TABLE `nilai_tingkat_kepentingan_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pertanyaan_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nomor_tingkat_kepentingan` int NOT NULL,
  `nama_tingkat_kepentingan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `nilai_tingkat_kepentingan_cst7_ibfk_3`(`id_pertanyaan_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `nilai_tingkat_kepentingan_cst7_ibfk_3` FOREIGN KEY (`id_pertanyaan_unsur_pelayanan`) REFERENCES `pertanyaan_unsur_pelayanan_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nilai_tingkat_kepentingan_cst7
-- ----------------------------
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (1, 2, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (2, 2, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (3, 2, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (4, 2, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (5, 3, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (6, 3, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (7, 3, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (8, 3, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (9, 4, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (10, 4, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (11, 4, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (12, 4, 4, 'Sangat Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (13, 5, 1, 'Tidak Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (14, 5, 2, 'Kurang Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (15, 5, 3, 'Penting');
INSERT INTO `nilai_tingkat_kepentingan_cst7` VALUES (16, 5, 4, 'Sangat Penting');

-- ----------------------------
-- Table structure for organisasi
-- ----------------------------
DROP TABLE IF EXISTS `organisasi`;
CREATE TABLE `organisasi`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_parent` bigint UNSIGNED NOT NULL,
  `nama_organisasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of organisasi
-- ----------------------------

-- ----------------------------
-- Table structure for paket
-- ----------------------------
DROP TABLE IF EXISTS `paket`;
CREATE TABLE `paket`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `panjang_hari` int NOT NULL,
  `harga_paket` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of paket
-- ----------------------------
INSERT INTO `paket` VALUES (1, 'Paket Langganan 1 Tahun', 'Berlangganan paket 365 hari', 365, 40000000);
INSERT INTO `paket` VALUES (2, 'Paket Langganan 2 Tahun', 'Berlangganan paket 730 hari', 730, 80000000);

-- ----------------------------
-- Table structure for pekerjaan_utama
-- ----------------------------
DROP TABLE IF EXISTS `pekerjaan_utama`;
CREATE TABLE `pekerjaan_utama`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pekerjaan_utama_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pekerjaan_utama
-- ----------------------------
INSERT INTO `pekerjaan_utama` VALUES (1, 'PNS/TNI/Polri');
INSERT INTO `pekerjaan_utama` VALUES (2, 'Pegawai Swasta');
INSERT INTO `pekerjaan_utama` VALUES (3, 'Pelajar/Mahasiswa');
INSERT INTO `pekerjaan_utama` VALUES (4, 'Wiraswasta/Wirausaha');
INSERT INTO `pekerjaan_utama` VALUES (5, 'Ibu Rumah Tangga');
INSERT INTO `pekerjaan_utama` VALUES (6, 'Lainnya');

-- ----------------------------
-- Table structure for pembiayaan
-- ----------------------------
DROP TABLE IF EXISTS `pembiayaan`;
CREATE TABLE `pembiayaan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pembiayaan_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pembiayaan
-- ----------------------------
INSERT INTO `pembiayaan` VALUES (1, 'Umum');
INSERT INTO `pembiayaan` VALUES (2, 'BPJS Mandiri (Bayar Premi Sendiri/ Kantor)');
INSERT INTO `pembiayaan` VALUES (3, 'Subsidi Pemerintah (BPJS/PBI, SKM, KIS)');
INSERT INTO `pembiayaan` VALUES (4, 'Asuransi Lain');

-- ----------------------------
-- Table structure for pendidikan_terakhir
-- ----------------------------
DROP TABLE IF EXISTS `pendidikan_terakhir`;
CREATE TABLE `pendidikan_terakhir`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pendidikan_terakhir_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pendidikan_terakhir
-- ----------------------------
INSERT INTO `pendidikan_terakhir` VALUES (1, 'SD ke bawah');
INSERT INTO `pendidikan_terakhir` VALUES (2, 'SMP');
INSERT INTO `pendidikan_terakhir` VALUES (3, 'SMA');
INSERT INTO `pendidikan_terakhir` VALUES (4, 'D1-D2-D3-D4');
INSERT INTO `pendidikan_terakhir` VALUES (5, 'S-1');
INSERT INTO `pendidikan_terakhir` VALUES (6, 'S-2 ke atas');

-- ----------------------------
-- Table structure for perincian_pertanyaan_terbuka
-- ----------------------------
DROP TABLE IF EXISTS `perincian_pertanyaan_terbuka`;
CREATE TABLE `perincian_pertanyaan_terbuka`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `id_jenis_pilihan_jawaban` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan_terbuka` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `perincian_pertanyaan_terbuka_id_pertanyaan_terbuka_foreign`(`id_pertanyaan_terbuka` ASC) USING BTREE,
  INDEX `perincian_pertanyaan_terbuka_id_jenis_pilihan_jawaban_foreign`(`id_jenis_pilihan_jawaban` ASC) USING BTREE,
  CONSTRAINT `perincian_pertanyaan_terbuka_id_jenis_pilihan_jawaban_foreign` FOREIGN KEY (`id_jenis_pilihan_jawaban`) REFERENCES `jenis_pilihan_jawaban` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `perincian_pertanyaan_terbuka_id_pertanyaan_terbuka_foreign` FOREIGN KEY (`id_pertanyaan_terbuka`) REFERENCES `pertanyaan_terbuka` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of perincian_pertanyaan_terbuka
-- ----------------------------
INSERT INTO `perincian_pertanyaan_terbuka` VALUES (3, 3, 1, 'Bagaimana menurut Saudara tentang Kejelasan Informasi Pelayanan di Unit ini?');

-- ----------------------------
-- Table structure for perincian_pertanyaan_terbuka_cst17
-- ----------------------------
DROP TABLE IF EXISTS `perincian_pertanyaan_terbuka_cst17`;
CREATE TABLE `perincian_pertanyaan_terbuka_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `id_jenis_pilihan_jawaban` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan_terbuka` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `perincian_pertanyaan_terbuka_id_jenis_pilihan_jawaban_foreign`(`id_jenis_pilihan_jawaban` ASC) USING BTREE,
  INDEX `perincian_pertanyaan_terbuka_cst17_ibfk_2`(`id_pertanyaan_terbuka` ASC) USING BTREE,
  CONSTRAINT `perincian_pertanyaan_terbuka_cst17_ibfk_2` FOREIGN KEY (`id_pertanyaan_terbuka`) REFERENCES `pertanyaan_terbuka_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of perincian_pertanyaan_terbuka_cst17
-- ----------------------------
INSERT INTO `perincian_pertanyaan_terbuka_cst17` VALUES (3, 3, 1, 'Bagaimana menurut Saudara tentang Kejelasan Informasi Pelayanan di Unit ini?');

-- ----------------------------
-- Table structure for perincian_pertanyaan_terbuka_cst24
-- ----------------------------
DROP TABLE IF EXISTS `perincian_pertanyaan_terbuka_cst24`;
CREATE TABLE `perincian_pertanyaan_terbuka_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `id_jenis_pilihan_jawaban` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan_terbuka` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `perincian_pertanyaan_terbuka_id_jenis_pilihan_jawaban_foreign`(`id_jenis_pilihan_jawaban` ASC) USING BTREE,
  INDEX `perincian_pertanyaan_terbuka_cst24_ibfk_2`(`id_pertanyaan_terbuka` ASC) USING BTREE,
  CONSTRAINT `perincian_pertanyaan_terbuka_cst24_ibfk_2` FOREIGN KEY (`id_pertanyaan_terbuka`) REFERENCES `pertanyaan_terbuka_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of perincian_pertanyaan_terbuka_cst24
-- ----------------------------
INSERT INTO `perincian_pertanyaan_terbuka_cst24` VALUES (3, 3, 1, 'Bagaimana menurut Saudara tentang Kejelasan Informasi Pelayanan di Unit ini?');

-- ----------------------------
-- Table structure for perincian_pertanyaan_terbuka_cst7
-- ----------------------------
DROP TABLE IF EXISTS `perincian_pertanyaan_terbuka_cst7`;
CREATE TABLE `perincian_pertanyaan_terbuka_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pertanyaan_terbuka` bigint UNSIGNED NULL DEFAULT NULL,
  `id_jenis_pilihan_jawaban` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan_terbuka` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `perincian_pertanyaan_terbuka_id_jenis_pilihan_jawaban_foreign`(`id_jenis_pilihan_jawaban` ASC) USING BTREE,
  INDEX `perincian_pertanyaan_terbuka_cst7_ibfk_2`(`id_pertanyaan_terbuka` ASC) USING BTREE,
  CONSTRAINT `perincian_pertanyaan_terbuka_cst7_ibfk_2` FOREIGN KEY (`id_pertanyaan_terbuka`) REFERENCES `pertanyaan_terbuka_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of perincian_pertanyaan_terbuka_cst7
-- ----------------------------
INSERT INTO `perincian_pertanyaan_terbuka_cst7` VALUES (3, 3, 1, 'Bagaimana menurut Saudara tentang Kejelasan Informasi Pelayanan di Unit ini?');
INSERT INTO `perincian_pertanyaan_terbuka_cst7` VALUES (5, 5, 2, 'scsc');

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for pertanyaan_kualitatif
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_kualitatif`;
CREATE TABLE `pertanyaan_kualitatif`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `isi_pertanyaan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_kualitatif
-- ----------------------------

-- ----------------------------
-- Table structure for pertanyaan_kualitatif_cst17
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_kualitatif_cst17`;
CREATE TABLE `pertanyaan_kualitatif_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_survey` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_kualitatif_cst17
-- ----------------------------
INSERT INTO `pertanyaan_kualitatif_cst17` VALUES (1, NULL, 'Tes', 1);

-- ----------------------------
-- Table structure for pertanyaan_kualitatif_cst24
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_kualitatif_cst24`;
CREATE TABLE `pertanyaan_kualitatif_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_survey` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_kualitatif_cst24
-- ----------------------------

-- ----------------------------
-- Table structure for pertanyaan_kualitatif_cst7
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_kualitatif_cst7`;
CREATE TABLE `pertanyaan_kualitatif_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_survey` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_kualitatif_cst7
-- ----------------------------
INSERT INTO `pertanyaan_kualitatif_cst7` VALUES (1, NULL, 'scsc', 1);

-- ----------------------------
-- Table structure for pertanyaan_terbuka
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_terbuka`;
CREATE TABLE `pertanyaan_terbuka`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_pertanyaan_terbuka` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_pertanyaan_terbuka` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pertanyaan_terbuka_id_unsur_pelayanan_foreign`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `pertanyaan_terbuka_id_unsur_pelayanan_foreign` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_terbuka
-- ----------------------------
INSERT INTO `pertanyaan_terbuka` VALUES (3, 4, 'Kejelasan Informasi', 'T1');

-- ----------------------------
-- Table structure for pertanyaan_terbuka_cst17
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_terbuka_cst17`;
CREATE TABLE `pertanyaan_terbuka_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_pertanyaan_terbuka` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_pertanyaan_terbuka` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pertanyaan_terbuka_cst17_ibfk_3`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `pertanyaan_terbuka_cst17_ibfk_3` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_terbuka_cst17
-- ----------------------------
INSERT INTO `pertanyaan_terbuka_cst17` VALUES (3, 4, 'T1', 'Kejelasan Informasi');

-- ----------------------------
-- Table structure for pertanyaan_terbuka_cst24
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_terbuka_cst24`;
CREATE TABLE `pertanyaan_terbuka_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_pertanyaan_terbuka` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_pertanyaan_terbuka` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pertanyaan_terbuka_cst24_ibfk_3`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `pertanyaan_terbuka_cst24_ibfk_3` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_terbuka_cst24
-- ----------------------------
INSERT INTO `pertanyaan_terbuka_cst24` VALUES (3, 4, 'T1', 'Kejelasan Informasi');

-- ----------------------------
-- Table structure for pertanyaan_terbuka_cst7
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_terbuka_cst7`;
CREATE TABLE `pertanyaan_terbuka_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_pertanyaan_terbuka` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_pertanyaan_terbuka` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pertanyaan_terbuka_cst7_ibfk_3`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `pertanyaan_terbuka_cst7_ibfk_3` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_terbuka_cst7
-- ----------------------------
INSERT INTO `pertanyaan_terbuka_cst7` VALUES (3, 4, 'Kejelasan Informasi', 'T1');
INSERT INTO `pertanyaan_terbuka_cst7` VALUES (5, 4, '123', 'T2');

-- ----------------------------
-- Table structure for pertanyaan_unsur_pelayanan
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_unsur_pelayanan`;
CREATE TABLE `pertanyaan_unsur_pelayanan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan_unsur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pertanyaan_unsur_pelayanan_id_unsur_pelayanan_foreign`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `pertanyaan_unsur_pelayanan_id_unsur_pelayanan_foreign` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_unsur_pelayanan
-- ----------------------------
INSERT INTO `pertanyaan_unsur_pelayanan` VALUES (2, 4, 'Bagaimana menurut Saudara tentang Kejelasan Informasi Pelayanan di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan` VALUES (3, 6, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Admin di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan` VALUES (4, 7, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Perawat di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan` VALUES (5, 8, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Dokter di Unit ini?');

-- ----------------------------
-- Table structure for pertanyaan_unsur_pelayanan_cst17
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_unsur_pelayanan_cst17`;
CREATE TABLE `pertanyaan_unsur_pelayanan_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan_unsur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pertanyaan_unsur_pelayanan_cst17_ibfk_2`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `pertanyaan_unsur_pelayanan_cst17_ibfk_2` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_unsur_pelayanan_cst17
-- ----------------------------
INSERT INTO `pertanyaan_unsur_pelayanan_cst17` VALUES (2, 4, 'Bagaimana menurut Saudara tentang Kejelasan Informasi Pelayanan di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan_cst17` VALUES (3, 6, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Admin di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan_cst17` VALUES (4, 7, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Perawat di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan_cst17` VALUES (5, 8, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Dokter di Unit ini?');

-- ----------------------------
-- Table structure for pertanyaan_unsur_pelayanan_cst24
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_unsur_pelayanan_cst24`;
CREATE TABLE `pertanyaan_unsur_pelayanan_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan_unsur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pertanyaan_unsur_pelayanan_cst24_ibfk_2`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `pertanyaan_unsur_pelayanan_cst24_ibfk_2` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_unsur_pelayanan_cst24
-- ----------------------------
INSERT INTO `pertanyaan_unsur_pelayanan_cst24` VALUES (2, 4, 'Bagaimana menurut Saudara tentang Kejelasan Informasi Pelayanan di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan_cst24` VALUES (3, 6, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Admin di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan_cst24` VALUES (4, 7, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Perawat di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan_cst24` VALUES (5, 8, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Dokter di Unit ini?');

-- ----------------------------
-- Table structure for pertanyaan_unsur_pelayanan_cst7
-- ----------------------------
DROP TABLE IF EXISTS `pertanyaan_unsur_pelayanan_cst7`;
CREATE TABLE `pertanyaan_unsur_pelayanan_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unsur_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `isi_pertanyaan_unsur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pertanyaan_unsur_pelayanan_cst7_ibfk_2`(`id_unsur_pelayanan` ASC) USING BTREE,
  CONSTRAINT `pertanyaan_unsur_pelayanan_cst7_ibfk_2` FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES `unsur_pelayanan_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pertanyaan_unsur_pelayanan_cst7
-- ----------------------------
INSERT INTO `pertanyaan_unsur_pelayanan_cst7` VALUES (2, 4, 'Bagaimana menurut Saudara tentang Kejelasan Informasi Pelayanan di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan_cst7` VALUES (3, 6, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Admin di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan_cst7` VALUES (4, 7, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Perawat di Unit ini?');
INSERT INTO `pertanyaan_unsur_pelayanan_cst7` VALUES (5, 8, 'Bagaimana menurut Saudara tentang Kemudahan Prosedur Pelayanan Dokter di Unit ini?');

-- ----------------------------
-- Table structure for pilihan_jawaban_pertanyaan_harapan
-- ----------------------------
DROP TABLE IF EXISTS `pilihan_jawaban_pertanyaan_harapan`;
CREATE TABLE `pilihan_jawaban_pertanyaan_harapan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `pilihan_1` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_2` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_3` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_4` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pilihan_jawaban_pertanyaan_harapan
-- ----------------------------
INSERT INTO `pilihan_jawaban_pertanyaan_harapan` VALUES (1, 'Tidak Sesuai', 'Kurang Sesuai', 'Sesuai', 'Sangat Sesuai');
INSERT INTO `pilihan_jawaban_pertanyaan_harapan` VALUES (2, 'Tidak Baik', 'Kurang Baik', 'Baik', 'Sangat Baik');
INSERT INTO `pilihan_jawaban_pertanyaan_harapan` VALUES (3, 'Tidak Penting', 'Kurang Penting', 'Penting', 'Sangat Penting');

-- ----------------------------
-- Table structure for profil_responden_kuesioner
-- ----------------------------
DROP TABLE IF EXISTS `profil_responden_kuesioner`;
CREATE TABLE `profil_responden_kuesioner`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_klasifikasi_survey` bigint UNSIGNED NULL DEFAULT NULL,
  `id_mst_profil_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `profil_responden_kuesioner_id_klasifikasi_survey_foreign`(`id_klasifikasi_survey` ASC) USING BTREE,
  INDEX `profil_responden_kuesioner_id_mst_profil_responden_foreign`(`id_mst_profil_responden` ASC) USING BTREE,
  CONSTRAINT `profil_responden_kuesioner_id_klasifikasi_survey_foreign` FOREIGN KEY (`id_klasifikasi_survey`) REFERENCES `klasifikasi_survei` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `profil_responden_kuesioner_id_mst_profil_responden_foreign` FOREIGN KEY (`id_mst_profil_responden`) REFERENCES `mst_profil_responden_kuesioner` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of profil_responden_kuesioner
-- ----------------------------
INSERT INTO `profil_responden_kuesioner` VALUES (1, 1, 1, NULL, NULL, 1);
INSERT INTO `profil_responden_kuesioner` VALUES (2, 1, 2, NULL, NULL, 1);
INSERT INTO `profil_responden_kuesioner` VALUES (3, 1, 3, NULL, NULL, 1);
INSERT INTO `profil_responden_kuesioner` VALUES (4, 1, 8, NULL, NULL, 1);

-- ----------------------------
-- Table structure for quality_control
-- ----------------------------
DROP TABLE IF EXISTS `quality_control`;
CREATE TABLE `quality_control`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_quality_control` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of quality_control
-- ----------------------------

-- ----------------------------
-- Table structure for responden
-- ----------------------------
DROP TABLE IF EXISTS `responden`;
CREATE TABLE `responden`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_organization` bigint UNSIGNED NULL DEFAULT NULL,
  `id_jenis_kelamin` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `handphone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of responden
-- ----------------------------

-- ----------------------------
-- Table structure for responden_cst14
-- ----------------------------
DROP TABLE IF EXISTS `responden_cst14`;
CREATE TABLE `responden_cst14`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `handphone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `alamat_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_jenis_kelamin` int UNSIGNED NULL DEFAULT NULL,
  `id_umur` int UNSIGNED NULL DEFAULT NULL,
  `id_pendidikan_akhir` int UNSIGNED NULL DEFAULT NULL,
  `id_pekerjaan_utama` int UNSIGNED NULL DEFAULT NULL,
  `pekerjaan_lainnya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_pembiayaan` int UNSIGNED NULL DEFAULT NULL,
  `id_status_responden` int UNSIGNED NULL DEFAULT NULL,
  `id_jumlah_kunjungan` int UNSIGNED NULL DEFAULT NULL,
  `lama_bekerja` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `file_signature` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of responden_cst14
-- ----------------------------

-- ----------------------------
-- Table structure for responden_cst17
-- ----------------------------
DROP TABLE IF EXISTS `responden_cst17`;
CREATE TABLE `responden_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `handphone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `alamat_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_jenis_kelamin` int UNSIGNED NULL DEFAULT NULL,
  `id_umur` int UNSIGNED NULL DEFAULT NULL,
  `id_pendidikan_akhir` int UNSIGNED NULL DEFAULT NULL,
  `id_pekerjaan_utama` int UNSIGNED NULL DEFAULT NULL,
  `pekerjaan_lainnya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_pembiayaan` int UNSIGNED NULL DEFAULT NULL,
  `id_status_responden` int UNSIGNED NULL DEFAULT NULL,
  `id_jumlah_kunjungan` int UNSIGNED NULL DEFAULT NULL,
  `lama_bekerja` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `file_signature` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of responden_cst17
-- ----------------------------

-- ----------------------------
-- Table structure for responden_cst24
-- ----------------------------
DROP TABLE IF EXISTS `responden_cst24`;
CREATE TABLE `responden_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `handphone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `alamat_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_jenis_kelamin` int UNSIGNED NULL DEFAULT NULL,
  `id_umur` int UNSIGNED NULL DEFAULT NULL,
  `id_pendidikan_akhir` int UNSIGNED NULL DEFAULT NULL,
  `id_pekerjaan_utama` int UNSIGNED NULL DEFAULT NULL,
  `pekerjaan_lainnya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_pembiayaan` int UNSIGNED NULL DEFAULT NULL,
  `id_status_responden` int UNSIGNED NULL DEFAULT NULL,
  `id_jumlah_kunjungan` int UNSIGNED NULL DEFAULT NULL,
  `lama_bekerja` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `file_signature` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of responden_cst24
-- ----------------------------

-- ----------------------------
-- Table structure for responden_cst7
-- ----------------------------
DROP TABLE IF EXISTS `responden_cst7`;
CREATE TABLE `responden_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `handphone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `alamat_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_jenis_kelamin` int UNSIGNED NULL DEFAULT NULL,
  `id_umur` int UNSIGNED NULL DEFAULT NULL,
  `id_pendidikan_akhir` int UNSIGNED NULL DEFAULT NULL,
  `id_pekerjaan_utama` int UNSIGNED NULL DEFAULT NULL,
  `pekerjaan_lainnya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_pembiayaan` int UNSIGNED NULL DEFAULT NULL,
  `id_status_responden` int UNSIGNED NULL DEFAULT NULL,
  `id_jumlah_kunjungan` int UNSIGNED NULL DEFAULT NULL,
  `lama_bekerja` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `file_signature` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of responden_cst7
-- ----------------------------
INSERT INTO `responden_cst7` VALUES (13, '53d3ae90-74ca-45af-83f1-76f14b77ff9b', 'abdurrahman', '123', 'Surabaya', 1, 1, 1, 0, '', 0, 0, 0, 1, '2022-04-11 15:59:52', NULL);
INSERT INTO `responden_cst7` VALUES (14, '985197cb-c192-40b1-8df8-b1930cb5d662', 'abdurrahman', '123', 'Surabaya', 1, 1, 1, 0, '', 0, 0, 0, 1, '2022-04-11 16:42:35', NULL);

-- ----------------------------
-- Table structure for sampling
-- ----------------------------
DROP TABLE IF EXISTS `sampling`;
CREATE TABLE `sampling`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_sampling` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sampling
-- ----------------------------
INSERT INTO `sampling` VALUES (1, 'Krejcie');
INSERT INTO `sampling` VALUES (2, 'Cochran');
INSERT INTO `sampling` VALUES (3, 'Slovin');

-- ----------------------------
-- Table structure for status_berlangganan
-- ----------------------------
DROP TABLE IF EXISTS `status_berlangganan`;
CREATE TABLE `status_berlangganan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of status_berlangganan
-- ----------------------------
INSERT INTO `status_berlangganan` VALUES (1, 'Aktif');
INSERT INTO `status_berlangganan` VALUES (2, 'Dinonaktifkan');

-- ----------------------------
-- Table structure for status_responden
-- ----------------------------
DROP TABLE IF EXISTS `status_responden`;
CREATE TABLE `status_responden`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_status_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of status_responden
-- ----------------------------
INSERT INTO `status_responden` VALUES (1, 'Pasien');
INSERT INTO `status_responden` VALUES (2, 'Keluarga Pasien');

-- ----------------------------
-- Table structure for survey
-- ----------------------------
DROP TABLE IF EXISTS `survey`;
CREATE TABLE `survey`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_surveyor` bigint UNSIGNED NULL DEFAULT NULL,
  `id_kuesioner` bigint UNSIGNED NULL DEFAULT NULL,
  `waktu_isi` datetime NOT NULL,
  `foto_selfie` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_ktp` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `saran` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_input_surveyor` int NOT NULL,
  `is_testing` int NOT NULL,
  `is_active` int NOT NULL,
  `is_submit` int NOT NULL COMMENT '1=sudah submit, 2=belum submit',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `survey_id_responden_foreign`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `survey_id_responden_foreign` FOREIGN KEY (`id_responden`) REFERENCES `responden` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of survey
-- ----------------------------

-- ----------------------------
-- Table structure for survey_cst17
-- ----------------------------
DROP TABLE IF EXISTS `survey_cst17`;
CREATE TABLE `survey_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_surveyor` bigint UNSIGNED NULL DEFAULT NULL,
  `id_kuesioner` bigint UNSIGNED NULL DEFAULT NULL,
  `waktu_isi` datetime NULL DEFAULT NULL,
  `foto_selfie` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `foto_ktp` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `saran` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_input_surveyor` int NULL DEFAULT NULL,
  `is_testing` int NULL DEFAULT NULL,
  `is_active` int NULL DEFAULT NULL,
  `is_submit` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `survey_cst17_ibfk_1`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `survey_cst17_ibfk_1` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst17` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of survey_cst17
-- ----------------------------

-- ----------------------------
-- Table structure for survey_cst24
-- ----------------------------
DROP TABLE IF EXISTS `survey_cst24`;
CREATE TABLE `survey_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_surveyor` bigint UNSIGNED NULL DEFAULT NULL,
  `id_kuesioner` bigint UNSIGNED NULL DEFAULT NULL,
  `waktu_isi` datetime NULL DEFAULT NULL,
  `foto_selfie` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `foto_ktp` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `saran` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_input_surveyor` int NULL DEFAULT NULL,
  `is_testing` int NULL DEFAULT NULL,
  `is_active` int NULL DEFAULT NULL,
  `is_submit` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `survey_cst24_ibfk_1`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `survey_cst24_ibfk_1` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst24` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of survey_cst24
-- ----------------------------

-- ----------------------------
-- Table structure for survey_cst7
-- ----------------------------
DROP TABLE IF EXISTS `survey_cst7`;
CREATE TABLE `survey_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `id_responden` bigint UNSIGNED NULL DEFAULT NULL,
  `id_surveyor` bigint UNSIGNED NULL DEFAULT NULL,
  `id_kuesioner` bigint UNSIGNED NULL DEFAULT NULL,
  `waktu_isi` datetime NULL DEFAULT NULL,
  `foto_selfie` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `foto_ktp` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `saran` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `is_input_surveyor` int NULL DEFAULT NULL,
  `is_testing` int NULL DEFAULT NULL,
  `is_active` int NULL DEFAULT NULL,
  `is_submit` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `survey_cst7_ibfk_1`(`id_responden` ASC) USING BTREE,
  CONSTRAINT `survey_cst7_ibfk_1` FOREIGN KEY (`id_responden`) REFERENCES `responden_cst7` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of survey_cst7
-- ----------------------------
INSERT INTO `survey_cst7` VALUES (13, 'd86fcb72-f42c-42d5-8fd9-427990df155e', 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2);
INSERT INTO `survey_cst7` VALUES (14, 'bdd84132-fdfc-46b2-9f20-6f5168131369', 14, NULL, NULL, '2022-04-12 07:57:20', NULL, NULL, 'saran', NULL, NULL, NULL, 1);

-- ----------------------------
-- Table structure for surveyor
-- ----------------------------
DROP TABLE IF EXISTS `surveyor`;
CREATE TABLE `surveyor`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` bigint UNSIGNED NULL DEFAULT NULL,
  `id_manage_survey` bigint UNSIGNED NULL DEFAULT NULL,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_surveyor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `surveyor_id_user_foreign`(`id_user` ASC) USING BTREE,
  INDEX `surveyor_id_manage_survey_foreign`(`id_manage_survey` ASC) USING BTREE,
  CONSTRAINT `surveyor_id_manage_survey_foreign` FOREIGN KEY (`id_manage_survey`) REFERENCES `manage_survey` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `surveyor_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of surveyor
-- ----------------------------
INSERT INTO `surveyor` VALUES (2, 5, 7, 'fc2aa8ee-26fe-4ab9-8ca1-2be4dc45e931', 'SURV001', NULL, NULL);
INSERT INTO `surveyor` VALUES (6, 10, 17, '52f39d67-ba5b-4a9c-8941-9dbf77c93350', 'SURV002', NULL, NULL);

-- ----------------------------
-- Table structure for template
-- ----------------------------
DROP TABLE IF EXISTS `template`;
CREATE TABLE `template`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_template` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of template
-- ----------------------------
INSERT INTO `template` VALUES (1, 'Dengan Template');
INSERT INTO `template` VALUES (2, 'Tanpa Template');

-- ----------------------------
-- Table structure for umur
-- ----------------------------
DROP TABLE IF EXISTS `umur`;
CREATE TABLE `umur`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `umur_responden` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of umur
-- ----------------------------
INSERT INTO `umur` VALUES (1, '16 - 25 th');
INSERT INTO `umur` VALUES (2, '26 - 35 th');
INSERT INTO `umur` VALUES (3, '36 - 45 th');
INSERT INTO `umur` VALUES (4, '46 - 55 th');
INSERT INTO `umur` VALUES (5, '56 - 65 th');
INSERT INTO `umur` VALUES (6, '> 65 th');

-- ----------------------------
-- Table structure for unsur_pelayanan
-- ----------------------------
DROP TABLE IF EXISTS `unsur_pelayanan`;
CREATE TABLE `unsur_pelayanan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_jenis_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_unsur_pelayanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_sub_unsur_pelayanan` int NOT NULL,
  `id_parent` bigint NOT NULL,
  `nomor_unsur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `unsur_pelayanan_id_jenis_pelayanan_foreign`(`id_jenis_pelayanan` ASC) USING BTREE,
  CONSTRAINT `unsur_pelayanan_id_jenis_pelayanan_foreign` FOREIGN KEY (`id_jenis_pelayanan`) REFERENCES `jenis_pelayanan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of unsur_pelayanan
-- ----------------------------
INSERT INTO `unsur_pelayanan` VALUES (4, '21767c26-67dc-4cda-880a-5e4d8864c771', 1, 'Persyaratan Pelayanan', 2, 0, 'U1');
INSERT INTO `unsur_pelayanan` VALUES (5, '904c7d43-e6e3-4cfc-9e5c-b58f09a68ba7', 1, 'Kemudahan Prosedur', 2, 0, 'U2');
INSERT INTO `unsur_pelayanan` VALUES (6, 'ad5c7a94-5e0b-410a-9b80-861aea839bad', 1, 'Kecepatan Pelayanan (Admin)', 1, 5, 'U2.1');
INSERT INTO `unsur_pelayanan` VALUES (7, 'c6717498-ddf1-4a27-bd21-38e791d0bd60', 1, 'Kemudahan Prosedur (Perawat)', 1, 5, 'U2.2');
INSERT INTO `unsur_pelayanan` VALUES (8, '75e43541-c5e1-44f1-940b-ac59cb4e914a', 1, 'Kemudahan Prosedur (Dokter)', 1, 5, 'U2.3');

-- ----------------------------
-- Table structure for unsur_pelayanan_cst17
-- ----------------------------
DROP TABLE IF EXISTS `unsur_pelayanan_cst17`;
CREATE TABLE `unsur_pelayanan_cst17`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_jenis_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_unsur_pelayanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_sub_unsur_pelayanan` int NOT NULL,
  `id_parent` bigint NOT NULL,
  `nomor_unsur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `unsur_pelayanan_id_jenis_pelayanan_foreign`(`id_jenis_pelayanan` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of unsur_pelayanan_cst17
-- ----------------------------
INSERT INTO `unsur_pelayanan_cst17` VALUES (4, '21767c26-67dc-4cda-880a-5e4d8864c771', 1, 'Persyaratan Pelayanan', 2, 0, 'U1');
INSERT INTO `unsur_pelayanan_cst17` VALUES (5, '904c7d43-e6e3-4cfc-9e5c-b58f09a68ba7', 1, 'Kemudahan Prosedur', 2, 0, 'U2');
INSERT INTO `unsur_pelayanan_cst17` VALUES (6, 'ad5c7a94-5e0b-410a-9b80-861aea839bad', 1, 'Kecepatan Pelayanan (Admin)', 1, 5, 'U2.1');
INSERT INTO `unsur_pelayanan_cst17` VALUES (7, 'c6717498-ddf1-4a27-bd21-38e791d0bd60', 1, 'Kemudahan Prosedur (Perawat)', 1, 5, 'U2.2');
INSERT INTO `unsur_pelayanan_cst17` VALUES (8, '75e43541-c5e1-44f1-940b-ac59cb4e914a', 1, 'Kemudahan Prosedur (Dokter)', 1, 5, 'U2.3');

-- ----------------------------
-- Table structure for unsur_pelayanan_cst24
-- ----------------------------
DROP TABLE IF EXISTS `unsur_pelayanan_cst24`;
CREATE TABLE `unsur_pelayanan_cst24`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_jenis_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_unsur_pelayanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_sub_unsur_pelayanan` int NOT NULL,
  `id_parent` bigint NOT NULL,
  `nomor_unsur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `unsur_pelayanan_id_jenis_pelayanan_foreign`(`id_jenis_pelayanan` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of unsur_pelayanan_cst24
-- ----------------------------
INSERT INTO `unsur_pelayanan_cst24` VALUES (4, '21767c26-67dc-4cda-880a-5e4d8864c771', 1, 'Persyaratan Pelayanan', 2, 0, 'U1');
INSERT INTO `unsur_pelayanan_cst24` VALUES (5, '904c7d43-e6e3-4cfc-9e5c-b58f09a68ba7', 1, 'Kemudahan Prosedur', 2, 0, 'U2');
INSERT INTO `unsur_pelayanan_cst24` VALUES (6, 'ad5c7a94-5e0b-410a-9b80-861aea839bad', 1, 'Kecepatan Pelayanan (Admin)', 1, 5, 'U2.1');
INSERT INTO `unsur_pelayanan_cst24` VALUES (7, 'c6717498-ddf1-4a27-bd21-38e791d0bd60', 1, 'Kemudahan Prosedur (Perawat)', 1, 5, 'U2.2');
INSERT INTO `unsur_pelayanan_cst24` VALUES (8, '75e43541-c5e1-44f1-940b-ac59cb4e914a', 1, 'Kemudahan Prosedur (Dokter)', 1, 5, 'U2.3');

-- ----------------------------
-- Table structure for unsur_pelayanan_cst7
-- ----------------------------
DROP TABLE IF EXISTS `unsur_pelayanan_cst7`;
CREATE TABLE `unsur_pelayanan_cst7`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_jenis_pelayanan` bigint UNSIGNED NULL DEFAULT NULL,
  `nama_unsur_pelayanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_sub_unsur_pelayanan` int NOT NULL,
  `id_parent` bigint NOT NULL,
  `nomor_unsur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `unsur_pelayanan_id_jenis_pelayanan_foreign`(`id_jenis_pelayanan` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of unsur_pelayanan_cst7
-- ----------------------------
INSERT INTO `unsur_pelayanan_cst7` VALUES (4, '21767c26-67dc-4cda-880a-5e4d8864c771', 1, 'Persyaratan Pelayanan', 2, 0, 'U1');
INSERT INTO `unsur_pelayanan_cst7` VALUES (5, '904c7d43-e6e3-4cfc-9e5c-b58f09a68ba7', 1, 'Kemudahan Prosedur', 2, 0, 'U2');
INSERT INTO `unsur_pelayanan_cst7` VALUES (6, 'ad5c7a94-5e0b-410a-9b80-861aea839bad', 1, 'Kecepatan Pelayanan (Admin)', 1, 5, 'U2.1');
INSERT INTO `unsur_pelayanan_cst7` VALUES (7, 'c6717498-ddf1-4a27-bd21-38e791d0bd60', 1, 'Kemudahan Prosedur (Perawat)', 1, 5, 'U2.2');
INSERT INTO `unsur_pelayanan_cst7` VALUES (8, '75e43541-c5e1-44f1-940b-ac59cb4e914a', 1, 'Kemudahan Prosedur (Dokter)', 1, 5, 'U2.3');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `re_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_selector` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activation_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `forgotten_password_selector` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `forgotten_password_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `forgotten_password_time` int UNSIGNED NULL DEFAULT NULL,
  `remember_selector` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remember_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_on` int UNSIGNED NULL DEFAULT NULL,
  `last_login` int UNSIGNED NULL DEFAULT NULL,
  `active` tinyint NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_klasifikasi_survei` bigint UNSIGNED NULL DEFAULT NULL,
  `is_surveyor` int NULL DEFAULT NULL,
  `foto_profile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `users_id_klasifikasi_survei_foreign`(`id_klasifikasi_survei` ASC) USING BTREE,
  CONSTRAINT `users_id_klasifikasi_survei_foreign` FOREIGN KEY (`id_klasifikasi_survei`) REFERENCES `klasifikasi_survei` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, '127.0.0.1', 'administrator', '$2y$10$/GpW3KvE.L8iI48NO9WB0OWomEV0qSwbg/VFaXt3xdri.kHR8BHpq', 'password', 'admin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1649923045, 1, 'Admin', 'istrator', 'ADMIN', '0', NULL, NULL, NULL);
INSERT INTO `users` VALUES (4, '192.168.1.27', 'abdhanif', '$2y$10$.bibV9sC2unDWqvKbnpI7.IqQjDC2WPssOrbx54qQvnNAua1UehiW', '12345678', 'programmer@kokek.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1649379593, 1649930013, 1, 'Abdurrahman', 'Hanif', 'kokek', '1234', 1, NULL, 'profil_4.png');
INSERT INTO `users` VALUES (5, '192.168.1.27', 's11', '$2y$10$Wzs3nmziiR38t8eEGPQH7.UJuMxb5GYv1nk3seDInuUciAGAkGnGG', '12345678', 'abdurrahmanhanif85@gmail.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1649390168, NULL, 1, 'Abdurrahman', 'hanif', 'capil', '122', NULL, 1, NULL);
INSERT INTO `users` VALUES (6, '192.168.1.59', 'lefi-andri', '$2y$10$NsA3oNJu8oE8rs676o19Ru0x23FgiOXLG9JtYjPbL2QJOCHFQn8.m', '~^l=*&l,', 'lefi.andri@kokek.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1649400305, 1649898596, 1, 'Lefi', 'Andri', 'PT. Sukses Jaya Maju', '12345678', 1, NULL, NULL);
INSERT INTO `users` VALUES (7, '192.168.1.59', 'muzaki', '$2y$10$TwYaeKYstgdm4whP32XrF.xBtVkFDuLuNaF802HiG3qeABwlS.vsK', '12345678', 'muzaki@gmail.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1649655430, 1649656418, 1, 'Ahmad', 'Muzaki', 'PT. Sukses Jaya Maju', '123', NULL, 1, NULL);
INSERT INTO `users` VALUES (8, '192.168.1.59', 'surveyor', '$2y$10$f2M50p24tIsi4lun3rB8t.ammZ5cfT8VvDT0Q9Onc9sCmDpBBvzXa', '12345678', 'surveyor@gmail.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1649732475, NULL, 1, 'Surveyor', 'Demo', 'PT. Sukses Jaya Maju', '1212', NULL, 1, NULL);
INSERT INTO `users` VALUES (9, '192.168.1.27', 'hanif99', '$2y$10$UG5QfP1e5sKeaai8ukOhG.M..Hj5SfWCslWcjxNZDv4gRPOS6i.tO', '12345678', 'hanif8@gmail.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1649745666, 1649745680, 1, 'Admin', 'Hanif', 'kokek', '222', NULL, 1, NULL);
INSERT INTO `users` VALUES (10, '192.168.1.27', 'hanif09', '$2y$10$Xg8IAeGs8Oh.NafGUc0xO.r6eIxJ/VX17B6uXudakyeytKjYc3nwe', '12345678', 'capil@gmail.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1649746915, 1649929456, 1, 'hanif', '09', 'kokek', '3434243', NULL, 1, NULL);

-- ----------------------------
-- Table structure for users_groups
-- ----------------------------
DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `group_id` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `users_groups_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `users_groups_group_id_foreign`(`group_id` ASC) USING BTREE,
  CONSTRAINT `users_groups_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users_groups
-- ----------------------------
INSERT INTO `users_groups` VALUES (1, 1, 1);
INSERT INTO `users_groups` VALUES (4, 4, 2);
INSERT INTO `users_groups` VALUES (5, 5, 3);
INSERT INTO `users_groups` VALUES (6, 6, 2);
INSERT INTO `users_groups` VALUES (7, 7, 3);
INSERT INTO `users_groups` VALUES (8, 8, 3);
INSERT INTO `users_groups` VALUES (9, 9, 3);
INSERT INTO `users_groups` VALUES (10, 10, 3);

-- ----------------------------
-- Table structure for web_settings
-- ----------------------------
DROP TABLE IF EXISTS `web_settings`;
CREATE TABLE `web_settings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `alias` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of web_settings
-- ----------------------------
INSERT INTO `web_settings` VALUES (1, 'Logo Light', 'logo_light', NULL);
INSERT INTO `web_settings` VALUES (2, 'Logo Dark', 'logo_dark', NULL);
INSERT INTO `web_settings` VALUES (3, 'Official Logo', 'official_logo', NULL);
INSERT INTO `web_settings` VALUES (4, 'Favicon 180', 'fav_180', NULL);
INSERT INTO `web_settings` VALUES (5, 'Favicon 32', 'fav_32', NULL);
INSERT INTO `web_settings` VALUES (6, 'Favicon 16', 'fav_16', NULL);
INSERT INTO `web_settings` VALUES (7, 'Favicon ICO', 'favicon', NULL);
INSERT INTO `web_settings` VALUES (8, 'Akun Email', 'akun_email', 'autoreply@kokek.com');
INSERT INTO `web_settings` VALUES (9, 'Email Pengirim', 'email_pengirim', 'autoreply@kokek.com');
INSERT INTO `web_settings` VALUES (10, 'Email Username', 'email_username', 'autoreply@kokek.com');
INSERT INTO `web_settings` VALUES (11, 'Email Password', 'email_password', 'CxK]3~,cn0l$');
INSERT INTO `web_settings` VALUES (12, 'Email Host', 'email_host', 'mail.kokek.com');
INSERT INTO `web_settings` VALUES (13, 'Email Port', 'email_port', '587');
INSERT INTO `web_settings` VALUES (14, 'Email CC', 'email_cc', 'programmer@kokek.com');
INSERT INTO `web_settings` VALUES (15, 'Email BCC', 'email_bcc', 'lefi.andri@kokek.com');

-- ----------------------------
-- View structure for view_unsur_pelayanan
-- ----------------------------
DROP VIEW IF EXISTS `view_unsur_pelayanan`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_unsur_pelayanan` AS select `unsur_pelayanan`.`id` AS `id_unsur_pelayanan`,`unsur_pelayanan`.`nama_unsur_pelayanan` AS `nama_unsur_pelayanan`,`unsur_pelayanan`.`nomor_unsur` AS `nomor_unsur`,`jenis_pelayanan`.`id` AS `id_jenis_pelayanan`,`jenis_pelayanan`.`nama_jenis_pelayanan_responden` AS `nama_jenis_pelayanan_responden`,`klasifikasi_survei`.`nama_klasifikasi_survei` AS `nama_klasifikasi_survei`,`unsur_pelayanan`.`id_parent` AS `id_parent`,(select `up`.`nama_unsur_pelayanan` from `unsur_pelayanan` `up` where (`unsur_pelayanan`.`id_parent` = `up`.`id`)) AS `nama_unsur_relasi`,(select `up`.`nomor_unsur` from `unsur_pelayanan` `up` where (`unsur_pelayanan`.`id_parent` = `up`.`id`)) AS `nomor_unsur_relasi` from ((`unsur_pelayanan` join `jenis_pelayanan` on((`jenis_pelayanan`.`id` = `unsur_pelayanan`.`id_jenis_pelayanan`))) join `klasifikasi_survei` on((`klasifikasi_survei`.`id` = `jenis_pelayanan`.`id_klasifikasi_survei`)));

SET FOREIGN_KEY_CHECKS = 1;
