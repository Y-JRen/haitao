<?php /* Smarty version 2.6.19, created on 2017-06-06 23:57:48
         compiled from member/gift-query.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'member/gift-query.tpl', 65, false),)), $this); ?>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/member/gift-query">
<span style="margin-left:5px;">卡号: </span><input type="text" name="card_sn" value="<?php echo $this->_tpl_vars['param']['card_sn']; ?>
" size="15" />
<span style="margin-left:5px;">密码: </span><input type="text" name="card_password" value="<?php echo $this->_tpl_vars['param']['card_password']; ?>
" size="15" />
<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('action'=>"gift-query",'do'=>'search',)));?>','ajax_search')"/>
</form>
</div>
<div id="ajax_search">
<?php endif; ?>
<div class="title">礼品卡信息查询 </div>
<?php if ($this->_tpl_vars['error']): ?>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td align="center">
<font color="red" size="3">
<?php if ($this->_tpl_vars['error'] == 'no_card'): ?>卡号或密码错误！
<?php elseif ($this->_tpl_vars['error'] == 'no_card_log'): ?>找不到开卡信息！
<?php elseif ($this->_tpl_vars['error'] == 'no_card_sn'): ?>卡号必须输入！
<?php endif; ?>
</font>
</td>
</tr>
</table>
</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']): ?>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">礼券类型</td>
<td width="40%">
<?php if ($this->_tpl_vars['data']['card_type'] == 1): ?>出售
<?php elseif ($this->_tpl_vars['data']['card_type'] == 2): ?>赠送
<?php elseif ($this->_tpl_vars['data']['card_type'] == 3): ?>兑换
<?php endif; ?>
</td>
<td width="10%">截止日期</td>
<td width="40%"><?php echo $this->_tpl_vars['data']['end_date']; ?>
</td>
</tr>
<tr>
<td>礼券金额</td>
<td><?php echo $this->_tpl_vars['data']['card_price']; ?>
</td>
<td>剩余金额</td>
<td><?php echo $this->_tpl_vars['data']['card_real_price']; ?>
</td>
</tr>
</table>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['data']['user_id']): ?>
<div class="title">会员信息 </div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">会员名称</td>
<td width="40%"><?php echo $this->_tpl_vars['data']['user_name']; ?>
</td>
<td width="10%">会员ID</td>
<td><?php echo $this->_tpl_vars['data']['user_id']; ?>
</td>
</tr>
<tr>
<td>使用时间</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['using_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</table>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['orderInfo']): ?>
<div class="title">订单信息 </div>
<?php $_from = $this->_tpl_vars['orderInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">订单号</td>
<td width="40%"><a href="/admin/order/info/batch_sn/<?php echo $this->_tpl_vars['data']['batch_sn']; ?>
"><?php echo $this->_tpl_vars['data']['batch_sn']; ?>
</a></td>
<td width="10%">订单状态</td>
<td><?php if ($this->_tpl_vars['data']['status'] == 0): ?>正常单<?php elseif ($this->_tpl_vars['data']['status'] == 1): ?>取消单<?php elseif ($this->_tpl_vars['data']['status'] == 2): ?>无效单<?php endif; ?></td>
</tr>
<tr>
<td>订单金额</td>
<td><?php echo $this->_tpl_vars['data']['price_pay']; ?>
</td>
<td>抵扣金额</td>
<td><?php echo $this->_tpl_vars['data']['consume_amount']; ?>
</td>
</tr>
<tr>
<td>联系方式</td>
<td><?php echo $this->_tpl_vars['data']['addr_consignee']; ?>
 <?php echo $this->_tpl_vars['data']['addr_mobile']; ?>
</td>
<td></td>
<td></td>
</tr>
</table>
<?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
</div>
<?php endif; ?>