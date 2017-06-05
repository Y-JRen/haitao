{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
下单日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
    - <input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
结算日期：<input type="text" name="clear_fromdate" id="clear_fromdate" size="12" value="{{$param.clear_fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
    - <input  type="text" name="clear_todate" id="clear_todate" size="12" value="{{$param.clear_todate}}"  class="Wdate"  onClick="WdatePicker()"/>
&nbsp;当前店铺：
  <select name="shop_id" id="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=data}}
      {{if $data.shop_type ne 'jiankang' && $data.shop_type ne 'credit'}}
      <option value="{{$data.shop_id}}" {{if $data.shop_id eq $param.shop_id}}selected{{/if}}>{{$data.shop_name}}</option>
      {{/if}}
    {{/foreach}}
  </select>
&nbsp;结款状态：
<select name="is_settle" id="is_settle">
  <option value="">请选择...</option>
  <option value="0" {{if $param.is_settle eq '0'}}selected{{/if}}>未结款</option>
  <option value="1" {{if $param.is_settle eq '1'}}selected{{/if}}>已结款</option>
</select>
<br><br>
刷单状态：
<select name="status" id="status">
  <option value="">请选择...</option>
  <option value="3" {{if $param.status eq '3'}}selected{{/if}}>是</option>
  <option value="0" {{if $param.status eq '0'}}selected{{/if}}>否</option>
</select>
&nbsp;刷单核销状态：
<select name="is_fake" id="is_fake">
  <option value="">请选择...</option>
  <option value="1" {{if $param.is_fake eq '1'}}selected{{/if}}>未核销</option>
  <option value="2" {{if $param.is_fake eq '2'}}selected{{/if}}>已核销</option>
</select> 
渠道订单号：<input type="text" name="order_sn" size="16" maxLength="50" value="{{$param.order_sn}}"/>
官网订单号：<input type="text" name="batch_sn" size="16" maxLength="50" value="{{$param.batch_sn}}"/>
结款单号：<input type="text" name="clear_no" size="16" maxLength="50" value="{{$param.clear_no}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">结款订单查询 [<a href="{{url param.todo=export}}" target="_blank">导出订单</a>]</div>
<form name="myForm" id="myForm">
<div class="content">
    <div style="text-align:right;">
    <b>订单金额：{{$total.amount}}&nbsp;&nbsp;&nbsp;佣金：{{$total.commission}}&nbsp;&nbsp;&nbsp;结算金额：{{$total.amount-$total.commission}}</b>
    &nbsp;&nbsp;&nbsp;
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>操作</td>
            <td>店铺名称</td>
            <td>渠道订单号</td>
            <td>官网订单号</td>
            <td>下单日期</td>
            <td>订单金额</td>
            <td>佣金</td>
            <td>结算状态</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.id}}">
        <td>
	      <input type="button" onclick="window.open('/admin/order/info/batch_sn/{{$data.batch_sn}}')" value="查看">
        </td>
        <td>{{$data.shop_name}}</td>
        <td>{{$data.order_sn}}</td>
        <td>{{$data.batch_sn}}</td>
        <td>{{$data.order_time}}</td>
        <td>{{$data.amount}}</td>
        <td>{{$data.commission}}</td>
        <td>
          {{if $data.is_settle}}
            已结款
          {{else}}
            未结款
          {{/if}}
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
 </form>
<script language="JavaScript">
function getArea(id)
{
    var value = id.value;
    var select = $(id).getNext();
    new Request({
        url: '/admin/member/area/id/' + value,
        onRequest: loading,
        onSuccess:function(data){
            select.options.length = 1;
	        if (data != '') {
	            data = JSON.decode(data);
	            $each(data, function(item, index){
	                var option = document.createElement("OPTION");
                    option.value = index;
                    option.text  = item;
                    select.options.add(option);
	            });
	        }
            loadSucess();
        }
    }).send();
}
</script>