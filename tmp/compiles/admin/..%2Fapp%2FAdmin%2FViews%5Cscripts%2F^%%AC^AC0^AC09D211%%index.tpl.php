<?php /* Smarty version 2.6.19, created on 2014-07-24 11:37:55
         compiled from goods/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'goods/index.tpl', 65, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form id="searchForm" action="/admin/goods/">
<input type="hidden" name="angle_id" value="<?php echo $this->_tpl_vars['angle_id']; ?>
">
<div class="search">
<span style="float:left;line-height:18px;">添加开始日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">添加结束日期：</span>
<span>
<input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
<input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value=''"/></span>
是否冻结删除
<select name="is_del" >
		<option value="0" <?php if ($this->_tpl_vars['param']['is_del'] == '0'): ?>selected<?php endif; ?>>正常</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['is_del'] == '1'): ?>selected<?php endif; ?>>已冻结</option>
</select>
    分类：<?php echo $this->_tpl_vars['catSelect']; ?>

上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" <?php if ($this->_tpl_vars['param']['onsale'] == 'on'): ?>selected<?php endif; ?>>上架</option><option value="off" <?php if ($this->_tpl_vars['param']['onsale'] == 'off'): ?>selected<?php endif; ?>>下架</option></select>
<br><br>
分类名称：<input type="text" name="cat_name" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['cat_name']; ?>
"/>
商品名称：<input type="text" name="goods_name" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
编码：<input type="text" name="goods_sn" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
"/>
品牌：<input type="text" name="brand_name" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['brand_name']; ?>
"/>
本店价：<input type="text" name="fromprice" size="5" maxLength="6" value="<?php echo $this->_tpl_vars['param']['fromprice']; ?>
"/>
- <input type="text" name="toprice" size="5" maxLength="6" value="<?php echo $this->_tpl_vars['param']['toprice']; ?>
"/>
<select name="orderby" onchange="searchForm.submit()">
  <option value="">排序方式</option>
  <option value="goods_add_time" <?php if ($this->_tpl_vars['param']['orderby'] == 'goods_add_time'): ?>selected<?php endif; ?>>添加时间(升序)</option>
  <option value="price" <?php if ($this->_tpl_vars['param']['orderby'] == 'price'): ?>selected<?php endif; ?>>本店价(升序)</option>
  <option value="price desc" <?php if ($this->_tpl_vars['param']['orderby'] == 'price desc'): ?>selected<?php endif; ?>>本店价(降序)</option>
</select>
<input type="submit" name="dosearch" value="查询"/>
<input type="button" onclick="window.open('/admin/goods/export'+location.search)" value="导出商品资料">
</div>
</form>
<div class="title">商品管理  &nbsp; &nbsp; &nbsp;&nbsp;    
 </div>     
<div class="content">
    <div class="sub_title">
      [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"goods-cat",)));?>')">添加新品</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>排序</td>
            <td>ID</td>
            <td>商品编码</td>
			<td>商品分类</td>
            <td  width="220px">商品名称</td>
            <td>市场价</td>
            <td>本店价</td>
			<td>限购数量</td>
            <td>状态</td>
            <td>评论</td>
            <td>购买记录</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
        <td><input type="text" name="update" size="2" value="<?php echo $this->_tpl_vars['data']['goods_sort']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['goods_id']; ?>
,'goods_sort',this.value)"></td>
        <td><?php echo $this->_tpl_vars['data']['goods_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['goods_sn']; ?>
</td>
		<td><?php echo $this->_tpl_vars['data']['cat_name']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['market_price']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['price']; ?>
</td>
		<td><input type="text" name="update" size="2" value="<?php echo $this->_tpl_vars['data']['limit_number']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['goods_id']; ?>
,'limit_number',this.value)"></td>
        <td><?php echo $this->_tpl_vars['data']['goods_status']; ?>
</td>
        <td>
			<a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('controller'=>'msg','action'=>'listgoodscomment','goods_id'=>$this->_tpl_vars['data']['goods_id'],'job'=>'view',)));?>','ajax','<?php echo $this->_tpl_vars['data']['goods_name']; ?>
评论',750,400)">查看</a> | 
			<a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('controller'=>'msg','action'=>'goodscommentadd','goods_id'=>$this->_tpl_vars['data']['goods_id'],'goods_name'=>$this->_tpl_vars['data']['goods_name'],)));?>','ajax','<?php echo $this->_tpl_vars['data']['goods_name']; ?>
 添加评论',750,400)">添加</a>
		</td>
		<td><a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('controller'=>'msg','action'=>'goodsbuylog','goods_id'=>$this->_tpl_vars['data']['goods_id'],'goods_name'=>$this->_tpl_vars['data']['goods_name'],)));?>','ajax','<?php echo $this->_tpl_vars['data']['goods_name']; ?>
 购买记录',750,400)">查看/添加</a>
        </td>
        <td>
         <a href="javascript:fGo()" onclick="window.open('/shop/goods/show/id/<?php echo $this->_tpl_vars['data']['goods_id']; ?>
')">查看</a>
         | <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['data']['goods_id'],)));?>')">编辑</a>  | 
        <?php if ($this->_tpl_vars['data']['is_del'] == 1): ?> 
         已冻结  <a href="javascript:fGo()" onclick="delGoods(<?php echo $this->_tpl_vars['data']['goods_id']; ?>
,0)">解冻</a> <?php else: ?>  
         <a href="javascript:fGo()" onclick="delGoods(<?php echo $this->_tpl_vars['data']['goods_id']; ?>
,1)">冻结删除</a>
         <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>

<script type="text/javascript">
function delGoods(id,value){
	if(confirm("确认要操作吗？")){
		id=parseInt(id);
		if(id<1){alert('参数错误！');return;}
		new Request({
			url:'/admin/goods/delete/id/'+id+'/value/'+value,
			method:'get',
			onSuccess:function(data){
				if(data=='ok'){
					alert("操作ID="+id+"成功");
					$('ajax_list'+id).destroy();
				}else{
					alert("操作失败，请稍后重试");
				}
			},
			onFailure:function(){
				alert("网路繁忙，请稍后重试");
			}
		}).send();
	}
}
</script>