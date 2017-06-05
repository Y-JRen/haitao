/**jquery操作cookie插件*/
jQuery.cookie=function(e,t,n){if(typeof t=="undefined"){var a=null;if(document.cookie&&document.cookie!=""){var f=document.cookie.split(";");for(var l=0;l<f.length;l++){var c=jQuery.trim(f[l]);if(c.substring(0,e.length+1)==e+"="){a=decodeURIComponent(c.substring(e.length+1));break}}}return a}n=n||{},t===null&&(t="",n.expires=-1);var r="";if(n.expires&&(typeof n.expires=="number"||n.expires.toUTCString)){var i;typeof n.expires=="number"?(i=new Date,i.setTime(i.getTime()+n.expires*24*60*60*1e3)):i=n.expires,r="; expires="+i.toUTCString()}var s=n.path?"; path="+n.path:"",o=n.domain?"; domain="+n.domain:"",u=n.secure?"; secure":"";document.cookie=[e,"=",encodeURIComponent(t),r,s,o,u].join("")};


/**点击文本域清除默认值*/
(function(e){e.fn.iClear=function(t){var n=this;t=e.extend({Curval:null,color:"#000",CurColor:"#666666",Enter:null},t||{}),e(n).each(function(){t.Curval!=null&&e(n).val(t.Curval),e(this).css("color",t.CurColor).focus(function(){e(this).css("color",t.color),e(this).val()==(t.Curval?t.Curval:this.defaultValue)&&e(this).val("")}).blur(function(){e(this).val()==""&&e(this).val(t.Curval?t.Curval:this.defaultValue).css("color",t.CurColor)}),t.Enter!=null&&e(this).keydown(function(e){e.keyCode==13&&t.Enter.click()})})}})(jQuery);


/** tab 切换 */
(function(e){e.fn.tab=function(t){return t=e.extend({crt:0},t||{}),this.each(function(){var n=e(this);n.find(".tab-h").find("li").eq(t.crt).addClass("tab-h-c"),n.find(".tab-c").children("div").hide(),n.find(".tab-c>div:eq("+t.crt+")").show(),n.find(".tab-h").find("li").hover(function(){var t=e(this).index();e(this).siblings().removeClass("tab-h-c"),e(this).addClass("tab-h-c"),n.find(".tab-c").children("div").hide(),n.find(".tab-c").children("div").eq(t).fadeIn()},function(){var t=e(this).index();e(this).siblings().removeClass("tab-h-c"),e(this).addClass("tab-h-c"),n.find(".tab-c").children("div").hide(),n.find(".tab-c").children("div").eq(t).show()})})}})(jQuery);


/** 回到顶部*/
(function(e){e.fn.goTop=function(t){var n=e.extend({},e.fn.goTop.defualts,t);_this=e(this),e.fn.goTop.scrollEffects(_this,n),_this.click(function(){e("html,body").animate({scrollTop:0},"fast")})},e.fn.goTop.defualts={right:20,bottom:30},e.fn.goTop.scrollEffects=function(t,n){t.css({position:"absolute",right:n.right});var r=e(window).scrollTop();r>0?t.show():t.hide(),e(window).scroll(function(){var r=n.bottom,i=e(window).height(),s=e(window).scrollTop(),o=t.height();s>0?t.show():t.hide();var u=s+i-(r+o);t.stop(!0,!1).css({top:u})})}})(jQuery); 


