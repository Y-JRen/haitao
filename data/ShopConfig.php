<?php
class ShopConfig
{
	public static function getShopConfig()
	{
		$shopConfig['page_title']='国人海淘网-专业的国外代购直购商城';		#页面标题
		$shopConfig['page_description']='国人海淘网-专业的国外代购直购商城';		#页面描述
		$shopConfig['page_keyword']='专业的国外代购直购商城';		#页面关键字
		$shopConfig['service_qq']='290860200';		#客服QQ号
		$shopConfig['service_msn']='wddzsw@msn.com';		#客服msn
		$shopConfig['service_email']='service@ejiankang.com';		#客服邮件地址
		$shopConfig['service_phone']='4006033883';		#客服电话
		$shopConfig['shop_close']='0';		#暂时关闭网店
		$shopConfig['shop_close_reason']='';		#关闭网店原因
		$shopConfig['member_notice']='欢迎来到国人海淘网';		#用户中心公告
		$shopConfig['shop_notice']='国人海淘网';		#商店公告
		$shopConfig['watermark']='';		#水印文件
		$shopConfig['watermark_position']='1';		#水印位置
		$shopConfig['watermark_alpha']='80';		#水印透明度
		$shopConfig['member_comments']='0';		#用户评论是否需要审核
		$shopConfig['goods_default_image']='';		#商品默认图片
		$shopConfig['statistics']='';		#统计代码
		$shopConfig['upload_max_size']='1';		#上传文件大小
		$shopConfig['goods_comments']='0';		#商品评论的条件
		$shopConfig['goods_image_width']='';		#商品图片宽度
		$shopConfig['goods_image_height']='';		#商品图片高度
		$shopConfig['goods_mini_image_width']='';		#缩略图宽度
		$shopConfig['goods_mini_image_height']='';		#缩略图高度
		$shopConfig['browse_history']='';		#浏览历史数量
		$shopConfig['comments_number']='';		#显示评论数量
		$shopConfig['goods_related']='';		#显示相关商品数量
		$shopConfig['order_send_email']='0';		#确认订单时发送邮件
		$shopConfig['shipping_send_email']='0';		#发货时发送邮件
		$shopConfig['cancel_order_send_email']='0';		#取消订单时发送邮件
		$shopConfig['invalid_order_send_email']='0';		#把订单设为无效时发送邮件
		$shopConfig['email_service_type']='1';		#邮件服务
		$shopConfig['email_smtp_address']='oa.1jiankang.com';		#发送邮件服务器地址(SMTP)
		$shopConfig['email_smtp_port']='25';		#邮件服务器端口
		$shopConfig['email_smtp_username']='info@oa.1jiankang.com';		#邮件发送帐号名
		$shopConfig['email_smtp_password']='1jk.com';		#邮件发送帐号密码
		$shopConfig['email_pop_username']='service@ejiankang.com';		#邮件回复地址
		$shopConfig['email_encode']='GBK';		#邮件编码
		$shopConfig['price_logistic']='10';		#运费
		$shopConfig['free_logistic']='199';		#满多少免运费
		$shopConfig['reg_point']='100';		#成功注册即可获得积分
		$shopConfig['fav_point']='1';		#满意不退货1元兑换多少积分
		$shopConfig['point_to_price']='100';		#会员购物多少积分兑换1元
		$shopConfig['min_point_to_price']='100';		#积分兑换最低为多少积分起竞
		$shopConfig['fast_track_id']='5';		#非会员购买默认用户ID (usert_id)
		$shopConfig['fast_track_menber_id']='5';		#非会员购买默认用户ID (member_id)
		return $shopConfig;
	}
}
