<?php /* Smarty version 2.6.19, created on 2014-08-12 14:01:34
         compiled from admin/index.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<form name="searchForm" id="searchForm">
<div class="search">
<select id="group_id" class="required" msg="请选择管理员组" name="group_id">
<option value="">选择管理员组</option>
<?php $_from = $this->_tpl_vars['groupIds']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['group']):
?> 
<option value="<?php echo $this->_tpl_vars['key']; ?>
" label="<?php echo $this->_tpl_vars['group']; ?>
" <?php if ($this->_tpl_vars['param']['group_id'] == $this->_tpl_vars['key']): ?> selected="selected"  <?php endif; ?> ><?php echo $this->_tpl_vars['group']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select> 
名称：<input type="text" name="admin_name" size="12" maxLength="50" value="<?php echo $this->_tpl_vars['param']['admin_name']; ?>
"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
</div>
</div>
</form>
<?php endif; ?>
<div id="ajax_search">
<div class="title">管理员管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加管理员</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>管理员名称</td>
            <td>真实姓名</td>
            <td>创建者</td>
            <td>管理员组</td>
            <td>最后登录时间</td>
            <td>最后登录IP</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['adminList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['admin']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['admin']['admin_id']; ?>
">
            <td><?php echo $this->_tpl_vars['admin']['admin_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['admin']['admin_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['admin']['real_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['admin']['add_admin']; ?>
</td>
            <td><?php echo $this->_tpl_vars['admin']['group_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['admin']['last_login']; ?>
</td>
            <td><?php echo $this->_tpl_vars['admin']['last_login_ip']; ?>
</td>
            <td id="ajax_status<?php echo $this->_tpl_vars['admin']['admin_id']; ?>
"><?php echo $this->_tpl_vars['admin']['status']; ?>
</td>
            <td>
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['admin']['admin_id'],)));?>')">编辑</a> | 
                <a href="javascript:fGo()" onclick="delAdmin('<?php echo $this->_tpl_vars['admin']['admin_id']; ?>
')">删除</a>
            </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>

<script type="text/javascript">
function delAdmin(id){
	if(confirm("确认要对该管理员做删除操作吗？")){
		id=parseInt(id);
		if(id<1){alert('参数错误！');return;}
		new Request({
			url:'/admin/admin/delete/id/'+id,
			method:'get',
			onSuccess:function(data){
				if(data=='ok'){
					alert("操作成功");
					$('ajax_list'+id).destroy();
				}else{
					alert(data+"操作失败，请稍后重试");
				}
			},
			onFailure:function(){
				alert("网路繁忙，请稍后重试");
			}
		}).send();
	}
}
</script>