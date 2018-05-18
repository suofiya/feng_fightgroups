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
    file_put_contents($log_file, print_r(json_decode(json_encode($response),true)), FILE_APPEND);
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
        pdo_update('tg_order', array('status' => $status), array('id' => $orderId));
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
