<?php /* Smarty version 2.6.19, created on 2015-01-15 10:21:10
         compiled from goods/price.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'goods/price.tpl', 21, false),)), $this); ?>
<form name="myForm1" id="myForm1" action="<?php echo $this -> callViewHelper('url', array());?>" method="post" target="ifrmSubmit" onsubmit="return check();">
<input type="hidden" name="old_value" value='<?php echo $this->_tpl_vars['old_value']; ?>
'>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
  <tr>
    <td width="10%"><a href="#" onclick="document.getElementById('common').style.display='';document.getElementById('seg').style.display='none';return false;">单品价格</a></td>
    <td><a href="#" onclick="document.getElementById('common').style.display='none';document.getElementById('seg').style.display='';return false;">多个数量价格</a></td>
  </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" id="common">
<tbody>
    <tr> 
      <td width="10%"><strong>商品名称</strong> * </td>
      <td><?php echo $this->_tpl_vars['data']['goods_name']; ?>
</td>
    </tr>
    <tr> 
      <td width="10%"><strong>商品编码</strong> * </td>
      <td><?php echo $this->_tpl_vars['data']['goods_sn']; ?>
</td>
    </tr>
    <tr>
      <td width="10%"><strong>市场价</strong> * </td>
      <td><input type="text" name="market_price" size="8" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['market_price'])) ? $this->_run_mod_handler('default', true, $_tmp, 0.00) : smarty_modifier_default($_tmp, 0.00)); ?>
" msg="请填写市场价" class="required" /></td>
    </tr>
    <tr> 
      <td width="10%"><strong>本店价</strong> * </td>
      <td><input type="text" name="shop_price" id="shop_price" size="8" onchange="calc()" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['shop_price'])) ? $this->_run_mod_handler('default', true, $_tmp, 0.00) : smarty_modifier_default($_tmp, 0.00)); ?>
" msg="请填写本店价" class="required" /></td>
    </tr>
    <tr> 
      <td width="10%"><strong>完税价</strong> * </td>
      <td><input type="text" name="org_tax_price" id="org_tax_price" size="8" onchange="calc()" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['org_tax_price'])) ? $this->_run_mod_handler('default', true, $_tmp, 0.00) : smarty_modifier_default($_tmp, 0.00)); ?>
" msg="请填写完税价" class="required" /></td>
    </tr>
    <tr> 
      <td width="10%"><strong>计量单位</strong> * </td>
      <td><input type="text" name="measurement_unit" id="measurement_unit" size="8" onchange="calc()" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['measurement_unit'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" msg="请填写计量单位" class="required" /></td>
    </tr>
     <tr> 
      <td width="10%"><strong>税率</strong> * </td>
      <td><input type="text" name="tax_rate" id="tax_rate" size="8" onchange="calc()" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['tax_rate'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" msg="请填写税率" class="required" /></td>
    </tr>
    <tr> 
      <td width="10%"><strong>行邮税</strong> * </td>
      <td><input type="text" name="tax" id="tax" size="8" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['tax'])) ? $this->_run_mod_handler('default', true, $_tmp, 0.00) : smarty_modifier_default($_tmp, 0.00)); ?>
" onchange="javascript:alert('aaa');" msg="请填写行邮税" class="required" readonly="readonly"/></td>
    </tr>
   <!--  <tr> 
      <td width="10%"><strong>运费</strong> * </td>
      <td><input type="text" name="fare" id="fare" size="8" onchange="calc()" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['fare'])) ? $this->_run_mod_handler('default', true, $_tmp, 0.00) : smarty_modifier_default($_tmp, 0.00)); ?>
" msg="请填写本店价" class="required" /></td>
    </tr> -->
    <tr> 
      <td width="10%"><strong>商品总价</strong> * </td>
      <td><input type="text" name="price" id="price" size="8" readonly="readonly" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['price'])) ? $this->_run_mod_handler('default', true, $_tmp, 0.00) : smarty_modifier_default($_tmp, 0.00)); ?>
" msg="请填写本店价" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>更改备注</strong></td>
      <td><textarea name="remark" style="width:500px; height:80px;"></textarea></td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" id="seg" style="display:none">
  <tr> 
    <td width="10%"><strong>数量区间</strong></td>
    <td width="18%">
      <input type="text" name="quantity1_from" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['0']['1']; ?>
" onkeypress="return NumOnly(event)"/> - <input type="text" name="quantity1_to" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['0']['2']; ?>
" onkeypress="return NumOnly(event)"/>
    </td>
    <td width="6%"><strong>价格</strong></td>
    <td width="10%"><input type="text" name="price1" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['0']['0']; ?>
" onkeypress="return NumOnly(event)"/></td>
    <td>
      示例 数量区间 2-5 &nbsp;&nbsp;价格20.5
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>
      <input type="text" name="quantity2_from" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['1']['1']; ?>
" onkeypress="return NumOnly(event)"/> - <input type="text" name="quantity2_to" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['1']['2']; ?>
" onkeypress="return NumOnly(event)"/>
    </td>
    <td>&nbsp;</td>
    <td><input type="text" name="price2" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['1']['0']; ?>
" onkeypress="return NumOnly(event)"/></td>
    <td>
      　　 数量区间 6-10 价格15
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>
      <input type="text" name="quantity3_from" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['2']['1']; ?>
" onkeypress="return NumOnly(event)"/> - <input type="text" name="quantity3_to" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['2']['2']; ?>
" onkeypress="return NumOnly(event)"/>
    </td>
    <td>&nbsp;</td>
    <td><input type="text" name="price3" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['2']['0']; ?>
" onkeypress="return NumOnly(event)"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>
      <input type="text" name="quantity4_from" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['3']['1']; ?>
" onkeypress="return NumOnly(event)"/> - <input type="text" name="quantity4_to" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['3']['2']; ?>
" onkeypress="return NumOnly(event)"/>
    </td>
    <td>&nbsp;</td>
    <td><input type="text" name="price4" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['3']['0']; ?>
" onkeypress="return NumOnly(event)"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>
      <input type="text" name="quantity5_from" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['4']['1']; ?>
" onkeypress="return NumOnly(event)"/> - <input type="text" name="quantity5_to" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['4']['2']; ?>
" onkeypress="return NumOnly(event)"/>
    </td>
    <td>&nbsp;</td>
    <td><input type="text" name="price5" size="2" value="<?php echo $this->_tpl_vars['data']['price_seg']['4']['0']; ?>
" onkeypress="return NumOnly(event)"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="4" align=center>
      * 起始数量必须大于等于2，最后一个数量区间可以只填写起始数量<br>
      * 数量区间必须连续<br>
      * 价格如果是整数，可以不输小数点
    </td>
  </tr>
</table>

<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
jQuery.noConflict();
function NumOnly(e)
{
    var key = window.event ? e.keyCode : e.which;
    return key>=48&&key<=57||key==46||key==8;
}
function check()
{
    if (!confirm('确定要修改吗？')) {
        return false;
    }
}
function calc(){
	var shop_price = parseFloat(document.getElementById('shop_price').value);
	var org_tax_price = parseFloat(document.getElementById('org_tax_price').value);
	var measurement_unit = parseFloat(document.getElementById('measurement_unit').value);
	var tax_rate = parseFloat(document.getElementById('tax_rate').value);
	jQuery.ajax({
		   type: "get",
		   url: "/admin/goods/calc-price",
		   data: 'shop_price='+shop_price+'&org_tax_price='+org_tax_price+'&unit='+measurement_unit+'&tax_rate='+tax_rate,
		   async: false,
		   success: function(data){
			   document.getElementById('tax').value = parseFloat(data).toFixed(2);
			   calc2();
		   },
		   error:function(){
			   alert("呦吼吼~网络有问题~！");
		   }
	});
}
function calc2()
{
	var shop_price = parseFloat(document.getElementById('shop_price').value);
	var tax = parseFloat(document.getElementById('tax').value);
	tax = tax >= 50 ? tax : 0;
	var price = shop_price + tax ;
	document.getElementById('price').value = parseFloat(price).toFixed(2);
}

</script>