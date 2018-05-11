<?php
	
/**
 * [weliam] Copyright (c) 2016/3/23 
 * 会员model
 */
 function member_get_list($array=array()) {
		global $_W;
		$usepage = !empty($args['usepage'])? $args['usepage'] : false;
		$page = !empty($args['page'])? intval($args['page']): 1;
		$pagesize = !empty($args['pagesize'])? intval($args['pagesize']): 10;
		$orderby = !empty($args['orderby'])? $args['orderby'] : 'order by id desc';
		$params = !empty($args['params'])? $args['params']: '';
		
		$condition = ' and `uniacid` = :uniacid';
		$params = array(':uniacid' => $_W['uniacid']);
				
		if(!empty($params)){
			$condition = ' and '.$params.' ';
		}
		if ($usepage) {
			$sql = "SELECT * FROM " . tablename('tg_member') . " where 1 {$condition} {$orderby} LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;
		} else {
			$sql = "SELECT * FROM " . tablename('tg_member') . " where 1 {$condition} ";
		} 
		$list = pdo_fetchall($sql, $params);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('tg_member')."where 1 $condition ",$params);
		$data = array();
		$data['list'] = $list;
		$data['total'] = $total;
		return $data;
	}

 	function member_get_by_params($params = ''){
		if(!empty($params)){
			$params = ' where '. $params;
		}
		$sql = "SELECT * FROM " . tablename('tg_member') . $params;
		$member = pdo_fetch($sql);
		return $member;
	}
	
 function member_update_by_params($data=array(),$params='') {
		global $_W;
		pdo_update('tg_member',$data,$params);
	}
	
	
	function getListNum($array=array()) {
		global $_W;
		$condition =  "and uniacid = :uniacid";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT count(*) FROM " . tablename('tg_member') . " where 1 {$condition}";
		$totle = pdo_fetchcolumn($sql, $params);
		return $totle;
	}
	
	function getMember($openid = '') {
		global $_W;
		if (empty($openid)) {
			return;
		} 
		$info = pdo_fetch('select * from ' . tablename('tg_member') . ' where openid=:openid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
		if($_W['fans']['follow'] == 1 && $_W['tgsetting']['member']['credit_type'] == 1){
			$uid = mc_openidTouid($openid);
			$mc = mc_fetch($uid, array('credit1', 'credit2'));
			$info['credit1'] = $mc['credit1'];
			$info['credit2'] = $mc['credit2'];
		}
		return $info;
	} 
	
	function checkMember($openid = '') {
		global $_W;
		if (empty($openid)) {
			$openid = getOpenid();
		} 
		if (empty($openid)) {
			return;
		} 
		
		$member = getMember($openid);
		$userinfo = getInfo();
		$uid = 0;
		if($_W['fans']['follow'] == 1){
			$uid = mc_openidTouid($openid);
		}
		
		if (empty($member)) {
			$member = array(
				'uid'=>$uid,
				'uniacid' => $_W['uniacid'], 
				'openid' => $openid, 
				'nickname' => $userinfo['nickname'], 
				'avatar' => $userinfo['avatar'], 
			);
			pdo_insert('tg_member', $member);
		} else {
			if (!empty($member['id'])) {
				$address = pdo_fetch("SELECT * FROM " . tablename('tg_address') . " where openid=:openid and uniacid=:uniacid and status=1", array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
				$mem_addr = $address['peovince'].",".$address['city'].",".$address['country'].",".$address['detailed_address'];
				
				$upgrade = array();
				if ($userinfo['nickname'] != $member['nickname']) {
					$upgrade['nickname'] = $userinfo['nickname'];
				} 
				if ($userinfo['avatar'] != $member['avatar']) {
					$upgrade['avatar'] = $userinfo['avatar'];
				}
				if ($userinfo['address'] != $member['address']) {
					$upgrade['address'] = $mem_addr;
				}
				if ($address['tel'] != $member['mobile']) {
					$upgrade['mobile'] = $address['tel'];
				}
				if ($address['cname'] != $member['realname']) {
					$upgrade['realname'] = $address['cname'];
				}
				if (empty($member['uid'])) {
					$upgrade['uid'] = $uid;
				}

				if (!empty($upgrade)) {
					pdo_update('tg_member', $upgrade, array('id' => $member['id']));
				} 
			} 
		} 
	} 

	function getOpenid() {
		$userinfo = getInfo();
		return $userinfo['openid'];
	} 
	
	function getInfo() {
		global $_W, $_GPC;
		$userinfo = array();
		$debug = TRUE;
		load() -> model('mc');
		$userinfo = mc_oauth_userinfo();
		if($debug == FALSE){
			if (empty($userinfo['openid'])) {
				die("<!DOCTYPE html>
	            <html>
	                <head>
	                    <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
	                    <title>抱歉，出错了</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
	                </head>
	                <body>
	                <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>请在微信客户端打开链接</h4></div></div></div>
	                </body>
	            </html>");
			}
		}
		return $userinfo;
	} 
