{{if !$param.do}}
<div class="search">
	<form id="searchForm">
		<div style="clear:both; padding-top:5px">
		分类:
		<select name="cat_id">
			<option value="">请选择...</option>
			{{foreach from=$catTree key=cat_id item=item}}
			{{if $item.leaf}}
			<option value={{$cat_id}} style="padding-left:{{$item.step*20-20}}px" {{if $param.cat_id==$cat_id}}selected="selected"{{/if}}>
			{{$item.cat_name}}（{{$item.num}}）
			</option>
			{{else}}
			<optgroup label='{{$item.cat_name}}' style="padding-left:{{$item.step*20-20}}px"></optgroup>
			{{/if}}
			{{/foreach}}		
		</select>
		标题：<input type="text" name="title" size="20" maxLength="50" value="{{$param.title}}">
        
        是否显示：<select name="is_view">
          <option value="null">请选择</option>
          <option value="1" {{if $param.is_view eq '1'}}selected{{/if}}>显示</option>
          <option value="0" {{if $param.is_view eq '0'}}selected{{/if}}>不显示</option>
        </select>      
        是否推荐：<select name="is_hot">
          <option value="null">请选择</option>
          <option value="1" {{if $param.is_hot eq '1'}}selected{{/if}}>是</option>
          <option value="0" {{if $param.is_hot eq '0'}}selected{{/if}}>否</option>
        </select>   
        
		<input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.do=search}}','ajax_search')"/>
		</div>	
	</form>
</div>
<div id="ajax_search">
{{/if}}
	<div class="title">文章管理</div>
	<div class="content">
		<div class="sub_title">[ <a href="javascript:fGo()" onclick="G('{{url param.action=addform}}')">添加文章</a> ][ <a href="javascript:fGo()" onclick="G('{{url param.action=footer}}')">生成底部菜单列表缓存</a> ]</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
                <td>排序</td>
				<td>分类</td>
				<td>标题</td>
				<td>添加日期</td>
				<td>是否显示</td>
				<td>是否推荐置顶</td>
				<td>操作</td>
			</tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.article_id}}">
			<td>{{$item.article_id}}</td>
                    <td><input value='{{$item.sort}}' type='text' style='width:30px' 
	onchange="ajax_update('{{url param.action=ajaxupdatearticle}}',{{$item.article_id}},'sort',this.value)"></td>
            
			<td>{{$item.cat_name}}</td>
			<td>
				<input type="text" name="update" size="45" value="{{$item.title}}"  
				onchange="ajax_update('{{url param.action=ajaxupdatearticle}}',{{$item.article_id}},'title',this.value)">
			</td>
			<td >{{if $item.add_time}}{{$item.add_time|date_format:'%Y-%m-%d'}}{{else}}{{/if}}</td>
			<td  >  {{if $item.is_view eq 1}}  <font color="#009966">显示</font> {{elseif $item.is_view eq 2 }} <font color="#FF3300">不显示 </font> {{/if}}</td>
			<td>{{if $item.is_hot eq 1}}<font style="color:#009966; cursor:pointer;" onclick="changHot({{$item.article_id}}, 0);">是</font>{{elseif $item.is_hot eq 0}}<font style="color:#FF3300; cursor:pointer;" onclick="changHot({{$item.article_id}}, 1);">否</font>{{/if}}</td>
			<td>
			<a href="javascript:fGo()" onclick="G('{{url param.action=editform param.id=$item.article_id}}')">编辑</a>||
            <a href="javascript:fGo()" onclick="openDiv('{{url param.action=linkgoods param.id=$item.article_id}}','ajax','查看{{$item.title}}关联商品',750,400)">编辑关联商品</a>||
			<a href="javascript:fGo()" onclick="reallydelete('{{url param.action=del}}','{{$item.article_id}}','{{url param.action=list}}')">删除</a>
		</td>
		</tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>
<script type="text/javascript">
function changHot(articleID, st){
	articleID = parseInt(articleID);
	st = parseInt(st);
	if(st!=1 && st!=0){ st = 0; }
	new Request({
		url:'/admin/article/is-hot/article_id/'+articleID+'/st/'+st,
		onSuccess:function(msg){
			if(msg == 'ok'){
				location.reload();
			}else{
				alert(msg);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
</script>