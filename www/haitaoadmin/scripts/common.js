/**
 * Neglected window error.
 *
 * @param    void
 * @return   boolen
 */
function killErr()
{
    return true;
}
/**
 * Definition href null function.
 *
 * @param    void
 * @return   void
 */
function fGo(){};
/**
 * loading placeholder.
 *
 * @param    void
 * @return   string
 */
function loading()
{
    window.top.$("index_header_loading").style.display = "block";
    window.top.$("index_Gfocus").focus();
}
/**
 * Loaded placeholder.
 *
 * @param    void
 * @return   string
 */
function loadSucess()
{
    window.top.$('index_header_loading').style.display = "none";
}

/**
 * Loaded and record url.
 *
 * @param    string    url
 * @return   void
 */
function loaded()
{
    if(top.window != window){
    loadSucess();
    //绑定表单验证
    if ($('myForm')){
        new Validate('myForm',{
			submitType: 2
		});
    }
    mainAddEvent();
	window.top.resetTimer();
	}
	if ($('pfocus')){
	    $('pfocus').focus();
	}
}

/**
 * Load url function.
 *
 * @param    string    url
 * @return   void
 */
function G(url)
{
    loading();
    var obj = window.top.frames.main_iframe;
    if (window.parent != window) {
	    window.parent.main_iframe.location.href = url;
	}else{
        obj.location.href=url;
	}

	//runSchedule();
}

/**
 * Load url use 'backward', 'forward', 'refresh'.
 *
 * @param    string    key
 * @return   void
 */
function Gurl(key)
{
   if(!key) {
	   key = 'goback';
	   if(Browser.Engine.gecko){
	   	   var step = -2;
	   }else{
	   	   var step = -1;
	   }
   }
   var obj = window.top.frames.main_iframe;
   switch (key) {
	   case 'goback' :
	   	   obj.history.go(step);
	       break;
	   case 'backward' :
	   	   obj.history.back();
	       break;
	   case 'forward' :
	   	   obj.history.forward();
	       break;
	   case 'refresh' :
	   	   obj.location.href=obj.location.href;
	       break;
   }
}

/**
 * Split page
 *
 * @param    string    url
 * @param    string    div
 * @return   void
 */
function splitPage(url, div)
{
    if (url.indexOf('job') > 0){
    new Request({
        url: url,
        method: 'post',
        data: 'do=splitPage',
        evalScripts: true,
        onRequest: loading,
        onSuccess: function(data)
        {
            $(div).innerHTML = data;
            loaded();
        },
        onFailure: function()
        {
            alert('error');
        }
    }).send();
    }else{
    	url = filterUrl(url, 'do');
	    G(url);
    }
}
/**
 * Build index content.
 *
 * @param    void
 * @return   void
 */
function buildMenu()
{
    goMenu(1);
}
/**
 * Build menu list.
 *
 * @param    int    n
 * @return   void
 */
function goMenu(n)
{
    var menus = $('index_header_menu').getElements('a[id^=index_menu-]');
    $("menu_iframe").setStyle('margin-top',0);
    url = '/admin/index/menu/pid/' + n;
    new Request({
        url: url,
        evalScripts: true,
        onRequest: loading,
        method: 'get',
        onSuccess: function(data)
        {
            loadSucess();
        }
    }).send();
    menus.removeClass('index_head-nav-on');
    $("index_menu-" + n).addClass('index_head-nav-on');
}

function FormatSec(oSum)
{
	var str;
	var min=Math.floor(oSum/60);
	if(min<1)
	{min==0}
	var sec=oSum%60;
	str="请在<strong style=\"color:#004ea2\">"+min+"分"+sec+"秒</strong>内完成信息录入";
	return str;
}
function ShowCountdown(objName)
{
	countdown=countdown-1;
	obj = $(objName);
	if(obj){
	obj.innerHTML = FormatSec(countdown);
	}
	if(countdown==0)
	{
		clearInterval(myTimer);
		window.top.openDiv('/admin/auth/relogin','ajax','重新登录',300,146,true,'relogin')
		//alert("登录超时,请您复制内容以防止内容丢失！");
	}
}
function resetTimer(){
	//倒计时
	countdown=1440;
    clearInterval(myTimer);
    myTimer=setInterval("ShowCountdown('countdown')",1000);
}

