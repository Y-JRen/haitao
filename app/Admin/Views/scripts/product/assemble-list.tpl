{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm">
<div class="search">

制单开始日期：<input type="text" name="start_ts" id="start_ts" size="11" value="{{$params.start_ts|default:''}}"  class="Wdate" onClick="WdatePicker()"/>
制单结束日期：<input type="text" name="end_ts" id="end_ts" size="11" value="{{$params.end_ts|default:''}}" class="Wdate" onClick="WdatePicker()"/>
审核开始日期：<input type="text" name="audit_start_ts" id="audit_start_ts" size="11" value="{{$params.audit_start_ts|default:''}}" class="Wdate" onClick="WdatePicker()"/>
审核结束日期：<input type="text" name="audit_end_ts" id="audit_end_ts" size="11" value="{{$params.audit_end_ts|default:''}}" class="Wdate" onClick="WdatePicker()"/>
</div>
<div class="line">
<select name="status">
  <option value="">选择单据状态</option>
  {{foreach from=$search_option.assemble_status key=key item=status}}
  <option value="{{$key}}" {{if $params.status != '' && $params.status eq $key}}selected{{/if}}>{{$status}}</option>
  {{/foreach}}
</select>
<select name="type">
  <option value="">请选择类型</option>
  {{foreach from=$search_option.assemble_type key=key item=type}}
  <option value="{{$key}}" {{if $params.type != '' && $params.type eq $key}}selected{{/if}}>{{$type}}</option>
  {{/foreach}}
</select>
组装单编号：<input type="text" name="assemble_sn" size="20" maxLength="20" value="{{$params.assemble_sn}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.search=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的出库单" onclick="ajax_search(this.form,'{{url param.search=search1 param.lock=1}}','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的出库单" onclick="ajax_search(this.form,'{{url param.search=search1 param.lock=0}}','ajax_search')"/>
</div>
</form>
{{/if}}
<div id="ajax_search">
<div class="title">组装开单管理  -&gt; 组装开单审核</div>
<form name="myForm" id="myForm">
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>操作</td>
            <td>单号</td>
            <td>类型</td>
            <td>状态</td>
            <td>生成时间</td>
            <td>备注</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$infos item=info}}
        <tr id="ajax_list{{$info.assemble_id}}">
            <td>
                <input type="button" onclick="openDiv('{{url param.action=assemble-check param.assemble_id=$info.assemble_id param.query_type=assemble_list}}','ajax','查看单据',750,400)" value="查看">
            </td>
            <td>{{$info.assemble_sn}}</td>
            <td>{{$info.type_name}}</td>
            <td>{{$info.status_name}}</td>
            <td>{{$info.created_ts}}</td>
            <td>{{$info.remark}}</td>
        </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
</div>