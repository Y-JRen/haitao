{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm">
<div class="search">
<input type="button" name="dosearch2" value="所有被我锁定的出库单" onclick="ajax_search(this.form,'{{url param.do=search param.is_lock=yes}}','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的出库单" onclick="ajax_search(this.form,'{{url param.do=search param.is_lock=no}}','ajax_search')"/>
</div>
</form>
{{/if}}
<div id="ajax_search">
<div class="title">物流管理 -&gt; 拣配区管理 -&gt; {{$actions.$action}}</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">


<input type="button" value="批量发货" onclick="ajax_submit(this.form, '{{url param.action=batch-send}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">

</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
        	<td width="30">全选</td>
            <td>单据编号</td>
            <td>单据类型</td>
            <td>制单人</td>
            <td>制单日期</td>
            <td>是否锁定</td>
             <td>物流单号</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.outstock_id}}">
    	<td><input type="checkbox" name="ids[]" value="{{$data.outstock_id}}"/></td>
        <td>{{$data.bill_no}}</td>
        <td>{{$billType[$data.bill_type]}}</td>
        <td>{{$data.admin_name}}</td>
        <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
        <td>{{$data.logistic_no}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">
</div>
</div>

<div class="page_nav">{{$pageNav}}</div>
</form>
</div>