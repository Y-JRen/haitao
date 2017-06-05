<?php /* Smarty version 2.6.19, created on 2014-12-30 16:08:33
         compiled from replenishment/new.tpl */ ?>
<form name="myForm2" id="myForm2" method="post" action="<?php echo $this -> callViewHelper('url', array());?>" target="ifrmSubmit">
<input type="hidden" name="logic_area" value="1">
<input type="hidden" name="bill_type" value="18">
<input type="hidden" name="replenishment_ids" value="<?php echo $this->_tpl_vars['replenishment_ids']; ?>
">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" id="common">
<tbody>
    <tr> 
      <td width="20%"><strong>单据类型</strong></td>
      <td>补货入库单</td>
    </tr>
    <tr> 
      <td><strong>供应商</strong></td>
      <td><?php echo $this->_tpl_vars['supplier']['supplier_name']; ?>
<input type="hidden" name="supplier_id" value="<?php echo $this->_tpl_vars['supplier']['supplier_id']; ?>
"></td>
    </tr>
    <tr> 
      <td><strong>预计到货日期</strong></td>
      <td><input type="text" name="delivery_date" id="delivery_date" size="15" class="Wdate" onClick="WdatePicker()"/></td>
    </tr>
    <tr> 
      <td><strong>供货商付款信息</strong></td>
      <td>
        银行：<input type="text" size="15" name="bank_name" id="bank_name" value="<?php echo $this->_tpl_vars['supplier']['bank_name']; ?>
">&nbsp;
        银行帐户：<input type="text" size="20" name="bank_account" id="bank_account" value="<?php echo $this->_tpl_vars['supplier']['bank_account']; ?>
">&nbsp;
        银行帐号：<input type="text" size="20" name="bank_sn" id="bank_sn" value="<?php echo $this->_tpl_vars['supplier']['bank_sn']; ?>
">
      </td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td><textarea name="remark" style="width: 400px;height: 50px"></textarea></td>
    </tr>
</tody>
</table>
<div id="ajax_search">
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>产品编码</td>
        <td>产品名称</td>
        <td>需求数量</td>
        <td>入库单价</td>
        <td>申请数量</td>
    </tr>
</thead>
<tbody>
<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
<tr>
    <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
    <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['data']['require_number']; ?>
</td>
    <td><input type="text" size="6" name="price[]" value="<?php echo $this->_tpl_vars['data']['cost']; ?>
" class="required" msg="不能为空"></td>
    <td>
      <input type="text" size="6" name="number[]" value="<?php echo $this->_tpl_vars['data']['require_number']; ?>
" class="required" msg="不能为空" onblur="checkNum(this)"/>
      <input type="hidden" name="batch_id[]" value="0">
      <input type="hidden" name="product_id[]" value="<?php echo $this->_tpl_vars['data']['product_id']; ?>
">
      <input type="hidden" name="ids[]" value="<?php echo $this->_tpl_vars['data']['product_id']; ?>
0">
    </td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
</div>
<br>
<div style="text-align:center">
<input type="submit" name="dosubmit1" id="dosubmit1" value="提交"/>
</div>
</form>