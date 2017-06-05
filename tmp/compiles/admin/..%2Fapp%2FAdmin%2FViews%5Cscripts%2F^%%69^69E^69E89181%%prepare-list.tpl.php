<?php /* Smarty version 2.6.19, created on 2015-07-21 15:58:40
         compiled from transport/prepare-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'transport/prepare-list.tpl', 71, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<script type="text/javascript">
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js', createWin);
var win;
function createWin()
{
    win = new dhtmlXWindows();
    win.setImagePath("/scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
}
</script>
<div class="search">
<form name="searchForm" id="searchForm" method="get">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" <?php if ($this->_tpl_vars['param']['is_cod'] == '0'): ?>selected<?php endif; ?>>非货到付款</option><option value="1" <?php if ($this->_tpl_vars['param']['is_cod'] == '1'): ?>selected<?php endif; ?>>货到付款</option></select>
<div class="line">
店铺：
  <select name="shop_id">
    <option value="">请选择...</option>
    <?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shop']):
?>
      <?php if ($this->_tpl_vars['shop']['shop_type'] != 'tuan'): ?>
      <option value="<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
" <?php if ($this->_tpl_vars['shop']['shop_id'] == $this->_tpl_vars['param']['shop_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['shop']['shop_name']; ?>
</option>
      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  </select>
收货人：<input type="text" name="addr_consignee" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['addr_consignee']; ?>
"/>
订单号：<input type="text" name="batch_sn" size="30" maxLength="50" value="<?php echo $this->_tpl_vars['param']['batch_sn']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的订单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('is_lock'=>yes,)));?>'+location.search)"/>
<input type="button" name="dosearch3" value="所有没有锁定的订单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('is_lock'=>no,)));?>'+location.search)"/>
</div>
</form>
<div class="title">配送管理 -&gt; 仓库配货</div>
<form name="myForm" id="myForm">
<div class="content">
	<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
	<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"lock-order",)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"lock-order",)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')">
	<!--<input type="button" value="合并选中订单" onclick="showMergeWin()">-->
	<input type="button" value="批量配货" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'prepare',)));?>','Gurl(\'refresh\')')">
	</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>订单号</td>
            <td>店铺</td>
            <td>下单时间</td>
            <td width="350">订单商品</td>
            <td width="60">收货人</td>
		    <td>金额</td>
            <!--<td>运费</td>-->
            <td>付款方式</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['item']['order_batch_id']; ?>
">
        <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['item']['batch_sn']; ?>
"/></td>
        <td>
	      <input type="button" onclick="showOrderWin('<?php echo $this->_tpl_vars['item']['batch_sn']; ?>
')" value="操作">
        </td>
        <td><?php if ($this->_tpl_vars['item']['can_merge']): ?><b><font color="<?php echo $this->_tpl_vars['item']['order_color']; ?>
"><?php echo $this->_tpl_vars['item']['batch_sn']; ?>
</font></b><?php else: ?><?php echo $this->_tpl_vars['item']['batch_sn']; ?>
<?php endif; ?><br /><b><?php echo $this->_tpl_vars['item']['note_logistic']; ?>
</b></td>
        <td><?php echo $this->_tpl_vars['item']['shop_name']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
        <td>
		  <?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
		    <?php if ($this->_tpl_vars['goods']['batch_sn'] == $this->_tpl_vars['item']['batch_sn']): ?>
			  <?php echo $this->_tpl_vars['goods']['goods_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['goods']['goods_style']; ?>
</font>)  
              <font color="#336633"><?php echo $this->_tpl_vars['goods']['product_sn']; ?>
</font>*<?php echo $this->_tpl_vars['goods']['number']; ?>
<br />
		    <?php endif; ?>
		  <?php endforeach; endif; unset($_from); ?>
		</td>
        <td><?php echo $this->_tpl_vars['item']['addr_consignee']; ?>
</td>
        <td><?php echo $this->_tpl_vars['item']['price_order']; ?>
</td>
        <!--<td><?php echo $this->_tpl_vars['item']['price_logistic']; ?>
</td>-->
        <td><?php if ($this->_tpl_vars['item']['pay_type'] == 'cod'): ?>货到付款<?php else: ?>非货到付款<?php endif; ?></td>
        <td><?php if ($this->_tpl_vars['item']['lock_name']): ?><font color="red">被<?php echo $this->_tpl_vars['item']['lock_name']; ?>
锁定</font><?php else: ?>未锁定<?php endif; ?></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"lock-order",)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"lock-order",)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')">
<!--<input type="button" value="合并选中订单" onclick="showMergeWin()">-->
<input type="button" value="批量配货" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'prepare',)));?>','Gurl(\'refresh\')')">
</div>
</form>

<script type="text/javascript">
function showMergeWin()
{
	var control = document.getElementsByName('ids[]');
	var ids = '';
	var count = 0;
	for (var i = 0; i < control.length; i++) {
	    if (control[i].checked) {
	        ids += control[i].value + ',';
	        count++;
	    }
	}
	if (ids == '') {
	    alert('请先勾选要合并的订单!');
	    return;
	}
    if (count < 2) {
        alert('至少要勾选两个以上的订单!');
	    return;
    }
    
	var run = win.createWindow("mergeWindow", 200, 50, 500, 400);
	run.setText("合并订单");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/transport/merge-order/ids/" + ids, true);
}

function showSpliltWin(batch_sn)
{
	var run = win.createWindow("splitWindow", 200, 50, 600, 400);
	run.setText("拆分订单");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/transport/split-order/batch_sn/" + batch_sn, true);
}

function showOrderWin(batch_sn)
{
	var run = win.createWindow("orderWindow", 200, 50, 600, 400);
	run.setText("订单配货");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/transport/prepare-order/batch_sn/" + batch_sn, true);
}

var areaIndex = 0;
function splitOrder()
{
    var baseArray = getAllBaseSplitOrderGoods();
    var newArray = new Array();
    var index = 0;
    for (var i = 0; i < baseArray.length; i++) {
        tempArray = baseArray[i].split('_');
        product_sn = tempArray[0];
        base_number = tempArray[1];
        number = getCurrentSplitOrderGoodsNumber(product_sn);
        if (number > base_number) {
            alert('产品编码' + product_sn + '的数量不能大于总数量' + base_number + '!');
            return false;
        }
        if (number < base_number) {
            newArray[index] = product_sn + '_' + (base_number - number);
            index++;
        }
    }
    
    if (newArray.length == 0)   return false;
    
    areaIndex++;
    var content = '<table cellpadding="0" cellspacing="0" border="5" class="table" id="area_' + areaIndex + '">';
    for (i = 0; i < newArray.length; i++) {
        tempArray = newArray[i].split('_');
        product_sn = tempArray[0];
        number = tempArray[1];
        for (j = 0; j < baseArray.length; j++) {
            tempArray = baseArray[j].split('_');
            if (tempArray[0] == product_sn) {
                product_name = tempArray[2] + ' (<font color="red">' + tempArray[3] + '</font>)';
            }
        }
        content = content + '<tr>' +
                            '<td width="292px">' + product_name +'</td>' + 
                            '<td width="60px">' + product_sn + '</td>' + 
                            '<td width="40px" style="text-align:center">' + number + '<input type="hidden" name="number[]" id="number_' + product_sn + '" value="' + number + '"><input type="hidden" name="product[]" value="' + areaIndex + '_' + product_sn + '"></td>';
        if (i == 0) {
            content = content + '<td rowspan="' + newArray.length + '"><input type="button" value="删除" onclick="removeSplitOrderTable(' + areaIndex + ')"></td>';
        }
        content = content + '</tr>';
    }
    content = content + '</table><br>';
    var newArea = document.getElementById('newArea');
    newArea.innerHTML = newArea.innerHTML + content;
}

function getAllBaseSplitOrderGoods()
{
    var control = document.getElementsByName('base_number');
    var result = new Array();
    for (var i = 0; i < control.length; i++) {
        id = control[i].id;
        tempArray = id.split('_');
        result[i] = tempArray[1] + '_' + control[i].value;
    }
    
    return result;
}

function getCurrentSplitOrderGoodsNumber(product_sn)
{
    var control = document.getElementsByName('number[]');
    var number = 0;
    for (var i = 0; i < control.length; i++) {
        id = control[i].id;
        tempArray = id.split('_');
        if (product_sn == tempArray[1]) {
            number += parseInt(control[i].value);
        }
    }
    
    return number;
}

function removeSplitOrderTable(index)
{
    var table = document.getElementById('area_' + index);
    var base_table = document.getElementById('area_0');
    for (var i = 0; i < table.rows.length; i++) {
        row = table.rows[i];
        inputs = row.getElementsByTagName('input');
        tempArray = inputs[0].id.split('_');
        product_no = tempArray[1];
        number = parseInt(inputs[0].value);
        
        for (var j = 0; j < base_table.rows.length; j++) {
            base_row = base_table.rows[j];
            inputs = base_row.getElementsByTagName('input');
            tempArray = inputs[0].id.split('_');
            if (product_no == tempArray[1]) {
                inputs[0].value = parseInt(inputs[0].value) + number;
                break;
            }
        }
    }
    
    document.getElementById('newArea').removeChild(table);
}

function NumOnly(e)
{
    var key = window.event ? e.keyCode : e.which;
    return key>=48&&key<=57||key==46||key==8;
}

</script>

