<?php /* Smarty version 2.6.19, created on 2014-12-22 16:57:44
         compiled from finance/receivable-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'finance/receivable-list.tpl', 112, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
生成日期：<input type="text" name="fromdate" id="fromdate" size="12" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()"/>
- <input type="text" name="todate" id="todate" size="12" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate"  onClick="WdatePicker()"/>
发货日期：<input type="text" name="send_fromdate" id="send_fromdate" size="12" value="<?php echo $this->_tpl_vars['param']['send_fromdate']; ?>
" class="Wdate" onClick="WdatePicker()"/>
- <input type="text" name="send_todate" id="send_todate" size="12" value="<?php echo $this->_tpl_vars['param']['send_todate']; ?>
" class="Wdate"  onClick="WdatePicker()"/>
支付类型：
<select name="receivable_type" id="receivable_type" style="width:80px" onchange="changeType(this.value)">
  <option value="">请选择...</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['receivable_type'] == 1): ?>selected<?php endif; ?>>在线支付</option>
  <option value="2" <?php if ($this->_tpl_vars['param']['receivable_type'] == 2): ?>selected<?php endif; ?>>后台支付</option>
  <option value="5" <?php if ($this->_tpl_vars['param']['receivable_type'] == 5): ?>selected<?php endif; ?>>抵扣支付</option>
  <option value="6" <?php if ($this->_tpl_vars['param']['receivable_type'] == 6): ?>selected<?php endif; ?>>换货支付</option>
</select>
<select name="pay_type" id="pay_type">
</select>
<br><br>
渠道：<select name="entry" id="entry" onchange="changeEntry(this.value)">
  <option value="">请选择...</option>
  <option value="self" <?php if ($this->_tpl_vars['param']['entry'] == 'self'): ?>selected<?php endif; ?>>官网自营</option>
</select>
<select name="type" id="type">
  <option value="">请选择...</option>
</select>
结款状态：
<select name="clear_pay" id="clear_pay">
  <option value="">请选择...</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['clear_pay'] == '0'): ?>selected<?php endif; ?>>未结款</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['clear_pay'] == '1'): ?>selected<?php endif; ?>>已结款</option>
</select>
单据编号：<input type="text" name="batch_sn" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['batch_sn']; ?>
"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
<input type="reset" name="reset" value="清除">
<input type="button" onclick="if ($('pay_type').value == ''){alert('请先选择支付类型');return;}openDiv('/admin/finance/receivable-clear/pay_type/'+$('pay_type').value,'ajax','批量结款',780,400);" value="批量结款">
</form>
</div>
<?php endif; ?>
<div id="ajax_search">
<div class="title">应收款查询</div>
<form name="myForm" id="myForm">
<div class="content">
    <div style="text-align:right;">
      <b>应收金额：<?php echo $this->_tpl_vars['total']['amount']; ?>
&nbsp;&nbsp;已结金额：<?php echo $this->_tpl_vars['total']['settle_amount']; ?>
&nbsp;&nbsp;佣金：<?php echo $this->_tpl_vars['total']['commission']; ?>
&nbsp;&nbsp;差异：<?php echo $this->_tpl_vars['total']['diff']; ?>
</b>&nbsp;&nbsp;<br><br>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>操作</td>
            <td>渠道</td>
            <td>单据编号</td>
            <td>支付方式</td>
            <td>应收金额</td>
            <td>已结金额</td>
            <td>佣金</td>
            <td>生成日期</td>
            <td>发货日期</td>
            <td>结算日期</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
        <td>
	      <input type="button" onclick="window.open('/admin/order/info/batch_sn/<?php echo $this->_tpl_vars['data']['batch_sn']; ?>
')" value="查看">
	      <?php if ($this->_tpl_vars['data']['settle_time'] == 0): ?>
	        <input type="button" onclick="openDiv('/admin/finance/receivable-single-clear/id/<?php echo $this->_tpl_vars['data']['id']; ?>
','ajax','应收款结款',300,150,true)" value="结款">
	      <?php endif; ?>
        </td>
        <td>
          <?php if ($this->_tpl_vars['data']['shop_name']): ?><?php echo $this->_tpl_vars['data']['shop_name']; ?>

          <?php elseif ($this->_tpl_vars['data']['type'] == 14): ?><?php echo $this->_tpl_vars['data']['addr_consignee']; ?>

          <?php elseif ($this->_tpl_vars['data']['user_name'] == 'gift'): ?>客情
          <?php elseif ($this->_tpl_vars['data']['user_name'] == 'other'): ?>其它
          <?php elseif ($this->_tpl_vars['data']['user_name'] == 'internal'): ?>内购
          <?php elseif ($this->_tpl_vars['data']['status'] == 4): ?>
            <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['areaID'] => $this->_tpl_vars['areaName']):
?>
            <?php if ($this->_tpl_vars['areaID'] == $this->_tpl_vars['distributionUsername'][$this->_tpl_vars['data']['user_name']]): ?><?php echo $this->_tpl_vars['areaName']; ?>
<?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($this->_tpl_vars['data']['pay_type'] == 'sf' || $this->_tpl_vars['data']['pay_type'] == 'ems'): ?><?php echo $this->_tpl_vars['data']['logistic_no']; ?>

          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'external' || $this->_tpl_vars['data']['pay_type'] == 'externalself'): ?><?php echo $this->_tpl_vars['data']['external_order_sn']; ?>

          <?php else: ?><?php echo $this->_tpl_vars['data']['batch_sn']; ?>

          <?php endif; ?>
        </td>
        <td>
          <?php if ($this->_tpl_vars['data']['pay_type'] == 'alipay'): ?>支付宝
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'phonepay'): ?>手机支付
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'tenpay'): ?>财付通
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'bankcomm'): ?>交通银行
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'bank'): ?>银行打款
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'cash'): ?>现金支付
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'credit'): ?>赊销支付
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'distribution'): ?>直供支付
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'external'): ?>渠道支付
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'externalself'): ?>渠道代发货支付
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'sf'): ?>顺丰
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'ems'): ?>EMS
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'gift'): ?>礼品卡
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'point'): ?>积分
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'account'): ?>账户余额
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'exchange'): ?>换货支付
          <?php elseif ($this->_tpl_vars['data']['pay_type'] == 'easipay'): ?>东方支付
          <?php endif; ?>
        </td>
        <td><?php echo $this->_tpl_vars['data']['amount']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['settle_amount']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['commission']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['logistic_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['settle_time']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['settle_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
<?php endif; ?></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
<script>
function changeType(val)
{
    $('pay_type').options.length = 0;
    if (val == 1) {
        $('pay_type').options.add(new Option('东方支付', 'easipay'<?php if ($this->_tpl_vars['param']['pay_type'] == 'easipay'): ?>, true, true<?php endif; ?>));
      
    }
    else if (val == 2) {
        $('pay_type').options.add(new Option('银行打款', 'bank'<?php if ($this->_tpl_vars['param']['pay_type'] == 'bank'): ?>, true, true<?php endif; ?>));
        $('pay_type').options.add(new Option('现金支付', 'cash'<?php if ($this->_tpl_vars['param']['pay_type'] == 'cash'): ?>, true, true<?php endif; ?>));
    }
    else if (val == 3) {
        $('pay_type').options.add(new Option('渠道支付', 'external'<?php if ($this->_tpl_vars['param']['pay_type'] == 'external'): ?>, true, true<?php endif; ?>));
        $('pay_type').options.add(new Option('渠道代发货支付', 'externalself'<?php if ($this->_tpl_vars['param']['pay_type'] == 'externalself'): ?>, true, true<?php endif; ?>));
    }
    else if (val == 4) {
        $('pay_type').options.add(new Option('顺丰', 'sf'<?php if ($this->_tpl_vars['param']['pay_type'] == 'sf'): ?>, true, true<?php endif; ?>));
        $('pay_type').options.add(new Option('EMS', 'ems'<?php if ($this->_tpl_vars['param']['pay_type'] == 'ems'): ?>, true, true<?php endif; ?>));
    }
    else if (val == 5) {
        $('pay_type').options.add(new Option('礼品卡', 'gift'<?php if ($this->_tpl_vars['param']['pay_type'] == 'gift'): ?>, true, true<?php endif; ?>));
        $('pay_type').options.add(new Option('账户余额', 'account'<?php if ($this->_tpl_vars['param']['pay_type'] == 'account'): ?>, true, true<?php endif; ?>));
    }
    else if (val == 6) {
        $('pay_type').options.add(new Option('换货支付', 'exchange'<?php if ($this->_tpl_vars['param']['pay_type'] == 'exchange'): ?>, true, true<?php endif; ?>));
    }
    else {
        $('pay_type').options.add(new Option('请选择...', ''));
    }
}

function changeEntry(val)
{
    $('type').options.length = 0;
    $('type').options.add(new Option('请选择...', ''));
    if (val == 'self') {
        $('type').options.add(new Option('海淘网', 'haitao'<?php if ($this->_tpl_vars['param']['type'] == 'haitao'): ?>, true, true<?php endif; ?>));
        //$('type').options.add(new Option('国药内购', 'employee'<?php if ($this->_tpl_vars['param']['type'] == 'employee'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('客情', 'gift'<?php if ($this->_tpl_vars['param']['type'] == 'gift'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('内购', 'internal'<?php if ($this->_tpl_vars['param']['type'] == 'internal'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('其他', 'other'<?php if ($this->_tpl_vars['param']['type'] == 'other'): ?>, true, true<?php endif; ?>));
    }
    else if (val == 'call') {
        $('type').options.add(new Option('呼入', 'call_in'<?php if ($this->_tpl_vars['param']['type'] == 'call_in'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('呼出', 'call_out'<?php if ($this->_tpl_vars['param']['type'] == 'call_out'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('咨询', 'call_tq'<?php if ($this->_tpl_vars['param']['type'] == 'call_tq'): ?>, true, true<?php endif; ?>));
    }
    else if (val == 'channel') {
        for (i = 0; i < shopData.length; i++) {
            shop = shopData[i].split('_');
            if (shop[0] == 'jiankang' || shop[0] == 'tuan' || shop[0] == 'credit' || shop[0] == 'distribution')   continue;
            
            if (type == shop[1]) {
                $('type').options.add(new Option(shop[2], shop[1], true, true));
            }
            else    $('type').options.add(new Option(shop[2], shop[1]));
        }
    }
    else if (val == 'distribution') {
        $('type').options.add(new Option('购销', 'batch_channel'<?php if ($this->_tpl_vars['param']['type'] == 'batch_channel'): ?>, true, true<?php endif; ?>));
        <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
          <?php if ($this->_tpl_vars['key'] > 20): ?>
          $('type').options.add(new Option('<?php echo $this->_tpl_vars['item']; ?>
', '<?php echo $this->_tpl_vars['distributionArea'][$this->_tpl_vars['key']]; ?>
'<?php if ($this->_tpl_vars['param']['type'] == $this->_tpl_vars['distributionArea'][$this->_tpl_vars['key']]): ?>, true, true<?php endif; ?>));
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
    }
    else if (val == 'new_distribution') {
        for (i = 0; i < shopData.length; i++) {
            shop = shopData[i].split('_');
            if (shop[0] != 'distribution')   continue;
            
            if (type == shop[1]) {
                $('type').options.add(new Option(shop[2], shop[1], true, true));
            }
            else    $('type').options.add(new Option(shop[2], shop[1]));
        }
    }
    else if (val == 'tuan') {
        for (i = 0; i < shopData.length; i++) {
            shop = shopData[i].split('_');
            if (shop[0] != 'tuan' && shop[0] != 'credit')   continue;
            
            if (type == shop[1]) {
                $('type').options.add(new Option(shop[2], shop[1], true, true));
            }
            else    $('type').options.add(new Option(shop[2], shop[1]));
        }
    }
}


var type = '<?php echo $this->_tpl_vars['param']['type']; ?>
';
var shopData = new Array();
<?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['shop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['shop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['shop']):
        $this->_foreach['shop']['iteration']++;
?>
<?php $this->assign('index', $this->_foreach['shop']['iteration']-1); ?>
shopData[<?php echo $this->_tpl_vars['index']; ?>
] = '<?php echo $this->_tpl_vars['shop']['shop_type']; ?>
_<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
_<?php echo $this->_tpl_vars['shop']['shop_name']; ?>
';
<?php endforeach; endif; unset($_from); ?>

changeType($('receivable_type').value);
changeEntry($('entry').value);

</script>