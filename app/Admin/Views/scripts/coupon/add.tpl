<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<script>
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js', createWin);
var win;
function createWin()
{
    win = new dhtmlXWindows();
    win.setImagePath("/scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
}
</script>
<form name="myForm" id="myForm" action="{{url param.action=add}}" method="post">
<input type="hidden" name="GoodsNum" value="0">
<div class="title">添加礼券</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=log }}')">礼券发放记录</a> ]
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">礼券类型 * </td>
<td width="40%">
<select name="card_type" onchange="showBlock()">
	{{html_options options=$cardType selected=0}}
</select>
</td>
<td width="11%">是否可重复使用 * </td>
<td width="39%">
<select name="is_repeat">
	{{html_options options=$isRepeat selected=0}}
</select>
</td>
</tr>
</tbody>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%" id="type012_1">礼券价格 * </td>
<td width="10%" id="type3_1" style="display:none">抵扣方式 * </td>
<td width="10%" id="type4_1" style="display:none">订单折扣 * </td>
<td id="type012_2"><input type="text" name="card_price" id="card_price" size="6" maxlength="6" /></td>
<td id="type3_2" style="display:none;">
  <span style="float:left">
  <select name="deductionType" id="deductionType" onchange="showDeductionPrice()">
    <option value="1">商品全额</option>
    <option value="2">商品金额抵扣</option>
  </select>  
  
  </span>
  <span id="deduction_price_area" style="display:none">&nbsp;<input type="text" name="card_price1" id="card_price1" size="6" maxlength="6" msg="请填写抵扣价格" onkeypress="return NumOnly(event)"/></span>
</td>
<td width="10%">生成数量 * </td>
<td width="40%"><input type="text" name="number" id="number" size="6" maxlength="6" msg="请填写生成数量" class="required int" /></td>
</tr>
<tr>
<td>开始日期 *</td>
<td>   <input type="text" name="start_date" id="start_date" size="11" value="{{$smarty.now|date_format:"%Y-%m-%d"}}"    class="Wdate"   onClick="WdatePicker()" />
截止日期 * <input type="text" name="end_date" id="end_date" size="11" value="{{$smarty.now|date_format:"%Y-%m-%d"}}"    class="Wdate"   onClick="WdatePicker()" /></td>
<td>绑定联盟ID</td>
<td><input type="text" name="parent_id" id="parent_id" size="10" maxlength="10" />&nbsp;&nbsp;
联盟下级参数：<input type="text" name="aid" id="aid"   value="0" size="10"  />0为不指定
<input type="checkbox" name="is_limit_user" id="is_limit_user" value="1" onclick="check_limit_parent_id();"/>是否限定联盟ID才可以使用
<input type="checkbox" name="is_affiliate" id="is_affiliate" value="1" onclick="check_parent_id();"/>是否按照券分成</td>
</tr>
<tr>
<td id="type234_1" style="display:none">最低订单价格</td>
<td id="type234_2" style="display:none"><input type="text" name="min_amount" id="min_amount" size="6" maxlength="6" onkeypress="return NumOnly(event)"/></td>
<td id="type12_3">购买指定商品</td>
<td id="type12_4">
<input type="hidden" name="goods_info[allGoods]" id="allGoods" value="{{$offers.config.allGoods}}"/><input type="button" value="设置商品范围" onclick="openAllGoodsWin('checkbox', 'allGoods')" />
<!--<input type="hidden" name="goods_info[allGroupGoods]" id="allGroupGoods" value="{{$offers.config.allGroupGoods}}"/><input type="button" value="设置组合商品范围" onclick="openAllGroupGoodsWin('checkbox', 'allGroupGoods')" />-->
</td>
<td id="type3_3" style="display:none">绑定商品编号</td>
<td id="type3_4" style="display:none">
<div id="numArea">
  <div id="numGoods0">
    <input type="text" name="goods_id0" id="goods_id0" size="8" maxlength="9"/>
    &nbsp;&nbsp;
    <a href="#" onclick="reduceNum(0);return false;"><img src="/haitaoadmin/images/tree_collapse.gif"></a>
    <input type="text" name="goods_num0" id="goods_num0" size="2" maxlength="2" value="1" readonly/>
    <a href="#" onclick="addNum(0);return false;"><img src="/haitaoadmin/images/tree_expand.gif"></a>
    &nbsp;&nbsp;
    <a href="#" onclick="addNumArea();return false;">添加商品ID</a>
  </div>
</div>
</td>
</tr>
<tr>
  <td>运费</td>
  <td>
    减免 <input type="text" name="freight" id="freight" value="0" style="width:30px"> 元
    <font color="#999999">(0为不减免运费，10为免运费)</font>
  </td>
  <td id="type3_5" style="display:none" colspan="2">
    <input type="checkbox" name="exclusive_except" value="1"> 当订单<b>仅有</b>站内站外专享活动的<b>0元</b>商品时，不能使用该券
    
    <br>
    <input type="checkbox" name="price_recount" value="1"> 扣减金额按订单中符合条件商品数量叠加
    
  </td>
