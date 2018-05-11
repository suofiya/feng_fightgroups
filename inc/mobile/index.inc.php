<?php
session_start();
$this->getuserinfo();
$_SESSION['goodsid'] = '';
$_SESSION['tuan_id'] = '';
$_SESSION['groupnum'] = '';
global $_W, $_GPC;
$attention = pdo_fetch("select follow from".tablename('mc_mapping_fans')."where uniacid = '{$_W['uniacid']}' and openid = '{$_W['openid']}'");
$slogo = $this->module['config']['slogo'];
$sname = $this->module['config']['sname'];
load()->model('mc');
$reslut = $_GPC['result'];
$ordertype = $_GPC['ordertype'];
$share_data = $this->module['config'];
$showtype = $share_data['showtype'];
$qrcode = $this->module['config']['qrcode'];
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

if ($this -> module['config']['mode'] == 1) {
	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	if ($operation == 'display') {
		$category = pdo_fetchall("SELECT * FROM " . tablename('tg_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 and parentid=0 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		//幻灯片
		$advs = pdo_fetchall("select * from " . tablename('tg_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'");
		foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
		}
		unset($adv);
		$condition = '';
		if (!empty($_GPC['type'])) {
			switch ($_GPC['type']) {
				case 'isnew':
					$condition .= " AND isnew = 1";
					break;
				case 'ishot':
					$condition .= " AND ishot = 1";
					break;
				case 'isrecommand':
					$condition .= " AND isrecommand = 1";
					break;
				default:
					$condition .= " AND isdiscount = 1";
					break;
			}
		}
		if (!empty($_GPC['gid'])) {
			if($this->module['config']['ditype']!=2){
				$cid = intval($_GPC['gid']);
				$condition .= " AND fk_typeid = '{$cid}'";
			}else{
				$cid = intval($_GPC['gid']);
				$condition .= " AND category_parentid = '{$cid}'";
			}
		}
		if (!empty($_GPC['category_childid'])) {
			$category_childid = intval($_GPC['category_childid']);
			$condition .= " AND category_childid = '{$category_childid}'";
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$goodses = pdo_fetchall("SELECT * FROM " . tablename('tg_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND isshow in(1,3) $condition ORDER BY displayorder desc LIMIT " . (1 - 1) * $psize . ',' . $psize);
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
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_goods') . "WHERE uniacid = '{$_W['uniacid']}' AND isshow in(1,3) $condition ");
		$pager = pagination($total, $pindex, $psize);
	}
	if ($operation == 'search') {
		$condition = '';
		if (!empty($_GPC['gid'])) {
			$cid = intval($_GPC['gid']);
			$condition .= " AND category_parentid = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND gname LIKE '%{$_GPC['keyword']}%'";
		}
		$goodses = pdo_fetchall("SELECT * FROM " . tablename('tg_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND isshow in(1,3) $condition ");
	}
	include $this -> template('simpindex');
} else {
	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	if ($operation == 'display') {
		$category = pdo_fetchall("SELECT * FROM " . tablename('tg_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $key => $value) {
			if (!empty($value['description'])) {
				$pindex = max(1, intval($_GPC['page']));
				$psize = intval($value['description']);
				$sqlmess = " LIMIT 0" . ',' . $psize;
			}
			$category[$key]['goodses'] = pdo_fetchall("SELECT * FROM " . tablename('tg_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND isshow in(1,3) AND fk_typeid = '{$value['id']}' ORDER BY displayorder DESC, id desc" . $sqlmess);
		}
		//幻灯片
		$advs = pdo_fetchall("select * from " . tablename('tg_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'");
		foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
		}
		unset($adv);
	}
	if ($operation == 'search') {
		$condition = '';
		if (!empty($_GPC['gid'])) {
			$cid = intval($_GPC['gid']);
			$condition .= " AND category_parentid = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND gname LIKE '%{$_GPC['keyword']}%'";
		}
		$goodses = pdo_fetchall("SELECT * FROM " . tablename('tg_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND isshow in(1,3)$condition ");
	}
	include $this -> template('index');
}
?>