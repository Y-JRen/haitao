{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
下单日期：<input type="text" name="fromdate" id="fromdate" size="12" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
- <input  type="text" name="todate" id="todate" size="12" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
发货日期：<input type="text" name="sync_fromdate" id="sync_fromdate" size="12" value="{{$param.sync_fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
- <input  type="text" name="sync_todate" id="sync_todate" size="12" value="{{$param.sync_todate}}"  class="Wdate"  onClick="WdatePicker()"/>
当前店铺：
  <select name="shop_id" id="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=data}}
      {{if $data.shop_type ne 'tuan' && $data.shop_type ne 'jiankang' && $data.shop_type ne 'credit'}}
      <option value="{{$data.shop_id}}" {{if $data.shop_id eq $param.shop_id}}selected{{/if}}>{{$data.shop_name}}</option>
      {{/if}}
    {{/foreach}}
  </select>
<br><br>
订单号码：<input type="text" name="external_order_sn" size="20" maxLength="50" value="{{$param.external_order_sn}}"/>
销账状态：
<select name="write_off" id="write_off">
  <option value="">请选择...</option>
  <option value="1" {{if $param.write_off eq '1'}}selected{{/if}}>未销账</option>
  <option value="2" {{if $param.write_off eq '2'}}selected{{/if}}>已销账</option>
</select>
结算状态：
<select name="is_settle" id="write_off">
  <option value="">请选择...</option>
  <option value="0" {{if $param.is_settle eq '0'}}selected{{/if}}>未结算</option>
  <option value="1" {{if $param.is_settle eq '1'}}selected{{/if}}>已结算</option>
</select>
订单状态：
  <input type="checkbox" name="status" value="2" {{if $param.status.2}}checked{{/if}}>待发货
  <input type="checkbox" name="status" value="3" {{if $param.status.3}}checked{{/if}}>待确认收货
  <input type="checkbox" name="status" value="10" {{if $param.status.10}}checked{{/if}}>已完成
  <input type="checkbox" name="status" value="11" {{if $param.status.11}}checked{{/if}}>已取消
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">渠道订单销账</div>
<form name="myForm" id="myForm">
<div class="content">
  <div style="float:left">
  <input type="button" name="submit" value="选中订单销账" onclick="check(this.form)">
  </div>
  <div style="text-align:right;float:right">
    <b>刷单总金额：{{$amount}}</b>
    &nbsp;&nbsp;&nbsp;
  </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="50"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/></td>
            <td>订单号</td>
            <td>店铺</td>
            <td>订单金额</td>
            <td>下单日期</td>
            <td>订单状态</td>
            <td>销账状态</td>
            <td>销账时间</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.shop_order_id}}">
        <td>
          {{if $data.is_fake eq '1'}}<input type="checkbox" name="ids[]" value="{{$data.shop_order_id}}"/>{{/if}}
        </td>
        <td>{{$data.external_order_sn}}</td>
        <td>{{$data.shop_name}}</td>
        <td>
          {{$data.amount}}
          {{if $data.is_fake eq '1'}}<input type="hidden" name="amount[]" value="{{$data.amount}}">{{/if}}
        </td>
        <td>{{$data.order_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
        <td>
          {{if $data.status eq 1}}待收款
		  {{elseif $data.status eq 2}}待发货
		  {{elseif $data.status eq 3}}待确认收货
		  {{elseif $data.status eq 10}}已完成
		  {{elseif $data.status eq 11}}已取消
		  {{elseif $data.status eq 12}}其它
		  {{/if}}
        </td>
        <td>
          {{if $data.is_fake eq '1'}}未销账
          {{elseif $data.is_fake eq '2'}}已销账
          {{/if}}
        </td>
        <td>{{if $data.fake_time}}{{$data.fake_time|date_format:"%Y-%m-%d %H:%M:%S"}}{{else}}&nbsp;{{/if}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
 </form>
 
<script>
function check(form)
{
    var ids_obj = document.getElementsByName('ids[]');
    var amount_obj = document.getElementsByName('amount[]');
    var amount = 0;
    var flag = false;
    for (i = 0; i < ids_obj.length; i++) {
        if (ids_obj[i].checked) {
            amount += parseFloat(amount_obj[i].value);
            flag = true;
        }
    }
    if (flag) {
        if (confirm('销账金额：' + amount + '，确定要销账吗？')) {
            ajax_submit(form, '{{url}}','Gurl(\'refresh\')');
        }
    }
    
    return false;
}
</script> 