<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	{{html_options options=$billType selected=$param.bill_type}}
</select>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	{{html_options options=$logisticList selected=$param.logistic_code}}
</select>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" {{if $param.is_cod eq '0'}}selected{{/if}}>非货到付款</option><option value="1" {{if $param.is_cod eq '1'}}selected{{/if}}>货到付款</option></select>
<br>
匹配类型：<select name="search_mod"><option value="">请选择</option><option value="排除法" {{if $param.search_mod eq '排除法'}}selected{{/if}}>排除法</option><option value="匹配法" {{if $param.search_mod eq '匹配法'}}selected{{/if}}>匹配法</option></select>
维护状态：<select name="is_protect"><option value="">请选择</option><option value="0" {{if $param.is_protect eq '0'}}selected{{/if}}>未维护</option><option value="1" {{if $param.is_protect eq '1'}}selected{{/if}}>已维护</option></select>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="{{$param.bill_no}}"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
<br>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="G('{{url param.is_lock=yes}}'+location.search)"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="G('{{url param.is_lock=no}}'+location.search)"/>
</div>
</form>
<div class="title">配送管理 -&gt; 关键字维护</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>单据类型</td>
            <td>付款方式</td>
            <td>匹配类型</td>
            <td>物流公司</td>
            <td>发货日期</td>
            <td>维护状态</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.sid}}">
        <td><input type="checkbox" name="ids[]" value="{{$data.tid}}"/></td>
        <td>
			<input type="button" onclick="openDiv('{{url param.action=protect param.id=$data.tid}}','ajax','关键字维护')" value="查看">
        </td>
        <td>{{$data.bill_no}}</td>
        <td>{{$billType[$data.bill_type]}}</td>
        <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
        <td>{{$data.search_mod}}</td>
        <td>{{$data.logistic_name}}</td>
        <td>{{$data.send_time|date_format:"%Y-%m-%d"}}</td>
        <td>{{if $data.is_protect}}已维护{{else}}未维护{{/if}}</td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>

<div class="page_nav">{{$pageNav}}</div>
</form>
