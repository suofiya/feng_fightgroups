<?php
//http://pyp.local.com/fboauthcallback.php?code=AQBbDk3ZlUb2GDmCyj7VwwXPJg3DlNYywaKwLnC7QnUoEtdISp5xUTXLseCOJ2YiwTgzEQfRG8Oie9qkHCrD6PR4Esh24YJGqKM6unOIyaJnhQH18GnrgNY85RM8SATfeawQcl_jF-IWXgsiqEmG3SHLicDJRyJnZH2HGRcY7mthcOFdi-FJbmzyqom1xYLqDCZgjqwEGtX1sYRc3xJUtocuBbrOnIDO5nsA3ytjJE1FSmqsWnwDNhEsBXWYhcYV68ltpOYuxLWwH-k2sygJu1AsSdh63veX_ity4iNWcsMaDO549NywzAih0bRgIz2Hnlc9dGLI3R1pfVVNwrhQPbtD&state=eb928f586fbbba45c83dec8f4fc1354d#_=_
/**
 * [weliam] Copyright (c) 2016/3/23
 * fblogin.ctrl
 * facebook登录验证控制器
 */
defined('IN_IA') or exit('Access Denied');
wl_load()->model('member');

// 页面action处理
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

// 获取Facebook用户信息
if (!empty($_GPC['code'])) {
    // facebook登陆授权回调地址
    $facebook_oauth_callback = 'https://'.$_SERVER['HTTP_HOST'].'/fboauthcallback.php';
    
    // STEP_1: 换accesstoken地址
    $token_url = "https://graph.facebook.com/oauth/access_token?client_id=206082400173820&redirect_uri=". urlencode($facebook_oauth_callback) ."&client_secret=fda9b4c5ec5386a916307e9db7b4e4e2&code=" . $_GPC['code'];
    
    // 去Facebook换取access_token
//    $arrFacebookResponse = pdd_doRestCurl($token_url);
$arrFacebookResponse['access_token'] = 'EAAC7bkxBvvwBAFECcybsGmmKYvVEgXvmXotTqH31MEbdLSpoAcmfBzHb7noZCG3bhKZABKQlZBJncdnJiCW8KcjpARfXnteZC3jrdLz5OjH8XqczyVnrJau3qfBlSGZB5ZCzF8tdJNKgEZBo3hd0PjmJ4ZB9bZA2WhiZAffkbJYVSmtQZDZD';
    
    // 换取access_token成功
    if (!empty($arrFacebookResponse['access_token'])) {
        
        // STEP_2: 获取用户信息
        $profile_url = 'https://graph.facebook.com/me?fields=id,email,first_name,last_name,name,gender';
        $profile_url .= '&access_token='.$arrFacebookResponse['access_token'];
        
        // 获取个人信息
//        $arrFacebookProfile = pdd_doRestCurl($profile_url);

$arrFacebookProfile = array(
   "id" => "1779741945425320",
   "email" => "tyliu1123@163.com",
   "first_name" => "Tao",
   "last_name" => "Liu",
   "name" => "Tao Liu"
);

        // 绑定或查询fb用户
        if (!empty($arrFacebookProfile['id'])) {
            $fb_snsid = $arrFacebookProfile['id'];
            if (!empty($arrFacebookProfile['email'])) {
                $fb_email = $arrFacebookProfile['email'];
            } else {
                $fb_email = $fb_snsid.'@facebook.com';
            }
            if (!empty($arrFacebookProfile['name'])) {
                $nickname = $arrFacebookProfile['name'];
            } elseif (!empty($arrFacebookProfile['first_name']) || !empty($arrFacebookProfile['last_name'])) {
                $nickname = $arrFacebookProfile['first_name'].' '.$arrFacebookProfile['last_name'];
                $nickname = trim($nickname);
            } else {
                list($nickname, $mail_domain) = explode('@', $fb_email);
            }
            
            // fb用户是否存在
            $member = member_get_by_params(" fb_snsid = '{$fb_snsid}' ");
            if (empty($member)) {
                $fb_openid = $_W['openid'];
                // 绑定fb用户
                $upgrade = array(
                    'email' => $fb_email,
                    'nickname' => $nickname,
                    'fb_snsid' => $fb_snsid,
                );
                pdo_update('tg_member', $upgrade, array('openid' => $fb_openid));            
            } else {
                $fb_openid = $member['openid'];
            }
            // 保持登录状态
            session_start();
            pdd_saveLoginState($fb_openid);

            // 调整到下一页
            if (!empty($_SESSION['goodsid'])) {
                header("Location: ".app_url('order/orderconfirm'));exit;
            } else {
                header("Location: ".app_url('member/home'));exit;
            }

        } else {
            header("Location: ".app_url('member/login'));exit;
        }

    } else {
        header("Location: ".app_url('member/login'));exit;   
    }

} else {
    header("Location: ".app_url('member/login'));exit;
}