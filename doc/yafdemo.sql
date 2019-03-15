/*
Navicat MySQL Data Transfer

Source Server         : local_vm
Source Server Version : 50640
Source Host           : 192.168.16.128:3306
Source Database       : yafdemo

Target Server Type    : MYSQL
Target Server Version : 50640
File Encoding         : 65001

Date: 2019-03-15 20:12:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `cid` int(11) DEFAULT '0' COMMENT '分类id',
  `content` text COMMENT '内容',
  `create_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article
-- ----------------------------
INSERT INTO `article` VALUES ('1', '2', '1', '<p>3333</p>\r\n', '2019-02-25 16:57:49', '2019-02-25 16:57:49');
INSERT INTO `article` VALUES ('2', 'rrr', '1', '<p>rrrrrr</p>\r\n', '2019-02-25 16:58:04', '2019-02-25 16:58:04');

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '' COMMENT '分类名称',
  `ismulti` tinyint(4) DEFAULT '1',
  `create_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', '2', '1', '2019-02-25 16:55:28', '2019-02-25 16:55:28');

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `modulename` varchar(255) NOT NULL DEFAULT '' COMMENT '模块名',
  `controllername` varchar(255) NOT NULL DEFAULT '' COMMENT '控制器名',
  `actioname` varchar(255) NOT NULL DEFAULT '' COMMENT '操作名',
  `create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '22', '22', '22', '2019-02-25 16:37:15', '2019-02-25 16:37:15');
INSERT INTO `role` VALUES ('2', '44', '44', '44', '2019-02-25 16:38:12', '2019-02-25 16:38:12');
INSERT INTO `role` VALUES ('3', '3', '4', '4', '2019-02-25 16:50:19', '2019-02-25 16:50:19');
INSERT INTO `role` VALUES ('4', '1', '1', '1', '2019-02-25 16:52:29', '2019-02-25 16:52:29');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `create_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '2222', '2be9bd7a3434f7038ca27d1918de58bd', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('2', 'dddd', '11ddbaf3386aea1f2974eee984542152', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('3', '的顶顶顶顶顶多多多', 'b7bc2a2f5bb6d521e64c8974c143e9a0', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('4', '2', 'c81e728d9d4c2f636f067f89cc14862c', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('5', '2', 'c81e728d9d4c2f636f067f89cc14862c', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('6', '2', 'c81e728d9d4c2f636f067f89cc14862c', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('7', '2', 'c81e728d9d4c2f636f067f89cc14862c', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('8', '2', 'c81e728d9d4c2f636f067f89cc14862c', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('9', '2', 'c81e728d9d4c2f636f067f89cc14862c', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('10', '2', 'c81e728d9d4c2f636f067f89cc14862c', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('11', '2', 'c81e728d9d4c2f636f067f89cc14862c', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('12', '2', 'c81e728d9d4c2f636f067f89cc14862c', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('13', '2', 'c81e728d9d4c2f636f067f89cc14862c', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('14', '的顶顶顶顶顶多多多', 'b7bc2a2f5bb6d521e64c8974c143e9a0', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('15', '222', '2be9bd7a3434f7038ca27d1918de58bd', '2019-02-25 16:36:49', '2019-02-25 16:36:49');
INSERT INTO `user` VALUES ('16', '444444', '550a141f12de6341fba65b0ad0433500', '2019-02-25 16:52:47', '2019-02-25 16:52:47');

-- ----------------------------
-- Table structure for userrole
-- ----------------------------
DROP TABLE IF EXISTS `userrole`;
CREATE TABLE `userrole` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `create_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userrole
-- ----------------------------
INSERT INTO `userrole` VALUES ('2', '1', '1', '2019-02-25 16:52:58', '2019-02-25 16:52:58');
