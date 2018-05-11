<?php
defined('IN_IA') or exit('Access Denied');

function _calc_current_frames2(&$frames) {
	global $_W,$_GPC;
	if(!empty($frames) && is_array($frames)) {
		foreach($frames as &$frame) {
			foreach($frame['items'] as &$fr) {
				if(count($fr['actions']) == 2){
					if($fr['actions']['1'] == $_GPC[$fr['actions']['0']]){
						$fr['active'] = 'active';
					}
				}elseif(count($fr['actions']) == 4){
					if($fr['actions']['1'] == $_GPC[$fr['actions']['0']] && $fr['actions']['3'] == $_GPC[$fr['actions']['2']]){
						$fr['active'] = 'active';
					}
				}else{
					$query = parse_url($fr['url'], PHP_URL_QUERY);
					parse_str($query, $urls);
					if(defined('ACTIVE_FRAME_URL')) {
						$query = parse_url(ACTIVE_FRAME_URL, PHP_URL_QUERY);
						parse_str($query, $get);
					} else {
						$get = $_GET;
					}
					if(!empty($_GPC['a'])) {
						$get['a'] = $_GPC['a'];
					}
					if(!empty($_GPC['c'])) {
						$get['c'] = $_GPC['c'];
					}
					if(!empty($_GPC['do'])) {
						$get['do'] = $_GPC['do'];
					}
					if(!empty($_GPC['ac'])) {
						$get['ac'] = $_GPC['ac'];
					}
					if(!empty($_GPC['status'])) {
						$get['status'] = $_GPC['status'];
					}
					if(!empty($_GPC['op'])) {
						$get['op'] = $_GPC['op'];
					}
					if(!empty($_GPC['m'])) {
						$get['m'] = $_GPC['m'];
					}
					$diff = array_diff_assoc($urls, $get);
				
					if(empty($diff)) {
						$fr['active'] = 'active';
					}else{
						$fr['active'] = '';
					}
				}
			}
		}
	}
}

//后台管理列表生成
function getstoreFrames(){
	global $_W;
	$frames = array();
	$frames['store']['title'] = '<i class="fa fa-gear"></i>&nbsp;&nbsp; 商城管理';
	$frames['store']['items'] = array();
	$frames['store']['items']['adv']['url'] = web_url('store/adv/display');
	$frames['store']['items']['adv']['title'] = '首页广告';
	$frames['store']['items']['adv']['actions'] = array();
	$frames['store']['items']['adv']['active'] = '';

	$frames['store']['items']['setting']['url'] = web_url('store/setting/display');
	$frames['store']['items']['setting']['title'] = '系统设置';
	$frames['store']['items']['setting']['actions'] = array();
	$frames['store']['items']['setting']['active'] = '';

//	$frames['store']['items']['display2']['url'] = web_url('store/address/display');
//	$frames['store']['items']['display2']['title'] = '地区管理';
//	$frames['store']['items']['display2']['actions'] = array();
//	$frames['store']['items']['display2']['active'] = '';
//
//	$frames['page']['title'] = '<i class="fa fa-inbox"></i>&nbsp;&nbsp; 页面管理';
//	$frames['page']['items'] = array();
//	$frames['page']['items']['home']['url'] = web_url('store/home/display');
//	$frames['page']['items']['home']['title'] = '商城主页';
//	$frames['page']['items']['home']['actions'] = array();
//	$frames['page']['items']['home']['active'] = '';

//	$frames['page']['items']['detail']['url'] = web_url('store/detail/display');
//	$frames['page']['items']['detail']['title'] = '商品详情';
//	$frames['page']['items']['detail']['actions'] = array();
//	$frames['page']['items']['detail']['active'] = '';
	
	return $frames;
}

