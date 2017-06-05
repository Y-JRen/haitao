<?php
require_once 'global.php';
$db = Zend_Registry::get('db');
$sql = "select distinct a.*,b.pay_log_id,b.serial_number,b.add_time as order_time,b.pay from shop_order_batch a inner join shop_order_pay_log b on a.batch_sn = b.batch_sn inner join shop_order_batch_goods c on a.order_batch_id = c.order_batch_id inner join shop_goods d on d.goods_id = c.goods_id where status_pay =2 and status_logistic = 3 and status_declare = 0 and status = 0";
$datas = $db->fetchAll($sql);

$order_batch_ids = array_reduce($datas, create_function('$v,$w', '$v[]=$w["order_batch_id"];return $v;'));
if(!$order_batch_ids){
	exit("All declared");
}
$order_batch_ids = implode(',', $order_batch_ids);
$sql = "select order_batch_id,b.goods_name,ifnull(b,kjt_sn,b.goods_sn) as goods_sn,a.number,b.shop_price,a.sale_price,a.tax,c.cat_name from shop_order_batch_goods a inner join shop_goods b on a.goods_id = b.goods_id left join shop_goods_cat c on c.cat_id = b.view_cat_id where a.order_batch_id in ({$order_batch_ids}) ";
$goods = $db->fetchAll($sql);
$order_batch_goods = array();
foreach ($goods as $k => $v){
	$order_batch_goods[$v['order_batch_id']][]=$v;
}
$kjt_api = new Admin_Models_API_Kjt();
foreach ($datas as $k => $v){
	$kjt_api -> order_request($v,$order_batch_goods[$v['order_batch_id']]);
}