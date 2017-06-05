<?php /* Smarty version 2.6.19, created on 2014-12-30 15:50:43
         compiled from menu/edit.tpl */ ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post">
<input type="hidden" name="privilege" id="privilege" value="<?php echo $this->_tpl_vars['data']['privilege']; ?>
"/>
<div class="title"><?php if ($this->_tpl_vars['action'] == 'edit'): ?>编辑菜单<?php else: ?>添加菜单<?php endif; ?></div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="10%"><strong>上级菜单</strong> * </td>
      <td>
      <input name="old_parent_id" type="hidden" value="<?php echo $this->_tpl_vars['data']['parent_id']; ?>
">
      <input name="old_menu_path" type="hidden" value="<?php echo $this->_tpl_vars['data']['menu_path']; ?>
">
      <input name="parent_id" id="parent_id" type="hidden" value="<?php echo $this->_tpl_vars['data']['parent_id']; ?>
">
      <input name="menu_path" id="menu_path" type="hidden" value="<?php echo $this->_tpl_vars['data']['menu_path']; ?>
">
      <span id="parent_title" style="color:red"><?php echo $this->_tpl_vars['data']['parent_title']; ?>
</span>
      <span id="ajax_sel_child"></span> <a href="javascript:fGo()" onclick="ajax_get_child('<?php echo $this -> callViewHelper('url', array(array('action'=>'getchild',)));?>',0);">重选</a>
</td>
    </tr>
    <tr> 
      <td><strong>菜单名称</strong> * </td>
      <td><input type="text" name="menu_title" size="30" value="<?php echo $this->_tpl_vars['data']['menu_title']; ?>
" msg="请填写菜单名称" class="required"></td>
    </tr>
    <tr> 
      <td><strong>是否展开</strong> * </td>
      <td>
	   <input type="radio" name="is_open" value="0" <?php if ($this->_tpl_vars['data']['is_open'] == 0): ?>checked<?php endif; ?>/> 是
	   <input type="radio" name="is_open" value="1" <?php if ($this->_tpl_vars['data']['is_open'] == 1): ?>checked<?php endif; ?>/> 否
	  </td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="menu_status" value="0" <?php if ($this->_tpl_vars['data']['menu_status'] == 0): ?>checked<?php endif; ?>/> 是
	   <input type="radio" name="menu_status" value="1" <?php if ($this->_tpl_vars['data']['menu_status'] == 1): ?>checked<?php endif; ?>/> 否
	  </td>
    </tr>
    <tr> 
      <td><strong>URL</strong></td>
      <td><input name="url" type="text" size="50" size="4" value="<?php echo $this->_tpl_vars['data']['url']; ?>
"></td>
    </tr>
	<tr> 
      <td><strong>权限列表</strong></td>
      <td><div class="tree_div" id="treeboxbox_tree" style="padding: 5px; width:390px; height: 400px; float:left; background-color:#f5f5f5; border:1px solid Silver; overflow:auto;"><img src='/haitaoadmin/images/loading.gif' alt='loading'> 正在加载，请稍候……</div></td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
loadCss('/scripts/dhtmlxSuite/dhtmlxTree/dhtmlxtree.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxTree/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxTree/dhtmlxtree.js,/scripts/dhtmlxSuite/dhtmlxTree/ext/dhtmlxtree_json.js', checkLoaded, 'treeboxbox_tree');
function checkLoaded(div) {
    $(div).set('html', '');
	var tree = new dhtmlXTreeObject(div, '100%', '100%', 0);
    tree.setImagePath('/scripts/dhtmlxSuite/dhtmlxTree/imgs/csh_bluebooks/');
    tree.enableTreeImages(false);
    tree.enableHighlighting(true);
    tree.enableCheckBoxes(true);
    tree.enableThreeStateCheckboxes(true);
    tree.setOnCheckHandler(treeboxbox_tree_check);
    tree.loadJSONObject(<?php echo $this->_tpl_vars['jsonDatabasePrivilege']; ?>
);
    function treeboxbox_tree_check(id, state)
    {
        $('privilege').value = tree.getAllChecked();
    }
}

function ajax_get_child(url,id){
    url = filterUrl(url, 'pid');
    new Request({
        url: url + '/pid/' + id,
        onRequest: loading,
        onSuccess:function(data){
	    $('parent_id').value=id;
	    $('menu_path').value += id + ',';
	    if(id==0){
	    	$('ajax_sel_child').innerHTML='';
	    	$('menu_path').value=',';
	    	$('parent_title').style.display='none';
	    }
        var span = document.createElement("span");
        span.innerHTML=data;
        $('ajax_sel_child').appendChild(span);
        loadSucess();
        }
    }).send();
}
<?php if ($this->_tpl_vars['data']['parent_id'] == 0 && $this->_tpl_vars['action'] == 'add'): ?>
ajax_get_child('<?php echo $this -> callViewHelper('url', array(array('action'=>'getchild',)));?>',0);
<?php endif; ?>
</script>