<?php /* Smarty version 2.6.19, created on 2014-08-12 16:16:25
         compiled from stock-report/inventory.tpl */ ?>
<form name="searchForm" id="searchForm" action="<?php echo $this -> callViewHelper('url', array());?>">
<div class="search">
选择仓库
<select name="logic_area" onchange="$('searchForm').submit()">
<?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['param']['logic_area'] == $this->_tpl_vars['key']): ?>
<?php if (! $this->_tpl_vars['param']['only_distribution'] || $this->_tpl_vars['key'] > 20): ?>
<?php endif; ?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['logic_area'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</select>
选择库存状态
<select name="status_id" onchange="$('searchForm').submit()">
<?php $_from = $this->_tpl_vars['status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['status_id'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<br><br>
<?php echo $this->_tpl_vars['catSelect']; ?>

产品编码：<input type="text" name="product_sn" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
</form>

<div class="title">库存管理 -&gt; 调整库存盘点</div>
<div class="content">
    <?php if (! $this->_tpl_vars['param']['only_distribution']): ?>
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"add-inventory-xls",)));?>')">导入盘点表格</a> ]
    </div>
    <?php endif; ?>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>仓库</td>
            <td>产品ID</td>
            <td>产品编码</td>
            <td>产品名称</td>
            <td>批次</td>
            <td>货位</td>
            <td>库存状态</td>
            <td>实际库存</td>
            <td>在途库存</td>
            <td>占用库存</td>
            <?php if ($this->_tpl_vars['param']['logic_area'] > 20): ?><td>实际盘点数</td><?php endif; ?>
        </tr>
    </thead>
    <tbody>
    <?php if ($this->_tpl_vars['datas']): ?>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
        <td><?php echo $this->_tpl_vars['areas'][$this->_tpl_vars['param']['logic_area']]; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
<font color="#FF0000">(<?php echo $this->_tpl_vars['data']['goods_style']; ?>
)</font></td>
        <td><?php if ($this->_tpl_vars['data']['batch_no']): ?><?php echo $this->_tpl_vars['data']['batch_no']; ?>
<?php else: ?>无批次<?php endif; ?></td>
        <!--<td><input type="text" id="local_sn_<?php echo $this->_tpl_vars['data']['product_id']; ?>
" value="<?php echo $this->_tpl_vars['data']['local_sn']; ?>
" size="12" onblur="changeLocalSN(<?php echo $this->_tpl_vars['data']['product_id']; ?>
, this.value)"></td>-->
        <td><?php echo $this->_tpl_vars['data']['position_no']; ?>
</td>
        <td><?php echo $this->_tpl_vars['status'][$this->_tpl_vars['param']['status_id']]; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['real_number']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['wait_number']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['hold_number']; ?>
</td>
        <?php if ($this->_tpl_vars['param']['logic_area'] > 20): ?>
        <td>
          <input name="product_id" type="hidden" value="<?php echo $this->_tpl_vars['data']['product_id']; ?>
" />
          <input name="batch_id" type="hidden" value="<?php echo $this->_tpl_vars['data']['batch_id']; ?>
" />
          <input id="number_<?php echo $this->_tpl_vars['data']['product_id']; ?>
_<?php echo $this->_tpl_vars['data']['batch_id']; ?>
" type="text" size="3" value="0" />
          <input type="button" name="btn" onclick="inventory('<?php echo $this->_tpl_vars['param']['logic_area']; ?>
', '<?php echo $this->_tpl_vars['data']['product_id']; ?>
', '<?php echo $this->_tpl_vars['data']['batch_id']; ?>
', '<?php echo $this->_tpl_vars['param']['status_id']; ?>
', 2)"  value="调整">
          <!--
          <?php if ($this->_tpl_vars['groupID'] == 1): ?>
          <input type="button" name="btn" onclick="inventory('<?php echo $this->_tpl_vars['param']['logic_area']; ?>
', '<?php echo $this->_tpl_vars['data']['product_id']; ?>
', '<?php echo $this->_tpl_vars['data']['batch_id']; ?>
', '<?php echo $this->_tpl_vars['param']['status_id']; ?>
', 1)"  value="初始化">
          <?php endif; ?>
          -->
          <input type="text" id="remark_<?php echo $this->_tpl_vars['data']['product_id']; ?>
_<?php echo $this->_tpl_vars['data']['batch_id']; ?>
" value="备注" style="width:100%" onclick="if (this.value=='备注') this.value = '';"> 
        </td>
        <?php endif; ?>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
<script type="text/javascript">
//同步
function inventory(logic_area, product_id, batch_id, status_id, type){
    if (type == 1)  str = '你确认要初始化吗？';
    else    str = '你确认要调整吗？';
	if(confirm(str)){
		if (!logic_area || !product_id || !status_id) {
		    alert('参数错误');
		    return false;
		}
		var number = $('number_' + product_id + '_' + batch_id).value;
		var remark = $('remark_' + product_id + '_' + batch_id).value;
		if (remark == '备注') remark = '';
	    new Request({
					 url:'/admin/stock-report/do-inventory/type/' + type + '/logic_area/' + logic_area + '/product_id/' + product_id + '/batch_id/' + batch_id + '/status_id/' + status_id + '/number/' + number + '/remark/' + encodeURI(remark),
					 onSuccess:function(msg){
							if(msg == 'ok'){
							    if (type == 1) {
								    alert('初始化成功');
								}
								else {
								    alert('调整成功');
								}
								location.reload();
							}
							else {
								alert(msg);
							}
						},
						onError:function() {
							alert("网络繁忙，请稍后重试");
						}
					}).send();
			
	}
}
function changeLocalSN(product_id, value)
{
    new Request({
        url:'/admin/stock-report/update-local-sn/product_id/' + product_id + '/local_sn/' + value,
	    onSuccess:function(msg){
            
		},
		onError:function() {
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
</script>