{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
入库日期：<input type="text" name="fromdate" id="fromdate" size="12" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
- <input  type="text" name="todate" id="todate" size="12" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
直供商：
  <select name="shop_id" id="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=data}}
      {{if $data.shop_type eq 'distribution'}}
      <option value="{{$data.shop_id}}" {{if $data.shop_id eq $param.shop_id}}selected{{/if}}>{{$data.shop_name}}</option>
      {{/if}}
    {{/foreach}}
  </select>
入库单号：<input type="text" name="bill_no" size="20" maxLength="50" value="{{$param.bill_no}}"/>
销账状态：
<select name="write_off" id="write_off">
  <option value="">请选择...</option>
  <option value="0" {{if $param.write_off eq '0'}}selected{{/if}}>未销账</option>
  <option value="1" {{if $param.write_off eq '1'}}selected{{/if}}>已销账</option>
</select>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">直供刷单销账</div>
<div style="float:right;margin-top:10px;margin-right:10px"><b>销账金额：{{$sum.amount}}&nbsp;&nbsp;差额：{{$sum.settle_amount-$sum.amount|string_format:"%.2f"}}</b></div>
<form name="myForm" id="myForm">
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>入库单号</td>
            <td>直供商</td>
            <td>入库单供货价</td>
            <td>入库日期</td>
            <td>销账状态</td>
            <td>销账时间</td>
            <td>销账人</td>
            <td>销账金额 / 差额</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$data.batch_sn}}</td>
        <td>{{$data.shop_name}}</td>
        <td>{{$data.amount}}</td>
        <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
        <td>
          {{if $data.settle_time}}已销账
		  {{else}}未销账
		  {{/if}}
        </td>
        <td>{{if $data.settle_time}}{{$data.settle_time|date_format:"%Y-%m-%d"}}{{else}}&nbsp;{{/if}}</td>
        <td>{{$data.admin_name}}</td>
        <td>
          {{if $data.settle_time}}{{$data.settle_amount}} / {{$data.settle_amount-$data.amount|string_format:"%.2f"}}
          {{else}}<input type="text" id="amount_{{$data.batch_sn}}" value="0" size="4" style="text-align:center">
          {{/if}}
        </td>
        <td>
        {{if !$data.settle_time}}
          <input type="button" name="writeoff" value="销账" onclick="check('{{$data.batch_sn}}')">
        {{/if}}
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
 </form>
 
<script>
function check(batch_sn)
{
    var amount = $('amount_' + batch_sn).value;
    if (isNaN(amount) || amount <= 0) {
        alert('销账金额错误!');
        return;
    }
    
    new Request({
        url:'/admin/finance/distribution-write-off/batch_sn/' + batch_sn + '/amount/' + amount,
	    onSuccess:function(msg){
	        if (msg == 'error') {
	            alert('该入库单已销账!');
	        }
	        else {
	            $('searchForm').submit();
	        }
		},
		onError:function() {
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
</script> 