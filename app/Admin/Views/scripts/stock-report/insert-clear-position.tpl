<form name="myForm" id="myForm" action="{{url}}" method="post" onsubmit="return check();" enctype="multipart/form-data">
<div class="title">清空导入产品库位 [<a href="/admin/stock-report/position-list">库位列表</a>]</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>所属仓库</strong> * </td>
      <td>
        <select name="area" onchange="changeArea(this.value)">
          {{foreach from=$areas item=item key=key}}
          {{if $params.area eq $key}}
          <option value="{{$key}}">{{$item}}</option>
          {{/if}}
          {{/foreach}}
        </select>
      </td>
    </tr>
    <tr> 
      <td width="15%"><strong>所属库区</strong> * </td>
      <td id="districtBox">
        <select name="district_id" id="district_id" onchange="changeDistrict()" {{if $action eq 'edit'}}disabled{{/if}}>
          {{foreach from=$districts item=item key=key}}
          <option value="{{$key}}" {{if $data.district_id eq $key}}selected{{/if}}>{{$item}}</option>
          {{/foreach}}
        </select>
      </td>
    </tr>
    <tr> 
      <td>导入文件 </td>
      <td><input type="file" name="import_file" id="import_file" msg="请选择上传的EXcel文件(2003版本)" class="required"></td>
    </tr>
    <tr> 
      <td>导入格式 </td>
      <td>XLS文件(2003/2007版)，第1列：库位号，第二列：产品编码。<a href="/admin/stock-report/download-template/type/position-clear-product" ><下载模板></a></td>
    </tr>
</tbody>
</table>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定"/> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function changeArea(area)
{
    new Request({
        url:'/admin/stock-report/get-district-box/area/' + area,
	    onSuccess:function(msg){
            $('districtBox').innerHTML = msg;
            changeDistrict();
		},
		onError:function() {
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
function changeDistrict()
{
    if ($('district_id') == null) {
        $('position_no').value = '';
        return;
    }
    
    var district_id = $('district_id').value;
    new Request({
        url:'/admin/stock-report/get-district-no/district_id/' + district_id,
	    onSuccess:function(msg){
            $('position_no').value = msg;
		},
		onError:function() {
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}

function check()
{
    if (!confirm('确定上传库位文件吗')) {
        return false;
    }
    var excel = $("import_file").value;
    var reg=/[^\.](\.xls)$/i;  
    if (false === reg.test(excel)) {
        alert('请上传excel文件');
        return false;
    }
}
</script>