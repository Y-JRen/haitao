<?php /* Smarty version 2.6.19, created on 2014-08-01 13:47:23
         compiled from product/cost-edit.tpl */ ?>
<form name="myForm1" id="myForm1" action="<?php echo $this -> callViewHelper('url', array());?>" method="post" target="ifrmSubmit" onsubmit="if(confirm('确定要修改吗？')){return true;}return false;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" id="common">
<tbody>
    <tr> 
      <td width="20%"><strong>产品名称</strong> * </td>
      <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
</td>
    </tr>
    <tr> 
      <td><strong>商品编码</strong> * </td>
      <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
    </tr>
    <tr> 
      <td><strong>建议销售价</strong> * </td>
      <td><input type="text" name="suggest_price" size="8" value="<?php echo $this->_tpl_vars['data']['suggest_price']; ?>
" msg="请填写建议销售价" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>(采购)成本价</strong> * </td>
      <td><input type="text" name="purchase_cost" size="8" value="<?php echo $this->_tpl_vars['data']['purchase_cost']; ?>
" msg="请填写成本价" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>(移动)成本价</strong> * </td>
      <td><?php echo $this->_tpl_vars['data']['cost']; ?>
</td>
    </tr>
     <tr> 
      <td><strong>(移动)初始成本价</strong> * </td>
      <td><?php echo $this->_tpl_vars['data']['init_cost']; ?>
</td>
    </tr>
    <tr>
      <td><strong>未税成本价</strong></td>
      <td><input type="text" name="cost_tax" size="8" value="<?php echo $this->_tpl_vars['data']['cost_tax']; ?>
"/></td>
    </tr>
    <tr> 
      <td><strong>发票税率</strong></td>
      <td><input type="text" name="invoice_tax_rate" size="8" value="<?php echo $this->_tpl_vars['data']['invoice_tax_rate']; ?>
"/></td>
    </tr>
</tbody>
</table>
<?php if ($this->_tpl_vars['data']['p_lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
<?php endif; ?>
</form>
<script>
function NumOnly(e)
{
    var key = window.event ? e.keyCode : e.which;
    return key>=48&&key<=57||key==46||key==8;
}
</script>