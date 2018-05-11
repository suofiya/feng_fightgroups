<?php

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_dispatch` (
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
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id',
  `openid` varchar(100) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `tag` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_arealimit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `arealimitname` varchar(56) NOT NULL,
  `areas` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_saler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` varchar(225) DEFAULT '',
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `nickname` varchar(145) NOT NULL,
  `avatar` varchar(225) NOT NULL,
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_storeid` (`storeid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `storename` varchar(255) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `lat` varchar(255) DEFAULT '',
  `lng` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `createtime` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_arealimit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `arealimitname` varchar(56) NOT NULL,
  `areas` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_dispatch` (
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
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_goods_option` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_group` (
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_saler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` varchar(225) DEFAULT '',
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `nickname` varchar(145) NOT NULL,
  `avatar` varchar(225) NOT NULL,
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_storeid` (`storeid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `storename` varchar(255) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `lat` varchar(255) DEFAULT '',
  `lng` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `createtime` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_merchant` (
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_tg_merchant_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL COMMENT '商家ID',
  `uniacid` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '操作员id',
  `amount` decimal(10,2) NOT NULL COMMENT '交易总金额',
  `updatetime` varchar(45) NOT NULL COMMENT '上次结算时间',
  `no_money` decimal(10,2) NOT NULL COMMENT '目前未结算金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_tg_merchant_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL COMMENT '商家id',
  `money` varchar(45) NOT NULL COMMENT '本次结算金额',
  `uid` int(11) NOT NULL COMMENT '操作员id',
  `createtime` varchar(45) NOT NULL COMMENT '结算时间',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

if(!pdo_fieldexists('tg_order', 'goodsprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `goodsprice` VARCHAR( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'pay_price')) {
  pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `pay_price` VARCHAR( 45 ) NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'freight')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `freight` VARCHAR( 45 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'yunfei_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `yunfei_id` int( 11 )  NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'is_discount')) {
  pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `is_discount` int( 11 )  NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'credits')) {
  pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `credits` int( 11 )  NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'credits')) {
  pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `credits` int( 11 )  NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'is_usecard')) {
  pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `is_usecard` int( 11 )  NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'is_hexiao')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `is_hexiao` int( 2 )  NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'hexiao_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `hexiao_id` VARCHAR( 225 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'is_hexiao')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `is_hexiao` int( 2 )  NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'hexiaoma')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `hexiaoma` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'veropenid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `veropenid` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'is_share')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `is_share` int(2)  NOT NULL;");
}
if(!pdo_fieldexists('tg_address', 'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD  `type` int(2) NOT NULL COMMENT '1公司，2家庭';");
}
if(!pdo_fieldexists('tg_goods', 'gdetaile')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `gdetaile` longtext NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'isnew')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD  `isnew` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `isrecommand` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'isdiscount')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `isdiscount` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'hasoption')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `hasoption` int(11) NOT NULL;");
}
if(pdo_fieldexists('tg_member', 'from_user')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." CHANGE  `from_user`  `openid` varchar(100) NOT NULL COMMENT '微信会员openID';");
}
if(!pdo_fieldexists('tg_member', 'tag')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD   `tag` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('tg_member', 'mobile')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD  `mobile` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'sendtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `sendtime` varchar(115) NOT NULL;");
}

if(!pdo_fieldexists('tg_order', 'gettime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `gettime` varchar(115) NOT NULL;");
}

if(!pdo_fieldexists('tg_order', 'addresstype')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `addresstype` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'optionid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `optionid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'checkpay')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `checkpay` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_refund_record', 'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `type` int(11) NOT NULL COMMENT '1手机端2Web端3最后一人退款4部分退款';");
}
if(!pdo_fieldexists('tg_refund_record', 'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD   `goodsid` int(11) NOT NULL COMMENT '商品ID';");
}
if(!pdo_fieldexists('tg_refund_record', 'payfee')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD   `payfee` varchar(100) NOT NULL COMMENT '支付金额';");
}
if(!pdo_fieldexists('tg_refund_record', 'refundfee')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD   `refundfee` varchar(100) NOT NULL COMMENT '退还金额';");
}
if(pdo_fieldexists('tg_refund_record', 'transid')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." CHANGE `transid` `transid` varchar(115) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('tg_refund_record', 'refund_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD    `refund_id` varchar(115) NOT NULL COMMENT '微信退款单号';");
}
if(!pdo_fieldexists('tg_refund_record', 'refundername')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD     `refundername` varchar(100) NOT NULL COMMENT '退款人姓名';");
}
if(!pdo_fieldexists('tg_refund_record', 'refundermobile')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD     `refundermobile` varchar(100) NOT NULL COMMENT '退款人电话';");
}

if(!pdo_fieldexists('tg_refund_record', 'goodsname')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD  `goodsname` varchar(100) NOT NULL COMMENT '商品名称';");
}
if(pdo_fieldexists('tg_refund_record', 'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." CHANGE  `createtime` `createtime` varchar(45) NOT NULL COMMENT '退款时间';");
}
if(!pdo_fieldexists('tg_refund_record', 'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD  `status` int(11) NOT NULL COMMENT '0未成功1成功';");
}
if(!pdo_fieldexists('tg_refund_record', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD  `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_refund_record', 'orderid')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD  `orderid` varchar(45) NOT NULL;");
}
if(pdo_fieldexists('tg_address', 'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." CHANGE `type` `type` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_dispatch', 'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD   `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'group_level')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD    `group_level` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'group_level_status')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD    `group_level_status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD      `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'share_title')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD    `share_title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'share_image')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD     `share_image` varchar(250) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD      `share_desc` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'one_limit')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD      `one_limit` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'many_limit')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD      `many_limit` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'firstdiscount')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD     `firstdiscount` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('tg_group', 'price')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD      `price` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD       `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'optionname')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD      `optionname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_refund_record', 'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD       `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_saler', 'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD       `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_spec', 'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec')." ADD       `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_store', 'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD       `merchantid` int(11) NOT NULL;");
}
?>