<div class="title">投诉管理</div>
<div class="content">
    <div class="search">
        <form id="searchForm">
            <div style="clear:both; padding-top:5px">
            是否阅读：<select name="is_read" size="1">
              <option value="">  请 选 择 </option>
              <option value="1" {{if $param.is_read === '1'}}selected="selected" {{/if}}>是</option>
              <option value="0" {{if $param.is_read === '0'}}selected="selected" {{/if}}>否</option>
            </select>&nbsp;&nbsp;&nbsp;
            处理状态：<select name="is_solved" size="1">
                <option value="">  请 选 择 </option>
                <option value="0" {{if $param.is_solved === '0'}}selected="selected" {{/if}}>未受理</option>
                <option value="1" {{if $param.is_solved === '1'}}selected="selected" {{/if}}>已受理未解决</option>
                <option value="2" {{if $param.is_solved === '2'}}selected="selected" {{/if}}>已解决已解决</option>
            </select>
            <input type="submit" name="dosearch" value=" 搜 索 "/>
            </div>
        </form>
    </div>

    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
            <tr>
                <td>ID</td>
                <td style="width:60px;">投诉时间</td>
                <td>顾客姓名</td>
                <td>联系方式</td>
                <td>购买信息</td>
                <td>投诉内容</td>
                <td>阅读备注</td>
                <td>是否处理</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr>
            <td>{{$data.id}}</td>
            <td>{{$data.ctime|date_format:"%Y-%m-%d %T"}}</td>
            <td><strong>{{$data.consumer}}</strong></td>
            <td>电话：{{$data.phone}}<br>地址：{{$data.addr}}</td>
            <td>购买日期：{{if $data.order_date neq 0}}{{$data.order_date|date_format:"%Y-%m-%d"}}{{/if}}<br>商品名称：{{$data.order_goods_name}}<br>涉及金额：{{$data.order_money}}<br>订单编号：{{$data.order_sn}}</td>
            <td><textarea rows="5" cols="39" style="width:300px; height:80px;">{{$data.complaint}}</textarea></td>
            <td>
            {{if $data.is_read eq 0}}<font color="red">未阅读</font>{{else}}<font color="green">已阅读</font>{{/if}}<br>
            {{if $data.reply_remark}}<font color="green">已备注</font>{{else}}<font color="red">未备注</font>{{/if}}
            </td>
            <td>{{if $data.is_solved eq 0}}<font color="red">未受理</font>{{elseif $data.is_solved eq 1}}<font color="orange">已受理未解决</font>{{else}}<font color="green">已解决已解决</font>{{/if}}</td>
            <td><a href="javascript:fGo()" onclick="G('{{url param.action=complaint-detail param.id=$data.id}}')">阅读回复</a></td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>

