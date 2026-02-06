-- Script de criação do banco de dados financeiro
-- Compatível com MySQL 5.6, 5.7, 8.0+ e MariaDB

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salary` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` (`id`, `salary`) VALUES (1, 1600.00);

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `percentage` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` (`name`, `percentage`) VALUES ('Finanças', 10);
INSERT INTO `categories` (`name`, `percentage`) VALUES ('Lazer', 30);
INSERT INTO `categories` (`name`, `percentage`) VALUES ('Sobrevivência', 60);

SET FOREIGN_KEY_CHECKS = 1;
