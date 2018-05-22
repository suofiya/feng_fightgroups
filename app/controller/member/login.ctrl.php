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

$pagetitle = !empty($config['tginfo']['sname']) ? 'pagina de entrada- '.$config['tginfo']['sname'] : 'pagina de entrada';

if($op =='display'){
    // Facebook登录
    $facebook_client_id = '2112900268947111';
    $facebook_state_code = md5(pdd_generateOpenID());
    // 授权回到地址
    $facebook_oauth_callback = urlencode('https://'.$_SERVER['HTTP_HOST'].'/fboauthcallback.php');   

    // 模板
    include wl_template('member/login');
}

// 登录POST action
if ($op == 'post') {
    $email = $_GPC['email'];
    $member = member_get_by_params(" email = '{$email}' ");
    if (empty($member)) {
        wl_json(0, 'email disponivel!');
    }

    // 密码验证
    if (!pdd_validatePassword($_GPC['password'], $member['password'])) {
        wl_json(0, 'erro email ou senha!');
    }
    
    // 保持登录状态
    session_start();
    pdd_saveLoginState($member['openid']);

    // 返回值
    wl_json(1, array('openid'=>$member['openid']));
}
