<?php 
/**
 * [weliam] Copyright (c) 2016/4/4
 * 手机端支付控制器
 */
	defined('IN_IA') or exit('Access Denied');
    error_reporting(E_ALL);
	$moduels = uni_modules();
	wl_load()->model('goods');
	wl_load()->model('order');
	load()->func('communication');
	load()->model('payment');
	if(!empty($_GPC['orderno'])){
		$order = order_get_by_params(" orderno = '{$_GPC['orderno']}' ");
		$goods = goods_get_by_params(" id = {$order['g_id']} ");
	}else{
		$message = "erro，falta numero do pedido.";
		die(json_encode(array('errno'=>1,'message'=>$message)));
	}
	if($order['pay_price'] <= 0) {
		$message = "pagamento nao provado,tem que acima do R$0.00.";
		die(json_encode(array('errno'=>1,'message'=>$message)));
	}
	$params['tid'] = $_GPC['orderno'];
	$params['user'] = $_W['fans']['from_user'];
	$params['fee'] = $order['pay_price'];
	$params['title'] = $goods['gname'];
	$params['ordersn'] = $_GPC['orderno'];
	$params['module'] = "feng_fightgroups";
	if($_GPC['done'] == '1') {
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `tid`=:tid';
		$pars = array();
		$pars[':tid'] = $params['tid'];
		$log = pdo_fetch($sql, $pars);
		if(!empty($log)) {
			if (!empty($log['tag'])) {
				$tag = iunserializer($log['tag']);
				$log['uid'] = $tag['uid'];
			}
			$site = WeUtility::createModuleSite($log['module']);
			if(!is_error($site)) {
				$method = 'payResult';
				if (method_exists($site, $method)) {
					$ret = array();
					$ret['weid'] = $log['uniacid'];
					$ret['uniacid'] = $log['uniacid'];
					$ret['result'] = 'success';
					$ret['type'] = $log['type'];
					$ret['from'] = 'return';
					$ret['tid'] = $log['tid'];
					$ret['uniontid'] = $log['uniontid'];
					$ret['user'] = $log['openid'];
					$ret['fee'] = $log['fee'];
					$ret['tag'] = $tag;
					$ret['is_usecard'] = $log['is_usecard'];
					$ret['card_type'] = $log['card_type'];
					$ret['card_fee'] = $log['card_fee'];
					$ret['card_id'] = $log['card_id'];
					exit($site->$method($ret));
				}
			}else{
				wl_debug("error");
			}
		}else{
			wl_debug("logkong");
		}
	}
if ($_W['isajax']) {
	//生成paylog记录
	$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `openid`=:openid AND `module`=:module AND `tid`=:tid';
	$pars = array();
	$pars[':openid'] = $_W['openid'];
	$pars[':module'] = $params['module'];
	$pars[':tid'] = $params['tid'];
	$log = pdo_fetch($sql, $pars);
	$data = array(
		'uniacid' => $_W['uniacid'],
		'acid' => $_W['acid'],
		'openid' => $_W['openid'],
		'module' => $params['module'],
		'tid' => $params['tid'],
		'fee' => $params['fee'],
		'card_fee' => $params['fee'],
		'status' => '0',
		'is_usecard' => '0',
	);
	if (empty($log)) {
		pdo_insert('core_paylog', $data);
	}
	$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
	if(!is_array($setting['payment'])) {
		$message = "FORMA DE PAGAMENTO INVALIDO, CONTATE-NOS.";
		die(json_encode(array('errno'=>1,'message'=>$message)));
	}
	if (empty($_W['member']['uid'])) {
		$setting['payment']['credit']['switch'] = false;
	}
	if (!empty($setting['payment']['credit']['switch'])) {
		$credtis = mc_credit_fetch($_W['member']['uid']);
	}
	/*微信卡券*/
	if($setting['payment']['card']['switch'] == 2 && !empty($_W['openid'])) {}
	/*微擎卡券*/
	if($setting['payment']['card']['switch'] == 3 && $_W['member']['uid']) {}
	/*tg卡券*/
	if(empty($params) || !array_key_exists($params['module'], $moduels)) {
		wl_message('erro ao acesso.');
	}
	$dos = array();
	if(!empty($setting['payment']['credit']['switch'])) {
		$dos[] = 'credit';
	}
	if(!empty($setting['payment']['alipay']['switch'])) {
		$dos[] = 'alipay';
	}
	if(!empty($setting['payment']['wechat']['switch'])) {
		$dos[] = 'wechat';
	}
	$type = in_array($_GPC['paytype'], $dos) ? $_GPC['paytype'] : '';
	if(empty($type)) {
		$message = "FORMA DE PAGAMENTO INVALIDO, CONTATE-NOS";
		die(json_encode(array('errno'=>1,'message'=>$message)));
	}
	if(!empty($type)) {
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `openid`=:openid AND `module`=:module AND `tid`=:tid';
		$pars  = array();
		$pars[':openid'] = $_W['openid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];
		$log = pdo_fetch($sql, $pars);
		if(!empty($log) && $type != 'credit' && $log['status'] != '0') {
			$message = "esse pedido ja foi pago.!";
			die(json_encode(array('errno'=>0,'message'=>$message)));
		}
		$moduleid = pdo_fetchcolumn("SELECT mid FROM ".tablename('modules')." WHERE name = :name", array(':name' => $params['module']));
		$moduleid = empty($moduleid) ? '000000' : sprintf("%06d", $moduleid);
		$record = array();
		$record['type'] = $type;
		$record['uniontid'] = date('YmdHis').$moduleid.random(8,1);
		pdo_update('core_paylog', $record, array('plid' => $log['plid']));
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `openid`=:openid AND `module`=:module AND `tid`=:tid';
		$log = pdo_fetch($sql, $pars);
		//微信支付
		if($type == 'wechat') {
			$wechat = $setting['payment']['wechat'];
			$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
			$row = pdo_fetch($sql, array(':acid' => $wechat['account']));
			$wechat['appid'] = $row['key'];
			$wechat['secret'] = $row['secret'];
			$params = array(
				'tid' => $log['tid'],
				'fee' => $log['card_fee'],
				'user' => $log['openid'],
				'title' => $params['title'],
				'uniontid' => $log['uniontid'],
			);
			$wOpt = wechat_build($params, $wechat);
			if (is_error($wOpt)) {
				if ($wOpt['message'] == 'invalid out_trade_no' || $wOpt['message'] == 'OUT_TRADE_NO_USED') {
					$id = date('YmdH');
					pdo_update('core_paylog', array('plid' => $id), array('plid' => $log['plid']));
					pdo_query("ALTER TABLE ".tablename('core_paylog')." auto_increment = ".($id+1).";");
					wl_message("Desculpa，pagamento nao aprovado,foi erro do sistema,tente mais tarde。");
				}
				$message = "Desculpa，pagamento nao aprovado，motivo：“{$wOpt['errno']}:{$wOpt['message']}”。contate-nos。";
				die(json_encode(array('errno'=>0,'message'=>$message)));
			}
		
		}
	}
	$message = "pagamento com sucesso!";
	die(json_encode(array('errno'=>0,'message'=>$message,'data'=>$wOpt)));
}
?>
