<?php /* Smarty version 2.6.19, created on 2014-08-18 13:39:29
         compiled from ad/adboard.tpl */ ?>

<div id="ajax_search">
	<div class="title">广告管理</div>
	<div class="content">
		<div class="sub_title">[ <a onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"add-adboard",)));?>')" href="javascript:fGo()">添加广告位</a> ]</div>
		<table cellspacing="0" cellpadding="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
                <td>广告位名称</td> 						
				<td>广告类型</td>
				<td>广告位尺寸	</td>
				<td>广告位描述</td>
				<td>状态</td>
				<td>管理操作</td>
			</tr>
		</thead>
		<tbody>
		 <?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		   <tr id="ajax_list26">
			<td><?php echo $this->_tpl_vars['item']['id']; ?>
</td>
             <td><?php echo $this->_tpl_vars['item']['name']; ?>
</td>
            
			<td><?php echo $this->_tpl_vars['item']['tpl']; ?>
</td>
			<td><?php echo $this->_tpl_vars['item']['width']; ?>
X<?php echo $this->_tpl_vars['item']['height']; ?>
</td>
			<td><?php echo $this->_tpl_vars['item']['description']; ?>
</td>
			<td><?php if ($this->_tpl_vars['item']['status'] == 1): ?>开启<?php else: ?>关闭<?php endif; ?></td>
			<td>
			<a onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"edit-adboard",'id'=>$this->_tpl_vars['item']['id'],)));?>')" href="javascript:fGo()">编辑</a> |
          	<a onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>"del-adboard",)));?>','<?php echo $this->_tpl_vars['item']['id']; ?>
','<?php echo $this -> callViewHelper('url', array(array('action'=>'adboard',)));?>')" href="javascript:fGo()">删除</a>
		</td>
		</tr>	
		<?php endforeach; endif; unset($_from); ?>		
		</tbody>
		</table>
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>	
</div>