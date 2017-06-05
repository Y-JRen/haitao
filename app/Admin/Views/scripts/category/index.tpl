<div class="title">分类管理 ->产品分类</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=addcat}}')">添加分类</a> ] 	
		[ <a href="javascript:fGo()" onclick="G('{{url param.action=reflash-cache}}')">生成前台分类缓存</a> ]  
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>排序</td>
            <td>ID</td>
            <td>名称</td>
            <td>分类编码</td>
            <td>排序</td>  
		    <td>子分类</td>       
            <td>状态</td>
			<td>是否显示</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.cat_id}}">
        <td><input type="text" name="update" size="2" value="{{$data.cat_sort}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.cat_id}},'cat_sort',this.value)"></td>
        <td>{{$data.cat_id}}</td>
        <td style="padding-left:{{$data.step*20}}px">{{$data.depth}}<input type="text" name="update" size="20" value="{{$data.cat_name}}"  onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.cat_id}},'cat_name',this.value)"></td>
       <td>{{$data.cat_sn}}</td>
       <td><input type="text" size=3 value="{{$data.cat_sort}}" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.cat_id}},'cat_sort',this.value)"/></td>
	   <td>
		 {{if $data.parent_id eq '0'}} <a href="javascript:fGo()" onclick="G('{{url param.action=addcat param.pid=$data.cat_id}}')">添加子分类</a>  {{/if}}
        </td>
        <td id="ajax_status{{$data.cat_id}}">{{$data.status}}</td>
	   <td id="ajax_display{{$data.cat_id}}">{{$data.display}}</td>
        <td>
		  <a href="javascript:fGo()" onclick="G('{{url param.action=editcat  param.id=$data.cat_id}}')">编辑</a>		
		  {{if $data.parent_id ne '0'}}<a href="javascript:fGo()" onclick="openDiv('/admin/category/sel-attr/cat_id/{{$data.cat_id}}','ajax','选择产品属性',750,400);">属性</a>{{/if}}
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>