/**
 * Change input tag visual effects.
 *
 * @param    void
 * @return   void
 */
function mainAddEvent(){
    function focusInput(e){
        e.target.removeClass('input-text');
        e.target.addClass('input-text-focus');
    }

    function blurInput(e){
        e.target.removeClass('input-text-focus');
        e.target.addClass('input-text');
    }

    $(document).getElements('input').addEvents({
        'focus': function(e) {if (e.target.type == 'text' || e.target.type == 'password') {focusInput(e)}},
        'blur': function(e) {if (e.target.type == 'text' || e.target.type == 'password') {blurInput(e)}}
    });

    $(document).getElements('textarea').addEvents({
        'focus': focusInput,
        'blur': blurInput
    });
}

/**
 * 获取页面高度
 * @return   float
 */
function getPageHeight()
{
    var windowHeight
    if (self.innerHeight) {
        // all except Explorer
        windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) {
        // Explorer 6 Strict Mode
        windowHeight = document.documentElement.clientHeight;
    } else if (document.body) {
        // other Explorers
        windowHeight = document.body.clientHeight;
    }
    return windowHeight;
}

/**
 * 滑动门
 *
 * @param    int       id    选择ID
 * @return   void
 */
function show_tab(id)
{
	var tabPage = $(document).getElements('div[id^=show_tab_page_]');
	if (tabPage.length > 0){
		for (var i = 0; i < tabPage.length; i++)
		{
			tag = tabPage[i].id.replace(/show_tab_page_/, '');
			var nav = $("show_tab_nav_" + tag);
			var tid = $("show_tab_page_" + tag);
			if (tag == id)
			{
			nav.className = 'bg_nav_current';
			tid.style.display = "";
			}else{
			nav.className = 'bg_nav';
			tid.style.display = "none";
			}
		}
	}
}

/**
 * 全选
 *
 * @param    obj          tag
 * @param    string       prefix
 * @param    obj          obj
 * @return   void
 */
function checkall(tag, prefix, obj) {
	var checkbox = tag.getElements('input[type=checkbox]');
	for(var i = 0; i < checkbox.length; i++) {
		var e = checkbox[i];
		if(e.name != obj.name && (!prefix || (prefix && e.name.match(prefix)))) {
			e.checked = obj.checked;
		}
	}
}

/**
 * 获取多选
 *
 * @param    obj          tag
 * @param    string       prefix
 * @param    obj          obj
 * @return   void
 */
function multiCheck(tag, prefix, obj)
{
    var checkbox = tag.getElements('input[type=checkbox]');
    var checkedId = '';
    for (var i = 0; i < checkbox.length; i++)
    {
    	var e = checkbox[i];
		if(e.name != obj.name && (!prefix || (prefix && e.name.match(prefix)))) {
			if (e.checked == true) {
                checkedId += e.value + ',';
            }
		}
    }
    return checkedId.substr(0, checkedId.length-1);
}

/**
 * filterUrl url过滤
 *
 * @param    string    url   请求地址
 * @param    string    key   过滤参数
 */
function filterUrl(url,key)
{
    var re = new RegExp("(.*)(\/"+key+"\/)([^\/]*)", "i");
    url = url.replace(re, "$1");
	return url;
}

/**
 * reallydelete 是否删除
 *
 * @param    string    url   请求地址
 * @param    int       id    唯一ID
 * @param    string    urlForward   跳转地址
 * @param    string    div          删除区域
 * @return   void
 */
