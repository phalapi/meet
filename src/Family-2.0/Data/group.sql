
  `user_id` bigint(20) DEFAULT '0' COMMENT '创建者的用户ID',
  `number` varchar(10) DEFAULT '0000' COMMENT '家庭号',
  `groupname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '家庭组名称',
  `password` varchar(64) DEFAULT '' COMMENT '密码',
  `amount` int(11) DEFAULT '0' COMMENT '群组的成员数量',
  `create_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '创建日期',
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'all' COMMENT '家庭群的类型',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '是否已删除，0否，1删除',