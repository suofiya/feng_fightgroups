<?php
	global $_W, $_GPC;
	$share_data = $this -> module['config'];
	$orderid = $_GPC['orderid'];
	if($_GPC['op']=='share'){
		include $this->template('share');exit;
	}
	if($_GPC['op']=='message'){
		$message = $_GPC['remark'];
		$othername = $_GPC['othername'];
		pdo_update("tg_order", array("message" => $message,'othername'=>$othername), array("id" => $orderid, "uniacid" => $_W['uniacid']));
		die(json_encode(array("result" => 1, "data" => $data)));
	}
	$this->getuserinfo();
	$type = $_GPC['type'];
	$order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE id ='{$orderid}'");
	$result =  pdo_fetch("select * from" . tablename('tg_member') . "where openid = :openid limit 1",array(':openid'=>$order['openid']));
	//判断是否团满或者过期
	$goods = pdo_fetch("select * from".tablename('tg_goods')."where uniacid={$_W['uniacid']} and id='{$order['g_id']}' ");
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
	$params['module'] = "feng_fightgroups";
	 
	
		if(!$this->inMobile) {
			message('支付功能只能在手机上使用');
		}
		$mypaytype=$params['mypaytype'];
		$params['module'] = $this->module['name'];
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];
				if($params['fee'] <= 0) {
			$pars['from'] = 'return';
			$pars['result'] = 'success';
			$pars['type'] = 'alipay';
			$pars['tid'] = $params['tid'];
			$site = WeUtility::createModuleSite($pars[':module']);
			$method = 'payResult';
			if (method_exists($site, $method)) {
				exit($site->$method($pars));
			}
		}

		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
		$log = pdo_fetch($sql, $pars);
		if(!empty($log) && $log['status'] == '1') {
			message('这个订单已经支付成功, 不需要重复支付.');
		}
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if(!is_array($setting['payment'])) {
			message('没有有效的支付方式, 请联系网站管理员.');
		}
		$pay = $setting['payment'];
		$pay['credit']['switch'] = false;
		$pay['delivery']['switch'] = false;
		if (!empty($pay['credit']['switch'])) {
			$credtis = mc_credit_fetch($_W['member']['uid']);
		}
		$iscard = pdo_fetchcolumn('SELECT iscard FROM ' . tablename('modules') . ' WHERE name = :name', array(':name' => $params['module']));
		$you = 0;
		if($pay['card']['switch'] == 2 && !empty($_W['openid'])) {
			if($_W['card_permission'] == 1 && !empty($params['module'])) {
				$cards = pdo_fetchall('SELECT a.id,a.card_id,a.cid,b.type,b.title,b.extra,b.is_display,b.status,b.date_info FROM ' . tablename('coupon_modules') . ' AS a LEFT JOIN ' . tablename('coupon') . ' AS b ON a.cid = b.id WHERE a.acid = :acid AND a.module = :modu AND b.is_display = 1 AND b.status = 3 ORDER BY a.id DESC', array(':acid' => $_W['acid'], ':modu' => $params['module']));
				$flag = 0;
				if(!empty($cards)) {
					foreach($cards as $temp) {
						$temp['date_info'] = iunserializer($temp['date_info']);
						if($temp['date_info']['time_type'] == 1) {
							$starttime = strtotime($temp['date_info']['time_limit_start']);
							$endtime = strtotime($temp['date_info']['time_limit_end']);
							if(TIMESTAMP < $starttime || TIMESTAMP > $endtime) {
								continue;
							} else {
								$param = array(':acid' => $_W['acid'], ':openid' => $_W['openid'], ':card_id' => $temp['card_id']);
								$num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('coupon_record') . ' WHERE acid = :acid AND openid = :openid AND card_id = :card_id AND status = 1', $param);
								if($num <= 0) {
									continue;
								} else {
									$flag = 1;
									$card = $temp;
									break;
								}
							}
						} else {
							$deadline = intval($temp['date_info']['deadline']);
							$limit = intval($temp['date_info']['limit']);
							$param = array(':acid' => $_W['acid'], ':openid' => $_W['openid'], ':card_id' => $temp['card_id']);
							$record = pdo_fetchall('SELECT addtime,id,code FROM ' . tablename('coupon_record') . ' WHERE acid = :acid AND openid = :openid AND card_id = :card_id AND status = 1', $param);
							if(!empty($record)) {
								foreach($record as $li) {
									$time = strtotime(date('Y-m-d', $li['addtime']));
									$starttime = $time + $deadline * 86400;
									$endtime = $time + $deadline * 86400 + $limit * 86400;
									if(TIMESTAMP < $starttime || TIMESTAMP > $endtime) {
										continue;
									} else {
										$flag = 1;
										$card = $temp;
										break;
									}
								}
							}
							if($flag) {
								break;
							}
						}
					}
				}
				if($flag) {
					if($card['type'] == 'discount') {
						$you = 1;
						$card['fee'] = sprintf("%.2f", ($params['fee'] * ($card['extra'] / 100)));
					} elseif($card['type'] == 'cash') {
						$cash = iunserializer($card['extra']);
						if($params['fee'] >= $cash['least_cost']) {
														$you = 1;
							$card['fee'] = sprintf("%.2f", ($params['fee'] -  $cash['reduce_cost']));
						}
					}
					load()->classs('coupon');
					$acc = new coupon($_W['acid']);
					$card_id = $card['card_id'];
					$time = TIMESTAMP;
					$randstr = random(8);
					$sign = array($card_id, $time, $randstr, $acc->account['key']);
					$signature = $acc->SignatureCard($sign);
					if(is_error($signature)) {
						$you = 0;
					}
				}
			}
		}

		if($pay['card']['switch'] == 3 && $_W['member']['uid']) {
						$cards = array();
			if(!empty($params['module'])) {
				$cards = pdo_fetchall('SELECT a.id,a.couponid,b.type,b.title,b.discount,b.condition,b.starttime,b.endtime FROM ' . tablename('activity_coupon_modules') . ' AS a LEFT JOIN ' . tablename('activity_coupon') . ' AS b ON a.couponid = b.couponid WHERE a.uniacid = :uniacid AND a.module = :modu AND b.condition <= :condition AND b.starttime <= :time AND b.endtime >= :time  ORDER BY a.id DESC', array(':uniacid' => $_W['uniacid'], ':modu' => $params['module'], ':time' => TIMESTAMP, ':condition' => $params['fee']), 'couponid');
				if(!empty($cards)) {
					$condition = '';
					if($iscard == 1) {
						$condition = " AND grantmodule = '{$params['module']}'";
					}
					foreach($cards as $key => &$card) {
						$has = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_coupon_record') . ' WHERE uid = :uid AND uniacid = :aid AND couponid = :cid AND status = 1' . $condition, array(':uid' => $_W['member']['uid'], ':aid' => $_W['uniacid'], ':cid' => $card['couponid']));
						if($has > 0){
							if($card['type'] == '1') {
								$card['fee'] = sprintf("%.2f", ($params['fee'] * $card['discount']));
								$card['discount_cn'] = sprintf("%.2f", $params['fee'] * (1 - $card['discount']));
							} elseif($card['type'] == '2') {
								$card['fee'] = sprintf("%.2f", ($params['fee'] -  $card['discount']));
								$card['discount_cn'] = $card['discount'];
							}
						} else {
							unset($cards[$key]);
						}
					}
				}
			}
			if(!empty($cards)) {
				$cards_str = json_encode($cards);
			}
		}
		$share_data = $this -> module['config'];
		$_W['page']['footer'] = $share_data['copyright'];
		$title = '';
		include $this->template('otherpay');
?>