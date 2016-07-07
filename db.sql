/*
Navicat MySQL Data Transfer

Source Server         : SERVER
Source Server Version : 50547
Source Host           : server.local:3306
Source Database       : banco_vazio

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2016-07-07 11:41:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `comments`
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_media` varchar(255) DEFAULT NULL,
  `id_comment` varchar(255) DEFAULT NULL,
  `tempo_criacao` varchar(255) DEFAULT NULL,
  `texto` text,
  `username` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `profile_id` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `link_media` varchar(255) DEFAULT NULL,
  `data_check` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of comments
-- ----------------------------

-- ----------------------------
-- Table structure for `likes`
-- ----------------------------
DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_media` varchar(11) DEFAULT NULL,
  `id_like` varchar(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `link_media` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of likes
-- ----------------------------

-- ----------------------------
-- Table structure for `medias`
-- ----------------------------
DROP TABLE IF EXISTS `medias`;
CREATE TABLE `medias` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `atribuicao` varchar(255) DEFAULT NULL,
  `tags` text,
  `tipo` varchar(255) DEFAULT NULL,
  `localizacao_id` varchar(100) DEFAULT NULL,
  `localizacao_name` varchar(255) DEFAULT NULL,
  `localizacao_lat` varchar(255) DEFAULT NULL,
  `localizacao_lon` varchar(255) DEFAULT NULL,
  `totalComentarios` int(100) DEFAULT NULL,
  `filtro` varchar(255) DEFAULT NULL,
  `tempo_criacao` varchar(100) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `totalLikes` int(100) DEFAULT NULL,
  `imagem_320` varchar(255) DEFAULT NULL,
  `imagem_150` varchar(255) DEFAULT NULL,
  `imagem_640` varchar(255) DEFAULT NULL,
  `tempo_criacao_rubrica` varchar(100) DEFAULT NULL,
  `texto_rubrica` text,
  `id_rubrica` varchar(100) DEFAULT NULL,
  `user_has_liked` varchar(255) DEFAULT NULL,
  `id_media` varchar(255) DEFAULT NULL,
  `user_username` varchar(255) DEFAULT NULL,
  `user_profile_picture` varchar(255) DEFAULT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `user_full_name` varchar(255) DEFAULT NULL,
  `users_in_photo` text,
  `data_check` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of medias
-- ----------------------------

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `media` int(11) DEFAULT NULL,
  `followed_by` int(11) DEFAULT NULL,
  `follows` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `data_check` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user
-- ----------------------------
