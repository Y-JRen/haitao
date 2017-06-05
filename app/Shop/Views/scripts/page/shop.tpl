<script type="text/javascript" src="{{$_static_}}/scripts/getscript.js"></script>
<div class="bread container">
	<ul>
		<li><a href=""><strong>首页</strong></a>></li>
		<li><a href="">CNSC免税店实体店分布</a></li>
	</ul>
</div><!--breadcrumbs-->
<div class="tax-freeshop container">
	<div id="map"><div style="width:731px;height:522px;border:#ccc solid 1px;;" id="dituContent"></div></div>

	<div class="taxcont">
		<div class="taxcont-top">
			<p>CNSC免税店分布</p>
			<select id="cityValue" onchange="changeShop(options[selectedIndex].value)">
				<option value="0">全部</option>
				<option value="1">北京</option>
				<option value="2">上海</option>
				<option value="3">大连</option>
				<option value="4">郑州</option>
				<option value="5">杭州</option>
				<option value="6">青岛</option>
				<option value="7">南昌</option>
				<option value="8">南京</option>
				<option value="9">重庆</option>
				<option value="10">昆明</option>
				<option value="11">合肥</option>
				<option value="12">哈尔滨</option>
			</select>
		</div>
		<div class="storeadr border2" id="shopList">
			<dl>
				<dt>北京免税店</dt>
				<dd>地址：北京市朝阳区惠新东街4号富盛大厦2座1层<br>
					咨询电话：010-84663080<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>上海免税店</dt>
				<dd>地址：上海市静安区江宁路261-293号<br>
					咨询电话：021-62711050<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>大连免税店</dt>
				<dd>地址：大连市沙河口区会展路77#1-1-6号<br>
					咨询电话：0411-84990381<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>郑州免税店</dt>
				<dd>地址：郑州市金水区文化路44-1号<br>
					咨询电话：0371-63902777<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>杭州免税店</dt>
				<dd>地址：杭州市下城区环城西路30号温德姆酒店一层<br>
					咨询电话：0571-85175655<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>青岛免税店</dt>
				<dd>地址：青岛市市南区南京路46号<br>
					咨询电话：0532-85802885<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>南昌免税店</dt>
				<dd>地址：南昌市西湖区广场东路248号<br>
					咨询电话：0791-86229130<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>南京免税店</dt>
				<dd>地址：南京市鼓楼街28号绿地国际商务中心104单元<br>
					咨询电话：025-83168242<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>重庆免税店</dt>
				<dd>地址：重庆市渝中区民权路89号日月光广场1层<br>
					咨询电话：023-63264627<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>昆明免税店</dt>
				<dd>地址：昆明市吴井路21号<br>
					咨询电话：0871-8040303<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>合肥免税店</dt>
				<dd>地址：合肥市政务区潜山路绿地蓝海C座免税店<br>
					咨询电话：0551-63527273<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
			<dl>
				<dt>哈尔滨免税店</dt>
				<dd>地址：哈尔滨市南岗区赣水路天顺街10号<br>
					咨询电话：0451-87709888<br>
					营业时间：早10:00-晚6:30<br>
					网站链接：www.edutyfree.com.cn<br>
                </dd>
			</dl>
		</div>
	</div>
</div>
<script type="text/javascript">
function deploySearch(city){
	var map=new BMap.Map("dituContent");
	map.addControl(new BMap.NavigationControl());
	var local=new BMap.LocalSearch(city,{
	renderOptions:{
	map:map,
	autoViewport:true,
	selectFirstResult:true
	},
	pageCapacity:8
	});
	if(arguments.length>1){
		address=arguments[1];
	}else{
		address="免税店";
	}
	local.search(address);
}
function changeShop(opt){
	var cityList=['北京','上海','大连','郑州','杭州','青岛','南昌','南京','重庆','昆明','合肥','哈尔滨'];
	if(opt==0){
		$('#shopList dl').show()
		$('#dituContent').css('border','0');
		var mapStr='<div style="position:absolute;z-index:1;"><div style="background:url({{$_static_}}/images/map.jpg);width:733px;height:524px;"></div></div><div class="maodian">';
		for(var i=1;i<13;i++){
			mapStr+='<div class="d'+i+'" onclick="changeShop('+i+'),selectValue('+i+')"></div>';	
		}
		mapStr+='</div>';
		$('#dituContent').html(mapStr);
		return false;
	}
	$('#shopList dl').hide();
	$('#shopList dl').eq(opt-1).show();
	if(opt==6){
		deploySearch(cityList[opt-1],'中国出国人员服务总公司青岛免税店');
	}else{
		deploySearch(cityList[opt-1]);
	}	
}
function selectValue(opt){
	$("#cityValue option[value='"+opt+"']").attr('selected','selected');
}
deploySearch("北京");
window.onload=changeShop(0);
</script>