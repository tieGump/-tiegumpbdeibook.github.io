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
  `book_press` varchar(200) DEFAULT NULL COMMENT '??????',
  `book_isbn` varchar(64) DEFAULT NULL COMMENT '????ISBN????',
  `save_place` varchar(200) DEFAULT NULL,
  `book_cover` varchar(250) DEFAULT NULL COMMENT '??????',
  `status` tinyint(1) DEFAULT NULL,
  `read_number` int(11) DEFAULT NULL,
  `book_classification` varchar(10) DEFAULT NULL COMMENT '????',
  `book_classification_word` char(1) DEFAULT NULL COMMENT 'A-Z?????',
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
  `book_keyword` varchar(200) DEFAULT NULL COMMENT '?????',
  `book_key_words` varchar(200) DEFAULT NULL COMMENT '?????',
  `book_desc` varchar(248) DEFAULT NULL COMMENT '??????'
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
-- Records of bedei_administrator
-- ----------------------------
INSERT INTO `bedei_administrator` VALUES ('1', 'admin', '840600c989da4a9a702cc6de5c577b21:9T7', '2015-04-15', '2015-04-15');