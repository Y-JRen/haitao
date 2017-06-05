<script>
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js', '', '');
</script>
<div class="title">{{$title}}</div>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title" style="height:25px;">
	<ul id="show_tab">
	   <li onclick="show_tab(1)" id="show_tab_nav_1" class="bg_nav_current">基本设置</li>
	   <li onclick="show_tab(2)" id="show_tab_nav_2" class="bg_nav">折扣设置</li>
	</ul>
</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=index}}')">会员等级列表</a> ]
</div>
<div id="show_tab_page_1" style="display:block">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">会员等级名称 * </td>
<td width="40%"><input type="text" name="rank_name" size="20" maxlength="20" value="{{$rank.rank_name}}" msg="请填写会员等级名称" class="required" /></td>
<td width="10%">特殊会员等级</td>
<td width="40%">{{html_radios name="is_special" options=$specialOptions checked=$rank.is_special separator=""}}</td>
</tr>
<tr>
<td width="10%">积分下限</td>
<td width="40%"><input type="text" name="min_point" size="20" maxlength="9" value="{{$rank.min_point}}" /></td>
<td width="10%">积分上限</td>
<td width="40%"><input type="text" name="max_point" size="20" maxlength="9" value="{{$rank.max_point}}" /></td>
</tr>
<tr>
<td width="10%">显示价格</td>
<td colspan="3">{{html_radios name="show_price" options=$showPriceOptions checked=$rank.show_price separator=""}}</td>
</tr>
</tbody>
</table>
</div>
<div id="show_tab_page_2" style="display:none">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">普通商品折扣率</td>
<td><input type="text" name="discount" size="10" maxlength="3" value="{{$rank.discount.discount}}" /></td>
</tr>
<tr>
<td width="10%">商品类别折扣率</td>
<td><div class="tree_div" style="width:500px; height: 250px; float:left; background-color:#f5f5f5; border:1px solid Silver; overflow:auto;">{{$goodsCat}}</div></td>
</tr>
<tr>
<td width="10%">特殊商品折扣率</td>
<td>
<input type="button" value=" 添加 " onclick="openWin()" />
<div id="discountGoods" style="padding-left:5px; align:left">
{{foreach from=$discountGoods item=discountGood name=discountGood}}
    <p><a href="javascript:fGo()" onclick="removeDiscountGoods(this)" title="删除"><img src="/haitaoadmin/images/delete.png" border="0" /></a><span style="padding-right:8px">{{$discountGood.goods_sn}}</span><span style="padding-right:8px">{{$discountGood.goods_name}}</span><span style="padding-right:8px">{{$discountGood.cat_name}}</span><input type="text" name="goodsDiscount[{{$discountGood.goods_id}}]" value="{{if $discountGood.discount}}{{$discountGood.discount}}{{else}}{{$rank.discount.discount}}{{/if}}" size="15" /></p>
{{/foreach}}
</div>
</td>
</tr>
</table>
</div>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
<br />
</form>
<script>
var win;
function openWin()
{
	win = new dhtmlXWindows();
	win.setImagePath("./scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
	
	var goods = win.createWindow("goodsWin", 300, 150, 610, 500);
	goods.setText("选择商品");
	goods.button("minmax1").hide();
	goods.button("park").hide();
	goods.denyResize();
	goods.denyPark();
	goods.setModal(true);
	goods.attachURL("{{url param.action=select-goods}}", true);
}

function closeWin()
{
    win.window("goodsWin").close();
}

function addDiscountGoods(goodsId, goodsSn, goodsName, goodsCat)
{
    var goodsExists = $('discountGoods').getElements('input[type=text]');
    for (var i = 0; i < goodsExists.length; i++)
    {
        if (goodsExists[i].name == 'goodsDiscount[' + goodsId + ']') {
            return;
        }
    }
	var p = document.createElement("p");
	p.innerHTML = '<a href="javascript:fGo()" onclick="removeDiscountGoods(this)" title="删除"><img src="/haitaoadmin/images/delete.png" border="0" /></a><span style="padding-right:8px">' + goodsSn + '</span><span style="padding-right:8px">' + goodsName + '</span><span style="padding-right:8px">' + goodsCat + '</span><input type="text" name="goodsDiscount[' + goodsId + ']" value="" size="15" />';
	$('discountGoods').appendChild(p);
}

function removeDiscountGoods(obj)
{
    $('discountGoods').removeChild(obj.parentNode);
}
</script>