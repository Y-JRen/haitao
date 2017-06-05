<?php /* Smarty version 2.6.19, created on 2014-08-21 11:50:56
         compiled from category/index.tpl */ ?>
<div class="title">分类管理 ->产品分类</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'addcat',)));?>')">添加分类</a> ] 	
		[ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"reflash-cache",)));?>')">生成前台分类缓存</a> ]  
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>排序</td>
            <td>ID</td>
            <td>名称</td>
            <td>分类编码</td>
            <td>排序</td>  
		    <td>子分类</td>       
            <td>状态</td>
			<td>是否显示</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['cat_id']; ?>
">
        <td><input type="text" name="update" size="2" value="<?php echo $this->_tpl_vars['data']['cat_sort']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['cat_id']; ?>
,'cat_sort',this.value)"></td>
        <td><?php echo $this->_tpl_vars['data']['cat_id']; ?>
</td>
        <td style="padding-left:<?php echo $this->_tpl_vars['data']['step']*20; ?>
px"><?php echo $this->_tpl_vars['data']['depth']; ?>
<input type="text" name="update" size="20" value="<?php echo $this->_tpl_vars['data']['cat_name']; ?>
"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['cat_id']; ?>
,'cat_name',this.value)"></td>
       <td><?php echo $this->_tpl_vars['data']['cat_sn']; ?>
</td>
       <td><input type="text" size=3 value="<?php echo $this->_tpl_vars['data']['cat_sort']; ?>
" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['cat_id']; ?>
,'cat_sort',this.value)"/></td>
	   <td>
		 <?php if ($this->_tpl_vars['data']['parent_id'] == '0'): ?> <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'addcat','pid'=>$this->_tpl_vars['data']['cat_id'],)));?>')">添加子分类</a>  <?php endif; ?>
        </td>
        <td id="ajax_status<?php echo $this->_tpl_vars['data']['cat_id']; ?>
"><?php echo $this->_tpl_vars['data']['status']; ?>
</td>
	   <td id="ajax_display<?php echo $this->_tpl_vars['data']['cat_id']; ?>
"><?php echo $this->_tpl_vars['data']['display']; ?>
</td>
        <td>
		  <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'editcat','id'=>$this->_tpl_vars['data']['cat_id'],),""));?>')">编辑</a>		
		  <?php if ($this->_tpl_vars['data']['parent_id'] != '0'): ?><a href="javascript:fGo()" onclick="openDiv('/admin/category/sel-attr/cat_id/<?php echo $this->_tpl_vars['data']['cat_id']; ?>
','ajax','选择产品属性',750,400);">属性</a><?php endif; ?>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>