/** 倒计时插件*/
$.fn.YMCountDown=function(e,t){var n=$(this);n.time=n.attr("time")*1,n.interval=null,t!=null&&(n.day=n.find("#"+t[0]),n.hour=n.find("#"+t[1]),n.minute=n.find("#"+t[2]),n.second=n.find("#"+t[3])),n.countDow=function(){var r=n.time;if(r>=0){r==0&&window.location.reload();var i=new Array(86400,0,3600,0,60,0,1,0);for(var s=0;s<i.length;s+=2)i[s+1]=Math.floor(r/i[s]),r-=i[s+1]*i[s];t==null?(e?e=e:e="还剩",n.html("<p>"+e+"</p><b>"+i[1]+"</b><em>天</em><b>"+(i[3]<10?"0"+i[3]:i[3])+"</b><em>时</em><b >"+(i[5]<10?"0"+i[5]:i[5])+"</b><em>分</em><b>"+(i[7]<10?"0"+i[7]:i[7])+"</b><em>秒</em>")):(n.day.text(i[1]),n.hour.text(i[3]<10?"0"+i[3]:i[3]),n.minute.text(i[5]<10?"0"+i[5]:i[5]),n.second.text(i[7]<10?"0"+i[7]:i[7]))}},n.countDow(n,n.time--),n.autoChange=function(){n.interval=setInterval(function(){n.countDow(n,n.time--)},1e3)},n.autoChange()};

/** 延时加载  lazyload*/
(function(e){function n(n){var r=e(window).width()+e(window).scrollLeft(),i=e(window).height()+e(window).scrollTop();return r>=e(n).offset().left-t.threshold&&i>=e(n).offset().top-t.threshold}function r(t){return e(t).filter(function(){return!this.loaded})}var t={original:"_src",placeholder:undefined,threshold:0,effect:"fadeIn",effectspeed:"300"};e.fn.lazyload=function(i){i&&e.extend(t,i||{});var s=this;s.each(function(){var r=this;t.placeholder&&e(r).attr("src",t.placeholder),e(r).bind("appear",function(){e("<img />").bind("load",function(){e(r).hide().attr("src",e(r).attr(t.original))[t.effect](t.effectspeed)}).attr("src",e(r).attr(t.original)),r.loaded=!0}),n(this)?(e(this).trigger("appear"),this.loaded=!0):this.loaded=!1}),s=r(s),window.onscroll=function(){s.each(function(){n(this)&&e(this).trigger("appear")}),s=r(s)}}})(jQuery);

/**元素定位 {right:100,top:100,pageWidth:960}*/
(function(e){e.fn.capacityFixed=function(t){var n=e.extend({},e.fn.capacityFixed.deflunt,t),r=function(t){var r=n.top,i=(e(window).width()-n.pageWidth)/2+n.right;t.css({right:i,top:r}),e(window).resize(function(){var r=(e(window).width()-n.pageWidth)/2+n.right;t.css({right:r})}),e(window).scroll(function(){var n=e(this).scrollTop();n>r?window.XMLHttpRequest?t.css({position:"fixed",top:0}):t.css({top:n}):t.css({position:"absolute",top:r})}),t.find(".close-ico").click(function(e){t.remove(),e.preventDefault()})};return e(this).each(function(){r(e(this))})},e.fn.capacityFixed.deflunt={right:100,top:150,pageWidth:960}})(jQuery);

/**滚动到指定元素*/
jQuery.scrollto=function(e,t){$(e).click(function(){var e=$(this).attr("date-scroll");return $(this).addClass("thisscroll").siblings().removeClass("thisscroll"),$("html,body").animate({scrollTop:$(e).offset().top},t),!1})};


/**点击加减或手动改变表单值(支持键盘控制)*/
$.fn.iVaryVal=function(e,t){e=$.extend({Minus:$(".J_minus"),Add:$(".J_add"),Input:$(".J_input"),Min:1,Max:99},e);var n=null,r=null,i={};e.Add.each(function(n){$(this).click(function(){r=parseInt(e.Input.eq(n).val()),r+1<=e.Max||e.Max==null?e.Input.eq(n).val(r+1):e.Input.eq(n).val(e.Max),i.val=e.Input.eq(n).val(),i.index=n,typeof t=="function"&&t(i.val,i.index)})}),e.Minus.each(function(n){$(this).click(function(){r=parseInt(e.Input.eq(n).val()),r-1<e.Min?e.Input.eq(n).val(e.Min):e.Input.eq(n).val(r-1),i.val=e.Input.eq(n).val(),i.index=n,typeof t=="function"&&t(i.val,i.index)})}),e.Input.bind({click:function(){r=parseInt($(this).val()),$(this).select()},keyup:function(s){$(this).val()!=""&&(n=parseInt($(this).val()),/^[1-9]\d*|0$/.test(n)?($(this).val(n),r=n):$(this).val(r)),(s.keyCode==38||s.keyCode==39)&&e.Add.eq(e.Input.index(this)).click(),(s.keyCode==37||s.keyCode==40)&&e.Minus.eq(e.Input.index(this)).click(),i.val=$(this).val(),i.index=e.Input.index(this),typeof t=="function"&&t(i.val,i.index)},blur:function(){$(this).trigger("keyup"),$(this).val()==""&&$(this).val(r),e.Max&&r>e.Max&&$(this).val(e.Max),r<e.Min&&$(this).val(e.Min),i.val=$(this).val(),i.index=e.Input.index(this),typeof t=="function"&&t(i.val,i.index)}})};

