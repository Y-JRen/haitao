<?php /* Smarty version 2.6.19, created on 2014-09-01 17:47:13
         compiled from member/phone.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'member/phone.tpl', 12, false),array('modifier', 'truncate', 'member/phone.tpl', 48, false),)), $this); ?>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
    <span style="float:left">注册日期从：<input type="text" name="reg_fromdate" id="reg_fromdate" size="11" value=""   class="Wdate"   onClick="WdatePicker()"/></span>
    <span style="float:left; margin-left:10px">截止到：<input type="text" name="reg_todate" id="reg_todate" size="11" value=""  class="Wdate"   onClick="WdatePicker()"/></span>
    <span style="float:left; margin-left:10px">最后登陆日期从：<input type="text" name="log_fromdate" id="log_fromdate" size="11" value=""  class="Wdate"   onClick="WdatePicker()"/></span>
    <span style="float:left; margin-left:10px">截止到：<input type="text" name="log_todate" id="log_todate" size="11" value=""  class="Wdate"   onClick="WdatePicker()"/></span>
    <br><br><br>
    会员等级: 
    <select name="rank_id">
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['member_ranks']), $this);?>

    </select>
    会员名或昵称: </span><input type="text" name="user_name" value="" size="15" />
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('action'=>'phone','do'=>'search',)));?>','ajax_search')"/>
</form>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"quick-reg",)));?>" method="post">
    <div style="clear:both; padding-top:5px">
    会员名: <input type="text" name="user_name" id="user_name" value="" size="15" msg="请填写会员名" class="required" />
    <input type="submit" name="dosubmit" value="快速注册"/>
    </div>
</div>
</form>
<?php endif; ?>
<div id="ajax_search">
<div class="title">会员管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加会员</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>会员名称</td>
            <td>手机</td>
            <td>注册时间</td>
            <td>最后登陆时间</td>
            <td>状态</td>
            <td>操作</td>
            <td>电话下单</td>
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
            <td><?php echo $this->_tpl_vars['member']['user_id']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['user_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
            <td><?php echo $this->_tpl_vars['member']['mobile']; ?>
</td>
            <td><?php echo $this->_tpl_vars['member']['add_time']; ?>
</td>
            <td><?php echo $this->_tpl_vars['member']['last_login']; ?>
</td>
            <td id="ajax_status<?php echo $this->_tpl_vars['member']['user_id']; ?>
"><?php echo $this->_tpl_vars['member']['status']; ?>
</td>
            <td>
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'view','id'=>$this->_tpl_vars['member']['user_id'],)));?>')">查看</a> | 
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['member']['user_id'],)));?>')">编辑</a>
            </td>
            <td>
             <a href="http://www.1jiankang.com/shop/auth/mix-login/code/<?php echo $this->_tpl_vars['member']['auth_code']; ?>
/operator_id/<?php echo $this->_tpl_vars['operator_id']; ?>
" target="_blank">电话下单</a> 
            </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
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
</div>