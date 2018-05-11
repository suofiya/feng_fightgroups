<?php
	global $_W,$_GPC;
	session_start();
	$share_data = $this->module['config'];
	$goodsid = $_SESSION['goodsid'];
	$openid = $_W['openid'];
	$this->getuserinfo();
	if($_GPC['op']=='select'){
		$id=$_GPC['id'];
        $moren =  pdo_fetch("SELECT * FROM".tablename('tg_address')."where status=1 and openid='$openid'");
        pdo_update('tg_address',array('status' => 0),array('id' => $moren['id']));
        pdo_update('tg_address',array('status' =>1),array('id' => $id));
		if($goodsid!=''){
			echo "<script>location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('orderconfirm')."';</script>";
			exit;
		}
	}
	$address = pdo_fetchall("select * from " . tablename('tg_address')."where openid='{$openid}' ");
	include $this->template('addmanage');
?>