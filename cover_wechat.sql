/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : cover_wechat

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 27/06/2024 16:48:02
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
) ENGINE = MyISAM AUTO_INCREMENT = 151 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cw_exception_logs
-- ----------------------------
INSERT INTO `cw_exception_logs` VALUES (1, 'local', 'Lumen', 'http://localhost:8000/friends/apply', '::1', '824e33fb9d266d0ee5be34dce5337335', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'owner\' in \'where clause\' (Connection: mysql, SQL: select * from `cw_users` where `owner` = 7 and `friend` = 5 limit 1) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (2, 'local', 'Lumen', 'http://localhost:8000/friends/list', '::1', '09852447d19f54e8dea2657ca0dea3d4', ' App\\Http\\Controllers\\Controller::success(): Argument #1 ($data) must be of type array, Illuminate\\Database\\Eloquent\\Collection given, called in D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\FriendController.php on line 33 \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\Controller.php \n line: 21', 0);
INSERT INTO `cw_exception_logs` VALUES (3, 'local', 'Lumen', 'http://localhost:8000/friends/list', '::1', '386cf0dc6474866ca73a44b743ca3d25', ' App\\Http\\Controllers\\Controller::success(): Argument #1 ($data) must be of type array, Illuminate\\Database\\Eloquent\\Collection given, called in D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\FriendController.php on line 33 \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\Controller.php \n line: 21', 0);
INSERT INTO `cw_exception_logs` VALUES (4, 'local', 'Lumen', 'http://localhost:8000/friends/list', '::1', 'd4985c48e03f92aee11071d24199bb22', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type1\' in \'where clause\' (Connection: mysql, SQL: select * from `cw_friends` where `owner` = 5 and `type1` = verify and `status` = pass and `cw_friends`.`deleted_at` is null) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (5, 'local', 'Lumen', 'http://localhost:8000/friends/list', '::1', 'd4124568e3d004e9d55ca6b664702735', ' App\\Models\\Friend::friend(): Return value must be of type Illuminate\\Database\\Eloquent\\Relations\\BelongsTo, App\\Models\\User returned \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Models\\Friend.php \n line: 24', 0);
INSERT INTO `cw_exception_logs` VALUES (6, 'local', 'Lumen', 'http://localhost:8000/friends/list', '::1', '3ce069bde539b69f3b113b47d1c1e4fe', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'cw_users.friend\' in \'where clause\' (Connection: mysql, SQL: select * from `cw_users` where `cw_users`.`friend` in (3)) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (7, 'local', 'Lumen', 'http://localhost:8000/friends/list', '::1', 'be859562a4a49867b9224764623dbf4d', ' App\\Models\\Friend::friend(): Return value must be of type Illuminate\\Database\\Eloquent\\Relations\\HasOne, Illuminate\\Database\\Eloquent\\Relations\\BelongsTo returned \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Models\\Friend.php \n line: 24', 0);
INSERT INTO `cw_exception_logs` VALUES (8, 'local', 'Lumen', 'http://localhost:8000/friends/list', '::1', 'f50f294d4ef58b91adb0b14c12158fa8', ' App\\Models\\Friend::friend(): Return value must be of type Illuminate\\Database\\Eloquent\\Relations\\HasOne, Illuminate\\Database\\Eloquent\\Relations\\BelongsTo returned \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Models\\Friend.php \n line: 24', 0);
INSERT INTO `cw_exception_logs` VALUES (9, 'local', 'Lumen', 'http://localhost:8000/friends/apply-list', '::1', 'de63e4f84745aff2d35c3bfc0b75a7b1', ' A non-numeric value encountered \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 37', 0);
INSERT INTO `cw_exception_logs` VALUES (10, 'local', 'Lumen', 'http://localhost:8000/friends/list', '::1', '5f263ebd30f37423e48ac5743cf7c329', ' Uninitialized string offset 0 \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Support\\helpers.php \n line: 35', 0);
INSERT INTO `cw_exception_logs` VALUES (11, 'local', 'Lumen', 'http://localhost:8000/friends/apply-list', '::1', 'c364897acd3c9075c06e9ec6c5bdf849', ' A non-numeric value encountered \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 37', 0);
INSERT INTO `cw_exception_logs` VALUES (12, 'local', 'Lumen', 'http://localhost:8000/friends/list', '::1', '21a0c7afde17b17f0aad54fb49a79023', ' Uninitialized string offset 0 \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Support\\helpers.php \n line: 35', 0);
INSERT INTO `cw_exception_logs` VALUES (13, 'local', 'Lumen', 'http://localhost:8000/friends/apply-list', '::1', 'c44e01bb40f77ac0c88f4560e5e7bbf9', ' A non-numeric value encountered \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 37', 0);
INSERT INTO `cw_exception_logs` VALUES (14, 'local', 'Lumen', 'http://localhost:8000/friends/apply-list', '::1', '965ce3db126b3a5c8e24a8d9009457a1', ' A non-numeric value encountered \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 37', 0);
INSERT INTO `cw_exception_logs` VALUES (15, 'local', 'Lumen', 'http://localhost:8000/friends/apply-list', '::1', '396ea0dd8283c818b3c8d37249642a32', ' A non-numeric value encountered \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 37', 0);
INSERT INTO `cw_exception_logs` VALUES (16, 'local', 'Lumen', 'http://localhost:8000/friends/apply-list', '::1', '33d9959449372cd1132c0f19260aaa28', ' A non-numeric value encountered \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 38', 0);
INSERT INTO `cw_exception_logs` VALUES (17, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '2de6febcde766a901df363b1e94a79d1', ' Undefined array key \"user_id\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 81', 0);
INSERT INTO `cw_exception_logs` VALUES (18, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', 'eacd80f286801f897f1198bd38aab54d', ' Undefined array key \"user_id\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 81', 0);
INSERT INTO `cw_exception_logs` VALUES (19, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', 'd253caf2952ad2450b47dd9ba846d727', ' Attempt to read property \"source\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 94', 0);
INSERT INTO `cw_exception_logs` VALUES (20, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '40480c2c8ef36a5c1a0e90bfce9726f3', ' Attempt to read property \"source\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 89', 0);
INSERT INTO `cw_exception_logs` VALUES (21, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', 'cc263560342028aca23ad022f19d5955', ' Illegal offset type \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 90', 0);
INSERT INTO `cw_exception_logs` VALUES (22, 'local', 'Lumen', 'http://localhost:8000/friends/search/13006789517', '::1', 'c67670536365270b974e29549b3f0e0a', ' syntax error, unexpected token \"(\", expecting \";\" or \"{\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\FriendController.php \n line: 78', 0);
INSERT INTO `cw_exception_logs` VALUES (23, 'local', 'Lumen', 'http://localhost:8000/friends/search/13006789517', '::1', '2652c4a88bae9e88f9f324fe1c7c8622', ' syntax error, unexpected token \"(\", expecting \";\" or \"{\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\FriendController.php \n line: 78', 0);
INSERT INTO `cw_exception_logs` VALUES (24, 'local', 'Lumen', 'http://localhost:8000/friends/show-confirm', '::1', '34ba17e73f63b1a75f12017892ee9d9b', ' Unable to resolve dependency [Parameter #0 [ <required> $id ]] in class App\\Http\\Controllers\\FriendController \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\container\\BoundMethod.php \n line: 188', 0);
INSERT INTO `cw_exception_logs` VALUES (25, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '68d59ff92e2524fdb198291a20b66d83', ' Attempt to read property \"source\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 101', 0);
INSERT INTO `cw_exception_logs` VALUES (26, 'local', 'Lumen', 'http://localhost:8000/friends/apply-list', '::1', '7a7916fe5ad04655a4f21c167a60429a', ' Call to undefined relationship [user] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 0);
INSERT INTO `cw_exception_logs` VALUES (27, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '99dddd4baa053d63de106cddce2a44a7', ' Attempt to read property \"source\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 101', 0);
INSERT INTO `cw_exception_logs` VALUES (28, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '885b9a31af6abea7bbc7c0270f7a1881', ' Attempt to read property \"source\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 101', 0);
INSERT INTO `cw_exception_logs` VALUES (29, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '0f9897986703f88f56a393e88112cbad', ' Attempt to read property \"source\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 101', 0);
INSERT INTO `cw_exception_logs` VALUES (30, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', 'c3bc430a0a70e48668b8be1ee17e4763', ' Attempt to read property \"source\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 101', 0);
INSERT INTO `cw_exception_logs` VALUES (31, 'local', 'Lumen', 'http://localhost:8000/users/logout', '::1', '6f23c60af4af3dce8966c83c6f0419cc', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 71', 0);
INSERT INTO `cw_exception_logs` VALUES (32, 'local', 'Lumen', 'http://localhost:8000/users/logout', '::1', '1a44c1a0c97264da9d1392c4d23fc6c7', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 71', 0);
INSERT INTO `cw_exception_logs` VALUES (33, 'local', 'Lumen', 'http://localhost:8000/users/logout', '::1', 'ea81089eea3830c2508d63ba186d77a0', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 71', 0);
INSERT INTO `cw_exception_logs` VALUES (34, 'local', 'Lumen', 'http://localhost:8000/friends/apply-list', '::1', '549c20bb0e3aabe2fd6d290629cde949', ' Undefined array key \"mobile\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 48', 0);
INSERT INTO `cw_exception_logs` VALUES (35, 'local', 'Lumen', 'http://localhost:8000/users/logout', '::1', '8f7c5c20c63b31e746e674cdebba010d', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 71', 0);
INSERT INTO `cw_exception_logs` VALUES (36, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '61207ef8f5d844b88d5c121e89638f9a', ' Cache store [user] is not defined. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\cache\\CacheManager.php \n line: 88', 0);
INSERT INTO `cw_exception_logs` VALUES (37, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', 'd0e7bc701f8acf685c490d337f43f804', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (38, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '2a7826b771b8a65255de13a48416bd1a', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (39, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '3afdff7f9fcd236740a308e2e5e6b31f', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (40, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', 'd4fd021296988c3618996b3f0d55d4ab', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (41, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '97a0591ec154ade0e69e57e4a8971b66', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (42, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '7e6f62a68b6ace3532f6c1947e6d2334', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (43, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', 'f2e75400522a607d528756d8684655a1', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (44, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '4581fbd7232591462ca9b8c6a3f06fbf', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (45, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '0664da7e1f448e2b3a4343616cfef9ad', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (46, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', 'bca1b96c944cdde7ff043bc46a597bbb', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (47, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '8c813633a9f6c9e91565173adb20f4e6', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (48, 'local', 'Lumen', 'http://localhost:8000/users/13006789516/home', '::1', '3c53b55ce578fa89e630219ea143d2de', ' Call to a member function connect() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\redis\\RedisManager.php \n line: 110', 0);
INSERT INTO `cw_exception_logs` VALUES (49, 'local', 'Lumen', 'http://localhost:8000/users/13006789515/home', '::1', '3a8719ad8ef6fa32693fb9b75cd6f498', ' Attempt to read property \"created_at\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 102', 0);
INSERT INTO `cw_exception_logs` VALUES (50, 'local', 'Lumen', 'http://localhost:8000/users/13006789515/home', '::1', 'ce41c5ab9483394adbb70134377fff59', ' Attempt to read property \"created_at\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 102', 0);
INSERT INTO `cw_exception_logs` VALUES (51, 'local', 'Lumen', 'http://localhost:8000/users/13006789515/home', '::1', '0282df98182c17dd542d3f96ab787173', ' Attempt to read property \"created_at\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 102', 0);
INSERT INTO `cw_exception_logs` VALUES (52, 'local', 'Lumen', 'http://localhost:8000/users/13006789515/home', '::1', '2f2ba21d2a0d4d570d950a89497106d0', ' Attempt to read property \"created_at\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\UserService.php \n line: 102', 0);
INSERT INTO `cw_exception_logs` VALUES (53, 'local', 'Lumen', 'http://localhost:8000/friends/list', '::1', '5f8ac25a5e400ef58b778dc43b58b8dc', ' Undefined array key \"mobile\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 27', 0);
INSERT INTO `cw_exception_logs` VALUES (54, 'local', 'Lumen', 'http://localhost:8000/users/logout', '::1', '701b0dbfe105ef7489ece5db1cba46d8', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 72', 0);
INSERT INTO `cw_exception_logs` VALUES (55, 'local', 'Lumen', 'http://localhost:8000/users/logout', '::1', 'b1f0ca5985d65ed0bdc53142dbadb8d3', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 72', 0);
INSERT INTO `cw_exception_logs` VALUES (56, 'local', 'Lumen', 'http://localhost:8000/users/logout', '::1', '24f91be82a4e38df2577fb451ab05af5', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 72', 0);
INSERT INTO `cw_exception_logs` VALUES (57, 'local', 'Lumen', 'http://localhost:8000/messages/send', '::1', '506bfe1378e8a29e486506038fbebb0a', ' Undefined array key \"at_users\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 87', 0);
INSERT INTO `cw_exception_logs` VALUES (58, 'local', 'Lumen', 'http://localhost:8000/messages/send', '::1', 'c5f65ff199f40a47e79e3569fbb0c197', ' Call to a member function toArray() on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 107', 0);
INSERT INTO `cw_exception_logs` VALUES (59, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '938f2166c35d7f0a70b677bee0ce2cd2', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'cw_users.from_user\' in \'where clause\' (Connection: mysql, SQL: select * from `cw_users` where `cw_users`.`from_user` in (7, 8, 9)) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (60, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '6176449aeb05e304a9451d2258c888be', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'count(is_read)\' in \'field list\' (Connection: mysql, SQL: select `count(is_read)` as `unread`, `from_user` from `cw_messages` where `from_user` in (8, 8, 8) and `to_user` = 6 and `unread` = 0 group by `from_user`, `to_user`) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (61, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '6977a1de2ee1bae1671a080dd825421f', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'count(is_read)\' in \'field list\' (Connection: mysql, SQL: select `count(is_read)` as `unread`, `from_user` from `cw_messages` where `from_user` in (8, 8, 8) and `to_user` = 6 and `unread` = 0 group by `from_user`, `to_user`) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (62, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'a91b5cc93e806d387303095ad3478a67', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'unread\' in \'where clause\' (Connection: mysql, SQL: select count(is_read) as unread,from_user from `cw_messages` where `from_user` in (8, 8, 8) and `to_user` = 6 and `unread` = 0 group by `from_user`, `to_user`) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (63, 'local', 'Lumen', 'http://localhost:8000/messages/send', '::1', 'ba602fa71cec22c40a2c4201895eadf7', ' Non-static method App\\Models\\Friend::checkIsFriend() cannot be called statically \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 30', 0);
INSERT INTO `cw_exception_logs` VALUES (64, 'local', 'Lumen', 'http://localhost:8000/messages/send', '::1', 'd4628ece018aa9c9d802def5e77a1505', ' App\\Services\\MessageService::send(): Return value must be of type array, App\\Models\\Message returned \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 129', 0);
INSERT INTO `cw_exception_logs` VALUES (65, 'local', 'Lumen', 'http://localhost:8000/messages/send', '::1', '2e94b4c9dece1e0f2b5c9c5cd251012b', ' App\\Services\\MessageService::send(): Return value must be of type array, App\\Models\\Message returned \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 129', 0);
INSERT INTO `cw_exception_logs` VALUES (66, 'local', 'Lumen', 'http://localhost:8000/messages/send', '::1', 'e329d5f9cd0211aa5a44a46efb19722d', ' App\\Services\\MessageService::send(): Return value must be of type array, App\\Models\\Message returned \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 129', 0);
INSERT INTO `cw_exception_logs` VALUES (67, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '2d1da3621a20bfe68afe2a3dcaae25ce', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'is_read1\' in \'field list\' (Connection: mysql, SQL: select count(is_read1) as unread,from_user from `cw_messages` where `from_user` in (6) and `to_user` = 8 and `is_read` = 0 group by `from_user`, `to_user`) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (68, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '3f8bd1de3950e73dbebc913fe2d6c078', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'nickname1\' in \'field list\' (Connection: mysql, SQL: select `id`, `nickname1`, `avatar` from `cw_users` where `id` in (6)) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (69, 'local', 'Lumen', 'http://localhost:8000/friend/search/13006789517', '::1', '8b9a84b075f251324b1bfa34fb65eec7', ' Undefined variable $fileds \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 88', 0);
INSERT INTO `cw_exception_logs` VALUES (70, 'local', 'Lumen', 'http://localhost:8000/friend/search/13006789517', '::1', 'f238806106d7d6bc095419e6c109ec99', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'929a4c6e30416dfd2781d00c6e0c2107\' in \'where clause\' (Connection: mysql, SQL: select `id`, `nickname`, `avatar` from `cw_users` where MD5(mobile) = 929a4c6e30416dfd2781d00c6e0c2107 and json_contains(`setting`, {\"FriendPerm\":{\"AddMyWay\":{\"Mobile\":1}}})) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (71, 'local', 'Lumen', 'http://localhost:8000/friend/search/13006789517', '::1', '127aba7ee7416f32081731c0f6fbac7b', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'929a4c6e30416dfd2781d00c6e0c2107\' in \'where clause\' (Connection: mysql, SQL: select `id`, `nickname`, `avatar` from `cw_users` where MD5(mobile) = 929a4c6e30416dfd2781d00c6e0c2107 and json_contains(`setting`, {\"FriendPerm\":{\"AddMyWay\":{\"Mobile\":1}}})) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (72, 'local', 'Lumen', 'http://localhost:8000/friend/search/13006789517', '::1', '014f63e1c73ccf3816a0a467f7402455', ' SQLSTATE[HY093]: Invalid parameter number (Connection: mysql, SQL: select `id`, `nickname`, `avatar` from `cw_users` where mobile and json_contains(`setting`, 13006789517)) \n code: HY093 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 0);
INSERT INTO `cw_exception_logs` VALUES (73, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '198679929d6c6b53f2747304f4f75279', ' Undefined array key \"friend\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Support\\helpers.php \n line: 29', 1718782511);
INSERT INTO `cw_exception_logs` VALUES (74, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '5e895086bbccc649d788675f50bc9028', ' Undefined array key \"friend\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Support\\helpers.php \n line: 29', 1718782540);
INSERT INTO `cw_exception_logs` VALUES (75, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '1a22baae7a156a40ac89fae5e5fd0dc9', ' Undefined array key \"friend\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Support\\helpers.php \n line: 29', 1718782547);
INSERT INTO `cw_exception_logs` VALUES (76, 'local', 'Lumen', 'http://localhost:8000/group/create', '::1', '0110795b3e6c588b757c4e02e3b34c91', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718788219);
INSERT INTO `cw_exception_logs` VALUES (77, 'local', 'Lumen', 'http://localhost:8000/group/create', '::1', 'd7cb77bb2da75c2dc5ffe9d5b5ed67c8', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718788550);
INSERT INTO `cw_exception_logs` VALUES (78, 'local', 'Lumen', 'http://localhost:8000/group/create', '::1', '8f0c6e156438095b0b1452d0c6a936c3', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718788560);
INSERT INTO `cw_exception_logs` VALUES (79, 'local', 'Lumen', 'http://localhost:8000/friend/apply-list', '::1', '675e547066fa79c3cb4e50ebd366a60a', ' Call to undefined relationship [owner] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1718789175);
INSERT INTO `cw_exception_logs` VALUES (80, 'local', 'Lumen', 'http://localhost:8000/friend/apply-list', '::1', 'c2c1ccb98a277a2329ef31ac185ffc03', ' Call to undefined relationship [owner] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1718789182);
INSERT INTO `cw_exception_logs` VALUES (81, 'local', 'Lumen', 'http://localhost:8000/friend/apply-list', '::1', '1a9d09ed7ef8efcb8cd50da29b94feb4', ' Trying to access array offset on value of type int \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 52', 1718789276);
INSERT INTO `cw_exception_logs` VALUES (82, 'local', 'Lumen', 'http://localhost:8000/friend/apply-list', '::1', '7c2c4f7a817aea01701b41cfbef42bd8', ' Trying to access array offset on value of type int \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 51', 1718789293);
INSERT INTO `cw_exception_logs` VALUES (83, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', 'fd116f9e92c50ace7bc2b2161f6c19b6', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718789618);
INSERT INTO `cw_exception_logs` VALUES (84, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '8e2159e69e774ee57fe8a7aff4a77820', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718789674);
INSERT INTO `cw_exception_logs` VALUES (85, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '9d1f2cc94ccf2b4f163ec0c79af37317', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718789686);
INSERT INTO `cw_exception_logs` VALUES (86, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', 'da29a9f0f3942a6f32f00e41a83d2efe', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718789688);
INSERT INTO `cw_exception_logs` VALUES (87, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '656a57265fa4260ab6cd02d416d0631d', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718789691);
INSERT INTO `cw_exception_logs` VALUES (88, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '70c54dad0e04ec900afbb8619aa5bbd6', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718789694);
INSERT INTO `cw_exception_logs` VALUES (89, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '8b63de762982156bd8fc9e86470e3e9e', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718789700);
INSERT INTO `cw_exception_logs` VALUES (90, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', 'b2046260956a1750e192b00a10f3063d', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1718789719);
INSERT INTO `cw_exception_logs` VALUES (91, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '32830279373e9480f70e4baf094a0499', ' SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'\' at line 1 (Connection: mysql, SQL: select `id`, `from_user`, `to_user`, `content`, `type`, `is_group`, `is_undo`, `at_users`, `pid`, `created_at` from `cw_messages` where (from_user = 7 OR to_user = 7) and (FIND_IN_SET(7, deleted_users) is null OR (FIND_IN_SET(7, deleted_users) = \'\') and `is_last` = 1) \n code: 42000 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1718847954);
INSERT INTO `cw_exception_logs` VALUES (92, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '7653a8a84cfcd4a9c059d715f9e9d8a7', ' SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'\' at line 1 (Connection: mysql, SQL: select `id`, `from_user`, `to_user`, `content`, `type`, `is_group`, `is_undo`, `at_users`, `pid`, `created_at` from `cw_messages` where (from_user = 7 OR to_user = 7) and (FIND_IN_SET(7, deleted_users) is null OR (FIND_IN_SET(7, deleted_users) = \'\') and `is_last` = 1) \n code: 42000 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1718847960);
INSERT INTO `cw_exception_logs` VALUES (93, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'd701969439f8553b994843439523567d', ' SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'\' at line 1 (Connection: mysql, SQL: select `id`, `from_user`, `to_user`, `content`, `type`, `is_group`, `is_undo`, `at_users`, `pid`, `created_at` from `cw_messages` where (from_user = 7 OR to_user = 7) and ((FIND_IN_SET(7, deleted_users) is null OR (FIND_IN_SET(7, deleted_users) = \'\')) and `is_last` = 1) \n code: 42000 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1718848011);
INSERT INTO `cw_exception_logs` VALUES (94, 'local', 'Lumen', 'http://localhost:8000/message/read', '::1', 'b43dad30ae3eaccb9d2e8454b9281c7b', ' syntax error, unexpected token \",\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 136', 1718848118);
INSERT INTO `cw_exception_logs` VALUES (95, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=0&to_user=8', '::1', '58bfb95d3ed56e1d509f02aea6db906f', ' syntax error, unexpected token \",\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 136', 1718848118);
INSERT INTO `cw_exception_logs` VALUES (96, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=0&to_user=8', '::1', '58c602f2fe90942fca744d30564d4fe7', ' SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'order by `created_at` asc\' at line 1 (Connection: mysql, SQL: select * from `cw_messages` where ((from_user = 7 AND to_user = 8) OR (from_user = 8 AND to_user = 7)) and (FIND_IN_SET(7, deleted_users) is null OR (FIND_IN_SET(7, deleted_users) = \'\') order by `created_at` asc) \n code: 42000 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1718848139);
INSERT INTO `cw_exception_logs` VALUES (97, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=0&to_user=8', '::1', 'df74924f4594006afcd45d8444ded010', ' Undefined array key \"from_user\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 136', 1718848165);
INSERT INTO `cw_exception_logs` VALUES (98, 'local', 'Lumen', 'http://localhost:8000/group/create', '::1', '8a2fe046633ea6724f9a0bbbf55f8642', ' Call to undefined relationship [to] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1718849224);
INSERT INTO `cw_exception_logs` VALUES (99, 'local', 'Lumen', 'http://localhost:8000/user/logout', '::1', 'b0a8ddf5cd1c615ad4b8f363eaf82f94', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 72', 1718849770);
INSERT INTO `cw_exception_logs` VALUES (100, 'local', 'Lumen', 'http://localhost:8000/user/logout', '::1', 'f059214359a2b8b21eb91d7b3ee9fbd9', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 72', 1718849773);
INSERT INTO `cw_exception_logs` VALUES (101, 'local', 'Lumen', 'http://localhost:8000/user/logout', '::1', '4c69cfe322e14e084877ee363e7dc4ba', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 72', 1718849792);
INSERT INTO `cw_exception_logs` VALUES (102, 'local', 'Lumen', 'http://localhost:8000/user/logout', '::1', '3c11c100fa73fcfc68609f94e4d9e352', ' Undefined array key \"user\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 72', 1718849850);
INSERT INTO `cw_exception_logs` VALUES (103, 'local', 'Lumen', 'http://localhost:8000/group/create', '::1', '06b1a7d22fea88161db66254f35bd7ba', ' Attempt to read property \"name\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Workerman\\Action\\Group.php \n line: 31', 1718854270);
INSERT INTO `cw_exception_logs` VALUES (104, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=1&to_user=18', '::1', '04de9c7962b877ccdd247cbae90c14b2', ' Undefined variable $item \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 107', 1718854882);
INSERT INTO `cw_exception_logs` VALUES (105, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=1&to_user=19', '::1', '98339da28dda5e5796c2778ea0f8956a', ' Undefined variable $item \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 107', 1718855118);
INSERT INTO `cw_exception_logs` VALUES (106, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=1&to_user=19', '::1', 'c2e4e606b10157cf6cc6721c9b02a386', ' Undefined variable $item \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 107', 1718855847);
INSERT INTO `cw_exception_logs` VALUES (107, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '7f01ccf70f53cf7285db6d06ecdf2b85', ' Array to string conversion \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\ChatService.php \n line: 21', 1718863875);
INSERT INTO `cw_exception_logs` VALUES (108, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '00b8c4daa333aba8a8e433929d7d499c', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'deleted_users1\' in \'where clause\' (Connection: mysql, SQL: select `id`, `from_user`, `to_user`, `content`, `type`, `is_group`, `is_undo`, `at_users`, `pid`, `created_at` from `cw_messages` where ((from_user = 7 OR to_user = 7) AND is_group = 0) and (FIND_IN_SET(7, deleted_users1) = \'\') and `is_last` = 1) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1718863927);
INSERT INTO `cw_exception_logs` VALUES (109, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '8ac598e39b15c6689a521165d528bec1', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'deleted_users1\' in \'where clause\' (Connection: mysql, SQL: select `id`, `from_user`, `to_user`, `content`, `type`, `is_group`, `is_undo`, `at_users`, `pid`, `created_at` from `cw_messages` where ((from_user = 7 OR to_user = 7) AND is_group = 0) and (FIND_IN_SET(7, deleted_users1) = \'\') and `is_last` = 1) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1718864188);
INSERT INTO `cw_exception_logs` VALUES (110, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'ef5592ec9adf7c2cf969d89fdcbd3a14', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'deleted_users1\' in \'where clause\' (Connection: mysql, SQL: select `id`, `from_user`, `to_user`, `content`, `type`, `is_group`, `is_undo`, `at_users`, `pid`, `created_at` from `cw_messages` where ((from_user = 7 OR to_user = 7) AND is_group = 0) and (FIND_IN_SET(7, deleted_users1) = \'\') and `is_last` = 1) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1718864206);
INSERT INTO `cw_exception_logs` VALUES (111, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '1080a4ddc2603dadb7ccc33b1b65081f', ' Undefined array key 0 \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Workerman\\Action\\User.php \n line: 52', 1718871739);
INSERT INTO `cw_exception_logs` VALUES (112, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '967d287c1cb4b200508d02e07dadee2c', ' Undefined array key 0 \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Workerman\\Action\\User.php \n line: 52', 1718871744);
INSERT INTO `cw_exception_logs` VALUES (113, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'da04b49fef365d979ce51d7255d46925', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'id1\' in \'where clause\' (Connection: mysql, SQL: select `id`, `name` from `cw_groups` where `id1` in (19) and `cw_groups`.`deleted_at` is null) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1718872390);
INSERT INTO `cw_exception_logs` VALUES (114, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'ba1f24342d0d5aeaa57cd1a17b38e787', ' Undefined array key 0 \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Workerman\\Action\\User.php \n line: 52', 1718872474);
INSERT INTO `cw_exception_logs` VALUES (115, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '6baec9001aa25f03c91403f110332817', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1719373613);
INSERT INTO `cw_exception_logs` VALUES (116, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'a7b9b08a5c97b0f5eb779b209d0f4b41', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1719373706);
INSERT INTO `cw_exception_logs` VALUES (117, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'c33faed25b698f9c4161845f6827a49b', ' Undefined property: App\\Models\\Friend::$friend \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\Relations\\BelongsTo.php \n line: 378', 1719373811);
INSERT INTO `cw_exception_logs` VALUES (118, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '70f025c3871de39dca5a1e1d3b2e1a2e', ' Trying to access array offset on value of type null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\ChatService.php \n line: 35', 1719373893);
INSERT INTO `cw_exception_logs` VALUES (119, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'f19df2a926ba729cdef7ba54c9357e47', ' Trying to access array offset on value of type int \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\ChatService.php \n line: 35', 1719373988);
INSERT INTO `cw_exception_logs` VALUES (120, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'f13faf9bd3faba8823835f589dafcc14', ' Undefined array key \"avatar\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\ChatService.php \n line: 37', 1719374315);
INSERT INTO `cw_exception_logs` VALUES (121, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '8d5fd3613a5ee17b4f08c2bc1982877e', ' Trying to access array offset on value of type null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\ChatService.php \n line: 37', 1719374370);
INSERT INTO `cw_exception_logs` VALUES (122, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'e118f9eb0f0115b577bdd24bfaa58273', ' Trying to access array offset on value of type null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\ChatService.php \n line: 37', 1719374386);
INSERT INTO `cw_exception_logs` VALUES (123, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '8ff58ae38eb8f6c174f3a13c56f0ce28', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'from_user\' in \'field list\' (Connection: mysql, SQL: select `unread`, `top`, `from_user`, `group_id` from `cw_group_users` where `user_id` = 7 and `display` = 1) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1719382289);
INSERT INTO `cw_exception_logs` VALUES (124, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '8bd05b41cd04a332ed05450588e92750', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'send_user\' in \'field list\' (Connection: mysql, SQL: select `unread`, `top`, `send_user`, `group_id` from `cw_group_users` where `user_id` = 7 and `display` = 1) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1719382322);
INSERT INTO `cw_exception_logs` VALUES (125, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '86dc58d8249c746b4719c959b5560ff7', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'avatar\' in \'field list\' (Connection: mysql, SQL: select `avatar`, `group_id` from `cw_group_users` where `cw_group_users`.`id` in (?) order by `created_at` asc limit 4) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1719382514);
INSERT INTO `cw_exception_logs` VALUES (126, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '332b0ff4c2021679f79f133a3a7cf446', ' Undefined array key \"send\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\ChatService.php \n line: 59', 1719469923);
INSERT INTO `cw_exception_logs` VALUES (127, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'beb8f049b3bced8349fcf2b29305a27a', ' Undefined array key \"send\" \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\ChatService.php \n line: 59', 1719469930);
INSERT INTO `cw_exception_logs` VALUES (128, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', 'cf4b94545fc248f2a2666b184f04db7e', ' Trying to access array offset on value of type null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\ChatService.php \n line: 59', 1719470003);
INSERT INTO `cw_exception_logs` VALUES (129, 'local', 'Lumen', 'http://localhost:8000/chat/list', '::1', '2d89e08db7c6e0908025e4df5db7554c', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'cw_users.send_user\' in \'where clause\' (Connection: mysql, SQL: select `nickname` from `cw_users` where `cw_users`.`send_user` in (20)) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1719470163);
INSERT INTO `cw_exception_logs` VALUES (130, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=1&to_user=20', '::1', 'f0f3fe52311356bf134ada6c0fa52c31', ' Undefined variable $item \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 107', 1719470484);
INSERT INTO `cw_exception_logs` VALUES (131, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=1&to_user=20', '::1', 'adaf9b07bd804c4cd234903fa9a2912b', ' Undefined variable $item \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 107', 1719470486);
INSERT INTO `cw_exception_logs` VALUES (132, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=1&to_user=20', '::1', 'f65973708568a838c5e9f62a4d3f416e', ' Undefined variable $item \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 107', 1719470877);
INSERT INTO `cw_exception_logs` VALUES (133, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=1&to_user=20', '::1', '7aa9db6ceca439efd56ee72790f217f2', ' Undefined variable $item \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 107', 1719470883);
INSERT INTO `cw_exception_logs` VALUES (134, 'local', 'Lumen', 'http://localhost:8000/message/read', '::1', '327c895f2f7b0590d5f1de2eea34e693', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'is_read\' in \'field list\' (Connection: mysql, SQL: update `cw_messages` set `is_read` = 1, `cw_messages`.`updated_at` = 1719472238 where `from_user` = 20 and `to_user` = 7) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1719472238);
INSERT INTO `cw_exception_logs` VALUES (135, 'local', 'Lumen', 'http://localhost:8000/message/list?is_group=1&to_user=20', '::1', '0da1b1d54653c62401c83baad198fafe', ' Undefined variable $item \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\MessageService.php \n line: 109', 1719472579);
INSERT INTO `cw_exception_logs` VALUES (136, 'local', 'Lumen', 'http://localhost:8000/message/read', '::1', '388d228264502ee687cc926bfb6d5677', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'from_user\' in \'where clause\' (Connection: mysql, SQL: update `cw_friends` set `unread` = 0, `cw_friends`.`updated_at` = 1719472909 where `from_user` = 7 and `to_user` = 6 and `cw_friends`.`deleted_at` is null) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1719472909);
INSERT INTO `cw_exception_logs` VALUES (137, 'local', 'Lumen', 'http://localhost:8000/message/read', '::1', '9ce2c969d7669584879192b7e287184a', ' SQLSTATE[42S22]: Column not found: 1054 Unknown column \'from_user\' in \'where clause\' (Connection: mysql, SQL: update `cw_friends` set `unread` = 0, `cw_friends`.`updated_at` = 1719474481 where `from_user` = 7 and `to_user` = 8 and `cw_friends`.`deleted_at` is null) \n code: 42S22 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Connection.php \n line: 822', 1719474481);
INSERT INTO `cw_exception_logs` VALUES (138, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '7025c361a2c1b076d909e59acc12c067', ' Call to undefined relationship [friend] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1719477089);
INSERT INTO `cw_exception_logs` VALUES (139, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '1c741850769336e562d56e67ab5859f0', ' Call to undefined relationship [friend] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1719477097);
INSERT INTO `cw_exception_logs` VALUES (140, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '3d0588e4ced2df25bda2bae93cf46c37', ' Call to undefined relationship [friend] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1719477111);
INSERT INTO `cw_exception_logs` VALUES (141, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', 'b5abe92dff294c08da77a0cb606080ca', ' Call to undefined relationship [friend] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1719477123);
INSERT INTO `cw_exception_logs` VALUES (142, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '7a4bc45edab2500f3b8082269d4494b3', ' Call to undefined relationship [friend] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1719477171);
INSERT INTO `cw_exception_logs` VALUES (143, 'local', 'Lumen', 'http://localhost:8000/friend/apply-list', '::1', 'b7a2a6658dcc2b6fcd3c069c3575f365', ' Call to undefined relationship [friend] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1719477174);
INSERT INTO `cw_exception_logs` VALUES (144, 'local', 'Lumen', 'http://localhost:8000/user/logout', '::1', '58102beca5d6dad4add7cf4f53d50496', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 72', 1719477195);
INSERT INTO `cw_exception_logs` VALUES (145, 'local', 'Lumen', 'http://localhost:8000/user/logout', '::1', 'e80790045f15e1fbdc8ab4bdb0626883', ' Attempt to read property \"id\" on null \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Http\\Controllers\\UserController.php \n line: 72', 1719477204);
INSERT INTO `cw_exception_logs` VALUES (146, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', 'f4c8f951c131256a70271fba23910aeb', ' Call to undefined relationship [friend] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1719477236);
INSERT INTO `cw_exception_logs` VALUES (147, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', 'ddd55cb225437396988789c6e9867360', ' Trying to access array offset on value of type int \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 29', 1719477625);
INSERT INTO `cw_exception_logs` VALUES (148, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', '41b512c8baf1211973a4eb90571c2f5e', ' Trying to access array offset on value of type int \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 29', 1719477650);
INSERT INTO `cw_exception_logs` VALUES (149, 'local', 'Lumen', 'http://localhost:8000/friend/apply-list', '::1', 'b99bea62875201ad314d0526026b908f', ' Call to undefined relationship [owner] on model [App\\Models\\Friend]. \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\vendor\\illuminate\\database\\Eloquent\\RelationNotFoundException.php \n line: 35', 1719477655);
INSERT INTO `cw_exception_logs` VALUES (150, 'local', 'Lumen', 'http://localhost:8000/friend/list', '::1', 'b0c9c4eba7c2176af0883dd610e13d4a', ' Trying to access array offset on value of type int \n code: 0 \n file: D:\\CoverWeChat\\cover-wechat-backend\\app\\Services\\FriendService.php \n line: 29', 1719477810);

-- ----------------------------
-- Table structure for cw_files
-- ----------------------------
DROP TABLE IF EXISTS `cw_files`;
CREATE TABLE `cw_files`  (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` enum('file','video','image') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `preview_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
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
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文件' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cw_files
-- ----------------------------

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
  `unread` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '未读消息数',
  `top` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '置顶时间',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '最新消息',
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最新消息时间',
  `display` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '显示',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `setting` json NOT NULL,
  `source` enum('mobile','wechat','group','qrcode') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mobile',
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `deleted_at` int(10) UNSIGNED NULL DEFAULT NULL,
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_owner_friend`(`owner`, `friend`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '好友' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cw_friends
-- ----------------------------
INSERT INTO `cw_friends` VALUES (1, 7, 5, '', 'verify', 'pass', 0, 0, NULL, 0, 0, '11111', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1715572125, NULL, 1715572125);
INSERT INTO `cw_friends` VALUES (3, 5, 7, '', 'verify', 'pass', 0, 0, NULL, 0, 0, '11111', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1715583275, NULL, 1715596456);
INSERT INTO `cw_friends` VALUES (4, 5, 6, '测试用户001', 'verify', 'pass', 0, 0, NULL, 0, 0, '我是yellow', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1716432007, NULL, 1718158977);
INSERT INTO `cw_friends` VALUES (5, 5, 8, '测试009', 'verify', 'pass', 0, 0, NULL, 0, 0, '我是yellow', '', '{\"MomentAndStatus\": {\"DontSeeHim\": true, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718097715, NULL, 1718103539);
INSERT INTO `cw_friends` VALUES (6, 8, 7, '测试用户002', 'verify', 'pass', 0, 0, '😉😉😉😉', 1719476856, 1, '我是测试009', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718103483, NULL, 1719477094);
INSERT INTO `cw_friends` VALUES (7, 8, 5, '张三', 'verify', 'pass', 0, 0, NULL, 0, 0, '', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": true}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718103539, NULL, 1718173972);
INSERT INTO `cw_friends` VALUES (8, 8, 6, '李四', 'verify', 'pass', 0, 0, NULL, 0, 0, '我是测试009', '老老实实', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718157385, NULL, 1718176408);
INSERT INTO `cw_friends` VALUES (9, 6, 8, '测试009', 'verify', 'pass', 0, 0, NULL, 0, 0, '', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": false}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718158968, NULL, 1718158968);
INSERT INTO `cw_friends` VALUES (10, 6, 5, '老六', 'verify', 'pass', 0, 0, NULL, 0, 0, '', '灌灌灌灌', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718158977, NULL, 1718176440);
INSERT INTO `cw_friends` VALUES (11, 6, 7, '测试用户002', 'verify', 'pass', 0, 0, NULL, 0, 0, '我是测试用户001', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718334864, NULL, 1718334923);
INSERT INTO `cw_friends` VALUES (12, 7, 8, '哈哈哈', 'verify', 'pass', 0, 0, '😉😉😉😉', 1719476856, 1, '', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718334914, NULL, 1719476861);
INSERT INTO `cw_friends` VALUES (13, 7, 6, '测试用户001', 'verify', 'pass', 0, 0, NULL, 0, 0, '', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718334923, NULL, 1718334923);
INSERT INTO `cw_friends` VALUES (14, 9, 6, '测试用户001', 'apply', 'check', 0, 0, NULL, 0, 0, '我是测试008', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718679919, NULL, 1718679919);
INSERT INTO `cw_friends` VALUES (15, 9, 7, '测试用户002', 'apply', 'check', 0, 0, NULL, 0, 0, '我是测试008', '', '{\"MomentAndStatus\": {\"DontSeeHim\": 0, \"DontLetHimSeeIt\": 0}, \"SettingFriendPerm\": \"ALLOW_ALL\"}', 'mobile', 1718680545, NULL, 1718680545);

-- ----------------------------
-- Table structure for cw_group_users
-- ----------------------------
DROP TABLE IF EXISTS `cw_group_users`;
CREATE TABLE `cw_group_users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role` enum('user','super','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `invite_id` int(11) NOT NULL,
  `invite_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '群备注',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '我在本群昵称',
  `unread` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '未读消息数',
  `top` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '置顶时间',
  `display` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '显示',
  `setting` json NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `deleted_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_group_id_user_id`(`group_id`, `user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 84 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群成员' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cw_group_users
-- ----------------------------
INSERT INTO `cw_group_users` VALUES (80, 23, 7, 'super', 7, 1, '群聊', '测试用户002', 0, 0, 1, '[]', 1719477273, 1719478044, 0);
INSERT INTO `cw_group_users` VALUES (81, 23, 5, 'user', 7, 1, '群聊', 'yellow', 3, 0, 1, '[]', 1719477273, 1719478031, 0);
INSERT INTO `cw_group_users` VALUES (82, 23, 6, 'user', 7, 1, '群聊', '测试用户001', 2, 0, 1, '[]', 1719477273, 1719477977, 0);
INSERT INTO `cw_group_users` VALUES (83, 23, 8, 'user', 7, 1, '群聊', '测试009', 1, 0, 1, '[]', 1719477273, 1719478031, 0);

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
) ENGINE = MyISAM AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cw_groups
-- ----------------------------
INSERT INTO `cw_groups` VALUES (23, '群聊', 7, 6, '21232131231', 1719478031, 0, '', 0, '[]', NULL, 1719477273, 1719478031);

-- ----------------------------
-- Table structure for cw_messages
-- ----------------------------
DROP TABLE IF EXISTS `cw_messages`;
CREATE TABLE `cw_messages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `from_user` int(11) NOT NULL DEFAULT 0 COMMENT '发送者',
  `to_user` int(11) NOT NULL DEFAULT 0 COMMENT '接受收者',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '消息内容，如果为文件或图片就是url',
  `type` enum('text','file','image','video','emoji') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text' COMMENT '消息类型：text、file、image...',
  `is_group` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '群聊消息',
  `is_undo` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回',
  `is_tips` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否提示信息',
  `at_users` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '提及某人',
  `pid` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '引用的消息ID',
  `file_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件id',
  `file_type` enum('file','video','image') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件类型',
  `file_size` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名称',
  `extends` json NULL COMMENT '消息扩展内容',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送时间',
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `deleted_users` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '已删除成员',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_from_to`(`from_user`, `to_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 94 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '消息' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cw_messages
-- ----------------------------
INSERT INTO `cw_messages` VALUES (89, 7, 23, '测试用户002邀请你进入群聊‘群聊’', 'text', 1, 0, 1, '', 0, 0, 'file', 0, '', NULL, 1, 1719477273, 0, '7');
INSERT INTO `cw_messages` VALUES (90, 7, 23, '你创建了群聊‘群聊’', 'text', 1, 0, 1, '', 0, 0, 'file', 0, '', NULL, 1, 1719477273, 0, '5,6,8');
INSERT INTO `cw_messages` VALUES (91, 7, 23, '111111', 'text', 1, 0, 0, '', 0, 0, 'file', 0, '', NULL, 1, 1719477970, 0, '');
INSERT INTO `cw_messages` VALUES (92, 8, 23, '474714414', 'text', 1, 0, 0, '', 0, 0, 'file', 0, '', NULL, 1, 1719477977, 0, '');
INSERT INTO `cw_messages` VALUES (93, 6, 23, '21232131231', 'text', 1, 0, 0, '', 0, 0, 'file', 0, '', NULL, 1, 1719478031, 0, '');

-- ----------------------------
-- Table structure for cw_users
-- ----------------------------
DROP TABLE IF EXISTS `cw_users`;
CREATE TABLE `cw_users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `wechat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `salt` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `token_expire_in` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nickname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sign` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `gender` enum('male','female','unknown') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown',
  `status` enum('normal','ban') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `setting` json NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_wechat`(`wechat`) USING BTREE,
  UNIQUE INDEX `uk_mobile`(`mobile`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cw_users
-- ----------------------------
INSERT INTO `cw_users` VALUES (5, 'yellow', '13006789517', '$2y$12$pm/aCn4gnn1Bbky1q/QRf.K9J3y0albBh6cFHvJV7tcEi5hOFByrm', '<rdZ]RuY1q', '', 0, 'yellow', 'https://api.multiavatar.com/929a4c6e30416dfd2781d00c6e0c2107.png', '', 'male', 'normal', '{\"FriendPerm\": {\"AddMyWay\": {\"Mobile\": 1, \"QRCode\": 1, \"Wechat\": 1, \"GroupChat\": 1}}}', 1715303743, 1718158949);
INSERT INTO `cw_users` VALUES (8, 'cs009', '13006789514', '$2y$12$WR5yL2P39.aMOhHOR2v8p.Ul2W6vRAD8stWGRTaw3vuwx9mMsz5U.', 'L?zLA2{*)A', '', 0, '测试009', 'https://api.multiavatar.com/be902e63416c7a86475c4be35738bef7.png', '', 'unknown', 'normal', '{\"FriendPerm\": {\"AddMyWay\": {\"Mobile\": 1, \"QRCode\": 1, \"Wechat\": 1, \"GroupChat\": 1}}}', 1718097347, 1719478012);
INSERT INTO `cw_users` VALUES (6, 'test_001', '13006789516', '$2y$12$.vBNapmihiXDDFOpw12SJurpQ3Det7zxxapTOmnYx6dyExMoKqFD.', 'O%D^40Q\\D&', 'eyJpdiI6IlpqOG9qa0EzY0FWTm1TUDV2c3RBQWc9PSIsInZhbHVlIjoid0V2Qm05MFF3WmJsbWNJb0lQOTd2Zz09IiwibWFjIjoiM2RmOTJiOTQ0ODBhYTQ0ZDc4NmJlZTdkYTY2YTc4OWY1ZTg5MjQ4NTA2ZDFhZWFhZTU0NTZkNDQ4NzEzYzU4ZSIsInRhZyI6IiJ9', 1720082821, '测试用户001', 'https://api.multiavatar.com/2de3cef5dac2c8af932f9d005d25420f.png', '', 'male', 'normal', '{\"FriendPerm\": {\"AddMyWay\": {\"Mobile\": 1, \"QRCode\": 1, \"Wechat\": 1, \"GroupChat\": 1}}}', 1715565414, 1719478021);
INSERT INTO `cw_users` VALUES (9, 'cs008', '13006789513', '$2y$12$vnrBv/f2IKpz0PYxo9owgOKtcQh1.GGhVhdTk0eU40qu/ABiX3GOa', '7P.Cdds(Kw', '', 0, '测试008', 'https://api.multiavatar.com/fcf16d3cb276dd7447019f49bac97e71.png', '', 'unknown', 'normal', '{\"FriendPerm\": {\"AddMyWay\": {\"Mobile\": 1, \"QRCode\": 1, \"Wechat\": 1, \"GroupChat\": 1}}}', 1718679864, 1719373138);
INSERT INTO `cw_users` VALUES (7, 'test_002', '13006789515', '$2y$12$1WScrPq8uMmuzDuS6FZD0.jf648Ibil.n4HSGxU6UnbptHXLz8xB2', '4d)VxdmbJ?', 'eyJpdiI6Imc1NEMzMC9zbmhkT3hkMVhRY0lKZUE9PSIsInZhbHVlIjoiSUpEa1liSXgxMUsvWmQzck5xazQydz09IiwibWFjIjoiMTI5NGViN2VjZTgxODRiM2Q0MDk2MTBlNzUyNzEyZWI1YmFjOGUwNThkMDg3ZDY2NWQ2MTRlZjRlN2U0ZGE3YyIsInRhZyI6IiJ9', 1720080263, '测试用户002', 'https://api.multiavatar.com/7e87014f14e948dbafecdb8f517f5107.png', '', 'male', 'normal', '{\"FriendPerm\": {\"AddMyWay\": {\"Mobile\": 1, \"QRCode\": 1, \"Wechat\": 1, \"GroupChat\": 1}}}', 1715565683, 1719475463);

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

-- ----------------------------
-- Records of migrations
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
