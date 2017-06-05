<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table_form">
    <tbody>
        <tr>
          <th colspan="6">银行转账</th>
          </tr>
        <tr><th>开行名称</th>
          <td>{{$bank.bank}}</td>
          <th>帐号</th>
          <td>{{$bank.account}}</td>
          <th>开户名</th>
          <td>{{$bank.user}}</td>
          </tr>
        <tr>
          <th colspan="6">邮局汇款</th>
        </tr>
        <tr>
          <th>汇款地址</th>
          <td>{{$bank.address}}</td>
          <th>邮编</th>
          <td>{{$bank.zip}}</td>
          <th>姓名</th>
          <td>{{$bank.name}}</td>
          </tr>
    </tbody>
</table>
</div>