/** 滚动插件  示例：$("#s3").Scroll({line:4,speed:500,timer:2000,up:"btn1",down:"btn2"});*/
(function($){$.fn.extend({Scroll:function(opt,callback){if(!opt)var opt={};var _btnUp=$("#"+opt.up);var _btnDown=$("#"+opt.down);var timerID;var _this=this.eq(0).find("ul:first");var lineH=_this.find("li:first").height(),line=opt.line?parseInt(opt.line,10):parseInt(this.height()/lineH,10),speed=opt.speed?parseInt(opt.speed,10):500;timer=opt.timer
if(line==0)line=1;var upHeight=0-line*lineH;var scrollUp=function(){_btnUp.unbind("click",scrollUp);_this.animate({marginTop:upHeight},speed,function(){for(i=1;i<=line;i++){_this.find("li:first").appendTo(_this);}
_this.css({marginTop:0});_btnUp.bind("click",scrollUp);});}
var scrollDown=function(){_btnDown.unbind("click",scrollDown);for(i=1;i<=line;i++){_this.find("li:last").show().prependTo(_this);}
_this.css({marginTop:upHeight});_this.animate({marginTop:0},speed,function(){_btnDown.bind("click",scrollDown);});}
var autoPlay=function(){if(timer)timerID=window.setInterval(scrollUp,timer);};var autoStop=function(){if(timer)window.clearInterval(timerID);};_this.hover(autoStop,autoPlay).mouseout();_btnUp.css("cursor","pointer").click(scrollUp).hover(autoStop,autoPlay);_btnDown.css("cursor","pointer").click(scrollDown).hover(autoStop,autoPlay);}})})(jQuery);



