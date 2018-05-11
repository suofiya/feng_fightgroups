<?php
$orderno = $_GPC['mid'];
$result = $_GPC['result'];
$order = pdo_fetch("select * from".tablename('tg_order')."where uniacid='{$_W['uniacid']}' and orderno = '{$orderno}'");
$goods = pdo_fetch("select *from".tablename('tg_goods')."where id = '{$order['g_id']}' and uniacid='{$_W['uniacid']}'");
$is_hexiao_member = FALSE;
$store_ids = explode(',',substr($goods['hexiao_id'],0,strlen($goods['hexiao_id'])-1)); 
 //*判断是否是核销人员*/
$hexiao_member = pdo_fetch("select *from".tablename('tg_saler')."where openid='{$_W['openid']}' and  uniacid='{$_W['uniacid']}' and status=1 and merchantid={$goods['merchantid']}");

if($hexiao_member){
	if($hexiao_member['storeid']==''){
		$is_hexiao_member = TRUE;
	}else{
		if(empty($store_ids)){
			$is_hexiao_member = TRUE;
		}else{
			$hexiao_ids = explode(',', substr($hexiao_member['storeid'],0,strlen($hexiao_member['storeid'])-1)); 
			foreach($hexiao_ids as$key=> $value){
				if(in_array($value,$store_ids)){
					$is_hexiao_member = TRUE;
				}
			}
		}
		
	}
	if(!empty($hexiao_member['merchantid']) && !empty($goods['merchantid'])){
		if($hexiao_member['merchantid'] != $goods['merchantid']){
			$is_hexiao_member = FALSE;
		}
	}
}
//门店信息
$storesids = explode(",", $goods['hexiao_id']);
foreach($storesids as$key=>$value){
	if($value){
		$stores[$key] =  pdo_fetch("select * from".tablename('tg_store')."where id ='{$value}' and uniacid='{$_W['uniacid']}'");
	}
}

if($_W['isajax']){
	if(pdo_update('tg_order',array('status'=>4,'is_hexiao'=>2,'veropenid' => $_W['openid'],'sendtime'=>TIMESTAMP,'gettime'=>TIMESTAMP),array('orderno'=>$orderno))){
		wl_json(1);
	}else{
		wl_json(0,'核销失败，请重试！');
	}
}
include wl_template('order/check');