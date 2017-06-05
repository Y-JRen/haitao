<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" method="get">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	{{html_options options=$billType selected=$param.bill_type}}
</select>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	{{html_options options=$logisticList selected=$param.logistic_code}}
</select>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" {{if $param.is_cod eq '0'}}selected{{/if}}>非货到付款</option><option value="1" {{if $param.is_cod eq '1'}}selected{{/if}}>货到付款</option></select>
匹配类型：<select name="search_mod"><option value="">请选择</option><option value="排除法" {{if $param.search_mod eq '排除法'}}selected{{/if}}>排除法</option><option value="匹配法" {{if $param.search_mod eq '匹配法'}}selected{{/if}}>匹配法</option></select>
<div class="line">
店铺：
  <select name="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=shop}}
      <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
    {{/foreach}}
  </select>
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="{{$param.consignee}}"/>
制单人：<input type="text" name="admin_name" size="10" maxLength="20" value="{{$param.admin_name}}"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="{{$param.bill_no}}"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="G('{{url param.is_lock=yes}}'+location.search)"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="G('{{url param.is_lock=no}}'+location.search)"/>
</div>
</form>
<div class="title">配送管理 -&gt; 物流派单</div>
<form name="myForm" id="myForm">
<div class="content">
	<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
	<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="确认派单" onclick="ajax_submit(this.form, '{{url param.action=assigns}}','Gurl(\'refresh\')')">
	<input type="button" value="返回配货" onclick="ajax_submit(this.form, '{{url param.action=prepare-return}}','Gurl(\'refresh\')')">
	</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>店铺</td>
            <td>单据类型</td>
            <!--<td>重量(kg)</td>-->
            <td>地区</td>
            <td>付款方式</td>
            <td>配送方式</td>
            <td>是否锁定</td>
            <td>件数</td>
            <td>承运商</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.tid}}">
        <td><input type="checkbox" name="ids[]" value="{{$data.tid}}"/></td>
        <td>
	      <input type="button" onclick="openDiv('{{url param.action=assign param.id=$data.tid}}','ajax','查看单据')" value="查看">
        </td>
        <td>{{$data.bill_no_str}}{{if $data.remark}}<br><b>{{$data.remark}}</b>{{/if}}</td>
        <td>{{$data.shop_name}}</td>
        <td>{{$billType[$data.bill_type]}}</td>
        <!--<td>{{$data.weight}}</td>-->
        <td>{{$data.province}}{{$data.city}}{{$data.area}}({{$data.address}})</td>
        <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
        <td>{{if $data.logistic_code neq 'ems'}}快递{{else}}EMS{{/if}}</td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
        <td><input type="text" name="info[{{$data.tid}}][number]" size="6" maxlength="6" value="1" /></td>
        <td><input type="hidden" name="info[{{$data.tid}}][bill_type]" value="{{$data.bill_type}}">
        <input type="hidden" name="info[{{$data.tid}}][bill_no]" value="{{$data.bill_no}}">
        <select name="info[{{$data.tid}}][logistic]">
			{{$data.logisticList}}
		   </select>
	    </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">
<input type="button" value="确认派单" onclick="ajax_submit(this.form, '{{url param.action=assigns}}','Gurl(\'refresh\')')">
<input type="button" value="返回配货" onclick="ajax_submit(this.form, '{{url param.action=prepare-return}}','Gurl(\'refresh\')')">
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
