<?php
		session_start();
		$this->getuserinfo();
		$_SESSION['goodsid']='';
		$_SESSION['tuan_id']='';
		$_SESSION['groupnum']='';
		global $_W, $_GPC;
		$share_data = $this->module['config'];
		$op = $_GPC['op'];
		$content = '';
		$category =  pdo_fetchall("SELECT * FROM " . tablename('tg_category') . " WHERE weid = '{$_W['uniacid']}' and parentid=0  ORDER BY displayorder DESC");
//		echo "<pre>";print_r($category);exit;
		$childs = array();
		foreach($category as $key=>$value){
			$category_childs = pdo_fetchall("SELECT * FROM " . tablename('tg_category') . " WHERE weid = '{$_W['uniacid']}' and parentid={$value['id']} and enabled=1 ORDER BY displayorder DESC");
			$childs[$value['id']] = $category_childs;
			$category[$key]['c'] = $category_childs;
		} 
		
		include $this->template('category');
?>