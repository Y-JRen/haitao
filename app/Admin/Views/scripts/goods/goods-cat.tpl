<div class="title">商品分类选择</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <tbody>
    <tr id="ajax_list{{$data.cat_id}}">
        <td><form name="myForm" id="myForm" action="{{url}}" method="post">
            商品分类：{{$viewcatSelect}}
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