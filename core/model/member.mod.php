<?php
/*
alter table ims_tg_member add column `fb_snsid` varchar(255) NOT NULL DEFAULT '' COMMENT 'Facebook会员ID' after realname;
alter table ims_tg_member add column `email` varchar(128) NOT NULL DEFAULT '' COMMENT '邮箱账号' after fb_snsid;
alter table ims_tg_member add column `password` varchar(255) NOT NULL DEFAULT '' COMMENT '登录密码' after email;
*/
/**
 * [weliam] Copyright (c) 2016/3/23 
 * 会员model
 */
 function member_get_list($args=array()) {
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

	/********************* 以下部分为适配巴西市场新调整逻辑 *********************/

	/**
	 * 初始用户
	 * @param  string $openid [description]
	 * @return [type]         [description]
	 */
	function pdd_initMemberState($openid = '') {
		global $_W;
		if (empty($openid)) {
			$openid = pdd_generateOpenID();
		} 
		// INIT SESSION
		$_SESSION['openid'] = $openid;
		$_W['openid'] = $openid;
		
		//
		$member = getMember($openid);
		if (empty($member)) {
			$member = array(
				'uid' => 0,
				'uniacid' => $_W['uniacid'], 
				'openid' => $openid, 
				'nickname' => '',
				'avatar' => '', 
			);
			pdo_insert('tg_member', $member);
		} else {
			if (!empty($member['id'])) {
				$address = pdo_fetch("SELECT * FROM " . tablename('tg_address') . " where openid=:openid and uniacid=:uniacid and status=1", array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
				if (!empty($address)) {
					$mem_addr = $address['province'].",".$address['city'].",".$address['country'].",".$address['detailed_address'];
				} else {
					$mem_addr = '';
				}
				
				$upgrade = array();
				if (empty($member['address']) && !empty($mem_addr)) {
					$upgrade['address'] = $mem_addr;
				}
				if (!empty($upgrade)) {
					pdo_update('tg_member', $upgrade, array('id' => $member['id']));
				} 
			} 
		}
	}


	/**
	 * 退出当前登录状态
	 * @return [type] [description]
	 */
	function pdd_logout() {
		$_SESSION['login_status'] = 0;
		$_SESSION['openid'] = '';
	}


	/**
	 * 保存登录状态
	 * @param string $openid
	 */
	function pdd_saveLoginState($openid) {
		$_SESSION['openid'] = $openid;
		$_SESSION['login_status'] = 1;
	}

	/** 
	 * 是否保持登录状态
	 * @return boolean
	 */
	function pdd_isLoginedStatus() {
		return intval($_SESSION['login_status']) > 0 ?  true : false;
	}


	/**
     * 由plain生成密文密码
     * 
     * @param  string  $plain  明文密码
     * @param  boolean $hasMd5 明文是否MD5过, 主要考虑API回传密码为加密串
     * @return string          加密密文
     */
    function pdd_encryptPassword($plain, $hasMd5=false) {
        
        $seed = mt_rand(1000000000,9999999999);

        $salt = substr(md5($seed), 0, 3);

        if ($hasMd5) {
            $encryptedPassword = md5($salt . $plain) . ':' . $salt;
        } else {
            $encryptedPassword = md5($salt . md5($plain)) . ':' . $salt;
        }

        return $encryptedPassword;
    }

    /**
     * 验证密码是否正确
     * 
     * @param  string  $plain     明文密码
     * @param  string  $encrypted 加密密文
     * @param  boolean $hasMd5    明文是否MD5过, 主要考虑API回传密码为加密串
     * @return boolean            true|false
     */
    function pdd_validatePassword($plain, $encrypted, $hasMd5=false) {
        $result = false;

        if (!empty($plain) && !empty($encrypted)) {
            // split encypted password into hash and salt
            $stack = explode(':', $encrypted);
            if ($hasMd5) {
                $result = ( md5($stack[1] . $plain) == $stack[0] ) ? true : false;
            } else {
                $result = ( md5($stack[1] . md5($plain)) == $stack[0] ) ? true : false;
            }
        }

        return $result;
    }

	/**
	 * 生成24位唯一号码，格式：YYYY-MM-DD-HH-II-SS-NNNNNNNN-CC，其中：YYYY=年份，MM=月份，DD=日期，HH=24格式小时，II=分，SS=秒，NNNNNNNN=随机数，CC=检查码
	 * @return string
	 */
	function pdd_generateOpenID() {
		//openid主体（YYYYMMDDHHIISSNNNNNNNN）
 		$open_id_main = date('YmdHis') . rand(10000000,99999999);
 		//订单号码主体长度
  		$open_id_len = strlen($open_id_main);
		$open_id_sum = 0;
		for($i=0; $i<$open_id_len; $i++){
			$open_id_sum += (int)(substr($open_id_main,$i,1));
		}
		//唯一号码（YYYYMMDDHHIISSNNNNNNNNCC）
		return  $open_id_main . str_pad((100 - $open_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
	}

	/**
	 * 获取session中openID
	 * @return string
	 */
	function pdd_getSessionOpenID() {
		return $_SESSION['openid'];
	}

	/**
	 * rest get
	 * @param  string $url 
	 * @return [type] [description]
	 */
	function pdd_doRestCurl($url) {
	    $headers = array(
	        'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
	        'Accept: application/json',
	    );

    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
        // 以返回的形式接收信息
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 设置超时
    	//curl_setopt($ch, CURLOPT_TIMEOUT, '60');
        // 不验证https证书
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    // 设置头信息
    	//curl_setopt($ch, CURLOPT_HTTPHEADER, 0);

    	curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);             //代理认证模式  
		curl_setopt($ch, CURLOPT_PROXY, '172.31.14.26');               //代理服务器地址 
		curl_setopt($ch, CURLOPT_PROXYPORT, '80');                      //代理服务器端口  
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);             //使用http代理模式 

        // 发送数据
	    $response = curl_exec($ch);

	    if (curl_errno($ch)) {
	        echo 'Curl URL: ' . $url . ' with GET error:' . curl_error($ch);
	        return false;
	    }

	    // 解析json为数组
	    $arrResult = json_decode($response, true);

	    // 不要忘记释放资源
	    curl_close($ch);

	    return $arrResult;
    }
