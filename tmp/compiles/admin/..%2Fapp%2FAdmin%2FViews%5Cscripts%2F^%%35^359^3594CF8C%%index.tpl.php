<?php /* Smarty version 2.6.19, created on 2014-08-04 11:10:07
         compiled from ad/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'ad/index.tpl', 12, false),array('modifier', 'date_format', 'ad/index.tpl', 57, false),)), $this); ?>
 <script src="/scripts/my97/WdatePicker.js" type="text/javascript" language="javascript"></script>
<div class="search">
	<form id="searchForm" action="<?php echo $this -> callViewHelper('url', array(array('do'=>'index',)));?>" method="get">
		<div class="explain_col"><input type="hidden" value="admin" name="g"><input type="hidden" value="ad" name="m"><input type="hidden" value="index" name="a"><input type="hidden" value="12" name="menuid">开始时间：
            	<input type="text" value="" size="12" class="Wdate" value="<?php echo $this->_tpl_vars['params']['start_time_min']; ?>
"   onClick="WdatePicker()"  id="start_time_min" name="start_time_min">                -
                <input type="text" value="" size="12" class="Wdate"  value="<?php echo $this->_tpl_vars['params']['start_time_max']; ?>
"    onClick="WdatePicker()"  id="start_time_max" name="start_time_max">
                           结束时间：
                <input type="text" value="" size="12" class="Wdate"  value="<?php echo $this->_tpl_vars['params']['end_time_min']; ?>
"   onClick="WdatePicker()"   id="end_time_min" name="end_time_min">                -
                <input type="text" value="" size="12" class="Wdate"   value="<?php echo $this->_tpl_vars['params']['end_time_max']; ?>
"   onClick="WdatePicker()" " id="end_time_max" name="end_time_max"><div class="bk3"></div>广告位：
                <select class="mr10" name="board_id">
                <option value="">--所有--</option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['adBoard'],'selected' => $this->_tpl_vars['params']['board_id']), $this);?>

              </select>
              
		               状态: <select class="mr10" name="status">
		              <option value="">--所有--</option>
		              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['statusData'],'selected' => $this->_tpl_vars['params']['status']), $this);?>

		          </select>
		          广告类型：
               <select class="mr10" name="style">
               <option value="">--不限--</option>
               <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['adType'],'selected' => $this->_tpl_vars['params']['type']), $this);?>

              </select>关键词：
                <input type="text" value="" size="25" class="input-text mr10"  value="<?php echo $this->_tpl_vars['params']['keyword']; ?>
"   name="keyword">
              	<input type="submit" name="dosearch" value="搜索" />              
              </div>
	</form>
</div>


<div id="ajax_search">
	<div class="title">广告管理</div>
	<div class="content">
		<div class="sub_title">[ <a onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"add-ad",)));?>')" href="javascript:fGo()">添加广告</a> ]</div>
		<table cellspacing="0" cellpadding="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
                <td>广告名称</td> 						
				<td>广告链接</td>
				<td>广告类型</td>
				<td>广告位</td>
				<td>有效时间</td>
				<td>排序</td>
				<td>状态</td>
				<td>管理操作</td>
			</tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		   <tr>
			<td><?php echo $this->_tpl_vars['item']['id']; ?>
</td>   
			<td><?php echo $this->_tpl_vars['item']['name']; ?>
</td>   
			<td><?php echo $this->_tpl_vars['item']['url']; ?>
</td>
			<td><?php echo $this->_tpl_vars['item']['type']; ?>
</td>
			<td><?php echo $this->_tpl_vars['adBoard'][$this->_tpl_vars['item']['board_id']]; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['start_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
 / <?php echo ((is_array($_tmp=$this->_tpl_vars['item']['end_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
</td>
			<td><input type="text" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>"ajax-change-ad",)));?>',<?php echo $this->_tpl_vars['item']['id']; ?>
,'ordid',this.value)"style="width:30px" value="<?php echo $this->_tpl_vars['item']['ordid']; ?>
"></td>
            <td id="ajax_status<?php echo $this->_tpl_vars['item']['id']; ?>
"><?php echo $this->_tpl_vars['item']['status']; ?>
</td>
			<td>
			<a onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"edit-ad",'id'=>$this->_tpl_vars['item']['id'],'back_url'=>$this->_tpl_vars['back_url'],)));?>')" href="javascript:fGo()">编辑</a> |
          	<a onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>"del-ad",)));?>','<?php echo $this->_tpl_vars['item']['id']; ?>
','<?php echo $this -> callViewHelper('url', array(array('action'=>'index',)));?>')" href="javascript:fGo()">删除</a>
	
			</td>
		</tr>	
	   <?php endforeach; endif; unset($_from); ?>			
		</tbody>
		
		</table>
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>	
</div>