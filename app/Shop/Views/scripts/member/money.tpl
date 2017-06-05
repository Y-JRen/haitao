<div class="member">
	{{include file="member/menu.tpl"}}
	<div class="memberright">
		<div class="member-box">
			<div class="mb-t"> 账户余额 </div>
			<div class="mb-c">
				<p class="fl">您的账户余额为：<span>￥<font color="#D52319" size="4">{{$money}}</font></span></p>
				<a class="fr btn-mem" href="/member/charge">充值</a>
				<div style="clear:both;"></div>
			</div>
		</div>
		<div style="height: 10px;overflow: hidden;"></div>
		{{include file="member/inc-amount-tab.tpl"}}
		<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
			<thead>
				<tr>
					<th>变动时间</th>
					<th>账户余额</th>
					<th>余额变动</th>
					<th>备注</th>
				</tr>
			</thead>
			<tbody>
				{{foreach from=$moneyInfo item=money}}
				<tr>
					<td>{{$money.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
					<td>{{$money.money_total}}</td>
					<td>{{$money.money}}</td>
					<td align="left">{{$money.note}}</td>
				</tr>
				{{/foreach}}
			</tbody>
		</table>
		<div class="page_nav">
			{{$pageNav}}
		</div>
	</div>
	<div style="clear: both;"></div>
</div>

