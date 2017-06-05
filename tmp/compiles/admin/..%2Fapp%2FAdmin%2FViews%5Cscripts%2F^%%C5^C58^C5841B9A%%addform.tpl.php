<?php /* Smarty version 2.6.19, created on 2014-09-01 14:37:19
         compiled from payment/addform.tpl */ ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post">
<div class="title">添加支付方式</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>所属类别</strong> * </td>
      <td><select name='payment[pay_type]' onchange='listPaymentCfg(this);'>
		<option value=''>--请选择--</option>
		<?php $_from = $this->_tpl_vars['payments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['item']):
?>
		<option value=<?php echo $this->_tpl_vars['id']; ?>
><?php echo $this->_tpl_vars['item']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</select></td>
    </tr>
    <tr>
      <td width="10%"><strong>支付方式名称</strong> * </td>
      <td><input type="text" name='payment[name]' value='<?php echo $this->_tpl_vars['payment']['name']; ?>
' size="30" msg="请填写支付方式名称" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>支付费率</strong> * </td>
      <td><input type="text" name="payment[fee]" size="30" value="<?php echo $this->_tpl_vars['payment']['fee']; ?>
" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>支付介绍</strong> * </td>
      <td><textarea name="payment[dsc]" rows="3" cols="39" style="width:330px; height:45px;"><?php echo $this->_tpl_vars['payment']['dsc']; ?>
</textarea></td>
    </tr>
	
    <tr>
      <td width="10%"><strong>图片标识</strong> * </td>
      <td><input type="text" name="payment[img_url]" size="30" value="<?php echo $this->_tpl_vars['payment']['img_url']; ?>
" /></td>
    </tr>
	
    <tr> 
      <td><strong>类型</strong> * </td>
      <td>
	   <input type="radio" name="payment[is_bank]" value="2" <?php if ($this->_tpl_vars['payment']['is_bank'] == 2): ?>  checked  <?php endif; ?> /> 银行直连
	   <input type="radio" name="payment[is_bank]" value="1" <?php if ($this->_tpl_vars['payment']['is_bank'] == 1): ?>  checked  <?php endif; ?> /> 支付网关银行
	   <input type="radio" name="payment[is_bank]" value="0"   <?php if ($this->_tpl_vars['payment']['is_bank'] == 0): ?>  checked  <?php endif; ?> /> 支付网关
	  </td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="payment[status]" value="0"/> 启用
	   <input type="radio" name="payment[status]" value="1" checked/> 未启用
	  </td>
    </tr>
</tbody>
</table>
</div>
<div id='payment_cfg'><?php echo $this->_tpl_vars['config']; ?>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function listPaymentCfg(obj){
    new Request({
        url: '<?php echo $this -> callViewHelper('url', array(array('action'=>'getplugin',)));?>'+'/payment/'+obj.value,
        onRequest: loading,
        onSuccess:function(data){
            $('payment_cfg').set('html', data);
            loadSucess();
        }
    }).send();
}
</script>