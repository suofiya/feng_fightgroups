<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * index.ctrl
 * 首页控制器
 */
defined('IN_IA') or exit('Access Denied');

wl_load()->model('page');
$_W['page']['title'] = '首页';

$page = wl_page_home();
if (empty($page)) {
	header('Location: '.app_url('goods/list'));
	exit;
}
session_start();
puvindex($_W['openid']);
$_W['page']['title'] = $title = $page['title'];
wl_template_page($page['id']);
exit;

