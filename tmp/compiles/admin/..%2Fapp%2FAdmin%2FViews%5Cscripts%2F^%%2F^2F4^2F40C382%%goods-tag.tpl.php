<?php /* Smarty version 2.6.19, created on 2014-08-18 15:10:25
         compiled from goods/goods-tag.tpl */ ?>
<div class="title">商品标签管理   [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"add-tag",)));?>')">添加新标签</a> ]</div>
<form name="searchForm" id="searchForm" action="/admin/goods/goods-tag">
<div class="search">
标题：<input type="text" name="title" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['title']; ?>
"/>
标签名：<input type="text" name="tag" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['tag']; ?>
"/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>标签ID</td>
            <td>标题</td>
            <td>标签名</td>
			<td>类别</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['taglist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
        <td><?php echo $this->_tpl_vars['data']['tag_id']; ?>
</td>
        <td>  
		<input type="text" name="update" size="30" value="<?php echo $this->_tpl_vars['data']['title']; ?>
"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate','type'=>'tag',),""));?>',<?php echo $this->_tpl_vars['data']['tag_id']; ?>
,'title',this.value)">
		 </td>
        <td>
		<input type="text" name="update" size="30" value="<?php echo $this->_tpl_vars['data']['tag']; ?>
"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate','type'=>'tag',),""));?>',<?php echo $this->_tpl_vars['data']['tag_id']; ?>
,'tag',this.value)">
		</td>
	   <td><?php echo $this->_tpl_vars['data']['type']; ?>
</td>
        <td>
		<?php if ($this->_tpl_vars['data']['type'] == 'brand'): ?>
		 <a href="javascript:fGo()" onclick="G('/admin/goods/tag/type/<?php echo $this->_tpl_vars['data']['type']; ?>
/id/<?php echo $this->_tpl_vars['data']['tag_id']; ?>
')">编辑添加品牌</a>  
		<?php elseif ($this->_tpl_vars['data']['type'] == 'goods'): ?>
		<a href="javascript:fGo()" onclick="G('/admin/goods/tag/type/<?php echo $this->_tpl_vars['data']['type']; ?>
/id/<?php echo $this->_tpl_vars['data']['tag_id']; ?>
')">编辑添加单品</a>
		<?php elseif ($this->_tpl_vars['data']['type'] == 'groupgoods'): ?>
		<a href="javascript:fGo()" onclick="G('/admin/goods/tag/type/<?php echo $this->_tpl_vars['data']['type']; ?>
/id/<?php echo $this->_tpl_vars['data']['tag_id']; ?>
')">编辑添加组合商品</a>
		<?php endif; ?>
		<a href="javascript:fGo()" onclick="window.open('/shop/goods/label/type/<?php echo $this->_tpl_vars['data']['type']; ?>
/id/<?php echo $this->_tpl_vars['data']['tag_id']; ?>
')">查看</a>
        </td>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>