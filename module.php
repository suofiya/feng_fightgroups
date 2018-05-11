<?php
/**
 * 拼团模块定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/feng_fightgroups');
define('MB2_ROOT', IA_ROOT . '/attachment/feng_fightgroups');
class Feng_fightgroupsModule extends WeModule {
	public function settingsDisplay($settings) {
		global $_W, $_GPC, $frames;
		require_once MB_ROOT . '/source/backlist.class.php';
        $backlist = new backlist();
		$frames = $backlist->getModuleFrames('feng_fightgroups');
		$backlist->_calc_current_frames2($frames);
		load()->func('tpl');
		load()->model('account');
		$modules = uni_modules();
		if(checksubmit()) {
			load()->func('file');
            $r = mkdirs(MB_ROOT . '/cert/'.$_W['uniacid']);
			$r2 = mkdirs(MB2_ROOT . '/cert/'.$_W['uniacid']);
			if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(MB_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
                $ret2 = file_put_contents(MB2_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(MB_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
                $ret2 = file_put_contents(MB2_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
                $r = $r && $ret;
            }
			if(!$r) {
                message('证书保存失败, 请保证该目录可写');
            }
			$dat = array(
				'm_daipay'=>$_GPC['m_daipay'],
				'daifu_desc'=>$_GPC['daifu_desc'],
				'daifushare_image'=>$_GPC['daifushare_image'],
				'daifushare_title'=>$_GPC['daifushare_title'],
				'otherpay'=>$_GPC['otherpay'],
				'checkgettime'=>$_GPC['checkgettime'],
				'guanzhu'=>$_GPC['guanzhu'],
				'followed_image'=>$_GPC['followed_image'],
				'ditype'=>$_GPC['ditype'],
				'one_many'=>$_GPC['one_many'],
				'qrcode'=>$_GPC['qrcode'],
				'showtype'=>$_GPC['showtype'],
				'share_type'=>$_GPC['share_type'],
				'userrefund'=>$_GPC['userrefund'],
				'gettime'=>$_GPC['gettime'],
				'goodstip'=>$_GPC['goodstip'],
				'openfirstpay'=>$_GPC['openfirstpay'],
				'firstpay'=>$_GPC['firstpay'],
				'refundpercent' => $_GPC['refundpercent'],
				'status' => $_GPC['status'],
				'sharestatus' => $_GPC['sharestatus'],
				'searchstatus' => $_GPC['searchstatus'],
				'mode' => $_GPC['mode'],
				'picmode' => $_GPC['picmode'],
				'mchid' => $_GPC['mchid'],
				'apikey' => $_GPC['apikey'],
                'share_title' => $_GPC['share_title'],
                'share_image' => $_GPC['share_image'],
                'share_desc' => $_GPC['share_desc'],
                'share_imagestatus'=>$_GPC['share_imagestatus'],
                'pay_suc'=>$_GPC['pay_suc'],
                'm_pay'=>$_GPC['m_pay'],
                'm_tuan'=>$_GPC['m_tuan'],
                'm_cancle'=>$_GPC['m_cancle'],
                'm_ref'=>$_GPC['m_ref'],
                'm_send'=>$_GPC['m_send'],
                'tuan_suc'=>$_GPC['tuan_suc'],
                'cancle'=>$_GPC['cancle'],
                'send'=>$_GPC['send'],
                'ref'=>$_GPC['ref'],
                'tag4'=>$_GPC['tag4'],
                'tag3'=>$_GPC['tag3'],
                'tag2'=>$_GPC['tag2'],
                'tag1'=>$_GPC['tag1'],
                'sname'=>$_GPC['sname'],
                'slogo'=>$_GPC['slogo'],
                'copyright'=>$_GPC['copyright'],
                'content' => htmlspecialchars_decode($_GPC['content'])
            );
			if ($this->saveSettings($dat)) {
                message('保存成功', 'refresh');
            }
		}
		//这里来展示设置项表单
		include $this->template('web/setting');
	}
}