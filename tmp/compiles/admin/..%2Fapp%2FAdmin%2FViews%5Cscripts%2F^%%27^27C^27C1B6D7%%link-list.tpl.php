<?php /* Smarty version 2.6.19, created on 2014-07-24 12:21:49
         compiled from goods/link-list.tpl */ ?>
<form name="searchForm" id="searchForm" action="/admin/goods/link-list/">
<div class="search">
<?php echo $this->_tpl_vars['catSelect']; ?>

上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" <?php if ($this->_tpl_vars['param']['onsale'] == 'on'): ?>selected<?php endif; ?>>上架</option><option value="off" <?php if ($this->_tpl_vars['param']['onsale'] == 'off'): ?>selected<?php endif; ?>>下架</option></select>
名称：<input type="text" name="goods_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/> 编码：<input type="text" name="goods_sn" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
"/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="title">商品管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>商品编码</td>
            <td>商品名称</td>
            <td>市场价</td>
            <td>本店价</td>
            <td>状态</td>
            <td>操 作</td>
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
        <td><?php echo $this->_tpl_vars['data']['goods_status']; ?>
</td>
        <td>
			<a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'link','id'=>$this->_tpl_vars['data']['goods_id'],)));?>','ajax','查看<?php echo $this->_tpl_vars['data']['goods_name']; ?>
关联商品',750,400)">编辑关联商品</a>
        </td>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>