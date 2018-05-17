<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * addmanage.ctrl
 * 我的地址控制器
 */
defined('IN_IA') or exit('Access Denied');
wl_load()->model('address');

// 判断用户是否已注册
if (pdd_isLoginedStatus() == false) {
    header("Location: ".app_url('member/login'));exit;  
}

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

session_start();
$goodsid = $_SESSION['goodsid'];
$openid = $_W['openid'];
$pagetitle = !empty($config['tginfo']['sname']) ? '我的收货地址endereço - '.$config['tginfo']['sname'] : '我的收货地址endereço';

if($goodsid){
	$bakurl = app_url('order/orderconfirm');
}else{
	$bakurl = app_url('member/home');
}

if($op == 'display'){
	$address = address_get_list("openid = '{$openid}'");
	include wl_template('address/addmanage');
}

if($op == 'select'){
	$id = $_GPC['id'];
	address_set_by_id($id,$openid);
	header("location:".app_url('address/addmanage'));
}
