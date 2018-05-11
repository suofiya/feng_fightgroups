<?php
	global $_W, $_GPC;
	$this->getuserinfo();
	$orderid = $_GPC['orderid'];
	$type = $_GPC['type'];
	if (empty($orderid)) {
        message('抱歉，参数错误！', '', 'error');
    }else{
    		$order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE id ='{$orderid}'");
			//判断是否团满或者过期
			$goods = pdo_fetch("select isshow from".tablename('tg_goods')."where uniacid={$_W['uniacid']} and id='{$order['g_id']}' ");
			$tuaninfo = pdo_fetch("SELECT * FROM".tablename('tg_group')."WHERE groupnumber = '{$order['tuan_id']}' and uniacid={$_W['uniacid']} ");
			$endtime = $tuaninfo['endtime'];
		    $time=time(); /*当前时间*/
		    $lasttime2 = $endtime - $time;//剩余时间（秒数）
		    if($order['is_tuan']==1){
		    	if($tuaninfo['groupstatus']!=3){
				message("该团已结束！");exit;
				}
				if($lasttime2<0){
					message("该团已过期！");exit;
				}
				if($goods['isshow']==0){
					message("该商品已下架！");exit;
				}
		    }
			
			
			$goods = pdo_fetch("SELECT * FROM " . tablename('tg_goods') . " WHERE id ='{$order['g_id']}'");
    		$params['tid'] = $order['orderno'];
    		$params['user'] = $_W['fans']['from_user'];
    		$params['fee'] = $order['pay_price'];
    		$params['title'] = $goods['gname'];
    		$params['ordersn'] = $order['orderno'];
			$params['mypaytype'] = $type;
    }
	$params['module'] = "feng_fightgroups";
	include $this->template('pay');
?>