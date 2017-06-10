
  `user_id` bigint(20) DEFAULT NULL,
  `weight` int(7) DEFAULT '0' COMMENT '体重（单位用g）',
  `report_date` int(11) DEFAULT '0' COMMENT '体重上报时间戳',
  `type` tinyint(2) DEFAULT '0' COMMENT '体重阶段：0普通人/产前，1孕妇，2抱婴（产后）',
  `baby_position` tinyint(4) DEFAULT '0' COMMENT '宝宝的位置，1老大，2老二，3老三（为0时表示不是宝宝的体重）',
  `report_short_date` varchar(8) DEFAULT '' COMMENT '短的日期，格式如：20150501',
  `cur_day_position` smallint(3) DEFAULT '1' COMMENT '当天上报第几次上报',