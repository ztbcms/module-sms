SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `cms_sms_aliyun`;
CREATE TABLE `cms_sms_aliyun`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `access_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'Access Key ID（阿里云API密钥）',
  `access_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'Access Key Secret（阿里云API密钥）',
  `sign` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '短信签名',
  `template` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '短信模版 Code',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '模板内容',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '模板别名',
  `create_time` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of cms_sms_aliyun
-- ----------------------------

-- ----------------------------
-- Table structure for cms_sms_platform
-- ----------------------------
DROP TABLE IF EXISTS `cms_sms_platform`;
CREATE TABLE `cms_sms_platform`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '运营商名称',
  `tablename` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '表名',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
  `enable` tinyint(4) NULL DEFAULT 0 COMMENT '是否启用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `cms_sms_platform` VALUES (1, '阿里云短信服务', 'aliyun', '阿里云短信服务', 1);
SET FOREIGN_KEY_CHECKS = 1;


SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cms_sms_log
-- ----------------------------
DROP TABLE IF EXISTS `cms_sms_log`;
CREATE TABLE `cms_sms_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `operator` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '运营商',
  `template` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '短信模板ID',
  `recv` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '接收人',
  `param` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '短信模板变量',
  `sendtime` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发送时间',
  `result` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '发送结果',
  `area_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '区号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
