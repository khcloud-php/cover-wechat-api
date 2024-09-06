/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : cover_wechat

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 06/09/2024 14:34:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cw_exception_logs
-- ----------------------------
DROP TABLE IF EXISTS `cw_exception_logs`;
CREATE TABLE `cw_exception_logs`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `env` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `project` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `request_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `request_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 280 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cw_files
-- ----------------------------
DROP TABLE IF EXISTS `cw_files`;
CREATE TABLE `cw_files`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` enum('file','video','image','audio') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `thumbnail_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `signature` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `width` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `height` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `duration` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_signature`(`signature`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 67 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文件' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cw_friends
-- ----------------------------
DROP TABLE IF EXISTS `cw_friends`;
CREATE TABLE `cw_friends`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `friend` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` enum('apply','verify') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'apply',
  `status` enum('check','pass','overdue') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'check',
  `is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `unread` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '未读消息数',
  `top` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '置顶时间',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '最新消息',
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最新消息时间',
  `display` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '显示',
  `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '隐藏',
  `muted` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '免打扰',
  `bg_file_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '背景图ID(对应cw_files里的ID)',
  `bg_file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '背景图路径',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `setting` json NOT NULL,
  `source` enum('mobile','wechat','group','qrcode','assistant') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mobile',
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `deleted_at` int(10) UNSIGNED NULL DEFAULT NULL,
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_owner_friend`(`owner`, `friend`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1039 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '好友' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cw_group_users
-- ----------------------------
DROP TABLE IF EXISTS `cw_group_users`;
CREATE TABLE `cw_group_users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role` enum('user','super','admin','assistant') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `invite_id` int(11) NOT NULL,
  `invite_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '群备注',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '我在本群昵称',
  `unread` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '未读消息数',
  `top` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '置顶时间',
  `display` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '显示',
  `muted` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '免打扰',
  `display_nickname` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '显示群成员昵称',
  `bg_file_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '背景图ID(对应cw_files里的ID)',
  `bg_file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '背景图路径',
  `setting` json NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `deleted_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_group_id_user_id`(`group_id`, `user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 179 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群成员' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cw_groups
-- ----------------------------
DROP TABLE IF EXISTS `cw_groups`;
CREATE TABLE `cw_groups`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '群主',
  `send_user` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送最新消息的用户ID',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '最新消息',
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送最新消息时间',
  `notice_user` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `noticed_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `setting` json NOT NULL,
  `deleted_at` int(10) NULL DEFAULT NULL,
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 39 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cw_messages
-- ----------------------------
DROP TABLE IF EXISTS `cw_messages`;
CREATE TABLE `cw_messages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `from_user` int(11) NOT NULL DEFAULT 0 COMMENT '发送者',
  `to_user` int(11) NOT NULL DEFAULT 0 COMMENT '接受收者',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '消息内容，如果为文件或图片就是url',
  `type` enum('text','image','video','audio','emoji','file') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text' COMMENT '消息类型：text、file、image...',
  `is_group` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '群聊消息',
  `is_undo` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回',
  `is_tips` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否提示信息',
  `at_users` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '提及某人',
  `pid` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '引用的消息ID',
  `file_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件id',
  `file_type` enum('file','video','image','audio') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '文件类型',
  `file_size` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名称',
  `extends` json NULL COMMENT '消息扩展内容',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送时间',
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `deleted_users` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '已删除成员',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_from_to`(`from_user`, `to_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 517 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '消息' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for cw_moment_comments
-- ----------------------------
DROP TABLE IF EXISTS `cw_moment_comments`;
CREATE TABLE `cw_moment_comments`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `moment_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '朋友圈ID',
  `from_user` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '来自用户ID',
  `to_user` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '回复用户ID',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '回复消息',
  `is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `deleted_at` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_moment_id`(`moment_id`) USING BTREE,
  INDEX `idx_from_user`(`from_user`) USING BTREE,
  INDEX `idx_to_user`(`to_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for cw_moment_files
-- ----------------------------
DROP TABLE IF EXISTS `cw_moment_files`;
CREATE TABLE `cw_moment_files`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `moment_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '朋友圈ID',
  `file_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件ID',
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_unique`(`moment_id`, `file_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for cw_moment_likes
-- ----------------------------
DROP TABLE IF EXISTS `cw_moment_likes`;
CREATE TABLE `cw_moment_likes`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `moment_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_unique`(`user_id`, `moment_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for cw_moments
-- ----------------------------
DROP TABLE IF EXISTS `cw_moments`;
CREATE TABLE `cw_moments`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
  `type` enum('text','image','video') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'text' COMMENT '类型',
  `content` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文案',
  `visible` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '谁可以看',
  `invisible` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '谁不可以看',
  `perm` enum('public','private','visible','invisible') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'public' COMMENT '权限',
  `unread` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '未读消息数',
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `deleted_at` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for cw_users
-- ----------------------------
DROP TABLE IF EXISTS `cw_users`;
CREATE TABLE `cw_users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `wechat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mobile` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `salt` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `token_expire_in` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nickname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sign` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `gender` enum('male','female','unknown') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown',
  `bg_file_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `bg_file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `moment_bg_file_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `moment_bg_file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` enum('normal','ban') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `setting` json NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_wechat`(`wechat`) USING BTREE,
  UNIQUE INDEX `uk_mobile`(`mobile`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1001 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
