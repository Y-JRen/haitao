<?php /* Smarty version 2.6.19, created on 2015-02-26 14:28:24
         compiled from order/edit-address.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'order/edit-address.tpl', 12, false),)), $this); ?>
<form id="myform1">
<input type="hidden" name="order_sn" value="<?php echo $this->_tpl_vars['data']['order_sn']; ?>
" />
<input type="hidden" name="type" value="submit" />
<input type="hidden" name="order_step" value="{$order_step}" />
<table>
	<tr><td>收货人</td><td><input type='text' name='addr_consignee' value='<?php echo $this->_tpl_vars['data']['addr_consignee']; ?>
'></td></tr>
	<tr>
		<td>地区</td>
		<td>
			<select name="addr_province_id" onchange="getArea(this)">
				<option value="">请选择省</option>
				<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['data']['addr_province_option'],'selected' => $this->_tpl_vars['data']['addr_province_id']), $this);?>

			</select>
			<select name="addr_city_id" onchange="getArea(this)">
				<option value="">请选择市</option>
				<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['data']['addr_city_option'],'selected' => $this->_tpl_vars['data']['addr_city_id']), $this);?>

			</select>
			<select name="addr_area_id" id="addr_area_id">
				<option value="">请选择区</option>
				<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['data']['addr_area_option'],'selected' => $this->_tpl_vars['data']['addr_area_id']), $this);?>

			</select>
		</td>
	</tr>
	<tr><td>收货地址</td><td><input type='text' size="100" name='addr_address' value='<?php echo $this->_tpl_vars['data']['addr_address']; ?>
'></td></tr>
	<tr><td>电话</td><td><input type='text' name='addr_tel' value='<?php echo $this->_tpl_vars['data']['addr_tel']; ?>
'></td></tr>
	<tr><td>手机</td><td><input type='text' name='addr_mobile' value='<?php echo $this->_tpl_vars['data']['addr_mobile']; ?>
'></td></tr>
	<?php if ($this->_tpl_vars['data']['sms_no']): ?>
	<tr><td>短信接收号码</td><td><input type='text' name='sms_no' value='<?php echo $this->_tpl_vars['data']['sms_no']; ?>
'></td></tr>
	<?php endif; ?>
	<tr><td>邮箱</td><td><input type='text' name='addr_email' value='<?php echo $this->_tpl_vars['data']['addr_email']; ?>
'></td></tr>
	<tr><td>邮编</td><td><input type='text' name='addr_zip' value='<?php echo $this->_tpl_vars['data']['addr_zip']; ?>
'/></td></tr>
	<tr>
		<td></td>
		<td>
			<input type="button" value="确定" onclick="if($('addr_area_id').value==''){alert('请选择省市区！');return false;} ajax_submit($('myform1'),'<?php echo $this -> callViewHelper('url', array(array('action'=>"edit-address",)));?>')" />
			<input type="button" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"not-confirm-info",)));?>')" value=" 返回订单页 " name="do"/>		
		</td>
	</tr>
</table>

</form>
<script>

function getArea(id)
{
    var value = id.value;
    var select = $(id).getNext();
    var parent = $(id).getParent();
    var last = parent.getLast();
    last.options.length = 1;

    new Request({
        url: '<?php echo $this -> callViewHelper('url', array(array('action'=>'area',)));?>/parent_id/' + value,
        //onRequest: loading,
        onSuccess:function(data){
            select.options.length = 1;
	        if (data != '') {
	            data = JSON.decode(data);
	            $each(data, function(item, index){
	                var option = document.createElement("OPTION");
					option.value = index;
					option.text  = item;
                    select.options.add(option);
	            });
	            if (select.name == 'addr_area_id') {
    	            var option = document.createElement("OPTION");
    			    option.value = -1;
    			    option.text  = '其它区';
                    select.options.add(option);
                }
	        }
            //loadSucess();
        }
    }).send();
}

</script>