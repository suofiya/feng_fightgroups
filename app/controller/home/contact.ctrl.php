<?php
defined('IN_IA') or exit('Access Denied');
$pagetitle = !empty($config['tginfo']['sname']) ? 'regra de compra do grupo - '.$config['tginfo']['sname'] : 'regra de compra do grupo';
include wl_template('home/contact');
