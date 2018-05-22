<?php 
/**
 * [weliam] Copyright (c) 2016/4/4
 * 手机端支付控制器
 */
	defined('IN_IA') or exit('Access Denied');
    $op = !empty($_GPC['op']) ? $_GPC['op'] : '';
    require IA_ROOT.'/payment/ebanx/autoload.php';
    $orderno = $_GPC['orderno'];
    error_reporting(E_ALL);
	$moduels = uni_modules();
	wl_load()->model('goods');
	wl_load()->model('order');
	load()->func('communication');
	load()->model('payment');
	if(!empty($_GPC['orderno'])){
		$order = order_get_by_params(" orderno = '{$_GPC['orderno']}' ");
		$goods = goods_get_by_params(" id = {$order['g_id']} ");
	}else{
		$message = "erro，falta numero do pedido.";
		die(json_encode(array('errno'=>1,'message'=>$message)));
	}
	if($order['pay_price'] <= 0) {
		$message = "pagamento nao aprovado,tem que pagar acima do R$0.00.";
		die(json_encode(array('errno'=>1,'message'=>$message)));
	}
	$params['tid'] = $_GPC['orderno'];
	$params['user'] = $_W['fans']['from_user'];
	$params['fee'] = $order['pay_price'];
	$params['title'] = $goods['gname'];
	$params['ordersn'] = $_GPC['orderno'];
	$params['module'] = "feng_fightgroups";
    $pay_method = $_GPC['method'];
