## 添加盲盒分类表
CREATE TABLE `hm_blindbox_type` (
`id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类id',
`name` varchar(32) COLLATE utf8mb4_bin DEFAULT '' COMMENT '分类名称',
`sort` int(3) DEFAULT '1' COMMENT '排序 值越大越靠前',
`is_delete` tinyint(2) DEFAULT '1' COMMENT '1 正常 2 删除',
`status` tinyint(2) DEFAULT '1' COMMENT '是否显示 1:显示 2:隐藏',
`create_time` datetime DEFAULT NULL COMMENT '创建时间',
`update_time` datetime DEFAULT NULL COMMENT '更新时间',
PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC COMMENT='盲盒分类表';
# 添加盲盒分类字段
alter table `hm_blindbox` add column `type` tinyint(1) DEFAULT NULL COMMENT '盲盒分类';
alter table `hm_blindbox` add column  `index_type` tinyint(1) DEFAULT '4' COMMENT '1 新人专享 2今日推荐 3 首页显示 4普通';
alter table `hm_user` add column `birthday` varchar(16) COLLATE utf8mb4_bin DEFAULT '00-00' COMMENT '生日';
alter table `hm_user` add column `is_promotion` tinyint(4) DEFAULT '0' COMMENT '推广 1开启 0关闭';

# 门店表
CREATE TABLE `hm_store` (
`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
`uid` varchar(256) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'uid',
`address` varchar(512) COLLATE utf8mb4_bin DEFAULT '' COMMENT '门店地址',
`sort` int(11) DEFAULT '0' COMMENT '排序',
`status` tinyint(4) DEFAULT '1' COMMENT '显示：1:是  2:否',
`info` varchar(512) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '详情',
`create_time` datetime DEFAULT NULL COMMENT '创建时间',
`update_time` datetime DEFAULT NULL COMMENT '更新时间',
PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC COMMENT='门店地址表';
