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

    try
    {
        //支付成功后回调事务：修改订单状态，更新库存，更新销量，更新拼团信息等
        if ($status == 2) {
            //notify数据参数
            $params['uniacid'] = $_W['uniacid'];
            $params['tid'] = $response->payment->order_number;
            $params['fee'] = $response->payment->amount_ext;
            $params['uniontid'] = $response->payment->order_number;
            $params['paytime'] = $response->payment->confirm_date;
            $params['paytypecode'] = $response->payment->payment_type_code;

            //支付结果回调事务
            $site = WeUtility::createModuleSite('feng_fightgroups');
            if(!is_error($site)) {
                $method = 'payResult';
                if (method_exists($site, $method)) {
                    $ret = array();
                    $ret['weid'] = $params['uniacid'];
                    $ret['uniacid'] = $params['uniacid'];
                    $ret['result'] = 'success';
                    $ret['type'] = 'ebanx'; // paytype
                    $ret['from'] = 'notify';
                    $ret['tid'] = $params['tid'];
                    $ret['uniontid'] = $params['uniontid'];
                    $ret['user'] = '0';
                    $ret['fee'] = $params['fee'];
                    $ret['tag'] = '';
                    $ret['is_usecard'] = '0';
                    $ret['card_type'] = '';
                    $ret['card_fee'] = '';
                    $ret['card_id'] = '';
                    $ret['paytime'] = $params['paytime'];
                    exit($site->$method($ret));
                }
            } else {
                throw new Exception("Error WeUtility::createModuleSite", 1);
            }
        } else  { //支付失败
            pdo_update('tg_order', array('status' => $status), array('id' => $orderId));
        }
    }
    catch (Exception $e)
    {
        $errorMessage = $e->getMessage();
        return false;
    }

    return true;
}

exit();
?>
