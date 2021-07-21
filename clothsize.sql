/*
 Navicat Premium Data Transfer

 Source Server         : cs-mariadb-clothsize
 Source Server Type    : MySQL
 Source Server Version : 100408
 Source Host           : localhost:3308
 Source Schema         : clothsize

 Target Server Type    : MySQL
 Target Server Version : 100408
 File Encoding         : 65001

 Date: 22/07/2021 04:42:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for client
-- ----------------------------
DROP TABLE IF EXISTS `client`;
CREATE TABLE `client`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `gender` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NOT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for client_cloth_size
-- ----------------------------
DROP TABLE IF EXISTS `client_cloth_size`;
CREATE TABLE `client_cloth_size`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NULL DEFAULT NULL,
  `size_chart_id` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `client_id`(`client_id`) USING BTREE,
  INDEX `size_chart_id`(`size_chart_id`) USING BTREE,
  CONSTRAINT `client_cloth_size_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_cloth_size_ibfk_2` FOREIGN KEY (`size_chart_id`) REFERENCES `size_chart` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for size_chart
-- ----------------------------
DROP TABLE IF EXISTS `size_chart`;
CREATE TABLE `size_chart`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size_type` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cloth_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `gender_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `chest_width` int(3) NOT NULL,
  `shirt_length` int(3) NOT NULL,
  `waist_width` int(3) NOT NULL,
  `sleeve_length` int(3) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
