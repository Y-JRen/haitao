<div>
&nbsp;<b>运单号码：{{$logistic_no}}</b>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
  <tr>
    <td>时间</td>
    <td>地点</td>
    <td>状态</td>
    <td>描述</td>
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
