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
    $("header_loading").style.display = "block";
}

/**
 * Loaded placeholder.
 *
 * @param    void
 * @return   string
 */
function loadSucess()
{
    $('header_loading').style.display = "none";
}

/**
 * clearTmp.
 *
 * @return   void
 */
function clearTmp()
{
    var elements = $(document).getElements('link[id^=tmp_],script[id^=tmp_],div[id^=tmp_]');
	for(var i=0; i<elements.length; i++)
	{
		elements[i].destroy();
	}
	
	elements = $(document).getElements('iframe');
	for(var i=0; i<elements.length; i++)
	{
		if(elements[i].id != 'ifrmSubmit' && !/admin\/ipcc/.test(elements[i].src)) {
		    elements[i].destroy();
		}
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
    if (!div) {
        clearTmp();
        div = 'main_iframe';
    }
    new Request({
        url: url,
        method: 'post',
        data: 'do=splitPage',
        evalScripts: true,
        onRequest: loading,
        onSuccess: function(data)
        {
            $(div).innerHTML = data;
            loaded(url, true);
            mainAddEvent();
        },
        onFailure: function()
        {
            alert('error');
        }
    }).send();
}

/**
 * Load url function.
 *
 * @param    string    url
 * @return   void
 */
function G(url, logurl, div)
{
    clearTmp();
    new Request({
        url: url,
        method: 'get',
        evalScripts: true,
        async: true,
        onRequest: loading,
        onSuccess: function(data)
        {
            Gdata(data, div);
            logurl = !logurl ? true : false;
            loaded(url, logurl);
            mainAddEvent();
        },
        onFailure: function()
        {
            alert('error');
        }
    }).send();
}

/**
 * Build 'main_iframe' content with load data.
 *
 * @param    string    data
 * @return   void
 */
function Gdata(data, div)
{
	if (!div) {
	div = 'main_iframe';
	var ht = getPageHeight() - 105;
	$(div).setStyle('height', ht + 'px');
	}
	if ($(div)){
    $(div).innerHTML = data;
    }
}

/**
 * Load url use 'backward', 'forward', 'refresh'.
 *
 * @param    string    key
 * @return   void
 */
var urls = new Array();
var gourl = 0;
var nowurl = 0;
function Gurl(key, div)
{
   var logurl = true; 
   if(!key) {key = 'backward'; logurl = false;}
   switch (key) {
	   case 'backward' :
	       if(gourl == 1) return false;
	       nowurl -= 1;
	       if(nowurl <=0) {nowurl = 0;$('backward-image').addClass('alpha');}
	       $('forward-image').removeClass('alpha');
	       break;
	   case 'forward' :
	       if(gourl == 1) return false;
	       if(nowurl < gourl - 1) {nowurl += 1;$('backward-image').removeClass('alpha');}
	       if(nowurl == gourl - 1)$('forward-image').addClass('alpha');
	       break;
   }
   var url = urls[nowurl];
   if (url.indexOf('do') > 0 && !div) div = 'ajax_search';
   if (url.indexOf('do') == -1) div = '';
   G(url, logurl, div);
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
    G('/admin/index/info/');
}

/**
 * Build menu list.
 *
 * @param    int    n
 * @return   void
 */
function goMenu(n)
{
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
    
    $('header_menu').getElements('li[id^=module-]').removeClass('head-nav-on');
    $("module-" + n).addClass('head-nav-on');
    $("Gfocus").focus();
}

/**
 * Loaded and record url.
 *
 * @param    string    url
 * @param    boolen    url
 * @return   void
 */
function loaded(url, logUrl)
{
    loadSucess();
    $("Gfocus").focus();
    
    if (logUrl == true) {
    	if(gourl == 4){
    	    for(i=0; i < gourl - 1; i++)
    		{
    			urls[i] = urls[i+1];
    		}
    		urls[gourl-1] = url;
    	}else if(urls[gourl - 1] != url){
		    urls[gourl] = url;
		    gourl += 1;
	    }
	    nowurl = gourl - 1;
	    if(gourl>1) $('backward-image').removeClass('alpha');
    }
    
    //绑定表单验证
    if ($('myForm')){
        new Validate('myForm',{
			submitType: 2
		});
    }
    if ($('pfocus')){
        $('pfocus').focus();
    }
    
    resetTimer();
}

var countdown=1440;//倒计时的时间（秒）
var myTimer=setInterval("ShowCountdown('countdown')",1000);
function FormatSec(oSum) 
{
	var str; 
	var min=Math.floor(oSum/60); 
	if(min<1) 
	{min==0} 
	var sec=oSum%60; 
	str="请在<span style=\"font-weight:bold;\">"+min+"分"+sec+"秒</span>内完成信息录入"; 
	return str;
} 
function ShowCountdown(objName)
{
	alert('b');
	return;
	countdown=countdown-1;
	document.getElementById(objName).innerHTML = FormatSec(countdown);
	if(countdown==0) 
	{ 
		clearInterval(myTimer);
		openDiv('/admin/auth/relogin','ajax','重新登录',300,146,true,'relogin')
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
 * Menu list explode and collapse.
 *
 * @param    object    obj
 * @param    string    status
 * @return   void
 */
function toggleCollapseExpand(obj, status)
{
    if (obj.tagName.toLowerCase() == 'li' && obj.className != 'menu-item')
    {
        for (i = 0; i < obj.childNodes.length; i++)
        {
            if (obj.childNodes[i].tagName == 'UL')
            {
                if (status == null)
                {
                    if (obj.childNodes[1].style.display != 'none') {
                        obj.childNodes[1].style.display = 'none';
                        obj.className = 'collapse';
                    } else {
                        obj.childNodes[1].style.display = 'block';
                        obj.className = 'explode';
                    }
                    break;
                } else {
                    if ( status == 'collapse') {
                        obj.className = 'collapse';
                    } else {
                        obj.className = 'explode';
                    }
                    obj.childNodes[1].style.display = (status == 'explode') ? 'block' : 'none';
                }
            }
        }
    }
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
    
    $('main_iframe').getElements('input').addEvents({
        'focus': function(e) {if (e.target.type == 'text' || e.target.type == 'password') {focusInput(e)}},
        'blur': function(e) {if (e.target.type == 'text' || e.target.type == 'password') {blurInput(e)}}
    });
    
    $('main_iframe').getElements('textarea').addEvents({
        'focus': focusInput,
        'blur': blurInput
    });
}

/**
 * Cache discount.
 *
 * @param    void
 * @return   void
 */
function cacheOffersFile()
{
    new Request({
        url: '/admin/offers/cache',
        method: 'get'
    }).send();
}

/**
 * Cache privilege.
 *
 * @param    void
 * @return   void
 */
function cachePrivilegeFile()
{
    new Request({
        url: '/admin/privilege/cache',
        method: 'get'
    }).send();
}

function openIpcc()
{
    var win;
    win = new dhtmlXWindows();
    win.setImagePath("/scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
    var ipccWin = win.createWindow("ipccWin", window.screen.width-224, 70, 224, 650);
    ipccWin.setText("正在加载，请稍候……");
    ipccWin.button("minmax1").hide();
    ipccWin.button("close").hide();
    ipccWin.denyResize();
    ipccWin.denyMove();
    win.attachEvent("onContentLoaded", function()
	{
		ipccWin.setText("IPCC 呼叫中心");
	});
    ipccWin.attachURL("/admin/ipcc/index/");
}

//window.onerror = killErr;