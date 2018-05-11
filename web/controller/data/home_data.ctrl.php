<?php
	wl_load()->model('order');
	wl_load()->model('goods');
	$ops = array('display');
	$op_names = array('概要统计');
	foreach($ops as$key=>$value){
		permissions('do', 'ac', 'op', 'data', 'home_data', $ops[$key], '数据中心', '概要统计', $op_names[$key]);
	}
	$op = in_array($op, $ops) ? $op : 'display';
	$data = pdo_fetch("select * from".tablename('tg_puv')."where uniacid={$_W['uniacid']} limit 1");
	$goods = goods_get_list(array(''));
	$orders = pdo_fetchall("select id from".tablename('tg_order')."where uniacid={$_W['uniacid']} and status in(0,1,2,3,4,6,7) ");
	$ordersnum = count($orders);
//	wl_debug($orders);
	$money = 0;
	foreach($orders as$key=>$value){
		$money += $value['price'];
	}
	$ystd = strtotime('-1 day');$ystd = date("Y-m-d", $ystd);$ystd = strtotime($ystd." 00:00"); 
	$today=strtotime(date('Y-m-d'));
	$yesterdaypv = pdo_fetchall("select id from".tablename('tg_puv_record')."where uniacid={$_W['uniacid']} and createtime>'{$ystd}' and createtime<'{$today}'");
	$ypv = count($yesterdaypv);
	$yesterdayuv = pdo_fetchall("select distinct openid from".tablename('tg_puv_record')."where uniacid={$_W['uniacid']} and createtime>'{$ystd}' and createtime<'{$today}'");
	$yuv = count($yesterdayuv);
	
	$yesterdayorders1 =  pdo_fetchall("select id from".tablename('tg_order')."where uniacid={$_W['uniacid']} and ptime>'{$ystd}' and ptime<'{$today}' and status in('1,2,3,4,6,7') ");
	$yorders1 = count($yesterdayorders1);
	$yesterdayorders2 =  pdo_fetchall("select id from".tablename('tg_order')."where uniacid={$_W['uniacid']} and successtime >'{$ystd}' and successtime<'{$today}' and status in('1,2,3,4,6,7') ");
	$yorders2 = count($yesterdayorders2);
	$yesterdayorders3 =  pdo_fetchall("select id from".tablename('tg_order')."where uniacid={$_W['uniacid']} and sendtime>'{$ystd}' and sendtime<'{$today}' and status in('1,2,3,4,6,7') ");
	$yorders3 = count($yesterdayorders3);
	include wl_template('data/home_data');