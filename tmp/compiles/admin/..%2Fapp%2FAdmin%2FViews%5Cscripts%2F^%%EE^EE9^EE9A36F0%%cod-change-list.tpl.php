<?php /* Smarty version 2.6.19, created on 2014-12-22 17:27:20
         compiled from cod/cod-change-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'cod/cod-change-list.tpl', 14, false),array('modifier', 'default', 'cod/cod-change-list.tpl', 87, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
发货开始日期：<input  class="Wdate" onClick="WdatePicker()" type="text" name="fromdate" id="fromdate" size="11" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"/>
结束日期：<input  class="Wdate" onClick="WdatePicker()" type="text" name="todate" id="todate" size="11" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"/>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
    <option value="sf" <?php if ($this->_tpl_vars['param']['logistic_code'] == 'sf'): ?>selected<?php endif; ?>>顺丰</option>
    <option value="ems" <?php if ($this->_tpl_vars['param']['logistic_code'] == 'ems'): ?>selected<?php endif; ?>>EMS</option>
</select>
省份：<select name="province_id" onchange="getArea(this)">
    <option value="">请选择省</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['province']), $this);?>

</select>
城市：<select name="city_id" onchange="getArea(this)">
    <option value="">请选择市</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['city']), $this);?>

</select>
区县：<select name="area_id">
    <option value="">请选择区</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['area']), $this);?>

</select>
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['billType']), $this);?>

</select>
<br />
部门：
<select name="sub_code" id="sub_code">
  <option value="">请选择</option>
  <option value="jiankang" <?php if ($this->_tpl_vars['param']['sub_code'] == 'jiankang'): ?>selected<?php endif; ?>>1健康</option>
  <option value="call" <?php if ($this->_tpl_vars['param']['sub_code'] == 'call'): ?>selected<?php endif; ?>>呼叫中心</option>
  <option value="other" <?php if ($this->_tpl_vars['param']['sub_code'] == 'other'): ?>selected<?php endif; ?>>其它</option>
</select>
配送状态：
<select name="logistic_status">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticStatus'],'selected' => $this->_tpl_vars['param']['logistic_status']), $this);?>

</select>
结算状态：<select name="cod_status"><option value="">请选择</option><option value="1">已结算</option><option value="0">未结算</option></select>

运单号码：<input type="text" name="logistic_no" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['logistic_no']; ?>
"/>
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['consignee']; ?>
"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
</form>
<div class="title">代收货款管理 -&gt; 代收货款变更</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="float:left;width:600px;"><input type="checkbox" onclick="checkall($('myForm'),'ids',this)" title="全选/全不选" name="chkall"> <input type="button" onclick="ajax_submit(this.form, '/admin/cod/lock-transport/val/1','Gurl(\'refresh\',\'ajax_search\')')" value="锁定"> <input type="button" onclick="ajax_submit(this.form, '/admin/cod/lock-transport/val/0','Gurl(\'refresh\',\'ajax_search\')')" value="解锁"></div>
<div style="float:right;">
    <b>总金额：<?php echo $this->_tpl_vars['sum']; ?>
</b>
    &nbsp;&nbsp;&nbsp;
    </div>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>单据类型</td>
            <td>物流公司</td>
            <td>金额</td>
            <td>佣金</td>
            <td>结算状态</td>
            <td>运单号</td>
            <td>配送状态</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['tid']; ?>
">
    	<td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['tid']; ?>
"/></td>
        <td>
			<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>"view-cod",'id'=>$this->_tpl_vars['data']['tid'],)));?>','ajax','查看单据')" value="查看">
        </td>
        <td><?php echo $this->_tpl_vars['data']['bill_no']; ?>
</td>
        <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['logistic_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['amount']+$this->_tpl_vars['data']['change_amount']; ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['logistic_price_cod'])) ? $this->_run_mod_handler('default', true, $_tmp, '0.00') : smarty_modifier_default($_tmp, '0.00')); ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['cod_status'] == 1): ?>已<?php else: ?>未<?php endif; ?>结算</td>
        <td><?php echo $this->_tpl_vars['data']['logistic_no']; ?>
</td>
        <td><?php echo $this->_tpl_vars['logisticStatus'][$this->_tpl_vars['data']['logistic_status']]; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['lock_name']): ?>已被<font color="red"><?php echo $this->_tpl_vars['data']['lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
<div style="padding:0 5px;"><input type="checkbox" onclick="checkall($('myForm'),'ids',this)" title="全选/全不选" name="chkall"> <input type="button" onclick="ajax_submit(this.form, '/admin/cod/lock-transport/val/1','Gurl(\'refresh\',\'ajax_search\')')" value="锁定"> <input type="button" onclick="ajax_submit(this.form, '/admin/cod/lock-transport/val/0','Gurl(\'refresh\',\'ajax_search\')')" value="解锁">
</div>
</div>
</div>
</form>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
<script language="JavaScript">
function getArea(id)
{
    var value = id.value;
    var select = $(id).getNext();
    new Request({
        url: '/admin/member/area/id/' + value,
        onRequest: loading,
        onSuccess:function(data){
            select.options.length = 1;
	        if (data != '') {
	            data = JSON.decode(data);
	            $each(data, function(item, index){
	                var option = document.createElement("OPTION");
                    option.value = index;
                    option.text  = item;
                    select.options.add(option);
	            });
	        }
            loadSucess();
        }
    }).send();
}
</script>