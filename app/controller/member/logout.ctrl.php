<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * logout.ctrl
 * 退出控制器
 */
defined('IN_IA') or exit('Access Denied');

// 退出登录状态
pdd_logout();
// 跳转url
header("Location: ".app_url('member/login'));exit;