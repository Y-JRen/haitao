{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
订单号：<input type="text" name="batch_sn" size="20" maxLength="50" value="{{$param.batch_sn}}"/>
收货人：<input type="text" name="addr_consignee" size="10" maxLength="50" value="{{$param.addr_consignee}}"/>
赠送人：<input type="text" name="giftbywho" size="10" maxLength="50" value="{{$param.giftbywho}}"/>
核销状态：
<select name="clear_pay" id="clear_pay">
  <option value="">请选择...</option>
  <option value="0" {{if $param.clear_pay eq '0'}}selected{{/if}}>未核销</option>
  <option value="1" {{if $param.clear_pay eq '1'}}selected{{/if}}>已核销</option>
</select>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">客情订单核销</div>
<form name="myForm" id="myForm">
<div class="content">
  <div style="float:left">
  <input type="button" name="submit" value="选中订单核销" onclick="check(this.form)">
  </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="50"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'batch_sns',this)"/></td>
            <td>订单号</td>
            <td>赠送人</td>
            <td>收货人</td>
            <td>下单日期</td>
            <td>订单状态</td>
            <td>核销状态</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr>
        <td>
          {{if $data.clear_pay eq '0'}}<input type="checkbox" name="batch_sns[]" value="{{$data.batch_sn}}"/>{{/if}}
        </td>
        <td>{{$data.batch_sn}}</td>
        <td>{{$data.giftbywho}}</td>
        <td>{{$data.addr_consignee}}</td>
        <td>{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
        <td>
          {{if $data.status_logistic eq 0}}未确认
		  {{elseif $data.status_logistic eq 1}}已确认待收款
		  {{elseif $data.status_logistic eq 2}}待发货
		  {{elseif $data.status_logistic eq 3}}已发货
		  {{elseif $data.status_logistic eq 4}}客户已签收
		  {{elseif $data.status_logistic eq 5}}客户已拒收
		  {{/if}}
        </td>
        <td>
          {{if $data.clear_pay}}
            已核销
          {{else}}
            未核销
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
function check(form)
{
    ajax_submit(form, '{{url}}','Gurl(\'refresh\')');
}
</script> 