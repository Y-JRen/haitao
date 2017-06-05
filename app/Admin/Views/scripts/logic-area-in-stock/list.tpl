{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
{{if $logic_area > 20}}
代销仓：
<select name="logic_area" id="logic_area" onchange="ajax_search($('searchForm'), '{{url}}', 'ajax_search')">
  {{foreach from=$areas key=key item=item}}
    {{if $key > 20}}
    <option value="{{$key}}" {{if $key eq $logic_area}}selected{{/if}}>{{$item}}</option>
    {{/if}}
  {{/foreach}}
</select>
&nbsp;&nbsp;
{{/if}}
{{$catSelect}}
商品编码：<input type="text" name="product_sn" size="12" maxLength="50" value="{{$param.product_sn}}"/>
商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}"/>
<div class="line">
制单开始日期：<input type="text" name="fromdate" id="fromdate" size="11" value="{{$param.fromdate|default:''}}"  class="Wdate" onClick="WdatePicker()"/>
制单结束日期：<input type="text" name="todate" id="todate" size="11" value="{{$param.todate|default:''}}" class="Wdate" onClick="WdatePicker()"/>
入库开始日期：<input type="text" name="fromdate2" id="fromdate2" size="11" value="{{$param.fromdate2|default:''}}" class="Wdate" onClick="WdatePicker()"/>
入库结束日期：<input type="text" name="todate2" id="todate2" size="11" value="{{$param.todate2|default:''}}" class="Wdate" onClick="WdatePicker()"/>
</div>
<div class="line">
<select name="bill_type">
<option value="">选择单据类型</option>
  {{if $billType}}
  {{foreach from=$billType key=key item=item}}
  <option value="{{$key}}" {{if $param.bill_type eq $key}}selected{{/if}}>{{$item}}</option>
  {{/foreach}}
  {{/if}}
</select>
<select name="bill_status">
  <option value="">选择单据状态</option>
  {{if $billStatus}}
  {{foreach from=$billStatus key=key item=item}}
  <option value="{{$key}}" {{if $param.bill_status != '' && $param.bill_status eq $key}}selected{{/if}}>{{$item}}</option>
  {{/foreach}}
  {{/if}}
</select>

 供货商:  <select name="supplier_id" msg="请选择供货商" class="required" id="supplier_id" >
             <option value="">请选择...</option>
		  {{foreach from=$supplier item=s}}
			  <option value="{{$s.supplier_id}}"    {{if $param.supplier_id eq $s.supplier_id}}selected{{/if}}    >{{$s.supplier_name}}</option>
		  {{/foreach}}
		  </select>

制单人：<input type="text" name="admin_name" size="10" maxLength="20" value="{{$param.admin_name}}"/>
单据编号：<input type="text" name="bill_no" size="20" maxLength="20" value="{{$param.bill_no}}"/>
接收者：<input type="text" name="recipient" size="10" maxLength="20" value="{{$param.recipient}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="ajax_search(this.form,'{{url param.do=search param.is_lock=yes}}','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="ajax_search(this.form,'{{url param.do=search param.is_lock=no}}','ajax_search')"/>
</div>
</form>
{{/if}}
<div id="ajax_search">
<div class="title">仓储管理 -&gt; {{$area_name}} -&gt; {{$actions.$action}}</div>
<form name="myForm" id="myForm">
<div class="content">
   {{if $auth.group_id eq 1 || $auth.group_id eq 3 ||  $auth.group_id eq 10 || $auth.group_id eq 11}}
    <div style="text-align:right;">
    <b>总金额：{{$sum.amount}}&nbsp;&nbsp;&nbsp;总金额(未税)：{{$sum.no_tax_amount}}&nbsp;&nbsp;&nbsp;实收金额：{{$sum.real_amount}}&nbsp;&nbsp;&nbsp;实收金额(未税)：{{$sum.real_no_tax_amount}}&nbsp;&nbsp;&nbsp;</b>
    </div>
    {{/if}}
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>单据类型</td>
			<td>供应商</td>
            <td>接收方</td>
            <td>制单人</td>
            <td>制单日期</td>
            <td>入库日期</td>
            <td>单据状态</td>
            <td>是否锁定</td>
            <td>备注</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.instock_id}}">
        <td><input type="checkbox" name="ids[]" value="{{$data.instock_id}}"/></td>
        <td>
			{{if $action eq 'setover-list'}}
			<input type="button" onclick="openDiv('{{url param.action=setover param.id=$data.instock_id}}','ajax','查看单据',750,400)" value="查看">
			{{else}}
			<input type="button" onclick="openDiv('{{url param.action=$operates.$action param.id=$data.instock_id}}','ajax','查看单据',850,450)" value="查看">
			{{/if}}
        </td>
        <td>
          {{$data.bill_no}}
          {{if $data.item_no}}<br>({{$data.item_no}}){{/if}}
        </td>
        <td>{{$billType[$data.bill_type]}}{{if $data.bill_type eq 2}}({{if $data.purchase_type eq 1}}经销{{else}}代销{{/if}}){{/if}}</td>
	    <td>{{$data.supplier_name}}</td>
        <td>{{if $areas[$data.recipient]}}{{$areas[$data.recipient]}}{{else}}{{$data.recipient}}{{/if}}</td>
        <td>{{$data.admin_name}}</td>
        <td>{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
        <td>{{if $data.finish_time}}{{$data.finish_time|date_format:"%Y-%m-%d %H:%M:%S"}}{{/if}}</td>
        <td>
            {{if $data.is_cancel==1}}
            待取消
            {{else}}
            {{$billStatus[$data.bill_status]}}
            {{/if}}
        </td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
        <td> <textarea name="" cols="18" rows="2"> {{$data.remark}} </textarea></td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>

<div class="page_nav">{{$pageNav}}</div>
</form>
</div>