function getgoodsFrames(){
	global $_W;
	$frames = array();
	$frames['goods']['title'] = '<i class="fa fa-gift"></i>&nbsp;&nbsp; 商品管理';
	$frames['goods']['items'] = array();
	$frames['goods']['items']['display1']['url'] = web_url('goods/goods/display',array('status' => '1'));
	$frames['goods']['items']['display1']['title'] = '出售中商品';
	$frames['goods']['items']['display1']['actions'] = array();
	$frames['goods']['items']['display1']['active'] = '';

	$frames['goods']['items']['display3']['url'] = web_url('goods/goods/display',array('status' => '3'));
	$frames['goods']['items']['display3']['title'] = '已售罄商品';
	$frames['goods']['items']['display3']['actions'] = array();
	$frames['goods']['items']['display3']['active'] = '';

	$frames['goods']['items']['display2']['url'] = web_url('goods/goods/display',array('status' => '2'));
	$frames['goods']['items']['display2']['title'] = '下架的商品';
	$frames['goods']['items']['display2']['actions'] = array();
	$frames['goods']['items']['display2']['active'] = '';

	$frames['goods']['items']['display4']['url'] = web_url('goods/goods/display',array('status' => '4'));
	$frames['goods']['items']['display4']['title'] = '商品回收站';
	$frames['goods']['items']['display4']['actions'] = array();
	$frames['goods']['items']['display4']['active'] = '';
	
	$frames['goods']['items']['post']['url'] = web_url('goods/goods/post');
	$frames['goods']['items']['post']['title'] = '发布商品';
	$frames['goods']['items']['post']['actions'] = array();
	$frames['goods']['items']['post']['active'] = '';
	
	$frames['other']['title'] = '<i class="fa fa-bookmark"></i>&nbsp;&nbsp; 其他管理';
	$frames['other']['items'] = array();
	$frames['other']['items']['category']['url'] = web_url('goods/category/display');
	$frames['other']['items']['category']['title'] = '商品分类';
	$frames['other']['items']['category']['actions'] = array('ac','category');
	$frames['other']['items']['category']['active'] = '';

//	$frames['other']['items']['evaluate']['url'] = web_url('goods/evaluate/display');
//	$frames['other']['items']['evaluate']['title'] = '商品评价';
//	$frames['other']['items']['evaluate']['actions'] = array();
//	$frames['other']['items']['evaluate']['active'] = '';
	
	return $frames;
}

function getorderFrames(){
	global $_W;
	$frames = array();
	$frames['order']['title'] = '<i class="fa fa-list"></i>&nbsp;&nbsp; 订单管理';
	$frames['order']['items'] = array();
	$frames['order']['items']['summary']['url'] = web_url('order/order/summary');
	$frames['order']['items']['summary']['title'] = '订单概况';
	$frames['order']['items']['summary']['actions'] = array();
	$frames['order']['items']['summary']['active'] = '';

//	$frames['order']['items']['all']['url'] = web_url('order/order/all');
//	$frames['order']['items']['all']['title'] = '所有订单';
//	$frames['order']['items']['all']['actions'] = array();
//	$frames['order']['items']['all']['active'] = '';

	$frames['order']['items']['received']['url'] = web_url('order/order/received');
	$frames['order']['items']['received']['title'] = '快递订单';
	$frames['order']['items']['received']['actions'] = array();
	$frames['order']['items']['received']['active'] = '';

	$frames['order']['items']['fetch']['url'] = web_url('order/fetch/display');
	$frames['order']['items']['fetch']['title'] = '到店自提订单';
	$frames['order']['items']['fetch']['actions'] = array();
	$frames['order']['items']['fetch']['active'] = '';
	
	$frames['group']['title'] = '<i class="fa fa-users"></i>&nbsp;&nbsp; 团购管理';
	$frames['group']['items'] = array();
	$frames['group']['items']['all']['url'] = web_url('order/group/all');
	$frames['group']['items']['all']['title'] = '全部团购';
	$frames['group']['items']['all']['actions'] = array('groupstatus','','ac','group');
	$frames['group']['items']['all']['active'] = '';

	$frames['group']['items']['ongoing']['url'] = web_url('order/group/all',array('groupstatus' => '3'));
	$frames['group']['items']['ongoing']['title'] = '团购中';
	$frames['group']['items']['ongoing']['actions'] = array('groupstatus','3');
	$frames['group']['items']['ongoing']['active'] = '';
	
	$frames['group']['items']['success']['url'] = web_url('order/group/all',array('groupstatus' => '2'));
	$frames['group']['items']['success']['title'] = '团购成功';
	$frames['group']['items']['success']['actions'] = array('groupstatus','2');
	$frames['group']['items']['success']['active'] = '';
	
	$frames['group']['items']['fail']['url'] = web_url('order/group/all',array('groupstatus' => '1'));
	$frames['group']['items']['fail']['title'] = '团购失败';
	$frames['group']['items']['fail']['actions'] = array('groupstatus','1');
	$frames['group']['items']['fail']['active'] = '';
	
	$frames['delivery']['title'] = '<i class="fa fa-paper-plane"></i>&nbsp;&nbsp; 配送方式';
	$frames['delivery']['items'] = array();
	$frames['delivery']['items']['template']['url'] = web_url('order/delivery/display');
	$frames['delivery']['items']['template']['title'] = '运费模板';
	$frames['delivery']['items']['template']['actions'] = array('ac','delivery');
	$frames['delivery']['items']['template']['active'] = '';
	
	$frames['import']['title'] = '<i class="fa fa-truck"></i>&nbsp;&nbsp; 批量发货';
	$frames['import']['items'] = array();
	$frames['import']['items']['import']['url'] = web_url('order/import/display');
	$frames['import']['items']['import']['title'] = '发货';
	$frames['import']['items']['import']['actions'] = array('ac','import');
	$frames['import']['items']['import']['active'] = '';
	
	$frames['refund']['title'] = '<i class="fa fa-money"></i>&nbsp;&nbsp; 批量退款';
	$frames['refund']['items'] = array();
	$frames['refund']['items']['refund']['url'] = web_url('order/refund/display');
	$frames['refund']['items']['refund']['title'] = '退款';
	$frames['refund']['items']['refund']['actions'] = array();
	$frames['refund']['items']['refund']['active'] = '';

	return $frames;
}

