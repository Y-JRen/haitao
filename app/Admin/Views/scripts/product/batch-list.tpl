<form name="searchForm" id="searchForm">
<div class="search">
{{$catSelect}}
状态：
<select name="p_status">
  <option value="" selected>请选择</option>
  <option value="0" {{if $param.p_status eq '0'}}selected{{/if}}>正常</option>
  <option value="1" {{if $param.p_status eq '1'}}selected{{/if}}>冻结</option>
</select>
供应商：<select name="supplier_id" id="supplier_id">
          <option value="">请选择...</option>
          {{foreach from=$supplierData item=s}}
 		  <option value="{{$s.supplier_id}}" {{if $param.supplier_id eq $s.supplier_id}}selected{{/if}}>{{$s.supplier_name}}</option>
          {{/foreach}}
        </select>
<br>
产品编码：<input type="text" name="product_sn" size="10" maxLength="50" value="{{$param.product_sn}}"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="{{$param.product_name}}"/>
批次号：<input type="text" name="batch_no" size="20" maxLength="50" value="{{$param.batch_no}}"/>
条形码：<input type="text" name="barcode" size="20" maxLength="50" value="{{$param.barcode}}"/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">

</div>
</form>
<form name="myForm" id="myForm">
<div class="title">产品管理 -&gt; 产品批次列表</div>
<div class="content">
<div class="sub_title">
  [ <a href="javascript:fGo()" onclick="G('/admin/product/add-batch');">添加新批次</a> ] 
</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>批次号</td>
            <td>产品编码</td>
            <td width="200px">产品名称（规格）</td>
            <td>系统分类</td>
            <td>条形码</td>
            <td>供应商</td>
            <td>状态</td>
            <td>排序</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.batch_id}}">
        <td>{{$data.batch_no}}</td>
        <td>{{$data.product_sn}}</td>
        <td>{{$data.product_name}}<font color="#FF0000"> ({{$data.goods_style}}) </font></td>
        <td>{{$data.cat_name}}</td>
        <td>{{$data.barcode}}</td>
        <td>{{$data.supplier_name}}</td>
        <td>
          {{if $data.status}}<font color="red">冻结</font>
          {{else}}正常
          {{/if}}
        </td>
        <td>
          <input type="text" name="sort" id="sort" size="1" style="text-align:center" value="{{$data.sort}}" onchange="setSort({{$data.batch_id}}, this.value)">
        </td>
        <td>
	      <a href="javascript:fGo()" onclick="G('/admin/product/edit-batch/batch_id/{{$data.batch_id}}')">编辑</a> 
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
<script language="JavaScript">
function setSort(batch_id, value)
{
    if (!/^\d{0,4}$/.test(value)) {
        alert('请输入四位以内的数字!');
        return;
    }

    if (batch_id != '' && value != '') {
        new Request({
            url: '/admin/product/batch-change-sort/batch_id/' + batch_id + '/sort/' + value,
            onRequest: loading,
            onSuccess: loadSucess,
            onFailure: function(){
        	    alert('error');
            }
        }).send();
    }
}
</script>