function reallydelete(url,id,urlForward,div)
{
    alertBox.init("msg='确认要删除吗？', event='ajax_delete(\""+url+"\",\""+id+"\",\""+urlForward+"\",\""+div+"\")', cancel=true, enter=true, last=true");
}

/**
 * 执行删除
 *
 * @param    string    url          请求地址
 * @param    int       id           唯一ID
 * @param    string    urlForward   跳转地址
 * @param    string    div          删除区域
 * @return   void
 */
function ajax_delete(url,id,urlForward,div)
{
    urlForward = urlForward.trim();
    div = (div != 'undefined' && div != '') ? div : 'ajax_list';
    url = filterUrl(url, 'id');
    new Request({
        url: url + '/id/' + id,
        onRequest:loading,
        onSuccess:function(data){
            if (data!='') {
                if (urlForward != 'undefined' && urlForward != ''){
                	alertBox.init("msg='"+data+"',url='"+urlForward+"',MS=1250");
                }else{
                    alertBox.init("msg='"+data+"',MS=1250");
                }
            } else {
                if (urlForward != 'undefined' && urlForward != ''){
                	G(urlForward);
                }else{
                	if (id.indexOf(',') > 0) {
                		id = id.split(',');
                		for (var i = 0; i < id.length; i++)
                		{
                			$(div + id[i]).destroy();
                		}
                	} else {
                		$(div + id).destroy();
                	}
                }
            }
            loadSucess();
        }
    }).send();
}

/**
 * ajax修改状态
 *
 * @param    string    url     请求地址
 * @param    int       id      唯一ID
 * @param    int       status  提交状态值
 * @return   void
 */
function ajax_status(url,id,status,div){
    url = filterUrl(url, 'id');
    if (!div) div = "ajax_status";
    new Request({
        url: url + '/id/' + id + '/status/' + status,
        onRequest: loading,
        onSuccess:function(data){
            if (data == 'forbidden') {
                alertBox.init("msg='禁止更改状态', MS=1250");
            } else if (data == 'failure') {
                alertBox.init("msg='更改状态失败', MS=1250");
            } else if (data == 'refresh') {
                Gurl('refresh');
            } else {
                $(div + id).innerHTML = data;
            }
            loadSucess();
        }
    }).send();
}

/**
 * ajax验证重复值
 *
 * @param    string    url      请求地址
 * @param    string    field    验证字段
 * @return   void
 */
function ajax_check(url,field){
    var button = $('dosubmit');
    new Request({
        url: url + '/field/' + field + '/val/' + encodeURIComponent($(field).value) ,
        onSuccess:function(data){
	        if (data !='') {
		        $('tip_' + field).innerHTML = data;
		        if(button) button.disabled = true;
	        }else{
		        if(button) button.disabled = false;
	        }
        }
    }).send();
}

/**
 * ajax更新字段值
 *
 * @param    string    url      请求地址
 * @param    int       id       唯一ID
 * @param    string    field    更新字段
 * @param    string    type     表单类型
 * @param    int       size     表单size
 * @return   void
 */
function ajax_update(url,id,field,val,type,size){
    if(!size) size=8;
    var e = $('ajax_'+ field + id);
    var r = '<span onclick="ajax_update(\''+url+'\','+id+',\''+field+'\',this.innerHTML,\'text\','+size+')" title="点击修改内容">'+val+'</span>';
    var c ='<input type="text" name="' + field + '" size="'+size+'" value="'+val+'" onchange="ajax_update(\''+url+'\','+id+',\''+field+'\',this.value,\'\','+size+')" onblur="ajax_update(\''+url+'\','+id+',\''+field+'\',this.value,\'callback\','+size+')">';
    if(type && type!='callback'){
    	e.innerHTML = c;
    	e.firstChild.focus();
    }else if(type=='callback'){
    	e.innerHTML = r;
    }else{
	    url = filterUrl(url, 'id');
	    new Request({
	        url: url + '/id/' + id + '/field/' + field + '/val/' + encodeURIComponent(val),
	        evalScripts: true,
	        onRequest: loading,
	        onSuccess:function(data){
	            if (data == 'failure') {
	                alert('更新失败!');
	            }
	            loadSucess();
	        }
	    }).send();
    }
}

