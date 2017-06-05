{{include file="inc/header.tpl"}}

<link rel="stylesheet" type="text/css" href="/Public/js/uploadify/uploadify.css" />
<script language="javascript" type="text/javascript" src="/Public/js/uploadify/jquery.uploadify-3.1.min.js"></script>

<div class="ui-layout-center">
	<div class="ui-layout-content">
		<div class="panel" style="padding:20px;">
			<form id="frm-custom-add" action="/admin/member/refund-do" method="post"
				submitafter="refund_after"
			>
				<input type="hidden" value="{{$r.member_id}}" name="id"/>
				<table class="tbl-frm">
					<tr>
						<th width="100">昵称：</th>
						<td>{{$r.nick_name}}</td>
					</tr>
					<tr>
						<th>账户邮箱：</th>
						<td>{{$r.email}}</td>
					</tr>
					<tr>
						<th>账户余额：</th>
						<td>{{$r.money}}</td>
					</tr>
					<tr>
						<th>冻结余额：</th>
						<td>{{$r.frost_money}}</td>
					</tr>
					<tr>
						<th>退款金额：</th>
						<td><input name="amount" value="0.00"/></td>
					</tr>
					<tr>
						<th>退款方式：</th>
						<td>
							<table>
								<tr>
									<td><label><input type="radio" name="bank_type" value="1"/>银行打款</label> </td>
									<td style="background: #f7f7f7;padding: 0px;">
										<table>
											<tr>
												<td width="80">开户行名称：</td>
												<td><input name="bank_config[1][bank_name]" size="20"/></td>
											</tr>
											<tr>
												<td>账号：</td>
												<td><input name="bank_config[1][account_id]" size="30"/></td>
											</tr>
											<tr>
												<td>开户名：</td>
												<td><input name="bank_config[1][account_name]" size="10"/></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td><label><input type="radio" name="bank_type" value="2"/>支付宝</label></td>
									<td style="background: #f7f7f7;padding:0px;">
										<table>
											<tr>
												<td width="80">账号：</td>
												<td><input name="bank_config[2][account_id]" size="30"/></td>
											</tr>
										</table>										
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<div class="ui-layout-footer">
		<input class="btn-ajax-submit" frmid="frm-custom-add" type="button" value="保存 & 关闭"/>
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
	
	
function refund_after(msg,$elt){
	if(msg.status == 'succ'){
		window.close();
		window.opener.grid_finder.datagrid('reload');
	}
	return true;
}
</script>


