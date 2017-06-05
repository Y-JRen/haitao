 <script src="/scripts/my97/WdatePicker.js" type="text/javascript" language="javascript"></script>
<div class="search">
	<form id="searchForm" action="{{url param.do=index}}" method="get">
		<div class="explain_col"><input type="hidden" value="admin" name="g"><input type="hidden" value="ad" name="m"><input type="hidden" value="index" name="a"><input type="hidden" value="12" name="menuid">开始时间：
            	<input type="text" value="" size="12" class="Wdate" value="{{$params.start_time_min}}"   onClick="WdatePicker()"  id="start_time_min" name="start_time_min">                -
                <input type="text" value="" size="12" class="Wdate"  value="{{$params.start_time_max}}"    onClick="WdatePicker()"  id="start_time_max" name="start_time_max">
                           结束时间：
                <input type="text" value="" size="12" class="Wdate"  value="{{$params.end_time_min}}"   onClick="WdatePicker()"   id="end_time_min" name="end_time_min">                -
                <input type="text" value="" size="12" class="Wdate"   value="{{$params.end_time_max}}"   onClick="WdatePicker()" " id="end_time_max" name="end_time_max"><div class="bk3"></div>广告位：
                <select class="mr10" name="board_id">
                <option value="">--所有--</option>
                {{html_options options=$adBoard  selected=$params.board_id}}
              </select>
              
		               状态: <select class="mr10" name="status">
		              <option value="">--所有--</option>
		              {{html_options options=$statusData  selected=$params.status}}
		          </select>
		          广告类型：
               <select class="mr10" name="style">
               <option value="">--不限--</option>
               {{html_options options=$adType selected=$params.type}}
              </select>关键词：
                <input type="text" value="" size="25" class="input-text mr10"  value="{{$params.keyword}}"   name="keyword">
              	<input type="submit" name="dosearch" value="搜索" />              
              </div>
	</form>
</div>


<div id="ajax_search">
	<div class="title">广告管理</div>
	<div class="content">
		<div class="sub_title">[ <a onclick="G('{{url param.action=add-ad}}')" href="javascript:fGo()">添加广告</a> ]</div>
		<table cellspacing="0" cellpadding="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
                <td>广告名称</td> 						
				<td>广告链接</td>
				<td>广告类型</td>
				<td>广告位</td>
				<td>有效时间</td>
				<td>排序</td>
				<td>状态</td>
				<td>管理操作</td>
			</tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		   <tr>
			<td>{{$item.id}}</td>   
			<td>{{$item.name}}</td>   
			<td>{{$item.url}}</td>
			<td>{{$item.type}}</td>
			<td>{{$adBoard[$item.board_id]}}</td>
			<td>{{$item.start_time|date_format:'%Y-%m-%d'}} / {{$item.end_time|date_format:'%Y-%m-%d'}}</td>
			<td><input type="text" onchange="ajax_update('{{url param.action=ajax-change-ad}}',{{$item.id}},'ordid',this.value)"style="width:30px" value="{{$item.ordid}}"></td>
            <td id="ajax_status{{$item.id}}">{{$item.status}}</td>
			<td>
			<a onclick="G('{{url param.action=edit-ad param.id=$item.id param.back_url=$back_url}}')" href="javascript:fGo()">编辑</a> |
          	<a onclick="reallydelete('{{url param.action=del-ad}}','{{$item.id}}','{{url param.action=index}}')" href="javascript:fGo()">删除</a>
	
			</td>
		</tr>	
	   {{/foreach}}			
		</tbody>
		
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>	
</div>