<link href="/_static/js/loadmask/jquery.loadmask.css" media="all" rel="stylesheet" type="text/css" />
<script src='/_static/js/loadmask/jquery.loadmask.min.js'></script>
<link href="/_static/js/asyncbox/skins/Ext/asyncbox.css" media="all" rel="stylesheet" type="text/css" />
<script src='/_static/js/asyncbox/AsyncBox.v1.4.5.js'></script>
<script src='/_static/js/js.js'></script>
<div class="member">
	{{include file="member/menu.tpl"}}
	<div class="memberright">
		<div class="member-box">
			<div class="mb-t">
				账户余额  &gt; 充值
			</div>
			<div class="mb-c">
				<form id="frm-charge-order" action="/member/charge-order-do"
				submitbefor="charge_order_befor">
					<table width="100%">
						<tr>
							<td width="100">充值金额：</td>
							<td>
							<input id="amount" name="amount" style="height: 28px;line-height: 28px;
							font-size: 18px; font-weight: bold;color: red;padding:0px 3px;" size="8"/>
							</td>
						</tr>
						<tr>
							<td>支付方式：</td>
							<td> {{foreach from=$pays item=v key=k}}
							<div style="border-bottom: 1px solid #eee;margin-bottom: 10px;padding:10px 0px;">
								{{foreach from=$v item=vv key=kk}}
								{{if $vv.id != 5}}
								<label>
									<input type="radio" name="pay_id" value="{{$vv.id}}"
									style="position: relative;top:-5px;"/>
									<img src="{{$vv.img_url}}"/></label>&nbsp;&nbsp;
								{{/if}}
								{{/foreach}}
							</div> {{/foreach}} </td>
						</tr>
						<tr>
							<td colspan="2" align="center"><a class="btn-mem btn-ajax-submit" frmid="frm-charge-order" href="javascript:void(0);">保存</a></td>
						</tr>
					</table>
				</form>
			</div>
		</div>

	</div>

	<div style="clear: both;"></div>
</div>

<script>
	$(function() {
		ajaxinit();
	});

	function charge_order_befor($frm, $btn) {

		var amount = $frm.find('input[name=amount]').val();
		var pay_id = $frm.find('input[name=pay_id]:checked').val();
		if(isNull(amount)){
			alert('金额不能为空！');
			return false;
		}
		
		if(!isMoney(amount)){
			alert('金额格式不正确！');
			return false;
		}
		
		if (!pay_id) {
			alert('请选择支付方式！');
			return false;
		}

		return true;
	}

	function isMoney(s) {
		var regu = "^[0-9]+[\.][0-9]{0,2}$|^[0-9]+$";
		var re = new RegExp(regu);
		if (re.test(s)) {
			return true;
		} else {
			return false;
		}
	}

	function isNull(str) {
		if (str == "")
			return true;
		var regu = "^[ ]+$";
		var re = new RegExp(regu);
		return re.test(str);
	}
</script>
