<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data" />
<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_form">
    <tr>
      <td width="28%" height="45" align="right"><strong>标题</strong> * </td>
      <td width="72%">
        <label>
          <input type="text" name="title" />
        </label>
      (如：热点推荐)</td>
    </tr>
    <tr>
      <td height="45" align="right"><strong>标签</strong> * </td>
      <td><label>
        <input type="text" name="tag" />
      (如：hot )</label></td>
    </tr>
	    <tr>
      <td height="50" colspan="2" align="center"><div class="submit"><input type="submit" name="dosubmit1" id="dosubmit1" value="确定"/> <input type="reset" name="reset" value="重置" /></div> </td>
    </tr>
</table>
</form>