</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">备注</td>
<td colspan="3"><textarea style="width: 500px;height: 200px" name="note"></textarea></td>
</tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function check_parent_id(obj) {
    var parent_id = $('parent_id');
    var regNum = /^[-\+]?\d+$/;
    if(!parent_id.value.match(regNum)){
        alert('警告：按照券绑定分成需要填写正确的绑定联盟ID！');
        $('is_affiliate').checked = false;
    }
}
function check_limit_parent_id(obj) {
    var parent_id = $('parent_id');
    var regNum = /^[-\+]?\d+$/;
    if(!parent_id.value.match(regNum)){
        alert('警告：如果该券要限定联盟使用，请填写正确的联盟ID！');
        $('is_affiliate').checked = false;
    }
}

function showBlock() {
    var value = document.myForm.card_type.value;
    if (value == '3' || value == '5') {
        $('type3_1').style.display='';
        $('type012_1').style.display='none';
        $('type4_1').style.display='none';
        
        $('type012_2').style.display='none';
        $('type3_2').style.display='';
        $('type3_2').float = 'right';
        
        $('type234_1').style.display='';
        $('type234_2').style.display='';
        
        $('type3_3').style.display='';
        $('type3_4').style.display='';
        $('type3_5').style.display='';
        
        $('type12_3').style.display='none';
        $('type12_4').style.display='none';
        
        $('freight').checked = true;
    }
    else {
        if ((value == '0') || (value == '1') || (value == '4')) {
            $('type12_3').style.display='';
            $('type12_4').style.display='';
        }
        else {
            $('type12_3').style.display='none';
            $('type12_4').style.display='none';
        }
    
        if ((value == '1') || (value == '4')) {
            $('type234_1').style.display='';
            $('type234_2').style.display='';
        }
        else {
            $('type234_1').style.display='none';
            $('type234_2').style.display='none';
            $('min_amount').value='';
        }
        $('type012_1').style.display='';
        $('type012_2').style.display='';
        $('type3_1').style.display='none';
        $('type3_2').style.display='none';
        $('type3_3').style.display='none';
        $('type3_4').style.display='none';
        $('type3_5').style.display='none';
        
        if (value == '4') {
            $('type012_1').style.display='none';
            $('type4_1').style.display='';
        }
        else {
            $('type012_1').style.display='';
            $('type4_1').style.display='none';
        }
        
        $('freight').checked = false;
    }
}
function showDeductionPrice()
{
    if ($('deductionType').value == 2) {
        $('deduction_price_area').style.display = 'block';
    }
    else {
        $('deduction_price_area').style.display = 'none';
        $('card_price1').value = '0';
    }
}
function addNum(index)
{
    var obj = $('goods_num'+index);
    if (obj.value == '')    obj.value = 0;
    obj.value++;
    
}
function reduceNum(index)
{
    var obj = $('goods_num'+index);
    if ((obj.value == '') || (obj.value == '0'))    obj.value = 1;
    obj.value--;
}
function addNumArea()
{
    if ($('goods_id0').value=='') {
        alert('商品编号必须填写!');
        return false;
    }
    if (($('goods_num0').value=='') || ($('goods_num0').value=='0')) {
        alert('商品数量必须填写!');
        return false;
    }
    
    document.myForm.GoodsNum.value = 1 + parseInt(document.myForm.GoodsNum.value);
    var index = parseInt(document.myForm.GoodsNum.value);
    var elm = document.createElement(" ");
    elm.innerHTML = '<div id="numGoods'+index+'"><input type="text" name="goods_id'+index+'" id="goods_id'+index+'" size="8" maxlength="9" value="'+$('goods_id0').value+'" onkeypress="return NumOnly(event)"/>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="reduceNum('+index+');return false;"><img src="/haitaoadmin/images/tree_collapse.gif"></a>&nbsp;<input type="text" name="goods_num'+index+'" id="goods_num'+index+'" size="2" maxlength="2" value="'+$('goods_num0').value+'" readonly/>&nbsp;<a href="#" onclick="addNum('+index+');return false;"><img src="/haitaoadmin/images/tree_expand.gif"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="this.parentNode.parentNode.removeChild(this.parentNode);return false;">删除</a></div>';
    var parent = $('numArea');
    parent.appendChild(elm);
    $('goods_id0').value = '';
    $('goods_num0').value = '1';
}

function NumOnly(e)
{
    var key = window.event ? e.keyCode : e.which;
    return key>=48&&key<=57||key==46||key==8;
}

/**
 * 打开商品设置窗口
 *
 * @param    string    intype         输入类型(input,checkbox)
 * @param    string    discountInput  保存设置的隐藏输入框名称及ID
 * @param    int       show           是否显示分类及全局配置
 * @return   void
 */
