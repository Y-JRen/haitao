<?php /* Smarty version 2.6.19, created on 2014-08-25 16:52:24
         compiled from brand/brand-region.tpl */ ?>
<div class="title">归属地管理</div>
<form name="searchForm" id="searchForm" action="/admin/brand/brand-region" method="get">
<div class="search">
归属地名称：<input type="text" name="region_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['region_name']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"region-edit",)));?>')">添加归属地</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="40">ID</td>
            <td>归属地名称</td>
			<td>图片</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['region']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['data']['brand_id']; ?>
">
            <td><?php echo $this->_tpl_vars['v']['region_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['v']['region_name']; ?>
</td>
            <td><img src='<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo $this->_tpl_vars['v']['region_imgurl']; ?>
' width=30  height=20 /></td>
	        <td>
				<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"region-edit",'id'=>$this->_tpl_vars['v']['region_id'],)));?>')">编辑</a>
	        </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</di>
