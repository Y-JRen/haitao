<?php
require_once 'global.php';
$sql = "select distinct b.pay_log_id from shop_order_batch a inner join shop_order_pay_log b on a.batch_sn = b.batch_sn inner join shop_order_batch_goods c on a.order_batch_id = c.order_batch_id inner join shop_goods d on d.goods_id = c.goods_id where status_pay =2 and status_logistic = 3 and status_declare = 0 and status =1";

$db = Zend_Registry::get('db');
$datas = $db->fetchAll($sql);

$kjt_api = new Admin_Models_API_Kjt();
foreach ($datas as $k => $v){
	$kjt_api -> order_search($v['pay_log_id']);
}