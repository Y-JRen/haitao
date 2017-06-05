<?php /* Smarty version 2.6.19, created on 2015-01-16 17:36:35
         compiled from order/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'order/list.tpl', 22, false),array('modifier', 'truncate', 'order/list.tpl', 170, false),array('modifier', 'date_format', 'order/list.tpl', 176, false),array('modifier', 'replace', 'order/list.tpl', 192, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form id="searchForm" method="get">
     <div>
        <span style="float:left;">下单开始日期：</span><span style="float:left;width:150px;"><input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left;margin-left:10px">下单结束日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/></span>
        <span style="float:left;margin-left:10px">发货开始日期：</span><span style="float:left;"><input type="text" name="logisticfromdate" id="logisticfromdate" size="11" value="<?php echo $this->_tpl_vars['param']['logisticfromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left;margin-left:10px">发货结束日期：</span>
        <span style="float:left;"><input type="text" name="logistictodate" id="logistictodate" size="11" value="<?php echo $this->_tpl_vars['param']['logistictodate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
        <input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value='' ;$('logisticfromdate').value='' ;$('logistictodate').value=''"/>
        </span>
    </div>

<div style="clear:both; padding-top:5px">
    商品编号：<input type="text" name="product_sn" size="6" maxLength="15" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
">
    商品名称：<input type="text" name="goods_name" size="10" maxLength="30" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
">
    联盟ID：<input type="text" name="parent_id" size="4" maxLength="15" value="<?php echo $this->_tpl_vars['param']['parent_id']; ?>
" />
    活动ID：<input type="text" name="offers_id" size="4" maxLength="15" value="<?php echo $this->_tpl_vars['param']['offers_id']; ?>
">
	物流公司：
	<select name="logistic_code">
	  <option value="">请选择</option>
	  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => $this->_tpl_vars['param']['logistic_code']), $this);?>

	</select>
    下单类型:
    <select name="entry" id="entry" onchange="changeEntry(this.value)">
      <option value="">请选择...</option>
      <option value="b2c" <?php if ($this->_tpl_vars['param']['entry'] == 'b2c'): ?>selected<?php endif; ?>>官网B2C</option>
      <!--<option value="channel" <?php if ($this->_tpl_vars['param']['entry'] == 'channel'): ?>selected<?php endif; ?>>渠道运营</option>-->
      <!--<option value="call" <?php if ($this->_tpl_vars['param']['entry'] == 'call'): ?>selected<?php endif; ?>>呼叫中心</option>-->
      <!--<option value="distribution" <?php if ($this->_tpl_vars['param']['entry'] == 'distribution'): ?>selected<?php endif; ?>>渠道分销</option>-->
      <option value="other" <?php if ($this->_tpl_vars['param']['entry'] == 'other'): ?>selected<?php endif; ?>>其它下单</option>
    </select>
    <select name="type" id="type" onchange="changeType(this.value)">
      <option value="">请选择...</option>
	</select>
	<select name="source" id="source">
      <option value="">请选择...</option>
      <option value="0" <?php if ($this->_tpl_vars['param']['source'] == '0'): ?>selected<?php endif; ?>>后台下单</option>
      <option value="1" <?php if ($this->_tpl_vars['param']['source'] == '1'): ?>selected<?php endif; ?>>会员下单</option>
      <option value="2" <?php if ($this->_tpl_vars['param']['source'] == '2'): ?>selected<?php endif; ?>>电话下单</option>
      <option value="3" <?php if ($this->_tpl_vars['param']['source'] == '3'): ?>selected<?php endif; ?>>匿名下单</option>
      <option value="4" <?php if ($this->_tpl_vars['param']['source'] == '4'): ?>selected<?php endif; ?>>试用下单</option>
	</select>
    <br />
    订单ID：<input type="text" name="order_batch_id" size="6" maxLength="50" value="<?php echo $this->_tpl_vars['param']['order_batch_id']; ?>
">
    订单号：<input type="text" name="batch_sn" size="16" maxLength="50" value="<?php echo $this->_tpl_vars['param']['batch_sn']; ?>
">
    收货人名字：<input type="text" name="addr_consignee" size="8" maxLength="50" value="<?php echo $this->_tpl_vars['param']['addr_consignee']; ?>
">
    收货人电话：<input type="text" name="addr_mobile" size="8" maxLength="50" value="<?php echo $this->_tpl_vars['param']['addr_mobile']; ?>
">
    用户名：<input type="text" name="user_name" id="user_name" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['user_name']; ?>
">
    运单号：<input type="text" name="logistic_no" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['logistic_no']; ?>
">
    订单金额：<input type="text" name="price_order_from" size="4" maxLength="50" value="<?php echo $this->_tpl_vars['param']['price_order_from']; ?>
">
    - <input type="text" name="price_order_to" size="4" maxLength="50" value="<?php echo $this->_tpl_vars['param']['price_order_to']; ?>
">
    <br />
     订单状态:
    <select name="status">
    <option value="">请选择...</option>
    <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>有效单</option>
    <option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>取消单</option>
    <option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>无效单</option>
    <!--<option value="3" <?php if ($this->_tpl_vars['param']['status'] == '3'): ?>selected<?php endif; ?>>渠道刷单</option>-->
    <!--<option value="4" <?php if ($this->_tpl_vars['param']['status'] == '4'): ?>selected<?php endif; ?>>分销单</option>-->
    <option value="5" <?php if ($this->_tpl_vars['param']['status'] == '5'): ?>selected<?php endif; ?>>预售单</option>
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
     支付状态:
    <select name="status_pay">
    <option value="">请选择...</option>
    <option value="0" <?php if ($this->_tpl_vars['param']['status_pay'] == '0'): ?>selected<?php endif; ?>>未收款</option>
    <option value="1" <?php if ($this->_tpl_vars['param']['status_pay'] == '1'): ?>selected<?php endif; ?>>未退款</option>
    <option value="2" <?php if ($this->_tpl_vars['param']['status_pay'] == '2'): ?>selected<?php endif; ?>>已收款</option>
    <option value="3" <?php if ($this->_tpl_vars['param']['status_pay'] == '3'): ?>selected<?php endif; ?>>部分收款</option>
    </select>
    配送状态:
    <select name="status_logistic">
    <option value="">请选择...</option>
    <option value="0" <?php if ($this->_tpl_vars['param']['status_logistic'] == '0'): ?>selected<?php endif; ?>>未确认</option>
    <option value="1" <?php if ($this->_tpl_vars['param']['status_logistic'] == '1'): ?>selected<?php endif; ?>>已确认</option>
    <option value="2" <?php if ($this->_tpl_vars['param']['status_logistic'] == '2'): ?>selected<?php endif; ?>>待发货</option>
    <option value="3" <?php if ($this->_tpl_vars['param']['status_logistic'] == '3'): ?>selected<?php endif; ?>>已发货未签收</option>
    <option value="4" <?php if ($this->_tpl_vars['param']['status_logistic'] == '4'): ?>selected<?php endif; ?>>客户已签收</option>
    <option value="5" <?php if ($this->_tpl_vars['param']['status_logistic'] == '5'): ?>selected<?php endif; ?>>客户拒收</option>
    <option value="6" <?php if ($this->_tpl_vars['param']['status_logistic'] == '6'): ?>selected<?php endif; ?>>部分签收</option>
    </select>
    退换货状态:
    <select name="status_return">
    <option value="">请选择...</option>
    <option value="0" <?php if ($this->_tpl_vars['param']['status_return'] == '0'): ?>selected<?php endif; ?>>正常单</option>
    <option value="1" <?php if ($this->_tpl_vars['param']['status_return'] == '1'): ?>selected<?php endif; ?>>退货单</option>
    </select>
    赠送人：
    <input type="text" name="giftbywho" value="<?php echo $this->_tpl_vars['param']['giftbywho']; ?>
" size="8"/>
    <br />
	是否开票:
    <select name="invoice_type">
    <option value="">请选择...</option>
    <option value="0" <?php if ($this->_tpl_vars['param']['invoice_type'] == '0'): ?>selected<?php endif; ?>>不需要</option>
    <option value="1" <?php if ($this->_tpl_vars['param']['invoice_type'] == '1'): ?>selected<?php endif; ?>>需要</option>
    </select>
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
    <input type="checkbox" name="only_show_gift" value="1" <?php if ($this->_tpl_vars['param']['only_show_gift']): ?>checked<?php endif; ?>/>只显示礼品卡
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search','page'=>1,)));?>','ajax_search')"/>
    <input type="button" name="dosearch" value="所有被我锁定的订单" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search','is_lock'=>yes,)));?>','ajax_search')"/>
    <input type="button" name="dosearch" value="所有没有锁定的订单" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search','is_lock'=>no,)));?>','ajax_search')"/>
    <input type="button" name="dosearch" value="所有挂起的订单" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search','hang'=>1,)));?>','ajax_search')"/>
    </div>	
    </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">订单查询列表 <?php if ($this->_tpl_vars['auth']['group_id'] == 1 || $this->_tpl_vars['auth']['group_id'] == 11 || $this->_tpl_vars['auth']['group_id'] == 14 || $this->_tpl_vars['auth']['admin_id'] == 66 || $this->_tpl_vars['auth']['admin_id'] == 60): ?>[<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出订单</a>]<?php endif; ?></div>
	<div class="content">
<div style="padding:0 5px">
	<div style="float:left;width:500px;">
		<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 
		<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="超级锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"super-lock",)));?>/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="超级解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"super-lock",)));?>/lock/0','Gurl(\'refresh\')')">
	</div>
	<div style="float:right;"><b>订单总金额：￥<?php echo $this->_tpl_vars['totalPriceOrder']; ?>
<?php if ($this->_tpl_vars['auth']['group_id'] == 1 || $this->_tpl_vars['auth']['group_id'] == 11): ?>&nbsp;&nbsp;&nbsp;财务总金额：￥<?php echo $this->_tpl_vars['totalBalanceAmount']; ?>
<?php if ($this->_tpl_vars['totalVitualAmount']): ?>&nbsp;&nbsp;&nbsp;礼品卡总金额：￥<?php echo $this->_tpl_vars['totalVitualAmount']; ?>
<?php endif; ?><?php endif; ?></b></div>
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td width=10></td>
				<td width="40">操作</td>
				<td>ID</td>
				<td width="120">订单号</td>
                <td>下单时间</td>
                <td>发货时间</td>
				<td width="350">订单商品</td>
				<td width="60">收货人</td>
				<td>金额</td>
                <td>运费</td>
				<td>支付方式</td>
				<td>锁定状态</td>
                <td>申报状态</td>
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
			<?php if ($this->_tpl_vars['item']['lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>
			<input type="button" onclick="G('/admin/order/info/batch_sn/<?php echo $this->_tpl_vars['item']['batch_sn']; ?>
')" value="修改">
			<?php else: ?>
			<input type="button" onclick="G('/admin/order/info/batch_sn/<?php echo $this->_tpl_vars['item']['batch_sn']; ?>
')" value="查看">
			<?php endif; ?></td>
			<td valign="top"><?php echo $this->_tpl_vars['item']['order_batch_id']; ?>
<br /><?php echo $this->_tpl_vars['item']['shop_name']; ?>
</td>
			<td valign="top">
			<?php echo $this->_tpl_vars['item']['batch_sn']; ?>
<br />
			<?php echo $this->_tpl_vars['item']['status']; ?>
  <?php echo $this->_tpl_vars['item']['status_pay']; ?>
 <?php echo $this->_tpl_vars['item']['status_logistic']; ?>
  <?php echo $this->_tpl_vars['item']['status_return']; ?>
   <br />
			<?php if ($this->_tpl_vars['item']['union_id']): ?><font color="red">联盟ID:</font><?php echo $this->_tpl_vars['item']['union_id']; ?>
<br />
            联盟参数<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['parent_param'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 12, "") : smarty_modifier_truncate($_tmp, 12, "")); ?>

            <?php endif; ?>
			<?php if ($this->_tpl_vars['item']['hang']): ?><font color="red">已被<?php echo $this->_tpl_vars['item']['hang_admin_name']; ?>
挂起</font><br /><?php endif; ?>
			<?php if ($this->_tpl_vars['item']['status_back'] == 1): ?><font color="red">已申请取消</font><br /><?php endif; ?>
			<?php if ($this->_tpl_vars['item']['status_back'] == 2): ?><font color="red">已申请返回</font><?php endif; ?>
			</td>
             <td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
              <td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['logistic_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
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
 </font>
						 数量：<?php echo $this->_tpl_vars['goods']['number']; ?>
 
						 <br />
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
            <td valign="top"><?php echo $this->_tpl_vars['item']['price_logistic']; ?>
</td>
			<td valign="top"><?php echo $this->_tpl_vars['item']['pay_name']; ?>
</td>
			<td valign="top"><?php if ($this->_tpl_vars['item']['lock_name']): ?><font color="red">被<?php echo $this->_tpl_vars['item']['lock_name']; ?>
锁定</font><?php else: ?>未锁定<?php endif; ?></td>
            <td>
            <?php if ($this->_tpl_vars['item']['status_declare'] == 0): ?>未申报
            <?php elseif ($this->_tpl_vars['item']['status_declare'] == 1): ?>已申报，未审核
            <?php elseif ($this->_tpl_vars['item']['status_declare'] == 2): ?>已审核
            <?php elseif ($this->_tpl_vars['item']['status_declare'] == 3): ?>审核失败
            <?php else: ?>未知<?php endif; ?>
            </td>
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
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
<script>
function changeEntry(val)
{
    $('type').options.length = 0;
    $('type').options.add(new Option('请选择...', ''));
    $('source').options.length = 0;
    $('source').options.add(new Option('请选择...', ''));
    $('source').options.add(new Option('后台下单', '0'<?php if ($this->_tpl_vars['param']['source'] == '0'): ?>, true, true<?php endif; ?>));
    if (val == 'b2c') {
        $('type').options.add(new Option('官网下单', '0'<?php if ($this->_tpl_vars['param']['type'] == '0' && $this->_tpl_vars['param']['user_name'] != 'yumi_jiankang' && $this->_tpl_vars['param']['user_name'] != 'xinjing_jiankang'): ?>, true, true<?php endif; ?>));
        //$('type').options.add(new Option('玉米网下单', '0'<?php if ($this->_tpl_vars['param']['type'] == '0' && $this->_tpl_vars['param']['user_name'] == 'yumi_jiankang'): ?>, true, true<?php endif; ?>));
        //$('type').options.add(new Option('信景下单', '0'<?php if ($this->_tpl_vars['param']['type'] == '0' && $this->_tpl_vars['param']['user_name'] == 'xinjing_jiankang'): ?>, true, true<?php endif; ?>));
        
        $('source').options.add(new Option('会员下单', '1'<?php if ($this->_tpl_vars['param']['source'] == '1'): ?>, true, true<?php endif; ?>));
        $('source').options.add(new Option('电话下单', '2'<?php if ($this->_tpl_vars['param']['source'] == '2'): ?>, true, true<?php endif; ?>));
        $('source').options.add(new Option('匿名下单', '3'<?php if ($this->_tpl_vars['param']['source'] == '3'): ?>, true, true<?php endif; ?>));
        $('source').options.add(new Option('试用下单', '4'<?php if ($this->_tpl_vars['param']['source'] == '4'): ?>, true, true<?php endif; ?>));
        
        $('shop_id').options[1].selected = true;
    }
    else if (val == 'call') {
        $('type').options.add(new Option('呼入下单', '10'<?php if ($this->_tpl_vars['param']['type'] == '10'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('呼出下单', '11'<?php if ($this->_tpl_vars['param']['type'] == '11'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('咨询下单', '12'<?php if ($this->_tpl_vars['param']['type'] == '12'): ?>, true, true<?php endif; ?>));
        
        $('source').options.add(new Option('会员下单', '1'<?php if ($this->_tpl_vars['param']['source'] == '1'): ?>, true, true<?php endif; ?>));
        $('source').options.add(new Option('电话下单', '2'<?php if ($this->_tpl_vars['param']['source'] == '2'): ?>, true, true<?php endif; ?>));
        $('source').options.add(new Option('匿名下单', '3'<?php if ($this->_tpl_vars['param']['source'] == '3'): ?>, true, true<?php endif; ?>));
        
        $('shop_id').options[0].selected = true;
    }
    else if (val == 'channel') {
        $('type').options.add(new Option('渠道下单', '13'<?php if ($this->_tpl_vars['param']['type'] == '13'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('渠道补单', '14'<?php if ($this->_tpl_vars['param']['type'] == '14' && $this->_tpl_vars['param']['user_name'] != 'batch_channel' && $this->_tpl_vars['param']['user_name'] != 'credit_channel'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('购销下单', '14'<?php if ($this->_tpl_vars['param']['type'] == '14' && $this->_tpl_vars['param']['user_name'] == 'batch_channel'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('赊销下单', '14'<?php if ($this->_tpl_vars['param']['type'] == '14' && $this->_tpl_vars['param']['user_name'] == 'credit_channel'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('直供下单', '16'<?php if ($this->_tpl_vars['param']['type'] == '16' && $this->_tpl_vars['param']['user_name'] == 'distribution_channel'): ?>, true, true<?php endif; ?>));
        $('shop_id').options[0].selected = true;
    }
    else if (val == 'distribution') {
        <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
          <?php if ($this->_tpl_vars['key'] > 20): ?>
          $('type').options.add(new Option('<?php echo $this->_tpl_vars['item']; ?>
', '18'<?php if ($this->_tpl_vars['param']['type'] == '18' && $this->_tpl_vars['param']['user_name'] == $this->_tpl_vars['distributionArea'][$this->_tpl_vars['key']]): ?>, true, true<?php endif; ?>));
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        $('shop_id').options[0].selected = true;
    }
    else if (val == 'other') {
        $('type').options.add(new Option('赠送下单', '5'<?php if ($this->_tpl_vars['param']['type'] == '5'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('其它下单', '15'<?php if ($this->_tpl_vars['param']['type'] == '15'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('内购下单', '7'<?php if ($this->_tpl_vars['param']['type'] == '7'): ?>, true, true<?php endif; ?>));
        $('shop_id').options[0].selected = true;
    }
    else {
        $('source').options.add(new Option('会员下单', '1'<?php if ($this->_tpl_vars['param']['source'] == '1'): ?>, true, true<?php endif; ?>));
        $('source').options.add(new Option('电话下单', '2'<?php if ($this->_tpl_vars['param']['source'] == '2'): ?>, true, true<?php endif; ?>));
        $('source').options.add(new Option('匿名下单', '3'<?php if ($this->_tpl_vars['param']['source'] == '3'): ?>, true, true<?php endif; ?>));
        $('source').options.add(new Option('试用下单', '4'<?php if ($this->_tpl_vars['param']['source'] == '4'): ?>, true, true<?php endif; ?>));
        //$('shop_id').options[0].selected = true;
    }
    
    changeType($('type').value);
}

function changeType(type)
{
    $('user_name').value = '';
    if (type == '14') {
        var text = $('type').options[$('type').selectedIndex].text;
        if (text == '购销下单') {
            $('user_name').value = 'batch_channel';
        }
        else if (text == '赊销下单') {
            $('user_name').value = 'credit_channel';
        }
    }
    else if (type == '16') {
        $('user_name').value = 'distribution_channel';
    }
    else if (type == '0') {
        var text = $('type').options[$('type').selectedIndex].text;
        if (text == '玉米网下单') {
            $('user_name').value = 'yumi_jiankang';
        }
        else  if (text == '信景下单') {
            $('user_name').value = 'xinjing_jiankang';
        }
    }
    else if (type == '18') {
        var text = $('type').options[$('type').selectedIndex].text;
        for (i = 0; 4 < distributionName.length; i++) {
            if (text == distributionName[i]) {
               $('user_name').value = distributionUsername[i];
               break;
            }
        }
    }
    else if (type == '') {
        if ($('entry').value == 'channel') {
            $('user_name').value = 'channel';
        }
        else if ($('entry').value == 'distribution') {
            $('user_name').value = '_distribution';
        }
    }
}

var distributionName = new Array();
var distributionUsername = new Array();
<?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['key'] > 20): ?>
distributionName.push('<?php echo $this->_tpl_vars['item']; ?>
');
distributionUsername.push('<?php echo $this->_tpl_vars['distributionArea'][$this->_tpl_vars['key']]; ?>
');
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

changeEntry($('entry').value);

</script>