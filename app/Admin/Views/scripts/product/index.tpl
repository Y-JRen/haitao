<form name="searchForm" id="searchForm">
<div class="search">
系统分类：{{$catSelect}}
状态：
<select name="p_status">
  <option value="" selected>请选择</option>
  <option value="0" {{if $param.p_status eq '0'}}selected{{/if}}>正常</option>
  <option value="1" {{if $param.p_status eq '1'}}selected{{/if}}>冻结</option>
</select>
虚拟商品：
<select name="is_vitual">
  <option value="" selected>请选择</option>
  <option value="1" {{if $param.is_vitual eq '1'}}selected{{/if}}>是</option>
  <option value="0" {{if $param.is_vitual eq '0'}}selected{{/if}}>否</option>
</select>
礼品卡：
<select name="is_gift_card">
  <option value="" selected>请选择</option>
  <option value="1" {{if $param.is_gift_card eq '1'}}selected{{/if}}>是</option>
  <option value="0" {{if $param.is_gift_card eq '0'}}selected{{/if}}>否</option>
</select>
产品编码：<input type="text" name="product_sn" size="15" maxLength="50" value="{{$param.product_sn}}"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="{{$param.product_name}}"/>
<br>
货位：<input type="text" name="local_sn" size="20" maxLength="50" value="{{$param.local_sn}}"/>
国际码：<input type="text" name="ean_barcode" size="25" maxLength="50" value="{{$param.ean_barcode}}"/>
<input type="checkbox" name="product_img" value="1" {{if $param.product_img}}checked{{/if}}> 图标未上传
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">   <input type="button" onclick="window.open('/admin/product/export'+location.search)" value="导出商品资料">
<br>
<input type="button" name="dosearch2" value="所有被我锁定的产品" onclick="ajax_search(this.form,'{{url param.is_lock=yes}}','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的产品" onclick="ajax_search(this.form,'{{url param.is_lock=no}}','ajax_search')"/>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">产品管理 -&gt; 产品列表</div>
<div class="content">
<!--
<div class="sub_title">
  [ <a href="javascript:fGo()" onclick="G('/admin/product/add');">添加新产品</a> ] 
</div>
-->
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td width="10px"></td>
            <td>产品主图</td>
            <td>产品ID</td>
            <td  width="60px">产品编码</td>
            <!--<td>国际码</td>-->
            <td width="260px">产品名称（规格）</td>
            <td>系统分类</td>
            <td>供应商</td>
            <!--<td>长宽高(cm)</td>-->
            <!--<td>重量(kg)</td>-->
            <td>状态</td>
            <td>是否锁定</td>
            <td>可超卖库存</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.product_id}}">
        <td><input type="checkbox" name="ids[]" value="{{$data.product_id}}"/></td>
        <td>{{if $data.product_img}}<img src="{{$imgBaseUrl}}/{{$data.product_img|replace:'.':'_60_60.'}}" width="35">{{else}}<font color="red" size="3">未上传</font>{{/if}}</td>
        <td>{{$data.product_id}}</td>
        <td>{{$data.product_sn}}</td>
        <!-- <td>{{$data.ean_barcode}}</td>-->
        <td>{{$data.product_name|stripslashes}}<font color="#FF0000"> ({{$data.goods_style}}) </font></td>
        <td>{{$data.cat_name}}</td>
        <td>{{$data.supplier}}</td>
        <!--<td>长：{{$data.p_length}}<br>宽：{{$data.p_width}}<br>高：{{$data.p_height}}</td>-->
        <!--<td>{{$data.p_weight}}</td>-->
        <td id="ajax_status{{$data.product_id}}">{{$data.status}}</td>
        <td>{{if $data.p_lock_name}}被<font color="red">{{$data.p_lock_name}}</font>{{else}}未{{/if}}锁定</td>
        <td>
            <input type="text" name="adjust_num" size="4" id="adjust_num" value="{{$data.adjust_num}}" style="text-align:center;"  onchange="js_ajax_update(this, '{{url param.action=ajaxupdate}}',{{$data.product_id}},'adjust_num',this.value, '{{$data.stock_able_number}}')" />
        </td>
        <td>
	      <a href="javascript:fGo()" onclick="openDiv('{{url param.action=edit param.id=$data.product_id}}','ajax','产品修改' ,850,400 );">{{if $data.p_lock_name eq $auth.admin_name}}编辑{{else}}查看{{/if}}</a>
	      <a href="javascript:fGo()" onclick="openDiv('{{url param.action=image param.id=$data.product_id param.product_sn=$data.product_sn}}','ajax','图片管理 {{$data.product_name}}');">{{if $data.p_lock_name eq $auth.admin_name}}上传图片{{else}}查看图片{{/if}}</a>
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
<div class="page_nav">{{$pageNav}}</div>
</form>

<script>
    
    function js_ajax_update(obj, url, id, field, val, stock_able_number)
    {
        if (parseInt(stock_able_number) < 0) {
            if (isNaN(val) || val == '' || parseInt(val) < Math.abs(parseInt(stock_able_number))) {
                val = Math.abs(parseInt(stock_able_number));
            }
        } else if (parseInt(stock_able_number) > 0) {
            if (isNaN(val) || val == '' || parseInt(val) < 0) {
                val = 0;
            }
        }
        obj.value = val;
        ajax_update(url, id, field, val);
    }

</script>
