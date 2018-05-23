<?php
define('IN_MOBILE', true);

global $_W,$_GPC;
wl_load()->model('setting');
wl_load()->model('member');
wl_load()->model('group');
// 非微信端用户使用登录注册制
/*
if ($_W['container'] != 'wechat') {
	pdd_initMemberState($_SESSION['openid']);
	updategourp(); //更新团
	$openid = pdd_getSessionOpenID();
} else {
	checkMember(); //检查用户信息
	updategourp(); //更新团
	$openid = getOpenid();
}
*/
pdd_initMemberState($_SESSION['openid']);
updategourp(); //更新团
$openid = pdd_getSessionOpenID();

//
$config = tgsetting_load();
puvindex($openid);
if($config['refund']['auto_refund']==1){
	check_refund();
}
// wl_debug($_SESSION);
// var_dump('----');
// wl_debug($_W);

$controller = $_GPC['do'];
$action = $_GPC['ac'];
if (empty($controller) || empty($action)) {
	$_GPC['do'] = $controller = 'goods';
	$_GPC['ac'] = $action = 'list';
}

$file = TG_APP . 'controller/'.$controller.'/'.$action.'.ctrl.php';
if (!file_exists($file)) {
	header("Location: index.php?i={$_W['uniacid']}&c=entry&do=goods&ac=list&m=feng_fightgroups");
	exit;
}
if ($action != 'group' && $action != 'detail' && $action != 'orderconfirm' && $action != 'addmanage' && $action != 'createadd' && $action != 'cash' && $action != 'paytype' && $action != 'login' && $action != 'register' && $action != 'fblogin') {
	session_start();
	unset($_SESSION['goodsid']);
	unset($_SESSION['tuan_id']);
	unset($_SESSION['groupnum']);
	unset($_SESSION['optionid']);
	unset($_SESSION['num']);
}

require $file;