/**
 * 通用弹窗
 *
 * @param    string    url      请求地址
 * @param    string    type     弹窗类型
 * @param    string    title    弹窗标题
 * @param    string    msgW     弹窗宽度
 * @param    string    msgH     弹窗高度
 * @param    string    id
 * @param    string    last
 * @return   void
 */
function openDiv(url,type,title,MsgW,MsgH,last,id){
	title = title ? title : '';
	last=last ? true : false;
	if (!id) id = Math.round(Math.random()*1000);
	if(!MsgW) MsgW = 700;
	if(!MsgH) MsgH = 400;
    if(type=='ajax'){
	    alertBox.init("title='"+title+"',msg='正在加载，请稍候……',msgW="+MsgW+",msgH="+MsgH+",id='"+id+"',last="+last+"");
	    new Request({
	        url: url,
	        method: 'get',
	        evalScripts: true,
	        onSuccess:function(data){
	        	var obj = $('msg'+id);
	        	if(obj){
		        	obj.innerHTML = data;
		            loadSucess();
		            if ($('sfocus')){
				        $('sfocus').focus();
				    }
			    }
	        }
	    }).send();
    }else if(type=='iframe'){
    	var title = '';
    	alertBox.init("title='"+title+"',url='"+url+"',bMsgT='iframe',msgW="+MsgW+",msgH="+MsgH+",id='"+id+"',last="+last+"");
    }else{
    	alertBox.init("title='"+title+"',msg='正在加载，请稍候……',msgW="+MsgW+"msgH="+MsgH+",id='"+id+"',last="+last+"");
    	$('msg'+id).innerHTML = $(url).innerHTML;
    }
}

/**
 * 通用Ajax返回数据
 *
 * @param    string    url      请求地址
 * @param    string    div      返回区域
 * @return   void
 */
function ajax_callback(url,div){
    new Request({
        url: url,
        onRequest: loading,
        onSuccess:function(data){
        $(div).innerHTML = data;
        loadSucess();
        }
    }).send();
}

/**
 * 通用Ajax表单查询数据
 *
 * @param    string    form     提交表单
 * @param    string    url      请求地址
 * @param    string    div      更新区域
 * @return   void
 */
function ajax_search(form,url,div){
    var checkboxstr = '';
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(e.type != 'button' && e.type != 'reset') {
		    if (e.type =='radio' && !e.checked) {
		        continue;
		    }
		    if (e.type =='checkbox') {
		        url = filterUrl(url, e.name);
		        if (e.checked) {
		            e.name = encodeURIComponent(e.name);
		            checkboxstr = checkboxstr + '/' + e.name + '/' + encodeURIComponent(e.value);
		        }
		        else    continue;
		    }
		    else {
		        url = filterUrl(url, e.name);
		        url = url + '/' + e.name + '/' + encodeURIComponent(e.value);
		    }
		}
	}
	if (checkboxstr != '') {
	    url = url + checkboxstr;
	}
	if (url.indexOf('job') > 0){
    new Request({
        url: url,
        onRequest: loading,
        onSuccess:function(data){
        $(div).innerHTML = data;
        loaded(url, true);
        loadSucess();
        }
    }).send();
	}else{
	url = filterUrl(url, 'do');
	G(url);
    }
}

/**
 * 通用Ajax提交表单
 *
 * @param    string    form     提交表单
 * @param    string    url      请求地址
 * @param    string    callback
 * @return   void
 */
