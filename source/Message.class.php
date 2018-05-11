<?php
class Message {
	/**
	 *
	 * 模板消息
	 *
	 * 模板消息请求URL
	 *
	 *
	 */
	public function sendTplNotice($touser, $template_id, $postdata, $url = '', $account = null) {
		global $_W;
		load() -> model('account');
		if (!$account) {
			if (!empty($_W['acid'])) {
				$account= WeAccount :: create($_W['acid']);
			} else {
				$acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $_W['uniacid']));
				$account= WeAccount :: create($acid);
			} 
		} 
		if (!$account) {
			return;
		} 
		return $account -> sendTplNotice($touser, $template_id, $postdata, $url);
	}

	/**
	 *
	 * 模板消息
	 * 支付成功模板消息
	 *
	 *
	 */
	public function pay_success($openid, $price, $goodsname, $the, $url) {
		global $_W;
		load() -> func('communication');
		load() -> model('account');
		$tuan_id = pdo_fetch("select * from" . tablename('tg_order') . "where orderno = :orderno", array(':orderno' => $orderno));
		$pay_suc = $the -> module['config']['pay_suc'];
		$pay_remark = $the -> module['config']['pay_remark'];
		$m_pay = $the -> module['config']['m_pay'];
		//支付成功模板消息提醒
		$content = "您已成功付款";
		$postdata  = array(
					"first"=>array( "value"=> $content,"color"=>"#173177"),
					"orderMoneySum"=>array('value' => $price."元", "color" => "#4a5077"),
					"orderProductName"=>array('value' => $goodsname, "color" => "#4a5077"),
					"remark"=>array("value"=>'点击查看详情', "color" => "#4a5077"),
				);
		self::sendTplNotice($openid, $m_pay, $postdata,$url);
	}

	/**
	 *
	 * 模板消息
	 *
	 * 组团成功模板消息
	 *
	 *
	 */
	public static function group_success($tuan_id, $the, $url) {
		global $_W;
		load() -> func('communication');
		load() -> model('account');
		load() -> model('mc');
		$m_tuan = $the -> module['config']['m_tuan'];
		$tuan_suc = $the -> module['config']['tuan_suc'];
		$tuan_remark = $the -> module['config']['tuan_remark'];
		$content = "组团成功!!!";
		$alltuan = pdo_fetchall("select openid from" . tablename('tg_order') . "where tuan_id = '{$tuan_id}' and status in(1,2,3,4)");
		$tuan_first_order = pdo_fetch("select openid,g_id from" . tablename('tg_order') . "where tuan_first=1 and tuan_id='{$tuan_id}'");
		$profile = pdo_fetch("select nickname from" . tablename('mc_mapping_fans') . "where openid = '{$tuan_first_order['openid']}'");
		$goods = pdo_fetch("select gname from" . tablename('tg_goods') . "where id = '{$tuan_first_order['g_id']}'");
		$postdata  = array(
					"first"=>array( "value"=> $tuan_suc,"color"=>"#173177"),
					"Pingou_ProductName"=>array('value' => $goods['gname'], "color" => "#4a5077"),
					"Weixin_ID"=>array('value' => $profile['nickname'], "color" => "#4a5077"),
					"remark"=>array("value"=>$tuan_remark, "color" => "#4a5077"),
				);
		foreach ($alltuan as $num => $all) {
			self::sendTplNotice($all['openid'], $m_tuan, $postdata,$url);
		}
	}

	/**
	 *
	 * 模板消息
	 *
	 * 发货提醒模板消息
	 *
	 *
	 */
	public static function send_success($orderno, $openid, $express, $expressn, $the, $url) {
		global $_W;
		load() -> func('communication');
		load() -> model('account');
		$m_send = $the -> module['config']['m_send'];
		$send = $the -> module['config']['send'];
		$send_remark = $the -> module['config']['send_remark'];
		$content = "亲，您的商品已发货!!!";
		$postdata  = array(
					"first"=>array( "value"=> $send,"color"=>"#173177"),
					"keyword1"=>array('value' => $orderno, "color" => "#4a5077"),
					"keyword2"=>array('value' => $express, "color" => "#4a5077"),
					"keyword3"=>array("value"=>$expressn, "color" => "#4a5077"),
					"remark"=>array("value"=>$send_remark, "color" => "#4a5077"),
				);
			self::sendTplNotice($openid, $m_send, $postdata,$url);
	}

	/**
	 *
	 * 模板消息
	 *
	 * 退款模板消息
	 *
	 *
	 */
	public static function refund($openid, $price, $the, $url) {
		global $_W;
		$m_ref = $the -> module['config']['m_ref'];
		$ref = $the -> module['config']['ref'];
		$ref_remark = $the -> module['config']['ref_remark'];
		$content = "您已成退款成功";
		$postdata  = array(
					"first"=>array( "value"=> $ref,"color"=>"#173177"),
					"reason"=>array('value' => "拼团失败", "color" => "#4a5077"),
					"refund"=>array('value' => $price."元", "color" => "#4a5077"),
					"remark"=>array("value"=>$ref_remark, "color" => "#4a5077"),
				);
		self::sendTplNotice($openid, $m_ref, $postdata,$url);
	}
	public static function part_refund($openid, $price, $the, $url) {
		global $_W;
		$m_ref = $the -> module['config']['m_ref'];
		$ref = $the -> module['config']['ref'];
		$ref_remark = $the -> module['config']['ref_remark'];
		$content = "您已成退款成功";
		$postdata  = array(
					"first"=>array( "value"=> "团购成功，团长部分返现","color"=>"#173177"),
					"reason"=>array('value' => "团长优惠,部分退款", "color" => "#4a5077"),
					"refund"=>array('value' => $price."元", "color" => "#4a5077"),
					"remark"=>array("value"=>$ref_remark, "color" => "#4a5077"),
				);
		self::sendTplNotice($openid, $m_ref, $postdata,$url);
	}
	public static function cancelorder($openid, $price, $goodsname, $orderno, $the, $url) {
		global $_W;
		$m_cancle = $the -> module['config']['m_cancle'];
		$cancle = $the -> module['config']['cancle'];
		$cancle_remark = $the -> module['config']['cancle_remark'];
		$content = "取消订单通知";
		$postdata  = array(
					"first"=>array( "value"=> $cancle,"color"=>"#173177"),
					"keyword5"=>array('value' => $price."元", "color" => "#4a5077"),
					"keyword3"=>array('value' => $goodsname, "color" => "#4a5077"),
					"keyword2"=>array("value"=>$_W['uniaccount']['name'], "color" => "#4a5077"),
					"keyword1"=>array("value"=>$orderno, "color" => "#4a5077"),
					"keyword4"=>array("value"=>"1", "color" => "#4a5077"),
					"remark"=>array("value"=>$cancle_remark, "color" => "#4a5077"),
				);
		self::sendTplNotice($openid, $m_cancle, $postdata,$url);
		
	}

}
?>