<?php /* Smarty version 2.6.19, created on 2014-07-23 10:53:13
         compiled from article/listcat.tpl */ ?>
<div class="title">文章分类管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'addcatform',)));?>')">添加分类</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>排序</td>
            <td>分类名</td>
            <td>文章数</td>
            <td>添加子分类</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['catTree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cat_id'] => $this->_tpl_vars['item']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['item']['article_id']; ?>
">
        <td><?php echo $this->_tpl_vars['item']['cat_id']; ?>
</td>
        <td><input value='<?php echo $this->_tpl_vars['item']['sort']; ?>
' type='text' style='width:30px' 
	onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdatecat',)));?>',<?php echo $this->_tpl_vars['cat_id']; ?>
,'sort',this.value)"></td>
        <td style="padding-left:<?php echo $this->_tpl_vars['item']['step']*30; ?>
px;"><input value='<?php echo $this->_tpl_vars['item']['cat_name']; ?>
' type='text'
	onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdatecat',)));?>',<?php echo $this->_tpl_vars['cat_id']; ?>
,'cat_name',this.value)"></td>
        <td><?php echo $this->_tpl_vars['item']['num']; ?>
</td>
        <td><?php if (! $this->_tpl_vars['item']['num']): ?>
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'addcatform','id'=>$this->_tpl_vars['cat_id'],)));?>')">添加子分类</a>
			<?php endif; ?>
    </td>
        <td>
		<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'editcatform','id'=>$this->_tpl_vars['cat_id'],)));?>')">编辑</a> ||
		<a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delcat',)));?>','<?php echo $this->_tpl_vars['cat_id']; ?>
','<?php echo $this -> callViewHelper('url', array(array('action'=>'listcat',)));?>')">删除</a>||
        <?php if ($this->_tpl_vars['item']['num']): ?>
        <a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>"link-cat-goods",'id'=>$this->_tpl_vars['item']['cat_id'],)));?>','ajax','查看<?php echo $this->_tpl_vars['item']['title']; ?>
关联商品',750,400)">编辑关联商品</a>||
        <a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>"link-cat-article",'id'=>$this->_tpl_vars['item']['cat_id'],)));?>','ajax','查看<?php echo $this->_tpl_vars['item']['title']; ?>
关联文章',750,400)">编辑关联文章</a>
        <?php endif; ?>
	</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>