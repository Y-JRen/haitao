<?php /* Smarty version 2.6.19, created on 2014-08-20 17:56:16
         compiled from order/confirm-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'order/confirm-list.tpl', 94, false),array('modifier', 'replace', 'order/confirm-list.tpl', 107, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form id="searchForm">
    <span style="float:left;line-height:18px;">开始日期：</span><span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">结束日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/></span>
    支付状态:
    <select name="status_pay">
    <option value="">请选择...</option>
    <option value="0" <?php if ($this->_tpl_vars['param']['status_pay'] == '0'): ?>selected<?php endif; ?>>未收款</option>
    <option value="1" <?php if ($this->_tpl_vars['param']['status_pay'] == '1'): ?>selected<?php endif; ?>>未退款</option>
    <option value="2" <?php if ($this->_tpl_vars['param']['status_pay'] == '2'): ?>selected<?php endif; ?>>已收款</option>
    <option value="3" <?php if ($this->_tpl_vars['param']['status_pay'] == '3'): ?>selected<?php endif; ?>>部分收款</option>
    </select>
	支付方式:
    <select name="pay_type">
      <option value="">请选择...</option>
	  <?php $_from = $this->_tpl_vars['payment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['tmp']):
?>
      <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['pay_type'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['tmp']['name']; ?>
</option>
	  <?php endforeach; endif; unset($_from); ?>
	  <option value="cash" <?php if ($this->_tpl_vars['param']['pay_type'] == 'cash'): ?>selected<?php endif; ?>>现金支付</option>
	  <option value="bank" <?php if ($this->_tpl_vars['param']['pay_type'] == 'bank'): ?>selected<?php endif; ?>>银行打款</option>
	  <!--<option value="external" <?php if ($this->_tpl_vars['param']['pay_type'] == 'external'): ?>selected<?php endif; ?>>渠道支付</option>-->
	  <option value="no_pay" <?php if ($this->_tpl_vars['param']['pay_type'] == 'no_pay'): ?>selected<?php endif; ?>>无需支付</option>
	</select>
    <br style="clear:both;" />
    店铺：
    <select name="shop_id" id="shop_id">
    <option value="">请选择...</option>
    <?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shop']):
?>
      <option value="<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
" <?php if ($this->_tpl_vars['shop']['shop_id'] == $this->_tpl_vars['param']['shop_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['shop']['shop_name']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
    </select>
    ID：<input type="text" name="order_batch_id" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['order_batch_id']; ?>
">
    订单号：<input type="text" name="batch_sn" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['batch_sn']; ?>
">
	用户名：<input type="text" name="user_name" id="user_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['user_name']; ?>
">
	收货人名字：<input type="text" name="addr_consignee" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['addr_consignee']; ?>
">
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
	<br />
	<input type="button" name="dosearch" value="所有被我锁定的订单" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','is_lock'=>yes,)));?>','ajax_search')"/>
	<input type="button" name="dosearch" value="所有没有锁定的订单" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','is_lock'=>no,)));?>','ajax_search')"/>
    <input type="button" name="dosearch" value="所有挂起的订单" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','hang'=>1,)));?>','ajax_search')"/>
    </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">待收款订单列表</div>
	<div class="content">
<div style="padding:0 5px">
	<div style="float:left;width:500px;">
		<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 
		<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="超级锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"super-lock",)));?>/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="超级解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"super-lock",)));?>/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="批量取消订单" onclick="if (confirm('确认执行批量取消订单操作？')) {ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"confirm-batch-cancel",)));?>','Gurl(\'refresh\')');}">
	</div>
	<div style="float:right;"><b>订单总金额：￥<?php echo $this->_tpl_vars['totalPriceOrder']; ?>
</b></div>
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td width=10></td>
				<td width="40">操作</td>
				<td>ID</td>
                <td>店铺</td>
				<td width="120">订单号</td>
                 <td>下单时间</td>
				<td width="350">订单商品</td>
				<td width="60">收货人</td>
				<td>金额</td>
				<td>支付方式</td>
				<td>锁定状态</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		<tr id="ajax_list<?php echo $this->_tpl_vars['item']['order_batch_id']; ?>
">
			<td valign="top"><input type='checkbox' name="ids[]" value="<?php echo $this->_tpl_vars['item']['batch_sn']; ?>
"></td>
			<td valign="top">
			<?php if ($this->_tpl_vars['item']['lock_name'] == $this->_tpl_vars['auth']): ?>
			<input type="button" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"confirm-info",'batch_sn'=>$this->_tpl_vars['item']['batch_sn'],'is_pay'=>1,)));?>')" value="修改">
			<?php else: ?>
			<input type="button" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"confirm-info",'batch_sn'=>$this->_tpl_vars['item']['batch_sn'],)));?>')" value="查看">
			<?php endif; ?></td>
			<td valign="top"><?php echo $this->_tpl_vars['item']['order_batch_id']; ?>
</td>
            <td valign="top"><?php echo $this->_tpl_vars['item']['shop_name']; ?>
</td>
			<td valign="top">
			<?php echo $this->_tpl_vars['item']['batch_sn']; ?>

			<?php if ($this->_tpl_vars['item']['hang']): ?><br /><font color="red">已被<?php echo $this->_tpl_vars['item']['hang_admin_name']; ?>
挂起</font><?php endif; ?>
             <br />
			<?php echo $this->_tpl_vars['item']['status']; ?>
  <?php echo $this->_tpl_vars['item']['status_pay']; ?>
 <?php echo $this->_tpl_vars['item']['status_logistic']; ?>
  <?php echo $this->_tpl_vars['item']['status_return']; ?>
   <br />
			<?php if ($this->_tpl_vars['item']['status_back'] == 1): ?><font color="red">已申请取消</font><br /><?php endif; ?>
			<?php if ($this->_tpl_vars['item']['status_back'] == 2): ?><font color="red">已申请返回</font><?php endif; ?>        
			</td>
            <td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>  
			<td valign="top">
				<?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
					<?php if ($this->_tpl_vars['goods']['batch_sn'] == $this->_tpl_vars['item']['batch_sn']): ?>
						 <?php echo $this->_tpl_vars['goods']['goods_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['goods']['goods_style']; ?>
</font>)  
                         <font color="#336633"><?php echo $this->_tpl_vars['goods']['product_sn']; ?>
 </font><br />
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</td>
			<td valign="top"><?php echo $this->_tpl_vars['item']['addr_consignee']; ?>
</td>
			<td valign="top">
			<?php if ($this->_tpl_vars['item']['blance'] > 0): ?>应收：<?php echo $this->_tpl_vars['item']['blance']; ?>
<br /><?php endif; ?>
			<?php if ($this->_tpl_vars['item']['price_payed']+$this->_tpl_vars['item']['account_payed']+$this->_tpl_vars['item']['point_payed']+$this->_tpl_vars['item']['gift_card_payed']+$this->_tpl_vars['item']['price_from_return']>0): ?>已收：<?php echo $this->_tpl_vars['item']['price_payed']+$this->_tpl_vars['item']['account_payed']+$this->_tpl_vars['item']['point_payed']+$this->_tpl_vars['item']['gift_card_payed']+$this->_tpl_vars['item']['price_from_return']; ?>
<br /><?php endif; ?>
			<?php if ($this->_tpl_vars['item']['blance'] < 0): ?>应退：<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['blance'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", "") : smarty_modifier_replace($_tmp, "-", "")); ?>
<br /><?php endif; ?>
			</td>
			<td valign="top"><?php echo $this->_tpl_vars['item']['pay_name']; ?>
</td>
			<td valign="top"><?php if ($this->_tpl_vars['item']['lock_name']): ?><font color="red">被<?php echo $this->_tpl_vars['item']['lock_name']; ?>
锁定</font><?php else: ?>未锁定<?php endif; ?></td>
		  </tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
		<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 
		<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="超级锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"super-lock",)));?>/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="超级解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"super-lock",)));?>/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="批量取消订单" onclick="if (confirm('确认执行批量取消订单操作？')) {ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"confirm-batch-cancel",)));?>','Gurl(\'refresh\')');}">
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>