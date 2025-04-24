/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 80034 (8.0.34)
 Source Host           : localhost:3306
 Source Schema         : nckh_huyen

 Target Server Type    : MySQL
 Target Server Version : 80034 (8.0.34)
 File Encoding         : 65001

 Date: 28/07/2024 15:26:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for chukys
-- ----------------------------
DROP TABLE IF EXISTS `chukys`;
CREATE TABLE `chukys`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `phieu_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `chukys_teacher_id_foreign`(`teacher_id` ASC) USING BTREE,
  INDEX `chukys_student_id_foreign`(`student_id` ASC) USING BTREE,
  INDEX `chukys_phieu_id_foreign`(`phieu_id` ASC) USING BTREE,
  CONSTRAINT `chukys_phieu_id_foreign` FOREIGN KEY (`phieu_id`) REFERENCES `phieus` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `chukys_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `chukys_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chukys
-- ----------------------------

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for khoas
-- ----------------------------
DROP TABLE IF EXISTS `khoas`;
CREATE TABLE `khoas`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `khoas_name_unique`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of khoas
-- ----------------------------
INSERT INTO `khoas` VALUES (1, 'Khoa Nghệ thuật', '2024-07-28 06:00:40', '2024-07-28 06:00:40');
INSERT INTO `khoas` VALUES (2, 'Khoa Công nghệ thông tin', '2024-07-28 06:00:40', '2024-07-28 06:00:40');
INSERT INTO `khoas` VALUES (3, 'Khoa Ngoại ngữ', '2024-07-28 06:00:40', '2024-07-28 06:00:40');
INSERT INTO `khoas` VALUES (4, 'Khoa Du lịch', '2024-07-28 06:00:40', '2024-07-28 06:00:40');
INSERT INTO `khoas` VALUES (5, 'Khoa Thủy Sản', '2024-07-28 06:00:40', '2024-07-28 06:00:40');
INSERT INTO `khoas` VALUES (6, 'Khoa Môi Trường', '2024-07-28 06:00:40', '2024-07-28 06:00:40');
INSERT INTO `khoas` VALUES (7, 'Khoa Văn hóa', '2024-07-28 06:00:40', '2024-07-28 06:00:40');
INSERT INTO `khoas` VALUES (8, 'Khoa Sư phạm', '2024-07-28 06:00:40', '2024-07-28 06:00:40');
INSERT INTO `khoas` VALUES (9, 'Khoa Khoa học cơ bản', '2024-07-28 06:00:40', '2024-07-28 06:00:40');

-- ----------------------------
-- Table structure for lops
-- ----------------------------
DROP TABLE IF EXISTS `lops`;
CREATE TABLE `lops`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nganh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `khoa_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `hocphi` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `lops_khoa_id_foreign`(`khoa_id` ASC) USING BTREE,
  INDEX `lops_teacher_id_foreign`(`teacher_id` ASC) USING BTREE,
  CONSTRAINT `lops_khoa_id_foreign` FOREIGN KEY (`khoa_id`) REFERENCES `khoas` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `lops_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 397 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of lops
-- ----------------------------
INSERT INTO `lops` VALUES (220, 'Nhạc cụ TT K15', 'Biểu diễn nhạc cụ truyền thống', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (221, 'Nhạc cụ HĐ K15', 'Biểu diễn nhạc cụ Phương Tây', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (222, 'TC Múa K11', 'Thanh nhạc (TC)', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (223, 'Nhạc cụ HĐ K16', 'Biểu diễn nhạc cụ Phương Tây', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (224, 'Nhạc cụ TT K16', 'Biểu diễn nhạc cụ truyền thống', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (225, 'TC Múa K12', 'Thanh nhạc (TC)', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (226, 'Khoa học Máy tính K6A', 'Khoa học máy tính', 2, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (227, 'Khoa học Máy tính K6B', 'Khoa học máy tính', 2, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (228, 'Ngôn ngữ Hàn Quốc K2A', 'Ngôn ngữ Hàn Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (229, 'Ngôn ngữ Hàn Quốc K2B', 'Ngôn ngữ Hàn Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (230, 'Ngôn ngữ Anh K6A', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (231, 'Ngôn ngữ Anh K6B', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (232, 'Ngôn ngữ Anh K6C', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (233, 'Ngôn ngữ Nhật K5A', 'Ngôn ngữ Nhật', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (234, 'Ngôn ngữ Nhật K5B', 'Ngôn ngữ Nhật', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (235, 'Ngôn ngữ Trung Quốc K6A', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (236, 'Ngôn ngữ Trung Quốc K6F', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (237, 'Ngôn ngữ Trung Quốc K6B', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (238, 'Ngôn ngữ Trung Quốc K6C', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (239, 'Ngôn ngữ Trung Quốc K6D', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (240, 'Ngôn ngữ Trung Quốc K6E', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (241, 'QT DVDL& LH  K6A', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (242, 'QT DVDL& LH  K6B', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (243, 'QT DVDL& LH  K6C', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (244, 'QT DVDL& LH  K6D', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (245, 'QT Khách sạn K5A', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (246, 'QT Khách sạn K5B', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (247, 'QT Khách sạn K5C', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (248, 'QT Khách sạn K5D', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (249, 'QT NH&DVAU K3A', 'Quản trị nhà hàng và dịch vụ ăn uống', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (250, 'QT NH&DVAU K3B', 'Quản trị nhà hàng và dịch vụ ăn uống', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (251, 'Nuôi trồng Thủy sản K5', 'Nuôi trồng thủy sản', 5, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (252, 'QLTN và môi trường K5', 'Quản lý tài nguyên và môi trường', 6, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (253, 'Quản lý Văn hóa K6A', 'Quản lý văn hóa', 7, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (254, 'Quản lý Văn hóa K6B', 'Quản lý văn hóa', 7, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (255, 'Hội họa K11', 'Hội họa', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (256, 'TC Múa K13', 'Thanh nhạc (TC)', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (257, 'Thanh nhạc K14', 'Thanh nhạc (CĐ)', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (258, 'Nhạc cụ TT K17', 'Biểu diễn nhạc cụ truyền thống', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (259, 'Nhạc cụ HĐ K17', 'Biểu diễn nhạc cụ Phương Tây', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (260, ' Mầm non K1', 'Giáo dục Mầm non (ĐH)', 8, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (261, ' Tiểu học K1A', 'Giáo dục Tiểu học', 8, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (262, ' Tiểu học K1B', 'Giáo dục Tiểu học', 8, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (263, ' Tiểu học K1C', 'Giáo dục Tiểu học', 8, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (264, ' Tiểu học K1D', 'Giáo dục Tiểu học', 8, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (265, 'Khoa học Máy tính K7A', 'Khoa học máy tính', 2, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (266, 'Khoa học Máy tính K7B', 'Khoa học máy tính', 2, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (267, 'Khoa học Máy tính K7C', 'Khoa học máy tính', 2, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (268, 'Ngôn ngữ Anh K7A', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (269, 'Ngôn ngữ Anh K7B', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (270, 'Ngôn ngữ Anh K7C', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (271, 'Ngôn ngữ Anh K7D', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (272, 'Ngôn ngữ Anh K7E', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (273, 'Ngôn ngữ Hàn Quốc K3A', 'Ngôn ngữ Hàn Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (274, 'Ngôn ngữ Hàn Quốc K3B', 'Ngôn ngữ Hàn Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (275, 'Ngôn ngữ Nhật K6A', 'Ngôn ngữ Nhật', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (276, 'Ngôn ngữ Nhật K6B', 'Ngôn ngữ Nhật', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (277, 'Ngôn ngữ Trung Quốc K7A', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (278, 'Ngôn ngữ Trung Quốc K7B', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (279, 'Ngôn ngữ Trung Quốc K7C', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (280, 'Ngôn ngữ Trung Quốc K7D', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (281, 'Ngôn ngữ Trung Quốc K7E', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (282, 'Ngôn ngữ Trung Quốc K7F', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (283, 'Ngôn ngữ Trung Quốc K7G', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (284, 'Ngôn ngữ Trung Quốc K7H', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (285, 'Nuôi trồng Thủy sản K6', 'Nuôi trồng thủy sản', 5, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (286, 'QLTN và môi trường K6', 'Quản lý tài nguyên và môi trường', 6, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (287, 'QT DVDL& LH  K7A', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (288, 'QT DVDL& LH  K7B', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (289, 'QT DVDL& LH  K7C', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (290, 'QT DVDL& LH  K7D', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (291, 'QT Khách sạn K6A', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (292, 'QT Khách sạn K6B', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (293, 'QT Khách sạn K6C', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (294, 'QT NH&DVAU K4A', 'Quản trị nhà hàng và dịch vụ ăn uống', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (295, 'QT NH&DVAU K4B', 'Quản trị nhà hàng và dịch vụ ăn uống', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (296, 'Quản lý Văn hóa K7', 'Quản lý văn hóa', 7, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (297, 'CM K20', 'Sư phạm', 8, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (298, 'Hội họa K12', 'Hội họa', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (299, 'Thanh nhạc K15', 'Thanh nhạc (CĐ)', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (300, 'Nhạc cụ TT K18', 'Biểu diễn nhạc cụ truyền thống', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (301, 'Nhạc cụ HĐ K18', 'Biểu diễn nhạc cụ Phương Tây', 1, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 0);
INSERT INTO `lops` VALUES (302, 'Ngôn ngữ Anh K8A', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (303, 'Ngôn ngữ Anh K8B', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (304, 'Ngôn ngữ Anh K8C', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (305, 'Ngôn ngữ Anh K8D', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (306, 'Ngôn ngữ Hàn Quốc K4A', 'Ngôn ngữ Hàn Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (307, 'Ngôn ngữ Hàn Quốc K4B', 'Ngôn ngữ Hàn Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (308, 'Ngôn ngữ Hàn Quốc K4C', 'Ngôn ngữ Hàn Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (309, 'Ngôn ngữ Nhật K7A', 'Ngôn ngữ Nhật', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (310, 'Ngôn ngữ Nhật K7B', 'Ngôn ngữ Nhật', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (311, 'Ngôn ngữ Trung Quốc K8A', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (312, 'Ngôn ngữ Trung Quốc K8B', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (313, 'Ngôn ngữ Trung Quốc K8C', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (314, 'Ngôn ngữ Trung Quốc K8D', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (315, 'Ngôn ngữ Trung Quốc K8E', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (316, 'Ngôn ngữ Trung Quốc K8F', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 4875000);
INSERT INTO `lops` VALUES (317, 'QT DVDL& LH K8A', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (318, 'QT DVDL& LH K8B', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (319, 'QT DVDL& LH K8C', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (320, 'QT DVDL& LH K8D', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (321, 'QT Khách Sạn K7A', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (322, 'QT Khách Sạn K7B', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (323, 'QT Khách Sạn K7C', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (324, 'QT Khách Sạn K7D', 'Quản trị khách sạn', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (325, 'QT NH&DVAU K5A', 'Quản trị nhà hàng và dịch vụ ăn uống', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (326, 'QT NH&DVAU K5B', 'Quản trị nhà hàng và dịch vụ ăn uống', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5775000);
INSERT INTO `lops` VALUES (327, 'QT Kinh Doanh K1A', 'Quản trị kinh doanh', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5500000);
INSERT INTO `lops` VALUES (328, 'QT Kinh Doanh K1B', 'Quản trị kinh doanh', 4, '2024-07-28 06:25:39', '2024-07-28 06:25:39', NULL, 5500000);
INSERT INTO `lops` VALUES (329, 'Khoa học Máy Tính K8A', 'Khoa học máy tính', 2, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (330, 'Khoa học Máy Tính K8B', 'Khoa học máy tính', 2, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (331, 'Khoa học Máy Tính K8C', 'Khoa học máy tính', 2, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (332, 'TK Đồ họa K1A', 'Thiết kế đồ họa', 2, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (333, 'TK Đồ họa K1B', 'Thiết kế đồ họa', 2, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (334, 'Nuôi trồng Thủy sản k7', 'Nuôi trồng thủy sản', 5, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (335, 'QLTN và Môi trường K7', 'Quản lý tài nguyên và môi trường', 6, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (336, 'Quản lý Văn hóa K8', 'Quản lý văn hóa', 7, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (337, 'Văn học K1', 'Sư phạm Ngữ Văn', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (338, 'Mầm non K2', 'Giáo dục Mầm non (ĐH)', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (339, 'Tiểu học K2A', 'Giáo dục Tiểu học', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (340, 'Tiểu học K2B', 'Giáo dục Tiểu học', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (341, 'Tiểu học K2C', 'Giáo dục Tiểu học', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (342, 'CM K21', 'Sư phạm', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (343, 'QT DVDL & Lữ hành K15', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (344, 'QT Khách sạn K18', 'Quản trị khách sạn', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (345, 'QT NH&DVAU K16', 'Quản trị nhà hàng và dịch vụ ăn uống', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (346, 'CĐ Thanh nhạc K12', 'Thanh nhạc (CĐ)', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (347, 'TC Múa K14', 'Thanh nhạc (TC)', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (348, 'Hội họa K13', 'Hội họa', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (349, 'Nhạc cụ HĐ K19', 'Biểu diễn nhạc cụ Phương Tây', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (350, 'Nhạc cụ TT K19', 'Biểu diễn nhạc cụ truyền thống', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (351, 'Thanh nhạc K16', 'Thanh nhạc (CĐ)', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (352, 'K9.LH 9A', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (353, 'K9.LH 9B', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (354, 'K9.LH 9C', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (355, 'K9.LH 9D', 'Quản trị dịch vụ du lịch và lữ hành', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (356, 'K9.KS 8A', 'Quản trị khách sạn', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (357, 'K9.KS 8B', 'Quản trị khách sạn', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (358, 'K9.KS 8C', 'Quản trị khách sạn', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (359, 'K9.QTKD 2A', 'Quản trị kinh doanh', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5500000);
INSERT INTO `lops` VALUES (360, 'K9.QTKD 2B', 'Quản trị kinh doanh', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5500000);
INSERT INTO `lops` VALUES (361, 'K9.QTKD 2C', 'Quản trị kinh doanh', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5500000);
INSERT INTO `lops` VALUES (362, 'K9.AU 6A', 'Quản trị nhà hàng và dịch vụ ăn uống', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (363, 'K9.AU 6B', 'Quản trị nhà hàng và dịch vụ ăn uống', 4, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (364, 'K9.TQ 9A', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (365, 'K9.TQ 9B', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (366, 'K9.TQ 9C', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (367, 'K9.TQ 9D', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (368, 'K9.TQ 9E', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (369, 'K9.TQ 9F', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (370, 'K9.TQ 9G', 'Ngôn ngữ Trung Quốc', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (371, 'K9.Anh 9A', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (372, 'K9.Anh 9B', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (373, 'K9.Anh 9C', 'Ngôn ngữ Anh', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (374, 'K9.HQ 5A', 'Ngôn ngữ Hàn Quốc', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (375, 'K9.HQ 5B', 'Ngôn ngữ Hàn Quốc', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (376, 'K9.Nhật 8', 'Ngôn ngữ Nhật', 3, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (377, 'K9.CNTT 1A', 'Công nghệ thông tin', 2, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (378, 'K9.CNTT 1B', 'Công nghệ thông tin', 2, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (379, 'K9.CNTT 1C', 'Công nghệ thông tin', 2, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (380, 'K9.KHMT9', 'Khoa học máy tính', 2, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (381, 'K9.TKDH 2', 'Thiết kế đồ họa', 2, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 5775000);
INSERT INTO `lops` VALUES (382, 'K9.TS 8', 'Nuôi trồng thủy sản', 5, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (383, 'K9.VH 9', 'Quản lý văn hóa', 7, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (384, 'K9.MT 8', 'Quản lý tài nguyên và môi trường', 6, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (385, 'K9.MN 3A', 'Giáo dục Mầm non (ĐH)', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (386, 'K9.MN 3B', 'Giáo dục Mầm non (ĐH)', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (387, 'K9.TH 3A', 'Giáo dục Tiểu học', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (388, 'K9.TH 3B', 'Giáo dục Tiểu học', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (389, 'K9.TH 3C', 'Giáo dục Tiểu học', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (390, 'K9.Văn học K2', 'Sư phạm Ngữ Văn', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 4875000);
INSERT INTO `lops` VALUES (391, 'K9.CM K22', 'Sư phạm', 8, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (392, 'CĐ Thanh nhạc K13', 'Thanh nhạc (CĐ)', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (393, 'Hội Họa K14', 'Hội họa', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (394, 'Thanh Nhạc K17', 'Thanh nhạc (CĐ)', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (395, 'Nhạc cụ PT K20', 'Biểu diễn nhạc cụ Phương Tây', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);
INSERT INTO `lops` VALUES (396, 'Nhạc cụ TT K20', 'Biểu diễn nhạc cụ truyền thống', 1, '2024-07-28 06:25:40', '2024-07-28 06:25:40', NULL, 0);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2024_04_06_021617_create_khoas_table', 1);
INSERT INTO `migrations` VALUES (6, '2024_04_06_021627_create_lops_table', 1);
INSERT INTO `migrations` VALUES (7, '2024_04_06_021647_create_students_table', 1);
INSERT INTO `migrations` VALUES (8, '2024_04_06_031515_create_teachers_table', 1);
INSERT INTO `migrations` VALUES (9, '2024_04_06_031516_add_column_student_id', 1);
INSERT INTO `migrations` VALUES (10, '2024_04_09_005508_table_student_add_cmnd', 1);
INSERT INTO `migrations` VALUES (11, '2024_04_09_065910_table_lop_add_teacher_id', 1);
INSERT INTO `migrations` VALUES (12, '2024_04_09_153524_create_phieus_table', 1);
INSERT INTO `migrations` VALUES (13, '2024_04_11_064441_create_stop_studies_table', 1);
INSERT INTO `migrations` VALUES (14, '2024_04_12_013233_table_stop_studies_add_status', 1);
INSERT INTO `migrations` VALUES (15, '2024_04_12_021611_create_notifications_table', 1);
INSERT INTO `migrations` VALUES (16, '2024_04_12_105400_table_stop_studies_add_lop_id', 1);
INSERT INTO `migrations` VALUES (17, '2024_04_14_132549_add_files_column_to_stop_studies_table', 1);
INSERT INTO `migrations` VALUES (18, '2024_04_14_143343_add_tpye_column_to_stop_studies_table', 1);
INSERT INTO `migrations` VALUES (19, '2024_04_18_040438_add_teacher_id_to_stop_studies_table', 1);
INSERT INTO `migrations` VALUES (20, '2024_04_22_015739_table_stop_studies_add_cloumn', 1);
INSERT INTO `migrations` VALUES (21, '2024_05_12_043802_add_column_type_miengiamhp', 1);
INSERT INTO `migrations` VALUES (22, '2024_05_13_153654_add_column_hocphi', 1);
INSERT INTO `migrations` VALUES (23, '2024_05_13_165034_add_column_phantramgiam', 1);
INSERT INTO `migrations` VALUES (24, '2024_05_18_182319_add_column_status', 1);
INSERT INTO `migrations` VALUES (25, '2024_05_27_070627_add_columm_theodoigiaiquyet', 1);
INSERT INTO `migrations` VALUES (26, '2024_05_28_133510_add_column_trocapxh', 1);
INSERT INTO `migrations` VALUES (27, '2024_06_20_082110_add_cloumn_chu_ky', 1);
INSERT INTO `migrations` VALUES (28, '2024_06_21_142500_add_column_chu_ky', 1);
INSERT INTO `migrations` VALUES (29, '2024_06_22_013713_create_otps_table', 1);
INSERT INTO `migrations` VALUES (30, '2024_06_22_014314_create_chukys_table', 1);
INSERT INTO `migrations` VALUES (31, '2024_07_04_210944_add_column_doi_tuong_chinh_sach', 1);
INSERT INTO `migrations` VALUES (32, '2024_07_04_213624_create_settings_table', 1);
INSERT INTO `migrations` VALUES (33, '2024_07_05_004910_add_column_che_do_chinh_sach_data', 1);
INSERT INTO `migrations` VALUES (34, '2024_07_06_170342_add_column_status_phieu', 1);
INSERT INTO `migrations` VALUES (35, '2024_07_10_032929_add_column_diachi_km', 1);
INSERT INTO `migrations` VALUES (36, '2024_07_22_040711_add_column_file_tb_phieu', 1);

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `phieu_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `notifications_phieu_id_foreign`(`phieu_id` ASC) USING BTREE,
  INDEX `notifications_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `notifications_phieu_id_foreign` FOREIGN KEY (`phieu_id`) REFERENCES `phieus` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notifications
-- ----------------------------

-- ----------------------------
-- Table structure for otps
-- ----------------------------
DROP TABLE IF EXISTS `otps`;
CREATE TABLE `otps`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `otp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `otps_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `otps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of otps
-- ----------------------------

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

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
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for phieus
-- ----------------------------
DROP TABLE IF EXISTS `phieus`;
CREATE TABLE `phieus`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` json NOT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `phieus_student_id_foreign`(`student_id` ASC) USING BTREE,
  INDEX `phieus_teacher_id_foreign`(`teacher_id` ASC) USING BTREE,
  CONSTRAINT `phieus_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `phieus_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of phieus
-- ----------------------------

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of settings
-- ----------------------------

-- ----------------------------
-- Table structure for stop_studies
-- ----------------------------
DROP TABLE IF EXISTS `stop_studies`;
CREATE TABLE `stop_studies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `round` int NOT NULL DEFAULT 0,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `phieu_id` bigint UNSIGNED NULL DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `lop_id` bigint UNSIGNED NULL DEFAULT NULL,
  `files` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_update` tinyint(1) NOT NULL DEFAULT 0,
  `is_pay` tinyint(1) NOT NULL DEFAULT 0,
  `note_pay` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `time_receive` datetime NULL DEFAULT NULL,
  `type_miengiamhp` tinyint(1) NULL DEFAULT NULL,
  `phantramgiam` int NOT NULL DEFAULT 0,
  `tiepnhan` json NULL,
  `ykien` json NULL,
  `lanhdaophong` json NULL,
  `lanhdaotruong` json NULL,
  `muchotrohp` bigint NULL DEFAULT 1080000,
  `muctrocapxh` bigint NULL DEFAULT 0,
  `doi_tuong_chinh_sach` json NULL,
  `che_do_chinh_sach_data` json NULL,
  `diachi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `km` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `stop_studies_student_id_foreign`(`student_id` ASC) USING BTREE,
  INDEX `stop_studies_parent_id_foreign`(`parent_id` ASC) USING BTREE,
  INDEX `stop_studies_phieu_id_foreign`(`phieu_id` ASC) USING BTREE,
  INDEX `stop_studies_lop_id_foreign`(`lop_id` ASC) USING BTREE,
  INDEX `stop_studies_teacher_id_foreign`(`teacher_id` ASC) USING BTREE,
  CONSTRAINT `stop_studies_lop_id_foreign` FOREIGN KEY (`lop_id`) REFERENCES `lops` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `stop_studies_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `stop_studies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `stop_studies_phieu_id_foreign` FOREIGN KEY (`phieu_id`) REFERENCES `phieus` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `stop_studies_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `stop_studies_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of stop_studies
