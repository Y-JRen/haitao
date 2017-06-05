<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="myForm" id="myForm" action="{{url param.action=add}}" method="post">
<div class="title">添加礼品卡</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=log}}')">礼品卡发放记录</a> ]
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">礼品卡类型 * </td>
<td width="40%">{{html_radios name="card_type" options=$cardType checked=1 separator=" "}}</td>
<td width="10%">礼品卡价格 * </td>
<td width="40%"><input type="text" name="card_price" id="card_price" size="6" maxlength="6" msg="请填写礼品卡价格" class="required int" /></td>
</tr>
<tr>
<td width="10%">生成数量 * </td>
<td width="40%"><input type="text" name="number" id="number" size="6" maxlength="6" msg="请填写生成数量" class="required int" /></td>
<td width="10%">截止日期 * </td>
<td width="40%"><span style="float:left;width:150px;"><input type="text" name="end_date" id="end_date" size="11" value="{{$smarty.now|date_format:"%Y-%m-%d"}}"  class="Wdate"   onClick="WdatePicker()"/></span></td>
</tr>
<tr>
<td width="10%">绑定用户ID</td>
<td colspan="3"><input type="text" name="parent_id" id="parent_id" size="10" maxlength="10" /></td>
</tr>
<tr>
<td width="10%">备注</td>
<td colspan="3"><textarea style="width: 500px;height: 200px" name="note"></textarea></td>
</tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>