<div class="title">礼券发放记录</div>
<form name="searchForm" id="searchForm" action="/admin/coupon/log">
<div class="search">
<select name="card_type" onchange="searchForm.submit()">
  <option value="">按礼券类型查询</option>
  <option value="0" {{if $param.card_type eq '0'}}selected{{/if}}>常规卡</option>
  <option value="1" {{if $param.card_type eq '1'}}selected{{/if}}>非常规卡</option>
  <option value="2" {{if $param.card_type eq '2'}}selected{{/if}}>绑定商品卡</option>
  <option value="3" {{if $param.card_type eq '3'}}selected{{/if}}>商品抵扣卡</option>
  <option value="4" {{if $param.card_type eq '4'}}selected{{/if}}>订单金额抵扣卡</option>
  <!--<option value="5" {{if $param.card_type eq '5'}}selected{{/if}}>组合商品抵扣卡</option>-->
</select>
&nbsp;&nbsp;
<select name="status" onchange="searchForm.submit()">
  <option value="">按状态</option>
  <option value="0" {{if $param.status eq '0'}}selected{{/if}}>正常</option>
  <option value="1" {{if $param.status eq '1'}}selected{{/if}}>无效</option>
</select>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add }}')">生成礼券</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>礼券类型</td>
            <td>重复使用</td>
            <td>礼券价格</td>
            <td>起始范围</td>
            <td>结束范围</td>
            <td>数量</td>
            <td>添加时间</td>
	    <td>开始时间</td>
            <td>结束时间</td>
            <td>添加人</td>
            <td>分成</td>
            <td>用户绑定数</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$logList item=log}}
        <tr id="ajax_list{{$log.log_id}}">
            <td>{{$log.log_id}}</td>
            <td><a href="javascript:void(0);" title="{{$log.note}}">{{$log.card_type}}</a></td>
            <td>{{$log.is_repeat}}</td>
            <td>{{$log.card_price}}</td>
            <td>{{$log.range_from}}</td>
            <td>{{$log.range_end}}</td>
            <td>{{$log.number}}</td>
            <td>{{$log.add_time}}</td>
	    <td>{{$log.start_date}}</td>
            <td>{{$log.end_date}}</td>
            <td>{{$log.admin_name}}</td>
            <td>{{if $log.is_affiliate eq 1}}<font color="red">是</font>{{else}}否{{/if}}</td>
            <td>{{$history_count[$log.log_id]|default:'0'}}</td>
            <td id="ajax_status{{$log.log_id}}">{{$log.status}}</td>
            <td>
                <a href="{{url param.action=view-log param.id=$log.log_id}}">查看</a> | 
                <a href="{{url param.action=get-file param.id=$log.log_id}}" target="_blank">获取</a> | 
                <a href="javascript:fGo()" onclick="G('{{url param.action=history param.lid=$log.log_id param.do=search}}')">已使用</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>