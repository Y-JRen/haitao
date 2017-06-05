<div class="search">
<form name="searchForm" id="searchForm" action="/admin/stock-report/warn">
仓库->{{$area_name}}    状态->正常
预警状态
<select name="warn">
<option value="1" {{if $param.warn eq '1'}}selected{{/if}}>预警</option>
<option value="2" {{if $param.warn eq '2'}}selected{{/if}}>未预警</option>
</select>

{{$catSelect}}
产品编码：<input type="text" name="product_sn" size="6" maxLength="20" value="{{$param.product_sn}}"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="{{$param.product_name}}"/>
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
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.product_id}}">
        <td>{{$data.product_id}}</td>
        <td>{{$data.product_sn}}</td>
        <td>{{$data.product_name}}</td>
        <td>{{$data.real_number}}</td>
        <td>{{$data.able_number}}</td>
        <td>{{$data.count30}}</td>
        <td>{{$data.count30avg}}</td>
        <td>{{$data.count7}}</td>
        <td>{{$data.count7avg}}</td>
        <td>{{if $data.warn eq 1}}<font color="red">库存预警</font>{{else}}库存正常{{/if}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>