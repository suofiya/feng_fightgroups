<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * goods.ctrl
 * 商品详情控制器
 */
defined('IN_IA') or exit('Access Denied');
wl_load()->model('goods');
wl_load()->model('merchant');
wl_load()->model('order');
$id = $_GPC['id'];
puv($_W['openid'],$id);
$pagetitle = !empty($config['tginfo']['sname']) ? 'detalhe do produto - '.$config['tginfo']['sname'] : 'detalhe do produto';
session_start();
if(!empty($_GPC['id'])){
	$_SESSION['goodsid'] = $_GPC['id'];
}
load()->model('mc');
if(!empty($id)){
	//商品
	$goods = goods_get_by_params("id = {$id}");
	if($goods['isshow']==2){
		message('produto indisponivel');exit;
	}
	if(empty($goods['unit'])){
		$goods['unit'] = 'peça';
	}
	
	//阶梯团
	if($goods['group_level_status']==2){
		$param_level = unserialize($goods['group_level']);
		for($i=0;$i<count($param_level)-1;$i++){
			for($j=0;$j<count($param_level)-$i-1;$j++){
				if($param_level[$j]['groupnum']<$param_level[$j+1]['groupnum']){
					$temp=$param_level[$j]; 
					$param_level[$j] = $param_level[$j+1];
					$param_level[$j+1]= $temp;
				}
			}
		}
		if($param_level){
			$num= round(((100-count($param_level)*2)/count($param_level)));
		}
		$goods['p'] = $param_level[0]['groupprice'];
	}
	
	/*判断购买次数*/
	$data = order_get_list(array('openid'=>$_W['openid'],'status'=>'1,2,3,4,5'));
	$times = $data['total'];
	
	//商家
	$merchant = merchant_get_by_params("id = {$goods['merchantid']}");
	
	//规格及规格项
	$allspecs = pdo_fetchall("select * from " . tablename('tg_spec') . " where goodsid=:id order by displayorder asc", array(':id' => $id));
	foreach ($allspecs as &$s) {
		$s['items'] = pdo_fetchall("select * from " . tablename('tg_spec_item') . " where  `show`=1 and specid=:specid order by displayorder asc", array(":specid" => $s['id']));
	}
	unset($s);
	$options = pdo_fetchall("select id,title,thumb,marketprice,productprice,costprice,specs from " . tablename('tg_goods_option') . " where goodsid=:id order by id asc", array(':id' => $id));
	$specs = array();
	if (count($options) > 0) {
		$specitemids = explode("_", $options[0]['specs'] );
		foreach($specitemids as $itemid){
			foreach($allspecs as $ss){
				$items = $ss['items'];
				foreach($items as $it){
					if($it['id']==$itemid){
						$specs[] = $ss;
						break;
					}
				}
			}
		}
	}
	
	//得到图集
	$advs = pdo_fetchall("select * from " . tablename('tg_goods_atlas') . " where g_id='{$id}'");
    foreach ($advs as &$adv) {
    	if (substr($adv['link'], 0, 5) != 'http:') {
            $adv['link'] = "http://" . $adv['link'];
        }
    }
    unset($adv);
	
	$params = pdo_fetchall("SELECT * FROM" . tablename('tg_goods_param') .  "WHERE goodsid = '{$id}' ");
	//门店信息
	$storesids = explode(",", $goods['hexiao_id']);
	foreach($storesids as$key=>$value){
		if($value){
			$stores[$key] =  pdo_fetch("select * from".tablename('tg_store')."where id ='{$value}' and uniacid='{$_W['uniacid']}'");
		}
	}
	
	// 分享团数据
	if(empty($goods['is_share'])){
		if ($this->module['config']['sharestatus'] != 2) {
			$thistuan = pdo_fetchall("select * from".tablename('tg_group')."where uniacid='{$_W['uniacid']}' and goodsid = '{$id}' and groupstatus=3 and lacknum<>neednum order by id asc limit 0,5");
			if (!empty($thistuan)) {
				foreach ($thistuan as $key => $value) {
					$tuan_first_order = order_get_by_params(" tuan_id = '{$value['groupnumber']}' and tuan_first=1 ");
					$userinfo=member_get_by_params(" openid = '{$tuan_first_order['openid']}'");
					$thistuan[$key]['avatar'] = $userinfo['avatar'];
					$thistuan[$key]['nickname'] = $userinfo['nickname'];
				}
			}
		}
	}
	
	$config['share']['share_title'] = !empty($goods['share_title']) ? $goods['share_title'] : $goods['gname'];
	$config['share']['share_desc'] = !empty($goods['share_desc']) ? $goods['share_desc'] : $config['share']['share_desc'];
	$config['share']['share_image'] = !empty($goods['share_image']) ? $goods['share_image'] : $goods['gimg'];
}else{
	message("Caracteristicas erro！");exit;
}
include wl_template('goods/goods_detail');
