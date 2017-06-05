<form name="searchForm" id="searchForm" method="get">
<div class="search">
{{$catSelect}}
上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" {{if $param.onsale eq 'on'}}selected{{/if}}>上架</option><option value="off" {{if $param.onsale eq 'off'}}selected{{/if}}>下架</option></select>
编码：<input type="text" name="goods_sn" size="20" maxLength="50" value="{{$param.goods_sn}}"/>
名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}"/>
<input type="button" name="dosearch"   id="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<!--
<input type="button" onclick="G('/admin/goods/sku-export/?{{$smarty.server.QUERY_STRING}}')" value="导出商品库存信息">
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
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.goods_id}}">
        <td>{{$data.goods_id}}</td>
        <td>{{$data.goods_sn}}</td>
        <td>{{$data.goods_name}}</td>
        <td>{{$data.market_price}}</td>
        <td>{{$data.price}}</td>
        <td>{{$data.able_number|default:0}}</td>
        <td id="ajax_status{{$data.goods_id}}">
          {{if $data.first_char ne '4'}}
			{{if $data.price neq 0}}
			  {{$data.status}}
			  {{if $data.onoff_remark}}
				({{$data.onoff_remark}})
			  {{/if}}
			{{/if}}
		  {{/if}}
		</td>
		<!--
		<td id="ajax_status{{$data.goods_id}}">
          {{if $data.first_char ne '4'}}
			{{if $data.staff_price neq 0}}
			  {{$data.status2}}
			  {{if $data.onoff_remark2}}
				({{$data.onoff_remark2}})
			  {{/if}}
			{{/if}}
		  {{/if}}
		</td>
		-->
        <td><a href="javascript:;void(0)" onclick="openDiv('/admin/goods/status-history/goods_id/{{$data.goods_id}}','ajax','查看',500,300,true)">查看</a></td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
	<div style="float:left;width:500px;">
	</div>
	</form>
</div>
<div class="page_nav">{{$pageNav}}</div>
