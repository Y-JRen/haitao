<?php /* Smarty version 2.6.19, created on 2014-08-22 15:26:38
         compiled from member/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'member/index.tpl', 18, false),array('modifier', 'truncate', 'member/index.tpl', 61, false),)), $this); ?>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>

<div class="search">
<form name="searchForm" id="searchForm">
<div>
<span style="float:left">注册日期从：
<input type="text"  value="<?php echo $this->_tpl_vars['param']['reg_fromdate']; ?>
" id="reg_fromdate"  name="reg_fromdate"   class="Wdate"   onClick="WdatePicker()" ></span>
<span style="float:left; margin-left:10px">截止到：<input  type="text"  value="<?php echo $this->_tpl_vars['param']['reg_todate']; ?>
" id="reg_todate"  name="reg_todate"   class="Wdate"   onClick="WdatePicker()" ></span>
<span style="float:left; margin-left:10px">最后登陆日期从：<input  type="text"  value="<?php echo $this->_tpl_vars['param']['log_fromdate']; ?>
" id="reg_todate"  name="log_fromdate"   class="Wdate"   onClick="WdatePicker()">
</span>
<span style="float:left; margin-left:10px">截止到：<input type="text"  value="<?php echo $this->_tpl_vars['param']['log_todate']; ?>
" id="reg_todate"  name="log_todate"   class="Wdate"   onClick="WdatePicker()" >
</span>
</div>
    <div style="clear:both; padding-top:5px">
    <span style="margin-left:5px; vertical-align:top">会员等级: </span>
    <select name="rank_id"><option value="">请选择</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['member_ranks'],'selected' => $this->_tpl_vars['param']['rank_id']), $this);?>
}}
    </select>
<span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="user_name"  value="<?php echo $this->_tpl_vars['param']['user_name']; ?>
"  size="15" />	
积分大于<input type="text" name="pointfrom"  value="<?php echo $this->_tpl_vars['param']['pointfrom']; ?>
"  size="10" /> 
小于<input type="text" name="pointto"  value="<?php echo $this->_tpl_vars['param']['pointto']; ?>
" size="10" />      
余额大于<input type="text" name="moneyfrom"  value="<?php echo $this->_tpl_vars['param']['moneyfrom']; ?>
"  size="10" /> 
小于<input type="text" name="moneyto"  value="<?php echo $this->_tpl_vars['param']['moneyto']; ?>
" size="10" />　
经验值大于<input type="text" name="experiencefrom"  value="<?php echo $this->_tpl_vars['param']['experiencefrom']; ?>
"  size="10" /> 
小于<input type="text" name="experienceto"  value="<?php echo $this->_tpl_vars['param']['experienceto']; ?>
" size="10" />　　 
<input type="button" name="dosearch" value="选择搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('action'=>'index','do'=>'search',)));?>','ajax_search')"/>
  <!--[<a href="<?php echo $this -> callViewHelper('url', array(array('action'=>"export-user",)));?>?<?php echo $_SERVER['QUERY_STRING']; ?>
" target="_blank">导出用户信息</a>]
<input type="button" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'exportmobile',)));?>')" value="导出用户手机号码">-->
</div>
</form>
</div>
<div id="ajax_search">
<?php endif; ?>
<div class="title">会员管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加会员</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>用户ID</td>
            <td>会员名称</td>
			<td>积分</td>
			<td>余额</td>
            <td>经验值</td>
            <td>注册时间</td>
            <td>最后登陆时间</td>
			<td>登陆次数</td>
			<td>CPS</td>
			<td>推荐</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['member_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['member'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['member']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['member']):
        $this->_foreach['member']['iteration']++;
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['member']['user_id']; ?>
">
            <td><!--<input type="checkbox" name=ids value="<?php echo $this->_tpl_vars['member']['user_id']; ?>
" />--><?php echo $this->_tpl_vars['member']['user_id']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['user_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
			 <td><?php echo $this->_tpl_vars['member']['point']; ?>
点</td>
			 <td><?php echo $this->_tpl_vars['member']['money']; ?>
元</td>
             <td><?php echo $this->_tpl_vars['member']['experience']; ?>
</td>
            <td><?php echo $this->_tpl_vars['member']['add_time']; ?>
</td>
            <td><?php echo $this->_tpl_vars['member']['last_login']; ?>
</td>
			<td><?php echo $this->_tpl_vars['member']['login_count']; ?>
</td>
			<td><?php if ($this->_tpl_vars['member']['parent_id']): ?><?php echo $this->_tpl_vars['member']['parent_id']; ?>
|<?php echo ((is_array($_tmp=$this->_tpl_vars['member']['parent_user_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
<?php endif; ?></td>
			<td><?php if ($this->_tpl_vars['member']['tj_user_id']): ?><?php echo $this->_tpl_vars['member']['tj_user_id']; ?>
|<?php echo ((is_array($_tmp=$this->_tpl_vars['member']['tj_user_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
<?php endif; ?></td>
            <td id="ajax_status<?php echo $this->_tpl_vars['member']['user_id']; ?>
"><?php echo $this->_tpl_vars['member']['status']; ?>
</td>
            <td>
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'view','id'=>$this->_tpl_vars['member']['user_id'],)));?>')">查看</a> | 
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['member']['user_id'],)));?>')">编辑</a>
            </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
</div>
<script>
function multiDelete()
{
    checked = multiCheck($('table'),'ids',$('doDelete'));
    if (checked != '') {
        reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delete',)));?>', checked);
    }
}
</script>
<?php endif; ?>