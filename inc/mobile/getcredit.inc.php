<?php
	global $_W,$_GPC;
	$id = $_GPC['id'];
	$uid = $_W['member']['uid'];
	$yes = FALSE;
	$re = 'fail';
	load()->model('mc');
	load()->model('activity');
	$result = activity_module_card_grant($uid,$id,'feng_fightgroups');
	die(json_encode($result));
?>