function openAllGoodsWin(intype, discountInput)
{
	var goods = win.createWindow("allGoodsWin", 300, 150, 610, 380);
	goods.setText("商品设置");
	goods.button("minmax1").hide();
	goods.button("park").hide();
	goods.denyResize();
	goods.denyPark();
	goods.setModal(true);
	goods.attachURL("/admin/offers/select-all-goods/id/{{$offers.offers_id}}/job/search/intype/" + intype + '/discountinput/' + discountInput + '/offersType/{{$offersType}}/discountvalue/' + JSON.encode($(discountInput).value), true);
}

/**
 * 打开组合商品设置窗口
 *
 * @param    string    intype         输入类型(input,checkbox)
 * @param    string    discountInput  保存设置的隐藏输入框名称及ID
 * @param    int       show           是否显示分类及全局配置
 * @return   void
 */
function openAllGroupGoodsWin(intype, discountInput)
{
	var goods = win.createWindow("allGroupGoodsWin", 300, 150, 610, 380);
	goods.setText("组合商品设置");
	goods.button("minmax1").hide();
	goods.button("park").hide();
	goods.denyResize();
	goods.denyPark();
	goods.setModal(true);
	goods.attachURL("/admin/offers/select-all-group-goods/id/{{$offers.offers_id}}/job/search/intype/" + intype + '/discountinput/' + discountInput + '/offersType/{{$offersType}}/discountvalue/' + JSON.encode($(discountInput).value), true);
}

function closeAllGoodsWin()
{
    win.window("allGoodsWin").close();
}

function closeAllGroupGoodsWin()
{
    win.window("allGroupGoodsWin").close();
}

function openGoodsWin(intype, offersType)
{
	var goods = win.createWindow("goodsWin", 300, 150, 610, 500);
	goods.setText("选择商品");
	goods.button("minmax1").hide();
	goods.button("park").hide();
	goods.denyResize();
	goods.denyPark();
	goods.setModal(true);
	goods.attachURL("/admin/offers/select-goods/intype/" + intype + '/offersType/' + offersType, true);
}

function openGroupGoodsWin(intype, offersType)
{
	var goods = win.createWindow("goodsWin", 300, 150, 610, 500);
	goods.setText("选择商品");
	goods.button("minmax1").hide();
	goods.button("park").hide();
	goods.denyResize();
	goods.denyPark();
	goods.setModal(true);
	goods.attachURL("/admin/offers/select-group-goods/intype/" + intype + '/offersType/' + offersType, true);
}

function closeGoodsWin()
{
    win.window("goodsWin").close();
}

function addDiscountGoods(intype, goodsId, goodsSn, goodsName, goodsColor, goodPrice)
{
    var goodsExists = $('discountGoods').getElements('input[name^=goodsDiscount]');
    for (var i = 0; i < goodsExists.length; i++)
    {
        if (goodsExists[i].name == 'goodsDiscount[' + goodsId + ']') {
            return;
        }
    }
	var p = document.createElement("p");
	var input;
	if (intype == 'text') {
	    input = '<input type="text" name="goodsDiscount[' + goodsId + ']" value="" size="15" />';
	} else if (intype == 'checkbox') {
	    input = '<input type="checkbox" name="goodsDiscount[' + goodsId + ']" value="1" checked=true />';
	}
	p.innerHTML = '<a href="javascript:fGo()" onclick="removeDiscountGoods(this)" title="删除"><img src="/haitaoadmin/images/delete.png" border="0" /></a><span style="padding-right:8px">' + goodsSn + '</span><span style="padding-right:8px">' + goodsName + '</span><span style="padding-right:8px">' + goodsColor + '</span><span style="padding-right:8px">' + goodPrice + '</span>' + input;
	$('discountGoods').appendChild(p);
	$('select_button_' + goodsId).style.backgroundColor='#ccc';
	$('select_button_' + goodsId).value='已选';
}

function removeDiscountGoods(obj)
{
    $('discountGoods').removeChild(obj.parentNode);
}

function storeDiscount(discountInput)
{
    var msg = '';
    var tab = $('distcountForm');
    
    if (tab.getElement('input[name=discount]').value != '' && !/^[\d|\.|,]+$/.test(tab.getElement('input[name=discount]').value)) {
        msg += '请输入正确的格式!\n';
    }
    
    var catDiscount = tab.getElements('input[name^=catDiscount]');
    for (var i = 0; i < catDiscount.length; i++)
    {
        if (catDiscount[i].value != '' && !/^[\d|\.|,]+$/.test(catDiscount[i].value)) {
            msg += '请输入正确的格式!\n';
            break;
        }
    }
    
    var goodsDiscount = tab.getElements('input[name^=goodsDiscount]');
    for (var i = 0; i < goodsDiscount.length; i++)
    {
        if (goodsDiscount[i].value != '' && !/^[\d|\.|,]+$/.test(goodsDiscount[i].value)) {
            msg += '请输入正确的格式!\n';
            break;
        }
    }
    
    if (msg.length > 0) {
        alert(msg);
        return false;
    } else {
        if ($(discountInput)) {
            $(discountInput).value=$('distcountForm').toQueryString();
            return true;
        }
    }
}

</script>