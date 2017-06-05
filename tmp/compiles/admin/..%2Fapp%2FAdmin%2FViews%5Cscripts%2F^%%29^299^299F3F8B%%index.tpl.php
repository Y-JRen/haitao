<?php /* Smarty version 2.6.19, created on 2014-08-13 09:31:42
         compiled from supplier/index.tpl */ ?>
<div class="title">供货商管理</div>
<div class="search">
  <form id="searchForm" method="get">
  供货商名称：<input type="text" name="supplier_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['supplier_name']; ?>
">
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search',)));?>','ajax_search')"/>
  </form>
</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加供货商</a> ]
        [ <a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出信息</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="25">ID</td>
            <td width="100">供货商名称</td>
            <td>产品数量</td>
			<td>公司名称</td>
            <td>联系电话</td>
            <td>联系人</td>
            <td width="100">添加时间</td>
            <td width="30">状态</td>
            <td width="100">操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['data']['supplier_id']; ?>
">
            <td><?php echo $this->_tpl_vars['data']['supplier_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['data']['supplier_name']; ?>
</td>
            <td> 
              <font color="#FF3300"><?php echo $this->_tpl_vars['data']['goods_num']; ?>
</font></a>
            </td>
			<td><?php echo $this->_tpl_vars['data']['company']; ?>
 </td>
            <td><input type="text" name="update" size="30" value="<?php echo $this->_tpl_vars['data']['tel']; ?>
" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['supplier_id']; ?>
,'tel',this.value)"></td>
            <td><input type="text" name="update" size="10" value="<?php echo $this->_tpl_vars['data']['contact']; ?>
" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['supplier_id']; ?>
,'contact',this.value)"></td>
            <td><?php echo $this->_tpl_vars['data']['add_time']; ?>
</td>
            <td id="ajax_status<?php echo $this->_tpl_vars['data']['supplier_id']; ?>
"><?php echo $this->_tpl_vars['data']['status']; ?>
</td>
	        <td>
				<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['data']['supplier_id'],)));?>')">编辑</a> 
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'goods','id'=>$this->_tpl_vars['data']['supplier_id'],)));?>')">供应产品</a> 
	        </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>