<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form id="searchForm" action="/admin/goods/">
<input type="hidden" name="angle_id" value="{{$angle_id}}">
<div class="search">
<span style="float:left;line-height:18px;">添加开始日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">添加结束日期：</span>
<span>
<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
<input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value=''"/></span>
是否冻结删除
<select name="is_del" >
		<option value="0" {{if $param.is_del eq '0'}}selected{{/if}}>正常</option>
		<option value="1" {{if $param.is_del eq '1'}}selected{{/if}}>已冻结</option>
</select>
    分类：{{$catSelect}}
上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" {{if $param.onsale eq 'on'}}selected{{/if}}>上架</option><option value="off" {{if $param.onsale eq 'off'}}selected{{/if}}>下架</option></select>
<br><br>
分类名称：<input type="text" name="cat_name" size="10" maxLength="50" value="{{$param.cat_name}}"/>
商品名称：<input type="text" name="goods_name" size="10" maxLength="50" value="{{$param.goods_name}}"/>
编码：<input type="text" name="goods_sn" size="10" maxLength="50" value="{{$param.goods_sn}}"/>
品牌：<input type="text" name="brand_name" size="10" maxLength="50" value="{{$param.brand_name}}"/>
本店价：<input type="text" name="fromprice" size="5" maxLength="6" value="{{$param.fromprice}}"/>
- <input type="text" name="toprice" size="5" maxLength="6" value="{{$param.toprice}}"/>
<select name="orderby" onchange="searchForm.submit()">
  <option value="">排序方式</option>
  <option value="goods_add_time" {{if $param.orderby eq 'goods_add_time'}}selected{{/if}}>添加时间(升序)</option>
  <option value="price" {{if $param.orderby eq 'price'}}selected{{/if}}>本店价(升序)</option>
  <option value="price desc" {{if $param.orderby eq 'price desc'}}selected{{/if}}>本店价(降序)</option>
</select>
<input type="submit" name="dosearch" value="查询"/>
<input type="button" onclick="window.open('/admin/goods/export'+location.search)" value="导出商品资料">
</div>
</form>
<div class="title">商品管理  &nbsp; &nbsp; &nbsp;&nbsp;    
 </div>     
<div class="content">
    <div class="sub_title">
      [ <a href="javascript:fGo()" onclick="G('{{url param.action=goods-cat}}')">添加新品</a> ]
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
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.goods_id}}">
        <td><input type="text" name="update" size="2" value="{{$data.goods_sort}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.goods_id}},'goods_sort',this.value)"></td>
        <td>{{$data.goods_id}}</td>
        <td>{{$data.goods_sn}}</td>
		<td>{{$data.cat_name}}</td>
        <td>{{$data.goods_name|stripslashes}}</td>
        <td>{{$data.market_price}}</td>
        <td>{{$data.price}}</td>
		<td><input type="text" name="update" size="2" value="{{$data.limit_number}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.goods_id}},'limit_number',this.value)"></td>
        <td>{{$data.goods_status}}</td>
        <td>
			<a href="javascript:fGo()" onclick="openDiv('{{url param.controller=msg param.action=listgoodscomment param.goods_id=$data.goods_id param.job=view}}','ajax','{{$data.goods_name}}评论',750,400)">查看</a> | 
			<a href="javascript:fGo()" onclick="openDiv('{{url param.controller=msg param.action=goodscommentadd param.goods_id=$data.goods_id param.goods_name=$data.goods_name}}','ajax','{{$data.goods_name}} 添加评论',750,400)">添加</a>
		</td>
		<td><a href="javascript:fGo()" onclick="openDiv('{{url param.controller=msg param.action=goodsbuylog param.goods_id=$data.goods_id param.goods_name=$data.goods_name}}','ajax','{{$data.goods_name}} 购买记录',750,400)">查看/添加</a>
        </td>
        <td>
         <a href="javascript:fGo()" onclick="window.open('/shop/goods/show/id/{{$data.goods_id}}')">查看</a>
         | <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$data.goods_id}}')">编辑</a>  | 
        {{if $data.is_del eq 1}} 
         已冻结  <a href="javascript:fGo()" onclick="delGoods({{$data.goods_id}},0)">解冻</a> {{else}}  
         <a href="javascript:fGo()" onclick="delGoods({{$data.goods_id}},1)">冻结删除</a>
         {{/if}}
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>

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