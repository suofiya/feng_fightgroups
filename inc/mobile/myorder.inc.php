<?php
	session_start();
	global $_W, $_GPC;
	$this->getuserinfo();
	load()->model('mc');
	$this->cancleorder();
	$share_data = $this -> module['config'];
	$_SESSION['goodsid'] = '';
	$_SESSION['tuan_id'] = '';
	$_SESSION['groupnum'] = '';
	$op = $_GPC['op'];
	$opp = $_GPC['opp'];
	$openid = $_W['openid'];
	$type = $_GPC['type'];
	$orderno = $_GPC['orderno'];
	if ($type == 'cancel' && !empty($orderno)) {
		$ordermy = pdo_fetch("select * from" . tablename('tg_order') . "where orderno='{$orderno}' and uniacid={$_W['uniacid']}");
		$goodsmy = pdo_fetch("select * from" . tablename('tg_goods') . "where id='{$ordermy['g_id']}' and uniacid={$_W['uniacid']}");
		$ret = pdo_update('tg_order', array('status' => 5), array('orderno' => $orderno));
		/*取消订单模板消息*/
		require_once IA_ROOT . '/addons/feng_fightgroups/source/Message.class.php';
		$url = '';
		$sendmessage = new Message();
		$res = $sendmessage -> cancelorder($openid, $ordermy['price'], $goodsmy['gname'], $orderno, $this,$url);
		/*取消订单模板消息*/
	}
	if (empty($op)) {
		$op = 0;
	}
	if ($op == 0) {
		$sql = 'SELECT * FROM ' . tablename('tg_order') . ' WHERE openid = :openid  and uniacid = :uniacid ORDER BY id desc';
		//从订单信息表里面取得数据的sql语句
		$params = array(':openid' => $openid, ':uniacid' => $_W['uniacid']);
		$orders = pdo_fetchall($sql, $params);
		foreach ($orders as $key => $value) {
			$goods = pdo_fetch("select * from" . tablename('tg_goods') . "where id='{$value['g_id']}' and uniacid={$_W['uniacid']}");
			$option  = pdo_fetch("select title,productprice,marketprice,stock from " . tablename("tg_goods_option") . " where id=:id limit 1", array(":id" => $value['optionid']));
			$orders[$key]['gid'] = $goods['id'];
			$orders[$key]['gimg'] = $goods['gimg'];
			$orders[$key]['gname'] = $goods['gname'];
			$orders[$key]['optionname'] = $option['title'];
		}
	} elseif ($op == 1) {//获取当前用户待付款订单信息
		$sql = 'SELECT * FROM ' . tablename('tg_order') . ' WHERE openid = :openid  and uniacid = :uniacid  and status = :status ORDER BY id desc';
		//从订单信息表里面取得数据的sql语句
		$params = array(':openid' => $openid, ':uniacid' => $_W['uniacid'], ':status' => 0);
		$orders = pdo_fetchall($sql, $params);
		foreach ($orders as $key => $value) {
			$goods = pdo_fetch("select * from" . tablename('tg_goods') . "where id='{$value['g_id']}' and uniacid={$_W['uniacid']}");
			$orders[$key]['id'] = $goods['id'];
			$orders[$key]['gimg'] = $goods['gimg'];
			$orders[$key]['gname'] = $goods['gname'];
		}
	} elseif ($op == 3) {//获取当前用户待收货订单信息 在数据表里status = 2代表待收货
		$sql = 'SELECT * FROM ' . tablename('tg_order') . ' WHERE openid = :openid  and uniacid = :uniacid  and status = :status ORDER BY id desc';
		//从订单信息表里面取得数据的sql语句
		$params = array(':openid' => $openid, ':uniacid' => $_W['uniacid'], ':status' => 3);
		$orders = pdo_fetchall($sql, $params);
		foreach ($orders as $key => $value) {
			$goods = pdo_fetch("select * from" . tablename('tg_goods') . "where id='{$value['g_id']}' and uniacid={$_W['uniacid']}");
			$orders[$key]['gid'] = $goods['id'];
			$orders[$key]['gimg'] = $goods['gimg'];
			$orders[$key]['gname'] = $goods['gname'];
			$orders[$key]['freight'] = $goods['freight'];
	
		}
	}
	if($opp == 'qrcode'){
		$qrcodepath = "../attachment/feng_fightgroups/qrcode/" . $_W['uniacid'];
		if(!file_exists($qrcodepath)){
			$qrcodepath = "../addons/feng_fightgroups/qrcode/" . $_W['uniacid'];
		}
		$orderno = $_GPC['orderno'];
		$src = $qrcodepath. "/".$orderno.'.png';
		$data = array('src' => $src);
		echo  json_encode($data);exit;
	}
	include $this -> template('myorder');
?>