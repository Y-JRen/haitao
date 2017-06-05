{{if $user}}
<div class="second">
  <ul class="top_cart">
  {{foreach from=$datas item=data}}
    <li class="clear">
      <a href="/goods-{{$data.goods_id}}.html" target=_blank><img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_60_60.'}}"/></a>
      <span><a href="/goods-{{$data.goods_id}}.html" target=_blank>{{$data.goods_name}}</a></span>
      <a href="javascript:;" onclick="indexAddCart('{{$data.goods_sn}}');return false;" class="buttons">购 买</a>
    </li>
  {{/foreach}}
  </ul>
  <div class="more"><a href="/member/favorite">查看全部收藏 &gt;&gt;</a></div>
</div>
{{else}}
<div class="first">您需要 <a href="login.html" class="blue">登录</a> 后才能查看收藏商品！</div>
{{/if}}