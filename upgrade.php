<?php 
$sql="CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_adact` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序',
  `name` varchar(255) DEFAULT '' COMMENT '标题',
  `subtitle` varchar(255) DEFAULT '' COMMENT '副标题',
  `thumb` varchar(255) DEFAULT '' COMMENT '封面图',
  `detail` text COMMENT '内容',
  `enabled` int(11) DEFAULT '0' COMMENT '开关',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_adgroup` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `setting_name` varchar(100) DEFAULT '' COMMENT '配置名',
  `setting_value` longtext COMMENT '配置值',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_adsp` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID',
  `uniacid` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) DEFAULT '' COMMENT '图片',
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用',
  `page_url` varchar(255) DEFAULT '' COMMENT '跳转页面',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_adv` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID',
  `uniacid` int(10) DEFAULT NULL,
  `advname` varchar(255) DEFAULT '' COMMENT '幻灯片标题',
  `thumb` varchar(255) DEFAULT '' COMMENT '图片',
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用',
  `page_url` varchar(255) DEFAULT '' COMMENT '跳转页面',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_booking` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user` bigint(20) DEFAULT '0' COMMENT '用户id',
  `name` varchar(100) DEFAULT '' COMMENT '名称',
  `tel` varchar(100) DEFAULT '' COMMENT '电话',
  `option1` text COMMENT '信息1',
  `option2` text COMMENT '信息2',
  `money` int(11) DEFAULT '0' COMMENT '金额,单位分',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态，0=未处理，1=已处理',
  `mark` varchar(255) DEFAULT '' COMMENT '备注',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_fav` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user` bigint(20) DEFAULT '0' COMMENT '用户ID',
  `pic_type` tinyint(4) DEFAULT '0' COMMENT '图分类型',
  `pic_id` int(11) DEFAULT '0' COMMENT '图ID，',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_giftbig` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `name` varchar(100) DEFAULT '' COMMENT '名称',
  `mobile` varchar(100) DEFAULT '' COMMENT '手机',
  `enabled` tinyint(4) DEFAULT '0' COMMENT '状态，0=未处理，1=已处理',
  `mark` varchar(200) DEFAULT '' COMMENT '备注',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_guestbook` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT '' COMMENT '名称',
  `contact` varchar(100) DEFAULT '' COMMENT '联系方式，邮箱/电话',
  `msg` varchar(1000) DEFAULT NULL COMMENT '留言',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态，0=未处理，1=已处理',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_news` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序',
  `parentid` int(11) DEFAULT '0' COMMENT '分类ID',
  `name` varchar(255) DEFAULT '' COMMENT '标题',
  `subtitle` varchar(255) DEFAULT '' COMMENT '副标题',
  `thumb` varchar(255) DEFAULT '' COMMENT '封面图',
  `detail` text COMMENT '内容',
  `enabled` int(11) DEFAULT '0' COMMENT '开关',
  `createtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '发布日期',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_order` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `ordersn` varchar(50) NOT NULL DEFAULT '',
  `good_id` int(11) NOT NULL,
  `money` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `mark` varchar(300) DEFAULT '',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_pic_multi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序',
  `name` varchar(255) DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) DEFAULT '' COMMENT '封面图',
  `options` text COMMENT '选项',
  `smeta` text COMMENT '照片组',
  `search_tag` varchar(5000) DEFAULT '' COMMENT '标签搜索',
  `show_num` bigint(20) DEFAULT '0' COMMENT '浏览数',
  `fav_num` bigint(20) DEFAULT '0' COMMENT '收藏数',
  `enabled` int(11) DEFAULT '0' COMMENT '开关',
  `createtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '发布日期',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_pic_multi_option` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID',
  `uniacid` int(11) DEFAULT NULL,
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  `parentid` int(11) DEFAULT '0' COMMENT '父节点',
  `name` varchar(255) DEFAULT '' COMMENT '分类名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '分类图片',
  `case` int(11) DEFAULT '0' COMMENT '套数',
  `isrecommand` tinyint(4) DEFAULT '0' COMMENT '首页推荐',
  `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_pic_sole` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序',
  `name` varchar(255) DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) DEFAULT '' COMMENT '封面图',
  `options` text COMMENT '选项',
  `search_tag` varchar(5000) DEFAULT '' COMMENT '标签搜索',
  `show_num` bigint(20) DEFAULT '0' COMMENT '浏览数',
  `fav_num` bigint(20) DEFAULT '0' COMMENT '收藏数',
  `enabled` int(11) DEFAULT '0' COMMENT '开关',
  `createtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '发布日期',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_pic_sole_option` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID',
  `uniacid` int(11) DEFAULT NULL,
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  `parentid` int(11) DEFAULT '0' COMMENT '父节点',
  `name` varchar(255) DEFAULT '' COMMENT '分类名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '分类图片',
  `case` int(11) DEFAULT '0' COMMENT '套数',
  `isrecommand` tinyint(4) DEFAULT '0' COMMENT '首页推荐',
  `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `setting_name` varchar(100) DEFAULT '' COMMENT '配置名',
  `setting_value` longtext COMMENT '配置值',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_term` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID',
  `uniacid` int(11) DEFAULT NULL,
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  `parentid` int(11) DEFAULT '0' COMMENT '父节点',
  `name` varchar(255) DEFAULT '' COMMENT '分类名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '分类图片',
  `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_term_des` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID',
  `uniacid` int(11) DEFAULT NULL,
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  `parentid` int(11) DEFAULT '0' COMMENT '父节点',
  `name` varchar(255) DEFAULT '' COMMENT '分类名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '分类图片',
  `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_slwl_fitment_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '系统公众号id',
  `openid` varchar(100) DEFAULT '' COMMENT 'openid',
  `unionid` varchar(100) DEFAULT '' COMMENT '微信的',
  `nicename` varchar(100) DEFAULT '' COMMENT '用户美名',
  `avatar` varchar(255) DEFAULT '' COMMENT '用户头像',
  `city` varchar(50) DEFAULT '' COMMENT '城市',
  `gender` tinyint(4) DEFAULT '0' COMMENT '性别 0：未知、1：男、2：女',
  `province` varchar(50) DEFAULT '' COMMENT '省份',
  `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists("slwl_fitment_adact", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adact")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_adact", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adact")." ADD   `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("slwl_fitment_adact", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adact")." ADD   `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序';");
}
if(!pdo_fieldexists("slwl_fitment_adact", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adact")." ADD   `name` varchar(255) DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists("slwl_fitment_adact", "subtitle")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adact")." ADD   `subtitle` varchar(255) DEFAULT '' COMMENT '副标题';");
}
if(!pdo_fieldexists("slwl_fitment_adact", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adact")." ADD   `thumb` varchar(255) DEFAULT '' COMMENT '封面图';");
}
if(!pdo_fieldexists("slwl_fitment_adact", "detail")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adact")." ADD   `detail` text COMMENT '内容';");
}
if(!pdo_fieldexists("slwl_fitment_adact", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adact")." ADD   `enabled` int(11) DEFAULT '0' COMMENT '开关';");
}
if(!pdo_fieldexists("slwl_fitment_adact", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adact")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_adgroup", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adgroup")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_adgroup", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adgroup")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_adgroup", "setting_name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adgroup")." ADD   `setting_name` varchar(100) DEFAULT '' COMMENT '配置名';");
}
if(!pdo_fieldexists("slwl_fitment_adgroup", "setting_value")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adgroup")." ADD   `setting_value` longtext COMMENT '配置值';");
}
if(!pdo_fieldexists("slwl_fitment_adgroup", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adgroup")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_adsp", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adsp")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID';");
}
if(!pdo_fieldexists("slwl_fitment_adsp", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adsp")." ADD   `uniacid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_adsp", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adsp")." ADD   `name` varchar(255) DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists("slwl_fitment_adsp", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adsp")." ADD   `thumb` varchar(255) DEFAULT '' COMMENT '图片';");
}
if(!pdo_fieldexists("slwl_fitment_adsp", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adsp")." ADD   `displayorder` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists("slwl_fitment_adsp", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adsp")." ADD   `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用';");
}
if(!pdo_fieldexists("slwl_fitment_adsp", "page_url")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adsp")." ADD   `page_url` varchar(255) DEFAULT '' COMMENT '跳转页面';");
}
if(!pdo_fieldexists("slwl_fitment_adsp", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adsp")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_adv", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adv")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID';");
}
if(!pdo_fieldexists("slwl_fitment_adv", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adv")." ADD   `uniacid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_adv", "advname")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adv")." ADD   `advname` varchar(255) DEFAULT '' COMMENT '幻灯片标题';");
}
if(!pdo_fieldexists("slwl_fitment_adv", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adv")." ADD   `thumb` varchar(255) DEFAULT '' COMMENT '图片';");
}
if(!pdo_fieldexists("slwl_fitment_adv", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adv")." ADD   `displayorder` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists("slwl_fitment_adv", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adv")." ADD   `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用';");
}
if(!pdo_fieldexists("slwl_fitment_adv", "page_url")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adv")." ADD   `page_url` varchar(255) DEFAULT '' COMMENT '跳转页面';");
}
if(!pdo_fieldexists("slwl_fitment_adv", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_adv")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_booking", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_booking", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_booking", "user")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `user` bigint(20) DEFAULT '0' COMMENT '用户id';");
}
if(!pdo_fieldexists("slwl_fitment_booking", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `name` varchar(100) DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists("slwl_fitment_booking", "tel")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `tel` varchar(100) DEFAULT '' COMMENT '电话';");
}
if(!pdo_fieldexists("slwl_fitment_booking", "option1")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `option1` text COMMENT '信息1';");
}
if(!pdo_fieldexists("slwl_fitment_booking", "option2")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `option2` text COMMENT '信息2';");
}
if(!pdo_fieldexists("slwl_fitment_booking", "money")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `money` int(11) DEFAULT '0' COMMENT '金额,单位分';");
}
if(!pdo_fieldexists("slwl_fitment_booking", "status")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `status` tinyint(4) DEFAULT '0' COMMENT '状态，0=未处理，1=已处理';");
}
if(!pdo_fieldexists("slwl_fitment_booking", "mark")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `mark` varchar(255) DEFAULT '' COMMENT '备注';");
}
if(!pdo_fieldexists("slwl_fitment_booking", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_booking")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_fav", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_fav")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_fav", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_fav")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_fav", "user")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_fav")." ADD   `user` bigint(20) DEFAULT '0' COMMENT '用户ID';");
}
if(!pdo_fieldexists("slwl_fitment_fav", "pic_type")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_fav")." ADD   `pic_type` tinyint(4) DEFAULT '0' COMMENT '图分类型';");
}
if(!pdo_fieldexists("slwl_fitment_fav", "pic_id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_fav")." ADD   `pic_id` int(11) DEFAULT '0' COMMENT '图ID，';");
}
if(!pdo_fieldexists("slwl_fitment_fav", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_fav")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_giftbig", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_giftbig")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_giftbig", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_giftbig")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_giftbig", "uid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_giftbig")." ADD   `uid` int(11) DEFAULT '0' COMMENT '用户ID';");
}
if(!pdo_fieldexists("slwl_fitment_giftbig", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_giftbig")." ADD   `name` varchar(100) DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists("slwl_fitment_giftbig", "mobile")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_giftbig")." ADD   `mobile` varchar(100) DEFAULT '' COMMENT '手机';");
}
if(!pdo_fieldexists("slwl_fitment_giftbig", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_giftbig")." ADD   `enabled` tinyint(4) DEFAULT '0' COMMENT '状态，0=未处理，1=已处理';");
}
if(!pdo_fieldexists("slwl_fitment_giftbig", "mark")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_giftbig")." ADD   `mark` varchar(200) DEFAULT '' COMMENT '备注';");
}
if(!pdo_fieldexists("slwl_fitment_giftbig", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_giftbig")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_guestbook", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_guestbook")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_guestbook", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_guestbook")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_guestbook", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_guestbook")." ADD   `name` varchar(100) DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists("slwl_fitment_guestbook", "contact")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_guestbook")." ADD   `contact` varchar(100) DEFAULT '' COMMENT '联系方式，邮箱/电话';");
}
if(!pdo_fieldexists("slwl_fitment_guestbook", "msg")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_guestbook")." ADD   `msg` varchar(1000) DEFAULT NULL COMMENT '留言';");
}
if(!pdo_fieldexists("slwl_fitment_guestbook", "status")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_guestbook")." ADD   `status` tinyint(4) DEFAULT '0' COMMENT '状态，0=未处理，1=已处理';");
}
if(!pdo_fieldexists("slwl_fitment_guestbook", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_guestbook")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_news", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_news", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("slwl_fitment_news", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序';");
}
if(!pdo_fieldexists("slwl_fitment_news", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `parentid` int(11) DEFAULT '0' COMMENT '分类ID';");
}
if(!pdo_fieldexists("slwl_fitment_news", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `name` varchar(255) DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists("slwl_fitment_news", "subtitle")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `subtitle` varchar(255) DEFAULT '' COMMENT '副标题';");
}
if(!pdo_fieldexists("slwl_fitment_news", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `thumb` varchar(255) DEFAULT '' COMMENT '封面图';");
}
if(!pdo_fieldexists("slwl_fitment_news", "detail")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `detail` text COMMENT '内容';");
}
if(!pdo_fieldexists("slwl_fitment_news", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `enabled` int(11) DEFAULT '0' COMMENT '开关';");
}
if(!pdo_fieldexists("slwl_fitment_news", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `createtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '发布日期';");
}
if(!pdo_fieldexists("slwl_fitment_news", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_news")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_order", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_order")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_order", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_order")." ADD   `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_order", "user")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_order")." ADD   `user` int(11) NOT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_order", "ordersn")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_order")." ADD   `ordersn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("slwl_fitment_order", "good_id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_order")." ADD   `good_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_order", "money")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_order")." ADD   `money` bigint(20) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_order", "status")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_order")." ADD   `status` tinyint(4) DEFAULT '0';");
}
if(!pdo_fieldexists("slwl_fitment_order", "mark")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_order")." ADD   `mark` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists("slwl_fitment_order", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_order")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `name` varchar(255) DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `thumb` varchar(255) DEFAULT '' COMMENT '封面图';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "options")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `options` text COMMENT '选项';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "smeta")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `smeta` text COMMENT '照片组';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "search_tag")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `search_tag` varchar(5000) DEFAULT '' COMMENT '标签搜索';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "show_num")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `show_num` bigint(20) DEFAULT '0' COMMENT '浏览数';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "fav_num")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `fav_num` bigint(20) DEFAULT '0' COMMENT '收藏数';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `enabled` int(11) DEFAULT '0' COMMENT '开关';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `createtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '发布日期';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi_option", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi_option")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi_option", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi_option")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi_option", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi_option")." ADD   `displayorder` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi_option", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi_option")." ADD   `parentid` int(11) DEFAULT '0' COMMENT '父节点';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi_option", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi_option")." ADD   `name` varchar(255) DEFAULT '' COMMENT '分类名称';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi_option", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi_option")." ADD   `thumb` varchar(255) DEFAULT '' COMMENT '分类图片';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi_option", "case")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi_option")." ADD   `case` int(11) DEFAULT '0' COMMENT '套数';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi_option", "isrecommand")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi_option")." ADD   `isrecommand` tinyint(4) DEFAULT '0' COMMENT '首页推荐';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi_option", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi_option")." ADD   `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用';");
}
if(!pdo_fieldexists("slwl_fitment_pic_multi_option", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_multi_option")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `name` varchar(255) DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `thumb` varchar(255) DEFAULT '' COMMENT '封面图';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "options")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `options` text COMMENT '选项';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "search_tag")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `search_tag` varchar(5000) DEFAULT '' COMMENT '标签搜索';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "show_num")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `show_num` bigint(20) DEFAULT '0' COMMENT '浏览数';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "fav_num")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `fav_num` bigint(20) DEFAULT '0' COMMENT '收藏数';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `enabled` int(11) DEFAULT '0' COMMENT '开关';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `createtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '发布日期';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole_option", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole_option")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole_option", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole_option")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole_option", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole_option")." ADD   `displayorder` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole_option", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole_option")." ADD   `parentid` int(11) DEFAULT '0' COMMENT '父节点';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole_option", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole_option")." ADD   `name` varchar(255) DEFAULT '' COMMENT '分类名称';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole_option", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole_option")." ADD   `thumb` varchar(255) DEFAULT '' COMMENT '分类图片';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole_option", "case")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole_option")." ADD   `case` int(11) DEFAULT '0' COMMENT '套数';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole_option", "isrecommand")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole_option")." ADD   `isrecommand` tinyint(4) DEFAULT '0' COMMENT '首页推荐';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole_option", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole_option")." ADD   `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用';");
}
if(!pdo_fieldexists("slwl_fitment_pic_sole_option", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_pic_sole_option")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_settings", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_settings")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_settings", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_settings")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_settings", "setting_name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_settings")." ADD   `setting_name` varchar(100) DEFAULT '' COMMENT '配置名';");
}
if(!pdo_fieldexists("slwl_fitment_settings", "setting_value")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_settings")." ADD   `setting_value` longtext COMMENT '配置值';");
}
if(!pdo_fieldexists("slwl_fitment_settings", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_settings")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_term", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID';");
}
if(!pdo_fieldexists("slwl_fitment_term", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_term", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term")." ADD   `displayorder` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists("slwl_fitment_term", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term")." ADD   `parentid` int(11) DEFAULT '0' COMMENT '父节点';");
}
if(!pdo_fieldexists("slwl_fitment_term", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term")." ADD   `name` varchar(255) DEFAULT '' COMMENT '分类名称';");
}
if(!pdo_fieldexists("slwl_fitment_term", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term")." ADD   `thumb` varchar(255) DEFAULT '' COMMENT '分类图片';");
}
if(!pdo_fieldexists("slwl_fitment_term", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term")." ADD   `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用';");
}
if(!pdo_fieldexists("slwl_fitment_term", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_term_des", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term_des")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID';");
}
if(!pdo_fieldexists("slwl_fitment_term_des", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term_des")." ADD   `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists("slwl_fitment_term_des", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term_des")." ADD   `displayorder` int(11) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists("slwl_fitment_term_des", "parentid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term_des")." ADD   `parentid` int(11) DEFAULT '0' COMMENT '父节点';");
}
if(!pdo_fieldexists("slwl_fitment_term_des", "name")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term_des")." ADD   `name` varchar(255) DEFAULT '' COMMENT '分类名称';");
}
if(!pdo_fieldexists("slwl_fitment_term_des", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term_des")." ADD   `thumb` varchar(255) DEFAULT '' COMMENT '分类图片';");
}
if(!pdo_fieldexists("slwl_fitment_term_des", "enabled")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term_des")." ADD   `enabled` tinyint(4) DEFAULT '0' COMMENT '是否启用';");
}
if(!pdo_fieldexists("slwl_fitment_term_des", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_term_des")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}
if(!pdo_fieldexists("slwl_fitment_users", "id")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_users")." ADD   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("slwl_fitment_users", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_users")." ADD   `uniacid` int(11) DEFAULT NULL COMMENT '系统公众号id';");
}
if(!pdo_fieldexists("slwl_fitment_users", "openid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_users")." ADD   `openid` varchar(100) DEFAULT '' COMMENT 'openid';");
}
if(!pdo_fieldexists("slwl_fitment_users", "unionid")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_users")." ADD   `unionid` varchar(100) DEFAULT '' COMMENT '微信的';");
}
if(!pdo_fieldexists("slwl_fitment_users", "nicename")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_users")." ADD   `nicename` varchar(100) DEFAULT '' COMMENT '用户美名';");
}
if(!pdo_fieldexists("slwl_fitment_users", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_users")." ADD   `avatar` varchar(255) DEFAULT '' COMMENT '用户头像';");
}
if(!pdo_fieldexists("slwl_fitment_users", "city")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_users")." ADD   `city` varchar(50) DEFAULT '' COMMENT '城市';");
}
if(!pdo_fieldexists("slwl_fitment_users", "gender")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_users")." ADD   `gender` tinyint(4) DEFAULT '0' COMMENT '性别 0：未知、1：男、2：女';");
}
if(!pdo_fieldexists("slwl_fitment_users", "province")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_users")." ADD   `province` varchar(50) DEFAULT '' COMMENT '省份';");
}
if(!pdo_fieldexists("slwl_fitment_users", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("slwl_fitment_users")." ADD   `addtime` datetime DEFAULT '2000-01-01 00:00:00' COMMENT '添加时间，程序插入';");
}

 ?>