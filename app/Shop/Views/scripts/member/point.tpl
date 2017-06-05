<div class="member">
    {{include file="member/menu.tpl"}}
  <div class="memberright">
    <div class="memberddbg">
	 <p>	您现在的等级为：{{$member.rank_name}}，您的可用积分为：<span class="highlight" style="color:#D52319;">{{$point}}</span>
				<form action="/member/update-rank"  method="post" id="form1">
					<input type="hidden" value="" name="torank" id="torank">
				</form>
	 </p>
	</div>
	<div style="margin-top:11px;"><img src="{{$imgBaseUrl}}/images/shop/member_point.png"></div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
				 	<thead>
					<tr>
						<th>变动时间</th>
						<th>积分</th>
						<th>积分变动</th>
						<th>备注</th>
					</tr>
					</thead>
					<tbody >
						{{foreach from=$pointInfo item=point}}
						<tr>
							<td>{{$point.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
							<td>{{$point.point_total}}</td>
							<td>{{$point.point}}</td>
							<td>{{$point.note}}</td>
						</tr>
						{{/foreach}}
				 </tbody>
				</table>
    <div class="page_nav">{{$pageNav}}</div>
  </div>
</div>
<script>
function updaterank(title, point, rank){
	$('#torank').val(rank);
    if(confirm('确认升级为['+title+'会员]吗，您将被扣除'+point+'积分')){
        $('#form1').submit();
    }
}
</script>