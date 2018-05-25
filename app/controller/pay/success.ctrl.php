<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * success.ctrl
 * 支付成功控制器
 */
defined('IN_IA') or exit('Access Denied');
wl_load()->model("order");
wl_load()->model("goods");

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$pagetitle = 'Resultados de pagamento';

if($op =='display'){
	$orderno = $_GPC['orderno'];
	$errno = $_GPC['errno'];
	$order = order_get_by_params(" orderno = {$orderno} ");
    $tuan_id = intval($order['tuan_id']);
    if (!empty($tuan_id)) {
        //$tuaninfo = group_get_by_params(" groupnumber = {$tuan_id} ");
        $goods = goods_get_by_params(" id = {$order['g_id']} ");  
        // 社区分享相关
        $config['share']['share_title'] = "R$ ".$goods['gprice'].", eu participei【".$goods['gname']."】do grupo，vamos junto！";
        $config['share']['share_url'] = app_url('order/group', array('tuan_id'=>$tuan_id));
        $config['share']['share_desc'] = $config['share']['share_title'];
        $config['share']['share_image'] = $goods['gimg'];
    } else {
        $tuaninfo = array();
    }
	include wl_template('pay/success');
}

