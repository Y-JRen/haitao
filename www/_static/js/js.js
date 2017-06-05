/****************ajax form ********************/

function ajaxinit(){

	$(".btn-ajax-submit").unbind("click");
	$('.btn-ajax-submit').click(btn_ajax_submit = function(){

		var $btn = $(this);
		var $frm = $('#'+$(this).attr('frmid'));

		var method = $frm.attr('method');
		if((typeof method) == "undefined"){
			method = 'POST';
		}
		var datatype = $frm.attr('datatype');
		if((typeof datatype) == "undefined"){
			datatype = 'json';
		}

		var action = $frm.attr('action');
		if(typeof action == "undefined"){
			alert('action is undefine!');
    		$btn.bind('click',btn_ajax_submit);
			return;
		}

		var vdt = $frm.attr('vdt');
		if(typeof vdt != "undefined" && vdt == 'true'){
			if(!$frm.validationEngine('validate')){
				return;
			}
		}

		var submitbefor = $frm.attr('submitbefor');
		if((typeof submitbefor) != "undefined"){
			var flag_befor = window[submitbefor]($frm,$btn);
			if(!flag_befor){
				return false;
			}
		}

		var isajax = $frm.attr('isajax');
		if(isajax && isajax == 'false'){
			$frm.submit();
			return;
		}

		action = action;

		$frm.mask('数据处理中,请稍后...');
		
		$.ajax({
			type: method,
			url: action,
			data:$frm.serialize(),
			async: true,
			cache:false,
			dataType:datatype,
			success: function(msg){
				
	    		var submitafter = $frm.attr('submitafter');
	    		if((typeof submitafter) != "undefined"){
	    			var flag = window[submitafter](msg,$frm,$btn);
	    			if(flag){
	    				ajax_back(msg);
	    			}
	    		}else{
	    			ajax_back(msg);
	    		}

			},
			complete:function(jqXHR, textStatus){
				$frm.unmask();
			}
		});
	});

	$(".btn-ajax").unbind("click");
	$('.btn-ajax').click(btn_ajax = function(){
		$btn = $(this);

		var data = '';
		var url = $(this).attr('href');
		var data_type = 'json';
		var $elt = $(this);
		
		if($(this).attr('data-type')) data_type = $(this).attr('data-type');
		if($(this).attr('data')) data = $(this).attr('data');

		var befordo = $elt.attr('befordo');
		
		if((typeof befordo) != "undefined"){
			if(!window[befordo]($elt)){
				return false;
			}
		}
		
		$.ajax({
			type: 'get',
			url: url,
			async: false,
			dataType:data_type,
			data:data,
			success:function(msg){
	    		var afterdo = $elt.attr('afterdo');
	    		if((typeof afterdo) != "undefined"){
	    			if(window[afterdo](msg,$elt)){
	    				ajax_back(msg);
	    			}
	    			return;
	    		}
	    		ajax_back(msg);
			}
		});
		return false;

	});

	$(".btn-ajax-confirm").unbind("click");
	$('.btn-ajax-confirm').click(btn_ajax_confirm = function(){

		$btn = $(this);
		$btn.unbind('click');

		var url = $(this).attr('href');
		var $elt = $(this);
		var msg = $(this).attr('msg');
		
		var befordo = $elt.attr('befordo');
		if((typeof befordo) != "undefined"){
			if(!window[befordo]($elt)){
				$btn.bind('click',btn_ajax_confirm);
				return false;
			}
		}

		asyncbox.confirm(msg,'警 告',cf = function(action){
　　　      		if(action == 'ok'){
	　　　      			var data = $elt.attr('data') ? $elt.attr('data') : '';
　　　      			$.ajax({
　　　      				type: 'get',
　　　      				url: url,
　　　      				async: false,
         				data:data,
　　　      				dataType:'json',
　　　      				success:function(msg){
　　　      		    		var afterdo = $elt.attr('afterdo');
　　　      		    		if((typeof afterdo) != "undefined"){
　　　      		    			if(window[afterdo](msg,$elt)){
　　　      		    				ajax_back(msg);
　　　      		    			}
  				    				$btn.bind('click',btn_ajax_confirm);
　　　      		    			return;
　　　      		    		}
　　　      		    		ajax_back(msg);
　　　      				}
　　　      			});
　　　      		}
		});
		$btn.bind('click',btn_ajax_confirm);
		return false;
	});

	$('.wm-ajax').unbind('click');
	$('.wm-ajax').click(function(){
		
		$elt = $(this);
		var title = $elt.attr('wmtitle');
		var w = $elt.attr('wmw');
		var h = $elt.attr('wmh');
		var url = $elt.attr('href');

		var opt = {};
		opt.modal = true;
		opt.title = title;
		opt.width = w;
		opt.height = h;

		if($elt.attr('wmid')){
			opt.id = $elt.attr('wmid')
		}

		$.ajax({
		   type: "GET",
		   url: url,
		   async: false,
		   dataType:'html',
		   success: function(msg){
		   	   var wm_url = '<input id="'+opt.id+'-hidden" type="hidden" value="'+url+'" class="wm-url"/>';
		   	   msg = msg+wm_url;
			   opt.html = msg;
			   asyncbox.open(opt);
		   }
		});
		return false;
	});

	$('.btn-ajax-load').unbind('click');
	$('.btn-ajax-load').click(function(){

		$elt = $(this);
		var url = $elt.attr('href');
		var container = $elt.attr('alc');

		var befordo = $elt.attr('befordo');
		var afterdo = $elt.attr('afterdo');
		
		var append = ($elt.attr('append') && $elt.attr('append') == 'true') ? true : false;

		if((typeof befordo) != "undefined"){
			if(!window[befordo]($elt)){
				return false;
			}
		}
		
		var data = $(this).attr('data') ? $(this).attr('data') : '';
		
		$(container).mask('loading...');
		
		$.ajax({
		   type: "GET",
		   url: url,
		   async: false,
		   dataType:'html',
		   data:data,
		   success: function(msg){
			   	
	    		if((typeof afterdo) != "undefined"){
	    			if(window[afterdo](msg,$elt)){
	    				ajax_back(msg);
	    			}
	    			return false;
	    		}
	    		
	    		if(append){
	    			$(container).append(msg);
	    		}else{
	    			$(container).empty().html(msg);
	    		}
	    		
	    		$(container).unmask();
	    		
			   return false;
		   }
		});

		return false;
	});

}


