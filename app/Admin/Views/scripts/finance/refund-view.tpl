{{include file="inc/header.tpl"}}

<link rel="stylesheet" type="text/css" href="/Public/js/uploadify/uploadify.css" />
<script language="javascript" type="text/javascript" src="/Public/js/uploadify/jquery.uploadify-3.1.min.js"></script>

<div class="ui-layout-center">
	<div class="ui-layout-content">
		<div class="panel" style="padding:20px;">
			<form id="frm-custom-add" action="/admin/finance/refund-do" method="post"
				submitafter="refund_do_after"
			>
				<input type="hidden" value="{{$r.refund_id}}" name="id"/>
				<table class="tbl-frm">
					<tr>
						<th width="100">单号：</th>
						<td>{{$r.refund_sn}}</td>
						<th>用户邮箱：</th>
						<td>{{$r.member.email}}</td>
					</tr>
					<tr>
						<th>用户手机：</th>
						<td>{{$r.member.mobile}}</td>
						<th>家用电话：</th>
						<td>{{$r.member.home_phone}}</td>
					</tr>
					<tr>
						<th>账户余额：</th>
						<td>{{$r.member.money}}</td>
						<th>冻结余额：</th>
						<td>{{$r.member.frost_money}}</td>
					</tr>
					<tr>
						<th>申请退款金额：</th>
						<td>{{$r.money}}</td>
						<th>当前状态：</th>
						<td><span style="color: {{$r.status_color}}">{{$r.status_name}}</span></td>
					</tr>
					<tr>
						<th>申请时间：</th>
						<td>{{$r.add_time}}</td>
						<th>退款方式：</th>
						<td>{{$r.bank_type_name}}</td>
					</tr>
					<tr>
						<th>账户信息：</th>
						<td colspan="3">
							{{if $r.bank_type == 1}}
								开户行：{{$r.bank_config.bank_name}}
								&nbsp;&nbsp;
								账号：{{$r.bank_config.account_id}}
								&nbsp;&nbsp;
								账户名：{{$r.bank_config.account_name}}
							{{else}}
								账号：{{$r.bank_config.account_id}}
							{{/if}}
						</td>
					</tr>
					{{if $r.status == 0}}
					<tr>
						<th>操作：</th>
						<td colspan="3">
							<label><input name="status" value="1" type="radio"/>通过</label>
							<label><input name="status" value="2" type="radio"/>作废</label>
						</td>
					</tr>
					{{/if}}
				</table>
			</form>
		</div>
	</div>
	<div class="ui-layout-footer">
		{{if $r.status == 0}}
		<input class="btn-ajax-submit" frmid="frm-custom-add" type="button" value="保存"/>
		{{/if}}
		<input onclick="window.close();" type="button" value="关闭"/>
	</div>
</div>

{{include file="inc/footer.tpl"}}

<script>
	$(document).ready(function() {
		ajaxinit();
		$('body').layout({
			applyDefaultStyles : false,
			north__resizable : true, //可以改变大小
			north__closable : false, //可以被关闭
			spacing_open : 10, //边框的间隙
			spacing_closed : 10, //关闭时边框的间隙
			resizerTip : "可调整大小", //鼠标移到边框时，提示语
			togglerTip_open : "关ddd闭", //pane打开时，当鼠标移动到边框上按钮上，显示的提示语
			togglerLength_open : 70, //pane打开时，边框按钮的长度
			togglerAlign_open : 120, //pane打开时，边框按钮显示的位置
			togglerContent_open : "手", //pane打开时，边框按钮中需要显示的内容可以是符号"<"等。需要加入默认css样式.ui-layout-toggler .content
			test : 2
		});
	});
</script>


<script type="text/javascript">
	
	
function refund_do_after(msg,$elt){
	if(msg.status == 'succ'){
		window.close();
		window.opener.grid_finder.datagrid('reload');
	}
	return true;
}
</script>


