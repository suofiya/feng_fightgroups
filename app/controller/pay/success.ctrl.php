<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * success.ctrl
 * 支付成功控制器
 */
defined('IN_IA') or exit('Access Denied');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$pagetitle = '支付结果';
wl_load()->model("order");
if($op =='display'){
	$orderno = $_GPC['orderno'];
	$errno = $_GPC['errno'];
	$order = order_get_by_params(" orderno = {$orderno} ");
	include wl_template('pay/success');
}