/** artDialog 5 */
(function(h,k,l){if("BackCompat"===document.compatMode)throw Error("artDialog: Document types require more than xhtml1.0");var m,q=0,p="artDialog"+ +new Date,u=k.VBArray&&!k.XMLHttpRequest,t="createTouch"in document&&!("onmousemove"in document)||/(iPhone|iPad|iPod)/i.test(navigator.userAgent),n=!u&&!t,e=function(a,b,c){a=a||{};if("string"===typeof a||1===a.nodeType)a={content:a,fixed:!t};var d;d=e.defaults;var f=a.follow=1===this.nodeType&&this||a.follow,g;for(g in d)a[g]===l&&(a[g]=d[g]);a.id=f&&
f[p+"follow"]||a.id||p+q;if(d=e.list[a.id])return f&&d.follow(f),d.zIndex().focus(),d;if(!n)a.fixed=!1;if(!a.button||!a.button.push)a.button=[];if(b!==l)a.ok=b;a.ok&&a.button.push({id:"ok",value:a.okValue,callback:a.ok,focus:!0});if(c!==l)a.cancel=c;a.cancel&&a.button.push({id:"cancel",value:a.cancelValue,callback:a.cancel});e.defaults.zIndex=a.zIndex;q++;return e.list[a.id]=m?m.constructor(a):new e.fn.constructor(a)};e.version="5.0";e.fn=e.prototype={constructor:function(a){var b;this.closed=!1;
this.config=a;this.dom=b=this.dom||this._getDom();a.skin&&b.wrap.addClass(a.skin);b.wrap.css("position",a.fixed?"fixed":"absolute");b.close[!1===a.cancel?"hide":"show"]();b.content.css("padding",a.padding);this.button.apply(this,a.button);this.title(a.title).content(a.content).size(a.width,a.height).time(a.time);a.follow?this.follow(a.follow):this.position();this.zIndex();a.lock&&this.lock();this._addEvent();this[a.visible?"visible":"hidden"]().focus();m=null;a.initialize&&a.initialize.call(this);
return this},content:function(a){var b,c,d,f,g=this,e=this.dom.content,j=e[0];this._elemBack&&(this._elemBack(),delete this._elemBack);if("string"===typeof a)e.html(a);else if(a&&1===a.nodeType)f=a.style.display,b=a.previousSibling,c=a.nextSibling,d=a.parentNode,this._elemBack=function(){b&&b.parentNode?b.parentNode.insertBefore(a,b.nextSibling):c&&c.parentNode?c.parentNode.insertBefore(a,c):d&&d.appendChild(a);a.style.display=f;g._elemBack=null},e.html(""),j.appendChild(a),h(a).show();return this.position()},
title:function(a){var b=this.dom,c=b.outer,b=b.title;!1===a?(b.hide().html(""),c.addClass("d-state-noTitle")):(b.show().html(a),c.removeClass("d-state-noTitle"));return this},position:function(){var a=this.dom,b=a.wrap[0],c=a.window,d=a.document,f=this.config.fixed,a=f?0:d.scrollLeft(),d=f?0:d.scrollTop(),f=c.width(),e=c.height(),h=b.offsetHeight,c=(f-b.offsetWidth)/2+a,f=f=(h<4*e/7?0.382*e-h/2:(e-h)/2)+d,b=b.style;b.left=Math.max(c,a)+"px";b.top=Math.max(f,d)+"px";return this},size:function(a,b){var c=
this.dom.main[0].style;"number"===typeof a&&(a+="px");"number"===typeof b&&(b+="px");c.width=a;c.height=b;return this},follow:function(a){var b=h(a),c=this.config;if(!a||!a.offsetWidth&&!a.offsetHeight)return this.position(this._left,this._top);var d=c.fixed,e=p+"follow",g=this.dom,s=g.window,j=g.document,g=s.width(),s=s.height(),r=j.scrollLeft(),j=j.scrollTop(),i=b.offset(),b=a.offsetWidth,k=d?i.left-r:i.left,i=d?i.top-j:i.top,o=this.dom.wrap[0],m=o.style,l=o.offsetWidth,o=o.offsetHeight,n=k-(l-
b)/2,q=i+a.offsetHeight,r=d?0:r,d=d?0:j;m.left=(n<r?k:n+l>g&&k-l>r?k-l+b:n)+"px";m.top=(q+o>s+d&&i-o>d?i-o:q)+"px";this._follow&&this._follow.removeAttribute(e);this._follow=a;a[e]=c.id;return this},button:function(){for(var a=this.dom.buttons,b=a[0],c=this._listeners=this._listeners||{},d=[].slice.call(arguments),e=0,g,k,j,l,i;e<d.length;e++){g=d[e];k=g.value;j=g.id||k;l=!c[j];i=!l?c[j].elem:document.createElement("input");i.type="button";i.className="d-button";c[j]||(c[j]={});if(k)i.value=k;if(g.width)i.style.width=
g.width;if(g.callback)c[j].callback=g.callback;if(g.focus)this._focus&&this._focus.removeClass("d-state-highlight"),this._focus=h(i).addClass("d-state-highlight"),this.focus();i[p+"callback"]=j;i.disabled=!!g.disabled;if(l)c[j].elem=i,b.appendChild(i)}a[0].style.display=d.length?"":"none";return this},visible:function(){this.dom.wrap.css("visibility","visible");this.dom.outer.addClass("d-state-visible");this._isLock&&this._lockMask.show();return this},hidden:function(){this.dom.wrap.css("visibility",
"hidden");this.dom.outer.removeClass("d-state-visible");this._isLock&&this._lockMask.hide();return this},close:function(){if(this.closed)return this;var a=this.dom,b=a.wrap,c=e.list,d=this.config.beforeunload,f=this.config.follow;if(d&&!1===d.call(this))return this;if(e.focus===this)e.focus=null;f&&f.removeAttribute(p+"follow");this._elemBack&&this._elemBack();this.time();this.unlock();this._removeEvent();delete c[this.config.id];if(m)b.remove();else{m=this;a.title.html("");a.content.html("");a.buttons.html("");
b[0].className=b[0].style.cssText="";a.outer[0].className="d-outer";b.css({left:0,top:0,position:n?"fixed":"absolute"});for(var g in this)this.hasOwnProperty(g)&&"dom"!==g&&delete this[g];this.hidden()}this.closed=!0;return this},time:function(a){var b=this,c=this._timer;c&&clearTimeout(c);if(a)this._timer=setTimeout(function(){b._click("cancel")},a);return this},focus:function(){if(this.config.focus)try{var a=this._focus&&this._focus[0]||this.dom.close[0];a&&a.focus()}catch(b){}return this},zIndex:function(){var a=
this.dom,b=e.focus,c=e.defaults.zIndex++;a.wrap.css("zIndex",c);this._lockMask&&this._lockMask.css("zIndex",c-1);b&&b.dom.outer.removeClass("d-state-focus");e.focus=this;a.outer.addClass("d-state-focus");return this},lock:function(){if(this._isLock)return this;var a=this,b=this.dom,c=document.createElement("div"),d=h(c),f=e.defaults.zIndex-1;this.zIndex();b.outer.addClass("d-state-lock");d.css({zIndex:f,position:"fixed",left:0,top:0,width:"100%",height:"100%",overflow:"hidden"}).addClass("d-mask");
n||d.css({position:"absolute",width:h(k).width()+"px",height:h(document).height()+"px"});d.bind("click",function(){a._reset()}).bind("dblclick",function(){a._click("cancel")});document.body.appendChild(c);this._lockMask=d;this._isLock=!0;return this},unlock:function(){if(!this._isLock)return this;this._lockMask.unbind();this._lockMask.hide();this._lockMask.remove();this.dom.outer.removeClass("d-state-lock");this._isLock=!1;return this},_getDom:function(){var a=document.body;if(!a)throw Error('artDialog: "documents.body" not ready');
var b=document.createElement("div");b.style.cssText="position:absolute;left:0;top:0";b.innerHTML=e._templates;a.insertBefore(b,a.firstChild);for(var c=0,d={},f=b.getElementsByTagName("*"),g=f.length;c<g;c++)(a=f[c].className.split("d-")[1])&&(d[a]=h(f[c]));d.window=h(k);d.document=h(document);d.wrap=h(b);return d},_click:function(a){a=this._listeners[a]&&this._listeners[a].callback;return"function"!==typeof a||!1!==a.call(this)?this.close():this},_reset:function(){var a=this.config.follow;a?this.follow(a):
this.position()},_addEvent:function(){var a=this,b=this.dom;b.wrap.bind("click",function(c){c=c.target;if(c.disabled)return!1;if(c===b.close[0])return a._click("cancel"),!1;(c=c[p+"callback"])&&a._click(c)}).bind("mousedown",function(){a.zIndex()})},_removeEvent:function(){this.dom.wrap.unbind()}};e.fn.constructor.prototype=e.fn;h.fn.dialog=h.fn.artDialog=function(){var a=arguments;this[this.live?"live":"bind"]("click",function(){e.apply(this,a);return!1});return this};e.focus=null;e.get=function(a){return a===
l?e.list:e.list[a]};e.list={};h(document).bind("keydown",function(a){var b=a.target,c=b.nodeName,d=/^input|textarea$/i,f=e.focus,a=a.keyCode;f&&f.config.esc&&!(d.test(c)&&"button"!==b.type)&&27===a&&f._click("cancel")});h(k).bind("resize",function(){var a=e.list,b;for(b in a)a[b]._reset()});e._templates='<div class="d-outer"><table class="d-border"><tbody><tr><td class="d-nw"></td><td class="d-n"></td><td class="d-ne"></td></tr><tr><td class="d-w"></td><td class="d-c"><div class="d-inner"><table class="d-dialog"><tbody><tr><td class="d-header"><div class="d-titleBar"><div class="d-title"></div><a class="d-close" href="javascript:/*artDialog*/;">\u00d7</a></div></td></tr><tr><td class="d-main"><div class="d-content"></div></td></tr><tr><td class="d-footer"><div class="d-buttons"></div></td></tr></tbody></table></div></td><td class="d-e"></td></tr><tr><td class="d-sw"></td><td class="d-s"></td><td class="d-se"></td></tr></tbody></table></div>';
e.defaults={content:'<div class="d-loading"><span>loading..</span></div>',title:"消息",button:null,ok:null,cancel:null,initialize:null,beforeunload:null,okValue:"确定",cancelValue:"取消",width:"auto",height:"auto",padding:"20px 25px",skin:null,time:null,esc:!0,focus:!0,visible:!0,follow:null,lock:!1,fixed:!1,zIndex:1987};this.artDialog=h.dialog=h.artDialog=e})(this.art||this.jQuery,this);

