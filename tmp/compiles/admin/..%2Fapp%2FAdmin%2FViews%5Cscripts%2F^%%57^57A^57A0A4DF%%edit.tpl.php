<?php /* Smarty version 2.6.19, created on 2015-03-31 13:55:42
         compiled from goods/edit.tpl */ ?>
<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" enctype="multipart/form-data" />
<div class="title" style="height:25px;">
	<ul id="show_tab">
	   <li onclick="show_tab(0)" id="show_tab_nav_0" class="bg_nav_current">基本信息</li>
	   <li onclick="show_tab(1)" id="show_tab_nav_1" class="bg_nav_attr">商品扩展</li>
	   <li onclick="show_tab(2)" id="show_tab_nav_2" class="bg_nav">商品描述</li>
	   <?php if ($this->_tpl_vars['attrInfo']): ?><li onclick="show_tab(3)" id="show_tab_nav_3" class="bg_nav">商品属性</li><?php endif; ?>
	</ul>
</div>
<div class="content">
<div id="show_tab_page_0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>商品编码</strong> * </td>
      <td><input type="text" name="goods_sn" size="10" value="<?php echo $this->_tpl_vars['data']['goods_sn']; ?>
" readonly/></td>
    </tr>
    <tr>
      <td width="12%"><strong>跨境通商品编码</strong>  </td>
      <td><input type="text" name="kjt_sn" size="20" value="<?php echo $this->_tpl_vars['data']['kjt_sn']; ?>
" /></td>
    </tr>
    <tr>
      <td width="12%"><strong>商品名称</strong> * </td>
      <td><input type="text" name="goods_name" size="60" value="<?php echo $this->_tpl_vars['data']['goods_name']; ?>
" msg="请填写商品名称" class="required"/></td>
    </tr>
	<tr>
      <td width="12%"><strong>商品别名</strong>  </td>
      <td><input type="text" name="short_name" size="60" value="<?php echo $this->_tpl_vars['data']['short_name']; ?>
" /></td>
    </tr>	
    <tr>
      <td><strong>限购数量</strong> * </td>
      <td><input type="text" name="limit_number" size="8" value="<?php echo $this->_tpl_vars['data']['limit_number']; ?>
" msg="请填写限购数量" class="required number" /></td>
    </tr>

    <tr>
      <td width="12%"><strong>产地</strong> * </td>
      <td>
      	<select name='region' >
		  	<?php $_from = $this->_tpl_vars['region']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		  		<option value='<?php echo $this->_tpl_vars['v']['region_id']; ?>
' <?php if ($this->_tpl_vars['data']['region'] == $this->_tpl_vars['v']['region_id']): ?>selected=selected<?php endif; ?> ><?php echo $this->_tpl_vars['v']['region_name']; ?>
</option>
		  	<?php endforeach; endif; unset($_from); ?>
		</select>
      </td>
    </tr>
    <tr>
      <td width="12%"><strong>商品分类</strong> * </td>
      <td><?php echo $this->_tpl_vars['data']['view_cat_name']; ?>
