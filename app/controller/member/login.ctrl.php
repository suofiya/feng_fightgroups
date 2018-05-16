<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * index.ctrl
 * 登录控制器
 */
defined('IN_IA') or exit('Access Denied');

// 跳转url
session_start();
$goodsid = $_SESSION['goodsid'];
if($goodsid){
    $bakurl = app_url('order/orderconfirm');
}else{
    $bakurl = app_url('member/home');
}

// 判断用户是否已注册，
if (pdd_isLoginedStatus() == true) {
    header("Location: ".$bakurl);exit;
}

// 页面action处理
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$pagetitle = !empty($config['tginfo']['sname']) ? '登录页 - '.$config['tginfo']['sname'] : '登录页';

if($op =='display'){
    // Facebook登录
    $facebook_client_id = '206082400173820';
    $facebook_state_code = md5(pdd_generateOpenID());
    // 授权回到地址
    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on' || $_SERVER['HTTPS']==1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']=='https') {
        $facebook_oauth_callback = urlencode('https://'.$_SERVER['HTTP_HOST'].'/fboauthcallback.php');
    } else {
        $facebook_oauth_callback = urlencode('http://'.$_SERVER['HTTP_HOST'].'/fboauthcallback.php');   
    }
    // 模板
    include wl_template('member/login');
}

// 登录POST action
if ($op == 'post') {
    $email = $_GPC['email'];
    $member = member_get_by_params(" email = '{$email}' ");
    if (empty($member)) {
        wl_json(0, '邮箱尚未注册过!');
    }

    // 密码验证
    if (!pdd_validatePassword($_GPC['password'], $member['password'])) {
        wl_json(0, '邮箱或是密码错误!');
    }
    
    // 保持登录状态
    session_start();
    pdd_saveLoginState($member['openid']);

    // 返回值
    wl_json(1, array('openid'=>$member['openid']));
}