<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * paytype.ctrl
 * 支付方式控制器
 */

session_start();
defined('IN_IA') or exit('Access Denied');
require IA_ROOT.'/payment/ebanx/autoload.php';
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pagetitle = 'forma de pagamento';
wl_load()->model("order");
wl_load()->model("member");
wl_load()->model("goods");
wl_load()->model('setting');
wl_load()->model('activity');

$orderno = $_GPC['orderno'];
$setting=setting_get_by_name("helpbuy");
$order = order_get_by_params(" orderno = '{$orderno}' ");
$_SESSION['goodsid'] = $order['g_id'];
$activity_used  = FALSE;
if(!empty($order['couponid']) && $order['is_usecard']==1){
	$res=coupon_handle($order['openid'],$order['couponid'],$order['pay_price']+$order['discount_fee']);
	if(is_array($res)){
		$activity_used = TRUE;
		order_update_by_params(array('pay_price'=>$order['pay_price']+$order['discount_fee'],'couponid'=>'','is_usecard'=>0), array('orderno'=>$orderno));
		$order = order_get_by_params(" orderno = '{$orderno}' ");
	}
	
}
if($op =='display'){
	$helppay = FALSE;
	if($order['status']!=0 && $order['status']!=5){
		message("Esse pedido ja foi pago.");
	}
	$goods = goods_get_by_params(" id={$order['g_id']} ");
	if($setting['helpbuy']==1){
		$helpbuy_message = pdo_fetch("select * from".tablename('tg_helpbuy')."where uniacid={$_W['uniacid']}  order by rand() limit 1");
		if(!empty($helpbuy_message)){
			$message=$helpbuy_message['name'];
		}else{
			$message='Aguarde a seu compra!....';
		} 
		$config['share']['share_title'] = "não perca seu desconto especial :";
		$config['share']['share_desc'] = $message;
		$config['share']['share_url'] = app_url('pay/paytype', array('orderno'=>$orderno));
		$config['share']['share_image'] = $goods['gimg'];
		if($order['openid']!=$_W['openid']){
			$helppay = TRUE;
			$member = member_get_by_params(" openid='{$order['openid']}' ");
		}
	}
	include wl_template('pay/paytype');
}
if ($_W['isajax'] && $op =='ajax') {
/*	if($_W['fans']['follow'] !=1 &&  $config['base']['guanzhu_buy']==1){
		die(json_encode(array('errno'=>4,'message'=>"您还未关注,不能购买.")));
	}*/
	$res = coupon_handle($order['openid'],$order['couponid'],$order['pay_price']);
	wl_load()->model('group');
	$checkpay = $_GPC['checkpay'];
	if($checkpay=="8"){
		order_update_by_params(array('checkpay'=>$checkpay), array('orderno'=>$orderno));
		$order = order_get_by_params(" orderno = '{$orderno}' ");
	}
	$nowtuan = group_get_by_params(" groupnumber = '{$order['tuan_id']}'");
	if(!empty($nowtuan)){
		if ($nowtuan['groupstatus'] != 3) {
			die(json_encode(array('errno'=>1,'message'=>"o grupo concluido.","group"=>$nowtuan)));
		}
	}
	if(!empty($order['status'])){
		die(json_encode(array('errno'=>2,'message'=>"Esse pedido ja foi pago.","group"=>$nowtuan)));
	}
/*	if($order['checkpay']=="9"){
		die(json_encode(array('errno'=>3,'message'=>"此订单正在支付,请稍等.","group"=>$nowtuan)));
	}*/
	order_update_by_params(array('message'=>$_GPC['remark'],'othername'=>$_GPC['othername'],'checkpay'=>$checkpay), array('orderno'=>$orderno));
	die(json_encode(array('errno'=>0,'message'=>"pagamento com sucesso.")));
}