(function(c){c.alert=c.dialog.alert=function(b,a){return c.dialog({id:"Alert",fixed:!0,lock:!0,content:b,ok:!0,beforeunload:a})};c.confirm=c.dialog.confirm=function(b,a,m){return c.dialog({id:"Confirm",fixed:!0,lock:!0,content:b,ok:a,cancel:m})};c.prompt=c.dialog.prompt=function(b,a,m){var d;return c.dialog({id:"Prompt",fixed:!0,lock:!0,content:['<div style="margin-bottom:5px;font-size:12px">',b,'</div><div><input type="text" class="d-input-text" value="',m||"",'" style="width:18em;padding:6px 4px" /></div>'].join(""),
	initialize:function(){d=this.dom.content.find(".d-input-text")[0];d.select();d.focus()},ok:function(){return a&&a.call(this,d.value)},cancel:function(){}})};c.dialog.prototype.shake=function(){var b=function(a,b,c){var h=+new Date,e=setInterval(function(){var f=(+new Date-h)/c;1<=f?(clearInterval(e),b(f)):a(f)},13)},a=function(c,d,g,h){var e=h;void 0===e&&(e=6,g/=e);var f=parseInt(c.style.marginLeft)||0;b(function(a){c.style.marginLeft=f+(d-f)*a+"px"},function(){0!==e&&a(c,1===e?0:1.3*(d/e-d),g,--e)},
	g)};return function(){a(this.dom.wrap[0],40,600);return this}}();var o=function(){var b=this,a=function(a){var c=b[a];b[a]=function(){return c.apply(b,arguments)}};a("start");a("over");a("end")};o.prototype={start:function(b){c(document).bind("mousemove",this.over).bind("mouseup",this.end);this._sClientX=b.clientX;this._sClientY=b.clientY;this.onstart(b.clientX,b.clientY);return!1},over:function(b){this._mClientX=b.clientX;this._mClientY=b.clientY;this.onover(b.clientX-this._sClientX,b.clientY-this._sClientY);
	return!1},end:function(b){c(document).unbind("mousemove",this.over).unbind("mouseup",this.end);this.onend(b.clientX,b.clientY);return!1}};var j=c(window),k=c(document),i=document.documentElement,p=!!("minWidth"in i.style)&&"onlosecapture"in i,q="setCapture"in i,r=function(){return!1},n=function(b){var a=new o,c=artDialog.focus,d=c.dom,g=d.wrap,h=d.title,e=g[0],f=h[0],i=d.main[0],l=e.style,s=i.style,t=b.target===d.se[0]?!0:!1,u=(d="fixed"===e.style.position)?0:k.scrollLeft(),v=d?0:k.scrollTop(),n=
	j.width()-e.offsetWidth+u,A=j.height()-e.offsetHeight+v,w,x,y,z;a.onstart=function(){t?(w=i.offsetWidth,x=i.offsetHeight):(y=e.offsetLeft,z=e.offsetTop);k.bind("dblclick",a.end).bind("dragstart",r);p?h.bind("losecapture",a.end):j.bind("blur",a.end);q&&f.setCapture();g.addClass("d-state-drag");c.focus()};a.onover=function(a,b){if(t){var c=a+w,d=b+x;l.width="auto";s.width=Math.max(0,c)+"px";l.width=e.offsetWidth+"px";s.height=Math.max(0,d)+"px"}else c=Math.max(u,Math.min(n,a+y)),d=Math.max(v,Math.min(A,
	b+z)),l.left=c+"px",l.top=d+"px"};a.onend=function(){k.unbind("dblclick",a.end).unbind("dragstart",r);p?h.unbind("losecapture",a.end):j.unbind("blur",a.end);q&&f.releaseCapture();g.removeClass("d-state-drag")};a.start(b)};c(document).bind("mousedown",function(b){var a=artDialog.focus;if(a){var c=b.target,d=a.config,a=a.dom;if(!1!==d.drag&&c===a.title[0]||!1!==d.resize&&c===a.se[0])return n(b),!1}})})(this.art||this.jQuery);

