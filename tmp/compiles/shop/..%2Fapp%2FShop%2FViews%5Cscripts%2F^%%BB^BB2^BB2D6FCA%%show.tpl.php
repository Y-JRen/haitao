<?php /* Smarty version 2.6.19, created on 2015-01-27 09:27:20
         compiled from goods/show.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'goods/show.tpl', 15, false),array('modifier', 'cut_str', 'goods/show.tpl', 127, false),array('modifier', 'date_format', 'goods/show.tpl', 132, false),array('modifier', 'stripslashes', 'goods/show.tpl', 138, false),)), $this); ?>

<div class="bread container">
	<ul>
		<li><a href="/"><strong>首页</strong></a>></li>
		<li><a href="/gallery-<?php echo $this->_tpl_vars['nav']['0']['cat_id']; ?>
-0-0-0-1.html"><strong><?php echo $this->_tpl_vars['nav']['0']['cat_name']; ?>
</strong></a>></li>
		<li><a href="/gallery-<?php echo $this->_tpl_vars['nav']['1']['cat_id']; ?>
-0-0-0-1.html"><strong><?php echo $this->_tpl_vars['nav']['1']['cat_name']; ?>
</strong></a>></li>
		<li><a href="javascript:void(0);"><?php echo $this->_tpl_vars['data']['goods_name']; ?>
</a></li>
	</ul>
</div><!--breadcrumbs-->
<div class="productDetail container">
	<div class="product-top">
      <div class="syFocusThumb">
      	<div class="jqzoom">
      		<a href="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo $this->_tpl_vars['data']['goods_img']; ?>
"  class="jqzoom" rel='gal1'  title="triumph">
      			<img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
" height="400" />      
      		</a>
      	</div>

      	<div class="spec_list" id="pic_small_wrapper">
      		<a href="javascript:;" onfocus="this.blur();"   class="iconbg btn_left  btn_left_disable ">
      			< </a>
      				<a href="javascript:;" onfocus="this.blur();"  class="iconbg btn_right ">></a>
      				<div class="spec_item" id="list_smallpic">
      					<ul id="thumblist">
      						<li>	
	                			<a onfocus="this.blur();" class="active"   class="active"  href="javascript:void(0)"  rel="{gallery: 'gal1', smallimage: '<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
',largeimage: '<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo $this->_tpl_vars['data']['goods_img']; ?>
'}" >
	                        		<img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" height="56"  alt="<?php echo $this->_tpl_vars['data']['goods_name']; ?>
" >
	                       		</a>
	                		</li>
      						<?php $_from = $this->_tpl_vars['imgurl']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
      						<li>
      							<a onfocus="this.blur();" href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['img_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
',largeimage: '<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo $this->_tpl_vars['v']['img_url']; ?>
'}" >
      								<img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['img_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
"  width="57" height="57"  alt="<?php echo $this->_tpl_vars['v']['img_desc']; ?>
" >
      							</a>
      						</li>
      						<?php endforeach; endif; unset($_from); ?>
      					</ul>
      				</div>
      			</div>
      		</div>
		<div class="product-info">
			<h1><?php echo $this->_tpl_vars['data']['goods_name']; ?>
</h1>
			<h2><?php echo $this->_tpl_vars['data']['goods_alt']; ?>
</h2>
			<div class="divider"></div>
			商品编号：<?php echo $this->_tpl_vars['data']['goods_sn']; ?>
<br>
			<span>品&nbsp;&nbsp;&nbsp;牌：<a href='/region-search-<?php echo $this->_tpl_vars['data']['brand_id']; ?>
-0-0-1.html'><?php echo $this->_tpl_vars['data']['brand_name']; ?>
</a></span><span>产&nbsp;&nbsp;&nbsp;地：<?php echo $this->_tpl_vars['data']['region_name']; ?>
</span><br>
			<p class="product_info_price">市场价格：<i>￥<?php echo $this->_tpl_vars['data']['market_price']; ?>
</i></p>
            <p class="product_info_price">本店价格：￥<?php echo $this->_tpl_vars['data']['shop_price']; ?>
</p>
            <p class="product_info_tax">行邮税：￥<?php echo $this->_tpl_vars['data']['tax']; ?>
  <?php if ($this->_tpl_vars['data']['tax'] <= 50): ?><span style='height:22px;line-height:22px;display:inline-block; color:#d7a21e;border:1px solid #f7e7d7;padding:0 5px;font-size:12px;background:#f6fbe7;'>单笔订单行邮税总额低于50元（含50元）予以免征</span><?php endif; ?></p>
            <p class="product_info_price">商品总价：<b>￥<?php echo $this->_tpl_vars['data']['price']; ?>
</b></p>
            <div class="service"><i>服务承诺：</i><p></p></div>
            <div class="divider"></div>
            <div class="guige">
            	<strong>规格分类：</strong>
            	<input type='hidden'  value="<?php echo $this->_tpl_vars['product']['0']['product_sn']; ?>
" id='product' name='product'/>
				<?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
					<a class="guige" rel="{gallery: 'gal1', smallimage: '<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['product_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
',largeimage: '<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo $this->_tpl_vars['v']['product_img']; ?>
'}">
						<img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['product_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" style='width:38px;height:38px;' val=<?php echo $this->_tpl_vars['v']['product_sn']; ?>
 class="product_type"  title="<?php echo $this->_tpl_vars['v']['goods_style']; ?>
"  />
					</a>
				<?php endforeach; endif; unset($_from); ?>
			</div>
            <div class="clearfix"></div>
            <div class="shuliang"><strong>购买数量：</strong>
             <a href="javascript:void(0);" class="plus" id="reduce">-</a><input type="text" id='buynum' value="1" onchange='isInt(this)'><a class="plus" href="javascript:void(0);" id="add" >+</a>
             <?php if ($this->_tpl_vars['data']['onsale'] == 0 && $this->_tpl_vars['data']['able_number'] != 0): ?>
             <input class="button" type="button" onclick="goods_add('buy');" value="加入购物车">
             <input class="button" type="button" onclick="goods_add('buy_now');" value="立刻购买">
             <?php elseif ($this->_tpl_vars['data']['onsale'] == 0 && $this->_tpl_vars['data']['able_number'] == 0): ?>
             <input class="button" type="button" onclick="document.getElementById('notice').style.display='block';document.getElementById('fade').style.display='block'" value="到货通知">
             <?php else: ?>
             <span>此商品已下架</span>
             <?php endif; ?>
             
             <dl id="notice" class="notice">
             	<dt>到货通知</dt>
             	<dd><b>商品到货时请短信或者邮件通知我</b></dd>
                <dd>
	                <form onsubmit="return check_notice()" id='frm_notice'>
	                 	<input type="hidden" name="goods_id" value="<?php echo $this->_tpl_vars['data']['goods_id']; ?>
" />
		                <p><span>我的手机</span><input type="text" id='mobile' name='mobile'></p>
		                <p><span>我的邮箱</span><input type="text" id='email' name='email'></p>
		                <p><input type="submit" value="确定" onclick = ""></p>
	                </form>
                </dd>
                 <a href = "javascript:void(0)" onclick = "document.getElementById('notice').style.display='none';document.getElementById('fade').style.display='none'" class="closemark">×</a></div>
            </dl>
            <div class="divider"></div>
            <div class="w_share">
	             <div class="share"><a class="png_bg" href = "javascript:void(0)">分享</a>
	                <dl>
	                 	<dd><a href="javascript:window.open('http://v.t.sina.com.cn/share/share.php?title=国人海淘网上这个产品看起来不错，长草了&url='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到新浪微博"  class="sina"></a></dd>
		            	<dd><a href="javascript:window.open('http://v.t.qq.com/share/share.php?title=国人海淘网上这个产品看起来不错，长草了&url='+encodeURIComponent(window.location.href)+'&source=bookmark','_blank','width=610,height=350');void 0" title="分享到腾讯微博"  class="tx"></a></dd>
		            	<dd><a href="javascript:window.open('http://www.kaixin001.com/repaste/share.php?rtitle=国人海淘网上这个产品看起来不错，长草了&rurl='+encodeURIComponent(window.location.href)+'&rcontent=看中一个好东东，很好看，是国药电商的'+encodeURIComponent(document.title)+' 亲爱的您也看下吧','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到开心网"  class="kai"></a></dd>
		            	<dd><a href="javascript:window.open('http://cang.baidu.com/do/add?it=国人海淘网上这个产品看起来不错，长草了&iu='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到百度收藏" class="baidu"></a></dd>
		            	<dd><a href="javascript:window.open('http://widget.renren.com/dialog/share?resourceUrl='+encodeURIComponent(window.location.href)+'&srcUrl='+encodeURIComponent(window.location.href)+'&title=国人海淘网上这个产品看起来不错，长草了&pic= &description=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes');void 0" title="分享到人人网" class="ren"></a></dd>
		            	
	                </dl>
	            </div>
	            <div class="collection">
	                <a class="png_bg" href = "javascript:void(0)" onclick="favGoods(this,'<?php echo $this->_tpl_vars['data']['goods_id']; ?>
')">收藏 <?php echo $this->_tpl_vars['data']['count']; ?>
</a>
	            	<dl id="collect_i" class="collect_i">
	            	  <dt>消息</dt>
	            	  <dd class="collect_dd1">请先登录！</dd>
	            	  <dd class="collect_dd2"><button onclick = "document.getElementById('collect_i').style.display='none';document.getElementById('fade').style.display='none'">确定</button></dd>
	            	  <a href = "javascript:void(0)" onclick = "document.getElementById('collect_i').style.display='none';document.getElementById('fade').style.display='none'" class="closemark">×</a>
	            	</dl>
	            </div>
	            <div id="fade" class="black_overlay"></div> 
            </div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="product-main">
		<div class="productM-left">
		    <div class="Menubox">
		      <ul>
			      <li id="two1" onClick="setTab('two',1,4)" class="hover"><a href="javascript:;">商品详情</a></li>
			      <li id="two2" onClick="setTab('two',2,4)"><a href="javascript:;">品牌介绍</a></li>
			      <li id="two3" onClick="setTab('two',3,4)"><a href="javascript:;">配送与邮费</a></li>
			      <li id="two4" onClick="setTab('two',4,4)"><a href="javascript:;">售后服务</a></li>
		      </ul>
		    </div>
		    <div class="Contentbox">
		        <div id="con_two_1">
		          <div class="attributes">
		           <div class="attribute">
		          	<p>商品名称：<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_name'])) ? $this->_run_mod_handler('cut_str', true, $_tmp, 25) : smarty_modifier_cut_str($_tmp, 25)); ?>
</p>
		          	<p>商品品牌：<?php echo $this->_tpl_vars['data']['brand_name']; ?>
</p>
		          	<p>商品编号：<?php echo $this->_tpl_vars['data']['goods_sn']; ?>
</p>
		          	<p>商品类型：<?php echo $this->_tpl_vars['data']['cat_name']; ?>
</p>
		          	<p>商品国别：<?php echo $this->_tpl_vars['data']['region_name']; ?>
</p>
		          	<p>上架时间：<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')); ?>
</p>
		          	</div>
		          </div>
		         <?php echo $this->_tpl_vars['data']['description']; ?>

		        </div>
		        <div id="con_two_2" style="display:none">
		            <?php echo ((is_array($_tmp=$this->_tpl_vars['data']['introduction'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

		        </div>
		        <div id="con_two_3" style="display:none">
		        	<div class="con_txt">
		        		<strong>运费说明</strong>
		        		<p>国人海淘网的产品都是由国外直接采购后，通过顺丰快递直发上海，并由上海海关报关后发往国内。相比较国内的电商平台，国人海淘网的每件产品需要额外收取运费，以覆盖从海外发运的快递成本。另外，由于货品的体积比较大，因此购买多件产品时，也需要支付多份运费。<br><br></p>
		        		<p>在商品详情页上我们已经标注了每件商品的运费，您在将商品添加入购物车时，运费将自动进行计算。</p>
		        		<p><img src='/public/images/logic.png' /></p>
		        	</div>
		        	<div class="con_txt">
		        		<strong>配送时间</strong>
		        		<p>消费者支付成功后，商户及物流公司会向监管部门对订单进行申报，申报并缴纳税款成功后，物流公司将安排配送，具体配送流程及时间节点请咨询实际配送的物流公司。</p>
		        		<p>目前国人海淘网的产品都是从国外直接采购，并通过快递发运到上海海关进行报关，报关完成后再通过国内快递发送到您的手中。</p>
		        		<p>举例从日本到上海的快递需要2-3天的时间，海关报关需要1-2天时间，国内的快递时间取决于您的具体地址，需要1-3天的时间。因此整个购物配送时间大致需要4-8天的时间。</p>
		        	</div>
		        	<div class="con_txt">
		        		<strong>配送范围</strong>
		        		<p>我们支持全国各地共22个省份、4个直辖市和5个自治区的配送。</p>
		        		<p>如因您所在地区相对偏远，不在全国各类快递公司到达的范围请拨打<b>400-603-3883</b>与我们客服中心联系。</p>
		        	</div>
		        	<div class="con_txt">
		        		<strong>商品验货与签收</strong>
		        		<p>商品送达后，请当场确认商品与您订购的是否一致。您在验收商品时如发现商品短缺、配送错误、包装破损、商品存在质量问题等，请您向配送人员指出，并当场拒收全部商品，并在送货单上注明原因。相应的赠品和优惠商品应同时拒收，请与《国人海淘网》客服中心联系，客服电话：<b>400-603-3883</b>（免长途费）。若有不符或短少，请您务必于24小时内联系客服中心（超过24小时后，《国人海淘网》将不再为以上问题负责）。有质量问题除外。<br><br></p>
		        		<p>如您验收商品后确认商品的名称、数量、价格等信息无误，商品的包装和商品没有缺损等表面质量问题，请在发货单正面客户签收处签字，您或您委托收货人的签字表示您已确认上述内容无误，本网站有权不接受您的退换货行为。</p>
		        	</div>
		        	<div class="con_txt con_txt_last">
		        		<strong>配送特殊说明</strong>
		        		<p>由于国人海淘网属于国外代购，不同商品会从不同国家发货，同时购买多个商品可能会拆分为多个包裹进行配送。</p>
		        	</div>
		        </div>
		        <div id="con_two_4" style="display:none">
		        	<div class="con_txt">
		        		<span><i>★</i><strong>以下几种情况不给于退换货：</strong></span>
		        		<p>	A、商品非正常使用出现质量问题。<br>
						　　B、将商品储存、暴露在超出商品适宜的环境中，导致商品损坏。<br>
						　　C、不可抗力导致的损坏。<br>
						　　D、退回商品的附属物不完整或者有损毁的，或者发货单丢失、涂改且不能提供商品出处的。<br>
						　　E、超出退换货期限（自客户签收之日起七天之内，逾期不退换）。<br>
						 以上所有条款“国人海淘网”拥有最终解释权！</p>
		        	</div>
		        	<div class="con_txt con_txt_last">
		        		<span><i>★</i><strong>退换货流程</strong></span>
		        		<p>由于“国人海淘网”的产品属于渠道正规的在售产品，属于海外代购性质，如有任何退换货问题请与客服联系(客服热线：<b>400-603-3883</b>)。希望能够得到您的理解与支持！</p>
		        	</div>
		        </div>
		    </div>
		</div>
		<div class="productM-right">
		    <p class="title-brand border2">相关品牌</p>
		    <div class="hot-pro rel-brand border1">
		     <?php $_from = $this->_tpl_vars['brand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		     	<p><a href="/region-search-<?php echo $this->_tpl_vars['v']['brand_id']; ?>
-0-0-1.html"><?php echo $this->_tpl_vars['v']['brand_name']; ?>
</a></p>
		     <?php endforeach; endif; unset($_from); ?>
		     
		    </div>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_library/renqi.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			
			
		</div>
	</div>
</div><!--productDetail-->


<script>

	//jQuery.noConflict();
	//放大镜
	$('.jqzoom').jqzoom({ zoomType: 'standard',lens:true,zoomWidth:400,zoomHeight:340,position:"right", preloadImages:false,alwaysOn:false});//放大镜       
	
	
	//商品小图片切换
	function setBtnEnable(jqObj, enable, classname) {
	    jqObj.removeClass(classname);
	    if (!enable) jqObj.addClass(classname);
	}
	var nowIdx = 0;
	var moving = false;
	var pic_num  =$("#thumblist li").length
	var avgWidth = 70;
	$("#thumblist").width(avgWidth * pic_num);
	function moveFn(direction) {
	    if (moving) return;
	    moving = true;
	    if (direction > 0) {
	        if (nowIdx >= pic_num - 5) return;
	        nowIdx++;
	    } else {
	        if (nowIdx <= 0) return;
	        nowIdx--;
	    }
	    $('#thumblist li').eq(nowIdx).mouseenter();
	    setBtnEnable($('#pic_small_wrapper .btn_right'), nowIdx < pic_num - 5, 'btn_right_disable');
	    setBtnEnable($('#pic_small_wrapper .btn_left'), nowIdx > 0, 'btn_left_disable');
	    $('#list_smallpic').animate({
	        scrollLeft: nowIdx * avgWidth
	    }, 100, '', function() {
	        moving = false;
	    });
	}
	setBtnEnable($('#pic_small_wrapper .btn_right'), 0 < pic_num - 5, 'btn_right_disable');
	setBtnEnable($('#pic_small_wrapper .btn_left'), false, 'btn_left_disable');
	$('#pic_small_wrapper .btn_left').click(function() {
	    if ($(this).hasClass('btn_left_disable')) {return;}; moveFn(-1);
	});
	$('#pic_small_wrapper .btn_right').click(function() {
	    if ($(this).hasClass('btn_right_disable')){return;}; moveFn(1);
	}); 
	//商品小图片切换 end
	
	
	$(function(){
		//选择规格
		$(".guige").children("a:first").addClass("p_current");
		$(".product_type").click(function(){
			selProduct($(this).attr("val"));
			$(".guige").children("a").removeClass("p_current");
			$(this).parent("a").addClass("p_current");
		});
		
		//设置数量
		var input;
		$("#add").click(function(){
			if(input == null)
				input = $(this).siblings("input[type='text']");
			var val= input.val();
			if(parseInt(val)){
				input.val(++val);
			}else{
				return false;
			}
		});
		$("#reduce").click(function(){
			if(input == null)
				input = $(this).siblings("input[type='text']");
			var val= input.val();
			if(parseInt(val)>1){
				input.val(--val);
			}else{
				return false;
			}
		});
	})
	function selProduct(product_id){
		document.getElementById("product").value=product_id;
	}
	
	function isInt(obj){
		var val = $(obj).val();
		if(parseInt(val)>1){
			return true;
		}else{
			$(obj).val(1);
		}
	}
	
	function goods_add(type){
		var goods_sn = $('#product').val();
		var num = $('#buynum').val();
		var imgval = $('a[class*="p_current"]').children('img').attr('val');
		if(goods_sn == imgval){
			addCart(goods_sn,type,num);
		}else{
			alert("系统繁忙~请刷新后再试！")
			return false;
		}
	}
	function check_notice()
	{
	  var mobile = $.trim($("#mobile").val());
	  var email = $.trim($("#email").val());
	  if(mobile == '' && email == ''){
		 alert("请输入邮箱或手机号码！");
		 $("#mobile").focus();
		 return false;
	  }
	  
	  if(mobile !='' && !Check.isMobile(mobile))
	  {
		 alert("手机格式错误！");
		 $("#mobile").focus();
		 return false;  
	  }
	  
	   if(email!='' && !Check.isEmail(email) ){
		    alert("邮箱格式错误！");
			 $("#email").focus();
			 return false;  
	   }
		  
		$.ajax({
			url:'/goods/send-notice',
			data:$('#frm_notice').serialize(),
			type:'post',
			dataType:'json',
			success:function(data){
				document.getElementById('notice').style.display='none';document.getElementById('fade').style.display='none';
			   alert(data.msg);
			},
			async:false
		});
		
		 return false;
	  
	}
	
</script>