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
    $bakurl = app_url('member/login');
}

// wl_debug($_SESSION);

// 判断用户是否已注册，
if (pdd_isLoginedStatus() == true) {
    header("Location: ".$bakurl);exit;  
}

// 页面action处理
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$pagetitle = !empty($config['tginfo']['sname']) ? 'pagina de cadastro - '.$config['tginfo']['sname'] : 'pagina de cadastro';

if($op =='display'){
    include wl_template('member/register');
}

// 登录POST action
if ($op == 'post') {
    $email = $_GPC['email'];
    $password = $_GPC['password'];
    $confirm_password = $_GPC['confirm_password'];
    
    // 验证邮箱
    $member = member_get_by_params(" email = '{$email}' ");
    if (!empty($member)) {
        wl_json(0, 'email utilizado!');
    }

    // 密码验证
    if ($password != $confirm_password) {
        wl_json(0, 'As suas palavras-pass não coincidem, por favor tente de novo');
    }
    
    //
    list($nickname, $mail_domain) = explode('@', $email);
    $encrypted_password = pdd_encryptPassword($password);

    // 更新字段
    $upgrade = array(
        'nickname'  => $nickname,
        'avatar'    => '',
        'email'     => $email,
        'password'  => $encrypted_password,
        'fb_snsid'  => '',
    );
    pdo_update('tg_member', $upgrade, array('openid' => $openid));

    // 保持登录状态
    session_start();
    pdd_saveLoginState($openid);

    // 返回值
    wl_json(1, array('openid'=>$member['openid']));
}
