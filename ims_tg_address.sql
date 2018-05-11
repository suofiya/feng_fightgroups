/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : weidongli

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-06-03 00:40:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_tg_address`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_address`;
CREATE TABLE `ims_tg_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `openid` varchar(300) NOT NULL,
  `cname` varchar(30) NOT NULL COMMENT '收货人名称',
  `tel` varchar(20) NOT NULL COMMENT '手机号',
  `province` varchar(20) NOT NULL COMMENT '省',
  `city` varchar(20) NOT NULL COMMENT '市',
  `county` varchar(20) NOT NULL COMMENT '县(区)',
  `detailed_address` varchar(225) NOT NULL COMMENT '详细地址',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  `addtime` varchar(45) NOT NULL,
  `status` int(2) NOT NULL COMMENT '1为默认',
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_address
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_admin`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_admin`;
CREATE TABLE `ims_tg_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(30) NOT NULL COMMENT '管理员名称',
  `password` varchar(20) NOT NULL COMMENT '管理员密码',
  `email` varchar(60) NOT NULL COMMENT '邮箱',
  `tel` varchar(20) NOT NULL COMMENT '手机号',
  `uniacid` int(10) DEFAULT NULL COMMENT '公众号id',
  `openid` varchar(100) DEFAULT NULL COMMENT '用户openid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `openid` (`openid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_tg_admin
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_adv`;
CREATE TABLE `ims_tg_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`),
  KEY `indx_weid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_adv
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_arealimit`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_arealimit`;
CREATE TABLE `ims_tg_arealimit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `arealimitname` varchar(56) NOT NULL,
  `areas` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_arealimit
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_category`;
CREATE TABLE `ims_tg_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_category
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_collect`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_collect`;
CREATE TABLE `ims_tg_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_collect
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_coupon`;
CREATE TABLE `ims_tg_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_template_id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `cash` varchar(20) NOT NULL,
  `is_at_least` tinyint(3) unsigned NOT NULL,
  `at_least` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  `start_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `use_time` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `uniacid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_coupon_template`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_coupon_template`;
