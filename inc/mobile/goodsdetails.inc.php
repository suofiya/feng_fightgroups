<?php
	global $_W,$_GPC;
	$this->getuserinfo();
	session_start();
	if(!empty($_GPC['id'])){
		$_SESSION['goodsid'] = $_GPC['id'];
	}
	if($_GPC['tuan_id']){
		$_SESSION['tuan_id'] = $_GPC['tuan_id'];
	}
	if($_GPC['groupnum']){
		$_SESSION['groupnum']=$_GPC['groupnum'];
	}else{
		$groupnum=$_SESSION['groupnum'];
	}

	$id= $_SESSION['goodsid'];
	$tuan_id = $_SESSION['tuan_id'];
	load()->model('mc');
	if(!empty($id)){
		/*判断购买次数*/
		$times = pdo_fetchcolumn("select count(*) from".tablename("tg_order")."where uniacid=:uniacid and g_id=:g_id and openid=:openid and status in(1,2,3,4,5)",array(":uniacid"=>$_W['uniacid'],':g_id'=>$id,':openid'=>$_W['openid']));
		//更新该商品的团状态
		$this->updateonegourp($id);
		//商品
		$sql = 'SELECT * FROM '.tablename('tg_goods').' WHERE id=:id and uniacid=:uniacid';
		$paramse = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
		$goods = pdo_fetch($sql, $paramse);
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
		//商家
		$merchant = pdo_fetch("select * from " . tablename('tg_merchant') . " where uniacid='{$_W['uniacid']}'  and id = '{$goods['merchantid']}'");
		//规格及规格项
		$allspecs = pdo_fetchall("select * from " . tablename('tg_spec') . " where goodsid=:id order by displayorder asc", array(':id' => $id));
		foreach ($allspecs as &$s) {
			$s['items'] = pdo_fetchall("select * from " . tablename('tg_spec_item') . " where  `show`=1 and specid=:specid order by displayorder asc", array(":specid" => $s['id']));
		}
		unset($s);
		//处理规格项
		$options = pdo_fetchall("select id,title,thumb,marketprice,productprice,costprice,specs from " . tablename('tg_goods_option') . " where goodsid=:id order by id asc", array(':id' => $id));
		//排序好的specs
		$specs = array();
		//找出数据库存储的排列顺序
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
//echo "<pre>";print_r($options);exit;
		//得到图集
		$advs = pdo_fetchall("select * from " . tablename('tg_goods_atlas') . " where g_id='{$id}'");
        foreach ($advs as &$adv) {
        	if (substr($adv['link'], 0, 5) != 'http:') {
                $adv['link'] = "http://" . $adv['link'];
            }
        }
        unset($adv);
		$params = pdo_fetchall("SELECT * FROM" . tablename('tg_goods_param') .  "WHERE goodsid = '{$id}' ");
		if(empty($goods)){
			message('未找到指定的商品.', $this->createMobileUrl('index'));
		}
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
						$tuan_first_order = pdo_fetch("select * from".tablename('tg_order')."where tuan_id = '{$value['groupnumber']}' and tuan_first=1 and uniacid='{$_W['uniacid']}'");
						$userinfo = $this->getfansinfo($tuan_first_order['openid']);
						$thistuan[$key]['avatar'] = $userinfo['avatar'];
						$thistuan[$key]['nickname'] = $userinfo['nickname'];
					}
				}
				
			}
		}
		
	}
	$_SESSION['optionid']='';
	$_SESSION['goodsid']='';
	$_SESSION['tuan_id']='';
	$_SESSION['groupnum']='';
	$_SESSION['goodsid'] = $id;
	$_SESSION['tuan_id'] = $tuan_id;
	$share_data = $this -> module['config'];
	if($goods['share_image']){
		$shareimage = $goods['share_image'];
	}else{
		$shareimage = $goods['gimg'];
	}
	if($goods['share_title']){
		$share_title = $goods['share_title'];
	}else{
		$share_title = $goods['gname'];
	}
	if($goods['share_desc']){
		$share_desc = $goods['share_desc'];
	}else{
		$share_desc = $share_data['share_desc'];
	}
	
	if($_GPC['share_type']=='share_type'){
		include $this->template('share_tuan');
	}else{
		include $this->template('simpgoodsdetails');
	}

?>