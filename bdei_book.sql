/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : bdei_book

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 20936

Date: 2015-04-17 13:32:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `bdei_book`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_book`;
CREATE TABLE `bdei_book` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_name` varchar(200) DEFAULT NULL,
  `book_author` varchar(160) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `book_press` varchar(200) DEFAULT NULL COMMENT '出版社',
  `book_isbn` varchar(64) DEFAULT NULL COMMENT '书本的ISBN号码',
  `save_place` varchar(200) DEFAULT NULL,
  `book_cover` varchar(250) DEFAULT NULL COMMENT '书籍封面',
  `status` tinyint(1) DEFAULT NULL,
  `read_number` int(11) DEFAULT NULL,
  `book_classification` varchar(10) DEFAULT NULL COMMENT '分类',
  `book_classification_word` char(1) DEFAULT NULL COMMENT 'A-Z的分类',
  `add_time` datetime DEFAULT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bdei_book
-- ----------------------------

-- ----------------------------
-- Table structure for `bdei_bookmark`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_bookmark`;
CREATE TABLE `bdei_bookmark` (
  `bookmark_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`bookmark_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bdei_bookmark
-- ----------------------------

-- ----------------------------
-- Table structure for `bdei_book_category`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_book_category`;
CREATE TABLE `bdei_book_category` (
  `dir_id` int(11) NOT NULL DEFAULT '0',
  `dir_name` varchar(200) DEFAULT NULL,
  `parent_id` char(2) DEFAULT NULL,
  `sort_number` int(5) DEFAULT NULL,
  PRIMARY KEY (`dir_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bdei_book_category
-- ----------------------------

-- ----------------------------
-- Table structure for `bdei_book_extend`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_book_extend`;
CREATE TABLE `bdei_book_extend` (
  `book_id` int(11) DEFAULT NULL,
  `book_keyword` varchar(200) DEFAULT NULL COMMENT '关键字',
  `book_key_words` varchar(200) DEFAULT NULL COMMENT '主题词',
  `book_desc` varchar(248) DEFAULT NULL COMMENT '书本描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bdei_book_extend
-- ----------------------------

-- ----------------------------
-- Table structure for `bdei_book_type`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_book_type`;
CREATE TABLE `bdei_book_type` (
  `book_type_id` int(4) NOT NULL DEFAULT '0',
  `book_type_name` varchar(200) DEFAULT NULL,
  `book_type_other` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`book_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bdei_book_type
-- ----------------------------

-- ----------------------------
-- Table structure for `bdei_user`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_user`;
CREATE TABLE `bdei_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(200) DEFAULT NULL,
  `user_nick` varchar(200) DEFAULT NULL,
  `user_pwd` char(32) DEFAULT NULL,
  `user_sex` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bdei_user
-- ----------------------------

-- ----------------------------
-- Table structure for `bdei_user_read_history`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_user_read_history`;
CREATE TABLE `bdei_user_read_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `add_time` datetime DEFAULT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bdei_user_read_history
-- ----------------------------

-- ----------------------------
-- Table structure for `bedei_administrator`
-- ----------------------------
DROP TABLE IF EXISTS `bedei_administrator`;
CREATE TABLE `bedei_administrator` (
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `admin_name` varchar(220) DEFAULT NULL,
  `admin_pwd` varchar(66) DEFAULT NULL,
  `add_time` date DEFAULT NULL,
  `last_login_time` date DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for `bdei_group`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_group`;
CREATE TABLE `bdei_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for `bdei_user_permission`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_user_permission`;
CREATE TABLE `bdei_user_permission` (
  `user_id` int(11) DEFAULT NULL,
  `permission_id` int(11) DEFAULT NULL,
  `in_out` tinyint(1) DEFAULT NULL,
  KEY `user_permission_id` (`user_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bdei_permission`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_permission`;
CREATE TABLE `bdei_permission` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(250) DEFAULT NULL,
  `permission_union_key` varchar(100) DEFAULT NULL,
  `page_name` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bdei_group_user`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_group_user`;
CREATE TABLE `bdei_group_user` (
  `admin_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  KEY `admin_group_id` (`admin_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bdei_group_permission`
-- ----------------------------
DROP TABLE IF EXISTS `bdei_group_permission`;
CREATE TABLE `bdei_group_permission` (
  `group_id` int(11) DEFAULT NULL,
  `permission_id` int(11) DEFAULT NULL,
  KEY `group_permission` (`group_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bedei_administrator
-- ----------------------------
INSERT INTO `bedei_administrator` VALUES ('1', 'admin', '840600c989da4a9a702cc6de5c577b21:9T7', '2015-04-15', '2015-04-15');

ALTER TABLE `bdei_administrator`
ADD COLUMN `group_id`  int(11) NULL AFTER `last_login_time`;

ALTER TABLE `bdei_administrator`
MODIFY COLUMN `admin_id`  int(11) NOT NULL AUTO_INCREMENT FIRST ;

CREATE TABLE `bdei_search_history` (
`search_id`  int(11) NULL AUTO_INCREMENT ,
`search_value`  varchar(250) NULL ,
`search_type`  tinyint(1) NULL ,
`search_time`  datetime NULL ,
PRIMARY KEY (`search_id`)
)
;

CREATE TABLE `bdei_config` (
`config_id`  int(11) NULL AUTO_INCREMENT ,
`config_name`  varchar(250) NULL ,
`config_unique_name`  varchar(50) NULL ,
`config_value`  varchar(250) NULL ,
`config_desc`  varchar(360) NULL ,
PRIMARY KEY (`config_id`)
)
;
CREATE TABLE `bdei_review` (
`review_id`  int(11) NULL AUTO_INCREMENT ,
`review_content`  text NULL ,
`user_id`  int(11) NULL ,
`review_time`  datetime NULL ,
`user_name`  varchar(60) NULL ,
PRIMARY KEY (`review_id`)
)
;

ALTER TABLE `bdei_review`
ADD COLUMN `book_id`  int(11) NULL AFTER `user_name`;
ALTER TABLE `bdei_review`
ADD INDEX `user_id` (`user_id`) ,
ADD INDEX `book_id` (`book_id`) ;

ALTER TABLE `bdei_search_history`
DROP COLUMN `ip`,
ADD COLUMN `ip`  char(15) NULL AFTER `search_time`,
ADD COLUMN `user_id`  int(11) NULL AFTER `ip`;
ALTER TABLE `bdei_search_history`
ADD INDEX `user_id` (`user_id`) ;

ALTER TABLE `bdei_review`
DROP COLUMN `ip`,
ADD COLUMN `ip`  char(15) NULL AFTER `book_id`;

CREATE TABLE `bdei_disable_ip` (
`id`  int(11) NULL AUTO_INCREMENT ,
`ip_address`  char(15) NULL ,
`start`  int(3) NULL ,
`end`  int(3) NULL ,
`add_time`  datetime NULL ,
PRIMARY KEY (`id`)
)
;

ALTER TABLE `bdei_disable_ip`
ADD COLUMN `one`  int(3) NULL AFTER `ip_address`,
ADD COLUMN `two`  int(3) NULL AFTER `one`,
ADD COLUMN `three`  int(3) NULL AFTER `two`;

ALTER TABLE `bdei_config`
MODIFY COLUMN `config_name`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `config_id`,
MODIFY COLUMN `config_unique_name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `config_name`,
MODIFY COLUMN `config_value`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `config_unique_name`,
MODIFY COLUMN `config_desc`  varchar(360) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `config_value`;

ALTER TABLE `bdei_config`
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;
ALTER TABLE `bdei_disable_ip`
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;
ALTER TABLE `bdei_disable_ip`
MODIFY COLUMN `ip_address`  char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `id`;

ALTER TABLE `bdei_review`
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `bdei_review`
MODIFY COLUMN `review_content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `review_id`,
MODIFY COLUMN `user_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `review_time`,
MODIFY COLUMN `ip`  char(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `book_id`;

ALTER TABLE `bdei_search_history`
MODIFY COLUMN `search_value`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `search_id`,
MODIFY COLUMN `ip`  char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `search_time`,
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;