</td>
    </tr>	
    <tr>
      <td width="12%"><strong>品牌</strong> * </td>
      <td>
        <select name="brand_id" msg="请选择商品品牌" class="required" >
        <?php $_from = $this->_tpl_vars['brand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['b']):
?>
        <option value="<?php echo $this->_tpl_vars['b']['brand_id']; ?>
" <?php if ($this->_tpl_vars['b']['brand_id'] == $this->_tpl_vars['data']['brand_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['b']['brand_name']; ?>
</option>
        <?php endforeach; endif; unset($_from); ?>
        </select>
      </td>
    </tr>	
	 <tr>
      <td width="12%"><strong>是否是赠品</strong> * </td>
      <td>
        <input type="radio" name="is_gift" value="1" <?php if ($this->_tpl_vars['data']['is_gift']): ?>checked<?php endif; ?>>是
        <input type="radio" name="is_gift" value="0" <?php if (! $this->_tpl_vars['data']['is_gift']): ?>checked<?php endif; ?>>否
      </td>
    </tr>	
	<script type="text/javascript">
	function addCats(){
		var sel = new Element('SELECT',{name:'other_cat_id[]'});
		var selCat = $('other_cat_id');
		for(i = 0; i < selCat.length; i++){
			var opt = new Element("OPTION",{
				text:selCat.options[i].text,
				value:selCat.options[i].value
			});
			sel.appendChild(opt);
		}
		document.getElementById('cats').appendChild(sel);
	}
	</script>

    <tr>
      <td><strong>活动/简要说明：</strong> * </td>
      <td>  
		<textarea name="act_notes" id="act_notes" rows="20" style="width:680px; height:160px;"><?php echo $this->_tpl_vars['data']['act_notes']; ?>
</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="act_notes"]', {
				            filterMode : false,
							allowFileManager : true
						});
			});
		</script>
	  </td>
    </tr>	
    <tr>
      <td><strong>商品ALT说明：</strong> * </td>
      <td>  
	  <input type="text" name="goods_alt" id="goods_alt" size="60" value="<?php echo $this->_tpl_vars['data']['goods_alt']; ?>
"  />
	  </td>
    </tr>
</tbody>
</table>
</div>

<div id="show_tab_page_1" style="display:none;">

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
   <tr>
      <td><strong>meta标题</strong></td>
      <td><input type="text" name="meta_title" size="50" value="<?php echo $this->_tpl_vars['data']['meta_title']; ?>
"></td>
    </tr>
    <tr>
      <td><strong>meta关键字</strong></td>
      <td><input type="text" name="meta_keywords" size="50" value="<?php echo $this->_tpl_vars['data']['meta_keywords']; ?>
"></td>
    </tr>
	<tr>
      <td><strong>meta描述</strong></td>
      <td><textarea name="meta_description" rows="3" cols="39" id="meta_description" style="width:330px; height:45px;"><?php echo $this->_tpl_vars['data']['meta_description']; ?>
</textarea></td>
    </tr>
</tbody>
</table>
</div>
<div id="show_tab_page_2" style="display:none;">
<input type="hidden" name="goods_sn" value="<?php echo $this->_tpl_vars['data']['goods_sn']; ?>
">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>商品特点</strong></td>
      <td> 
	   <textarea name="brief" rows="4" cols="39" id="brief" style="width:350px; height:45px;"><?php echo $this->_tpl_vars['data']['brief']; ?>
</textarea>
	   </td>
    </tr>
	<tr>
      <td><strong>商品说明</strong></td>
      <td>
		<textarea name="description" id="description" rows="20" style="width:680px; height:260px;"><?php echo $this->_tpl_vars['data']['description']; ?>
</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="description"]', {
				            filterMode : false,
							allowFileManager : true
						});
			});
		</script>
	 </td>
    </tr>
