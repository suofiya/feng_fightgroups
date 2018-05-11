<?php
	session_start();
	$this->getuserinfo();
	$_SESSION['goodsid'] = '';
	$_SESSION['tuan_id'] = '';
	$_SESSION['groupnum'] = '';
	global $_W, $_GPC;
	$merchantid = $_GPC['id'];
	$merchant = pdo_fetch("select * from " . tablename('tg_merchant') . " where uniacid='{$_W['uniacid']}'  and id = '{$merchantid}'");
	load()->model('mc');
	$share_data = $this->module['config'];
	$showtype = $share_data['showtype'];
	if($share_data['share_imagestatus'] == ''){
		$shareimage =$this->module['config']['share_image'];
	}
	if($share_data['share_imagestatus'] == 1){
		$shareimage =$this->module['config']['share_image'];
	}
	if($share_data['share_imagestatus'] == 2){
		$result = mc_fetch($_W['member']['uid'], array('credit1', 'credit2','avatar','nickname'));
		$shareimage = $result['avatar'];
	}
	if($share_data['share_imagestatus'] == 3){
		$shareimage =$this->module['config']['share_image'];
	}
	$goodses = pdo_fetchall("SELECT * FROM " . tablename('tg_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND isshow = 1 and merchantid={$merchantid}  ORDER BY displayorder desc");
	foreach($goodses as$k=> $v){
			$sql = 'SELECT * FROM '.tablename('tg_goods').' WHERE id=:id and uniacid=:uniacid';
			$paramse = array(':id'=>$v['id'], ':uniacid'=>$_W['uniacid']);
			$goods = pdo_fetch($sql, $paramse);		
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
			
		}
		$goodses[$k]['p'] = $param_level[0]['groupprice'];	
		}
	include $this -> template('merchant');
?>