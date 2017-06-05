{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm">
<div class="search">
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
<div class="line">
<span style="float:left;line-height:18px;">开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"   class="Wdate" onClick="WdatePicker()" /></span>
<span style="float:left;line-height:18px;">结束日期：<input type="text" name="todate" id="todate" size="15" value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()" /></span>
<select name="bill_status">
<option value="">选择单据状态</option>
<option value="0" {{if $param.bill_status eq '0'}}selected{{/if}}>未审核</option>
<option value="1" {{if $param.bill_status eq '1'}}selected{{/if}} >已审核</option>
<option value="2" {{if $param.bill_status eq '2'}}selected{{/if}} >已拒绝</option>
</select>
制单人：<input type="text" name="admin_name" size="10" maxLength="20" value="{{$param.admin_name}}" />
单据编号：<input type="text" name="bill_no" size="20" maxLength="20" value="{{$param.bill_no}}" />
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</div>
</div>
</form>
{{/if}}
<div id="ajax_search">
<div class="title">商品管理 -&gt; {{$area_name}} -&gt; 商品成本管理 -&gt;调整成本查询</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>制单人</td>
            <td>制单日期</td>
            <td>单据状态</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.sid}}">
        <td><input type="checkbox" name="ids[]" value="{{$data.bill_id}}"/></td>
        <td>
			<input type="button" onclick="openDiv('{{url param.action=checkdetail param.id=$data.bill_id param.flag=1}}','ajax','查看单据',750,400)" value="查看">
        </td>
        <td>{{$data.bill_sn}}</td>
        <td>{{$data.admin_name}}</td>
        <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
        <td>
            {{if $data.stauts==0}}
            	未审核
            {{elseif $data.stauts==1}}
            	已审核
            {{else}}
          		已拒绝
            {{/if}}
        </td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>

<div class="page_nav">{{$pageNav}}</div>
</form>
</div>