CREATE TABLE `ims_tg_coupon_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '优惠券名称',
  `value` varchar(50) NOT NULL COMMENT '最小面值',
  `value_to` varchar(50) NOT NULL COMMENT '最大面值',
  `is_random` tinyint(3) unsigned NOT NULL COMMENT '是否随机',
  `is_at_least` tinyint(3) unsigned NOT NULL COMMENT '是否存在最低消费',
  `at_least` varchar(20) NOT NULL COMMENT '最低消费',
  `is_sync_weixin` tinyint(11) unsigned NOT NULL,
  `user_level` tinyint(11) unsigned DEFAULT NULL,
  `quota` tinyint(10) unsigned NOT NULL COMMENT '领取限制',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '结束时间',
  `fans_tag` int(10) unsigned NOT NULL,
  `expire_notice` tinyint(4) unsigned NOT NULL,
  `is_share` tinyint(3) unsigned NOT NULL,
  `range_type` tinyint(3) unsigned NOT NULL,
  `is_forbid_preference` tinyint(3) unsigned NOT NULL,
  `description` varchar(255) NOT NULL COMMENT '描述',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `enable` tinyint(3) unsigned NOT NULL COMMENT '优惠券状态，1正常',
  `total` int(10) unsigned NOT NULL COMMENT '优惠券总量',
  `quantity_issue` int(10) unsigned NOT NULL,
  `quantity_used` int(10) unsigned NOT NULL COMMENT '已使用数量',
  `uid` int(10) unsigned NOT NULL,
  `uniacid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_coupon_template
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_credit_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_credit_record`;
CREATE TABLE `ims_tg_credit_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(245) NOT NULL,
  `num` varchar(30) NOT NULL,
  `createtime` varchar(145) NOT NULL,
  `transid` varchar(145) NOT NULL,
  `status` int(11) NOT NULL,
  `paytype` int(2) NOT NULL COMMENT '1微信2后台',
  `ordersn` varchar(145) NOT NULL,
  `type` int(2) NOT NULL COMMENT '1积分2余额',
  `remark` varchar(145) NOT NULL,
  `table` tinyint(4) DEFAULT NULL COMMENT '1微赞2tg',
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_credit_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_delivery_price`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_delivery_price`;
CREATE TABLE `ims_tg_delivery_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` int(10) unsigned NOT NULL,
  `province` varchar(12) NOT NULL,
  `city` varchar(12) NOT NULL,
  `district` varchar(12) NOT NULL,
  `first_weight` varchar(20) NOT NULL,
  `first_fee` varchar(20) NOT NULL,
  `additional_weight` varchar(20) NOT NULL,
  `additional_fee` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`template_id`),
  KEY `district` (`province`,`city`,`district`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_delivery_price
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_delivery_template`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_delivery_template`;
CREATE TABLE `ims_tg_delivery_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `region` longtext NOT NULL,
  `data` longtext NOT NULL,
  `updatetime` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `uniacid` int(11) NOT NULL,
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_delivery_template
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_dispatch`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_dispatch`;
CREATE TABLE `ims_tg_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` varchar(250) DEFAULT '',
  `areas` text,
  `carriers` text,
  `enabled` int(11) DEFAULT '0',
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_dispatch
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods`;
CREATE TABLE `ims_tg_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `gname` varchar(225) NOT NULL COMMENT '商品名称',
  `fk_typeid` int(10) unsigned NOT NULL COMMENT '所属分类id',
  `gsn` varchar(50) NOT NULL COMMENT '商品货号',
  `gnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品库存',
  `groupnum` int(10) unsigned NOT NULL COMMENT '最低拼团人数',
  `mprice` decimal(10,2) NOT NULL,
  `gprice` decimal(10,2) NOT NULL COMMENT '团购价',
  `oprice` decimal(10,2) NOT NULL COMMENT '单买价',
  `freight` decimal(10,2) NOT NULL,
  `gdesc` longtext NOT NULL COMMENT '商品简介',
  `gimg` varchar(225) DEFAULT NULL COMMENT '商品图片路径',
  `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否上架',
  `salenum` int(10) unsigned NOT NULL COMMENT '销量',
  `ishot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热卖',
  `displayorder` int(11) NOT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '最后修改时间',
  `uniacid` int(10) NOT NULL COMMENT '公众号的id',
  `endtime` int(11) NOT NULL COMMENT '团购限时（小时数）',
  `yunfei_id` int(11) NOT NULL,
  `is_discount` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `is_hexiao` int(2) NOT NULL,
  `hexiao_id` varchar(225) NOT NULL,
  `is_share` int(2) NOT NULL,
  `gdetaile` longtext NOT NULL,
  `isnew` int(11) NOT NULL,
  `isrecommand` int(11) NOT NULL,
  `isdiscount` int(11) NOT NULL,
  `hasoption` int(11) NOT NULL,
  `group_level` varchar(1000) NOT NULL,
  `group_level_status` int(11) NOT NULL,
  `merchantid` int(11) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_image` varchar(250) NOT NULL,
  `share_desc` varchar(200) NOT NULL,
  `one_limit` int(11) NOT NULL,
  `many_limit` int(11) NOT NULL,
  `firstdiscount` decimal(10,2) NOT NULL,
  `category_childid` int(11) NOT NULL,
  `category_parentid` int(11) NOT NULL,
  `pv` int(11) NOT NULL,
  `uv` int(11) NOT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `goodstab` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_goods_atlas`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods_atlas`;
CREATE TABLE `ims_tg_goods_atlas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `g_id` int(11) NOT NULL COMMENT '商品id',
  `thumb` varchar(145) NOT NULL COMMENT '图片路径',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_goods_atlas
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_goods_imgs`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods_imgs`;
CREATE TABLE `ims_tg_goods_imgs` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `fk_gid` int(10) NOT NULL COMMENT '对应商品的id',
  `albumpath` varchar(225) NOT NULL COMMENT '图片路径',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_gid` (`fk_gid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_tg_goods_imgs
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_goods_option`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods_option`;
CREATE TABLE `ims_tg_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_goods_option
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_goods_param`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods_param`;
CREATE TABLE `ims_tg_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_goods_param
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_goods_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods_type`;
CREATE TABLE `ims_tg_goods_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cname` varchar(30) NOT NULL COMMENT '分类名称',
  `pid` int(10) DEFAULT NULL COMMENT '上级分类的id',
  `uniacid` int(10) DEFAULT NULL COMMENT '公众号的id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_tg_goods_type
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_group`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_group`;
CREATE TABLE `ims_tg_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupnumber` varchar(115) NOT NULL COMMENT '团编号',
  `goodsid` int(11) NOT NULL COMMENT '商品ID',
  `goodsname` varchar(1024) NOT NULL COMMENT '商品名称',
  `groupstatus` int(11) NOT NULL COMMENT '团状态',
  `neednum` int(11) NOT NULL COMMENT '所需人数',
  `lacknum` int(11) NOT NULL COMMENT '缺少人数',
  `starttime` varchar(225) NOT NULL COMMENT '开团时间',
  `endtime` varchar(225) NOT NULL COMMENT '到期时间',
  `uniacid` int(11) NOT NULL,
  `grouptype` int(11) NOT NULL COMMENT '1同2异3普通4单',
  `isshare` int(11) NOT NULL COMMENT '1分享2不分享',
  `merchantid` int(11) NOT NULL,
  `price` varchar(11) DEFAULT NULL,
  `successtime` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_group
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_helpbuy`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_helpbuy`;
CREATE TABLE `ims_tg_helpbuy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(45) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_helpbuy
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_member`;
CREATE TABLE `ims_tg_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id',
  `openid` varchar(100) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `tag` varchar(1000) NOT NULL,
  `mobile` varchar(45) NOT NULL,
  `uid` int(11) NOT NULL,
  `credit1` varchar(100) DEFAULT NULL,
  `credit2` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `realname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_member
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_merchant`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_merchant`;
CREATE TABLE `ims_tg_merchant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `logo` varchar(225) NOT NULL,
  `industry` varchar(45) NOT NULL,
  `address` varchar(115) NOT NULL,
  `linkman_name` varchar(145) NOT NULL,
  `linkman_mobile` varchar(145) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `createtime` varchar(115) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `detail` varchar(1222) NOT NULL,
  `salenum` int(11) NOT NULL COMMENT '商家销量',
  `open` int(11) NOT NULL COMMENT '是否分配商家权限',
  `uname` varchar(45) NOT NULL,
  `password` varchar(145) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `messageopenid` varchar(150) NOT NULL,
  `openid` varchar(150) NOT NULL,
  `goodsnum` int(11) NOT NULL,
  `percent` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_merchant
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_merchant_account`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_merchant_account`;
CREATE TABLE `ims_tg_merchant_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL COMMENT '商家ID',
  `uniacid` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '操作员id',
  `amount` decimal(10,2) NOT NULL COMMENT '交易总金额',
  `updatetime` varchar(45) NOT NULL COMMENT '上次结算时间',
  `no_money` decimal(10,2) NOT NULL COMMENT '目前未结算金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_merchant_account
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_merchant_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_merchant_record`;
CREATE TABLE `ims_tg_merchant_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL COMMENT '商家id',
  `money` varchar(45) NOT NULL COMMENT '本次结算金额',
  `uid` int(11) NOT NULL COMMENT '操作员id',
  `createtime` varchar(45) NOT NULL COMMENT '结算时间',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_merchant_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_oplog`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_oplog`;
CREATE TABLE `ims_tg_oplog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `describe` varchar(225) DEFAULT NULL,
  `view_url` varchar(225) DEFAULT NULL,
  `ip` varchar(32) DEFAULT NULL COMMENT 'IP',
  `data` varchar(1024) DEFAULT NULL,
  `createtime` varchar(32) DEFAULT NULL,
  `user` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_oplog
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_order`;
CREATE TABLE `ims_tg_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` varchar(45) NOT NULL COMMENT '公众号',
  `gnum` int(11) NOT NULL COMMENT '购买数量',
  `openid` varchar(145) NOT NULL COMMENT '用户名',
  `ptime` varchar(45) NOT NULL COMMENT '支付成功时间',
  `orderno` varchar(45) NOT NULL COMMENT '订单编号',
  `price` varchar(45) NOT NULL COMMENT '价格',
  `status` int(9) NOT NULL COMMENT '订单状态0未支1支付，2已发货，3完成订单，9取消订单',
  `addressid` int(11) NOT NULL COMMENT '地址id',
  `g_id` int(11) NOT NULL COMMENT '商品id',
  `tuan_id` int(11) NOT NULL COMMENT '团id',
  `is_tuan` int(2) NOT NULL COMMENT '是否为团1为团0为单人',
  `createtime` varchar(45) NOT NULL COMMENT '订单生成时间',
  `pay_type` int(4) NOT NULL COMMENT '支付方式',
  `starttime` varchar(45) NOT NULL COMMENT '开始时间',
  `endtime` int(45) NOT NULL COMMENT '结束时间（小时）',
  `tuan_first` int(11) NOT NULL COMMENT '团长',
  `express` varchar(50) DEFAULT NULL COMMENT '快递公司名称',
  `expresssn` varchar(50) DEFAULT NULL COMMENT '快递单号',
  `transid` varchar(50) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `success` int(11) NOT NULL,
  `addname` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  `goodsprice` varchar(45) NOT NULL,
  `pay_price` varchar(45) NOT NULL,
  `freight` varchar(45) NOT NULL,
  `credits` int(11) NOT NULL,
  `is_usecard` int(11) NOT NULL,
  `is_hexiao` int(2) NOT NULL,
  `hexiaoma` varchar(50) NOT NULL,
  `veropenid` varchar(200) NOT NULL,
  `sendtime` varchar(115) NOT NULL,
  `gettime` varchar(115) NOT NULL,
  `addresstype` int(11) NOT NULL,
  `optionid` int(11) NOT NULL,
  `checkpay` int(11) NOT NULL,
  `merchantid` int(11) NOT NULL,
  `optionname` varchar(100) NOT NULL,
  `issettlement` int(11) NOT NULL,
  `message` text NOT NULL COMMENT '代付留言',
  `ordertype` int(11) NOT NULL,
  `othername` varchar(100) NOT NULL,
  `successtime` varchar(100) NOT NULL,
  `adminremark` varchar(100) NOT NULL,
  `discount_fee` varchar(100) NOT NULL,
  `first_fee` varchar(100) NOT NULL,
  `couponid` int(11) NOT NULL,
  `bdeltime` int(11) NOT NULL,
  `helpbuy_opneid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_order
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_order_goods`;
CREATE TABLE `ims_tg_order_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `fk_orderid` int(10) NOT NULL COMMENT '订单id',
  `fk_goodid` int(10) NOT NULL COMMENT '商品id',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_orderid` (`fk_orderid`),
  UNIQUE KEY `fk_goodid` (`fk_goodid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_order_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_order_print`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_order_print`;
CREATE TABLE `ims_tg_order_print` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `pid` int(3) NOT NULL,
  `oid` int(10) NOT NULL,
  `foid` varchar(50) NOT NULL,
  `status` int(3) NOT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_order_print
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_page`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_page`;
CREATE TABLE `ims_tg_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `click_pv` varchar(10) NOT NULL,
  `click_uv` varchar(10) NOT NULL,
  `enter_pv` varchar(10) NOT NULL,
  `enter_uv` varchar(10) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_page
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_print`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_print`;
CREATE TABLE `ims_tg_print` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `name` varchar(45) NOT NULL,
  `print_no` varchar(50) NOT NULL,
  `key` varchar(50) NOT NULL,
  `member_code` varchar(50) NOT NULL,
  `print_nums` int(3) NOT NULL,
  `qrcode_link` varchar(100) NOT NULL,
  `status` int(3) NOT NULL,
  `mode` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_print
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_puv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_puv`;
CREATE TABLE `ims_tg_puv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(45) NOT NULL,
  `pv` varchar(20) DEFAULT NULL COMMENT '总浏览人次',
  `uv` varchar(50) NOT NULL COMMENT '总浏览人数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_puv
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_puv_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_puv_record`;
CREATE TABLE `ims_tg_puv_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(45) NOT NULL,
  `openid` varchar(145) NOT NULL,
  `goodsid` int(11) NOT NULL COMMENT '商品id',
  `createtime` varchar(120) DEFAULT NULL COMMENT '访问时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_puv_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_refund_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_refund_record`;
CREATE TABLE `ims_tg_refund_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL COMMENT '1手机端2Web端3最后一人退款4部分退款',
  `goodsid` int(11) NOT NULL COMMENT '商品ID',
  `payfee` varchar(100) NOT NULL COMMENT '支付金额',
  `refundfee` varchar(100) NOT NULL COMMENT '退还金额',
  `transid` varchar(115) NOT NULL COMMENT '订单编号',
  `refund_id` varchar(115) NOT NULL COMMENT '微信退款单号',
  `refundername` varchar(100) NOT NULL COMMENT '退款人姓名',
  `refundermobile` varchar(100) NOT NULL COMMENT '退款人电话',
  `goodsname` varchar(100) NOT NULL COMMENT '商品名称',
  `createtime` varchar(45) NOT NULL COMMENT '退款时间',
  `status` int(11) NOT NULL COMMENT '0未成功1成功',
  `uniacid` int(11) NOT NULL,
  `orderid` varchar(45) NOT NULL,
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_refund_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_rules`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_rules`;
CREATE TABLE `ims_tg_rules` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `rulesname` varchar(40) NOT NULL COMMENT '规则名称',
  `rulesdetail` varchar(4000) DEFAULT NULL COMMENT '规则详情',
  `uniacid` int(10) NOT NULL COMMENT '公众号的id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rulesname` (`rulesname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_tg_rules
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_saler`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_saler`;
CREATE TABLE `ims_tg_saler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` varchar(225) DEFAULT '',
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `nickname` varchar(145) NOT NULL,
  `avatar` varchar(225) NOT NULL,
  `status` tinyint(3) DEFAULT '0',
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_storeid` (`storeid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_saler
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_setting`;
CREATE TABLE `ims_tg_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_setting
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_spec`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_spec`;
CREATE TABLE `ims_tg_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_spec
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_spec_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_spec_item`;
CREATE TABLE `ims_tg_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`),
  KEY `indx_weid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_spec_item
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_store`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_store`;
CREATE TABLE `ims_tg_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `storename` varchar(255) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `lat` varchar(255) DEFAULT '',
  `lng` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `createtime` varchar(45) NOT NULL,
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_store
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_user`;
CREATE TABLE `ims_tg_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_user
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_user_node`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_user_node`;
CREATE TABLE `ims_tg_user_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `url` varchar(300) NOT NULL,
  `do` varchar(255) NOT NULL,
  `ac` varchar(32) DEFAULT NULL,
  `op` varchar(32) DEFAULT NULL,
  `ac_id` int(11) DEFAULT NULL,
  `do_id` int(6) unsigned NOT NULL,
  `remark` varchar(255) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`do_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_user_node
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_user_role`;
CREATE TABLE `ims_tg_user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL,
  `nodes` text NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_user_role
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tg_users`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_users`;
CREATE TABLE `ims_tg_users` (
  `id` int(10) NOT NULL COMMENT '主键',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(20) NOT NULL COMMENT '用户密码',
  `email` varchar(60) NOT NULL COMMENT '邮箱',
  `tel` varchar(20) NOT NULL COMMENT '电话',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  `openid` varchar(100) NOT NULL COMMENT '用户openid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `uniacid` (`uniacid`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tg_users
-- ----------------------------
