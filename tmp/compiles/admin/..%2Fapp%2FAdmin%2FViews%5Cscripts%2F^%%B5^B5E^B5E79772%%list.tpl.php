<?php /* Smarty version 2.6.19, created on 2014-09-01 14:37:22
         compiled from payment/list.tpl */ ?>
<form id="searchForm" action="/admin/payment/list">
<div class="search">
启用状态：<select name="status">
<option value="" selected>请选择</option>
<option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>未启用</option>
<option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>启用</option>
</select>
支付方式：<input type="text" name="name" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['name']; ?>
"/>
支付代码：<input type="text" name="pay_type" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['pay_type']; ?>
"/>
支付方式类型：<select id="is_bank"   name="is_bank">
     <option value="" selected >请选择</option>
        <option value="0"  <?php if ($this->_tpl_vars['param']['is_bank'] == '0'): ?> selected="selected" <?php endif; ?> >支付网关</option>
        <option value="1"  <?php if ($this->_tpl_vars['param']['is_bank'] == '1'): ?> selected="selected" <?php endif; ?> >支付网关银行</option>
        <option value="2"  <?php if ($this->_tpl_vars['param']['is_bank'] == '2'): ?> selected="selected" <?php endif; ?> >银行直连</option>
    </select>  
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="title">支付方式管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'addform',)));?>')">添加支付方式</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>排序</td>
            <td>支付方式类型</td>
            <td>支付方式名称</td>
            <td>支付编码</td>
            <td>支付费率</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['item']['id']; ?>
">
        <td> <?php echo $this->_tpl_vars['item']['id']; ?>
</td>
        <td><input type="text" name="update" size="4" value="<?php echo $this->_tpl_vars['item']['sort']; ?>
" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['item']['id']; ?>
,'sort',this.value)"></td>
       <td>  
            <?php if ($this->_tpl_vars['item']['is_bank'] == 0): ?> 支付网关
            <?php elseif ($this->_tpl_vars['item']['is_bank'] == 1): ?> 支付网关银行
            <?php elseif ($this->_tpl_vars['item']['is_bank'] == 2): ?> 银行直连 <?php endif; ?>
       </td>
        <td><input type="text" name="update" size="30" value="<?php echo $this->_tpl_vars['item']['name']; ?>
"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['item']['id']; ?>
,'name',this.value)"></td>
        <td>  <?php echo $this->_tpl_vars['item']['pay_type']; ?>
</td>
        <td><input type="text" name="update" size="30" value="<?php echo $this->_tpl_vars['item']['fee']; ?>
" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['item']['id']; ?>
,'fee',this.value)"></td>
        <td><?php if ($this->_tpl_vars['item']['status'] == 1): ?>未启用<?php else: ?>启用<?php endif; ?></td>
        <td>
		<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'editform','id'=>$this->_tpl_vars['item']['id'],)));?>')">编辑</a> | 
		<a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'del',)));?>','<?php echo $this->_tpl_vars['item']['id']; ?>
','<?php echo $this -> callViewHelper('url', array(array('action'=>'list',)));?>')">删除</a>
	</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>