function getmemberFrames(){
	global $_W;
	$frames = array();
	$frames['member']['title'] = '<i class="fa fa-user"></i>&nbsp;&nbsp; 会员管理';
	$frames['member']['items'] = array();

	$frames['member']['items']['setting']['url'] = web_url('member/member/setting');
	$frames['member']['items']['setting']['title'] = '设置';
	$frames['member']['items']['setting']['actions'] = array();
	$frames['member']['items']['setting']['active'] = '';
	$frames['member']['items']['setting']['append']['url'] = web_url('member/member/setting');

	$frames['member']['items']['display']['url'] = web_url('member/member/display');
	$frames['member']['items']['display']['title'] = '会员管理';
	$frames['member']['items']['display']['actions'] = array();
	$frames['member']['items']['display']['active'] = '';
	
	return $frames;
}

function getapplicationFrames(){
	global $_W;
	$frames = array();
	$frames['application']['title'] = '<i class="fa fa-cloud"></i>&nbsp;&nbsp; 管理应用';
	$frames['application']['items'] = array();

	$frames['application']['items']['list']['url'] = web_url('application/plugins/list');
	$frames['application']['items']['list']['title'] = '应用插件';
	$frames['application']['items']['list']['actions'] = array();
	$frames['application']['items']['list']['active'] = '';
	$frames['application']['items']['list']['append']['url'] = web_url('application/plugins/list');
	$frames['application']['items']['list']['append']['title'] = '<i class="fa fa-plus"></i>';

	$frames['base']['title'] = '<i class="fa fa-inbox"></i>&nbsp;&nbsp; 基础应用';
	$frames['base']['items'] = array();

	$frames['base']['items']['bdelete']['url'] = web_url('application/bdelete/hx_entry');
	$frames['base']['items']['bdelete']['title'] = '线下核销';
	$frames['base']['items']['bdelete']['actions'] = array('ac','bdelete');
	$frames['base']['items']['bdelete']['active'] = '';

	$frames['base']['items']['ladder']['url'] = web_url('application/ladder/list');
	$frames['base']['items']['ladder']['title'] = '阶梯团';
	$frames['base']['items']['ladder']['actions'] = array('ac','ladder');
	$frames['base']['items']['ladder']['active'] = '';
	
	$frames['base']['items']['activity']['url'] = web_url('application/activity/list');
	$frames['base']['items']['activity']['title'] = '优惠券';
	$frames['base']['items']['activity']['actions'] = array('ac','activity');
	$frames['base']['items']['activity']['active'] = '';
	
	$frames['base']['items']['helpbuy']['url'] = web_url('application/helpbuy/list');
	$frames['base']['items']['helpbuy']['title'] = '找人代付';
	$frames['base']['items']['helpbuy']['actions'] = array();
	$frames['base']['items']['helpbuy']['active'] = '';
	
	$frames['merchant']['title'] = '<i class="fa fa-archive"></i>&nbsp;&nbsp; 商家管理';
	$frames['merchant']['items'] = array();

	$frames['merchant']['items']['merchantlist']['url'] = web_url('application/merchant/display',array('status' => '1'));
	$frames['merchant']['items']['merchantlist']['title'] = '商家列表';
	$frames['merchant']['items']['merchantlist']['actions'] = array('op','display');
	$frames['merchant']['items']['merchantlist']['active'] = '';
	
	$frames['merchant']['items']['merchantcenter']['url'] = web_url('application/merchant/account_display',array('status' => '1'));
	$frames['merchant']['items']['merchantcenter']['title'] = '商家中心';
	$frames['merchant']['items']['merchantcenter']['actions'] = array();
	$frames['merchant']['items']['merchantcenter']['active'] = '';
	
	return $frames;
}

