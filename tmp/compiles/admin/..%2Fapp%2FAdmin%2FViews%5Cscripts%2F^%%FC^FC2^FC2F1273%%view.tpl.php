<?php /* Smarty version 2.6.19, created on 2014-12-30 16:14:01
         compiled from replenishment/view.tpl */ ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" id="common">
<tbody>
    <tr> 
      <td width="10%"><strong>产品编码</strong></td>
      <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
    </tr>
    <tr> 
      <td><strong>产品名称</strong></td>
      <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
</td>
    </tr>
    <tr> 
      <td><strong>请求数量</strong></td>
      <td><?php echo $this->_tpl_vars['data']['require_number']; ?>
</td>
    </tr>
    <tr> 
      <td><strong>收货数量</strong></td>
      <td><?php echo $this->_tpl_vars['data']['receive_number']; ?>
</td>
    </tr>
</tody>
</table>
<div id="ajax_search">
<?php if (! empty ( $this->_tpl_vars['details'] )): ?>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>订单号</td>
        <td>店铺</td>
        <td>产品数量</td>
        <td>订单状态</td>
        <td>业务状态</td>
    </tr>
</thead>
<tbody>
<?php $_from = $this->_tpl_vars['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['order']):
?>
<tr>
    <td><?php if ($this->_tpl_vars['order']['type'] == '2'): ?> <?php echo $this->_tpl_vars['order']['batch_sn']; ?>
<?php else: ?><?php echo $this->_tpl_vars['order']['external_order_sn']; ?>
<?php endif; ?></td>
    <td><?php echo $this->_tpl_vars['order']['shop_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['order']['number']; ?>
</td>
    <td>
      <?php if ($this->_tpl_vars['order']['type'] == '2'): ?>
          <?php if ($this->_tpl_vars['order']['status'] == '0'): ?>正常单
          <?php elseif ($this->_tpl_vars['order']['status'] == 1): ?>取消单
          <?php elseif ($this->_tpl_vars['order']['status'] == 2): ?>无效单
          <?php elseif ($this->_tpl_vars['order']['status'] == 3): ?>渠道刷单
          <?php elseif ($this->_tpl_vars['order']['status'] == 4): ?>不发货订单
          <?php elseif ($this->_tpl_vars['order']['status'] == 5): ?>预售订单
          <?php endif; ?>
      <?php else: ?>
          <?php if ($this->_tpl_vars['order']['shop_order_status'] == 2): ?>待发货
          <?php elseif ($this->_tpl_vars['order']['shop_order_status'] == 3): ?>待确认收货
          <?php elseif ($this->_tpl_vars['order']['shop_order_status'] == 10): ?>已完成
          <?php elseif ($this->_tpl_vars['order']['shop_order_status'] == 11): ?>已取消
          <?php elseif ($this->_tpl_vars['order']['shop_order_status'] == 12): ?>其它
          <?php endif; ?>
      <?php endif; ?>
    </td>
    <td>
    <?php if ($this->_tpl_vars['order']['type'] == '2'): ?>
    <?php if ($this->_tpl_vars['order']['status_logistic'] == 0): ?>未确认
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 1): ?>待收款
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 2): ?>待发货
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 3): ?>已发货
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 4): ?>已签收
    <?php elseif ($this->_tpl_vars['order']['status_logistic'] == 5): ?>已拒收
    <?php endif; ?>
    <?php else: ?>
    <?php if ($this->_tpl_vars['order']['status_business'] == 0): ?>未审核
    <?php elseif ($this->_tpl_vars['order']['status_business'] == 1): ?>审核通过
    <?php elseif ($this->_tpl_vars['order']['status_business'] == 2): ?>已打印
    <?php elseif ($this->_tpl_vars['order']['status_business'] == 4): ?>直接第3方物流发货
    <?php elseif ($this->_tpl_vars['order']['status_business'] == 9): ?>审核不通过
    <?php endif; ?>
    <?php endif; ?>
    </td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
<?php endif; ?>
</div>
<!--
<?php if (! $this->_tpl_vars['data']['status']): ?>
<br>
<div style="text-align:center">
<form name="myForm1" id="myForm1">
<input type="button" name="confirm" value="确认" onclick="ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');">
</form>
</div>
<?php endif; ?>
-->