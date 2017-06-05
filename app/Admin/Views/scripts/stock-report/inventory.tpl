<form name="searchForm" id="searchForm" action="{{url}}">
<div class="search">
选择仓库
<select name="logic_area" onchange="$('searchForm').submit()">
{{foreach from=$areas key=key item=item}}
{{if $param.logic_area eq $key}}
{{if !$param.only_distribution || $key > 20}}
{{/if}}
<option value="{{$key}}" {{if $param.logic_area eq $key}}selected{{/if}}>{{$item}}</option>
{{/if}}
{{/foreach}}
</select>
选择库存状态
<select name="status_id" onchange="$('searchForm').submit()">
{{foreach from=$status key=key item=item}}
<option value="{{$key}}" {{if $param.status_id eq $key}}selected{{/if}}>{{$item}}</option>
{{/foreach}}
</select>
<br><br>
{{$catSelect}}
产品编码：<input type="text" name="product_sn" size="10" maxLength="50" value="{{$param.product_sn}}"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="{{$param.product_name}}"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
</form>

<div class="title">库存管理 -&gt; 调整库存盘点</div>
<div class="content">
    {{if !$param.only_distribution}}
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add-inventory-xls}}')">导入盘点表格</a> ]
    </div>
    {{/if}}
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>仓库</td>
            <td>产品ID</td>
            <td>产品编码</td>
            <td>产品名称</td>
            <td>批次</td>
            <td>货位</td>
            <td>库存状态</td>
            <td>实际库存</td>
            <td>在途库存</td>
            <td>占用库存</td>
            {{if $param.logic_area > 20}}<td>实际盘点数</td>{{/if}}
        </tr>
    </thead>
    <tbody>
    {{if $datas}}
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$areas[$param.logic_area]}}</td>
        <td>{{$data.product_id}}</td>
        <td>{{$data.product_sn}}</td>
        <td>{{$data.product_name}}<font color="#FF0000">({{$data.goods_style}})</font></td>
        <td>{{if $data.batch_no}}{{$data.batch_no}}{{else}}无批次{{/if}}</td>
        <!--<td><input type="text" id="local_sn_{{$data.product_id}}" value="{{$data.local_sn}}" size="12" onblur="changeLocalSN({{$data.product_id}}, this.value)"></td>-->
        <td>{{$data.position_no}}</td>
        <td>{{$status[$param.status_id]}}</td>
        <td>{{$data.real_number}}</td>
        <td>{{$data.wait_number}}</td>
        <td>{{$data.hold_number}}</td>
        {{if $param.logic_area > 20}}
        <td>
          <input name="product_id" type="hidden" value="{{$data.product_id}}" />
          <input name="batch_id" type="hidden" value="{{$data.batch_id}}" />
          <input id="number_{{$data.product_id}}_{{$data.batch_id}}" type="text" size="3" value="0" />
          <input type="button" name="btn" onclick="inventory('{{$param.logic_area}}', '{{$data.product_id}}', '{{$data.batch_id}}', '{{$param.status_id}}', 2)"  value="调整">
          <!--
          {{if $groupID eq 1}}
          <input type="button" name="btn" onclick="inventory('{{$param.logic_area}}', '{{$data.product_id}}', '{{$data.batch_id}}', '{{$param.status_id}}', 1)"  value="初始化">
          {{/if}}
          -->
          <input type="text" id="remark_{{$data.product_id}}_{{$data.batch_id}}" value="备注" style="width:100%" onclick="if (this.value=='备注') this.value = '';"> 
        </td>
        {{/if}}
    </tr>
    {{/foreach}}
    {{/if}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
//同步
function inventory(logic_area, product_id, batch_id, status_id, type){
    if (type == 1)  str = '你确认要初始化吗？';
    else    str = '你确认要调整吗？';
	if(confirm(str)){
		if (!logic_area || !product_id || !status_id) {
		    alert('参数错误');
		    return false;
		}
		var number = $('number_' + product_id + '_' + batch_id).value;
		var remark = $('remark_' + product_id + '_' + batch_id).value;
		if (remark == '备注') remark = '';
	    new Request({
					 url:'/admin/stock-report/do-inventory/type/' + type + '/logic_area/' + logic_area + '/product_id/' + product_id + '/batch_id/' + batch_id + '/status_id/' + status_id + '/number/' + number + '/remark/' + encodeURI(remark),
					 onSuccess:function(msg){
							if(msg == 'ok'){
							    if (type == 1) {
								    alert('初始化成功');
								}
								else {
								    alert('调整成功');
								}
								location.reload();
							}
							else {
								alert(msg);
							}
						},
						onError:function() {
							alert("网络繁忙，请稍后重试");
						}
					}).send();
			
	}
}
function changeLocalSN(product_id, value)
{
    new Request({
        url:'/admin/stock-report/update-local-sn/product_id/' + product_id + '/local_sn/' + value,
	    onSuccess:function(msg){
            
		},
		onError:function() {
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
</script>