<div class="search">
<form name="myForm" id="myForm" action="{{url param.action=add-logistic}}" method="post">
<table>
    <tr>
        <td >物流公司名</td>
        <td width=150><input type="text" name="name" /></td>
        <td >物流公司类别</td>
        <td width=150>
            <select name="logistic_code">
            {{foreach from=$logisticPlugin key=code item=label}}
            <option value={{$code}}>{{$label}}</option>
            {{/foreach}}
            </select>        </td>
        <td >代收款费率</td>
        <td width=150><input type="text" name="cod_rate" /></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>排序</td>
        <td><input type="text" name="sort" /></td>
        <td>启用</td>
        <td>
            <select name="open">
            <option value=0>否</option>
            <option value=1>是</option>
            </select>        </td>
        <td>简介</td>
        <td><input type="text" name="brief" /></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
      <td>连接地址</td>
      <td colspan="5"><input type="text" name="url" /></td>
      <td><input type="submit" value="添加" /></td>
    </tr>
</table>
</form>
</div>

<div id="ajax_search">
<div class="title">物流管理 -&gt; 物流公司管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
        <td >快递公司别名</td>
        <td >快递公司类别</td>
        <td >代收款费率</td>
        <td >代收款最小费用</td>
        <td >服务费</td>
        <td >排序</td>
        <td >启用</td>
        <td >操作区域管理</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    {{foreach from=$logistic item=data}}
    <tr>
        <td>{{$data.name}}</td>
        <td>
            {{foreach from=$logisticPlugin key=code item=label}}
            {{if $code==$data.logistic_code}}{{$label}}{{/if}}
            {{/foreach}}        </td>
        <td>{{$data.cod_rate}}</td>
        <td>{{$data.cod_min}}</td>
        <td>{{$data.fee_service}}</td>
        <td>{{$data.sort}}</td>
        <td>{{if $data.open}}是{{else}}否{{/if}}</td>
        <td><input type="button" value="操作区域管理" onclick="G('{{url param.action=list-logistic-area param.logistic_code=$data.logistic_code}}')" /></td>
        <td>
            <input type="button" value="编辑" onclick="G('{{url param.action=edit-logistic param.logistic_code=$data.logistic_code}}')" />
            <input type="button" value="删除" onclick="if(confirm('确定删除？')){G('{{url param.action=del-logistic param.logistic_code=$data.logistic_code}}');}" />
            <input type="button" value="模板" onclick="G('{{url param.action=template-logistic param.logistic_code=$data.logistic_code}}')" />
            <input type="button" value="导出" onclick="window.open('{{url param.action=export-logistic param.logistic_code=$data.logistic_code}}')" />
            <input type="button" value="导入" onclick="openDiv('{{url param.action=import-logistic param.logistic_code=$data.logistic_code}}','ajax','数据导入',MsgW=500,MsgH=200);" />
        </td>
    </tr>
    {{/foreach}}
</table>
</div>
</div>