if($op =='info'){
    $sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE status=1 AND `openid`=:openid AND `module`=:module AND `tid`=:tid';
    $pars = array();
    $pars[':openid'] = $_W['openid'];
    $pars[':module'] = $params['module'];
    $pars[':tid'] = $params['tid'];
    $pay_log_info = pdo_fetch($sql, $pars);
    if(!empty($pay_log_info)){
        header("Location: ".app_url('order/order'));exit;
    }
    $helppay = FALSE;
    if($order['status']!=0 && $order['status']!=5){
        wl_message("Esse pedido ja foi pago.");
    }
    $goods = goods_get_by_params(" id={$order['g_id']} ");
    if($setting['helpbuy']==1){
        $helpbuy_message = pdo_fetch("select * from".tablename('tg_helpbuy')."where uniacid={$_W['uniacid']}  order by rand() limit 1");
        if(!empty($helpbuy_message)){
            $message=$helpbuy_message['name'];
        }else{
            $message='Aguarde a seu compra!...';
        }
        $config['share']['share_title'] = "não perca seu desconto especial:";
        $config['share']['share_desc'] = $message;
        $config['share']['share_url'] = app_url('pay/paytype', array('orderno'=>$orderno));
        $config['share']['share_image'] = $goods['gimg'];
        if($order['openid']!=$_W['openid']){
            $helppay = TRUE;
            $member = member_get_by_params(" openid='{$order['openid']}' ");
        }
    }
    include wl_template('ebanx/info');
}
if ($_W['isajax']) {
	//生成paylog记录
	$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `openid`=:openid AND `module`=:module AND `tid`=:tid';
	$pars = array();
	$pars[':openid'] = $_W['openid'];
	$pars[':module'] = $params['module'];
	$pars[':tid'] = $params['tid'];
	$log = pdo_fetch($sql, $pars);
	$data = array(
		'uniacid' => $_W['uniacid'],
		'acid' => $_W['acid'],
		'openid' => $_W['openid'],
		'module' => $params['module'],
		'tid' => $params['tid'],
		'fee' => $params['fee'],
		'card_fee' => $params['fee'],
		'status' => '0',
		'is_usecard' => '0',
	);
	if (empty($log)) {
		pdo_insert('core_paylog', $data);
	}
	$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
	if(!is_array($setting['payment'])) {
		$message = "forma de pagamento invalido,contate-nos.";
		die(json_encode(array('errno'=>1,'message'=>$message)));
	}
	if (empty($_W['member']['uid'])) {
		$setting['payment']['credit']['switch'] = false;
	}
	if (!empty($setting['payment']['credit']['switch'])) {
		$credtis = mc_credit_fetch($_W['member']['uid']);
	}
    $type = 'ebanx';
	if(!empty($type)) {
        $sql = 'SELECT * FROM ' . tablename('tg_address') . ' WHERE `id`=:id';
        $pars  = array();
        $pars[':id'] = $order['addressid'];
        $address_info = pdo_fetch($sql, $pars);

        $ebanx_document = $_GPC['ebanx_document'];
        $ebanx_birth_date = $_GPC['ebanx_birth_date'];
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `openid`=:openid AND `module`=:module AND `tid`=:tid';
		$pars  = array();
		$pars[':openid'] = $_W['openid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];
		$log = pdo_fetch($sql, $pars);
		$moduleid = pdo_fetchcolumn("SELECT mid FROM ".tablename('modules')." WHERE name = :name", array(':name' => $params['module']));
		$moduleid = empty($moduleid) ? '000000' : sprintf("%06d", $moduleid);
		$record = array();
		$record['type'] = $type;
		$record['uniontid'] = date('YmdHis').$moduleid.random(8,1);
		pdo_update('core_paylog', $record, array('plid' => $log['plid']));
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `openid`=:openid AND `module`=:module AND `tid`=:tid';
		$log = pdo_fetch($sql, $pars);
        $sql = 'SELECT * FROM ' . tablename('tg_member') . ' WHERE `openid`=:openid';
        $member_pars[':openid'] = $_W['openid'];
        $member_info = pdo_fetch($sql, $member_pars);
        if($type == 'ebanx') {
            $params = array(
            'mode'      => 'full'
            , 'operation' => 'request'
            , 'currency_code'     => 'BRL'
            , 'amount'            => $order['pay_price']
            , 'name'              => $order['addname']
            , 'email'             => 'jose@example.org'
            , 'payment_type_code' => $pay_method
            , 'merchant_payment_code' => $log['plid']
            , 'payment'=> array(
                  'payment_type_code' => !empty($_GPC['ebanx_payment_type_code'])?$_GPC['ebanx_payment_type_code']:'boleto'
                , 'amount_total'      => $order['pay_price']
                , 'currency_code'     => 'BRL'
                , 'merchant_payment_code' => $log['plid']
                , 'order_number'  => $_GPC['orderno']
                , 'name'          => $order['addname']
                , 'birth_date'    => $ebanx_birth_date
                , 'document'      => $ebanx_document
                , 'email'         => $member_info['email']
                , 'address'       => $order['address']
                , 'street_number' => 'test'
                , 'state'         => 'AM'
                , 'zipcode'       => $address_info['zipcode']
                , 'city'          => $address_info['city']
                , 'country'       => 'br'
                , 'phone_number'  => $order['mobile']
                , 'notification_url' => 'http://desc.melitotal.com.br/app/index.php?i=1&c=entry&m=feng_fightgroups&do=pay&ac=ebanxnotify'
                )
            );
            if ($pay_method == 'creditcard')
            {
                $params['payment']['creditcard'] = array(
                    'card_number'   => $_GPC['ebanx_cc_number']
                , 'card_name'     => $_GPC['ebanx_cc_name']
                , 'card_due_date' => $_GPC['ebanx_cc_exp']
                , 'card_cvv'      => $_GPC['ebanx_cc_cvv']
                );
            }
            \Ebanx\Config::set(array(
                'integrationKey' => 'c8b2d53d92c1b14524222919b7a7bff4ad424e286b8bc18731c81d12c88c61ab195694d931ee2cd3a6f57147fac2bfbeca7c'
            , 'testMode'       => false
            , 'directMode'     => true
            ));
            $log_file = '/var/www/vhosts/m.melitotal.com.br/httpdocs/log/ebank-'.date('Y-m-d').'.log';
            file_put_contents($log_file, print_r($params, true), FILE_APPEND);
            $response = \Ebanx\Ebanx::doRequest($params);
            $log_res = json_encode($response);
            $json_decode_str = json_decode($log_res, true);
            file_put_contents($log_file, print_r($json_decode_str, true), FILE_APPEND);
            if($json_decode_str['status'] == 'ERROR'){
                $id = date('YmdH');
                pdo_update('core_paylog', array('plid' => $id), array('plid' => $log['plid']));
                pdo_query("ALTER TABLE ".tablename('core_paylog')." auto_increment = ".($id+1).";");
                $message = "desculpa，pagamento nao aprovado，foi erro do sistema，tente novamente。";
                die(json_encode(array('errno'=>1,'message'=>$message,'data'=>$response)));
            }else{
                Ebanx\Ebanx::saveOrderData($order['id'],$json_decode_str['payment']['hash'],$pay_method,$json_decode_str['payment']['boleto_url']);
                pdo_update('core_paylog', array('status' => 1), array('plid' => $log['plid']));
                if($pay_method == 'creditcard'){
                    //pdo_update('tg_order', array('status' => 1), array('id' => $order['id']));
                    $json_decode_str['payment']['boleto_url'] = app_url('order/order');
                }
            }
            die(json_encode(array('errno'=>0,'message'=>$message,'data'=>$json_decode_str)));
        }
	}
	$message = "pagamento com sucesso!";
	die(json_encode(array('errno'=>0,'message'=>$message,'data'=>$wOpt)));
}
?>
