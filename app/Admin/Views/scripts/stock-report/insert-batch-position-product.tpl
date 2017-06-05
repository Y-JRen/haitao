<form name="myForm" id="myForm" action="{{url}}" method="post" onsubmit="return check();" enctype="multipart/form-data">
<div class="title">批量导入库位产品 [<a href="/admin/stock-report/position-list">库位列表</a>]</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="200">导入库位产品文件</td>
      <td><input type="file" name="import_file" id="import_file" msg="请选择上传的EXcel文件(2003版本)" class="required"></td>
    </tr>
    <tr> 
      <td>导入格式 </td>
      <td>XLS文件(2003/2007版)，第1列：产品编码，第2列：库位号。<a href="/admin/stock-report/download-template/type/position-product" ><下载模板></a></td>
    </tr>
</tbody>
</table>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定"/> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function check()
{
    if (!confirm('确定上传库位产品文件吗')) {
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