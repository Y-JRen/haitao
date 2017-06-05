<?php /* Smarty version 2.6.19, created on 2015-07-31 11:31:15
         compiled from order/confirm-info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'order/confirm-info.tpl', 278, false),)), $this); ?>
<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
#myform td,#myform th{padding-left:10px;}
.goods_table{border:1px solid #ddd;border-left:0;border-top:0;border-collapse:collapse;border-spacing:0;margin-top:15px;}
.goods_table td{border-left:1px solid #ddd;border-top:1px solid #ddd;}
.goods_table th{border-left:1px solid #ddd;border-top:1px solid #ddd;background:#eee;}
</style>
<?php if ($this->_tpl_vars['order']['parent_batch_sn']): ?>
<div style="margin:0 auto; text-align:center; color:red;">
<span style="cursor:pointer;" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'info','batch_sn'=>$this->_tpl_vars['order']['parent_batch_sn'],)));?>')">换货单 [父单号：<?php echo $this->_tpl_vars['order']['parent_batch_sn']; ?>
]</span>
</div>
<?php endif; ?>
<form id="myform">
<div style="border-bottom:1px solid #CCC;">
<table width="50%" style="float:left;border-right:1px solid #CCC;">
<tr bgcolor="#F0F1F2">
  <th width="150" height="30">单据编号：</th>
  <td height="30"><?php echo $this->_tpl_vars['order']['batch_sn']; ?>
</td>
</tr>
<tr><th height="30">下单日期：</th>
<td height="30"><?php echo $this->_tpl_vars['order']['add_time']; ?>
</td>
</tr>
<tr bgcolor="#F0F1F2"><th height="30">用户名称：</th>
<td height="30"><?php echo $this->_tpl_vars['order']['user_name']; ?>
 <?php if ($this->_tpl_vars['order']['rank_id']): ?>(<?php echo $this->_tpl_vars['order']['rank_id']; ?>
)<?php endif; ?></td></tr>
<tr>
  <th height="30">下单类型：</th>
  <td height="30">
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
    渠道下单 (渠道订单号：<?php echo $this->_tpl_vars['order']['external_order_sn']; ?>
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
  <th height="30">是否接受回访：</th>
  <td height="30"><?php if ($this->_tpl_vars['order']['is_visit']): ?>是<?php else: ?>否<?php endif; ?></td>
</tr>
<tr>
  <th height="30">是否满意不退货：</th>
  <td height="30"><?php if ($this->_tpl_vars['order']['is_fav'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
</tr>
</table>


<div style="width:200px; float:left;" id="adddiv_<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
"><input type="button" value="查看收货信息" style="width:120px;height:40px;" onclick="chkAddressinfo('<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
','<?php echo $this->_tpl_vars['order']['user_id']; ?>
');"/></div>	

<table width="49%" style="display:none; float:right" id="addinfo_<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
">
<tr bgcolor="#F0F1F2"><th width="80" height="30">收货人：</th>
<td width="170" height="30" id="addr_consignee"><?php echo $this->_tpl_vars['order']['addr_consignee']; ?>
</td>
<td width="213" height="30" ><?php if ($this->_tpl_vars['order']['lock_name'] == $this->_tpl_vars['adminName']): ?>
  <input type="button" value="编辑收货人信息" onclick="G('/admin/order/edit-address/batch_sn/<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
/order_step/confirm')" ><?php endif; ?>
  </td>
</tr>
<tr><th width="80" height="30">联系电话：</th>
<td height="30" colspan="2" id="addr_tel"><?php echo $this->_tpl_vars['order']['addr_tel']; ?>
</td></tr>
<tr bgcolor="#F0F1F2"><th width="80" height="30">手机：</th>
<td height="30" colspan="2" id="addr_mobile"><?php echo $this->_tpl_vars['order']['addr_mobile']; ?>
    邮箱：<?php echo $this->_tpl_vars['order']['addr_email']; ?>
</td></tr>
<tr bgcolor="#F0F1F2">
  <th width="80" height="30">地区：</th>
  <td height="30" colspan="2"><?php echo $this->_tpl_vars['order']['addr_province']; ?>
<?php echo $this->_tpl_vars['order']['addr_city']; ?>
<?php echo $this->_tpl_vars['order']['addr_area']; ?>
</td>
</tr>
<tr bgcolor="#F0F1F2"><th width="80" height="30">收货地址：</th>
<td height="30" colspan="2" id="addr_address"><?php echo $this->_tpl_vars['order']['addr_address']; ?>
</td></tr>
<tr bgcolor="#F0F1F2"><th width="80" height="30">英文地址：</th>
<td height="30" colspan="2" id="addr_address"><?php echo $this->_tpl_vars['order']['addr_eng_address']; ?>
</td></tr>
<tr><th width="80" height="30">邮政编码：</th>
<td height="30" colspan="2" id="addr_zip"><?php echo $this->_tpl_vars['order']['addr_zip']; ?>
</td></tr>
</table>
<div style="clear:both;"></div>
</div>
<table style="border-bottom:1px solid #CCC;" width="100%">
<tr bgcolor="#F0F1F2"><th width="117" height="30">付款方式：</th>
<td height="30"><?php echo $this->_tpl_vars['order']['pay_name']; ?>
</td>
</tr>
</table>


<table width="100%" class="goods_table">
  <tr>
    <th height="30" >商品名称</th>
    <th height="30" >商品规格</th>
    <th height="30" >商品编号</th>
    <th height="30" >商品销售均价</th>
	<th height="30" > 行邮税</th>
    <th height="30" >销售价</th>
    <th height="30" >数量</th>
    <th height="30" >总金额</th>
  </tr>
  <?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
  <tr >
    <td height="30"><?php echo $this->_tpl_vars['item']['goods_name']; ?>
 <?php if ($this->_tpl_vars['item']['remark']): ?><font color="#FF0000"><?php echo $this->_tpl_vars['item']['remark']; ?>
</font><?php endif; ?></td>
     <td height="30"><?php echo $this->_tpl_vars['item']['goods_style']; ?>
&nbsp; </td>   
    <td height="30">
      <?php if ($this->_tpl_vars['item']['gift_card']): ?>
        <?php $_from = $this->_tpl_vars['item']['gift_card']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['card']):
?>
          <?php echo $this->_tpl_vars['card']['card_sn']; ?>
<br>
        <?php endforeach; endif; unset($_from); ?>
      <?php elseif ($this->_tpl_vars['item']['vitual_goods']): ?>
        <?php $_from = $this->_tpl_vars['item']['vitual_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vitual']):
?>
          <?php echo $this->_tpl_vars['vitual']['sn']; ?>
<br>
        <?php endforeach; endif; unset($_from); ?>
      <?php else: ?>
        <?php echo $this->_tpl_vars['item']['product_sn']; ?>

      <?php endif; ?>
    </td>
    <td height="30"><?php echo $this->_tpl_vars['item']['eq_price']; ?>
</td>
	<td height="30"><?php echo $this->_tpl_vars['item']['tax']; ?>
</td>
    <td height="30"><?php echo $this->_tpl_vars['item']['sale_price']; ?>
</td>
    <td height="30"><?php echo $this->_tpl_vars['item']['number']; ?>
</td>
    <td height="30"><?php echo $this->_tpl_vars['item']['amount']; ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['item']['child']): ?>
  <?php $_from = $this->_tpl_vars['item']['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
  <tr >
    <td height="30" style="padding-left:20px"><?php echo $this->_tpl_vars['a']['goods_name']; ?>
</td>
    <td height="30"><?php echo $this->_tpl_vars['a']['goods_style']; ?>
 &nbsp; </td>
    <td height="30"><?php echo $this->_tpl_vars['a']['product_sn']; ?>
</td>
    <td height="30"><?php echo $this->_tpl_vars['a']['eq_price']; ?>
</td>
   <td height="30"><?php echo $this->_tpl_vars['a']['avg_price']; ?>
</td>
    <td height="30"><?php echo $this->_tpl_vars['a']['sale_price']; ?>
</td>
    <td height="30"><?php echo $this->_tpl_vars['a']['number']; ?>
</td>
    <td height="30"><?php if ($this->_tpl_vars['a']['type'] != 5): ?><?php echo $this->_tpl_vars['a']['amount']; ?>
<?php endif; ?></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</table>
<table width="100%" class="goods_table">
  <tr>
    <td width="150" height="30"><strong>商品总金额：</strong></td>
    <td height="30"><?php echo $this->_tpl_vars['order']['price_goods']; ?>
</td>
  </tr>
  <tr>
  	<td width="117" height="30"><strong>商品总行邮税：</strong></td>
	<td height="30"><?php echo $this->_tpl_vars['order']['tax']; ?>
</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td height="30"><strong>运输费：</strong></td>
    <td height="30"><?php echo $this->_tpl_vars['order']['price_logistic']; ?>
</td>
  </tr>
  <tr>
    <td height="30"><strong>订单总金额：</strong></td>
    <td height="30"><?php echo $this->_tpl_vars['order']['price_order']; ?>
</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td height="30" bgcolor="#F0F1F2"><strong>调整金额：</strong></td>
    <td height="30" bgcolor="#F0F1F2"><?php echo $this->_tpl_vars['order']['price_adjust']; ?>
</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td height="30" bgcolor="#ffffff"><strong>已支付金额：</strong></td>
    <td height="30" bgcolor="#ffffff"><?php echo $this->_tpl_vars['order']['price_payed']+$this->_tpl_vars['order']['price_from_return']; ?>
</td>
  </tr>
<?php if ($this->_tpl_vars['order']['gift_card_payed'] > 0): ?>
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>礼品卡抵扣：</strong></td>
	<td height="30" bgcolor="#F0F1F2"><?php echo $this->_tpl_vars['order']['gift_card_payed']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['order']['point_payed'] > 0): ?>
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>积分抵扣：</strong></td>
	<td height="30" bgcolor="#F0F1F2"><?php echo $this->_tpl_vars['order']['point_payed']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['order']['account_payed'] > 0): ?>
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>账户余额抵扣：</strong></td>
	<td height="30" bgcolor="#F0F1F2"><?php echo $this->_tpl_vars['order']['account_payed']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['detail']['other']['price_must_pay']): ?>
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>需支付金额：</strong></td>
	<td height="30" bgcolor="#F0F1F2"><?php echo $this->_tpl_vars['detail']['other']['price_must_pay']; ?>
</td>
	</tr>
<?php endif; ?>
<tr bgcolor="#F0F1F2">
  <td height="30" bgcolor="#ffffff">&nbsp;</td>
  <td height="30" bgcolor="#ffffff">&nbsp;</td>
</tr>
<?php if ($this->_tpl_vars['detail']['finance']['price_return_money']): ?>
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>需退款现金金额：</strong></td>
	<td height="30" bgcolor="#F0F1F2"><?php echo $this->_tpl_vars['detail']['finance']['price_return_money']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['detail']['finance']['price_return_point']): ?>
	<tr>
	<td height="30"><strong>需退积分金额：</strong></td>
	<td height="30"><?php echo $this->_tpl_vars['detail']['finance']['price_return_point']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['detail']['finance']['price_return_account']): ?>
	<tr>
	<td height="30"><strong>需退账户余额金额：</strong></td>
	<td height="30"><?php echo $this->_tpl_vars['detail']['finance']['price_return_account']; ?>
</td>
	</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['detail']['finance']['price_return_gift']): ?>
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>需退礼品卡金额：</strong></td>
	<td height="30" bgcolor="#F0F1F2"><?php echo $this->_tpl_vars['detail']['finance']['price_return_gift']; ?>
</td>
	</tr>
<?php endif; ?>
</table>

<?php if ($this->_tpl_vars['payLog']): ?>
<table width="100%">
<tr>
<th height="30">支付接口订单单据号</th>
<th height="30">订单SN</th>
<th height="30">支付时间</th>
<th height="30">支付接口</th>
<th height="30">支付金额</th>
</tr>
<?php $_from = $this->_tpl_vars['payLog']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tmp']):
?>
<tr>
<td height="30"><?php echo $this->_tpl_vars['tmp']['pay_log_id']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['tmp']['batch_sn']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['tmp']['add_time']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['tmp']['pay_type']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['tmp']['pay']; ?>
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
<tr>
<th height="30">时间</th>
<th height="30">财务退款状态</th>
<th height="30">金额</th>
<th height="30">积分</th>
<th height="30">礼品卡</th>
<th height="30">备注</th>
</tr>
<?php $_from = $this->_tpl_vars['finance']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tmp']):
?>
<tr>
<td height="30"><?php echo $this->_tpl_vars['tmp']['add_time_label']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['tmp']['status_label']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['tmp']['pay_label']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['tmp']['point_label']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['tmp']['gift_label']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['tmp']['note']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<br />
<?php endif; ?>

<table width="100%">
<tr>
<th width="117" height="30">物流打印备注：</th>
<td height="30"><?php echo $this->_tpl_vars['order']['note_print']; ?>
</td>
</tr>
<tr>
<th width="117" height="30">物流部门备注：</th>
<td height="30"><?php echo $this->_tpl_vars['order']['note_logistic']; ?>
</td>
</tr>
<tr>
<td><b>开票信息：</b></td>
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
</td>
</tr>
</table>
<br>
<table width="100%">
<tr>
<th width="117" height="30">订单留言：</th>
<td height="30"><?php echo $this->_tpl_vars['order']['note']; ?>
</td>
</tr>
</table>
<br>
<?php if ($this->_tpl_vars['order']['lock_name'] == $this->_tpl_vars['adminName']): ?>
<table width="100%">
<tr>
  <th width="117">订单取消备注：</th>
  <td><textarea name="note_staff_cancel" id="note_staff_cancel" cols="39" rows="3" style="width:330px; height:45px;"></textarea></td>
</tr>
</table>
<table>
<tr>
<th></th>
<td>
<?php if ($this->_tpl_vars['order']['part_pay'] == '1'): ?>
	请填写本次收款金额：<input name="pay_money" size="8" type="text" value="<?php echo $this->_tpl_vars['detail']['other']['price_must_pay']; ?>
" />
<?php endif; ?>
<?php if ($this->_tpl_vars['order']['type'] != 16 || $this->_tpl_vars['adminName'] == 'huangchunqing' || $this->_tpl_vars['adminName'] == 'zhaoli' || $this->_tpl_vars['groupID'] == 1): ?>
<input type="button" value="确认收款" onclick="confirmed('确认收款', $('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"has-pay",)));?>')" />
<?php endif; ?>
<input type="button" value="订单返回" onclick="confirmed('订单返回', $('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"confirm-back",)));?>')" />
<input type="button" value="订单取消" onclick="if ($('note_staff_cancel').value==''){alert('请填写订单取消备注');return false;}confirmed('订单取消', $('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"confirm-cancel",'mod'=>"confirm-list",)));?>')" />
<input type="button" onclick="Gurl();" value="返回订单列表">
</td>
</tr>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['noteStaff']): ?>
<br>
<table width=100%>
<tr>
<th width="150" height="30">客服</th>
<th height="30">客服备注内容</th>
<th height="30">客服备注日期</th>
</tr>
<?php $_from = $this->_tpl_vars['noteStaff']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
<tr>
<td height="30"><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
<td height="30">
<?php echo $this->_tpl_vars['data']['content']; ?>

</td>
<td height="30"><?php echo $this->_tpl_vars['data']['date']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<br>
<?php endif; ?>
<table>
<tr>
<th width="117" height="30">客服添加新备注：</th>
<td height="30">
<input type="text" name="note_staff" id="note_staff" size="80">
<input type="button" value="添加" onclick="if($('note_staff').value==''){alert('备注内容不能为空');return false;}ajax_submit($('myform'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"add-note-staff",'mod'=>"confirm-info",),""));?>');" /></td>
</tr>
</table>
<br>
<?php if ($this->_tpl_vars['logs']): ?>
<br>
<table width=100%>
<tr>
<th width="150" height="30">操作者</th>
<th width="200" height="30">操作时间</th>
<th height="30">操作信息</th>
</tr>
<?php $_from = $this->_tpl_vars['logs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<tr>
<td height="30"><?php echo $this->_tpl_vars['item']['admin_name']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['item']['add_time']; ?>
</td>
<td height="30"><?php echo $this->_tpl_vars['item']['title']; ?>
 <?php if ($this->_tpl_vars['item']['note']): ?>[<?php echo $this->_tpl_vars['item']['note']; ?>
]<?php endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<?php endif; ?>
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