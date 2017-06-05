<form id="searchForm" action="/admin/payment/list">
<div class="search">
启用状态：<select name="status">
<option value="" selected>请选择</option>
<option value="1" {{if $param.status eq '1'}}selected{{/if}}>未启用</option>
<option value="0" {{if $param.status eq '0'}}selected{{/if}}>启用</option>
</select>
支付方式：<input type="text" name="name" size="10" maxLength="50" value="{{$param.name}}"/>
支付代码：<input type="text" name="pay_type" size="10" maxLength="50" value="{{$param.pay_type}}"/>
支付方式类型：<select id="is_bank"   name="is_bank">
     <option value="" selected >请选择</option>
        <option value="0"  {{if $param.is_bank eq '0'}} selected="selected" {{/if}} >支付网关</option>
        <option value="1"  {{if $param.is_bank eq '1'}} selected="selected" {{/if}} >支付网关银行</option>
        <option value="2"  {{if $param.is_bank eq '2'}} selected="selected" {{/if}} >银行直连</option>
    </select>  
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="title">支付方式管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=addform}}')">添加支付方式</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>排序</td>
            <td>支付方式类型</td>
            <td>支付方式名称</td>
            <td>支付编码</td>
            <td>支付费率</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$data item=item}}
    <tr id="ajax_list{{$item.id}}">
        <td> {{$item.id}}</td>
        <td><input type="text" name="update" size="4" value="{{$item.sort}}" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$item.id}},'sort',this.value)"></td>
       <td>  
            {{if $item.is_bank eq 0}} 支付网关
            {{elseif $item.is_bank eq 1}} 支付网关银行
            {{elseif $item.is_bank eq 2}} 银行直连 {{/if}}
       </td>
        <td><input type="text" name="update" size="30" value="{{$item.name}}"  onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$item.id}},'name',this.value)"></td>
        <td>  {{$item.pay_type}}</td>
        <td><input type="text" name="update" size="30" value="{{$item.fee}}" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$item.id}},'fee',this.value)"></td>
        <td>{{if $item.status==1}}未启用{{else}}启用{{/if}}</td>
        <td>
		<a href="javascript:fGo()" onclick="G('{{url param.action=editform param.id=$item.id}}')">编辑</a> | 
		<a href="javascript:fGo()" onclick="reallydelete('{{url param.action=del}}','{{$item.id}}','{{url param.action=list}}')">删除</a>
	</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>