<?php /* Smarty version 2.6.19, created on 2014-08-21 10:14:59
         compiled from finance/pay.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'finance/pay.tpl', 84, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form id="searchForm">
    添加日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
    - <input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
    &nbsp;&nbsp;
    审核日期：<input type="text" name="check_fromdate" id="check_fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['check_fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
    - <input  type="text" name="check_todate" id="check_todate" size="15" value="<?php echo $this->_tpl_vars['param']['check_todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
<div style="clear:both; padding-top:5px">	
        店铺：
        <select name="shop_id" id="shop_id">
        　<option value="">请选择...</option>
          <?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shop']):
?>
             <option value="<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
" <?php if ($this->_tpl_vars['param']['shop_id'] == $this->_tpl_vars['shop']['shop_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['shop']['shop_name']; ?>
</option>
          <?php endforeach; endif; unset($_from); ?>
        </select>
     
     订单编号：<input type="text" name="item_no" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['item_no']; ?>
">
     退款方式:
    <select name="bank_type">
    <option value="">请选择...</option>
    <option value="1" <?php if ($this->_tpl_vars['param']['bank_type'] == '1'): ?>selected<?php endif; ?>>银行转账</option>
    <option value="2" <?php if ($this->_tpl_vars['param']['bank_type'] == '2'): ?>selected<?php endif; ?>>邮政汇款</option>
    <option value="3" <?php if ($this->_tpl_vars['param']['bank_type'] == '3'): ?>selected<?php endif; ?>>帐户余额</option>
    <option value="5" <?php if ($this->_tpl_vars['param']['bank_type'] == '5'): ?>selected<?php endif; ?>>支付宝</option>
    <option value="6" <?php if ($this->_tpl_vars['param']['bank_type'] == '6'): ?>selected<?php endif; ?>>虚拟</option>
    <option value="4" <?php if ($this->_tpl_vars['param']['bank_type'] == '4'): ?>selected<?php endif; ?>>其他</option>
    </select>
     单据状态:
    <select name="status">
    <option value="">请选择...</option>
    <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>待收货</option>
    <option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>未付款</option>
    <option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>已付款</option>
    <option value="3" <?php if ($this->_tpl_vars['param']['status'] == '3'): ?>selected<?php endif; ?>>无效[财务设置]</option>
    <option value="4" <?php if ($this->_tpl_vars['param']['status'] == '4'): ?>selected<?php endif; ?>>无效[系统设置]</option>
    </select>
    订单状态:
    <select name="order_status">
    <option value="">请选择...</option>
    <option value="0" <?php if ($this->_tpl_vars['param']['order_status'] == '0'): ?>selected<?php endif; ?>>有郊单</option>
    <option value="1" <?php if ($this->_tpl_vars['param']['order_status'] == '1'): ?>selected<?php endif; ?>>取消单</option>
    <option value="2" <?php if ($this->_tpl_vars['param']['order_status'] == '2'): ?>selected<?php endif; ?>>无效单</option>
    </select>
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
    </div>	
    </form>
</div>
<div id="ajax_search">
<?php endif; ?>

<div class="title">应退款列表 [<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出信息</a>] </div>
<div style="float:right;top:10px"><br><b>退款金额:<?php echo $this->_tpl_vars['total']['pay']; ?>
&nbsp;&nbsp;&nbsp;积分金额:<?php echo $this->_tpl_vars['total']['point']; ?>
&nbsp;&nbsp;&nbsp;账户余额金额:<?php echo $this->_tpl_vars['total']['account']; ?>
&nbsp;&nbsp;&nbsp;礼品卡金额:<?php echo $this->_tpl_vars['total']['gift']; ?>
</b></div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>店铺</td>
				<td>订单号</td>
                <td>退款类型</td>
				<td>退款金额</td>
				<td>退款方式</td>
				<!--<td>退运费</td>-->
				<td>积分金额</td>
				<td>账户余额金额</td>
				<td>礼品卡金额</td>
				<td>添加日期</td>
				<td>审核日期</td>
				<td>操作</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		<tr id="ajax_list<?php echo $this->_tpl_vars['item']['finance_id']; ?>
">
		    <td><?php echo $this->_tpl_vars['item']['shop_name']; ?>
</td>
			<td>
			  <!--
			  <span style="cursor:pointer" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'info','batch_sn'=>$this->_tpl_vars['item']['item_no'],'finance_id'=>$this->_tpl_vars['item']['finance_id'],)));?>','ajax','查看订单',750,400)"><span>
			  -->
			  <?php echo $this->_tpl_vars['item']['item_no']; ?>
<?php if ($this->_tpl_vars['item']['external_order_sn']): ?><br><?php echo $this->_tpl_vars['item']['external_order_sn']; ?>
<?php endif; ?>
			</td>
            <td><?php if ($this->_tpl_vars['item']['way'] == 4): ?>代收货款变更<?php elseif ($this->_tpl_vars['item']['way'] == 5): ?>直供结算金额变更<?php elseif ($this->_tpl_vars['item']['way'] == 6): ?>物流公司变更<?php else: ?><?php if ($this->_tpl_vars['item']['item'] == '1'): ?>退货退款<?php else: ?>优惠补偿<?php endif; ?><?php endif; ?></td>
			<td>￥<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['pay'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", "") : smarty_modifier_replace($_tmp, "-", "")); ?>
<br><font color="red"><b><?php if ($this->_tpl_vars['item']['type'] == 1): ?>自营<?php elseif ($this->_tpl_vars['item']['type'] == 0): ?>系统退款<?php else: ?><?php if ($this->_tpl_vars['item']['way'] == 1): ?>中间平台<?php else: ?>我方账户<?php endif; ?><?php endif; ?></font></b></td>
			<td>
			<?php if ($this->_tpl_vars['item']['bank_type'] == 1): ?>
			银行转账
			<?php elseif ($this->_tpl_vars['item']['bank_type'] == 2): ?>
			邮局汇款
			<?php elseif ($this->_tpl_vars['item']['bank_type'] == 3): ?>
			帐户余额
			<?php elseif ($this->_tpl_vars['item']['bank_type'] == 4): ?>
			其他
			<?php elseif ($this->_tpl_vars['item']['bank_type'] == 5): ?>
			支付宝
			<?php elseif ($this->_tpl_vars['item']['bank_type'] == 6): ?>
			虚拟
			<?php endif; ?>			</td>
			<!--<td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['logistic'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", "") : smarty_modifier_replace($_tmp, "-", "")); ?>
</td>-->
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['point'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", "") : smarty_modifier_replace($_tmp, "-", "")); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['account'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", "") : smarty_modifier_replace($_tmp, "-", "")); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['gift'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", "") : smarty_modifier_replace($_tmp, "-", "")); ?>
</td>
			<td><?php echo $this->_tpl_vars['item']['add_time']; ?>
</td>
			<td><?php echo $this->_tpl_vars['item']['check_time']; ?>
</td>
			<td>
			<form id="myform1">
			<input type="hidden" name="finance_id" value="<?php echo $this->_tpl_vars['item']['finance_id']; ?>
" />
            <?php if ($this->_tpl_vars['item']['item'] == '1'): ?>
			<input type="button" value="查看订单" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'order','batch_sn'=>$this->_tpl_vars['item']['item_no'],)));?>','ajax','查看订单',750,400)" />
            <?php endif; ?>
            
			<?php if ($this->_tpl_vars['item']['bank_type'] == 1 || $this->_tpl_vars['item']['bank_type'] == 2): ?>
			<input type="button" value="查看帐户" onclick="openDiv('/admin/finance/bank/finance_id/<?php echo $this->_tpl_vars['item']['finance_id']; ?>
','ajax','查看帐户',750,400)" />
			<?php endif; ?>
			<input type="button" value="查看备注" onclick="openDiv('/admin/finance/note/finance_id/<?php echo $this->_tpl_vars['item']['finance_id']; ?>
','ajax','查看备注',750,400)" />
            
            <?php if ($this->_tpl_vars['item']['item'] == '1'): ?>
			<input type="button" value="打印" onclick="window.open('<?php echo $this -> callViewHelper('url', array(array('action'=>'print','batch_sn'=>$this->_tpl_vars['item']['item_no'],'finance_id'=>$this->_tpl_vars['item']['finance_id'],)));?>');">
            <?php endif; ?>
            
			<?php if ($this->_tpl_vars['item']['status'] == 0): ?>
				<input type="button" value="待收货" disabled="disabled">
			<?php elseif ($this->_tpl_vars['item']['status'] == 1): ?>
				<input type="button" value="付款" onclick="confirmed('付款', $('myform1'), '<?php echo $this -> callViewHelper('url', array(array('action'=>'pass','mod'=>'pay','finance_id'=>$this->_tpl_vars['item']['finance_id'],)));?>')" />
				<input type="button" value="无效" onclick="confirmed('无效', $('myform1'), '<?php echo $this -> callViewHelper('url', array(array('action'=>'invalid','mod'=>'pay','finance_id'=>$this->_tpl_vars['item']['finance_id'],)));?>')" />
			<?php elseif ($this->_tpl_vars['item']['status'] == 2): ?>
				<input type="button" value="已付款" disabled="disabled">
			<?php elseif ($this->_tpl_vars['item']['status'] == 3): ?>
				<input type="button" value="已无效[财务设置]" disabled="disabled">
			<?php elseif ($this->_tpl_vars['item']['status'] == 4): ?>
				<input type="button" value="已无效[系统设置]" disabled="disabled">
			<?php endif; ?>
			</form></td>
		  </tr>
		<?php endforeach; endif; unset($_from); ?>
		</table>
	</div>
		<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>	
<script>
		
	function confirmed(str, obj, url) {
		if (confirm('确认执行 "' + str + '" 操作？')) {
			ajax_submit(obj, url);
		}
	}
</script>