<?php
defined('IN_IA') or exit('Access Denied');

require IA_ROOT. '/addons/feng_fightgroups/core/common/defines.php';
require TG_CORE . 'class/wlloader.class.php';
wl_load()->func('global');
wl_load()->func('pdo');
wl_load()->func('tpl');
wl_load()->func('message');
class Feng_fightgroupsModuleSite extends WeModuleSite {
	public function __call($name, $arguments) {
		global $_W;
		$isWeb = stripos($name, 'doWeb') === 0;
		$isMobile = stripos($name, 'doMobile') === 0;
		if($isWeb || $isMobile) {
			$dir = IA_ROOT . '/addons/' . $this->modulename . '/';
			if($isWeb) {
				$dir .= 'web/';
				$controller = strtolower(substr($name, 5));
			}
			if($isMobile) {
				$dir .= 'app/';
				$controller = strtolower(substr($name, 8));
			}
			$file = $dir . 'index.php';
			if(file_exists($file)) {
				require $file;
				exit;
			}
		}
		trigger_error("访问的方法 {$name} 不存在.", E_USER_WARNING);
		return null;
	}
	/*支付结果返回*/
	public function payResult($params) {
		global $_W, $_GPC;
		wl_load()->model('goods');
		wl_load()->model('merchant');
		wl_load()->model('order');
		wl_load()->model('group');
		load()->model('mc');
		load() -> model('account');
		/*写入文件*/
		$pay_log = $params;$pay_log['createtime']= date("Y-m-d H:i:s",TIMESTAMP);
		file_put_contents(TG_DATA."payresult.log", var_export($pay_log, true).PHP_EOL, FILE_APPEND);
		$success = FALSE;
		$order_out = order_get_by_params(" orderno = '{$params['tid']}'");
		$goodsInfo = goods_get_by_params(" id = {$order_out['g_id']}");
		$nowtuan = group_get_by_params(" groupnumber = '{$order_out['tuan_id']}'");
		$merchan = merchant_get_by_params(" id = {$order_out['merchantid']}");
		if(empty($order_out['status'])){
			$data = array('status' => $params['result'] == 'success' ? 1 : 0);
			if($params['is_usecard']==1){
				$fee = $params['card_fee'];
				$data['is_usecard'] = 1;
			}else{
				$fee = $params['fee'];
			}
			$paytype = array('credit' => 1, 'wechat' => 2, 'alipay' => 2, 'delivery' => 3,'ebanx'=>4);
			$data['pay_type'] = $paytype[$params['type']];
			if ($params['type'] == 'wechat') {
				$data['transid'] = $params['tag']['transaction_id'];
			}
			$data['ptime'] = TIMESTAMP;
			$data['price'] = $fee;
			$data['starttime'] = TIMESTAMP;
			$data['credits'] = $goodsInfo['$goodsInfo'];
			if (!empty($nowtuan)) {
				if ($nowtuan['lacknum'] == 0 ) {
					$success = TRUE;
				}
			}
			/*后台通知，修改状态*/
			if ($params['result'] == 'success' && $params['from'] == 'notify') {
				file_put_contents(TG_DATA."params.log", var_export($pay_log, true).PHP_EOL, FILE_APPEND);
				/*处理优惠券*/
				$is_usecard = $order_out['is_usecard'];
/*				if($is_usecard==1){
					wl_load()->model('activity');
					$coupon_id = $order_out['couponid'];
					pdo_update('tg_coupon',array('use_time'=>$params['paytime']), array('coupon_template_id' => $coupon_id));
					coupon_quantity_issue_increase($coupon_id, 1);
				}*/
				/*处理代付*/
/*				if($order_out['openid'] != $params['user']){
					pdo_update('tg_order',array('ordertype'=>1,'helpbuy_opneid'=>$params['user']), array('orderno' => $params['tid']));
					$time = date("Y-m-d H:i:s",$params['paytime']);
					$url = app_url('order/order/detail', array('id' => $order_out['id']));
					daipay_success($order_out['openid'], $fee, $order_out['othername'], $params['tid'], $goodsInfo['gname'], $time, $order_out['message'], $url);
					daipay_success($params['user'], $fee, $order_out['othername'], $params['tid'], $goodsInfo['gname'], $time, $order_out['message'], $url);
				}*/
				if($order_out['couponid'] > 0){
					pdo_update('tg_coupon',array('uid' => 2, 'use_time' => TIMESTAMP), array('id' => $order_out['couponid']));
				}
				/*更新订单状态*/
				pdo_update('tg_order', $data, array('orderno' => $params['tid']));
				
				if(!$success){
					/*支付成功通知*/
					$url = app_url('order/order/detail', array('id' => $order_out['id']));
					pay_success($order_out['openid'], $fee, $goodsInfo['gname'], $url);
					if ($order_out['is_tuan'] == 0) {
							pdo_update('tg_order', array('status' => 2), array('orderno' => $params['tid']));
							/*处理积分*/
							if(!empty($goodsInfo['credits'])){
								wl_load()->model('credit');
								wl_load()->model('member');
								wl_load()->model('setting');
								checkMember($order_out['openid']);
								$member = getMember($order_out['openid']);
								$setting=setting_get_by_name("member");
								$credit_type = $setting['credit_type']?$setting['credit_type']:1;
								credit_update_credit1($member['uid'],$goodsInfo['credits'],$credit_type,"购买积分【".$goodsInfo['gname']."】");
							}
						}else{
							if ($nowtuan['lacknum'] > 0) {
								pdo_update('tg_group', array('lacknum' => $nowtuan['lacknum'] - 1), array('groupnumber' => $order_out['tuan_id']));
							}
						}
						/*更改库存*/
					if ($goodsInfo['gnum'] == 1) {
							goods_update_by_params(array('gnum' => $goodsInfo['gnum'] - 1, 'salenum' => $goodsInfo['salenum'] + 1, 'isshow' => 3), array('id' => $order_out['g_id']));
						}elseif(!empty($goodsInfo['gnum'])){
							goods_update_by_params(array('gnum' => $goodsInfo['gnum'] - $order_out['gnum'], 'salenum' => $goodsInfo['salenum'] + $order_out['gnum']), array('id' => $order_out['g_id']));
						}
					/*更改商家销售量*/
					$merchant = merchant_get_by_params(" id = '{$goodsInfo['merchantid']}' ");
					if($merchant){
						merchant_update_amount($fee, $merchant['id']);
						merchant_update_by_params(array('salenum' => $merchant['salenum'] + $order_out['gnum']), array('id' => $merchant['id']));
					}
					$now = group_get_by_params(" groupnumber = '{$order_out['tuan_id']}'");
					if (!empty($now) && $now['lacknum'] == 0) {
						/*团成功通知*/
						$url = app_url('order/group', array('tuan_id' => $order_out['tuan_id']));
						group_success($order_out['tuan_id'],$url);
						group_update_by_params(array('groupstatus' => 2,'successtime'=>TIMESTAMP), array('groupnumber' => $now['groupnumber']));
						order_update_by_params(array('status' => 2,'successtime'=>TIMESTAMP), array('tuan_id' => $now['groupnumber'], 'status' => 1));
						/*处理积分*/
						if(!empty($goodsInfo['credits'])){
							wl_load()->model('credit');
							wl_load()->model('member');
							wl_load()->model('setting');
							$orders = pdo_fetchall("select openid from".tablename('tg_order')."where uniacid=:uniacid and tuan_id=:tuan_id and status=:status",array(':uniacid'=>$order_out['uniacid'],':tuan_id'=>$order_out['tuan_id'],':status'=>2));
							foreach($orders as$key=>$value){
								checkMember($value['openid']);
								$member = getMember($value['openid']);
								$setting=setting_get_by_name("member");
								$credit_type = $setting['credit_type']?$setting['credit_type']:1;
								credit_update_credit1($member['uid'],$goodsInfo['credits'],$credit_type,"购买积分【".$goodsInfo['gname']."】");
							}
						}
					}
			  	}else{
			  		order_update_by_params(array('status'=>6,'is_tuan'=>2), array('orderno' => $params['tid']));
			  	}
			}
		}
				/*前台通知*/
		if ($params['result'] == 'success' && $params['from'] == 'return') {
		 	if($order_out['is_tuan'] == 2){
				echo "<script>location.href='". app_url('pay/success',array('orderno'=>$order_out['orderno'],'errno'=>2))."';</script>";
				exit;
			}elseif($order_out['is_tuan'] == 1){
				echo "<script>  location.href='" .app_url('order/group', array('tuan_id' => $order_out['tuan_id'])) . "';</script>";
				exit ;
			}elseif($order_out['is_tuan'] == 0){
				echo "<script>  location.href='" . app_url('pay/success',array('orderno'=>$order_out['orderno'])) . "';</script>";
				exit ;
			}
		}
 	}
}