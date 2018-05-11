<?php
	global $_W,$_GPC;
	$this -> backlists();
	load() -> func('tpl');
	$ops = array('display', 'edit', 'delete','search','permissions');
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
	//商家列表显示
	if($op == 'display'){
		$uniacid=$_W['uniacid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$merchants = pdo_fetchall("SELECT * FROM ".tablename('tg_merchant')." WHERE uniacid = {$uniacid} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_merchant') . " WHERE uniacid = '{$uniacid}'");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('web/merchant');
	}
	if($op == 'search'){
		$con     = "uniacid='{$_W['uniacid']}' ";
		$keyword = $_GPC['keyword'];
		$type = $_GPC['t'];
		if ($keyword != '') {
			$con .= " and nickname LIKE '%{$keyword}%'";
		}  
		$ds = pdo_fetchall("select * from" . tablename('tg_member') . "where $con");
		include $this->template('web/select_merchanter');
		exit;
	}
	//商家编辑
	if ($op == 'edit') {
		$id = intval($_GPC['id']);
		if(!empty($id)){
			$sql = 'SELECT * FROM '.tablename('tg_merchant').' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
			$params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
			$merchant = pdo_fetch($sql, $params);
			$saler = pdo_fetch("select * from" . tablename('tg_member') . "where uniacid={$_W['uniacid']} and openid='{$merchant['openid']}'");
			$messagesaler = pdo_fetch("select * from" . tablename('tg_member') . "where uniacid={$_W['uniacid']} and openid='{$merchant['messageopenid']}'");
			if(empty($merchant)){
				message('未找到指定的商家.', $this->createWebUrl('merchant'));
			}
		}
		
		if (checksubmit()) {
			$data = $_GPC['merchant']; // 获取打包值
			$data['detail'] = htmlspecialchars_decode($data['detail']);
			$data['openid'] = $_GPC['openid']; 
			$data['messageopenid'] = $_GPC['messageopenid'];
			if(empty($merchant)){
				$data['uniacid'] = $_W['uniacid'];
				$data['createtime'] = TIMESTAMP;
				  
				if($data['open']==1){
					load()->model('user');
					if(!preg_match(REGULAR_USERNAME, $data['uname'])) {
						message('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
					}
					if(user_check(array('username' => $data['uname']))) {
						message('非常抱歉，此用户名已经被注册，你需要更换注册名称！');
					}else{
						$tpwd = trim($_GPC['tpwd']);
						$data['password'] = trim($data['password']);
						if(empty($data['password']) || empty($tpwd)){
							message('密码不能为空！');exit;
						}
						if($data['password']!=$tpwd){
								message('两次密码输入不一致！');exit;
						}
						if(istrlen($data['password']) < 8) {
							message('必须输入密码，且密码长度不得低于8位。');exit;
						}
						/*生成用户*/
						$user = array();
						$user['salt'] = random(8);
						$user['username'] = $data['uname'];
						$user['password'] = user_hash($data['password'], $user['salt']);
						$user['groupid'] = 1;
						$user['joinip'] = CLIENT_IP;
						$user['joindate'] = TIMESTAMP;
						$user['lastip'] = CLIENT_IP;
						$user['lastvisit'] = TIMESTAMP;
						if (empty($user['status'])) {
							$user['status'] = 2;
						}
						$result = pdo_insert('users', $user);
						$uid = pdo_insertid();
						$data['uid'] = $uid;
						/*分配模块*/
						$m = array();
						$m['uniacid'] = $_W['uniacid'];
						$m['uid'] = $uid;
						$m['type'] = 'feng_merchants';
						$m['permission'] = 'all';
						$result = pdo_insert('users_permission', $m);
						/*添加操作员*/
						 pdo_insert('uni_account_users', array('uniacid'=>$_W['uniacid'],'uid'=>$uid,'role'=>'operator'));
					}
				}
				$ret = pdo_insert('tg_merchant', $data);
			} else {
//				echo "<pre>";print_r($data);exit;
				$ret = pdo_update('tg_merchant', $data, array('id'=>$id));
				$user = pdo_fetch("select * from".tablename("users")."where uid=:uid",array(':uid'=>$merchant['uid']));
				$opwd = trim($_GPC['opwd']);
				$npwd = trim($_GPC['npwd']);
				$tpwd = trim($_GPC['tpwd']);
				if($data['open']==2){
					$ret = pdo_update('users', array('status'=>1), array('uid'=>$merchant['uid']));
				}else{
					if(empty($opwd) || empty($npwd)|| empty($tpwd)){
						
					}else{
						if($opwd!=$merchant['password']){
							message('原密码错误！');exit;
						}else{
							if($npwd!=$tpwd){
								message('两次密码输入不一致！');exit;
							}
						}
						if(istrlen($npwd) < 8) {
							message('必须输入密码，且密码长度不得低于8位。');exit;
						}
						$p = user_hash($npwd, $user['salt']);
						$ret = pdo_update('users', array('password'=> $p,'status'=>2), array('uid'=>$merchant['uid']));
					}
					
				}
			}
			
			if (!empty($ret)) {
				message('商家信息保存成功', $this->createWebUrl('merchant', array('op'=>'display', 'id'=>$id)), 'success');
			} else {
				message('商家信息保存失败');
			}
		}
		
		include $this->template('web/merchant');
	}
	if($op == 'detail') {
		$id = intval($_GPC['id']);
		$sql = 'SELECT * FROM '.tablename('tg_merchant').' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
		$params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
		$merchant = pdo_fetch($sql, $params);
		if(empty($id)){
			message('未找到指定商家分类');
		}
		$result = pdo_delete('tg_merchant', array('id'=>$id, 'uniacid'=>$_W['uniacid']));
		if(intval($result) == 1){
			pdo_delete('users', array('uid'=>$merchant['uid']));
			message('删除商家成功.', $this->createWebUrl('merchant'), 'success');
		} else {
			message('删除商家失败.');
		}
	}
	if($op == 'delete') {
		$id = intval($_GPC['id']);
		$sql = 'SELECT * FROM '.tablename('tg_merchant').' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
		$params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
		$merchant = pdo_fetch($sql, $params);
		if(empty($id)){
			message('未找到指定商家分类');
		}
		$result = pdo_delete('tg_merchant', array('id'=>$id, 'uniacid'=>$_W['uniacid']));
		if(intval($result) == 1){
			pdo_delete('users', array('uid'=>$merchant['uid']));
			message('删除商家成功.', $this->createWebUrl('merchant'), 'success');
		} else {
			message('删除商家失败.');
		}
	}
//	if($op == 'permissions') {
//		$id = intval($_GPC['id']);
//		$sql = 'SELECT * FROM '.tablename('tg_merchant').' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
//		$params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
//		$merchant = pdo_fetch($sql, $params);
//		if(empty($id)){
//			message('未找到指定商家分类');
//		}
//		include $this->template('web/merchant_permissions');
//		exit;
//	}
?>