/**刷新验证码*/
function change_verify(id,type)
{
  var img_url = '/auth/auth-image/space/'+type+'/code/'+Math.random();
  $("#"+id).attr('src',img_url);
  return;
}

/**
 * 转化大小字母
 * @param obj
 */
function parseUpperCase(obj) {
	obj.value = obj.value.toUpperCase();
}

function fGo(){};
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
 * ajax 分页
 * @param url
 * @param div
 */		
function splitPage(url, div)
{	
	$.post(url,function(data){
		$("#"+div).html(data);
	});	
}	

/**
 * 收藏商品
 */
function favGoods(ob,gid)
{
  if(!gid)
  {
	  $.dialog.alert('<div style="width:150px;text-align:center;">参数错误！</div>'); return false;
  }
  
  $.post('/goods/favorite/goodsid/'+gid,function(data){
   if(data.status==1){
	  $.dialog.alert('<div style="width:150px;text-align:center;">收藏成功！</div>').time(2000);
   }else{
	   $.dialog.alert('<div style="width:150px;text-align:center;">'+data.msg+'<div>'); 
   }	  
  },'json');
  
}

function openTab(obj){
	window.open('/page/opentab/id/' + obj.id + '/goods_id/' + encodeURIComponent(obj.goods_id) + '/select/' + obj.select, "newwindow", "height=750, width=650, top=0, left=0, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,location=no, status=no");
}
function sortby(url,sort)
{
    url = filterUrl(url,'sort');
    window.location.href = url + '/sort/' + sort;
}


