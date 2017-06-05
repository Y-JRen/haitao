<form name="searchForm" id="searchForm">
<div class="search">
产品编码：<input type="text" name="product_sn" size="15" maxLength="50" value="{{$param.product_sn}}"/>
产品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}"/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">产品管理 -&gt; 礼品卡金额管理       </div>
<div class="content">
   <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>产品ID</td>
            <td>产品编码</td>
            <td>产品名称</td>
			<td>金额</td>
			<td>操作人</td>
			<td>添加时间</td>
            <td>修改时间</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$infos item=info}}
    <tr id="{{$info.product_id}}">
        <td>{{$info.product_id}}</td>
        <td>{{$info.product_sn}}</td>
        <td>{{$info.product_name}}</td>
		<td><input type="text" name="amount"  size="8" value="{{$info.amount}}" style="text-align:center;" onchange="changePrice('{{$info.product_id}}', this, '{{$info.amount}}')"></td>
        <td>{{$info.admin_name}}</td>
        <td>{{$info.created_ts}}</td>
        <td>{{$info.update_ts}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
<script>
    function changePrice(product_id,obj, origin_amount)
    {
        if (!confirm("确认更改价格吗?")) {
            return false;
        }
        if (parseInt(product_id) < 1) {
            alert('产品ID不正确');
            return false;
        }

        var amount = obj.value;

        if (isNaN(amount)) {
            alert('金额不正确');
            obj.value = origin_amount;
            obj.focus();
            return false;
        }

        if (Math.ceil(amount) <= 0) {
            alert('金额不能小于0');
            obj.value = origin_amount;
            return false;
        }
        new Request({
		url:'/admin/product/change-ajax-giftproduct/product_id/'+product_id+'/amount/'+ amount,
		onSuccess:function(data){
			data = JSON.decode(data);
            if (data.success == 'false') {
                alert(data.message);
                return false;
            } else {
                data = data.data;
                obj.value = data.amount;
                var tr = document.getElementById(product_id);
                tr.cells[4].innerHTML = data.admin_name;
                tr.cells[5].innerHTML = data.created_ts;
                tr.cells[6].innerHTML = data.update_ts;
            }
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
    }
</script>
