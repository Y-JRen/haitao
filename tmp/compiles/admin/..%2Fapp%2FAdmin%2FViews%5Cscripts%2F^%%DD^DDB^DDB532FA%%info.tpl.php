<?php /* Smarty version 2.6.19, created on 2015-06-23 10:55:58
         compiled from order/info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', 'order/info.tpl', 252, false),array('modifier', 'date_format', 'order/info.tpl', 358, false),)), $this); ?>
<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<?php if ($this->_tpl_vars['order']['parent_batch_sn']): ?>
<div style="margin:0 auto; text-align:center; color:red;">
<span style="cursor:pointer;" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'info','batch_sn'=>$this->_tpl_vars['order']['parent_batch_sn'],)));?>')">换货单 [父单号：<?php echo $this->_tpl_vars['order']['parent_batch_sn']; ?>
]</span>
</div>
<?php endif; ?>
<form id="myform">
  <table width="100%" border="0">
    <tr>
      <td width="50%" valign="top"><table width="100%">
        <tr bgcolor="#F0F1F2">
          <td width="150"> 单据编号：</td>
          <td><?php echo $this->_tpl_vars['order']['batch_sn']; ?>
</td>
        </tr>
        <tr>
          <td>下单日期：</td>
          <td><?php echo $this->_tpl_vars['order']['add_time']; ?>
</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>用户名称：</td>
          <td><?php echo $this->_tpl_vars['order']['user_name']; ?>
 <?php if ($this->_tpl_vars['order']['rank_id']): ?>(<?php echo $this->_tpl_vars['order']['rank_id']; ?>
)<?php endif; ?></td>
        </tr>
        <tr>
          <td>下单类型：</td>
          <td>
            <?php if ($this->_tpl_vars['order']['shop_id'] == 1): ?>
              官网下单 (<?php if ($this->_tpl_vars['order']['source'] == 0): ?>后台下单<?php elseif ($this->_tpl_vars['order']['source'] == 1): ?>会员下单<?php elseif ($this->_tpl_vars['order']['source'] == 2): ?>电话下单<?php elseif ($this->_tpl_vars['order']['source'] == 3): ?>匿名下单<?php elseif ($this->_tpl_vars['order']['source'] == 4): ?>试用下单<?php endif; ?>)
            <?php elseif ($this->_tpl_vars['order']['shop_id'] == 3): ?>
              国药内购平台下单 (<?php if ($this->_tpl_vars['order']['source'] == 0): ?>后台下单<?php elseif ($this->_tpl_vars['order']['source'] == 1): ?>会员下单<?php elseif ($this->_tpl_vars['order']['source'] == 2): ?>电话下单<?php elseif ($this->_tpl_vars['order']['source'] == 3): ?>匿名下单<?php elseif ($this->_tpl_vars['order']['source'] == 4): ?>试用下单<?php endif; ?>)
            <?php elseif ($this->_tpl_vars['order']['type'] == 5): ?>
            赠送下单
            <?php elseif ($this->_tpl_vars['order']['type'] == 7): ?>
            内购下单
            <?php elseif ($this->_tpl_vars['order']['type'] == 10): ?>
            呼入下单 (<?php if ($this->_tpl_vars['order']['source'] == 0): ?>后台下单<?php elseif ($this->_tpl_vars['order']['source'] == 1): ?>会员下单<?php elseif ($this->_tpl_vars['order']['source'] == 2): ?>电话下单<?php elseif ($this->_tpl_vars['order']['source'] == 3): ?>匿名下单<?php endif; ?>)
            <?php elseif ($this->_tpl_vars['order']['type'] == 11): ?>
            呼出下单 (<?php if ($this->_tpl_vars['order']['source'] == 0): ?>后台下单<?php elseif ($this->_tpl_vars['order']['source'] == 1): ?>会员下单<?php elseif ($this->_tpl_vars['order']['source'] == 2): ?>电话下单<?php elseif ($this->_tpl_vars['order']['source'] == 3): ?>匿名下单<?php endif; ?>)
            <?php elseif ($this->_tpl_vars['order']['type'] == 12): ?>
            咨询下单 (<?php if ($this->_tpl_vars['order']['source'] == 0): ?>后台下单<?php elseif ($this->_tpl_vars['order']['source'] == 1): ?>会员下单<?php elseif ($this->_tpl_vars['order']['source'] == 2): ?>电话下单<?php elseif ($this->_tpl_vars['order']['source'] == 3): ?>匿名下单<?php endif; ?>)
            <?php elseif ($this->_tpl_vars['order']['type'] == 13): ?>
            渠道下单 <?php if ($this->_tpl_vars['order']['status'] == 3 && $this->_tpl_vars['order']['fake_type']): ?>[无款刷单]<?php endif; ?> (渠道订单号：<?php echo $this->_tpl_vars['order']['external_order_sn']; ?>
)
            <?php elseif ($this->_tpl_vars['order']['type'] == 14 && $this->_tpl_vars['order']['user_name'] == 'batch_channel'): ?>
            购销下单
        	<?php elseif ($this->_tpl_vars['order']['type'] == 14 && $this->_tpl_vars['order']['user_name'] == 'credit-channel'): ?>
        	赊销下单
            <?php elseif ($this->_tpl_vars['order']['type'] == 14): ?>
            渠道补单 <?php if ($this->_tpl_vars['order']['external_order_sn']): ?>(渠道订单号：<?php echo $this->_tpl_vars['order']['external_order_sn']; ?>
)<?php endif; ?>
            <?php elseif ($this->_tpl_vars['order']['type'] == 15): ?>
            其它下单
            <?php elseif ($this->_tpl_vars['order']['type'] == 16): ?>
            直供下单
            <?php elseif ($this->_tpl_vars['order']['type'] == 17): ?>
            试用下单
            <?php elseif ($this->_tpl_vars['order']['type'] == 18): ?>
            渠道分销 [<?php if ($this->_tpl_vars['order']['distribution_type']): ?>刷单<?php else: ?>销售单<?php endif; ?>]
            <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
              <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['distributionArea'][$this->_tpl_vars['order']['user_name']]): ?>
                (<?php echo $this->_tpl_vars['item']; ?>
)
              <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['order']['type'] != 18): ?>
              (<?php echo $this->_tpl_vars['areas'][$this->_tpl_vars['order']['lid']]; ?>
发货)
            <?php endif; ?>
          </td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>是否接受回访：</td>
          <td><?php if ($this->_tpl_vars['order']['is_visit']): ?>是<?php else: ?>否<?php endif; ?></td>
        </tr>
        <tr>
          <td>是否满意不退货：</td>
          <td><?php if ($this->_tpl_vars['order']['is_fav'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
        </tr>
      </table></td>
      <td width="50%" valign="top">
      
      
<div style="width:200px; float:left;" id="adddiv_<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
"><input type="button" value="查看收货信息" style="width:120px;height:40px;" onclick="chkAddressinfo('<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
','<?php echo $this->_tpl_vars['order']['user_id']; ?>
');"/></div>	

<table width="96%" style="display:none; float:left;" id="addinfo_<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
">
<tr bgcolor="#F0F1F2"><th width="80" height="16">收货人：</th>
<td width="170" height="16" id="addr_consignee"><?php echo $this->_tpl_vars['order']['addr_consignee']; ?>
</td>
<td width="213" height="16" ><?php if ($this->_tpl_vars['order']['lock_name'] == $this->_tpl_vars['adminName']): ?>
  <input type="button" value="编辑收货人信息" onclick="G('/admin/order/edit-address/batch_sn/<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
')" ><?php endif; ?>
  </td>
</tr>
<tr><th width="120" height="16">联系电话：</th>
<td height="16" colspan="2" id="addr_tel"><?php echo $this->_tpl_vars['order']['addr_tel']; ?>
</td></tr>
<tr bgcolor="#F0F1F2"><th width="80" height="16">手机：</th>
<td height="16" colspan="2" id="addr_mobile"><?php echo $this->_tpl_vars['order']['addr_mobile']; ?>
    邮箱：<?php echo $this->_tpl_vars['order']['addr_email']; ?>
</td></tr>
<tr bgcolor="#F0F1F2">
  <th width="80" height="16">地区：</th>
  <td height="16" colspan="2"><?php echo $this->_tpl_vars['order']['addr_province']; ?>
<?php echo $this->_tpl_vars['order']['addr_city']; ?>
<?php echo $this->_tpl_vars['order']['addr_area']; ?>
</td>
</tr>
<tr bgcolor="#F0F1F2"><th width="120" height="16">收货地址：</th>
<td height="16" colspan="2" id="addr_address"><?php echo $this->_tpl_vars['order']['addr_address']; ?>
</td></tr>
<tr bgcolor="#F0F1F2"><th width="80" height="30">英文地址：</th>
<td height="30" colspan="2" id="addr_address"><?php echo $this->_tpl_vars['order']['addr_eng_address']; ?>
</td></tr>
<tr><th width="120" height="16">邮政编码：</th>
<td height="16" colspan="2" id="addr_zip"><?php echo $this->_tpl_vars['order']['addr_zip']; ?>
</td></tr>
</table>
<div style="clear:both;"></div>
</div>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
<table>
<tr bgcolor="#F0F1F2"><th width="150">付款方式：</td>
<td><?php echo $this->_tpl_vars['order']['pay_name']; ?>
</td>
</tr>
</table>
<br>
<table>
<tr bgcolor="#F0F1F2"><th width="150">配送方式：</td>
<td><?php echo $this->_tpl_vars['order']['logistic_name']; ?>
<?php if ($this->_tpl_vars['order']['logistic_no']): ?><span style="cursor:pointer;" onclick="openDiv('/admin/transport/public-view/bill_no/<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
/','ajax','运单号查询',750,400);">[<?php echo $this->_tpl_vars['order']['logistic_no']; ?>
]</span><?php endif; ?></td>
</tr>
</table>
<br>
<?php if ($this->_tpl_vars['auth']['group_id'] == 1 || $this->_tpl_vars['auth']['group_id'] == 11): ?>
<table>
<tr bgcolor="#F0F1F2"><th width="150">财务金额：</td>
<td><?php echo $this->_tpl_vars['order']['balance_amount']-$this->_tpl_vars['order']['balance_point_amount']; ?>
</td>
</tr>
</table>
<br>
<?php endif; ?>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
  <tr>
    <td>商品名称</td>
    <td>商品规格</td>
    <td>商品编号</td>
    <td>商品销售均价</td>
    <th height="30" > 行邮税</th>
    <td>销售价</td>
    <td>数量</td>
    <td>已退数量</td>
    <td>总金额</td>
  </tr>
  </thead>
  <tbody>
  <?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
	<?php if ($this->_tpl_vars['item']['product_id'] > 0): ?>
	  <tr>
		<td><?php echo $this->_tpl_vars['item']['goods_name']; ?>
 <?php if ($this->_tpl_vars['item']['remark']): ?><font color="#FF0000"><?php echo $this->_tpl_vars['item']['remark']; ?>
</font><?php endif; ?></td>
		<td><?php echo $this->_tpl_vars['item']['goods_style']; ?>
&nbsp; </td>
		<td>
		  <?php if ($this->_tpl_vars['item']['card_type'] == 'coupon'): ?>
		    <a href="/admin/coupon/history/card_sn/<?php echo $this->_tpl_vars['item']['card_sn']; ?>
"><?php echo $this->_tpl_vars['item']['card_sn']; ?>
</a>
		  <?php else: ?>
		    <?php if ($this->_tpl_vars['item']['gift_card']): ?>
              <?php $_from = $this->_tpl_vars['item']['gift_card']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['card']):
?>
                <?php echo $this->_tpl_vars['card']['card_sn']; ?>
<br>
              <?php endforeach; endif; unset($_from); ?>
            
            <?php else: ?>
              <?php echo $this->_tpl_vars['item']['product_sn']; ?>

            <?php endif; ?>
		  <?php endif; ?>
		</td>
		<td><?php echo $this->_tpl_vars['item']['eq_price']; ?>
</td>
		<td height="30"><?php echo $this->_tpl_vars['item']['tax']; ?>
</td>
		<td><?php echo $this->_tpl_vars['item']['sale_price']; ?>
</td>
		<td><?php echo $this->_tpl_vars['item']['number']; ?>
</td>
		<td><?php echo $this->_tpl_vars['item']['return_number']; ?>
</td>
		<td><?php echo $this->_tpl_vars['item']['sale_price']*$this->_tpl_vars['item']['number']-$this->_tpl_vars['item']['sale_price']*$this->_tpl_vars['item']['return_number']; ?>
 </td>
	  </tr>
	  <?php else: ?>
		<tr>
		<td><?php echo $this->_tpl_vars['item']['goods_name']; ?>
 <?php if ($this->_tpl_vars['item']['remark']): ?><font color="#FF0000"><?php echo $this->_tpl_vars['item']['remark']; ?>
</font><?php endif; ?></td>
		<td><?php echo $this->_tpl_vars['item']['goods_style']; ?>
&nbsp; </td>
		<td><?php if ($this->_tpl_vars['item']['card_type'] == 'gift'): ?><?php echo $this->_tpl_vars['item']['card_sn']; ?>
<?php endif; ?></td>
		<td><?php echo $this->_tpl_vars['item']['eq_price']; ?>
</td>
		<td height="30"><?php echo $this->_tpl_vars['item']['tax']; ?>
</td>
		<td><?php echo $this->_tpl_vars['item']['sale_price']; ?>
</td>
		<td><?php echo $this->_tpl_vars['item']['number']; ?>
</td>
		<td><?php echo $this->_tpl_vars['item']['return_number']; ?>
</td>
		<td> <?php echo $this->_tpl_vars['item']['amount']; ?>
 </td>
	  </tr>
	  
	  <?php endif; ?>
  
  <?php if ($this->_tpl_vars['item']['child']): ?>
  <?php $_from = $this->_tpl_vars['item']['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
  <tr>
    <td style="padding-left:20px"><font  color="#FF0000">
        <?php if ($this->_tpl_vars['a']['type'] == 1): ?>
       		 (活动)
        <?php elseif ($this->_tpl_vars['a']['type'] == 2): ?>
       		 (礼券)
        <?php elseif ($this->_tpl_vars['a']['type'] == 3): ?>
       		 (帐户余额)
        <?php elseif ($this->_tpl_vars['a']['type'] == 4): ?>
       		 (积分)
        <?php elseif ($this->_tpl_vars['a']['type'] == 5): ?>
       		 (组合商品)
        <?php elseif ($this->_tpl_vars['a']['type'] == 6): ?>
        	(团购商品)
        <?php endif; ?>
    </font><?php echo $this->_tpl_vars['a']['goods_name']; ?>
</td>
     <td><?php echo $this->_tpl_vars['a']['goods_style']; ?>
 &nbsp;</td>
    <td><?php echo $this->_tpl_vars['a']['product_sn']; ?>
</td>
    <td><?php echo $this->_tpl_vars['a']['eq_price']; ?>
</td>
	<td><?php echo $this->_tpl_vars['a']['tax']; ?>
</td>
    <td><?php echo $this->_tpl_vars['a']['sale_price']; ?>
</td>
    <td><?php echo $this->_tpl_vars['a']['number']; ?>
</td>
    <td><?php echo $this->_tpl_vars['a']['return_number']; ?>
</td>
    <td><?php if ($this->_tpl_vars['a']['type'] != 5): ?><?php echo $this->_tpl_vars['a']['amount']; ?>
<?php endif; ?></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
  </tbody>
</table>
<br>
<table >
  <tr>
    <th width="150">商品总金额：</td>
    <td><?php echo $this->_tpl_vars['order']['price_goods']; ?>
</td>
  </tr>
  <tr>
    <td>总行邮税：</td>
    <td><?php echo $this->_tpl_vars['order']['tax']; ?>
</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td>运输费：</td>
    <td><?php echo $this->_tpl_vars['order']['price_logistic']; ?>
</td>
  </tr>
  <tr>
    <td>订单总金额：</td>
    <td><?php echo $this->_tpl_vars['order']['price_order']; ?>
</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td>调整金额：</td>
    <td><?php echo $this->_tpl_vars['order']['price_adjust']; ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['order']['price_adjust_return']): ?>
  <tr bgcolor="#F0F1F2">
    <td>退货调整金额：</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['price_adjust_return'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
  </tr>
  <?php endif; ?>
<?php if ($this->_tpl_vars['detail']['adjust']['price_adjust_return_logistic_to']): ?>
  <tr bgcolor="#F0F1F2">
    <td>退还运费：</td>
    <td><?php echo $this->_tpl_vars['detail']['adjust']['price_adjust_return_logistic_to']; ?>
</td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['detail']['adjust']['price_adjust_return_logistic_back']): ?>
  <tr bgcolor="#F0F1F2">
    <td>退还顾客邮寄回来的运费：</td>
    <td><?php echo $this->_tpl_vars['detail']['adjust']['price_adjust_return_logistic_back']; ?>
</td>
</tr>
<?php endif; ?>
  <tr bgcolor="#F0F1F2">
    <td>已支付金额：</td>
    <td><?php echo $this->_tpl_vars['order']['price_payed']+$this->_tpl_vars['order']['price_from_return']; ?>
</td>
  </tr>
  </tr>
<?php if ($this->_tpl_vars['order']['gift_card_payed'] > 0): ?>
	<tr>
	<td>礼品卡抵扣：</td>
	<td><?php echo $this->_tpl_vars['order']['gift_card_payed']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['order']['point_payed'] > 0): ?>
	<tr>
	<td>积分抵扣：</td>
	<td><?php echo $this->_tpl_vars['order']['point_payed']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['order']['account_payed'] > 0): ?>
	<tr>
	<td>账户余额抵扣：</td>
	<td><?php echo $this->_tpl_vars['order']['account_payed']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['detail']['other']['price_must_pay']): ?>
	<tr>
	<td>需支付金额：</td>
	<td><?php echo $this->_tpl_vars['detail']['other']['price_must_pay']; ?>
</td>
	</tr>
<?php endif; ?>
<tr bgcolor="#F0F1F2">
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<?php if ($this->_tpl_vars['detail']['finance']['price_return_money']): ?>
	<tr>
	<td>需退款现金金额：</td>
	<td><?php echo $this->_tpl_vars['detail']['finance']['price_return_money']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['detail']['finance']['price_return_point']): ?>
	<tr>
	<td>需退积分金额：</td>
	<td><?php echo $this->_tpl_vars['detail']['finance']['price_return_point']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['detail']['finance']['price_return_account']): ?>
	<tr>
	<td>需退账户余额金额：</td>
	<td><?php echo $this->_tpl_vars['detail']['finance']['price_return_account']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['detail']['finance']['price_return_gift']): ?>
	<tr>
	<td>需退礼品卡金额：</td>
	<td><?php echo $this->_tpl_vars['detail']['finance']['price_return_gift']; ?>
</td>
	</tr>
<?php endif; ?>
</table>
<br />
<?php if ($this->_tpl_vars['payLog']): ?>
<table width="100%">
<tr align="left">
<th align="left">支付接口订单单据号</th>
<th align="left">订单SN</th>
<th align="left">支付时间</th>
<th align="left">支付接口</th>
<th align="left">支付金额</th>
</tr>
<?php $_from = $this->_tpl_vars['payLog']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tmp']):
?>
<tr>
<td><?php echo $this->_tpl_vars['tmp']['pay_log_id']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['batch_sn']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['add_time']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['pay_type']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['pay']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<br />
<?php endif; ?>
<?php if ($this->_tpl_vars['giftCardLog']): ?>
<table width="100%">
<tr align="left">
<th align="left">礼品卡抵扣卡号</th>
<th align="left">抵扣时间</th>
<th align="left">抵扣金额</th>
<th align="left">操作用户</th>
</tr>
<?php $_from = $this->_tpl_vars['giftCardLog']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tmp']):
?>
<tr>
<td><?php echo $this->_tpl_vars['tmp']['card_sn']; ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['tmp']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['price']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['user_name']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<br />
<?php endif; ?>
<?php if ($this->_tpl_vars['finance']): ?>
<table width="100%">
<tr align="left">
<th align="left">时间</th>
<th align="left">财务退款状态</th>
<th align="left">金额</th>
<th align="left">积分</th>
<th align="left">账户余额</th>
<th align="left">礼品卡</th>
<th align="left">备注</th>
</tr>
<?php $_from = $this->_tpl_vars['finance']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tmp']):
?>
<tr>
<td><?php echo $this->_tpl_vars['tmp']['add_time_label']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['status_label']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['pay_label']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['point_label']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['account_label']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['gift_label']; ?>
</td>
<td><?php echo $this->_tpl_vars['tmp']['note']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<br />
<?php endif; ?>
<table>
<tr>
<th width="150">物流打印备注：</td>
<td><?php echo $this->_tpl_vars['order']['note_print']; ?>
</td>
</tr>
<tr>
<td>物流部门备注：</td>
<td><?php echo $this->_tpl_vars['order']['note_logistic']; ?>
</td>
</tr>
<tr>
<td>开票信息：</td>
<td>
<?php if ($this->_tpl_vars['order']['invoice_type'] == 1): ?>
个人
<?php elseif ($this->_tpl_vars['order']['invoice_type'] == 2): ?>
抬头：<?php echo $this->_tpl_vars['order']['invoice']; ?>

<?php endif; ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php if ($this->_tpl_vars['order']['invoice_type'] == 1 || $this->_tpl_vars['order']['invoice_type'] == 2): ?>
开票内容：<?php echo $this->_tpl_vars['order']['invoice_content']; ?>

<?php endif; ?>
<?php if ($this->_tpl_vars['order']['invoice_no']): ?>
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['order']['invoice_no']; ?>

<?php endif; ?>
</td>
</tr>
</table>
<br>
<table>
<tr>
<th width="150">订单留言：</td>
<td><?php echo $this->_tpl_vars['order']['note']; ?>
</td>
</tr>
</table>
<br />
<?php if ($this->_tpl_vars['childOrder']): ?>
<table>
<tr>
<th width="150">产生的换货单号</td>
<td>产生时间</td>
</tr>
<?php $_from = $this->_tpl_vars['childOrder']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<tr>
<td><span style="cursor:pointer;" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'info','batch_sn'=>$this->_tpl_vars['item']['batch_sn'],)));?>')"><?php echo $this->_tpl_vars['item']['batch_sn']; ?>
</span></td>
<td><?php echo $this->_tpl_vars['item']['add_time']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<br>
<?php endif; ?>

<?php if ($this->_tpl_vars['order']['lock_name'] == $this->_tpl_vars['adminName']): ?>
    <?php if ($this->_tpl_vars['order']['status_logistic'] == 2 && $this->_tpl_vars['order']['status_back'] == 0): ?>
    <table>
    <tr>
      <td>订单取消/返回备注：</td>
      <td><textarea name="note_staff_cancel" id="note_staff_cancel" cols="39" rows="3" style="width:330px; height:45px;"></textarea></td>
    </tr>
    </table>
    <?php endif; ?>
    <table>
    <tr>
    <th width="150">订单类型：</td>
    <td>
    <?php if ($this->_tpl_vars['order']['status'] == 2): ?>
    无效订单
    <?php elseif ($this->_tpl_vars['order']['status'] == 1): ?>
    取消订单
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 1): ?>
    待收款订单
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 2): ?>
    待发货订单
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 3): ?>
    已发货订单
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 4): ?>
    客户已签收订单
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 5): ?>
    拒收
    <?php endif; ?>
    </td></tr>
    <tr>
    <td></td>
    <td>
    <?php if ($this->_tpl_vars['order']['status'] == 1): ?><!--取消单-->
        <input type="button" value="恢复订单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'undo',)));?>')" >
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 1): ?><!--待收款-->
			<?php if ($this->_tpl_vars['is_pay']): ?> 	
			  <input type="button" value="确认收款" onclick="confirmed('确认收款', $('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"has-pay",)));?>')" />
			<?php endif; ?>
             <input type="button" value="订单取消" onclick="confirmed('订单取消', $('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"confirm-cancel",'mod'=>"confirm-list",)));?>')" />
        <input type="button" value="订单返回" onclick="confirmed('订单返回', $('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"confirm-back",)));?>')" />
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 2): ?><!--待发货-->
        <?php if ($this->_tpl_vars['order']['status_back'] == 2): ?>
            待发货订单 申请返回 处理中
        <?php elseif ($this->_tpl_vars['order']['status_back'] == 1): ?>
            待发货订单 申请取消 处理中
        <?php else: ?>
            <?php if (! $this->_tpl_vars['order']['split_status'] || ! $this->_tpl_vars['order']['split_status']['hasConfirm']): ?>
            <input type="button" value="申请返回" onclick="if ($('note_staff_cancel').value==''){alert('请填写订单备注');return false;}confirmed('申请返回', $('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"to-be-shipping-back",)));?>')" />
            <input type="button" value="申请取消" onclick="if ($('note_staff_cancel').value==''){alert('请填写订单备注');return false;}confirmed('申请取消', $('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"to-be-shipping-cancel",)));?>')" />
            <?php endif; ?>
        <?php endif; ?>
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 3 || $this->_tpl_vars['order']['status_logistic'] == 4 || $this->_tpl_vars['order']['status_logistic'] == 5 || $this->_tpl_vars['order']['status_logistic'] == 6): ?><!--已发货、客户已签收，客户已拒收，部分签收-->
        <?php if ($this->_tpl_vars['order']['status_logistic'] == 3): ?>
            <?php if ($this->_tpl_vars['complain']): ?>
            配送投诉中
            <?php else: ?>
            <div><textarea name="remark"></textarea> <input type="button" value="配送投诉" onclick="confirmed('配送投诉', $('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>'complain',)));?>')" /></div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (( $this->_tpl_vars['order']['status'] == '0' || $this->_tpl_vars['order']['status'] == '4' ) && $this->_tpl_vars['order']['status_logistic'] > 3): ?>
            <input type="button" value="退换货开单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"return-product",)));?>')" >
        <?php endif; ?>
        <?php if (( $this->_tpl_vars['order']['status_pay'] == '1' && $this->_tpl_vars['detail']['finance']['status_return'] ) || ( $this->_tpl_vars['order']['type'] == 16 && $this->_tpl_vars['order']['price_payed'] > 0 )): ?>
            <input type="button" value="退款开单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'finance','jump'=>"return-list",)));?>')" >
        <?php endif; ?>
    <?php endif; ?>
    <input type="button" onclick="Gurl();" value="返回订单列表">
    </td>
    </tr>
    </table>
<?php endif; ?>


<?php if ($this->_tpl_vars['noteStaff']): ?>
<table width=100%>
<tr align="left">
<th width="150">客服</td>
<td>客服备注内容</td>
<td>客服备注日期</td>
</tr>
<?php $_from = $this->_tpl_vars['noteStaff']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
<tr>
<td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
<td>
<?php echo $this->_tpl_vars['data']['content']; ?>

</td>
<td><?php echo $this->_tpl_vars['data']['date']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<br>
<?php endif; ?>
<table>
<tr>
<th width="150">客服添加新备注：</td>
<td>
<input type="text" name="note_staff" id="note_staff" size="80">
<input type="button" value="添加" onclick="if($('note_staff').value==''){alert('备注内容不能为空');return false;}ajax_submit($('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"add-note-staff",'mod'=>'info',)));?>');" /></td>
<td><input type="button" onclick="window.open('/admin/logic-area-out-stock/print/logic_area/1/bill_no/<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
')" value="打印销售单"></td>
</tr>
</table>
<br>
<?php if ($this->_tpl_vars['logs']): ?>
<table  cellpadding="0" cellspacing="0" border="0" width="100%" >
<tr align="left">
<th align="left" width="150">操作者</td>
<th align="left" width="200">操作时间</td>
<td>操作信息</td>
</tr>
<?php $_from = $this->_tpl_vars['logs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<tr>
<td><?php echo $this->_tpl_vars['item']['admin_name']; ?>
</td>
<td><?php echo $this->_tpl_vars['item']['add_time']; ?>
</td>
<td><?php echo $this->_tpl_vars['item']['title']; ?>
 <?php if ($this->_tpl_vars['item']['note']): ?>[<?php echo $this->_tpl_vars['item']['note']; ?>
]<?php endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['op']): ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<thead>
<tr>
    <td  align="left" width="180">操作时间</td>
    <td  align="left" width="150">操作人</td>
    <td  align="left" width="200">操作内容</td>
    <td>备注</td>
    </tr>
</thead>
<tbody>
	<?php $_from = $this->_tpl_vars['op']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['d']):
?>
	<tr>
	<td width="150"><?php echo ((is_array($_tmp=$this->_tpl_vars['d']['op_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['admin_name']; ?>
</td>
	<td><?php if ($this->_tpl_vars['d']['op_type'] == 'assign'): ?>
	物流派单
	<?php elseif ($this->_tpl_vars['d']['op_type'] == 'confirm'): ?>
	运输单确认
	<?php elseif ($this->_tpl_vars['d']['op_type'] == 'prepare'): ?>
	仓库配库
	<?php endif; ?>
	<?php echo $this->_tpl_vars['d']['item_value']; ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['remark']; ?>
</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
<?php endif; ?>

<table cellpadding="0" cellspacing="0" border="0" width="100%" >
    <tr>
      <td width="80"><strong>操作人</strong></td>
      <td width="150"><strong>维护时间</strong></td>
      <td width="80"><strong>维护状态</strong></td>
      <td><strong>维护说明</strong></td>
    </tr>
	<?php $_from = $this->_tpl_vars['tracks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
		<tr>
		  <td><?php echo $this->_tpl_vars['t']['admin_name']; ?>
</td>
		  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['t']['op_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
		  <td><?php echo $this->_tpl_vars['logisticStatus'][$this->_tpl_vars['t']['logistic_status']]; ?>
</td>
		  <td><?php echo $this->_tpl_vars['t']['remark']; ?>
</td>
		</tr>
	<?php endforeach; endif; unset($_from); ?>
</table>


</form>
<script>	
function confirmed(str, obj, url) {
	if (confirm('确认执行 "' + str + '" 操作？')) {
		ajax_submit(obj, url);
	}
}
//查询收货信息
function chkAddressinfo(orderno,userid){
	$("adddiv_"+orderno).setStyle('display', 'none'); 
	$("addinfo_"+orderno).setStyle('display', 'block'); 
	
}
</script>