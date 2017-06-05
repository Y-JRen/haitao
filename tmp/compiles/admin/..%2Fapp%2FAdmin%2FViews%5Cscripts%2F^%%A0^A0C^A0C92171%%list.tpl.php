<?php /* Smarty version 2.6.19, created on 2014-07-23 10:53:40
         compiled from article/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'article/list.tpl', 65, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<div class="search">
	<form id="searchForm">
		<div style="clear:both; padding-top:5px">
		分类:
		<select name="cat_id">
			<option value="">请选择...</option>
			<?php $_from = $this->_tpl_vars['catTree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cat_id'] => $this->_tpl_vars['item']):
?>
			<?php if ($this->_tpl_vars['item']['leaf']): ?>
			<option value=<?php echo $this->_tpl_vars['cat_id']; ?>
 style="padding-left:<?php echo $this->_tpl_vars['item']['step']*20-20; ?>
px" <?php if ($this->_tpl_vars['param']['cat_id'] == $this->_tpl_vars['cat_id']): ?>selected="selected"<?php endif; ?>>
			<?php echo $this->_tpl_vars['item']['cat_name']; ?>
（<?php echo $this->_tpl_vars['item']['num']; ?>
）
			</option>
			<?php else: ?>
			<optgroup label='<?php echo $this->_tpl_vars['item']['cat_name']; ?>
' style="padding-left:<?php echo $this->_tpl_vars['item']['step']*20-20; ?>
px"></optgroup>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>		
		</select>
		标题：<input type="text" name="title" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['title']; ?>
">
        
        是否显示：<select name="is_view">
          <option value="null">请选择</option>
          <option value="1" <?php if ($this->_tpl_vars['param']['is_view'] == '1'): ?>selected<?php endif; ?>>显示</option>
          <option value="0" <?php if ($this->_tpl_vars['param']['is_view'] == '0'): ?>selected<?php endif; ?>>不显示</option>
        </select>      
        是否推荐：<select name="is_hot">
          <option value="null">请选择</option>
          <option value="1" <?php if ($this->_tpl_vars['param']['is_hot'] == '1'): ?>selected<?php endif; ?>>是</option>
          <option value="0" <?php if ($this->_tpl_vars['param']['is_hot'] == '0'): ?>selected<?php endif; ?>>否</option>
        </select>   
        
		<input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
		</div>	
	</form>
</div>
<div id="ajax_search">
<?php endif; ?>
	<div class="title">文章管理</div>
	<div class="content">
		<div class="sub_title">[ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'addform',)));?>')">添加文章</a> ][ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'footer',)));?>')">生成底部菜单列表缓存</a> ]</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
                <td>排序</td>
				<td>分类</td>
				<td>标题</td>
				<td>添加日期</td>
				<td>是否显示</td>
				<td>是否推荐置顶</td>
				<td>操作</td>
			</tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		<tr id="ajax_list<?php echo $this->_tpl_vars['item']['article_id']; ?>
">
			<td><?php echo $this->_tpl_vars['item']['article_id']; ?>
</td>
                    <td><input value='<?php echo $this->_tpl_vars['item']['sort']; ?>
' type='text' style='width:30px' 
	onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdatearticle',)));?>',<?php echo $this->_tpl_vars['item']['article_id']; ?>
,'sort',this.value)"></td>
            
			<td><?php echo $this->_tpl_vars['item']['cat_name']; ?>
</td>
			<td>
				<input type="text" name="update" size="45" value="<?php echo $this->_tpl_vars['item']['title']; ?>
"  
				onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdatearticle',)));?>',<?php echo $this->_tpl_vars['item']['article_id']; ?>
,'title',this.value)">
			</td>
			<td ><?php if ($this->_tpl_vars['item']['add_time']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
<?php else: ?><?php endif; ?></td>
			<td  >  <?php if ($this->_tpl_vars['item']['is_view'] == 1): ?>  <font color="#009966">显示</font> <?php elseif ($this->_tpl_vars['item']['is_view'] == 2): ?> <font color="#FF3300">不显示 </font> <?php endif; ?></td>
			<td><?php if ($this->_tpl_vars['item']['is_hot'] == 1): ?><font style="color:#009966; cursor:pointer;" onclick="changHot(<?php echo $this->_tpl_vars['item']['article_id']; ?>
, 0);">是</font><?php elseif ($this->_tpl_vars['item']['is_hot'] == 0): ?><font style="color:#FF3300; cursor:pointer;" onclick="changHot(<?php echo $this->_tpl_vars['item']['article_id']; ?>
, 1);">否</font><?php endif; ?></td>
			<td>
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'editform','id'=>$this->_tpl_vars['item']['article_id'],)));?>')">编辑</a>||
            <a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'linkgoods','id'=>$this->_tpl_vars['item']['article_id'],)));?>','ajax','查看<?php echo $this->_tpl_vars['item']['title']; ?>
关联商品',750,400)">编辑关联商品</a>||
			<a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'del',)));?>','<?php echo $this->_tpl_vars['item']['article_id']; ?>
','<?php echo $this -> callViewHelper('url', array(array('action'=>'list',)));?>')">删除</a>
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
function changHot(articleID, st){
	articleID = parseInt(articleID);
	st = parseInt(st);
	if(st!=1 && st!=0){ st = 0; }
	new Request({
		url:'/admin/article/is-hot/article_id/'+articleID+'/st/'+st,
		onSuccess:function(msg){
			if(msg == 'ok'){
				location.reload();
			}else{
				alert(msg);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
</script>