function ajax_back(msg){

	if(msg.status == 'fail-unlogin'){
		$('#btn-login').click();
	}else if(msg.status == 'succ'){
		if(msg.msg){
			asyncbox.tips(msg.msg,'success');
		}
	}else if(msg.status == 'succ-href'){
		asyncbox.tips(msg.msg,'success');
		setInterval(function(){
			window.location.href = msg.href;
		}, "2000");
	}else if(msg.status == 'succ-reload'){
		asyncbox.tips(msg.msg,'success');
		setInterval(function(){
			window.location.reload();
		}, "2000");
	}else if(msg.status == 'debug'){
		asyncbox.open({
			modal:true,
			width:500,
			height:300,
			title:'debug',
			id:'wm-debug',
			html:msg.msg
		});
	}else if(msg.status == 'fail'){
		asyncbox.tips(msg.msg,'error');
	}

}

function wm_reload(wmid,data){
	var url = $('#'+wmid+'-hidden').val();
	if((typeof data) != 'undefined') url = url + '&' + data;
	$.ajax({
	   type: "GET",
	   url: url,
	   async: false,
	   dataType:'html',
	   success: function(msg){
	   		$('#'+wmid+'_content').empty();
	   		msg = msg + '<input type="hidden" class="wm-url" value="'+url+'" id="'+wmid+'-hidden'+'"/>';
	   		$('#'+wmid+'_content').html(msg);
	   }
	});
}

