<div class="title">修改外部团购商品</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table" width="300">
        <tbody>
        <tr>
            <td width="100">客户姓名：</td>
            <td>{{$detail.consumer}}</td>
        </tr>
        <tr>
            <td>联系电话：</td>
            <td>{{$detail.phone}}</td>
        </tr>
        <tr>
            <td>通信地址：</td>
            <td>{{$detail.addr}}</td>
        </tr>
        <tr>
            <td>购买信息：</td>
            <td>
                购买日期：{{if $detail.order_date neq 0}}{{$detail.order_date|date_format:"%Y-%m-%d"}}{{/if}}<br>
                商品名称：{{$detail.order_goods_name}}<br>
                涉及金额：{{$detail.order_money}}<br>
                订单编号：{{$detail.order_sn}}
            </td>
        </tr>
        <tr>
            <td>投诉内容：</td>
            <td><textarea rows="5" cols="39" style="width:300px; height:80px;">{{$detail.complaint}}</textarea></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>处理情况：</td>
            <td>
                <input type="radio" name="is_solved" value="0" {{if $detail.is_solved == 0}}checked="checked"{{/if}} onclick="setSolved(0, {{$detail.id}})" />未处理&nbsp;&nbsp;&nbsp;
                <input type="radio" name="is_solved" value="1" {{if $detail.is_solved == 1}}checked="checked"{{/if}} onclick="setSolved(1, {{$detail.id}})" />已处理未解决&nbsp;&nbsp;&nbsp;
				<input type="radio" name="is_solved" value="2" {{if $detail.is_solved == 2}}checked="checked"{{/if}} onclick="setSolved(2, {{$detail.id}})" />已处理已解决
            </td>
        </tr>
        {{if $detail.reply_remark}}
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>备注：</td>
            <td>{{$detail.reply_remark}}</td>
        </tr>
        {{/if}}
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>添加备注：</td>
            <td><textarea cols="60" rows="4" id="rmk"></textarea>&nbsp;<input type="button" value="添加备注" onclick="addComplaintRemark({{$detail.id}})" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="button" value="返回" onclick="history.back()"></td>
        </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    //更改投诉解决状态
    function setSolved(st, id){
        if(st!=0 && st!=1 && st!=2){st = 0;}
        id = parseInt(id);
        if(id < 1){ alert('参数错误'); return false; }
        new Request({
            'url':'/admin/msg/set-solved/id/'+id+'/st/'+st,
            'onSuccess':function(msg){
                if(msg == 'ok'){ alert('操作成功'); }
                else{ alert(msg); }
            },
            'onFailure':function(){ alert('网络繁忙，请稍候重试'); }
        }).send();
    }
    //添加备注
    function addComplaintRemark(id){
        id = parseInt(id);
        if(id < 1){ alert('参数错误'); return false; }
        var rmk = $('rmk').value.trim();
        if(rmk == ''){ alert('请输入备注内容'); return false; }
        new Request({
            'url':'/admin/msg/add-complaint-remark',
            'data':({'id':id, 'rmk':rmk}),
            'onSuccess':function(msg){
                if(msg == 'ok'){ alert('操作成功'); location.reload(); }
                else{ alert(msg); }
            },
            'onFailure':function(){ alert('网络繁忙，请稍候重试'); }
        }).send();
    }
</script>