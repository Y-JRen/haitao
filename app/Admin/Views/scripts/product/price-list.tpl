<form name="searchForm" id="searchForm">
<div class="search">

{{$catSelect}}
状态：
<select name="p_status">
  <option value="" selected>请选择</option>
  <option value="0" {{if $param.p_status eq '0'}}selected{{/if}}>正常</option>
  <option value="1" {{if $param.p_status eq '1'}}selected{{/if}}>冻结</option>
</select>
产品编码：<input type="text" name="product_sn" size="10" maxLength="50" value="{{$param.product_sn}}"/>
产品名称：<input type="text" name="product_name" size="10" maxLength="50" value="{{$param.product_name}}"/>
货位：<input type="text" name="local_sn" size="10" maxLength="50" value="{{$param.local_sn}}"/>
限价：<input type="checkbox" name="price_limit" value="1" {{if $param.price_limit eq '1'}}checked='true'{{/if}}/>
<br>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
<input type="button" name="dosearch2" value="所有被我锁定的产品" onclick="ajax_search(this.form,'{{url param.is_lock=yes}}','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的产品" onclick="ajax_search(this.form,'{{url param.is_lock=no}}','ajax_search')"/>
<input type="button" onclick="window.open('/admin/product/export-price'+location.search)" value="导出产品成本">
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">产品管理 -&gt; 成本管理</div>
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td width="30"></td>
            <td>产品ID</td>
            <td width="80px">产品编码</td>
            <td width="200px">产品名称</td>
            <td>系统分类</td>
            <td>建议销售价</td>
            <td>(移动)成本</td>
            <td>(采购)成本</td>
            <td>发票税率</td>
            <td>真实库存</td>
            <td>状态</td>
            <td>是否锁定</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.product_id}}">
        <td><input type="checkbox" name="ids[]" value="{{$data.product_id}}"/></td>
        <td>{{$data.product_id}}</td>
        <td>{{$data.product_sn}}</td>
        <td>{{$data.product_name|stripslashes}}<font color="#FF0000">({{$data.goods_style}})</font></td>
        <td>{{$data.cat_name}}</td>
        <td {{if $data.suggest_price < $data.price_limit}} style="color:#ff0000"{{/if}}>{{$data.suggest_price}}</td>
		<td>{{$data.cost}}</td>
        <td>{{$data.purchase_cost}}</td>
        <td>{{$data.invoice_tax_rate}}</td>
        <td>{{$data.real_number|default:0}}</td>
        <td>
          {{if $data.p_status eq '0'}}正常
          {{else}}<font color="red">冻结</font>
          {{/if}}
        </td>
        <td>{{if $data.p_lock_name}}被<font color="red">{{$data.p_lock_name}}</font>{{else}}未{{/if}}锁定</td>
        <td>
	      <a href="javascript:fGo()" onclick="openDiv('{{url param.action=cost-edit param.id=$data.product_id}}','ajax','产品成本修改');">
	      {{if $data.p_lock_name eq $auth.admin_name}}编辑{{else}}查看{{/if}}</a>
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
<div class="page_nav">{{$pageNav}}</div>
</form>
