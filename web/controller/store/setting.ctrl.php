<?php 
/**
 * [weliam] Copyright (c) 2016/3/26
 * 商城系统设置控制器
 */
defined('IN_IA') or exit('Access Denied');
$ops = array('copyright');
$op_names = array('系统设置');
foreach($ops as$key=>$value){
	permissions('do', 'ac', 'op', 'store', 'setting', $ops[$key], '订单', '参数设置', $op_names[$key]);
}
$op = in_array($op, $ops) ? $op : 'copyright';
wl_load()->model('setting');
if ($op == 'copyright') {
	$_W['page']['title'] = '商城信息设置 - 系统管理';
	$set = setting_get_list();
	if(empty($set)){
		$settings = $this->module['config'];
	}else{
		$settings = array();
		foreach($set as$key=>$value){
			$settingarray= $value['value'];
			foreach($settingarray as $k=>$v){
				$settings[$k] = $v;
			}
		}
	}
//	wl_debug($settings);
	if (checksubmit('submit')) {
		load()->func('file');
        $r = mkdirs(IA_ROOT . '/attachment/feng_fightgroups/cert/'. $_W['uniacid']);
		$r2 = mkdirs(TG_CERT.$_W['uniacid']);
		if(!empty($_GPC['cert'])) {
            $ret = file_put_contents(IA_ROOT . '/attachment/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
            $ret2 = file_put_contents(TG_CERT.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
            $r = $r && $ret;
        }
        if(!empty($_GPC['key'])) {
            $ret = file_put_contents(IA_ROOT . '/attachment/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
            $ret2 = file_put_contents(TG_CERT.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
            $r = $r && $ret;
        }
		if(!$r) {
            message('证书保存失败, 请保证该目录可写');
        }
		$base = array(
			'guanzhu'=>$_GPC['guanzhu'],
			'guanzhu_buy'=>$_GPC['guanzhu_buy'],
			'goodstip'=>$_GPC['goodstip'],
			'sharestatus' => $_GPC['sharestatus'],
			'share_type'=>$_GPC['share_type']
		);
		$share = array(
			'share_title' => $_GPC['share_title'],
            'share_image' => $_GPC['share_image'],
            'share_desc' => $_GPC['share_desc']
		);
		$refund = array(
			'mchid' => $_GPC['mchid'],
			'apikey' => $_GPC['apikey'],
			'auto_refund'=>$_GPC['auto_refund']
		);
		$message = array(
			'm_daipay'=>$_GPC['m_daipay'],
			'm_pay'=>$_GPC['m_pay'],
            'm_tuan'=>$_GPC['m_tuan'],
            'm_cancle'=>$_GPC['m_cancle'],
            'm_ref'=>$_GPC['m_ref'],
            'm_send'=>$_GPC['m_send']
		);
		$tginfo = array(
			'sname'=>$_GPC['sname'],
            'slogo'=>$_GPC['slogo'],
            'copyright'=>$_GPC['copyright'],
            'guanzhu'=>$_GPC['guanzhu'],
            'qrcode'=>$_GPC['qrcode'],
            'followed_image'=>$_GPC['followed_image'],
            'content' => htmlspecialchars_decode($_GPC['content'])
		);
		$tip = array(
			'tag4'=>$_GPC['tag4'],
            'tag3'=>$_GPC['tag3'],
            'tag2'=>$_GPC['tag2'],
            'tag1'=>$_GPC['tag1']
		);
		
		$pdobase = array(
			'uniacid'=>$_W['uniacid'],
			'key'=>'base',
			'value'=>serialize($base)
		);
		$pdoshare = array(
			'uniacid'=>$_W['uniacid'],
			'key'=>'share',
			'value'=>serialize($share)
		);
		$pdorefund = array(
			'uniacid'=>$_W['uniacid'],
			'key'=>'refund',
			'value'=>serialize($refund)
		);
		$pdomessage = array(
			'uniacid'=>$_W['uniacid'],
			'key'=>'message',
			'value'=>serialize($message)
		);
		$pdotginfo  = array(
			'uniacid'=>$_W['uniacid'],
			'key'=>'tginfo',
			'value'=>serialize($tginfo)
		);
		$pdotip = array(
			'uniacid'=>$_W['uniacid'],
			'key'=>'tip',
			'value'=>serialize($tip)
		);
		$ifbase = setting_get_by_name('base');
		$ifshare = setting_get_by_name('share');
		$ifrefund = setting_get_by_name('refund');
		$ifmessage = setting_get_by_name('message');
		$iftginfo = setting_get_by_name('tginfo');
		$iftip = setting_get_by_name('tip');
		if(!empty($ifbase)){
			setting_update_by_params($pdobase, array('key'=>'base','uniacid'=>$_W['uniacid']));
		}else{
			setting_insert($pdobase);
		}
		if(!empty($ifshare)){
			setting_update_by_params($pdoshare, array('key'=>'share','uniacid'=>$_W['uniacid']));
		}else{
			setting_insert($pdoshare);
		}
		if(!empty($ifrefund)){
			setting_update_by_params($pdorefund, array('key'=>'refund','uniacid'=>$_W['uniacid']));
		}else{
			setting_insert($pdorefund);
		}
		if(!empty($ifmessage)){
			setting_update_by_params($pdomessage, array('key'=>'message','uniacid'=>$_W['uniacid']));
		}else{
			setting_insert($pdomessage);
		}
		if(!empty($iftginfo)){
			setting_update_by_params($pdotginfo, array('key'=>'tginfo','uniacid'=>$_W['uniacid']));
		}else{
			setting_insert($pdotginfo);
		}
		if(!empty($iftip)){
			setting_update_by_params($pdotip, array('key'=>'tip','uniacid'=>$_W['uniacid']));
		}else{
			setting_insert($pdotip);
		}
		message('更新设置成功！', web_url('store/setting/copyright'));
	}
}

include wl_template('store/setting');