function ajax_submit(form,url,callback){
	form.set('send', {
	    url: url,
	    method: 'post',
	    evalScripts: true,
	    onRequest: function()
	    {
	        loading();
	    },
	    onSuccess: function(data)
	    {
	        if (callback) {
        	    eval(callback);
        	}
	        loadSucess();
	    },
	    onFailure: function()
	    {
	        alert('error');
	    }
	}).send();
}

/**
 * ajax获取子信息
 *
 * @param    string    url      请求地址
 * @param    int       id       父ID
 * @return   void
 */
var last_sub_id=0;
function ajax_sub(url,id){
	var info=$('ajax_list'+id);
	var body=$('ajax_sub'+id);
	var last=$('ajax_sub'+last_sub_id);
	var lastbody=$('ajax_sub'+last_sub_id);
	url = filterUrl(url, 'pid');
    new Request({
        url: url + '/pid/' + id,
        onRequest: loading,
        onSuccess:function(data){
			if (body){
				info.removeChild(body);
				info.removeClass('list_selected');
			    info.addClass('list');
		    }else{
				if (last_sub_id>0 && lastbody){
					last.removeChild(lastbody);
					last.removeClass('list_selected');
				    last.addClass('list');
			    }
			info.removeClass('list');
			info.addClass('list_selected');
			var div = document.createElement("div");
			div.setAttribute("id",'ajax_sub'+id);
            div.innerHTML=data;
            info.appendChild(div);
            last_sub_id=id;
	        }
            loadSucess();
        }
    }).send();
}

/**
 * 加载CSS文件
 *
 * @param    string    urls     支多个CSS文件，以英文逗号隔开
 * @return   void
 */
function loadCss(urls, id)
{
    var url = urls.split(",");
    id = (id) ? id : 'tmp_css';
    for (var i=0; i<url.length; i++)
    {
        new Asset.css(url[i].trim(),{id: id + i});
	}
}

/**
 * 加载JS文件
 *
 * @param    string    file       JS文件,多个JS文件以','号隔开
 * @param    string    callback   回调方法
 * @param    string    param      回调方法参数
 * @return   void
 */
function loadJs(file, callback, param)
{
    new Request({
        method: 'post',
        url: '/admin/loadscript/index/file/',
        data: 'file=' + file,
        evalScripts: true,
        onSuccess: function(){
        	if (callback) {
        	    callback.run(param);
        	}
        },
        onFailure: function(){
        	alert('error');
        }
    }).send();
}

/**
 * 信息的显隐
 * @param    string    objId      容器ID
 * @return   void
 */
function showNotice(id)
{
    var obj = $(id);

    if (obj) {
        if (obj.style.display != "block") {
            obj.style.display = "block";
        } else {
            obj.style.display = "none";
        }
    }
}

/**
 * 树形信息的显隐
 * @param    string    subTree  容器
 * @param    string    img      图片
 * @return   void
 */
function display(subTree,img)
{
	if(subTree.style.display=="none"){
		subTree.style.display="";
		img.src="images/tree_collapse.gif";
	} else {
	    subTree.style.display="none";
	    img.src="images/tree_expand.gif";
	}
}

/**
 * ajax处理
 *
 * @param    string    url
 * @return   void
 */
function ajaxLoad(url)
{
	new Request({
	    url: url,
	    method: 'get',
	    evalScripts: true,
	    onSuccess: function(data)
	    {
	    	alert(data);
	    },
	    onFailure: function()
	    {
	        alert('error');
	    }
	}).send();
}

/**
 * 运行计划任务
 *
 * @return   void
 */
function runSchedule()
{
    new Request({
        url: '/admin/schedule/run-auto',
        onRequest: loading,
        onSuccess:function(data){
            if ( data ) {
                var idArray = data.split(",");
                for ( var i = 0; i < idArray.length; i++ ) {
                    new Request({
                        url: '/admin/schedule/run/do/1/id/' + idArray[i],
                        onRequest: loading,
                        onSuccess:function(data){

                        }
                    }).send();
                }
            }
        }
    }).send();
}