function ajax_confirm(elt,url,msg,data,befordo,afterdo){
	if(!confirm(msg)) return;

	if(befordo){
		if(!window[befordo]($(elt))){
			return false;
		}
	}
	var data = data;
	$.ajax({
		type: 'get',
		url: url,
		async: false,
		data:data,
		dataType:'json',
		success:function(msg){
    		if(afterdo){
    			if(window[afterdo](msg,$(elt))){
    				ajax_back(msg);
    			}
    			return;
    		}
    		ajax_back(msg);
		}
	});
}

function dlg_open(url,id,title,w,h,wmmaxiable){

	var opt = {};
	opt.modal = true;
	opt.title = title;
	opt.width = w;
	opt.height = h;
	opt.wmmaxiable = true;

	if(!wmmaxiable){
		opt.wmmaxiable = false;
	}
	
	if(id){
		opt.id = id;
	}

	$.ajax({
	   type: "GET",
	   url: url,
	   data:{pop_id:id},
	   async: false,
	   dataType:'html',
	   success: function(msg){
		   
		   var tools_refresh = ' <a href="javascript:void(0)" class="icon-reload" onclick="$(\'#'+opt.id+'\').window(\'refresh\',\''+url+'\');"></a>';
		   var wm_tools_div = '<div id="wm-tools-'+opt.id+'">'+tools_refresh+'</div>';
		   var wm_div = '<div id="'+opt.id+'">'+wm_tools_div+'</div>';
		   $('body').append(wm_div);
		   
		   $('#'+opt.id).window({  
				title:opt.title,
			    width:opt.width,  
			    height:opt.height,  
			    minimizable:false,
			    maximizable:opt.wmmaxiable,
			    collapsible:false,
			    content:msg,
			    cache:false,
			    shadow:true,
			    tools:'#wm-tools-'+opt.id,
			    onClose:function(){
			    	$('#'+opt.id).window('destroy');
			    },
			    modal:true
			});  	
			
	   }
	});
}


/***数据处理验证***/
String.prototype.trim= function(){
    return this.replace(/(^\s*)|(\s*$)/g, "");
};

function vUrl(p){
	var checkfiles=new RegExp("((^http)|(^https)|(^ftp)):\/\/(\\w)+\.(\\w)+");
	return checkfiles.test(p);
}

function isEmail(strEmail){
    if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
        return true;
    else
        return false;
}

function isUpass(upass){

	if (upass.search(/^[a-z0-9_-]{6,18}$/) != -1)
		return true;
	else
		return false;
}

function ajustH(iframe){
	var bh = $(iframe).contents().find('body').height();
	$(iframe).css('height',bh);
}

function win_open(url,win_id,title,w,h){
	//var win = window.showModalDialog(url,window,'dialogWidth:'+w+'px;dialogHeight:'+h+'px;location=no;toolbar=no;status:no;help:no;scroll:no;');
	window.open (url,'newwindow','height='+h+',width='+w+',top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no') 
	//win.open();
}


function winopen(url){
	var win = window.showModalDialog(url,window,'dialogWidth:760px;dialogHeight:500px;status:no;help:no;scroll:no;');
	win.open();
}

function openWin(htmUrl,w,h) {

	var url=htmUrl; //要打开的窗口
	
	var winName="newWin"; //给打开的窗口命名
	
	// screen.availWidth 获得屏幕宽度
	
	// screen.availHeight 获得屏幕高度
	
	// 居中的算法是：
	
	// 左右居中： (屏幕宽度-窗口宽度)/2
	
	// 上下居中： (屏幕高度-窗口高度)/2
	
	var awidth = w; //窗口宽度,需要设置
	
	var aheight = h; //窗口高度,需要设置
	
	var atop=(screen.availHeight - aheight)/2; //窗口顶部位置,一般不需要改
	
	var aleft=(screen.availWidth - awidth)/2; //窗口放中央,一般不需要改
	
	var param0="scrollbars=0,status=0,menubar=0,resizable=2,location=0"; //新窗口的参数
	
	//新窗口的左部位置，顶部位置，宽度，高度
	
	var params="top=" + atop + ",left=" + aleft + ",width=" + awidth + ",height=" + aheight + "," + param0 ;
	
	win=window.open(url,winName,params); //打开新窗口
	
	win.focus(); //新窗口获得焦点

}

