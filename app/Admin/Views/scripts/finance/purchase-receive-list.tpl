{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
<span style="float:left;line-height:18px;">出库开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">出库结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
&nbsp;供货商：<select name="supplier_id" msg="请选择供货商" class="required">
                <option value="">请选择...</option>
                {{foreach from=$supplier item=s}}
		        {{if $s.status==0}}
		 	    <option value="{{$s.supplier_id}}" {{if $param.supplier_id eq $s.supplier_id}}selected{{/if}}>{{$s.supplier_name}}</option>
		        {{/if}}
                {{/foreach}}
              </select>
<br><br>
&nbsp;收款状态：
<select name="status" id="status">
  <option value="">请选择...</option>
  <option value="0" {{if $param.status eq '0'}}selected{{/if}}>未收款</option>
  <option value="1" {{if $param.status eq '1'}}selected{{/if}}>部分收款</option>
  <option value="2" {{if $param.status eq '2'}}selected{{/if}}>已收款</option>
</select>
&nbsp;发票状态：
<select name="invoice" id="invoice">
  <option value="">请选择...</option>
  <option value="0" {{if $param.invoice eq '0'}}selected{{/if}}>未寄</option>
  <option value="1" {{if $param.invoice eq '1'}}selected{{/if}}>已寄</option>
</select>
出库单号：<input type="text" name="bill_no" size="20" maxLength="50" value="{{$param.bill_no}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">结款订单查询</div>
<form name="myForm" id="myForm">
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>操作</td>
            <td>出库单号</td>
            <td>供应商</td>
            <td>应收金额</td>
            <td>实收金额</td>
            <td>出库日期</td>
            <td>收款状态</td>
            <td>发票</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.id}}">
        <td>
	      <input type="button" onclick="openDiv('/admin/finance/purchase-receive/id/{{$data.id}}','ajax','{{if $data.status eq 2}}查看{{else}}收款{{/if}}',600,400,true)" value="{{if $data.status eq 2}}查看{{else}}收款{{/if}}">
        </td>
        <td>{{$data.bill_no}}</td>
        <td>{{$data.supplier_name}}</td>
        <td>{{$data.amount }}</td>
        <td>{{$data.real_amount}}</td>
        <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
        <td>
          {{if $data.status eq '0'}}未收款
          {{elseif $data.status eq '1'}}部分收款
          {{elseif $data.status eq '2'}}已收款
          {{/if}}
        </td>
        <td id="invoice_{{$data.bill_no}}">
          {{if $data.invoice eq '0'}}<a href="javascript:;void(0)" onclick="changeInvoice('{{$data.bill_no}}')">未寄</a>
          {{elseif $data.invoice eq '1'}}已寄
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
function changeInvoice(bill_no)
{
    new Request({url: '/admin/finance/purchase-change-invoice/bill_no/' + bill_no + '/type/2',
                method:'get' ,
                evalScripts:true,
                onSuccess: function(responseText) {
                    $('invoice_' + bill_no).innerHTML = responseText;
                }
    }).send();
}
</script>