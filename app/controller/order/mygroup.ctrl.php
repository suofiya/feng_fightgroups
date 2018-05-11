<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * goods.ctrl
 * 我的团控制器
 */
defined('IN_IA') or exit('Access Denied');
wl_load()->model('goods');
wl_load()->model('merchant');
wl_load()->model('order');

$_SESSION['goodsid']='';
$_SESSION['tuan_id']='';
$_SESSION['groupnum']='';
$op = $_GPC['op'];
$content = '';
$pagetitle = !empty($config['tginfo']['sname']) ? '我的团 - '.$config['tginfo']['sname'] : '我的团';

if(!empty($op)){
	$content .= " and groupstatus = '{$op}' ";
}else{
	$content .= " and groupstatus = 3 ";
}
$reslut = $_GPC['result'];
$share_data = $this->module['config'];
$orders = pdo_fetchall("SELECT * FROM " . tablename('tg_order') . " WHERE uniacid ='{$_W['uniacid']}' and openid='{$_W['openid']}' and is_tuan in(1,2,3) and status in(1,2,3,4,6,7) order by ptime desc");
foreach ($orders as $key => $order) {
	$goods = pdo_fetch("SELECT * FROM ".tablename('tg_goods')."WHERE id = '{$order['g_id']}'");
	$thistuan = pdo_fetch("SELECT * FROM ".tablename('tg_group')."WHERE groupnumber = '{$order['tuan_id']}' $content");
	if(empty($thistuan)){
		unset($orders[$key]);
	}else{
		$orders[$key]['groupnum'] = $goods['groupnum'];
		if(!empty($thistuan['price'])){
			$orders[$key]['gprice'] = $thistuan['price'];
		}else{
			$orders[$key]['gprice'] = $goods['gprice'];
		}
		
		$orders[$key]['gid'] = $goods['id'];
		$orders[$key]['gname'] = $goods['gname'];
		$orders[$key]['gimg'] = $goods['gimg'];
        $orders[$key]['itemnum'] = $thistuan['lacknum'];
		$orders[$key]['groupstatus'] = $thistuan['groupstatus'];
	}
}
include wl_template('order/mygroup');