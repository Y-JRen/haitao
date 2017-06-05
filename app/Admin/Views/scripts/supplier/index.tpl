<div class="title">供货商管理</div>
<div class="search">
  <form id="searchForm" method="get">
  供货商名称：<input type="text" name="supplier_name" size="20" maxLength="50" value="{{$param.supplier_name}}">
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加供货商</a> ]
        [ <a href="{{url param.todo=export}}" target="_blank">导出信息</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="25">ID</td>
            <td width="100">供货商名称</td>
            <td>产品数量</td>
			<td>公司名称</td>
            <td>联系电话</td>
            <td>联系人</td>
            <td width="100">添加时间</td>
            <td width="30">状态</td>
            <td width="100">操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr id="ajax_list{{$data.supplier_id}}">
            <td>{{$data.supplier_id}}</td>
            <td>{{$data.supplier_name}}</td>
            <td> 
              <font color="#FF3300">{{$data.goods_num}}</font></a>
            </td>
			<td>{{$data.company}} </td>
            <td><input type="text" name="update" size="30" value="{{$data.tel}}" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.supplier_id}},'tel',this.value)"></td>
            <td><input type="text" name="update" size="10" value="{{$data.contact}}" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.supplier_id}},'contact',this.value)"></td>
            <td>{{$data.add_time}}</td>
            <td id="ajax_status{{$data.supplier_id}}">{{$data.status}}</td>
	        <td>
				<a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$data.supplier_id}}')">编辑</a> 
                <a href="javascript:fGo()" onclick="G('{{url param.action=goods param.id=$data.supplier_id}}')">供应产品</a> 
	        </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>