</tbody>
</table>
</div>
<?php if ($this->_tpl_vars['attrInfo']): ?>
<div id="show_tab_page_3" style="display:none;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<?php $_from = $this->_tpl_vars['attrInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['mainAttr']):
?>
<tr>
<td><input type="checkbox" id="main_<?php echo $this->_tpl_vars['mainAttr']['attr_id']; ?>
" value="1" onclick="clearAttrCheck('<?php echo $this->_tpl_vars['mainAttr']['attr_id']; ?>
', this.checked)"> <b><?php echo $this->_tpl_vars['mainAttr']['attr_title']; ?>
</b></td>
</tr>
<tr>
<td>
&nbsp;&nbsp;&nbsp;
<?php $_from = $this->_tpl_vars['mainAttr']['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['attr']):
?>
<input type="radio" name="attrID_<?php echo $this->_tpl_vars['mainAttr']['attr_id']; ?>
[]" value="<?php echo $this->_tpl_vars['attr']['attr_id']; ?>
" onclick="$('main_<?php echo $this->_tpl_vars['mainAttr']['attr_id']; ?>
').checked = true">&nbsp;<?php echo $this->_tpl_vars['attr']['attr_title']; ?>
&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<tr>
<td>
  产品名称：<input type="text" name="product_name" id="product_name" size="40">
  规格：<input type="text" name="goods_style" id="goods_style" size="20">
  <input type="button" value="添加产品" onclick="addProduct()">
</td>
</tr>
</table>
<br>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table" id="productTable">
<thead>
<tr>
<td>产品编码</td>
<td>产品名称</td>
<td>产品规格</td>
<td>属性</td>
<td>操作</td>
</tr>
</thead>
<?php if ($this->_tpl_vars['productData']): ?>
<?php $_from = $this->_tpl_vars['productData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
<tr>
<td><?php echo $this->_tpl_vars['product']['product_sn']; ?>
</td>
<td><?php echo $this->_tpl_vars['product']['product_name']; ?>
</td>
<td><?php echo $this->_tpl_vars['product']['goods_style']; ?>
</td>
<td>
  <?php if ($this->_tpl_vars['product']['attrs']): ?>
    <?php $_from = $this->_tpl_vars['product']['attrs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['attr']):
?>
      <?php echo $this->_tpl_vars['attr']; ?>
&nbsp;
    <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
</td>
<td><a href="javascript:void(0);" onclick="openDiv('/admin/goods/attr-edit/product_id/<?php echo $this->_tpl_vars['product']['product_id']; ?>
','ajax','编辑产品属性',600,300);">编辑属性</a></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
</table>
</div>
<?php endif; ?>
</div>
<input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function clearAttrCheck(attrID, checked)
{
    var objs = document.getElementsByName('attrID_' + attrID + '[]');
    for (var i = objs.length - 1; i >= 0; i--) {
        objs[i].checked = checked;
    }
}
function clearAttrCheck1(attrID, checked)
{
    var objs = document.getElementsByName('attrID1_' + attrID + '[]');
    for (var i = objs.length - 1; i >= 0; i--) {
        objs[i].checked = checked;
    }
}

function addProduct()
{
    if ($('product_name').value == '') {
        alert('产品名称不能为空');
        return;
    }
    if ($('goods_style').value == '') {
        alert('产品规格不能为空');
        return;
    }
    
    var attrs = ',';
    <?php $_from = $this->_tpl_vars['attrInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['mainAttr']):
?>
    var objs = document.getElementsByName('attrID_<?php echo $this->_tpl_vars['mainAttr']['attr_id']; ?>
[]');
    for (var i = objs.length - 1; i >= 0; i--) {
        if (objs[i].checked) {
            attrs = attrs + objs[i].value + ',';
        }
    }
    <?php endforeach; endif; unset($_from); ?>
    if (attrs == ',') {
        attrs = '';
    }
    
    new Request({
        url: '/admin/product/add-product/goods_id/<?php echo $this->_tpl_vars['data']['goods_id']; ?>
/product_name/' + $('product_name').value + '/goods_style/' + $('goods_style').value + '/attrs/' + attrs + '/r/' + Math.random(),
        onRequest: loading,
        onSuccess:function(data) {
            if (data.substring(0, 2) == 'ok') {
                var array = data.split(']::[');
                var obj = $('productTable');
	            var tr = obj.insertRow(obj.rows.length);
                for (var j = 0;j <= 4; j++) {
            	    tr.insertCell(j);
            	}
            	tr.cells[0].innerHTML = array[1];
            	tr.cells[1].innerHTML = $('product_name').value;
            	tr.cells[2].innerHTML = $('goods_style').value;
            	tr.cells[3].innerHTML = array[2];
                tr.cells[4].innerHTML = "<a href=\"javascript:void(0);\" onclick=\"openDiv('/admin/goods/attr-edit/product_id/" + array[3] + "','ajax','编辑产品属性',600,300);\">编辑属性</a>";
                
                alert('添加成功');
            }
            else if (data == 'goods not found') {
                alert('找不到商品');
            }
            else if (data == 'same product name') {
                alert('产品名称重复');
            }
            else if (data == 'same product') {
                alert('已经存在相同的产品属性');
            }
            else if (data == 'add product error') {
                alert('添加产品失败');
            }
        }
    }).send();
}
show_tab(<?php echo $this->_tpl_vars['tab']; ?>
);
</script>