<div class="member">

{{include file="member/menu.tpl"}}
  <div class="memberright">
	<div style="margin-top:11px;"><img src="{{$imgBaseUrl}}/images/shop/member_coupon.png"></div>
	<div class="ordertype">
        <div class="ordertypea">
        <a href="/member/coupon/type/1">可使用</a>
        <a href="/member/coupon/type/2">已无效</a>
        <a href="/member/active-coupon" class="sel">激活优惠券</a>
        </div>
    </div>
    <form name="myForm" id="myForm" action="{{url}}" method="post">
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
                <thead>
                    <tr align="center">
                        <th width="20%">券号：</th>
                        <th width="10%"><input type="text" name="card_sn" id="card_sn" size="20"></th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr align="center">
                        <th >密码：</th>
                        <th ><input type="text" name="card_pwd" id="card_pwd" size="20"></th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr align="center">
                    	<th>&nbsp;</th>
                        <th><input type="button" name="active" value="激活" onclick="doActive()" class="buttons"></th>
                        
                        <th>&nbsp;</th>
                    </tr>
                </thead>
            </table>
            </form>
  </div>
</div>
<script>
function doActive()
{
    if ($('#card_sn').val() == '' || $('#card_pwd').val() == '') {
        alert('请输入完整的券号和密码!');
        return false;
    }
    
    $.ajax({
        url : '/member/active-coupon',
        data : {
		    card_sn : $('#card_sn').val(),
		    card_pwd : $('#card_pwd').val()
		},
		type : 'post',
		success : function(msg) {
            if (msg == 'ok') {
                alert('激活成功!');
                $('#card_sn').val('');
                $('#card_pwd').val('');
            }
            else if (msg == 'card not exists') {
                alert('券号或密码错误!');
            }
            else if (msg == 'card binded') {
                alert('券已被绑定!');
            }
            else if (msg == 'card expired') {
                alert('该券已过期!');
            }
            else if (msg == 'card binded') {
                alert('券已被使用!');
            }
		}
	});
}
</script>