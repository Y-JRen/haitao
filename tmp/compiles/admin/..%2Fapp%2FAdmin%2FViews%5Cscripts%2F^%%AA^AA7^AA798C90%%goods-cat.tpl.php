<?php /* Smarty version 2.6.19, created on 2014-08-13 09:27:18
         compiled from goods/goods-cat.tpl */ ?>
<div class="title">商品分类选择</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <tbody>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['cat_id']; ?>
">
        <td><form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array());?>" method="post">
            商品分类：<?php echo $this->_tpl_vars['viewcatSelect']; ?>

			商品编码：<input type="text" name="goods_sn" id="goods_sn" size="8" readonly>
			商品名称：<input type="text" name="goods_name" id="goods_name" size="40">
			<input name="submit"  value="添加商品" type="submit" /> 
		</form>
        </td>
    </tr>
    </tbody>
    </table>
</div>
<script>
function changeCat(value)
{
    new Request({
        url: '/admin/goods/get-goods--prefix-sn/catID/' + value + '/r/' + Math.random(),
        onRequest: loading,
        onSuccess:function(data) {
            if (data == 'error') {
                alert('必须选择底层分类！');
            }
            else {
                $('goods_sn').value = data;
            }
        }
    }).send();

}
</script>