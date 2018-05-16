<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * index.ctrl
 * 个人中心控制器
 */
defined('IN_IA') or exit('Access Denied');

// 判断用户是否已注册
if (pdd_isLoginedStatus() == false) {
    header("Location: ".app_url('member/login'));exit;  
}

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle = !empty($config['tginfo']['sname']) ? '个人中心 - '.$config['tginfo']['sname'] : '个人中心';

if($op =='display'){
	$member = getMember($openid);
	if(!$member['credit1']){
		$member['credit1'] = '0.00';
	}
	if(!$member['credit2']){
		$member['credit2'] = '0.00';
	}
	$tatal = pdo_select_count('tg_coupon',array('openid' => $openid, 'uniacid' => $_W['uniacid']));
	include wl_template('member/home');
}