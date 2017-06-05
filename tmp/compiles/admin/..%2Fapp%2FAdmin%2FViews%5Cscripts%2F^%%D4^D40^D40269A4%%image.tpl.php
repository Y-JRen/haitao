<?php /* Smarty version 2.6.19, created on 2014-08-12 14:02:51
         compiled from product/image.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'product/image.tpl', 7, false),)), $this); ?>
<form name="upForm" id="upForm" action="<?php echo $this -> callViewHelper('url', array());?>" method="post" enctype="multipart/form-data" target="ifrmSubmit" onsubmit="return dosubmit()">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>标准图片</strong> * </td>
      <td width="88%"><?php if ($this->_tpl_vars['data']['product_img'] != ''): ?>
      <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['product_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
" border="0" width="50"><br>
      <?php endif; ?>
      <input type="file" name="product_img" msg="请上传商品图片" class="required"/></td>
    </tr>
    <!--
    <tr>
      <td width="12%"><strong>商品规格图</strong> * </td>
      <td><?php if ($this->_tpl_vars['data']['product_arr_img'] != ''): ?>
      <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['product_arr_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
" border="0" width="50"><br>
      <?php endif; ?>
      <input type="file" name="product_arr_img" msg="请上传商品图片" class="required"/></td>
    </tr>	
    -->
	<tr>
      <td><strong>细节图片</strong></td>
      <td><?php if (! empty ( $this->_tpl_vars['img_url'] )): ?><ul id="showimgs">
      <?php $_from = $this->_tpl_vars['img_url']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
?>
      <li id="ajax_list<?php echo $this->_tpl_vars['r']['img_id']; ?>
"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['r']['img_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0"><br>
      排序：<input type="text" name="update" value="<?php echo $this->_tpl_vars['r']['img_sort']; ?>
" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate','type'=>'img',)));?>',<?php echo $this->_tpl_vars['r']['img_id']; ?>
,'img_sort',this.value)" style="width:30px"><br>
      <input type="text" name="update" value="<?php echo $this->_tpl_vars['r']['img_desc']; ?>
" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate','type'=>'img',)));?>',<?php echo $this->_tpl_vars['r']['img_id']; ?>
,'img_desc',this.value)" style="width:66px" title="图片描述">
       <a href="javascript:fGo();" onclick="if(confirm('确认要删除吗')){deleteImg('<?php echo $this -> callViewHelper('url', array(array('action'=>'deleteimg',)));?>','<?php echo $this->_tpl_vars['r']['img_id']; ?>
')}" title="删除此图片" >[-]</a></li>
      <?php endforeach; endif; unset($_from); ?>
      </ul><?php endif; ?>
      <div id="img_url">
          <p>
		  <input type="file" name="img_url[]"> 排序：<input type="text" name="img_sort[]" value="0" style="width:30px"> 图片描述 <input type="text" size="20" name="img_desc[]"> <a onclick="addImg(this,'img_url')" href="javascript:fGo();">[ 添加 ]</a></p>
	  </div>
	  </td>
    </tr>
    <!--
    <tr>
      <td><strong>展示图片</strong></td>
      <td><?php if (! empty ( $this->_tpl_vars['img_ext_url'] )): ?><ul id="showimgs">
      <?php $_from = $this->_tpl_vars['img_ext_url']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
?>
      <li id="ajax_list<?php echo $this->_tpl_vars['r']['img_id']; ?>
"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['r']['img_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0"><br>
      排序：<input type="text" name="update" value="<?php echo $this->_tpl_vars['r']['img_sort']; ?>
" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate','type'=>'img',)));?>',<?php echo $this->_tpl_vars['r']['img_id']; ?>
,'img_sort',this.value)" style="width:30px"><br>
      <input type="text" name="update" value="<?php echo $this->_tpl_vars['r']['img_desc']; ?>
" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate','type'=>'img',)));?>',<?php echo $this->_tpl_vars['r']['img_id']; ?>
,'img_desc',this.value)" style="width:66px" title="图片描述">
       <a href="javascript:fGo();" onclick="if(confirm('确认要删除吗')){deleteImg('<?php echo $this -> callViewHelper('url', array(array('action'=>'deleteimg',)));?>','<?php echo $this->_tpl_vars['r']['img_id']; ?>
')}" title="删除此图片" >[-]</a></li>
      <?php endforeach; endif; unset($_from); ?>
      </ul><?php endif; ?>
      <div id="img_ext_url">
          <p>
		  <input type="file" name="img_ext_url[]"> 排序：<input type="text" name="img_ext_sort[]" value="0" style="width:30px"> 图片描述 <input type="text" size="20" name="img_ext_desc[]"> <a onclick="addImg(this,'img_ext_url')" href="javascript:fGo();">[ 添加 ]</a></p>
      </div>
      </td>
    </tr>
    -->
</tbody>
</table>
<?php if ($this->_tpl_vars['data']['p_lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>
<div style="margin:0 auto;padding:10px;">
<input type="submit" name="dosubmit1" id="dosubmit1" value="上传">
</div>
<?php endif; ?>
</form>
<script language="JavaScript">
function dosubmit()
{
	if(confirm('确认上传吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = 'disabled';
		return true;
	}else{
	    return false;
	}
}
function failed()
{
	$('dosubmit1').value = '提交';
	$('dosubmit1').disabled = false;
}
function addImg(obj,div)
{
	var p = document.createElement("p");
	p.innerHTML = obj.parentNode.innerHTML;
	p.innerHTML = p.innerHTML.replace(/(.*)(addImg)(.*)(\[ )(添加)/i, "$1removeImg$3$4删除");
	$(div).appendChild(p);
}
function removeImg(obj,div)
{
    $(div).removeChild(obj.parentNode);
}
function deleteImg(url, id){
    url = filterUrl(url, 'id');
    new Request({
        url: url + '/id/' + id,
        onRequest:loading,
        onSuccess:function(data){
            if (data!='') {
                alertBox.init("strMsg='"+data+"',MS=1250");
            } else {
                $('ajax_list' + id).destroy();
            }
            loadSucess();
        }
    }).send(); 
}
</script>