<?php
define('IN_MOBILE', true);
wl_load()->func('template');
wl_load()->model('permissions');
global $_W,$_GPC;
$controller = $_GPC['do'];
$action = $_GPC['ac'];
$op = $_GPC['op'];

if(empty($controller) || empty($action)) {
	$_GPC['do'] = $controller = 'store';
	$_GPC['ac'] = $action = 'setting';
}



$getlistFrames = 'get'.$controller.'Frames';
$frames = $getlistFrames();
$top_menus = get_top_menus();

$file = TG_WEB . 'controller/'.$controller.'/'.$action.'.ctrl.php';
if (!file_exists($file)) {
	header("Location: index.php?i={$_W['uniacid']}&c=entry&do=home&ac=index&m=feng_fightgroups");
	exit;
}

require $file;

