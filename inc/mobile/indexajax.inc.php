<?php
	global $_W,$_GPC;
	$this->getuserinfo();
	session_start();
	$_SESSION['goodsid']='';
	$_SESSION['tuan_id']='';
	$_SESSION['groupnum']='';
	$nowpage=$_GPC['pages'];
	$condition = '';
	if (!empty($_GPC['gid'])) {
		$cid = intval($_GPC['gid']);
		$condition .= " AND fk_typeid = '{$cid}'";
	}
	$pindex = max(2, intval($nowpage));
	$psize = 10;
	$list = pdo_fetchall("SELECT * FROM ".tablename('tg_goods')." WHERE uniacid = '{$_W['uniacid']}' AND isshow in(1,3) $condition order by displayorder desc LIMIT ".($pindex-1) * $psize.','.$psize); 
	foreach($list as$k=> $v){
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
		$list[$k]['p'] = $param_level[0]['groupprice'];	
		}
	$info=array();
	if (!empty($list)){
		foreach ($list as $item){
			$row=array(
				'isshow'=>$item['isshow'],
				'id'=>$item['id'],
				'gname'=>$item['gname'],
				'id'=>$item['id'],
				'fk_typeid'=>$item['fk_typeid'],
				'gsn'=>$item['gsn'],
				'gnum'=>$item['gnum'],
				'groupnum'=>$item['groupnum'],
				'gname'=>$item['gname'],
				'gprice'=>$item['gprice'],
				'oprice'=>$item['oprice'],			
				'mprice'=>$item['mprice'],
				'gdesc'=>$item['gdesc'],
				'gimg'=>tomedia($item['gimg']),
				'gubtime'=>$item['gubtime'],
				'salenum'=>$item['salenum'],
				'ishot'=>$item['ishot'],
				'group_level_status'=>$item['group_level_status'],
				'uniacid'=>$item['uniacid'],
				'p'=>$item['p']
			);
			$info[]=$row;			
		}
		$sta =1;
	}else{
		$sta =0;
	}
	$result=array(
		'success'=>$sta,
		'list'=>$info,	
	);
	echo json_encode($result);
?>