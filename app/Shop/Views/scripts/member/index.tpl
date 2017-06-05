<div class="memberCenter">
    {{include file="member/menu.tpl"}}
      <div class="mcContentRight">
        <div class="mcContentRightTop">
          <div class="member_content">
            <ul class="member_tx">
              <li class="member_tx_img"><img src="{{$imgBaseUr}}/{{$member.photo|default:'public/images/member_tx.jpg'}}"/></li>
              
            </ul>
            <ul class="member_main">
              <li class="member_name"><span>{{if $member.nick_name}}{{$member.nick_name}}{{else}}{{$member.user_name}}{{/if}}<span>&nbsp;&nbsp;&nbsp;&nbsp;欢迎回来！</li>
              <li class="member_event first">
                <div class="member_event_tit">事务提醒：</div>
                <div class="member_event_list"><a href="/member/order/ordertype/4">等待付款（{{$feeOrder}}）</a></div>
                <div class="member_event_list"><a href="/member/order/ordertype/6">等待收货（{{$feeOrder2}}）</a></div>
                
                <br class="clearfix"/>
              </li>
              <li class="member_event">
                <div class="member_event_tit">我的收藏：</div>
                <div class="member_event_list"><a href="/member/favorite">收藏数量（{{$fav}}）</a></div>
                <br class="clearfix"/>
              </li>
            </ul>
            <br class="clearfix"/>
          </div>
          <div class="member_img">{{widget class="AdvertWidget" id="31"}}</div>
        </div>
        <div class="mcContentRightMiddle">
          <div class="mccrmTitle"><img src="{{$imgBaseUr}}/public/images/mcContentRightMiddle_title.jpg"/></div>
          <ul class="tj_product">
          	{{section name=v loop=$tui start=1 step=1 max=4 }}
          	<li {{if $smarty.section.v.index eq 4}}class="last"{{/if}}>
              <div class="tj_product_img"><a href='/goods-{{$tui[v].goods_id}}.html' title={{$tui[v].goods_name}}><img src="{{$imgBaseUr}}/{{$tui[v].goods_img}}"/></a></div>
              <p class="tj_product_name">{{$tui[v].title}}</p>
              <div class="tj_product_price">
                <div class="tj_product_price01">品牌：{{$tui[v].brand_name}}</div>
                <div class="tj_product_price02">￥{{$tui[v].price}}</div>
                <br class="clearfix"/>
              </div>
            </li>
            
          	
          	{{/section}}
            
            <br class="clearfix"/>
          </ul>
        </div>
        <div class="mcContentRightBottom">{{widget class="AdvertWidget" id="32"}}</div>
      </div>
      <br class="clearfix"/>
    </div>
  </div>
<script>
  	//清空浏览记录
		function clearCook(url,elt) {
			$.ajax({
				type : "GET",
				cache : false,
				url : url,
				success : function(msg) {
					$(elt).parent().parent().parent().find('.summary_content').empty().html("<div style='color:#999999;padding:10px;'>暂无浏览记录！</div>");;
				}
			});
		}
</script> 
