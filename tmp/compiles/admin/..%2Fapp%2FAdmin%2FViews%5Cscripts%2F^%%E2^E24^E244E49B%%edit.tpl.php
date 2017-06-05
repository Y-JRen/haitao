<?php /* Smarty version 2.6.19, created on 2014-08-13 09:29:23
         compiled from product/edit.tpl */ ?>
<form name="myForm" id="myForm1" action="<?php echo $this -> callViewHelper('url', array());?>" method="post" target="ifrmSubmit">
<input type="hidden" name="old_value" value='<?php echo $this->_tpl_vars['old_value']; ?>
'>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr height="30px">
      <td width="18%"><strong>产品名称</strong> * </td>
      <td><input type="text" name="product_name" id="product_name" value="<?php echo $this->_tpl_vars['data']['product_name']; ?>
" msg="请填写产品名称"   size="30"  class="required"></td>
      <td width="18%"><strong>类别</strong> * </td>
      <td><?php echo $this->_tpl_vars['data']['cat_name']; ?>
 <?php if ($this->_tpl_vars['action'] == 'add'): ?><?php echo $this->_tpl_vars['catSelect']; ?>
<?php endif; ?></td>
    </tr>
    <tr>
      <td><strong>产品编码</strong> * </td>
      <td>
        <?php if ($this->_tpl_vars['action'] == 'add'): ?>
        <input type="text" name="product_sn" id="product_sn"  value="<?php echo $this->_tpl_vars['data']['product_sn']; ?>
" maxlength="7" readonly>
        <?php else: ?>
        <?php echo $this->_tpl_vars['data']['product_sn']; ?>

        <input type="hidden" name="product_sn" value="<?php echo $this->_tpl_vars['data']['product_sn']; ?>
">
        <?php endif; ?>
      </td>
      <td><strong>状态</strong> * </td>
      <td>
	  <?php if ($this->_tpl_vars['data']['p_status'] == '0'): ?>正常   <?php else: ?> 冻结 <?php endif; ?>
      </td>
    </tr>
    <tr>
      <td><strong>产品规格</strong> * </td>
      <td>
        <input type="text" name="goods_style" id="goods_style" size="30" value="<?php echo $this->_tpl_vars['data']['goods_style']; ?>
" msg="请填写商品规格" class="required" />
      </td>
 <td><strong>长度(cm)</strong></td>
      <td><input type="text" name="info[p_length]" size="20" value="<?php echo $this->_tpl_vars['data']['p_length']; ?>
"/></td>
    <tr>
      <td><strong>宽度(cm)</strong></td>
      <td><input type="text" name="info[p_width]" size="20" value="<?php echo $this->_tpl_vars['data']['p_width']; ?>
"/></td>
            <td><strong>高度(cm)</strong></td>
      <td><input type="text" name="info[p_height]" size="20" value="<?php echo $this->_tpl_vars['data']['p_height']; ?>
"/></td>
    </tr>
    <tr>
      <td><strong>重量(kg)</strong></td>
      <td><input type="text" name="info[p_weight]" size="20" value="<?php echo $this->_tpl_vars['data']['p_weight']; ?>
"/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
     <tr>
      <td><strong>虚拟商品</strong></td>
      <td>
        <input type="radio" name="is_vitual" value="1" <?php if ($this->_tpl_vars['data']['is_vitual']): ?>checked<?php endif; ?>/>是
        <input type="radio" name="is_vitual" value="0" <?php if (! $this->_tpl_vars['data']['is_vitual']): ?>checked<?php endif; ?>/>否
      </td>
      <td><strong>礼品卡</strong></td>
      <td>
        <input type="radio" name="is_gift_card" value="1" <?php if ($this->_tpl_vars['data']['is_gift_card']): ?>checked<?php endif; ?>/>是
        <input type="radio" name="is_gift_card" value="0" <?php if (! $this->_tpl_vars['data']['is_gift_card']): ?>checked<?php endif; ?>/>否
      </td>
    </tr>
    <tr>
      <td><strong>国际商品编码(EAN)</strong></td>
      <td><input type="text" name="info[ean_barcode]" size="35" value="<?php echo $this->_tpl_vars['data']['ean_barcode']; ?>
"   maxlength="13" /></td>
      
	  <td><strong>单位</strong> * </td>
      <td>
        <select name="goods_units" msg="请选择商品单位" class="required" >
        <?php $_from = $this->_tpl_vars['units']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
        <option value="<?php echo $this->_tpl_vars['item']; ?>
" style="padding-left:5px" <?php if ($this->_tpl_vars['data']['goods_units'] == $this->_tpl_vars['item']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
	    <?php endforeach; endif; unset($_from); ?>
        </select>
      </td>
	  
   </tr>
  <?php if ($this->_tpl_vars['action'] == 'add'): ?>
    <tr>
      <td><strong>建议销售价</strong></td>
      <td><input type="text" name="suggest_price" size="8" /></td>
      <td><strong>发票税率</strong></td>
      <td><input type="text" name="invoice_tax_rate" size="8" /></td>
    </tr>
    <tr>
      <td><strong>(采购)成本价</strong></td>
      <td><input type="text" name="purchase_cost" size="8" /></td>
      <td><strong>未税成本价</strong></td>
      <td><input type="text" name="cost_tax" size="8" /></td>
    </tr>
  <?php endif; ?>
</tbody>
</table>
<div style="margin:0 auto;padding:10px;">
<?php if ($this->_tpl_vars['data']['p_lock_name'] == $this->_tpl_vars['auth']['admin_name'] || $this->_tpl_vars['action'] == 'add'): ?>
<input type="submit" value="保存"> <input type="button" onclick="alertBox.closeDiv();" value="关闭">
<?php else: ?>
<input type="button" onclick="alertBox.closeDiv();" value="返回">
<?php endif; ?>
</div>
</form>

<script>
function changeCat(value)
{
    new Request({
        url: '/admin/product/get-product--prefix-sn/catID/' + value + '/r/' + Math.random(),
        onRequest: loading,
        onSuccess:function(data) {
            if (data == 'error') {
                alert('必须选择底层分类！');
            }
            else {
                $('product_sn').value = data;
            }
        }
    }).send();

}
</script>