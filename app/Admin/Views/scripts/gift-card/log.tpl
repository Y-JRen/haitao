<div class="title">礼品卡发放记录</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">生成礼品卡</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>礼品卡类型</td>
            <td>礼品卡价格</td>
            <td>数量</td>
            <td>添加时间</td>
            <td>结束时间</td>
            <td>添加人</td>
            <td>绑定用户数</td>
            <td>消费数量</td>
            <td>消费总金额</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$logList item=log}}
        <tr id="ajax_list{{$log.log_id}}">
            <td>{{$log.log_id}}</td>
            <td><a href="jvavascropt:void(0);" title="{{$log.note|default:' '}}">{{$log.type}}</a></td>
            <td>{{$log.card_price}}</td>
            <td>{{$log.number}}</td>
            <td>{{$log.add_time}}</td>
            <td>{{$log.end_date}}</td>
            <td>{{$log.admin_name}}</td>
            <td>{{$sum[$log.log_id].bind_count|default:0}}</td>
            <td>{{$sum[$log.log_id].consume_count|default:0}}</td>
            <td>{{$sum[$log.log_id].consume_amount|default:0}}</td>
            <td>
                <a href="{{url param.action=get-file param.id=$log.log_id}}" target="_blank">获取</a> | 
                <a href="javascript:fGo()" onclick="G('{{url param.action=list param.lid=$log.log_id param.do=search}}')">查看</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>