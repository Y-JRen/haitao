<?php /* Smarty version 2.6.19, created on 2015-07-30 17:09:46
         compiled from transport/track-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'transport/track-list.tpl', 8, false),array('modifier', 'date_format', 'transport/track-list.tpl', 95, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
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
<div class="line">
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => $this->_tpl_vars['param']['logistic_code']), $this);?>

</select>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" <?php if ($this->_tpl_vars['param']['is_cod'] == '0'): ?>selected<?php endif; ?>>非货到付款</option><option value="1" <?php if ($this->_tpl_vars['param']['is_cod'] == '1'): ?>selected<?php endif; ?>>货到付款</option></select>
是否投诉：<select name="is_complain"><option value="">请选择</option><option value="0" <?php if ($this->_tpl_vars['param']['is_complain'] == '0'): ?>selected<?php endif; ?>>否</option><option value="1" <?php if ($this->_tpl_vars['param']['is_complain'] == '1'): ?>selected<?php endif; ?>>是</option></select>
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['billType'],'selected' => $this->_tpl_vars['param']['bill_type']), $this);?>

</select>
配送状态：
<select name="logistic_status">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticStatus'],'selected' => $this->_tpl_vars['param']['logistic_status']), $this);?>

</select>
</div>
<div class="line">
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['consignee']; ?>
"/>
运单号码：<input type="text" name="logistic_no" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['logistic_no']; ?>
"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('is_lock'=>yes,)));?>'+location.search)"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('is_lock'=>no,)));?>'+location.search)"/>
</div>
</form>
<div class="title">配送管理 -&gt; 运输单跟踪</div>
<form name="myForm" id="myForm">
<div class="content">
<div class="sub_title">[ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"track-batch",)));?>')">批量维护</a> ]</div>
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
<?php if ($this->_tpl_vars['datas']): ?>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>物流公司</td>
            <td>运输单号</td>
            <td>付款方式</td>
            <td>省份</td>
            <td>城市</td>
            <td>区县</td>
            <td>配送状态</td>
            <td>单据编号</td>
            <td>单据类型</td>
            <td>发货日期</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['sid']; ?>
">
        <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['tid']; ?>
"/></td>
        <td>
			<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'track','id'=>$this->_tpl_vars['data']['tid'],)));?>','ajax','运输单跟踪',750,400)" value="查看">
			<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'change','id'=>$this->_tpl_vars['data']['tid'],)));?>','ajax','运输单变更',500,300)" value="变更">
            <?php if ($this->_tpl_vars['data']['logistic_code'] == 'ht' || $this->_tpl_vars['data']['logistic_code'] == 'zjs' || $this->_tpl_vars['data']['logistic_code'] == 'yt' || $this->_tpl_vars['data']['logistic_code'] == 'ems' || $this->_tpl_vars['data']['logistic_code'] == 'sf'): ?>
            <input type="button" onclick="openDiv('/admin/transport/bill-track/logistic_code/<?php echo $this->_tpl_vars['data']['logistic_code']; ?>
/logistic_no/<?php echo $this->_tpl_vars['data']['logistic_no']; ?>
','ajax','运输单跟踪查询',600,400)" 
            value="运输单跟踪查询">
            <?php endif; ?>
        </td>
        <td><?php echo $this->_tpl_vars['data']['logistic_name']; ?>
</td>
        <td>
        <?php echo $this->_tpl_vars['data']['logistic_no']; ?>

        </td>
        <td><?php if ($this->_tpl_vars['data']['is_cod']): ?>货到付款<?php else: ?>非货到付款<?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['province']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['city']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['area']; ?>
</td>
        <td><?php echo $this->_tpl_vars['logisticStatus'][$this->_tpl_vars['data']['logistic_status']]; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['bill_no_str']; ?>
</td>
        <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['send_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['lock_name']): ?>已被<font color="red"><?php echo $this->_tpl_vars['data']['lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
	<input type="button" onclick="window.open('<?php echo $this -> callViewHelper('url', array(array('action'=>'export',)));?>'+location.search)" value="导出数据">
    <?php endif; ?>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>

<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
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