/**
 * 判断cookie支持
 *
 * @param    void
 * @return   void
 */
function cookieEnable()
{
    $.cookie('enable', 1, {path: "/", expires: 1});
    if ($.cookie('enable') != 1) {
        alert('对不起,您浏览器的Cookie功能被禁用,开启后才能正常购物!');
    } else {
        $.cookie('enable',null);
    }
}
/**
 * ajax获取数据
 *
 * @param    string    url
 * @param    string    div
 * @return   void
 */
function loadAuthData(url)
{
	$.ajax({
		url:url,
		type:'get',
		success:function(data){
			$('#user_login_span').html(data);
		}
	})
}

//搜索
function chkValue(form){
	var val=form.keywords.value.replace(/^\s+|\s+$/g,"");
	if(val=='' || val=='请输入关键词'){
		alert("请输入您要搜索的关键词！");
		form.keywords.value="";
		form.keywords.focus();
		return false;
	}
	else{
		return true;
	}
}

//设置为首页
function setHomepage(url)
{
	if (document.all)
	{
		document.body.style.behavior='url(#default#homepage)';
		document.body.setHomePage(url);
	}
	else if (window.sidebar)
	{
		if(window.netscape)
		{
			try
			{
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			}
			catch(e)
			{
				alert("this action was aviod by your browser，if you want to enable，please enter about:config in your address line,and change the value of signed.applets.codebase_principal_support to true");
			}
		}
		var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components. interfaces.nsIPrefBranch);
		prefs.setCharPref('browser.startup.homepage',url);
	}
}

