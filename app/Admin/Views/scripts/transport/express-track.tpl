<style type="text/css">
.mytable {
	border-collapse: collapse;
	border: 1px solid #390;
}
.mytable td {
	border: 1px solid #390;
	padding: 3px;
}
.mytable th {
	border: 1px solid #390;
	height:28px;
	background:#e5feda;
	font-weight:bold;
	text-align:center;
}
</style>
<div class="search">
	<form action="/admin/transport/express-track" method="post" onSubmit="return chk();">
	<table class="mytable">
		<tr>
			<td>选择快递公司：
				<select name="logistic_code" id="logistic_code">
					<option value="">请选择</option>
					<option value="yt" {{if $params.logistic_code eq 'yt'}} selected {{/if}}>圆通</option>
				</select>&nbsp;&nbsp;&nbsp;
			</td>
			<td>快递单号：<input type="text" name="logistic_no" id="logistic_no" value="{{$params.logistic_no}}" />&nbsp;&nbsp;&nbsp;</td>
			<td><input type="submit" value="查询" /></td>
		</tr>
	</table>
	</form>
	<br />
	<table class="mytable">
		<thead>
			<tr>
				<th width="200">时间</th>
				<th width="200">地点</th>
				<th width="100">状态</th>
				<th width="100">描述</th>
			</tr>
		</thead>
		<tbody>
			{{foreach from=$tracks item=track}}
			<tr>
				<td>{{$track.dateTime}}</td>
				<td>{{$track.location}}</td>
				<td>{{$track.contents}}</td>
				<td>{{$track.details}}</td>
			</tr>
			{{/foreach}}
		</tbody>
	</table>
</div>
<script type="text/javascript">
function chk(){
	var code = $("logistic_code").value.trim();
	if(code == ''){ alert('请选择快递公司'); return false; }
	var no = $("logistic_no").value.trim();
	if(no == ''){ alert('请输入快递单号'); return false; }
	return true;
}
</script>