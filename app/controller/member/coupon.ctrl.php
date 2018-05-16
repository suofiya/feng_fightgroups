<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * coupon.ctrl
 * 优惠券控制器
 */
defined('IN_IA') or exit('Access Denied');
wl_load()->model('activity');

// 判断用户是否已注册
if (pdd_isLoginedStatus() == false) {
	header("Location: ".app_url('member/login'));exit;	
}

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if($op =='display'){
	$where1 = "SELECT * FROM ".tablename('tg_coupon').' WHERE `openid` = :openid AND `use_time` != 0 ORDER BY `end_time` DESC ';
	$params1 = array(
		':openid' => $openid
	);
	//已过期
	$where2 = "SELECT * FROM ".tablename('tg_coupon').' WHERE `openid` = :openid AND `use_time` < :use_time AND `end_time` < :time ORDER BY `end_time` DESC ';
	$params2 = array(
		':openid' => $openid,
		':use_time' => 0,
		':time' => TIMESTAMP
	);
	//未使用
	$where3 = "SELECT * FROM ".tablename('tg_coupon').' WHERE `openid` = :openid AND `use_time` = :use_time AND `start_time` < :now1 AND `end_time` > :now2 ORDER BY `end_time` DESC ';
	$params3 = array(
		':openid' => $openid,
		':use_time' => 0,
		':now1' => TIMESTAMP,
		':now2' => TIMESTAMP
	);
	$pagetitle = !empty($config['tginfo']['sname']) ? '优惠券列表 - '.$config['tginfo']['sname'] : '优惠券列表';
	$coupon1 = pdo_fetchall($where1, $params1);
	if($coupon1){
		foreach ($coupon1 as $key1 => $value1) {
			$coupon1[$key1]['end_time'] = date('Y-m-d', $value1['end_time']);
		}
	}
	$coupon2 = pdo_fetchall($where2, $params2);
	if($coupon2){
		foreach ($coupon2 as $key2 => $value2) {
			$coupon2[$key2]['end_time'] = date('Y-m-d', $value2['end_time']);
		}
	}
	$coupon3 = pdo_fetchall($where3, $params3);
	if($coupon3){
		foreach ($coupon3 as $key3 => $value3) {
			$coupon3[$key3]['end_time'] = date('Y-m-d', $value3['end_time']);
		}
	}
	include wl_template('member/coupon_list');
}

if($op =='detail'){
	$id = intval($_GPC['id']);
	if($id){
		$coupon = coupon($id);
		$pagetitle = $coupon['name'];
	}else{
		message('优惠券不存在！');
	}
	include wl_template('member/coupon_detail');
}

if($op =='get'){
	$id = intval($_GPC['id']);
	if($id){
		$coupon = coupon_template($id);
		$pagetitle = $coupon['name'];
	}else{
		message('优惠券不存在！');
	}
	
	include wl_template('member/coupon_get');
}

if($op =='post'){
	$id = intval($_GPC['id']);
	if($id){
		$coupon = coupon_grant($openid,$id);
		if($coupon['errno'] != 1){
			wl_json(1);
		}else{
			wl_json(0,$coupon['message']);
		}
	}else{
		wl_json(0,'缺少优惠券id参数');
	}
}