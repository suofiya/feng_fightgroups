<?php
	global $_W,$_GPC;
	$this -> backlists();
	$id = intval($_GPC['id']);
	$roles = pdo_fetch("select * from".tablename('tg_user_role')."where uniacid={$_W['uniacid']} and merchantid={$id}");
	if (checksubmit('submit')) {
		$nodes = $_GPC['node_ids'];
		$nodes=serialize($nodes);
		$data = array(
			'merchantid'=>$id,
			'nodes'=>$nodes,
			'uniacid'=>$_W['uniacid']
		);
		if($roles){
			pdo_update('tg_user_role',$data,array('merchantid'=>$id));
		}else{
			pdo_insert('tg_user_role',$data);
		}
		message('保存成功！', referer(), 'success');
//		echo "<pre>";print_r($nodes);exit;
	}
	$roles = pdo_fetch("select * from".tablename('tg_user_role')."where uniacid={$_W['uniacid']} and merchantid={$id}");
	$nodes=array();
	if($roles){
		$nodes = unserialize($roles['nodes']);
	}
	
	include $this->template('web/merchant_permissions');