var index_tuan = 0;
var adTimer_tuan=null;
var len_tuan=0;
var index = 0;
var adTimer=null;
var len=0;
var menu_timer=null;
var on_object=null
var on1_object=null
//调用首焦图自动轮播
function go(){
	if(adTimer==null){
		adTimer = setInterval(function(){
			  showImg(index);
			   index++;
			if(index==len){index=0;}
			  } , 2000);
	}
}

//轮播，来显示不同的幻灯片
function showImg(index_on){
		$(".slideadv .adv").each(function(ind){					 		
			if(ind==index_on){
			  $(this).find('img').each(function(){
					 if( $(this).attr('_src')){
						 $(this).attr('src', $(this).attr('_src')).removeAttr('_src'); 
					 }
				 });				  
				var tmp_num_on = ".num ul li:eq("+index_on+")";
				$(this).show();				
				$(tmp_num_on).show();
				$(tmp_num_on).attr("class","cur");	
			}else{
				var tmp_banner = ".slideadv .adv:eq("+ind+")";
				var tmp_num = ".num ul li:eq("+ind+")";
				$(tmp_banner).hide();
				$(tmp_num).removeClass("cur");
			}
			
		});
}

//提示信息
function zt_message(msg){
	$("#m_content").html(msg);
	$("#xj_msg_info").fadeTo(1000,1,function(){
	$("#xj_msg_info").fadeOut("slow");
	});
}
function delShopCart(id,url){
	var priceQuantity=$("#cartPrice_"+id).html().replace("￥","").replace("元","");
	var priceQuantityArr=priceQuantity.split("×");
	var price=parseFloat(priceQuantityArr[0]);
	$.ajax({
		type: "GET",
		cache:false,
		url: url,
		success: function(){
			var totalQuantity=parseInt($("#cartQuantity").html())-1;
			if(totalQuantity<=0){
				$("#mycart-listbox").html('<div style="text-align: center;height:20px;line-height:20px;margin-bottom:10px;" class="priceColor">购物车中没有购买的产品！</div>');
			}
			else{
				var totalPrice=parseFloat($("#cartPrice").html())-price;
				$("#cartGoods_"+id).remove();
				$("#cartQuantity").html(totalQuantity);
				$("#cartPrice").html(totalPrice);

			}
			$("#showTotalQuantity").html(" "+totalQuantity+" ");

		}
	});
}

function addGoodsToCart(url){
	window.location.href=url+"-num"+$("#buyQuantity").val()+".html";
}


//清空浏览记录
function clearCook(url,elt) {
	$.ajax({
		type : "GET",
		cache : false,
		url : url,
		success : function(msg) {
			$(elt).parent().parent().find('.conts').empty().html("<div style='color:#999999;padding:10px;'>暂无浏览记录！</div>");;
		}
	});
}

/**
 * 倒计时
 * @param int time  
 * @param string day_elem
 * @param string hour_elem
 * @param string minute_elem
 * @param string second_elem
 */
function countDown(time,day_elem,hour_elem,minute_elem,second_elem){	
	sys_second = time-cur_time;	
	var timer = setInterval(function(){
		if (sys_second > 0) {
			sys_second -= 1;
			var day = Math.floor((sys_second / 3600) / 24);
			var hour = Math.floor((sys_second / 3600) % 24);
			var minute = Math.floor((sys_second / 60) % 60);
			var second = Math.floor(sys_second % 60);
			day_elem && $(day_elem).text(day);//计算天
			$(hour_elem).text(hour<10?"0"+hour:hour);//计算小时
			$(minute_elem).text(minute<10?"0"+minute:minute);//计算分
			$(second_elem).text(second<10?"0"+second:second);// 计算秒
		} else { 
			clearInterval(timer);	
			if(sys_second == 0) window.location.reload(); //刷新页面
		}
	}, 1000);
}

