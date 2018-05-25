<?php 
/**
 * [weliam] Copyright (c) 2016/4/4
 * 手机端支付控制器
 */
defined('IN_IA') or exit('Access Denied');
require IA_ROOT.'/payment/ebanx/autoload.php';
$hashes = explode(',', $_GPC['hash_codes']);
$log_file = '/var/www/vhosts/m.melitotal.com.br/httpdocs/log/ebank-'.date('Y-m-d').'.log';
file_put_contents($log_file, 'notify:', FILE_APPEND);
file_put_contents($log_file, print_r($_GPC, true), FILE_APPEND);
error_reporting(E_ALL);
$errorMessage = '';
foreach ($hashes as $hash)
{
    if (updateOrder($hash))
    {
        echo 'OK: ' . $hash . '<br>';
    }
    else
    {
        echo 'NOK: ' . $hash . ' ' . $errorMessage . '<br>';
    }
}
function updateOrder($hash)
{
    \Ebanx\Config::set(array(
        'integrationKey' => 'c8b2d53d92c1b14524222919b7a7bff4ad424e286b8bc18731c81d12c88c61ab195694d931ee2cd3a6f57147fac2bfbeca7c'
    , 'testMode'       => false
    , 'directMode'     => true
    ));
    error_reporting(E_ALL);
    $log_file = '/var/www/vhosts/m.melitotal.com.br/httpdocs/log/ebank-'.date('Y-m-d').'.log';
    $response = \Ebanx\Ebanx::doQuery(array('hash' => $hash));
    file_put_contents($log_file, json_encode($response), FILE_APPEND);
    if ($response->status == 'ERROR')
    {
        return false;
    }

    $status  = Ebanx\Ebanx::getOrderStatus($response->payment->status);
    $orderId = Ebanx\Ebanx::findOrderIdByHash($hash);

    // No order found
    if (intval($orderId) == 0)
    {
        $errorMessage = 'No order found';
        return false;
    }
    $sql = "SELECT * FROM " . tablename('tg_order') . " where id= ".intval($orderId);
    $order = pdo_fetch($sql);
    $sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `tid`=:tid';
    $pars = array();
    $pars[':tid'] = $order['orderno'];
    $log = pdo_fetch($sql, $pars);
    try
    {
        $site = WeUtility::createModuleSite($log['module']);
        if(!is_error($site)) {
            $method = 'payResult';
            if (method_exists($site, $method)) {
                $ret = array();
                $ret['weid'] = $log['uniacid'];
                $ret['uniacid'] = $log['uniacid'];
                $ret['result'] = 'success';
                $ret['type'] = $log['type'];
                $ret['from'] = 'notify';
                $ret['tid'] = $log['tid'];
                $ret['uniontid'] = $log['uniontid'];
                $ret['user'] = $log['openid'];
                $ret['fee'] = $log['fee'];
                $ret['tag'] = '';
                $ret['is_usecard'] = $log['is_usecard'];
                $ret['card_type'] = $log['card_type'];
                $ret['card_fee'] = $log['card_fee'];
                $ret['card_id'] = $log['card_id'];
                $site->$method($ret);
                return true;
            }
        }else{
            file_put_contents($log_file, '支付回调错误:hash:'.$hash."\n", FILE_APPEND);
            return false;
        }
    }
    catch (Exception $e)
    {
        $errorMessage = $e->getMessage();
        return false;
    }

    return false;
}

exit();
?>