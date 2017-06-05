<html><head>
<meta http-equiv="content-type" content="text/html; charset=gbk"><style type="text/css">
* {
	margin:0;
	padding:0
}
body {
	font:12px/1.5 "\5FAE\8F6F\96C5\9ED1", "宋体";font-size:12px;
	color:#333
}
font{ color:#c00;}
.wai{width:19cm; position:relative}
.wrap {
	width:19cm;
	height:24.72cm;
}
.part-0 {
	height:9.7cm;
	padding-left:1cm;
}
.part-1{padding-left:0;}
.part-0 .header {
	height:0.8cm
}
.dispatching {
	height:2cm;
	overflow:hidden
}
.price {
	width:4.6cm
}
.price div{border:1px solid #c00;padding:0.1cm}
.price span {
	font-size:20px;
}
.ziti-info {
	width:6cm;
}
.area-part {
	font-size:20px
}
.ziti-part {
	font-size:14px
}
.barcode {
	width:7cm
}
.barcode img {
	height:80px;
	width:220px
}
.num, .name, .tel {
	width:6.8cm;
}
.t-list td {
	height:0.71cm;
	line-height:0.71cm; font-size:12px
}
.limit {
	width:5cm;
	height:3cm;
	overflow:hidden
}
.part-1 .header {
	height:0.5cm
}
.orderinfo {
	height:0.9cm;font-size:12px;
}
.o-num {
	width:3.9cm
}
.o-time {
	width:5cm
}
.user-name {
	width:5.7cm
}
.names{float:left;line-height:0.7cm;}
.names p{ margin:0; padding:0;}
.user-name p{width:4cm;height:0.7cm;line-height:0.7cm;overflow:hidden}
.goods-cont {text-align:right}
.goods-cont strong{font-size:14px}
.freighter {
	width:2cm
}
.pnum, .p-price {
	width:2.1cm
}
.pcout {
	width:1.1cm
}
.maintenance {
	width:2.9cm
}
.subtotal {
	width:1.5cm
}
.goodslist {
	border-collapse:collapse;
	border:1px solid #000
}
.goodslist th, .goodslist td {
	border:1px solid #000;font-size:12px;
}
.goodslist td {
	padding:1px;font-size:12px;
}
.goodslist th {
	height:0.6cm;
	font-weight:normal;font-size:12px;
}
.code-num {
	font-size:35px;
	width:5.6cm;
	text-align:center;vertical-align:top;border:1px solid #c00;
}
.pc-num{height:0.45cm;text-align:right}
.fj-code{padding-top:0cm;font-size:14px;text-align:left}
.adress{padding-top:0.1cm}
.store{font-size:14px}
.sign{border:1px solid #c00;width:4.3cm;padding:0.1cm;}
.sign div{text-align:right}
</style>
<style type="text/css" media="print">
.v-h {
	/*visibility:hidden*/
}
</style>
<style media="print">
.Noprint{display:none;}
.PageNext{page-break-after: always;}
</style>
<script>
function nextPageHandle(table_id){
	var table = document.getElementById(table_id);
	alert('table`s height:'+table.offsetHeight);
	if(table.offsetHeight>425){
		document.getElementById('div_'+table_id).innerHTML = '&nbsp;';
	}
}
</script>
</head><body>
{{foreach from=$datas item=data}}
<div class="wai">
<div class="wrap">
	<table class="header" width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody><tr>
            <td style="height: 50px; font-size: 20px; color: rgb(204, 0, 0); text-align: center; line-height: 50px; letter-spacing: 2px; position: relative;"><div style="position:absolute; left:0; top:0px; font-size:10px;"><img src="/haitaoadmin/images/jingdong/logo.gif" alt="京东商城" width="150" height="43"></div><div style="position:absolute; right:0; top:0; font-size:16px"></div><font style="font-size:20px; line-height:20px">京东商城(www.360buy.com)购物清单<br>网购上京东　省钱又放心</font></td>
		</tr>
	</tbody></table>
		<div class="part-0">

				<div class="pc-num">{{$order.order_sn}}</div>
				<table class="dispatching" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tbody><tr>
								<td class="ziti-info">
								<div class="store"><font style="font-weight:bold;color:black;">客户信息</font></div>
								<div class="area-part"></div>
										<div class="ziti-part"></div></td>
								<td class="barcode" align="center"></td>
						  <td class="price"><div><font style="font-size:12px">订单金额：</font><br><span>￥{{$data.order.price_order}}</span>元</div></td>
						</tr>
				</tbody></table>
				<table class="t-list" id="num-time" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tbody><tr>
								<td class="num"><span class="v-h">订单编号：</span>{{$data.bill.bill_no}}</td>
								<td class="time"><span class="v-h">出库时间：</span>{{$data.bill.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
						</tr>
				</tbody></table>
				<table class="t-list" id="name-advice" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tbody><tr>
								<td class="name"><span class="v-h">客户姓名：</span>{{$data.order.addr_consignee}}</td>
								<td class="advice"><span class="v-h">是否送货前通知：</span> 否 </td>
						</tr>
				</tbody></table>
				<table class="t-list" id="tel-deliver" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tbody><tr>
								<td class="tel"><span class="v-h">联系电话：</span>{{$data.order.addr_mobile}}</td>
								<td class="deliver"><span class="v-h">送货时间：</span>{{$data.bill.add_time|date_format:"%Y-%m-%d"}}</td>
						</tr>
				</tbody></table>
				<table class="t-list" id="adress" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tbody><tr>
								<td class="adress"><span class="v-h">客户地址：</span>{{$data.order.addr_province}} {{$data.order.addr_city}} {{$data.order.addr_area}} {{$data.order.addr_address}}</td>
						</tr>
				</tbody></table>
                <table class="t-list" id="adress" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tbody><tr>
								<td class="adress"><span class="v-h">发票：</span>{{$data.order.invoice_title}}</td>
						</tr>
				</tbody></table>
				<table class="dispatching" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tbody><tr>
								<td class="ziti-info">
								<div class="store"><font style="font-weight:bold;color:black;">商品信息</font></div>
								<div class="area-part"></div>
								<div class="ziti-part"></div></td>
								<td class="barcode" align="center"></td>
						</tr>
				</tbody></table>
				<table id="1" class="goodslist" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tbody><tr>
								<th class="pnum">商品编号</th>
								<th class="pname">商品名称</th>
								<th class="pcout">数量</th>
								<th class="p-price">价格</th>
								<th class="subtotal">小计</th>
						</tr>
						{{foreach from=$data.details item=d key=key}}
										<tr style="height: 25px;">
        								<td class="pnum">{{$d.product_sn}}</td>
        								<td class="pname">{{$d.goods_name}}</td>
        								<td class="pcout">{{$d.number}}</td>
        								<td class="p-price">{{$d.shop_price}}</td>
        								<td class="subtotal">{{math equation="x * y" x=$d.shop_price y=$d.number}}</td>
        						</tr>
    					{{/foreach}}
    															</tbody></table><br>
                <hr size="1" width="100%" noshade="noshade" align="center"><br>
                <table id="1" class="goodslist" width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody><tr>
                        <td>
                        <font style="font-weight:bold;color:black;">服务承诺</font><br>
                        
&nbsp;&nbsp;&nbsp;&nbsp;全国联保，统一网上报修，在线响应。在产品保修期内，只需要您登录我们网站 
www.360buy.com 在“我的京东-我的返修”中进行在线提交返修申请单，我们的工作人员会及时审核确认并帮助您进行后续处理。<br>
                        
&nbsp;&nbsp;&nbsp;&nbsp;当您有需要返修/退货商品时可通过以下方式与我们联系，客服电话:400-606-5500 
具体退货参考信息您可登录京东商家帮助中心查询： http://www.360buy.com/help。<br>
                        <font style="font-weight:bold;color:black;">退换货流程</font><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;上门取件退货/保修/换货流程：在线提交申请一审核确认一上门取件一登记检测一退款或换货/保修。<br>
	                    &nbsp;&nbsp;&nbsp;&nbsp;邮寄取件退货/保修/换货流程：在线提交申请一审核确认一寄回京东一登记检测一退款或换货/保修。<br>
	                    &nbsp;&nbsp;&nbsp;&nbsp;备注:请登录www.360buy.com进行在线提交返修申请单，申请通这后根据网站提示选择邮寄地址。
                        </td>
                    </tr>
                </tbody></table>
		</div>
</div>
</div>

    <div style="padding-top: 20px;">
    <table style="margin-bottom: 40px;" width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
        <tbody><tr>
            <td style="height: 50px; font-size: 20px; color: rgb(204, 0, 0); text-align: center; line-height: 50px; letter-spacing: 2px; position: relative;">
                <div style="position:absolute; left:40px;text-align:center;">
                    <table>
                        <tbody><tr>
                            <td>
                                <font style="font-size:20px;font-weight:bold;">家用电器、手机数码、电脑产品、日用百货、尽在&nbsp;</font>
                            </td>
                            <td>
                                <span>
                                    <img src="/haitaoadmin/images/jingdong/dingdandayinlogo.png" alt="京东商城" style="margin-top: 10px;" width="200" height="30">
                                </span>
                            </td>
                        </tr>
                    </tbody></table>
                </div>
                <div style="position:absolute; right:0; top:0; font-size:16px"></div>
            </td>
        </tr>
    </tbody></table> 
    </div>
{{/foreach}}
</body></html>