{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>

支付方式：<select name="pay_type" id="pay_type">
          <option value="" >请选择 </option>
            {{foreach from=$payment_list item=payment }}
              {{if $payment.pay_type ne 'external' && $payment.pay_type ne 'cod'}}
              <option value="{{$payment.pay_type}}" {{if $param.pay_type eq $payment.pay_type}}selected{{/if}}>  {{$payment.name}} </option>
              {{/if}}
            {{/foreach}}
            <option value="bank" {{if $param.pay_type eq 'bank'}}selected{{/if}}>银行打款</option>
            <option value="cash" {{if $param.pay_type eq 'cash'}}selected{{/if}}>现金支付</option>
		</select>
订单号码：<input type="text" name="batch_sn" size="18" maxLength="50" value="{{$param.batch_sn}}"/>
制单人：<input type="text" name="admin_name" size="10" maxLength="20" value="{{$param.admin_name}}"/>
结算单号：<input type="text" name="clear_no" size="18" maxLength="50" value="{{$param.clear_no}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">在线支付订单货款结算 -&gt; 结算单查询</div>
<form name="myForm" id="myForm">
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>操作</td>
            <td>支付方式</td>
            <td>结算单</td>
            <td>实际返款</td>
            <td>佣金</td>
            <td>调整金额</td>
            <td>结算日期</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.id}}">
        <td>
			<input type="button" onclick="openDiv('{{url param.action=view-clear param.id=$data.id }}','ajax','查看单据')" value="查看">
        </td>
        <td>{{if $payment_list[$data.pay_type].name}}{{$payment_list[$data.pay_type].name}}{{elseif $data.pay_type eq 'bank'}}银行打款{{elseif $data.pay_type eq 'cash'}}现金支付{{/if}}</td>
        <td>{{$data.clear_no}}</td>
        <td>{{$data.real_amount}}</td>
        <td>{{$data.commission}}</td>
        <td>{{$data.adjust_amount}}</td>
        <td>{{$data.clear_time|date_format:"%Y-%m-%d"}}</td>
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