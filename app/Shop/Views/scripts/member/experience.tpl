<div class="member">
    {{include file="member/menu.tpl"}}
  <div class="memberright">
    <div class="memberddbg">
	 <p>	您的当前经验值为：<span class="highlight" style="color:#D52319;">{{$experience}}</span>
	 </p>
	</div>
	<div style="margin-top:11px;"><img src="{{$_static_}}/images/shop/member_experience.jpg"></div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
				 	<thead>
					<tr>
						<th>变动时间</th>
						<th>经验值</th>
						<th>经验值变动</th>
						<th>备注</th>
					</tr>
					</thead>
					<tbody >
						{{foreach from=$infos item=info}}
						<tr>
							<td>{{$info.created_ts}}</td>
							<td>{{$info.experience_total}}</td>
							<td>{{$info.experience}}</td>
							<td>{{$info.remark}}</td>
						</tr>
						{{/foreach}}
				 </tbody>
				</table>
    <div class="page_nav">{{$pageNav}}</div>
  </div>
</div>