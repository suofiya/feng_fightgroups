<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * goods.ctrl
 * 团详情控制器
 */
defined('IN_IA') or exit('Access Denied');
wl_load()->model('goods');
wl_load()->model('merchant');
wl_load()->model('order');
load()->func('file');


// 判断用户是否已注册
if (pdd_isLoginedStatus() == false) {
    //header("Location: ".app_url('member/login'));exit;  
}

$tuan_id = intval($_GPC['tuan_id']);
if(!empty($tuan_id)){
    //取得该团所有订单
    $data = order_get_list(array('is_tuan'=>'1,3','status'=>' 1,2,3,4,6,7','tuan_id'=>$tuan_id));
    $orders = $data['list'];
    foreach($orders as$key =>$value){
        if($value['address']=='invalido'){
            $orders[$key]['avatar'] = $value['openid'];
            $orders[$key]['nickname'] =  $value['addname'];
        }else{
            $fans = member_get_by_params(" openid = '{$value['openid']}'");
            if (!empty($fans)) {
                $avatar = $fans['avatar'];
                $nickname=$fans['nickname'];
            }
            $orders[$key]['avatar'] = $avatar;
            $orders[$key]['nickname'] = $nickname;
        }
    }

    //取团长订单$order
    $order = order_get_by_params(" tuan_id = {$tuan_id} and tuan_first = 1 ");
    //自己的订单，若没有参团则$myorder为空
    $myorder = order_get_by_params(" openid = '{$_W['openid']}' and tuan_id = {$tuan_id} and status in(1,2,3,4,6,7) ");
    //团状态
    $tuaninfo = group_get_by_params(" groupnumber = {$tuan_id} ");
    $num_arr = array();
    for($i=0;$i<$tuaninfo['lacknum'];$i++){
        $num_arr[$i] = $i; 
    }
    if (empty($order['g_id'])) {
        echo "<script>alert('detalhe invalido！');location.href='".app_url('home/index')."';</script>";
        exit;
    }else{
        $goods = goods_get_by_params(" id = {$order['g_id']} ");
        $endtime = $tuaninfo['endtime'];
        $time = time(); /*当前时间*/
        $lasttime2 = $endtime - $time;//剩余时间（秒数）
        $lasttime = $goods['endtime'];
    }
    
    // 动态商品缩略图，不存在则重新生成300*200
    $thumb_gimg = str_replace('images', 'images/300x200', $goods['gimg_db']);
    if (!file_exists(ATTACHMENT_ROOT.'/'.$thumb_gimg)) {
        $thumb_gimg = file_image_thumb_white(ATTACHMENT_ROOT.'/'.$goods['gimg_db'], ATTACHMENT_ROOT.'/'.str_replace('images', 'images/300x200', $goods['gimg_db']), 300, 200);
    }
    $goods['thumb_gimg'] = tomedia($thumb_gimg);
    
    // 社区分享相关
    $config['share']['share_title'] = "R$ ".$goods['gprice'].", eu participei【".$goods['gname']."】do grupo，vamos junto！";
    $config['share']['share_desc'] = "【falta".$tuaninfo['lacknum']."pessoa】，vamos participamos junto【".$goods['gname']."】".$config['share']['share_desc'];
    $config['share']['share_url'] = app_url('order/group', array('tuan_id'=>$tuan_id));
    $config['share']['share_big_image'] = $goods['gimg'];
    // 分享缩略图
    $config['share']['share_image'] = $goods['thumb_gimg'];
    
    // 页头title
    $pagetitle = $goods['gname'];

    // session记录
    session_start();
    if($tuaninfo['groupstatus']==3){
      $_SESSION['goodsid'] = $goods['id'];
      $_SESSION['tuan_id'] = $tuan_id;
      $_SESSION['groupnum'] = $tuaninfo['neednum'];
    }
    
    include wl_template('order/group');
}else{
    echo "<script>alert('erro');location.href='".app_url('home/index')."';</script>";
}
