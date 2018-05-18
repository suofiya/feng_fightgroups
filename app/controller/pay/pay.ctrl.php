<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
require IA_ROOT.'/framework/bootstrap.inc.php';
require IA_ROOT.'/app/common/bootstrap.app.inc.php';
load()->app('common');
load()->app('template');

$sl = $_GPC['ps'];
$params = @json_decode(base64_decode($sl), true);
if($_GPC['done'] == '1') {
	$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `plid`=:plid';
	$pars = array();
	$pars[':plid'] = $params['tid'];
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
		}
	}
}

$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `plid`=:plid';
$log = pdo_fetch($sql, array(':plid' => $params['tid']));
if(!empty($log) && $log['status'] != '0') {
	exit('Esse pedido ja foi pago.');
}
$auth = sha1($sl . $log['uniacid'] . $_W['config']['setting']['authkey']);
if($auth != $_GPC['auth']) {
	exit('erro dos dados.');
}
load()->model('payment');
$_W['uniacid'] = intval($log['uniacid']);
$_W['openid'] = intval($log['openid']);
$setting = uni_setting($_W['uniacid'], array('payment'));
if(!is_array($setting['payment'])) {
	exit('erro dos dados.');
}
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
		message("desculpa，pagamento nao aprovado，foi erro do sistema，tente novamente。");
	}
	message("desculpa，pagamento nao aprovado，motivo：“{$wOpt['errno']}:{$wOpt['message']}”。contate-nos。");
	exit;
}
?>
<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	WeixinJSBridge.invoke('getBrandWCPayRequest', {
		'appId' : '<?php echo $wOpt['appId'];?>',
		'timeStamp': '<?php echo $wOpt['timeStamp'];?>',
		'nonceStr' : '<?php echo $wOpt['nonceStr'];?>',
		'package' : '<?php echo $wOpt['package'];?>',
		'signType' : '<?php echo $wOpt['signType'];?>',
		'paySign' : '<?php echo $wOpt['paySign'];?>'
	}, function(res) {
		if(res.err_msg == 'get_brand_wcpay_request:ok') {
			location.search += '&done=1';
		} else {
			//alert('启动微信支付失败, 请检查你的支付参数. 详细错误为: ' + res.err_msg);
			history.go(-1);
		}
	});
}, false);
</script>
