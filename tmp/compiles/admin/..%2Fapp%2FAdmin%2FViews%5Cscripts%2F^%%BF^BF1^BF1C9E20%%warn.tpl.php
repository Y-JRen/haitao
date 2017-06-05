<?php /* Smarty version 2.6.19, created on 2014-12-22 17:35:31
         compiled from stock-report/warn.tpl */ ?>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/stock-report/warn">
仓库-><?php echo $this->_tpl_vars['area_name']; ?>
    状态->正常
预警状态
<select name="warn">
<option value="1" <?php if ($this->_tpl_vars['param']['warn'] == '1'): ?>selected<?php endif; ?>>预警</option>
<option value="2" <?php if ($this->_tpl_vars['param']['warn'] == '2'): ?>selected<?php endif; ?>>未预警</option>
</select>

<?php echo $this->_tpl_vars['catSelect']; ?>

产品编码：<input type="text" name="product_sn" size="6" maxLength="20" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
</form>
</div>
<form name="myForm" id="myForm">
<div class="title">库存管理 -&gt; 库存预警</div>
<div class="content">
 <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 30天平均销量 = 截止当前时间的30天的总销量/30<br>
	    　* 可销售天数1 = 可用库存/30天平均销量 <br>
	    　* 7天平均销量 = 截止当前时间的7天的总销量/7<br>
	    　*可销售天数2 = 可用库存/7天平均销量<br>
	    　
	    </font>
	    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
    <thead>
        <tr>
            <td>产品ID</td>
            <td>产品编码</td>
            <td>产品名称</td>
            <th>实际库存</th>
            <th>可用库存</th>
            <th>30天平均销量</th>
            <th>可销售天数1</th>
            <th>7天平均销量</th>
           <th>可销售天数2</th>
            <th>预警状态</th>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['product_id']; ?>
">
        <td><?php echo $this->_tpl_vars['data']['product_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['real_number']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['able_number']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['count30']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['count30avg']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['count7']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['count7avg']; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['warn'] == 1): ?><font color="red">库存预警</font><?php else: ?>库存正常<?php endif; ?></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>