<?php /* Smarty version 2.6.19, created on 2014-07-25 09:35:48
         compiled from goods/goods-status.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'goods/goods-status.tpl', 45, false),)), $this); ?>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
<?php echo $this->_tpl_vars['catSelect']; ?>

上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" <?php if ($this->_tpl_vars['param']['onsale'] == 'on'): ?>selected<?php endif; ?>>上架</option><option value="off" <?php if ($this->_tpl_vars['param']['onsale'] == 'off'): ?>selected<?php endif; ?>>下架</option></select>
编码：<input type="text" name="goods_sn" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
"/>
名称：<input type="text" name="goods_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
<input type="button" name="dosearch"   id="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
<!--
<input type="button" onclick="G('/admin/goods/sku-export/?<?php echo $_SERVER['QUERY_STRING']; ?>
')" value="导出商品库存信息">
-->
</div>
</form>
<div class="title">商品管理</div>
<div class="content">
	<form name="myForm" id="myForm">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>商品编码</td>
            <td>商品名称</td>
            <td>市场价</td>
            <td>本店价</td>
            <!--
			<td>内购价</td>
            <td>成本价</td>
            <td>成本总金额</td>
            <td>真实库存</td>
            <td>占用库存</td>
            -->
            <td>可用库存</td>
            <td>官网状态</td>
            <!--<td>内购平台状态</td>-->
            <td>修改记录</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
        <td><?php echo $this->_tpl_vars['data']['goods_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['goods_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['goods_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['market_price']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['price']; ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['able_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
        <td id="ajax_status<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
          <?php if ($this->_tpl_vars['data']['first_char'] != '4'): ?>
			<?php if ($this->_tpl_vars['data']['price'] != 0): ?>
			  <?php echo $this->_tpl_vars['data']['status']; ?>

			  <?php if ($this->_tpl_vars['data']['onoff_remark']): ?>
				(<?php echo $this->_tpl_vars['data']['onoff_remark']; ?>
)
			  <?php endif; ?>
			<?php endif; ?>
		  <?php endif; ?>
		</td>
		<!--
		<td id="ajax_status<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
          <?php if ($this->_tpl_vars['data']['first_char'] != '4'): ?>
			<?php if ($this->_tpl_vars['data']['staff_price'] != 0): ?>
			  <?php echo $this->_tpl_vars['data']['status2']; ?>

			  <?php if ($this->_tpl_vars['data']['onoff_remark2']): ?>
				(<?php echo $this->_tpl_vars['data']['onoff_remark2']; ?>
)
			  <?php endif; ?>
			<?php endif; ?>
		  <?php endif; ?>
		</td>
		-->
        <td><a href="javascript:;void(0)" onclick="openDiv('/admin/goods/status-history/goods_id/<?php echo $this->_tpl_vars['data']['goods_id']; ?>
','ajax','查看',500,300,true)">查看</a></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
	<div style="float:left;width:500px;">
	</div>
	</form>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>