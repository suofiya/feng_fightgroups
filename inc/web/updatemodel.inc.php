<?
global $_W, $_GPC;
		if(!pdo_fieldexists('tg_address', 'type')) {
			pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `type` INT NOT NULL;");
		}
		if(pdo_fieldexists('tg_goods', 'gdesc1')) {
			pdo_query("ALTER TABLE ".tablename('tg_goods')." DROP `gdesc1`;");
		}
		if(pdo_fieldexists('tg_goods', 'gdesc2')) {
			pdo_query("ALTER TABLE ".tablename('tg_goods')." DROP `gdesc2`;");
		}
		if(pdo_fieldexists('tg_goods', 'gdesc3')) {
			pdo_query("ALTER TABLE ".tablename('tg_goods')." DROP `gdesc3`;");
		}
		if(pdo_fieldexists('tg_goods', 'gubtime')) {
			pdo_query("ALTER TABLE ".tablename('tg_goods')." DROP `gubtime`;");
		}
		if(!pdo_fieldexists('tg_goods', 'gdetaile')) {
			pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `gdetaile` longtext NOT NULL;");
		}
		if(!pdo_fieldexists('tg_goods', 'isnew')) {
			pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `isnew` INT NOT NULL;");
		}
		if(!pdo_fieldexists('tg_goods', 'isrecommand')) {
			pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `isrecommand` INT NOT NULL;");
		}
		if(!pdo_fieldexists('tg_goods', 'isdiscount')) {
			pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `isdiscount` INT NOT NULL;");
		}
		if(!pdo_fieldexists('tg_goods', 'hasoption')) {
			pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `hasoption` INT NOT NULL;");
		}
		if(!pdo_fieldexists('tg_order', 'sendtime')) {
			pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `sendtime` varchar(115) NOT NULL;");
		}
		if(!pdo_fieldexists('tg_order', 'gettime')) {
			pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `gettime` varchar(115) NOT NULL;");
		}
		if(!pdo_fieldexists('tg_order', 'gettime')) {
			pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `gettime` varchar(115) NOT NULL;");
		}
		if(!pdo_fieldexists('tg_order', 'addresstype')) {
			pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `addresstype` INT NOT NULL;");
		}
		if(!pdo_fieldexists('tg_order', 'optionid')) {
			pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `optionid` INT NOT NULL;");
		}
		if(!pdo_fieldexists('tg_order', 'checkpay')) {
			pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `checkpay` INT NOT NULL;");
		}
		pdo_query("DROP TABLE `ims_tg_refund_record`;");
		pdo_query("
		CREATE TABLE IF NOT EXISTS `ims_tg_group` (
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
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
		pdo_query("
		CREATE TABLE IF NOT EXISTS `ims_tg_refund_record` (
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
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");