function getdataFrames(){
	global $_W;
	$frames = array();
	$frames['data']['title'] = '<i class="fa fa-pie-chart"></i>&nbsp;&nbsp; 管理数据';
	$frames['data']['items'] = array();

	$frames['data']['items']['home_data']['url'] = web_url('data/home_data');
	$frames['data']['items']['home_data']['title'] = '数据概况';
	$frames['data']['items']['home_data']['actions'] = array();
	$frames['data']['items']['home_data']['active'] = '';
	$frames['data']['items']['home_data']['append']['url'] = web_url('data/home_data');
	$frames['data']['items']['home_data']['append']['title'] = '';
	
	$frames['data']['items']['goods_data']['url'] = web_url('data/goods_data');
	$frames['data']['items']['goods_data']['title'] = '商品统计';
	$frames['data']['items']['goods_data']['actions'] = array();
	$frames['data']['items']['goods_data']['active'] = '';
	$frames['data']['items']['goods_data']['append']['url'] = web_url('data/goods_data');
	$frames['data']['items']['goods_data']['append']['title'] = '';
	
	$frames['data']['items']['order_data']['url'] = web_url('data/order_data');
	$frames['data']['items']['order_data']['title'] = '订单统计';
	$frames['data']['items']['order_data']['actions'] = array();
	$frames['data']['items']['order_data']['active'] = '';
	$frames['data']['items']['order_data']['append']['url'] = web_url('data/order_data');
	$frames['data']['items']['order_data']['append']['title'] = '';
	
	$frames['log']['title'] = '<i class="fa fa-history"></i>&nbsp;&nbsp; 日志';
	$frames['log']['items'] = array();
	
	$frames['log']['items']['system_log']['url'] = web_url('data/system_log');
	$frames['log']['items']['system_log']['title'] = '系统日志';
	$frames['log']['items']['system_log']['actions'] = array();
	$frames['log']['items']['system_log']['active'] = '';
	$frames['log']['items']['system_log']['append']['url'] = web_url('data/system_log');
	$frames['log']['items']['system_log']['append']['title'] = '';
	
	$frames['log']['items']['refund_log']['url'] = web_url('data/refund_log');
	$frames['log']['items']['refund_log']['title'] = '退款日志';
	$frames['log']['items']['refund_log']['actions'] = array();
	$frames['log']['items']['refund_log']['active'] = '';
	$frames['log']['items']['refund_log']['append']['url'] = web_url('data/refund_log');
	$frames['log']['items']['refund_log']['append']['title'] = '';
	
	return $frames;
}

function get_top_menus(){
	$frames = array();
	$frames['store']['title'] = '<i class="fa fa-desktop"></i>&nbsp;&nbsp; 商城';
	$frames['store']['url'] = web_url('store/setting/display');
	$frames['store']['active'] = 'store';
	
	$frames['goods']['title'] = '<i class="fa fa-gift"></i>&nbsp;&nbsp; 商品';
	$frames['goods']['url'] = web_url('goods/goods/display',array('status' => '1'));
	$frames['goods']['active'] = 'goods';
	
	$frames['order']['title'] = '<i class="fa fa-shopping-cart"></i>&nbsp;&nbsp; 订单';
	$frames['order']['url'] = web_url('order/order/summary');
	$frames['order']['active'] = 'order';
	
	$frames['member']['title'] = '<i class="fa fa-user"></i>&nbsp;&nbsp; 会员';
	$frames['member']['url'] = web_url('member/member/setting');
	$frames['member']['active'] = 'member';
	
	$frames['data']['title'] = '<i class="fa fa-area-chart"></i>&nbsp;&nbsp; 数据中心';
	$frames['data']['url'] = web_url('data/home_data');
	$frames['data']['active'] = 'data';
	
	$frames['application']['title'] = '<i class="fa fa-cubes"></i>&nbsp;&nbsp; 应用和营销';
	$frames['application']['url'] = web_url('application/plugins/list');
	$frames['application']['active'] = 'application';
	
	return $frames;
}