-- ----------------------------

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `student_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `student_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date_of_birth` date NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lop_id` bigint UNSIGNED NULL DEFAULT NULL,
  `school_year` year NULL DEFAULT NULL,
  `he_tuyen_sinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nganh_tuyen_sinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `trinh_do` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ngay_nhap_hoc` date NULL DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cmnd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date_range_cmnd` date NULL DEFAULT NULL,
  `gioitinh` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `chu_ky` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `students_student_code_unique`(`student_code` ASC) USING BTREE,
  INDEX `students_lop_id_foreign`(`lop_id` ASC) USING BTREE,
  CONSTRAINT `students_lop_id_foreign` FOREIGN KEY (`lop_id`) REFERENCES `lops` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2167 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of students
-- ----------------------------

-- ----------------------------
-- Table structure for teachers
-- ----------------------------
DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `khoa_id` bigint UNSIGNED NULL DEFAULT NULL,
  `dia_chi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sdt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `chuc_danh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `chu_ky` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `teachers_khoa_id_foreign`(`khoa_id` ASC) USING BTREE,
  CONSTRAINT `teachers_khoa_id_foreign` FOREIGN KEY (`khoa_id`) REFERENCES `khoas` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 226 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teachers
-- ----------------------------
INSERT INTO `teachers` VALUES (1, 'Nguyễn Đức Tiệp', NULL, '', '911757999', 'nguyenductiep@daihochalong.edu.vn', 'Ban Giám hiệu', '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL);
INSERT INTO `teachers` VALUES (2, 'Hoàng Thị Thu Giang', NULL, '', '989128498', 'hoangthithugiang@daihochalong.edu.vn', 'Ban Giám hiệu', '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL);
INSERT INTO `teachers` VALUES (3, 'Phan Thị Huệ', 2, '', '986132478', 'phanthihue@daihochalong.edu.vn', 'Ban Giám hiệu', '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL);
INSERT INTO `teachers` VALUES (4, 'Trần Trung Vỹ', 4, '', '913508571', 'Trantrungvy@daihochalong.edu.vn', 'Ban Giám hiệu', '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL);
INSERT INTO `teachers` VALUES (5, 'Đặng Hoàng Thông', 9, '', '978857888', 'danghoangthong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL);
INSERT INTO `teachers` VALUES (6, 'Lê Phương Anh', 6, '', '985863555', 'Lephuonganh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL);
INSERT INTO `teachers` VALUES (7, 'Nguyễn Xuân Bách', 1, '', '985863555', 'nguyenxuanbach@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL);
INSERT INTO `teachers` VALUES (8, 'Nguyễn Văn Chính', 3, '', '975797166', 'nguyenvanchinh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (9, 'Mai Xuân Đạt', 8, NULL, '985863555', 'maixuandat@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:30:33', NULL);
INSERT INTO `teachers` VALUES (10, 'Lương Khắc Định', 5, NULL, '968231085', 'luongkhacdinh@daihochalong.edu.vn', 'Trưởng khoa', '2024-07-28 06:00:46', '2024-07-28 06:33:17', NULL);
INSERT INTO `teachers` VALUES (11, 'Lê Minh Đức', 7, '', '985863555', 'leminhduc@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (12, 'Đỗ Thu Huyền', NULL, '', '985863555', 'dothuhuyen@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (13, 'Phạm Thanh Huyền', NULL, '', '985863555', 'phamthanhhuyen@daihochalong.edu.vn', 'Phó trưởng khoa', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (14, 'Cao Thị Bích Liên', NULL, '', '985863555', 'caothibichlien@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (15, 'Nguyễn Quỳnh Nga', NULL, '', '985863555', 'nguyenquynhnga@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (16, 'Lê Hải Thanh', NULL, '', '985863555', 'lehaithanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (17, 'Vũ Thị Anh Trâm', NULL, '', '985863555', 'vuthianhtram@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (18, 'Trịnh Thị Vân', NULL, '', '985863555', 'trinhthivan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (19, 'Phạm Thị Phương Anh', NULL, '', '985863555', 'phamthiphuonganh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (20, 'Lại Văn Đoàn', NULL, '', '985863555', 'laivandoan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (21, 'Nguyễn Minh Đức', NULL, '', '985863555', 'nguyenminhduc@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (22, 'Đặng Việt Hà', NULL, '', '985863555', 'dangvietha@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (23, 'Nguyễn Vân Hà', NULL, '', '985863555', 'nguyenvanha@daihochalong.edu.vn', 'Phó́ tưởng khoa', '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL);
INSERT INTO `teachers` VALUES (24, 'Nguyễn Thị Thu Hằng', NULL, '', '985863555', 'nguyenthithuhang@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (25, 'Hà Thị Hương', NULL, '', '985863555', 'hathihuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (26, 'Nguyễn Thị Thu Huyền', NULL, '', '985863555', 'nguyenthithuhuyen@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (27, 'Hà thị phương lan', NULL, '', '985863555', 'hathiphuonglan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (28, 'Nguyễn Thúy Lan', NULL, '', '985863555', 'nguyenthuylan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (29, 'Nguyễn Thị Mai Linh', NULL, '', '985863555', 'nguyenthimailinh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (30, 'Đinh Thị Phương Loan', NULL, '', '985863555', 'dinhthiphuongloan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (31, 'Hà Kiều My', NULL, '', '985863555', 'hakieumy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (32, 'Nguyễn Hà My', NULL, '', '985863555', 'nguyenhamy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (33, 'Phạm Thị Lan Phượng', NULL, '', '985863555', 'phamthilanphuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (34, 'Phạm Bình Quảng', NULL, '', '985863555', 'phambinhquang@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (35, 'Lê Minh Quyết', NULL, '', '985863555', 'leminhquyet@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (36, 'Phạm Minh Thắng', NULL, '', '985863555', 'phamminhthnag@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (37, 'Nguyễn thị kim thanh', NULL, '', '985863555', 'nguyenthikimthanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (38, 'Trần Thị Phương Thảo', NULL, '', '985863555', 'tranthiphuongthao@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (39, 'Bùi Thu Thủy', NULL, '', '985863555', 'buithuthuy@daihochalong.edu.vn', 'Phó trưởng khoa', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (40, 'Lê Minh Thủy', NULL, '', '985863555', 'leminhthuy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL);
INSERT INTO `teachers` VALUES (41, 'Trần Thu Thủy', NULL, '', '985863555', 'tranthuthuy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (42, 'Phùng Thị Vân Trang', NULL, '', '985863555', 'phungthivantrang@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (43, 'Vũ Thu Trang', NULL, '', '985863555', 'vuthutrang@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (44, 'Phạm Xuân Tùng', NULL, '', '985863555', 'phamxuantung@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (45, 'Nguyễn Vân Thịnh', NULL, '', '985863555', 'nguyenvanthinh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (46, 'Vũ Văn Viện', NULL, '', '985863555', 'vuvanvien@daihochalong.edu.vn', 'Trưởng khoa', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (47, 'Nguyễn Thế Anh', NULL, '', '985863555', 'nguyentheanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (48, 'Nguyễn Văn Anh', NULL, '', '985863555', 'nguyenvanan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (49, 'Nguyễn Thị Chính', NULL, '', '985863555', 'nguyenthichinh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (50, 'Vũ Thị Hồng Định', NULL, '', '985863555', 'vuthihongdinh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (51, 'Hoàng Thị Thanh Hà', NULL, '', '985863555', 'hoangthithanhha@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (52, 'Bùi Thị Thúy Hằng', NULL, '', '985863555', 'buithithuyhang@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (53, 'Võ Thị Thu Hằng', NULL, '', '985863555', 'vothithuhang@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (54, 'Nguyễn Thị Thanh Hoa', NULL, '', '985863555', 'nguyenthithanhhoa@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (55, 'Phạm văn hoàng', NULL, '', '985863555', 'phamvanhoang@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (56, 'Bùi Thị Minh Huệ', NULL, '', '985863555', 'buithiminhhue@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL);
INSERT INTO `teachers` VALUES (57, 'Bùi Văn Lợi', NULL, '', '985863555', 'buivanloi@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (58, 'Nguyễn Văn Mạnh', NULL, '', '985863555', 'nguyenvanmanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (59, 'Vũ Thị Minh Nguyệt', NULL, '', '985863555', 'vuthinhunguyet@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (60, 'Phùng Đức Nhật', NULL, '', '985863555', 'phungducnhat@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (61, 'Nguyễn Lâm Sung', NULL, '', '985863555', 'nguyenlamsung@daihochalong.edu.vn', 'Trưởng khoa', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (62, 'Đoàn Thị Tâm', NULL, '', '985863555', 'doanthitam@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (63, 'Vũ Thị Thanh Thanh', NULL, '', '985863555', 'vuthithanhthanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (64, 'Nguyễn Thu Thủy', NULL, '', '985863555', 'nguyenthuthuy@daihochalong.edu.vn', 'Phó trưởng khoa', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (65, 'Hoàng Thị Bích Hồng', NULL, '', '985863555', 'hoangthibichhong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (66, 'Nguyễn Thị Khiên', NULL, '', '985863555', 'nguyenthikhien@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (67, 'Đinh Quỳnh Oanh', NULL, '', '985863555', 'dinhquynhanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (68, 'Nguyễn Thị Thắm', NULL, '', '985863555', 'nguyenthitham@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (69, 'Diệp Thị Thu Thủy', NULL, '', '985863555', 'diepthithuthuy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (70, 'Chu Lương Trí', NULL, '', '985863555', 'chuluongtri@daihochalong.edu.vn', 'Trưởng khoa', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (71, 'Vũ Tô Sa Anh', NULL, '', '985863555', 'vutosaanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (72, 'Nguyễn Thuỳ Dương', NULL, '', '985863555', 'nguyenthuyduong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (73, 'Nguyễn Ngọc Hải', NULL, '', '985863555', 'nguyenngochai@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL);
INSERT INTO `teachers` VALUES (74, 'Ngô Thị Hiệp', NULL, '', '985863555', 'ngothihiep@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (75, 'Nguyễn Viết Hoàn', NULL, '', '985863555', 'nguyenviethoan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (76, 'Vũ Mạnh Hùng', NULL, '', '985863555', 'vumanhhung@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (77, 'Đặng Kiều Hưng', NULL, '', '985863555', 'dangkieuhung@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (78, 'Phạm Quang Huy', NULL, '', '985863555', 'phamquanghuy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (79, 'Nguyễn Thị Huyền', NULL, '', '985863555', 'nguyenthihuyen@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (80, 'Nguyễn Thị Thanh Huyền(NT)', NULL, '', '985863555', 'nguyenthithanhhuyennt@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (81, 'Bùi Thế Khương', NULL, '', '985863555', 'buithekhuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (82, 'Trần Vũ Lâm', NULL, '', '985863555', 'tranvulam@daihochalong.edu.vn', 'Trưởng khoa', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (83, 'Nguyễn Thị Loan', NULL, '', '985863555', 'nguyenthiloan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (84, 'Trần Đức Mạnh', NULL, '', '985863555', 'tranducmanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (85, 'Đào Thị Thanh Ngân', NULL, '', '985863555', 'daothithanhngan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (86, 'Trần Đức Nhâm', NULL, '', '985863555', 'tranducnham@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (87, 'Trịnh Thị Kim Oanh', NULL, '', '985863555', 'trinhthikimoanh@daihochalong.edu.vn', 'Phó trưởng khoa', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (88, 'Hoàng May Quý', NULL, '', '985863555', 'hoangmayquy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (89, 'Nguyễn Bá Quyền', NULL, '', '985863555', 'nguyenbaquyen@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (90, 'Hoàng Văn Thành', NULL, '', '985863555', 'hoangvanthanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL);
INSERT INTO `teachers` VALUES (91, 'Nguyễn Thị Thiền', NULL, '', '985863555', 'nguyenthithien@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (92, 'Đinh Thị Khánh Thơ', NULL, '', '985863555', 'dinhthikhanhtho@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (93, 'Lê Thị Thu', NULL, '', '985863555', 'lethithu@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (94, 'Đoàn Thanh Thủy', NULL, '', '985863555', 'doanthanhthuy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (95, 'Nguyễn Ngọc Thủy', NULL, '', '985863555', 'nguyenngocthuy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (96, 'Trần Thanh Thủy', NULL, '', '985863555', 'tranthanhthuy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (97, 'Trần Đức Toàn', NULL, '', '985863555', 'tranductoan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (98, 'Chu Thu Trang', NULL, '', '985863555', 'chuthutrang@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (99, 'Lê nhật trường', NULL, '', '985863555', 'lenhattruong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (100, 'Trần Anh Tuấn', NULL, '', '985863555', 'trananhtuan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (101, 'Hoàng Thị Yến(NT)', NULL, '', '985863555', 'hoangthiyennt@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (102, 'Hoàng Thị Hải Anh', NULL, '', '985863555', 'hoangthihaianh@daihochalong.edu.vn', 'Phó trưởng khoa', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (103, 'Nguyễn Vân Anh', NULL, '', '985863555', 'nguyenvananh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (104, 'Thân Thị Mỹ Bình', NULL, '', '985863555', 'thanthimybinh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (105, 'TRẦN THỊ CHUNG', NULL, '', '985863555', 'tranthichung@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (106, 'Bùi Thị Bích Diệp', NULL, '', '985863555', 'buibichdiep@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (107, 'Trần Thị Giang', NULL, '', '985863555', 'tranthigiang@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL);
INSERT INTO `teachers` VALUES (108, 'Hoàng Thị Thu Hà', NULL, '', '985863555', 'hoangthithuha@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (109, 'Vũ Phương Hà', NULL, '', '985863555', 'vuphuongha@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (110, 'Nguyễn Thị Hằng', NULL, '', '985863555', 'nguyenthihang@daihochalong.edu.vn', 'Trưởng khoa', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (111, 'Hoàng Thị Hạnh', NULL, '', '985863555', 'hoangthihanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (112, 'Nguyễn Thị Hảo', NULL, '', '985863555', 'nguyenthihao@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (113, 'Tạ Thị Hoa', NULL, '', '985863555', 'tathihoa@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (114, 'Vũ Thanh Hòa(NN)', NULL, '', '985863555', 'vuthanhhoann@daihochalong.edu.vn', 'Phó trưởng khoa', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (115, 'Nguyễn Thị Hoàn', NULL, '', '985863555', 'nguyenthihoan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (116, 'Nguyễn Tâm Hồng', NULL, '', '985863555', 'nguyentamhong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (117, 'Nguyễn Thị Thu Hương', NULL, '', '985863555', 'nguyenthithuhuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (118, 'Bùi Thị Huyền', NULL, '', '985863555', 'buithihuyen@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (119, 'Nguyễn Thị Thanh Huyền(NN)', NULL, '', '985863555', 'nguyenthithanhhuyennn@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (120, 'Vương Thị Bích Liên', NULL, '', '985863555', 'vuongthibichlien@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (121, 'Nguyễn Diệp Linh', NULL, '', '985863555', 'nguyendieplinh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (122, 'Phạm Thị Linh', NULL, '', '985863555', 'phamthilinh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (123, 'Lê Nguyệt Minh', NULL, '', '985863555', 'lenguyetminh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (124, 'Vương Thị Kim Minh', NULL, '', '985863555', 'vuongthikimminh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL);
INSERT INTO `teachers` VALUES (125, 'Hoàng Phương Nam', NULL, '', '985863555', 'hoangphuongnam@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (126, 'Nguyễn Thị Thanh Ngân', NULL, '', '985863555', 'nguyenthithanhngan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (127, 'VŨ VĂN NGÂN', NULL, '', '985863555', 'vuvanngan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (128, 'Nguyễn Thúy Ngọc', NULL, '', '985863555', 'nguyenthuyngoc@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (129, 'Bùi Như Nguyệt', NULL, '', '985863555', 'buinhunguyet@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (130, 'Bùi Bích Phương', NULL, '', '985863555', 'buibichphuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (131, 'Bùi Trí Quân', NULL, '', '985863555', 'buitriquan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (132, 'Lê Tuấn Sơn', NULL, '', '985863555', 'letuanson@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (133, 'Đỗ Thị Yến Thoa', NULL, '', '985863555', 'dothiyenthoa@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (134, 'Nguyễn Thị Thanh Thủy', NULL, '', '985863555', 'nguyenthithanhthuy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (135, 'Đỗ Thị Hương Trà', NULL, '', '985863555', 'dothihuongtra@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (136, 'Nguyễn Đức Tú', NULL, '', '985863555', 'nguyenductu@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (137, 'Ngô Thị Ánh Tuyết', NULL, '', '985863555', 'ngothianhtuyet@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (138, 'Vương Thị Hồng Vân', NULL, '', '985863555', 'vuongthihongvan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (139, 'Đỗ Thị Xuân', NULL, '', '985863555', 'dothixuan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (140, 'Đoàn Thị Hải Yến', NULL, '', '985863555', 'doanthihaiyen@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL);
INSERT INTO `teachers` VALUES (141, 'Bùi Văn Chương', NULL, '', '985863555', 'buivanchuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (142, 'Nguyễn Thị Điệp', NULL, '', '985863555', 'nguyenthidiep@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (143, 'Nguyễn Thị Gấm', NULL, '', '985863555', 'nguyenthigam@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (144, 'Đặng Thị Thu Hiền(SP)', NULL, '', '985863555', 'dangthithuhiensp@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (145, 'Trần Thị Hòa', NULL, '', '985863555', 'tranthihoa@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (146, 'Vũ Thanh Hòa(SP)', NULL, '', '985863555', 'vuthanhhoasp@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (147, 'Nguyễn Minh Huệ', NULL, '', '985863555', 'nguyenminhhue@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (148, 'Bùi Thị Lan Hương', NULL, '', '985863555', 'buithilanhuong@daihochalong.edu.vn', 'Phó trưởng khoa', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (149, 'Nguyễn Thị Hương', NULL, '', '985863555', 'nguyenthihuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (150, 'Bế Thị Thu Huyền', NULL, '', '985863555', 'bethithuhuyen@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (151, 'Phạm Trung Kiên', NULL, '', '985863555', 'phamtrungkien@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (152, 'Nguyễn Thị Quý Kim', NULL, '', '985863555', 'nguyenthiquyskim@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (153, 'Trần Thị Kim Loan', NULL, '', '985863555', 'tranthikimloan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (154, 'Nguyễn Thị Ngọc Lương', NULL, '', '985863555', 'nguyenthingocluong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (155, 'Phạm Thị Minh Lương', NULL, '', '985863555', 'phamthiminhluong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (156, 'Liễu Quỳnh Như', NULL, '', '985863555', 'lieuquynhnhu@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (157, 'Nguyễn Ngọc Quỳnh', NULL, '', '985863555', 'nguyenngocquynh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL);
INSERT INTO `teachers` VALUES (158, 'Đặng Quang Rinh', NULL, '', '985863555', 'dangquangrinh@daihochalong.edu.vn', 'Phó trưởng khoa', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (159, 'Nguyễn Thị Minh Thái', NULL, '', '985863555', 'nguyenthiminhthai@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (160, 'Nguyễn Thị Thương', NULL, '', '985863555', 'nguyenthithuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (161, 'Nguyễn Hữu Tới', NULL, '', '985863555', 'nguyenhuutoi@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (162, 'Vũ Vương Trưởng', NULL, '', '985863555', 'vuvuongtruowng@daihochalong.edu.vn', 'Trưởng khoa', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (163, 'Nguyễn Hoàng Vân', NULL, '', '985863555', 'nguyenhoangvan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (164, 'Hà Ngọc Yến', NULL, '', '985863555', 'hangocyen@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (165, 'Ngô Thị Hoản', NULL, '', '985863555', 'ngothihoan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (166, 'Hoàng Văn Hùng', NULL, '', '985863555', 'hoangvanhung@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (167, 'Vũ Thị Thanh Hương', NULL, '', '985863555', 'vuthithanhhuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (168, 'Lê Thị Như Phương', NULL, '', '985863555', 'lethinhuphuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (169, 'Nguyễn Hữu Tích', NULL, '', '985863555', 'nguyenhuutich@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (170, 'Nguyễn Duy Cường', NULL, '', '985863555', 'nguyenduycuong@daihochalong.edu.vn', 'Phó trưởng khoa', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (171, 'Nguyễn Thị Thuỳ Dương', NULL, '', '985863555', 'nguyenthithuyduong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (172, 'Hạp Thu Hà', NULL, '', '985863555', 'hapthuha@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (173, 'Lê Thanh Hoa', NULL, '', '985863555', 'lethanhhoa@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL);
INSERT INTO `teachers` VALUES (174, 'Lưu Thị Thanh Hòa', NULL, '', '985863555', 'luuthithanhhoa@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (175, 'hoàng tuyết mai', NULL, '', '985863555', 'hoangtuyetmai@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (176, 'Ngô Hải Ninh', NULL, '', '985863555', 'ngohaininh@daihochalong.edu.vn', 'Trưởng khoa', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (177, 'Cao Thị Thường', NULL, '', '985863555', 'caothithuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (178, 'Nguyễn Quốc Tuấn', NULL, '', '985863555', 'nguyenquoctuan@daihochalong.edu.vn', 'Trưởng phòng', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (179, 'Đào Thị Vương', NULL, '', '985863555', 'daothivuong@daihochalong.edu.vn', 'Phó phòng ', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (180, 'Nguyễn Thị Thủy', NULL, '', '985863555', 'nguyenthithuy@daihochalong.edu.vn', 'Phó phòng ', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (181, 'Nguyễn Thị Thu Hiền', NULL, '', '985863555', 'nguyenthithuhien@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (182, 'Nguyễn Doãn Hùng', NULL, '', '985863555', 'nguyendoanhung@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (183, 'Ty Văn Quỳnh', NULL, '', '985863555', 'tyvanquynh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (184, 'Lê Anh Tú', NULL, '', '985863555', 'leanhtu@daihochalong.edu.vn', 'Trưởng phòng', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (185, 'Hoàng Văn Vinh', NULL, '', '985863555', 'hoangvanvinh@daihochalong.edu.vn', 'Phó phòng ', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (186, 'Phạm Quý Giang', NULL, '', '985863555', 'phamquygiang@daihochalong.edu.vn', 'Trưở̉ng phòng', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (187, 'TÔ THỊ THÁI HÀ', NULL, '', '985863555', 'tothithaiha@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (188, 'Đặng Trần Hùng', NULL, '', '985863555', 'dangtranhung@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (189, 'Vũ Thị Thu Hương', NULL, '', '985863555', 'vuthithuhuong@daihochalong.edu.vn', 'Phó phòng ', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (190, 'Trần Thị Thu Trang', NULL, '', '985863555', 'tranthithutrang@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL);
INSERT INTO `teachers` VALUES (191, 'Lại Thế Sơn', NULL, '', '985863555', 'laitheson@daihochalong.edu.vn', 'Phó phòng ', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (192, 'Văn Trọng Hùng', NULL, '', '985863555', 'vantronghung@daihochalong.edu.vn', 'Trưởng phòng', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (193, 'Đỗ Thị Lan', NULL, '', '985863555', 'dothilan@daihochalong.edu.vn', 'Kế toán trưởng', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (194, 'Vũ Thị Doan', NULL, '', '985863555', 'vuthidoan@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (195, 'Nguyễn Thị Hồng Hải', NULL, '', '985863555', 'nguyenthihoghai@daihochalong.edu.vn', 'Phó phòng ', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (196, 'Phạm Thị Tuyết Hạnh', NULL, '', '985863555', 'phamthituyethanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (197, 'Nguyễn Mai Hùng', NULL, '', '985863555', 'nguyenmaihung@daihochalong.edu.vn', 'Trưởng phòng', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (198, 'Dương Thị Hồng Nhung', NULL, '', '985863555', 'duongthihongnhung@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (199, 'VŨ HẰNG THƯ', NULL, '', '985863555', 'vuhangthu@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (200, 'Hoàng Thị Yến(KT)', NULL, '', '985863555', 'hoangthiyenkt@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (201, 'Vũ Thị Thu Hà', NULL, '', '985863555', 'vuthithuha@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (202, 'Nguyễn Văn Lâm', NULL, '', '985863555', 'nguyenvanlam@daihochalong.edu.vn', 'Phó phò̀ng', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (203, 'Nguyễn Thị Ngọc Lan', NULL, '', '985863555', 'nguyenthingoclan@daihochalong.edu.vn', 'Trưởng phòng', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (204, 'Bùi Thị Hằng Nga', NULL, '', '985863555', 'buithihangnga@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (205, 'Nguyễn Thị Ngọc Oanh', NULL, '', '985863555', 'nguyenthingocoanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (206, 'Đồng Thị Quyên', NULL, '', '985863555', 'dongthiquyen@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (207, 'Nguyễn Thị Mai Ly', NULL, '', '985863555', 'nguyenthimaily@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL);
INSERT INTO `teachers` VALUES (208, 'Nguyễn Văn Quang', NULL, '', '985863555', 'nguyenvanquang@daihochalong.edu.vn', 'Phó GĐ Trung tâm', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (209, 'Vũ Công Tâm', NULL, '', '985863555', 'vucongtam@daihochalong.edu.vn', 'Giám đốc Trung tâm', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (210, 'Đinh Thị Tuyết', NULL, '', '985863555', 'dinhthituyet@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (211, 'Vũ Thị Bích Thảo', NULL, '', '985863555', 'vuthibichthao@daihochalong.edu.vn', 'Giám đốc Trung tâm', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (212, 'Nguyễn Thị Trang', NULL, '', '985863555', 'nguyenthitrang@daihochalong.edu.vn', 'Phó GĐ Trung tâm', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (213, 'Nguyễn Chí Đông', NULL, '', '985863555', 'nguyenchidong@daihochalong.edu.vn', 'Phó GĐ Trung tâm', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (214, 'Nguyễn Trung Dũng', NULL, '', '985863555', 'nguyentrungdung@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (215, 'Lê Mạnh Hà', NULL, '', '985863555', 'lemanhha@daihochalong.edu.vn', 'Giám đốc Trung tâm', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (216, 'Giáp Lương Thụy', NULL, '', '985863555', 'giangluongthuy@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (217, 'Phạm Huy Anh', NULL, '', '985863555', 'phamhuyanh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (218, 'Bùi Thu Hiền', NULL, '', '985863555', 'buithuhien@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (219, 'Đặng Thị Thu Hiền', NULL, '', '985863555', 'dangthithuhien@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (220, 'Trần Thị Minh Ngọc', NULL, '', '985863555', 'tranthiminhngoc@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (221, 'Nguyễn Minh Phong', NULL, '', '985863555', 'nguyenminhphong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (222, 'Nguyễn Thị Lệ Quyên', NULL, '', '985863555', 'nguyenthilequyen@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (223, 'Hoàng Thị Thương', NULL, '', '985863555', 'hoangthithuong@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (224, 'Nguyễn Thị Nhung', NULL, '', '985863555', 'nguyenthinhung@daihochalong.edu.vn', 'Hiệu trưởng PT', '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL);
INSERT INTO `teachers` VALUES (225, 'Vũ Tiến Tình', NULL, '', '985863555', 'vutientinh@daihochalong.edu.vn', 'Giảng viên', '2024-07-28 06:00:59', '2024-07-28 06:00:59', NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_username_unique`(`username` ASC) USING BTREE,
  INDEX `users_student_id_foreign`(`student_id` ASC) USING BTREE,
  INDEX `users_teacher_id_foreign`(`teacher_id` ASC) USING BTREE,
  CONSTRAINT `users_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `users_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2392 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Admin', 'admin', NULL, '$2y$10$knM0rTKnigxU..OsoNzdWuzmla.aeImwOuO.flVNX1OT4WYB0ZSre', '0', NULL, NULL, '2024-07-28 06:00:31', '2024-07-28 06:00:31', NULL, NULL);
