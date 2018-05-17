<?php 
/**
 * [weliam] Copyright (c) 2016/4/4
 * 手机端支付控制器
 */
defined('IN_IA') or exit('Access Denied');
require IA_ROOT.'/payment/ebanx/autoload.php';
$hashes = explode(',', $_GPC['hash_codes']);

foreach ($hashes as $hash)
{
    if (updateOrder($hash))
    {
        echo 'OK: ' . $hash . '<br>';
    }
    else
    {
        echo 'NOK: ' . $hash . ' ' . $this->errorMessage . '<br>';
    }
}
function updateOrder($hash)
{
    $response = \Ebanx\Ebanx::doQuery(array('hash' => $hash));

    if ($response->status == 'ERROR')
    {
        return false;
    }

    $status  = Ebanx\Ebanx::getOrderStatus($response->payment->status);
    $orderId = Ebanx\Ebanx::findOrderIdByHash($hash);

    // No order found
    if (intval($orderId) == 0)
    {
        $this->errorMessage = 'No order found';
        return false;
    }

    try
    {
        pdo_update('order', array('status' => 1), array('id' => $orderId));
    }
    catch (Exception $e)
    {
        $this->errorMessage = $e->getMessage();
        return false;
    }

    return true;
}

exit();
?>
