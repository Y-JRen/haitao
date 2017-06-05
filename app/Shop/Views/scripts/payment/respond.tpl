{{include file="flow_header.tpl"}}

{{if $result.stats eq true}}
<div class="pay_success" style='margin:50px auto;width:990px;'>
    	<table width="414" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td><b>支付成功，我们将尽快为您发货！</b></td>
          </tr>
        </tbody></table>
    </div>
  
{{else}} 
<div class="pay_success" style='margin:50px auto;width:990px;'>
    	<table width="414" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="right" rowspan="4"><img width="39" height="38" src="{{$_static_}}/images/cart/icon_fail.jpg"></td>
            <td><b>很遗憾支付失败{{if $result.msg neq ''}},{{$result.msg}}{{/if}}！</b>
            <a href="/member/change-payment/batch_sn/{{$orderinfo.batch_sn}}"><img width="160" height="31" src="{{$_static_}}/images/cart/pay_other.jpg"><br>
              <br>
            </a></td>
          </tr>
          
        </tbody></table>
    </div>
{{/if}}
{{include file="footer.tpl"}}
</div> 
</body>
</html>