INSERT INTO `users` VALUES (2, 'Nguyễn Đức Tiệp', 'nguyenductiep', NULL, '$2y$10$o1CNDy.f175Mvd6TTpQwF.9WDFunjEuPLMtRoacUvyJ.LJ0ifc/OS', '7', NULL, NULL, '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL, 1);
INSERT INTO `users` VALUES (3, 'Hoàng Thị Thu Giang', 'hoangthithugiang', NULL, '$2y$10$COImZhlnoVHrzopwvxq9XuaHcwxyzIfjck20hdRe4wmbnBwrazBbK', '7', NULL, NULL, '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL, 2);
INSERT INTO `users` VALUES (4, 'Phan Thị Huệ', 'phanthihue', NULL, '$2y$10$aaFLzbp333u9632yC9imhuD07W7uSgSKbd51.43MaXsq/.8mcIrXK', '7', NULL, NULL, '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL, 3);
INSERT INTO `users` VALUES (5, 'Trần Trung Vỹ', 'Trantrungvy', NULL, '$2y$10$/La2MNlwsB0z6G8fuJk/NOoRTN5MAhVtFipDVkn5wy2b3F7pzcy2a', '7', NULL, NULL, '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL, 4);
INSERT INTO `users` VALUES (6, 'Đặng Hoàng Thông', 'danghoangthong', NULL, '$2y$10$dk6GS8oIltF88XANhCP2..qpXWbzmxezTYz6uyetcXaBgushdaqQG', '2', NULL, NULL, '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL, 5);
INSERT INTO `users` VALUES (7, 'Lê Phương Anh', 'Lephuonganh', NULL, '$2y$10$YmRvU2ZhMroKNXiVAfRx0OIk1X6scOEjgKxzPFR5pbE4pZbBvEwC2', '2', NULL, NULL, '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL, 6);
INSERT INTO `users` VALUES (8, 'Nguyễn Xuân Bách', 'nguyenxuanbach', NULL, '$2y$10$YD7s57GWE3ZOzudcFvmBCewE630zkeHIS1socnLma9habjpTl5o/W', '2', NULL, NULL, '2024-07-28 06:00:45', '2024-07-28 06:00:45', NULL, 7);
INSERT INTO `users` VALUES (9, 'Nguyễn Văn Chính', 'nguyenvanchinh', NULL, '$2y$10$4BQAKeGfEaAspesk.bVkVeWmaKlVNKNmxlwVowsSIn7KEan8qUY5y', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 8);
INSERT INTO `users` VALUES (10, 'Mai Xuân Đạt', 'maixuandat', NULL, '$2y$10$mBunzfWy7zA27lpFsZg9wOO00XRkaVGFxL1LSg6OizNojQWIwUsJO', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:38:18', NULL, 9);
INSERT INTO `users` VALUES (11, 'Lương Khắc Định', 'luongkhacdinh', NULL, '$2y$10$Bv.pQUFUcrtqA8pzSYZ.MeO3VsQ9XkjWZQiAUtPbZcZFFLtYjJbp6', '3', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:33:27', NULL, 10);
INSERT INTO `users` VALUES (12, 'Lê Minh Đức', 'leminhduc', NULL, '$2y$10$qAKf4k5L0aN4cAK9qGeHOuEmIDnvQrHpnZy.eHtzgVOBZweABDRv.', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 11);
INSERT INTO `users` VALUES (13, 'Đỗ Thu Huyền', 'dothuhuyen', NULL, '$2y$10$1Nt9PBQraqagevZNuQJ1Bu5cuETMDoyOfc7oTLcpPezDS/.kybqky', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 12);
INSERT INTO `users` VALUES (14, 'Phạm Thanh Huyền', 'phamthanhhuyen', NULL, '$2y$10$W.MoSdIE3DGeRaclP3s5CeoAv.CLLojG4pE.vLVj6oMCmzRPnQyfq', '3', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 13);
INSERT INTO `users` VALUES (15, 'Cao Thị Bích Liên', 'caothibichlien', NULL, '$2y$10$GOSs2mlf9QNeGxA.pZ.xA.R9YtHQ6a2vI3Z2Sc0pKxXuQmrnn.8jC', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 14);
INSERT INTO `users` VALUES (16, 'Nguyễn Quỳnh Nga', 'nguyenquynhnga', NULL, '$2y$10$Mv9CirzRv0FRQEugKk7KgOA9SALJCOxRJGAiiagdYdAZwxyCny7ai', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 15);
INSERT INTO `users` VALUES (17, 'Lê Hải Thanh', 'lehaithanh', NULL, '$2y$10$xMxKygWvXtIEiGnLzfrK5udaVVFijIVvDJs2xps/cfiz3KPZ02n8S', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 16);
INSERT INTO `users` VALUES (18, 'Vũ Thị Anh Trâm', 'vuthianhtram', NULL, '$2y$10$dGKQN0ToFA5HR7hzT8slgO6f4J321pyaS.q0iUwNLzE3lesEbAqF.', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 17);
INSERT INTO `users` VALUES (19, 'Trịnh Thị Vân', 'trinhthivan', NULL, '$2y$10$RbVjM/yrC87uVb4bgJRsvuFNV69RAArSLpxBgPO/gGCvJC0F35aqa', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 18);
INSERT INTO `users` VALUES (20, 'Phạm Thị Phương Anh', 'phamthiphuonganh', NULL, '$2y$10$35y1lScw0k7F/e.Cl6BNievG5OKMKgjjr.eWepQp7a..GnOxGysVq', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 19);
INSERT INTO `users` VALUES (21, 'Lại Văn Đoàn', 'laivandoan', NULL, '$2y$10$OUKQRhDlrqps6pHAJLoIFu4PH1CxmeqqcKrfOIWptRcgXnftrZqP2', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 20);
INSERT INTO `users` VALUES (22, 'Nguyễn Minh Đức', 'nguyenminhduc', NULL, '$2y$10$PiiYTgxyFS5wWUfNKqPh.eAHRaRl1fmdocpSk6nQoRv5UIhtu0v/m', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 21);
INSERT INTO `users` VALUES (23, 'Đặng Việt Hà', 'dangvietha', NULL, '$2y$10$zvqODhySb2Ht0ueHjtCuCuhDpE3IwnCrwscUF3gyTdNTuiAkLq1qS', '2', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 22);
INSERT INTO `users` VALUES (24, 'Nguyễn Vân Hà', 'nguyenvanha', NULL, '$2y$10$DBYz8eFqA/zxVQ2Dnqf2levwAoX9TW/UW2CTBbYHqEFVETVEhdE5a', '3', NULL, NULL, '2024-07-28 06:00:46', '2024-07-28 06:00:46', NULL, 23);
INSERT INTO `users` VALUES (25, 'Nguyễn Thị Thu Hằng', 'nguyenthithuhang', NULL, '$2y$10$QFS5p0v7qCi.telOhi7Se.EOGcvxJm6KPPWh.lIEFMcT2clBqvP6u', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 24);
INSERT INTO `users` VALUES (26, 'Hà Thị Hương', 'hathihuong', NULL, '$2y$10$mhatjkUo9mn5NZjewJlAqugYJSu3kizY4C9adAUIxeYfmpf1A7pK2', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 25);
INSERT INTO `users` VALUES (27, 'Nguyễn Thị Thu Huyền', 'nguyenthithuhuyen', NULL, '$2y$10$QTYAPy8hFUBGDPpc47Tn7OL7hpMNtTMyHCEbpDhc1ObzjIVw7Cag.', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 26);
INSERT INTO `users` VALUES (28, 'Hà thị phương lan', 'hathiphuonglan', NULL, '$2y$10$cy0MtmVzyHgDHXW0XgUf5utts.t7UkaGvFM041jUsVf6IL/UB/hwO', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 27);
INSERT INTO `users` VALUES (29, 'Nguyễn Thúy Lan', 'nguyenthuylan', NULL, '$2y$10$RgxBqYI.AYyyuvai9fRMtOjyhslrQHucWI8JKc8Cf0ID8C3Oywq4G', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 28);
INSERT INTO `users` VALUES (30, 'Nguyễn Thị Mai Linh', 'nguyenthimailinh', NULL, '$2y$10$7fuNlDOaGBkK7nrevELIweW1X.S1xwI3sf8CRod65ulZHbNyeYEEu', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 29);
INSERT INTO `users` VALUES (31, 'Đinh Thị Phương Loan', 'dinhthiphuongloan', NULL, '$2y$10$HWmEnBFdWosYEKQAhfITZ.VPRU9ki44qbF2KkVT8ed631fnKPS/x6', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 30);
INSERT INTO `users` VALUES (32, 'Hà Kiều My', 'hakieumy', NULL, '$2y$10$Y/a9IiEwHB.xmdTmXwIgiOMJiQqMiAQEW9WJ8qpYRu9/qTMwy5sDO', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 31);
INSERT INTO `users` VALUES (33, 'Nguyễn Hà My', 'nguyenhamy', NULL, '$2y$10$J32Crot.WmBE1oopK1rMh.BHWzz9N/WKnj6jIJVQOalJfdev9zzPm', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 32);
INSERT INTO `users` VALUES (34, 'Phạm Thị Lan Phượng', 'phamthilanphuong', NULL, '$2y$10$KXXsTs..k1zCj9u4ONI5R.21B8w1Jafljw69mjtUX33Cm/hGQ9Gdi', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 33);
INSERT INTO `users` VALUES (35, 'Phạm Bình Quảng', 'phambinhquang', NULL, '$2y$10$1OPHrxTBHNrPRxkGeCdPV.zm7AWQKOqNeGvz.JU3Y7kUJSo.xHpea', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 34);
INSERT INTO `users` VALUES (36, 'Lê Minh Quyết', 'leminhquyet', NULL, '$2y$10$scF66/iiroX8YXoSb/MaVOGIgKdtmV/V.JdyWAPR4Yk9s8bExABWG', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 35);
INSERT INTO `users` VALUES (37, 'Phạm Minh Thắng', 'phamminhthnag', NULL, '$2y$10$j0zvIiOLSr3CqkNlWn8dMeT4ty/yy.mr6hpu/siw58sTG02H9LbJK', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 36);
INSERT INTO `users` VALUES (38, 'Nguyễn thị kim thanh', 'nguyenthikimthanh', NULL, '$2y$10$/xzDUYcYNz4z.4XozyBvhOo3RglsQUKD/xCtwC0JWVLw4Cz3sK9X.', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 37);
INSERT INTO `users` VALUES (39, 'Trần Thị Phương Thảo', 'tranthiphuongthao', NULL, '$2y$10$KCJn6VWBLilQfEMXSHIDO.2D7oxpouw3Yuw5m2eBl0mGFkBwcloWK', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 38);
INSERT INTO `users` VALUES (40, 'Bùi Thu Thủy', 'buithuthuy', NULL, '$2y$10$6W5q3.mSCzn/vn54ACXw6Ocs3zHAlRtBh6fuRVqK5G3abYYP6xWHq', '3', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 39);
INSERT INTO `users` VALUES (41, 'Lê Minh Thủy', 'leminhthuy', NULL, '$2y$10$DWYOmV3Qm8zlDox8VCE9yuF6rSQYNIuD3SLGVW5gqf.5CUFIw2CBe', '2', NULL, NULL, '2024-07-28 06:00:47', '2024-07-28 06:00:47', NULL, 40);
INSERT INTO `users` VALUES (42, 'Trần Thu Thủy', 'tranthuthuy', NULL, '$2y$10$YDKtDYwb2qOUn/H1dc8qLe9t8vo3fNLdL.REkFgRsi6x9uziVZPKq', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 41);
INSERT INTO `users` VALUES (43, 'Phùng Thị Vân Trang', 'phungthivantrang', NULL, '$2y$10$mYw0sofqe7EixLS6i9J9nuanhc2ekAe54reLXIpOozGgVXwkEPCC2', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 42);
INSERT INTO `users` VALUES (44, 'Vũ Thu Trang', 'vuthutrang', NULL, '$2y$10$PfyAiNP45a5gtzMARO.Ybu9aCPXZETFIiPTb9.BsqNsbgvSE5OnPO', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 43);
INSERT INTO `users` VALUES (45, 'Phạm Xuân Tùng', 'phamxuantung', NULL, '$2y$10$90L/T4HsbGNE2BNIsLcuN.vKb5n7tTbsBDP5mI.OLJhNwO7bA2i.e', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 44);
INSERT INTO `users` VALUES (46, 'Nguyễn Vân Thịnh', 'nguyenvanthinh', NULL, '$2y$10$eV1V5QYv19YlL5xG/004/uz67by1m7QTzI8hrFtBLPNqlj3fif9Wy', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 45);
INSERT INTO `users` VALUES (47, 'Vũ Văn Viện', 'vuvanvien', NULL, '$2y$10$Pj/Lj3ArZ.zg.MC/lfYDNOZFMF7JSi.J9Q6t3sPJ.RQ7xuYq.QFPm', '3', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 46);
INSERT INTO `users` VALUES (48, 'Nguyễn Thế Anh', 'nguyentheanh', NULL, '$2y$10$O5JZ9CatCvdWE8ejm/xfpe4xoKEBWTX2kAI6auX8YBdVP41iSkN5G', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 47);
INSERT INTO `users` VALUES (49, 'Nguyễn Văn Anh', 'nguyenvanan', NULL, '$2y$10$Gl.YifWv5/YfiZTBhh5dhuGrvNfled4rSurpExaYoSCWgyfV1X/pS', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 48);
INSERT INTO `users` VALUES (50, 'Nguyễn Thị Chính', 'nguyenthichinh', NULL, '$2y$10$IehYjVAM60acs26uJVtgE.ECePJ31rBEQuFJaSPbNkuYJoVcBmjA.', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 49);
INSERT INTO `users` VALUES (51, 'Vũ Thị Hồng Định', 'vuthihongdinh', NULL, '$2y$10$N6gm3M9kKkdvwAmSKZIYXueNcutiVGfN9O8ZAjH5R3SqXwyE5NQGa', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 50);
INSERT INTO `users` VALUES (52, 'Hoàng Thị Thanh Hà', 'hoangthithanhha', NULL, '$2y$10$SMYAwSMoMqtjWTWNWRFFeuj7FnTSXsYBcK9bFnxXFJ4b/q1T3o6Em', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 51);
INSERT INTO `users` VALUES (53, 'Bùi Thị Thúy Hằng', 'buithithuyhang', NULL, '$2y$10$K.eG3vRBWQLaU9ixeiKCB.VXmnJBP7Zw9tXwfGoxpneO95EiWciaK', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 52);
INSERT INTO `users` VALUES (54, 'Võ Thị Thu Hằng', 'vothithuhang', NULL, '$2y$10$r0vUcoqV3FaGlY2b/calZu55BpGtdDOUrFWYTkS2z0VDPYgTpmyKy', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 53);
INSERT INTO `users` VALUES (55, 'Nguyễn Thị Thanh Hoa', 'nguyenthithanhhoa', NULL, '$2y$10$/bElpnfNiXpntNb1HPr.L.zKxiETDq8dAJQAErQxtUfQeAaM69hWm', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 54);
INSERT INTO `users` VALUES (56, 'Phạm văn hoàng', 'phamvanhoang', NULL, '$2y$10$PShM7eMSMB/5.rX6SRcZoe/Lvtyuby9ZWM6d6yyD8WewX6xBaPAt.', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 55);
INSERT INTO `users` VALUES (57, 'Bùi Thị Minh Huệ', 'buithiminhhue', NULL, '$2y$10$PZstefVPvyGxlWjVRvNkXOEXEXSj2DST/sw5X0IxtqT/LLJlEYgCq', '2', NULL, NULL, '2024-07-28 06:00:48', '2024-07-28 06:00:48', NULL, 56);
INSERT INTO `users` VALUES (58, 'Bùi Văn Lợi', 'buivanloi', NULL, '$2y$10$dnfwERdlC3xixzli//yYmen9ae5zuhM.nnWEvuyO7yQN6WKgI6uXa', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 57);
INSERT INTO `users` VALUES (59, 'Nguyễn Văn Mạnh', 'nguyenvanmanh', NULL, '$2y$10$WT/aSD/Sro/smi.u2aAuXu0fkyGgQ8xOl3CKKtL9NqQyzHSDTxlwS', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 58);
INSERT INTO `users` VALUES (60, 'Vũ Thị Minh Nguyệt', 'vuthinhunguyet', NULL, '$2y$10$neQTnaFTHK3jQ7FkiATxqeN5276ZNM.RAT9Rj5HaiUpcZy9gWggLC', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 59);
INSERT INTO `users` VALUES (61, 'Phùng Đức Nhật', 'phungducnhat', NULL, '$2y$10$eP5iR5yl4JTCdS3auHvuveshJEpFRTd3uQLxIu4NQsgjHdMSN4Lwm', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 60);
INSERT INTO `users` VALUES (62, 'Nguyễn Lâm Sung', 'nguyenlamsung', NULL, '$2y$10$aUA3mkChtkVW84rJF/H38ekthlVnpjVtc6ZwyFUE.RQEodB3nZ9IG', '3', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 61);
INSERT INTO `users` VALUES (63, 'Đoàn Thị Tâm', 'doanthitam', NULL, '$2y$10$WgldKT8ZLADIAMuthgWwnOxn3xE7maG.VIebsPOHf5kkvIhDRWOb6', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 62);
INSERT INTO `users` VALUES (64, 'Vũ Thị Thanh Thanh', 'vuthithanhthanh', NULL, '$2y$10$VOctNGvrYoE7AVlr7aXWveNFlI6DSuLYe3npoH9SEa9pAMCJg7pf2', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 63);
INSERT INTO `users` VALUES (65, 'Nguyễn Thu Thủy', 'nguyenthuthuy', NULL, '$2y$10$qkJoIFiH55WC.lcftG.JK..OZrpQrgyb2/SpxhCSHNuFv0ejpXScm', '3', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 64);
INSERT INTO `users` VALUES (66, 'Hoàng Thị Bích Hồng', 'hoangthibichhong', NULL, '$2y$10$a9l/ReVuaYHGKUvCMGkQp.JdLhhtyBOAG28LK0HLIYaJG1St2W48G', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 65);
INSERT INTO `users` VALUES (67, 'Nguyễn Thị Khiên', 'nguyenthikhien', NULL, '$2y$10$iTFbGsMFihrIUymM6YKKpuW4GYES66uvCgd6NBldVoVwFM7V5xaE.', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 66);
INSERT INTO `users` VALUES (68, 'Đinh Quỳnh Oanh', 'dinhquynhanh', NULL, '$2y$10$csFRWnnMOGmbUsNOM2m5UuDp6rd16qLEagQTyX9DpA7Y58T5cRlPy', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 67);
INSERT INTO `users` VALUES (69, 'Nguyễn Thị Thắm', 'nguyenthitham', NULL, '$2y$10$VITJoCK6uqnZJUnT5gaRY.pOsgrIbi4DzF6bRzEfnkpkUrmr2cz.C', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 68);
INSERT INTO `users` VALUES (70, 'Diệp Thị Thu Thủy', 'diepthithuthuy', NULL, '$2y$10$mk0PcHuwT6SpSdFjpzpupuzvlji6YFinTgOJItWbFz2NNN1vKGnOK', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 69);
INSERT INTO `users` VALUES (71, 'Chu Lương Trí', 'chuluongtri', NULL, '$2y$10$lfwceFbdxJES7OrA2jj86.H4AM1S/mlGJjLjNPdhZ8AVavX7ocTOu', '3', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 70);
INSERT INTO `users` VALUES (72, 'Vũ Tô Sa Anh', 'vutosaanh', NULL, '$2y$10$hl2QQ98iocdtPVYay5ek1uPSBmRF70k4wOLwsLhgkKYjSGUEqoYBi', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 71);
INSERT INTO `users` VALUES (73, 'Nguyễn Thuỳ Dương', 'nguyenthuyduong', NULL, '$2y$10$25w7/r5yJonWdokClVj4M.arFDQbqJSoH5mNCFyQlu0UzUU81BxBy', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 72);
INSERT INTO `users` VALUES (74, 'Nguyễn Ngọc Hải', 'nguyenngochai', NULL, '$2y$10$G57LhMEwYjqCkYLASI7aAuST4G3h.7bqkWgF3nrJgG9JIwApVHU4i', '2', NULL, NULL, '2024-07-28 06:00:49', '2024-07-28 06:00:49', NULL, 73);
INSERT INTO `users` VALUES (75, 'Ngô Thị Hiệp', 'ngothihiep', NULL, '$2y$10$wUqPCcviYCz0greLXRgpmeIWlH8lHn3yN55ehz9Qft.CRlomFxwUu', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 74);
INSERT INTO `users` VALUES (76, 'Nguyễn Viết Hoàn', 'nguyenviethoan', NULL, '$2y$10$QnOLqbzRDccO3SQJwM3WGekedRCHYPRx1U2nx0/BIIXQcVTuIfLuq', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 75);
INSERT INTO `users` VALUES (77, 'Vũ Mạnh Hùng', 'vumanhhung', NULL, '$2y$10$S7emgmbFI/J/5LlqXfLynOLeXRIfxR/QyiCTiAwTyJXlqW5RhyQoC', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 76);
INSERT INTO `users` VALUES (78, 'Đặng Kiều Hưng', 'dangkieuhung', NULL, '$2y$10$Pv8Gy/fowRt4DaX765TAgeF3sPKSPA6elvYq/0NpHjMnWp.mvEHqu', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 77);
INSERT INTO `users` VALUES (79, 'Phạm Quang Huy', 'phamquanghuy', NULL, '$2y$10$5O0W0WfskCUDrJxodF73DuOh8qErxaqxKH6qc6PSDPfdGj7BJvAn2', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 78);
INSERT INTO `users` VALUES (80, 'Nguyễn Thị Huyền', 'nguyenthihuyen', NULL, '$2y$10$Mll8kQNuY0PPWr9WhtrdYetFxaD4MwaE394elM0oiYq07.kVgmdKO', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 79);
INSERT INTO `users` VALUES (81, 'Nguyễn Thị Thanh Huyền(NT)', 'nguyenthithanhhuyennt', NULL, '$2y$10$CGEbjcbjigd7VAuN0FeYW.5g8hxm1UWl.DFPykcX2WKpsJIvr/5Xa', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 80);
INSERT INTO `users` VALUES (82, 'Bùi Thế Khương', 'buithekhuong', NULL, '$2y$10$0n0tcz4OjcrebQDBEBYx8OXH42QoCrKsq9YKAin7BlpysMWSb/aI.', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 81);
INSERT INTO `users` VALUES (83, 'Trần Vũ Lâm', 'tranvulam', NULL, '$2y$10$4h4/ozFcSnNPZIHjAnNW0Od63MgaAO733pYC6lkAojfdu6gIuk4fC', '3', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 82);
INSERT INTO `users` VALUES (84, 'Nguyễn Thị Loan', 'nguyenthiloan', NULL, '$2y$10$5YI14Jm1XXAuKYWHVlLpTOzmpvN/F1xWdqj3H27x87p0h/8WNb0Ai', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 83);
INSERT INTO `users` VALUES (85, 'Trần Đức Mạnh', 'tranducmanh', NULL, '$2y$10$.vLMvWeZB/t3BpsW3/o8LeF46efN7ZMNtV4yY7KWGf9/jL45F6e3K', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 84);
INSERT INTO `users` VALUES (86, 'Đào Thị Thanh Ngân', 'daothithanhngan', NULL, '$2y$10$uO7TPyJjqSY4gFRBdfAlhO4iNyDoqOpW0DaWdBGJGDgM3/uineUXa', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 85);
INSERT INTO `users` VALUES (87, 'Trần Đức Nhâm', 'tranducnham', NULL, '$2y$10$IS3XQm8ymmEa4pdYuoa3Wu2XyTV94j9EAspvoqGl4HS.m.RDsgQdC', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 86);
INSERT INTO `users` VALUES (88, 'Trịnh Thị Kim Oanh', 'trinhthikimoanh', NULL, '$2y$10$9Lu6oK47nlo6PNk9216Lh.BvW/XXq2eiNgjxKccOt6PTJpFMRrTJS', '3', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 87);
INSERT INTO `users` VALUES (89, 'Hoàng May Quý', 'hoangmayquy', NULL, '$2y$10$fRNZxek5wi034Rp/.xVgN.byaQCqWmgvVS5JU6bLG1J9cfiOerIp6', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 88);
INSERT INTO `users` VALUES (90, 'Nguyễn Bá Quyền', 'nguyenbaquyen', NULL, '$2y$10$PKUoSxdJ6/QP1RwkeXVO4u3/l4JtYWlr5eoq7ooObKT9vHEAQSe5y', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 89);
INSERT INTO `users` VALUES (91, 'Hoàng Văn Thành', 'hoangvanthanh', NULL, '$2y$10$KwtiZC/Dyl.9GvLTnhTNOuwfKTFB3f2F4HTvbvGKC29W508IqPSUy', '2', NULL, NULL, '2024-07-28 06:00:50', '2024-07-28 06:00:50', NULL, 90);
INSERT INTO `users` VALUES (92, 'Nguyễn Thị Thiền', 'nguyenthithien', NULL, '$2y$10$LYfb5OBEz43uAigHOa9eheLv7MtdfVHtXLuKkORLM0f9qybXuUrmm', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 91);
INSERT INTO `users` VALUES (93, 'Đinh Thị Khánh Thơ', 'dinhthikhanhtho', NULL, '$2y$10$GseojVVC7GcVT.Dahy4imOlBpx.LG4g3A4urKVrHk.dL1aC1q/aEu', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 92);
INSERT INTO `users` VALUES (94, 'Lê Thị Thu', 'lethithu', NULL, '$2y$10$ngWzPOIdx/liBelLkKiRKuez9NuygW6byyR9g8.xC0IO/bv0DyVE6', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 93);
INSERT INTO `users` VALUES (95, 'Đoàn Thanh Thủy', 'doanthanhthuy', NULL, '$2y$10$CIWgIp1qD1ZV8pJfRMJ/O.d5TbGQ9bf5SRtkCpEq3ZEeYLxwMptzS', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 94);
INSERT INTO `users` VALUES (96, 'Nguyễn Ngọc Thủy', 'nguyenngocthuy', NULL, '$2y$10$GROAq4.RJj6prrmDz.0y6edaYYKjEe09IyhyLuyrAUVilTvy2j6PO', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 95);
INSERT INTO `users` VALUES (97, 'Trần Thanh Thủy', 'tranthanhthuy', NULL, '$2y$10$5Ly5iE2JS5OYE3kMSFc6rO7HV29PK0U7kQKQQKBpo5I85J5jS4B8W', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 96);
INSERT INTO `users` VALUES (98, 'Trần Đức Toàn', 'tranductoan', NULL, '$2y$10$iMQbaio.Hwh7DceGdEY7qOgRHKITlyBNrW7lT/UWoEQ7Hf/tktwLC', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 97);
INSERT INTO `users` VALUES (99, 'Chu Thu Trang', 'chuthutrang', NULL, '$2y$10$elC3nN.o.ygfkv.V0djcSukubwaxGJNX/BUbyIwPx2O2whzYaMiP2', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 98);
INSERT INTO `users` VALUES (100, 'Lê nhật trường', 'lenhattruong', NULL, '$2y$10$67iMTDdGfa2W4EaDYWVyROvmvszppPEdkp1Go65kditjVSByLvg3G', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 99);
INSERT INTO `users` VALUES (101, 'Trần Anh Tuấn', 'trananhtuan', NULL, '$2y$10$85/PRk9wESlLxM4Nt4gs0em8/1j07c48lShiViTABDDVbSfMTy6zu', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 100);
INSERT INTO `users` VALUES (102, 'Hoàng Thị Yến(NT)', 'hoangthiyennt', NULL, '$2y$10$M3RLNTuG45RTffXrw2NIMOcWrNEWr4AlC/5wQahUahYlAExtW28Ca', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 101);
INSERT INTO `users` VALUES (103, 'Hoàng Thị Hải Anh', 'hoangthihaianh', NULL, '$2y$10$AQSAoLK9zEWTkqcjtSjN2.Yqdvla8J.1x5mxvdUHDbagFDyPVZojW', '3', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 102);
INSERT INTO `users` VALUES (104, 'Nguyễn Vân Anh', 'nguyenvananh', NULL, '$2y$10$fVYCPReVyANpIE.Vo7BviOCkghaa.3/7FGzt83I/ig3WH3WGF2Cdq', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 103);
INSERT INTO `users` VALUES (105, 'Thân Thị Mỹ Bình', 'thanthimybinh', NULL, '$2y$10$5WPAq.21X1ZEOhGymBfiA.g6xWONnsN3gZHXZD/ROwMGPJpLorumK', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 104);
INSERT INTO `users` VALUES (106, 'TRẦN THỊ CHUNG', 'tranthichung', NULL, '$2y$10$4rxzwn/nZ0Dv//2x/UwvgO.jRuY6b2RJDq7RW5l2oiAU/T/SoDKMC', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 105);
INSERT INTO `users` VALUES (107, 'Bùi Thị Bích Diệp', 'buibichdiep', NULL, '$2y$10$Y8mCdNJCeiDfb2WA0sDGPORSAxfy.ZH8Nt7bsXWQRAa6qhxKGgE7y', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 106);
INSERT INTO `users` VALUES (108, 'Trần Thị Giang', 'tranthigiang', NULL, '$2y$10$BqLJc0fJ0SBrrhU.9nbCM.LSucqirvHVFUK.MkZASTbfNwOxohPP.', '2', NULL, NULL, '2024-07-28 06:00:51', '2024-07-28 06:00:51', NULL, 107);
INSERT INTO `users` VALUES (109, 'Hoàng Thị Thu Hà', 'hoangthithuha', NULL, '$2y$10$lPJfPIVK/MJceQRINZZc3.nbw7Cw4Ak8RHNrWvKkbeIcWTSovJHcS', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 108);
INSERT INTO `users` VALUES (110, 'Vũ Phương Hà', 'vuphuongha', NULL, '$2y$10$wZFVucVFOrs6I2tJR.YaeukWWhz7ThHuCXnbVNvP0jZWNrIMOPsNO', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 109);
INSERT INTO `users` VALUES (111, 'Nguyễn Thị Hằng', 'nguyenthihang', NULL, '$2y$10$ynf7ZrEv29ZK/Yx/m02FO.oB26li8BX7WF0Q4LOrhN497Szj1N0fm', '3', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 110);
INSERT INTO `users` VALUES (112, 'Hoàng Thị Hạnh', 'hoangthihanh', NULL, '$2y$10$25jPQ/YU/zG2Ua9LZqTrsOauq.WrA6LsmIVXUfTNQzFu0B0XCNxdO', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 111);
INSERT INTO `users` VALUES (113, 'Nguyễn Thị Hảo', 'nguyenthihao', NULL, '$2y$10$kaMLLyp1z/zASWgeinkJuOuGZwTyjC325n2WvNPZXWqxbX6QT0aIu', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 112);
INSERT INTO `users` VALUES (114, 'Tạ Thị Hoa', 'tathihoa', NULL, '$2y$10$.FscrzpLZQrD44qOQolIou4B7HkurVwbE48E.T80JMjqog1Nk9gki', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 113);
INSERT INTO `users` VALUES (115, 'Vũ Thanh Hòa(NN)', 'vuthanhhoann', NULL, '$2y$10$ML12nSoY3Z9B9PgiPh.OVOM.JYYr/TA8lfrm8y9RLVeThru80z7ri', '3', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 114);
INSERT INTO `users` VALUES (116, 'Nguyễn Thị Hoàn', 'nguyenthihoan', NULL, '$2y$10$3x8ZNa4VA87hmsYDV2eKfeXir3mMTRPgkmraTyxBxbQ/NfCaaJ2q6', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 115);
INSERT INTO `users` VALUES (117, 'Nguyễn Tâm Hồng', 'nguyentamhong', NULL, '$2y$10$0zmDQqC1zFJnoYk0yBJfAO5bD6LNwO9QpB6hoEj9ovuDsRv6ZcKzO', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 116);
INSERT INTO `users` VALUES (118, 'Nguyễn Thị Thu Hương', 'nguyenthithuhuong', NULL, '$2y$10$obsXQm05lw6o46swoP2HFu6uySxMMuQOC3LearzhNWppk9hinveIi', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 117);
INSERT INTO `users` VALUES (119, 'Bùi Thị Huyền', 'buithihuyen', NULL, '$2y$10$OyC7I1t09zbAbjowU6q.COopZR8dofY3AH8MDkPSsdBf36X0T35A6', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 118);
INSERT INTO `users` VALUES (120, 'Nguyễn Thị Thanh Huyền(NN)', 'nguyenthithanhhuyennn', NULL, '$2y$10$XMSIjRF.386/n24VnLxMK.P5Rn5cSoHk1B.9uG0G8KyfKHmA5RrLK', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 119);
INSERT INTO `users` VALUES (121, 'Vương Thị Bích Liên', 'vuongthibichlien', NULL, '$2y$10$DImON0BsSl7AG/V56gTbEu2iNg4eBAVI60n5yzdmcf8AhtNDYdfPa', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 120);
INSERT INTO `users` VALUES (122, 'Nguyễn Diệp Linh', 'nguyendieplinh', NULL, '$2y$10$TFzlGicysQFyVxFktcvtM.xJlaV6phKgssIKVPCw9j5WhFA7BInDu', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 121);
INSERT INTO `users` VALUES (123, 'Phạm Thị Linh', 'phamthilinh', NULL, '$2y$10$4cOfxOXntd/Cnta8KY3bW.TumZOrFL.k601CGKKyyU.PXk242mzOW', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 122);
INSERT INTO `users` VALUES (124, 'Lê Nguyệt Minh', 'lenguyetminh', NULL, '$2y$10$1AeGg4k4s2AXbIJGRSl8vuvL0slpBO31rfe3kTo8up64PC13Cdt/S', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 123);
INSERT INTO `users` VALUES (125, 'Vương Thị Kim Minh', 'vuongthikimminh', NULL, '$2y$10$nbNKLe4dmS6luC1Od13FJOHlHWvaqd1CgWAMlWAkfvynyglf8Z7Xy', '2', NULL, NULL, '2024-07-28 06:00:52', '2024-07-28 06:00:52', NULL, 124);
INSERT INTO `users` VALUES (126, 'Hoàng Phương Nam', 'hoangphuongnam', NULL, '$2y$10$whNwSZ3V5QTJ81eSVgAr3uRAC8vsPBpmOJEMloAWAYMY1Jhm82a6a', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 125);
INSERT INTO `users` VALUES (127, 'Nguyễn Thị Thanh Ngân', 'nguyenthithanhngan', NULL, '$2y$10$QUV/JFnpc5l.Dl42Nmu9M.Sooy5qWjErv.Dtvp53aJckUbC.H.5Fu', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 126);
INSERT INTO `users` VALUES (128, 'VŨ VĂN NGÂN', 'vuvanngan', NULL, '$2y$10$L6HXPDVRsWAD.bOvuDtNA.pHwIfJvBvCMtcQ.1X/khHpS.qYDAu56', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 127);
INSERT INTO `users` VALUES (129, 'Nguyễn Thúy Ngọc', 'nguyenthuyngoc', NULL, '$2y$10$/pRnBn1fV2E71Fh/OWpEV.VqXGkJiuU/k8jySrdbP7SgpaK99xFma', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 128);
INSERT INTO `users` VALUES (130, 'Bùi Như Nguyệt', 'buinhunguyet', NULL, '$2y$10$D/1vt7HF010MQGxCmdhNB.poR02.2eQkFX4HhobKfI1tybamO8ApC', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 129);
INSERT INTO `users` VALUES (131, 'Bùi Bích Phương', 'buibichphuong', NULL, '$2y$10$nnzwUUJM9tHjwb0bzZohz.CwhrCLRdC0.ps5kf91V0uMUBKqvZZJm', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 130);
INSERT INTO `users` VALUES (132, 'Bùi Trí Quân', 'buitriquan', NULL, '$2y$10$Qf60FpM6z/YLHtmsJZBojeICU8mlZbLoqBCADp1b6kxD5hGqs.W76', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 131);
INSERT INTO `users` VALUES (133, 'Lê Tuấn Sơn', 'letuanson', NULL, '$2y$10$RkOLBrmlVoauqB5KaG/t9ey7oxBfk/o3U.vuubjcohOxgBRbzQhva', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 132);
INSERT INTO `users` VALUES (134, 'Đỗ Thị Yến Thoa', 'dothiyenthoa', NULL, '$2y$10$IrtKkJWt1uxqkkuaNF.ApOd67VjJuUjwgEdG7KTs9laXNrCuQBfii', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 133);
INSERT INTO `users` VALUES (135, 'Nguyễn Thị Thanh Thủy', 'nguyenthithanhthuy', NULL, '$2y$10$c4Or.MQy7PAHoOnD44JFPOqy.jBcyqrYjzdc5piYncjUxUN7dRKAW', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 134);
INSERT INTO `users` VALUES (136, 'Đỗ Thị Hương Trà', 'dothihuongtra', NULL, '$2y$10$Vk0kMM1bTr1mTH8b2ONo9Or1ZtYKqMOPZYiwcljUjXrSXABLS1OlO', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 135);
INSERT INTO `users` VALUES (137, 'Nguyễn Đức Tú', 'nguyenductu', NULL, '$2y$10$TpbcALYHZ0Q4o5y.23c4IuWJTLrSpYKjPgTfdIL./XMZsqOlLsNgm', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 136);
INSERT INTO `users` VALUES (138, 'Ngô Thị Ánh Tuyết', 'ngothianhtuyet', NULL, '$2y$10$SlPVcdzc.zwicpfyaHSOSeoPDES8zxvhRnnpdOPe5J47lWsColPze', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 137);
INSERT INTO `users` VALUES (139, 'Vương Thị Hồng Vân', 'vuongthihongvan', NULL, '$2y$10$UyTfwlT/3G5LW6csXv.Vle4hwoRpJqjvWXTZXedOVT7I2AO.A7ZNa', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 138);
INSERT INTO `users` VALUES (140, 'Đỗ Thị Xuân', 'dothixuan', NULL, '$2y$10$m/9NSCKMsJEc3gRXFdGuWuMtO/ikCaq0iqw1NPZfFm.rplVtfTPGu', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 139);
INSERT INTO `users` VALUES (141, 'Đoàn Thị Hải Yến', 'doanthihaiyen', NULL, '$2y$10$vLrpPiTEyE1GIFo3GUzYPuvgwYhWrhXPkIy/ctmN9sqbiYw7bOc7m', '2', NULL, NULL, '2024-07-28 06:00:53', '2024-07-28 06:00:53', NULL, 140);
INSERT INTO `users` VALUES (142, 'Bùi Văn Chương', 'buivanchuong', NULL, '$2y$10$nemrthwgWy6FwqRKR3zyQ.o7mFbZ.g3Twa6g1ff9EyQp8hNlhL1uu', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 141);
INSERT INTO `users` VALUES (143, 'Nguyễn Thị Điệp', 'nguyenthidiep', NULL, '$2y$10$KsNv3uCdpaTHqad7us8Y.OReoZBoKumZ9UAICAEYhTbwdNsm9IWCa', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 142);
INSERT INTO `users` VALUES (144, 'Nguyễn Thị Gấm', 'nguyenthigam', NULL, '$2y$10$u4nwKZNB6cbI/9FwGhweNOwJR9CeGiIYsPfDWP/g2zgKtM8js4Xoy', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 143);
INSERT INTO `users` VALUES (145, 'Đặng Thị Thu Hiền(SP)', 'dangthithuhiensp', NULL, '$2y$10$isJiTmiHCblBmLdZqLwBruBJQyyYiMcxdkwQZprIcmUpF9g3Squse', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 144);
INSERT INTO `users` VALUES (146, 'Trần Thị Hòa', 'tranthihoa', NULL, '$2y$10$36rrd0ht0FA3pOKPkWmOxeaFRRUpDiqCvIBMlzNVjUZK3cSaHRnQW', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 145);
INSERT INTO `users` VALUES (147, 'Vũ Thanh Hòa(SP)', 'vuthanhhoasp', NULL, '$2y$10$.ajdAZlG9V7tzL/AMLgJXOIQ4YxZYSztOgzQ.Let1ST8EIs1JAdrW', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 146);
INSERT INTO `users` VALUES (148, 'Nguyễn Minh Huệ', 'nguyenminhhue', NULL, '$2y$10$3NmODF2uTgI5JrhdF0QJDOEoiF.4MIC0OmcUkUjXrEdPgqiKTyAGi', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 147);
INSERT INTO `users` VALUES (149, 'Bùi Thị Lan Hương', 'buithilanhuong', NULL, '$2y$10$rEuI4OPo4g4TXIOiNz9ZYe6nGbg9/EwFhjz28qaXzsMUsQ3gioD0K', '3', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 148);
INSERT INTO `users` VALUES (150, 'Nguyễn Thị Hương', 'nguyenthihuong', NULL, '$2y$10$ZB5Khcz9Yso.M6liKoR2lOJFmxIfy/FQr868HHLCSJbTm4JRVDor.', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 149);
INSERT INTO `users` VALUES (151, 'Bế Thị Thu Huyền', 'bethithuhuyen', NULL, '$2y$10$LeeN.djguYoMdjGqLYhmJuJHfl1I7pj6D6Bir4ZY4rpXxomMcgbky', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 150);
INSERT INTO `users` VALUES (152, 'Phạm Trung Kiên', 'phamtrungkien', NULL, '$2y$10$Ls/kcqeU.3vrH1byvD9RmuQ.91UDEghVj2gPdWn0tBMTFamUJTOxq', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 151);
INSERT INTO `users` VALUES (153, 'Nguyễn Thị Quý Kim', 'nguyenthiquyskim', NULL, '$2y$10$pj/HHMOfW0Dt75JjIVX5uODZskUzFJnYgwza/cZS4p3UM8mHViejC', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 152);
INSERT INTO `users` VALUES (154, 'Trần Thị Kim Loan', 'tranthikimloan', NULL, '$2y$10$nGGd7.Ru39QHkjAjmw6A3efmtQFA1diRE0Dp7xIZla1U6uyy0Iwwi', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 153);
INSERT INTO `users` VALUES (155, 'Nguyễn Thị Ngọc Lương', 'nguyenthingocluong', NULL, '$2y$10$FWXmXeeYS/mP8Woj69G2R.7pLbbPX2MSAnPRlRf8SNdbj0JvvIUhG', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 154);
INSERT INTO `users` VALUES (156, 'Phạm Thị Minh Lương', 'phamthiminhluong', NULL, '$2y$10$YAfUemlSPYbSVH5KWKdg1uNKn6kini6BoAbL.KotM1qCA.BefdamO', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 155);
INSERT INTO `users` VALUES (157, 'Liễu Quỳnh Như', 'lieuquynhnhu', NULL, '$2y$10$apuGBJrwLy6TrqyHPBpJoOvHMnEkK/OC9kpq2XGELcZAtZVLJ0DPS', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 156);
INSERT INTO `users` VALUES (158, 'Nguyễn Ngọc Quỳnh', 'nguyenngocquynh', NULL, '$2y$10$plhvnspGhYeiaSC5mCcWKeaqWNNqrJGUFXGhMEzhNFRlZkZPnTCDK', '2', NULL, NULL, '2024-07-28 06:00:54', '2024-07-28 06:00:54', NULL, 157);
INSERT INTO `users` VALUES (159, 'Đặng Quang Rinh', 'dangquangrinh', NULL, '$2y$10$44ZE0JSdSiIIUpav.js63uxTBFLG1aOQNhVKnhtfPcYRs0OdGJeuu', '3', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 158);
INSERT INTO `users` VALUES (160, 'Nguyễn Thị Minh Thái', 'nguyenthiminhthai', NULL, '$2y$10$IwXAT89tq9XbQdeUmeHgquoInFg8FVSEIEIxcy4NvcgXiNtkZswMS', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 159);
INSERT INTO `users` VALUES (161, 'Nguyễn Thị Thương', 'nguyenthithuong', NULL, '$2y$10$PTf4o1jUzZnkFXS.maJs.uzHwwWVaPK5FsPd0Z/DB8Dc0zOQV9udO', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 160);
INSERT INTO `users` VALUES (162, 'Nguyễn Hữu Tới', 'nguyenhuutoi', NULL, '$2y$10$J.yk4lIEyE3nAcfchXMvLerUyPExptP6h7RAYIHpRMnj720CHQkBG', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 161);
INSERT INTO `users` VALUES (163, 'Vũ Vương Trưởng', 'vuvuongtruowng', NULL, '$2y$10$4RXozPG.uxM.rjhijl1G3uILRYACYRj5002qY5LxueBSshvORa1H.', '3', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 162);
INSERT INTO `users` VALUES (164, 'Nguyễn Hoàng Vân', 'nguyenhoangvan', NULL, '$2y$10$IaJYBhU7IxPh4iL2q3mBZuVYla3Oz03bVzjyLJW/UhOJp1iR2MF96', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 163);
INSERT INTO `users` VALUES (165, 'Hà Ngọc Yến', 'hangocyen', NULL, '$2y$10$uSr7S.N0ENoSIWo3ywVB/OkONNn3AlluTznL1Jbr3.zr7j2n6vH0.', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 164);
INSERT INTO `users` VALUES (166, 'Ngô Thị Hoản', 'ngothihoan', NULL, '$2y$10$VL3GhoNW6b9eB3PCsJT3G.qn7k1fN0w4vVhRH8D7Qx5jDq97WmOtm', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 165);
INSERT INTO `users` VALUES (167, 'Hoàng Văn Hùng', 'hoangvanhung', NULL, '$2y$10$fSi8NPepHH81T0NOcgFLNOiA9pZ43VBKnInF19wAslOviAt9SHisy', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 166);
INSERT INTO `users` VALUES (168, 'Vũ Thị Thanh Hương', 'vuthithanhhuong', NULL, '$2y$10$d3O02Xw9jPC0Bya2ZsMJPO2G57tqgE7mS2vpa3VzZlW5caM1I5hsS', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 167);
INSERT INTO `users` VALUES (169, 'Lê Thị Như Phương', 'lethinhuphuong', NULL, '$2y$10$ka0cSu2Wk7FR/rRVscXV5.JcBxO0GSBXZTWiDhcYU84yJCiPMy6VW', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 168);
INSERT INTO `users` VALUES (170, 'Nguyễn Hữu Tích', 'nguyenhuutich', NULL, '$2y$10$j0v1UnNcObP2nYVy55aaOeXNZrbxhmRbwDbWK8fl8iIpzyEyYEehC', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 169);
INSERT INTO `users` VALUES (171, 'Nguyễn Duy Cường', 'nguyenduycuong', NULL, '$2y$10$xjzIqHqJHFsw/MVI69fUZeG956c8bFVO5l3A5DzHG.oWxudstWl2q', '3', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 170);
INSERT INTO `users` VALUES (172, 'Nguyễn Thị Thuỳ Dương', 'nguyenthithuyduong', NULL, '$2y$10$mfX6EqKrQvKCworIUwcnMO2aEUNh6x7WzuKNXfbxO/q585n28wUcS', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 171);
INSERT INTO `users` VALUES (173, 'Hạp Thu Hà', 'hapthuha', NULL, '$2y$10$XMoeJpmS2rlt1/kl0iJG2u3O6cGZz4feNQfYtx5ZFCT9DoKieOsfi', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 172);
INSERT INTO `users` VALUES (174, 'Lê Thanh Hoa', 'lethanhhoa', NULL, '$2y$10$Kl79sXCSahoaPKtcCzH5D.L9bG8iNwJ771mgE.3366nO/hXxrLaXK', '2', NULL, NULL, '2024-07-28 06:00:55', '2024-07-28 06:00:55', NULL, 173);
INSERT INTO `users` VALUES (175, 'Lưu Thị Thanh Hòa', 'luuthithanhhoa', NULL, '$2y$10$ihMMv8YQ5IfTCB9PtVeB5Oeh6avOXuut6RC96B5OG6rhhjgWYpvMy', '2', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 174);
INSERT INTO `users` VALUES (176, 'hoàng tuyết mai', 'hoangtuyetmai', NULL, '$2y$10$j0S8WWYS04jGVrRgRosI3O2OLfICHSC8EweDDP/O8MUUJ8Z0/z3q.', '2', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 175);
INSERT INTO `users` VALUES (177, 'Ngô Hải Ninh', 'ngohaininh', NULL, '$2y$10$Lmhh0u4LSpmmXs46BzWg4.58tbDpWTB.ZUOE4bknxuMUQIUa8cQxq', '3', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 176);
INSERT INTO `users` VALUES (178, 'Cao Thị Thường', 'caothithuong', NULL, '$2y$10$KXkc9aopulNEegYMLRuFLO2HoUwq6JyrNGHiapv3i5kDnx8IHfCXC', '2', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 177);
INSERT INTO `users` VALUES (179, 'Nguyễn Quốc Tuấn', 'nguyenquoctuan', NULL, '$2y$10$4ZQ4wqUIUylAk5.uq7GgmO3gJ3.7z9LHPyNdpUfPY2fnrMTVXPIDq', '6', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 178);
INSERT INTO `users` VALUES (180, 'Đào Thị Vương', 'daothivuong', NULL, '$2y$10$oa0QIMbxdbLDtBn9hMIslunC9olA/6XQJAn4vuMVCCl7soDaP/xiS', '6', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 179);
INSERT INTO `users` VALUES (181, 'Nguyễn Thị Thủy', 'nguyenthithuy', NULL, '$2y$10$3NhNTLNaGOmdi3eXcwobzuabxr2NIA.vkQRvz3nBZWAwlh.GC/WnW', '6', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 180);
INSERT INTO `users` VALUES (182, 'Nguyễn Thị Thu Hiền', 'nguyenthithuhien', NULL, '$2y$10$FnYc2lv1OTJZsb24gVZRTuxRXOlNRLJvrVUXvXM1hKOGfb43TbGRC', '4', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 181);
INSERT INTO `users` VALUES (183, 'Nguyễn Doãn Hùng', 'nguyendoanhung', NULL, '$2y$10$/AHbGE1Iy0t8m8DUpqWtvOyowad406M.prfBN70VHRhJ2cY2C85de', '4', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 182);
INSERT INTO `users` VALUES (184, 'Ty Văn Quỳnh', 'tyvanquynh', NULL, '$2y$10$2Ssker28VtTT/yTSBDpGX./jEy7KHaK.enYJKGcJoBOkEyM6TyO5O', '4', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 183);
INSERT INTO `users` VALUES (185, 'Lê Anh Tú', 'leanhtu', NULL, '$2y$10$B02RnjU4OTKsUKlCiDR0E.iN/5SHU7jYOo58juryBZAoGARrh3i/q', '6', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 184);
INSERT INTO `users` VALUES (186, 'Hoàng Văn Vinh', 'hoangvanvinh', NULL, '$2y$10$Dss7PtGQGhq96CgOm3X8ZOacN7K8VeKFdVBd5lfbZ0tPvp7T3zwV6', '6', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 185);
INSERT INTO `users` VALUES (187, 'Phạm Quý Giang', 'phamquygiang', NULL, '$2y$10$lh/7850Lt3M87n/STyOIz.yEi1hSl1EwtItlOE0cfHwjZfTjkz6EG', '6', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 186);
INSERT INTO `users` VALUES (188, 'TÔ THỊ THÁI HÀ', 'tothithaiha', NULL, '$2y$10$dybPIkIdBR7eHWtcDZrzKuagsMbJYa67H0vVEaX.gEqFz/tsMvB1.', '4', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 187);
INSERT INTO `users` VALUES (189, 'Đặng Trần Hùng', 'dangtranhung', NULL, '$2y$10$Bjo7yx96Mm83aQY1pQsF6ObVwKC1Ul35HMCOtbK.aXe2JXV08eXde', '4', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 188);
INSERT INTO `users` VALUES (190, 'Vũ Thị Thu Hương', 'vuthithuhuong', NULL, '$2y$10$BMqoVwlz1I0G1lwPlTzaI.ceyrzNB.xyE.Z9cqm5iaSByo8iWZkdq', '4', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 189);
INSERT INTO `users` VALUES (191, 'Trần Thị Thu Trang', 'tranthithutrang', NULL, '$2y$10$HjER8Ww7rOAeTtxMZdgYTO.7bLjeYhBjZpDDxUluDQLI4u8ekLaQi', '4', NULL, NULL, '2024-07-28 06:00:56', '2024-07-28 06:00:56', NULL, 190);
INSERT INTO `users` VALUES (192, 'Lại Thế Sơn', 'laitheson', NULL, '$2y$10$KPoJHsV/K/nQDbCk51kRFeFm25aytCGSjzv7t0kipRAPVHgcI2QMC', '5', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 191);
INSERT INTO `users` VALUES (193, 'Văn Trọng Hùng', 'vantronghung', NULL, '$2y$10$amy5FMBBiSKU8InOW6DYLuJ0hmMQqE84RxzQOYzWn/ndSAKyONusu', '5', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 192);
INSERT INTO `users` VALUES (194, 'Đỗ Thị Lan', 'dothilan', NULL, '$2y$10$snud05siVTdDpVyYvNZQ..pK490NlayAfoaeOA.Lv0rx6qdbnafn2', '5', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 193);
INSERT INTO `users` VALUES (195, 'Vũ Thị Doan', 'vuthidoan', NULL, '$2y$10$NDdVgxMUGVDUfBOVgSka0upwhdhOtv/Xhlk3FQlv2Sc.bXLOZe4l2', '4', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 194);
INSERT INTO `users` VALUES (196, 'Nguyễn Thị Hồng Hải', 'nguyenthihoghai', NULL, '$2y$10$Sz8OSj/cYhMe0d3i5BQOA.yj/OQQXCxERoWkSyvlCP2N1kVSuHCkK', '6', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 195);
INSERT INTO `users` VALUES (197, 'Phạm Thị Tuyết Hạnh', 'phamthituyethanh', NULL, '$2y$10$i3fuHOe2WHrIM1FpA4xWYOR/Adr.GDJJy2HvvIhLD30HlDlGIeISm', '4', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 196);
INSERT INTO `users` VALUES (198, 'Nguyễn Mai Hùng', 'nguyenmaihung', NULL, '$2y$10$QMQ8QF02IkRNkCmJMiKrTO4tIl6aak6VwdMabRQ.uuEI7UFz6/uoq', '6', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 197);
INSERT INTO `users` VALUES (199, 'Dương Thị Hồng Nhung', 'duongthihongnhung', NULL, '$2y$10$tTvC7Lnm/6MLHYDvIGKVJuLVOhtxPW6N7V3rwOYLPZaMdlr8E.ye.', '4', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 198);
INSERT INTO `users` VALUES (200, 'VŨ HẰNG THƯ', 'vuhangthu', NULL, '$2y$10$x9miqc1QChAg2yST6VuLj.rbZ1lJnRPHrRZZVJVxb1ZoC.e41THsK', '4', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 199);
INSERT INTO `users` VALUES (201, 'Hoàng Thị Yến(KT)', 'hoangthiyenkt', NULL, '$2y$10$pHgBQDctLIzPp5Wmrf1VFOnQ/2NMfaznv8ahFPHr7..MU0UPXphYO', '4', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 200);
INSERT INTO `users` VALUES (202, 'Vũ Thị Thu Hà', 'vuthithuha', NULL, '$2y$10$plYwaxJKv31/Z3c9VIqS7.3WbscR36Trx3W9ZZFu0teLkjM4FPJQu', '4', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 201);
INSERT INTO `users` VALUES (203, 'Nguyễn Văn Lâm', 'nguyenvanlam', NULL, '$2y$10$beTmLqH0.64As1BrqNcgQe3iiyH0nmJ/zpUMuXLcc/M0Z.xeBnL2a', '5', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 202);
INSERT INTO `users` VALUES (204, 'Nguyễn Thị Ngọc Lan', 'nguyenthingoclan', NULL, '$2y$10$qAQ2ainGQgVOy3NpqNwQ9Oh7G/SFjI4ieK8/vThDOZjIdDqWw/.3u', '5', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 203);
INSERT INTO `users` VALUES (205, 'Bùi Thị Hằng Nga', 'buithihangnga', NULL, '$2y$10$QwPyQyLbSZOYuh.seY9h9u1nLmHTk1j7C80aJN/ZClSPZxb6plwh.', '4', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 204);
INSERT INTO `users` VALUES (206, 'Nguyễn Thị Ngọc Oanh', 'nguyenthingocoanh', NULL, '$2y$10$Pyq33TBbvMbwuXYCSJPUhujRDqmg5PuPz0TjtNXJ4MtJOzk4xiucC', '4', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 205);
INSERT INTO `users` VALUES (207, 'Đồng Thị Quyên', 'dongthiquyen', NULL, '$2y$10$qhxRlTCmxQJDUg.v2oki6uZOgxkThUlTKY33YbF/r.v3AIEZPAMeO', '4', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 206);
INSERT INTO `users` VALUES (208, 'Nguyễn Thị Mai Ly', 'nguyenthimaily', NULL, '$2y$10$G4KjrpVbVrORO8T3qGc8TOO4EVXlAaPC9Wv9heJWxeiMUJfcbiAcu', '4', NULL, NULL, '2024-07-28 06:00:57', '2024-07-28 06:00:57', NULL, 207);
INSERT INTO `users` VALUES (209, 'Nguyễn Văn Quang', 'nguyenvanquang', NULL, '$2y$10$bZSeYsudUhxP8WALUT1XXeHsZ3voR4Dy4rHPJbUGhjjTGgc1N7kVG', '6', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 208);
INSERT INTO `users` VALUES (210, 'Vũ Công Tâm', 'vucongtam', NULL, '$2y$10$nCyyYfuoNpUQKRdU/Fl1JeYUKWoOh7COyej8jJZYcv3EwU.jvGp.q', '6', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 209);
INSERT INTO `users` VALUES (211, 'Đinh Thị Tuyết', 'dinhthituyet', NULL, '$2y$10$eVn1P7qjX2qEi.T5a2GFiegVtItbcb5StaEBsVyDsMWNGrzhlrPYa', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 210);
INSERT INTO `users` VALUES (212, 'Vũ Thị Bích Thảo', 'vuthibichthao', NULL, '$2y$10$UPvWY8f1Nll/xqXNI6xi1u8onZm4tonelptYPL.gc.oxL592hHyGa', '6', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 211);
INSERT INTO `users` VALUES (213, 'Nguyễn Thị Trang', 'nguyenthitrang', NULL, '$2y$10$lCxk89g3r3YpqLU.O2Ov7elLr6ydkPgMioDtnSmMkRXhCLTqyM.Y6', '6', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 212);
INSERT INTO `users` VALUES (214, 'Nguyễn Chí Đông', 'nguyenchidong', NULL, '$2y$10$8VQnYBpOcjkme4.J0Qf3AehNQi7//7nOpYu8XBy6pWPbOMSdlUpAe', '6', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 213);
INSERT INTO `users` VALUES (215, 'Nguyễn Trung Dũng', 'nguyentrungdung', NULL, '$2y$10$xLhmN1TVWZONQt7TCmdCVuZIKvNc.O0Ar0FujOuRpDGUmyUW6xRY.', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 214);
INSERT INTO `users` VALUES (216, 'Lê Mạnh Hà', 'lemanhha', NULL, '$2y$10$TrWRWF3WUDKwNVhLfHPNge207tnEcHLnuDQc378dZZtrHdU906C9q', '6', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 215);
INSERT INTO `users` VALUES (217, 'Giáp Lương Thụy', 'giangluongthuy', NULL, '$2y$10$bNPIghdSXa8mtjTSFYktP.UamjToEkGxySsyqJSsv6p.HP2pg34Ie', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 216);
INSERT INTO `users` VALUES (218, 'Phạm Huy Anh', 'phamhuyanh', NULL, '$2y$10$G2cBCp7GPP6fijKu.8jm3uq5C07q1w/AEMFOJjEW.PruZKckhmJSS', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 217);
INSERT INTO `users` VALUES (219, 'Bùi Thu Hiền', 'buithuhien', NULL, '$2y$10$zM8Fqo6owXfHGwXOnAKd7uWOcj5HLW.JSCkTiVFI0qby5f.vvXn6.', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 218);
INSERT INTO `users` VALUES (220, 'Đặng Thị Thu Hiền', 'dangthithuhien', NULL, '$2y$10$Fuq5HOGm./uwiPNNTD99MuFt093McVMI6lwEQTRRU88W71tJ.VJBi', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 219);
INSERT INTO `users` VALUES (221, 'Trần Thị Minh Ngọc', 'tranthiminhngoc', NULL, '$2y$10$73UkSBAABvsRJouvHppKMe69DumBXBrPLs8s.Km6HOlBqSJzMUlKi', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 220);
INSERT INTO `users` VALUES (222, 'Nguyễn Minh Phong', 'nguyenminhphong', NULL, '$2y$10$gUJYpM1Gc3Sx5c0V74Te4Ovn6EAghUZr20P5o1niISP2PIr2v1KYq', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 221);
INSERT INTO `users` VALUES (223, 'Nguyễn Thị Lệ Quyên', 'nguyenthilequyen', NULL, '$2y$10$rL9aizjFsMspnD3SkQBf8ewxPk.50hvt.o9bzsdog0Dw6TAKTGrxa', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 222);
INSERT INTO `users` VALUES (224, 'Hoàng Thị Thương', 'hoangthithuong', NULL, '$2y$10$4IYCC/.7zKnt1.PhrdesWuomlraxgV5qHi2OgkdhJtBthBvn1SoSO', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 223);
INSERT INTO `users` VALUES (225, 'Nguyễn Thị Nhung', 'nguyenthinhung', NULL, '$2y$10$b9vpox5g8rxVwC3HVySFluToJi6/HPsBhGjgMaGKj33c450yz0s7K', '4', NULL, NULL, '2024-07-28 06:00:58', '2024-07-28 06:00:58', NULL, 224);
INSERT INTO `users` VALUES (226, 'Vũ Tiến Tình', 'vutientinh', NULL, '$2y$10$P5gW.Vf9rb3FF3Zw5NZGHOCaHX8VmM/PtPxmj8f168etx3Waw177a', '4', NULL, NULL, '2024-07-28 06:00:59', '2024-07-28 06:00:59', NULL, 225);

SET FOREIGN_KEY_CHECKS = 1;
