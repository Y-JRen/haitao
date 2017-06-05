<div class="memberCenter">
    {{include file="member/menu.tpl"}}
        <div class="mcContentRight">
            <p class="pro_total">共收藏 <span>{{$total}}</span> 个商品</p>
            <div class="storeList">
                <table>
                    <tr class="first">
                        <td class="first"><input class='checkAll' type="checkbox" />全选</td>
                        <td class="second">商品</td>
                        <td class="third">价格</td>
                        <td class="forth">收藏时间</td>
                        <td align="center">操作</td>
                    </tr>
                    {{foreach from=$info item=goods}}
                    <tr>
                        <td class="listCheck"><input type="checkbox" class='goods' value="{{$goods.favorite_id}}"/></td>
                        <td class="second"><div><a href='/goods/show/id/{{$goods.goods_id}}'>{{$goods.goods_name}}</a></div></td>
                        <td class="third price">￥{{$goods.price}}</td>
                        <td class="forth time">{{$goods.add_time}}</td>
                        <td align="center" class="last">
                        	<a href="/goods/show/id/{{$goods.goods_id}}" target="_black">前去购买</a>
                        	<a href="/goods/del-favorite/favorite_id/{{$goods.favorite_id}}">取消收藏</a>
                        </td>
                    </tr>
                    {{/foreach}}
                </table>
            </div>
            <div class="storePage">
                <ul>
                    <li class="first"><label><input class='checkAll' type="checkbox">全选</label></li>
                    <li><a href='javascript:void(0);' onclick="return chanelFavorite();">取消收藏</a></li>
                    <br class="clearfix"/>
                </ul>
                <div class="pageBox">{{$pageNav}}<br class="clearfix"/></div>
            </div>
        </div>
        <br class="clearfix"/>
    </div>
</div>
<script>
$(function(){
	$(".checkAll").click(function(){
		if($(this).attr("checked")){
			$(".goods").attr("checked",true);
			$(".checkAll").attr("checked",true);
		}else{
			$(".goods").attr("checked",false);
			$(".checkAll").attr("checked",false);
		}
	});
	$(".goods").click(function(){
		$(".checkAll").attr("checked",false);
	});
})


function chanelFavorite()
{
	var values = new Array();
    $(".goods").each(function () {
        if ($(this).is(":checked")) {
        	values.push($(this).attr("value"));
        }
    });
    values = values.join(",");
	if(values == ""){
		alert("没有选中的收藏");
	}else{
		$.post('/member/del-favorites',{favorite_ids:values},function(data){

			alert(data);
			if(data=="删除成功"){
				location.reload();
			}else{
				return false;
			}
		});
	}
}
</script>