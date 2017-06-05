<?php /* Smarty version 2.6.19, created on 2014-12-17 14:43:26
         compiled from menu/index.tpl */ ?>
<div class="title">菜单管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加菜单</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="60">排序</td>
            <td width="60">ID</td>
            <td width="150">菜单名称</td>
            <td width="450">地址</td>
            <td width="80">是否展开</td>
            <td width="60">状态</td>
            <td width="250">操作</td>
	        <td>
	        </td>
       </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['menu_id']; ?>
">
        <td><input type="text" name="update" size="2" value="<?php echo $this->_tpl_vars['data']['menu_sort']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['menu_id']; ?>
,'menu_sort',this.value)"></td>
        <td><?php echo $this->_tpl_vars['data']['menu_id']; ?>
</td>
        <td style="padding-left:<?php echo $this->_tpl_vars['data']['step']*20; ?>
px"><?php echo $this->_tpl_vars['data']['depth']; ?>
<input type="text" name="update" size="18" value="<?php echo $this->_tpl_vars['data']['menu_title']; ?>
"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['menu_id']; ?>
,'menu_title',this.value)"></td>
        <td><?php echo $this->_tpl_vars['data']['url']; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['is_open']): ?>关闭<?php else: ?>展开<?php endif; ?></td>
        <td id="ajax_status<?php echo $this->_tpl_vars['data']['menu_id']; ?>
"><?php echo $this->_tpl_vars['data']['status']; ?>
</td>
        <td>
			<?php if ($this->_tpl_vars['data']['parent_id'] == 0): ?>
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('pid'=>$this->_tpl_vars['data']['menu_id'],)));?>')">管理子菜单</a> | 
			<?php endif; ?>
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add','pid'=>$this->_tpl_vars['data']['menu_id'],)));?>')">添加子菜单</a> | 
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['data']['menu_id'],)));?>')">编辑</a>
             <a href="javascript:fGo()" onclick="delMenu(<?php echo $this->_tpl_vars['data']['menu_id']; ?>
)">删除</a>
        </td>
        <td>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>

<script type="text/javascript">
function delMenu(id){
	if(confirm("确认要对该菜单做删除操作吗？")){
		id=parseInt(id);
		if(id<1){alert('参数错误！');return;}
		new Request({
			url:'/admin/menu/delete/id/'+id,
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