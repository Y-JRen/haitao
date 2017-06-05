<!--
var alertBox={
    // 根据ID获得DOM节点
    $: function(i){
	     if(!document.getElementById)return false;
	     if(typeof i==="string"){
	   	     if(document.getElementById && document.getElementById(i)) {// W3C DOM
	              return document.getElementById(i);
           }
           else if (document.all && document.all(i)) {// MSIE 4 DOM
	              return document.all(i);
           }
           else if (document.layers && document.layers[i]) {// NN 4 DOM.. note: this won't find nested layers
	              return document.layers[i];
           } 
           else {
	              return false;
           }
	     }
	     else{return i;}
    },
    oShadow: null,        // 遮照层 
    oAlertWindow: null,   // 提示框层
    oDragHandle: null,    // 提示框的拖动柄
    
    bID: null,            // 提示框的ID标识
    bTitle: null,         // 提示框的提示标题
    bMessage: null,       // 提示框的提示内容
    bMsgT: 1,             // 提示框的类型，1为普通提示,
    bUrl: null,           // 提示框跳转
    bEvent: null,         // 确定按钮绑定事件
    bShadow: true,        // 是否锁定背景，默认锁定背景
    bEnter: false,        // 是否显示确定按钮，默认不显示
    bCancel: false ,      // 是否显示取消按钮，默认不显示
    bLast: false,         // 是否保留已有提示框，默认不保留
    bDragWindow: true,    // 是否拖动提示框，默认拖动
    bSlideWindow: false,  // 是否渐变显示提示框，默认不渐变
    bMS: null,            // 自动跳转时间，默认不跳转
    
    init: function(str){
    	    var id,msg,url,event,MS,last,cancel,title,msgT,msgW,msgH,shadow,enter,drag,slide;
    	    eval(str);
    	    this.bID=id?id:Math.round(Math.random()*1000);
    	    this.bTitle=title?title:'';
    	    this.bMessage=msg?msg:'';
    	    this.bMS=MS?MS:'-1';
    	    this.bEvent=event?event:'';
    	    this.bUrl=url?url:'';
    	    this.bMsgW=msgW?msgW:300;
    	    this.bMsgH=msgH?msgH:120;
    	    this.bMsgT=msgT?msgT:1;
    	    this.bShadow=shadow?true:false;
    	    this.bEnter=enter?true:false;
    	    this.bCancel=cancel?true:false;
    	    this.bLast=last?true:false;
    	    this.bSlideWindow=slide?true:false;
    	    this.bDragWindow=drag?false:true;
    	    if(this.bMsgT!=1)this.bDragWindow=false;
    	    
    	    if(!this.bLast) alertBox.closeDiv();
    	    
            alertBox.createDiv();
            
            var yScroll=alertBox.getPageScroll()[1];
	   	    var pageHeight=alertBox.getPageHeight();
	   	    var marginTop=yScroll+(pageHeight-this.bMsgH)/2;
	        alertBox.marginTop= marginTop;
	        alertBox.dragDiv();
	        alertBox.fixPos();
            
            if(MS>0)
            {
	            if(this.bEvent!=null && this.bEvent!=''){
	                var tmp="setTimeout(\""+this.bEvent+";alertBox.closeDiv('"+this.bID+"');\","+MS+")";
	                eval(tmp);
	            }else{
	            	setTimeout("alertBox.closeDiv('"+this.bID+"')",MS);
	            }
            }
    }, 
    
    createDiv: function(obj){
    	   this.pageHeight=this.getPageHeight();
    	   this.yScroll=this.getPageScroll()[1];
    	   this.xScroll=this.getPageScroll()[0];
    	   this.bodyHeight=(this.yScroll<this.pageHeight)?this.pageHeight:this.yScroll;
	       var shadow=document.createElement("div");
	       
	       shadow.setAttribute("id","shadow"+this.bID);
	       shadow.className="alertbox-shadow";
	       if(this.bShadow)shadow.onclick=function(){alertBox.closeDiv();}
	       //shadow.style.height=this.bodyHeight+"px";
	       
	       this.bMsgW=this.bMsgW+12;
	       this.bMsgH=this.bMsgH+32;
	       
	       var obj=document.createElement("div");
	       obj.setAttribute("id","window"+this.bID);
	       obj.className="alertbox-window";
	       obj.style.width=this.bMsgW+"px";
	       obj.style.height=this.bMsgH+"px";
	       obj.style.zIndex="999";
	       
	       var box=document.createElement("div");
	       box.setAttribute("id","alertbox-window");
	       
	       if(document.all){
			       if(window.opera){
			       	    obj.style.opacity=0.1;
			       }
			       else{
			       	    obj.style.filter="alpha(opacity=10)";
			       }
		     }
		     else{
			       obj.style.opacity=0.1;
		     }
		     
	       var divTitle=document.createElement("div");
	       divTitle.className="win-tl";
	       divTitle.style.width=this.bMsgW+"px";
	       
	       var H2=document.createElement("h2");
	       H2.style.width=(this.bMsgW-21-12)+"px";
	       
	       var IMG=document.createElement("div");
	       IMG.className="img";
	       
	       if(this.bTitle==null || this.bTitle=='')
	       {
	           var txtTitle=document.createTextNode("系统提示");
	       }else{
	           var txtTitle=document.createTextNode(this.bTitle);
	       }
	       
	       H2.appendChild(IMG); 
	       H2.appendChild(txtTitle);
	       
	       var closeBar=document.createElement("div");
	       closeBar.className="closebar";
           
	       var A=document.createElement("div");
	       A.innerHTML="<a href=\"javascript:fGo();\" class=\"btnclose\" title=\"关闭窗口\" onclick=\"alertBox.closeDiv('"+this.bID+"')\"> </a>";
	       
	       closeBar.appendChild(A);
	       
	       var titleRight=document.createElement("div");
	       titleRight.className="win-tr";
	       
	       divTitle.appendChild(H2);
	       divTitle.appendChild(closeBar);
	       divTitle.appendChild(titleRight);
	       
	       var Container=document.createElement("div");
	       Container.className="msg-content";
	       Container.style.width=this.bMsgW+"px";
	       Container.style.height=(this.bMsgH-3-29)+"px";
           
	       
	       var cntLeft=document.createElement("div");
	       cntLeft.className="msg-leftbar";
	       cntLeft.style.height=(this.bMsgH-29-3)+"px";
	       
	       var MSG=document.createElement("div");
	       MSG.setAttribute("id","msg"+this.bID);
	       MSG.className="msg";
	       MSG.style.width=(this.bMsgW-6)+"px";
	       MSG.style.height=(this.bMsgH-3-29)+"px";
	   
	   if(this.bMsgT!='iframe'){
	       var INFO=document.createElement("div");
	       INFO.className="info";
	       
	       var P=document.createElement("p");
	       
	       if(this.bMessage==null || this.bMessage=='')
	       {
	       P.innerHTML="操作成功！";
	       }else{
	       P.innerHTML=this.bMessage;
	       }
	       INFO.appendChild(P);
	       
	       var Btns=document.createElement("div");
	       Btns.className="btns";
	       if(this.bEnter==true){
		       var bEnter=document.createElement("input");
		       bEnter.setAttribute("type","button");
		       bEnter.setAttribute("id","btnok"+this.bID);
		       bEnter.setAttribute("value"," 确定 ");
		       bEnter.className="btnok";
		       
		       if(this.bEvent==null || this.bEvent=='' || this.bMS>0){
		           eval("bEnter.onclick=function(){alertBox.closeDiv("+this.bID+")}");
		       }else{
		       	   eval("bEnter.onclick=function(){alertBox.closeDiv("+this.bID+");"+this.bEvent+"}");
		       }
		       Btns.appendChild(bEnter);
	       }
	       
	       if(this.bCancel==true){
		       var bCancel=document.createElement("input");
		       bCancel.setAttribute("type","button");
		       bCancel.setAttribute("id","btncancel"+this.bID);
		       bCancel.setAttribute("value"," 取消 ");
		       bCancel.className="btncancel";
		       eval("bCancel.onclick=function(){alertBox.closeDiv("+this.bID+");}");
		       Btns.appendChild(bCancel);
	       }
	       
	       MSG.appendChild(INFO);
	       MSG.appendChild(Btns);
	   }else{
	       var P=document.createElement("p");
	       P.setAttribute("id","boxIframe"+this.bID);
	       P.innerHTML='<iframe src="about:blank" frameborder="0" hspace="0" name="boxFrame'+this.bID+'" id="boxFrame" style="width:'+(this.bMsgW-6)+'px ;height:'+(this.bMsgH-3-29)+'px"></iframe>';
	       MSG.appendChild(P);
	   }
	       
	       var cntRight=document.createElement("div");
	       cntRight.className="msg-rightbar";
	       cntRight.style.height=(this.bMsgH-29-3)+"px";
	       
	       Container.appendChild(cntLeft);
	       Container.appendChild(MSG);
	       Container.appendChild(cntRight);
	       
	       var msgBottom=document.createElement("div");
	       msgBottom.className="msg-bottom";
	       msgBottom.style.width=(this.bMsgW-6)+"px";
	       
	       var msgBLeft=document.createElement("div");
	       msgBLeft.className="msg-bottom-left";
	       
	       var msgBRight=document.createElement("div");
	       msgBRight.className="msg-bottom-right";
	       
	       document.body.appendChild(shadow);
	       box.appendChild(divTitle);
	       box.appendChild(Container);
	       box.appendChild(msgBLeft);
	       box.appendChild(msgBottom);
	       box.appendChild(msgBRight);
	       obj.appendChild(box);
	       document.body.appendChild(obj);
	       
	       if(this.bMsgT=='iframe'){
	       	   document.getElementById('boxFrame').src=this.bUrl;
	       }
	       
	       this.oAlertWindow=obj;
	       this.oShadow=shadow;
	       this.oDragHandle=divTitle;
	       
	       alertBox.setEvent();
	   },
	   
	   setEvent: function(){
	       this.marginTop=this.yScroll+(this.pageHeight-this.oAlertWindow.offsetHeight)/2;
	       
	       if(this.bDragWindow){
	       	    this.dragDiv();
	       }
	       else{
	            this.fixPos();
	       }
	       
	       
	       if(this.bSlideWindow){
	       	    this.slideDiv();
	       }
	       else{
	            if(document.all){
			             if(window.opera){
			                 this.oAlertWindow.style.opacity=1;
			             }
			             else{	
			                 this.oAlertWindow.style.filter="";
			             }
		          }
		          else{
			             this.oAlertWindow.style.opacity=1;
		          }
	       }
	       
	       if(this.bEnter==true){this.$("btnok"+this.bID).focus();}
     
     },
     
     closeDiv: function(id){
     	    if (!this.oAlertWindow) return false;
     	    if(this.oAlertWindow.style.filter=="" || this.oAlertWindow.style.opacity==1){
               if (id && id!='undefined') {
	               if(this.$("window"+id)) document.body.removeChild(this.$("window"+id));
	               if(this.$("shadow"+id)) document.body.removeChild(this.$("shadow"+id));
               }else{
               	   var t = $(document).getElements('div[id^=shadow],div[id^=window]');
               	   for (var i = 0; i < t.length; i++){
               	   	   document.body.removeChild(t[i]);
               	   }
               }
               if(this.bUrl!='' && this.bUrl!=null && this.bUrl!='event'){
	               if(this.bUrl=='?'){window.location.replace('?');}
	               else if(this.bUrl=='reload'){window.location.reload();}
	               else if(this.bUrl=='goback'){Gurl('backward');}
	               else{G(this.bUrl);}
               }
          }
     },
     
     dragDiv: function(){
	        this.maxDragWidth=document.body.offsetWidth-this.bMsgW;
	        this.maxDragHeight=this.bodyHeight-this.bMsgH;
     
	        Drag.init(this.oDragHandle,this.oAlertWindow,true,0,this.maxDragWidth,0,this.maxDragHeight);
     },
     
     slideDiv: function(){
	       var i=10;
	       var j=0.1;
	       var _fliter_=function(){
	   	       if(document.all){
	   	    	       if(i>100 || j>1){
	   	    	            if(tt) tt=window.clearInterval(tt);
			                  if(window.opera){
			                       this.oAlertWindow.style.opacity=1;
			                  }
			                  else{	
	   	    	   	             alertBox.oAlertWindow.style.filter ="";
	   	    	            }
	   	    	            return false;
	   	    	        }
			              if(window.opera){
			                  alertBox.oAlertWindow.style.opacity=j;
			                  j += 0.1;
			              }
			              else{	
	   	                  alertBox.oAlertWindow.style.filter="alpha(opacity="+i+")";
	   	    	            i += 10;
	   	              }    
	   	       }
	   	       else{
	   	    	        if(j>1){
	   	    	             if(tt) tt=window.clearInterval(tt);
	   	    	             alertBox.oAlertWindow.style.opacity=1;
	   	    	             return false;
	   	    	        }
			              alertBox.oAlertWindow.style.opacity=j;
			              j += 0.1;
	   	       }
	       }
	       var tt=window.setInterval(_fliter_,50);
     },
     
     fixPos: function(){
     	    this.oAlertWindow.style.top= this.marginTop+"px";
	        this.oAlertWindow.style.left=(document.body.offsetWidth-this.bMsgW)/2+"px";	
     },
     
     getPageScroll: function(){
     	    var xScroll, yScroll;
          if (self.pageYOffset) {
               yScroll=self.pageYOffset;
               xScroll=self.pageXOffset;
          } else if (document.documentElement && document.documentElement.scrollTop) {	 
          	   // Explorer 6 Strict
               yScroll=document.documentElement.scrollTop;
               xScroll=document.documentElement.scrollLeft;
          } else if (document.body) {
          	   // all other Explorers
               yScroll=document.body.scrollTop;
               xScroll=document.body.scrollLeft;	
          }
          return new Array(xScroll,yScroll); 
     }, 
     
     getPageHeight: function(){
          var windowHeight
          if (self.innerHeight) {	
          	   // all except Explorer
               windowHeight=self.innerHeight;
          } else if (document.documentElement && document.documentElement.clientHeight) { 
          	   // Explorer 6 Strict Mode
               windowHeight=document.documentElement.clientHeight;
          } else if (document.body) { // other Explorers
               windowHeight=document.body.clientHeight;
          }	
          return windowHeight
     }  	
}



var Drag={
	obj : null,
	zIndex : 300,
	MaxWidth : null,
    MaxHeight : null,
  
  arrInit : function(handles,drags){
  	 for(var i=0;i<arrHandle.length;i++){
  	 	 var h=$(handles[i]);
  	 	 var d=$(drags[i]);
  	 	 if(!h || !d) return false;
  	 	 this.MaxWidth=parseInt(document.body.offsetWidth)-parseInt($(drags[i]).offsetWidth);
  	 	 this.MaxHeight=parseInt(document.body.offsetHeight)-parseInt($(drags[i]).offsetHeight);
       this.init(h, d, true, 0, this.MaxWidth, 0, this.MaxHeight);
     }
  },
  
	init : function(o, oRoot, iFloat, minX, maxX, minY, maxY, bSwapHorzRef, bSwapVertRef, fXMapper, fYMapper)
	{
		if(!o || !oRoot) return false;
		
		o.setAttribute("title","拖动");
        o.style.cursor="move";
		
		o.onmousedown	= Drag.start;

		o.hmode			= bSwapHorzRef ? false : true ;
		o.vmode			= bSwapVertRef ? false : true ;

		o.root=oRoot && oRoot != null ? oRoot : o ;
		o.root.onclick=function(){
			 o.root.style.zIndex=Drag.zIndex;
		   Drag.zIndex++;	
		}
			  
    if(iFloat){
    	  o.root.style.left=((parseInt(document.body.offsetWidth)-parseInt(o.root.offsetWidth))/2)+"px";
    	  o.root.style.top=alertBox.marginTop+"px";
    }
    else{	
		   if (o.hmode  && isNaN(parseInt(o.root.style.left  ))) o.root.style.left  ="0px";
		   if (o.vmode  && isNaN(parseInt(o.root.style.top   ))) o.root.style.top   ="0px";
		   if (!o.hmode && isNaN(parseInt(o.root.style.right ))) o.root.style.right ="0px";
		   if (!o.vmode && isNaN(parseInt(o.root.style.bottom))) o.root.style.bottom="0px";
    }
    
		o.minX	= typeof minX != 'undefined' ? minX : null;
		o.minY	= typeof minY != 'undefined' ? minY : null;
		o.maxX	= typeof maxX != 'undefined' ? maxX : null;
		o.maxY	= typeof maxY != 'undefined' ? maxY : null;

		o.xMapper=fXMapper ? fXMapper : null;
		o.yMapper=fYMapper ? fYMapper : null;

		o.root.onDragStart	= new Function();
		o.root.onDragEnd	= new Function();
		o.root.onDrag		= new Function();
	},

	start : function(e)
	{
		var o=Drag.obj=this;
		e=Drag.fixE(e);	
		Drag.fixIndex(o);
		
		var y=parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
		var x=parseInt(o.hmode ? o.root.style.left : o.root.style.right );

		o.root.onDragStart(x,y);

		o.lastMouseX	= e.clientX;
		o.lastMouseY	= e.clientY;

		if (o.hmode) {
			if (o.minX != null)	o.minMouseX	= e.clientX - x + o.minX;
			if (o.maxX != null)	o.maxMouseX	= o.minMouseX + o.maxX - o.minX;
		} else {
			if (o.minX != null) o.maxMouseX=-o.minX + e.clientX + x;
			if (o.maxX != null) o.minMouseX=-o.maxX + e.clientX + x;
		}

		if (o.vmode) {
			if (o.minY != null)	o.minMouseY	= e.clientY - y + o.minY;
			if (o.maxY != null)	o.maxMouseY	= o.minMouseY + o.maxY - o.minY;
		} else {
			if (o.minY != null) o.maxMouseY=-o.minY + e.clientY + y;
			if (o.maxY != null) o.minMouseY=-o.maxY + e.clientY + y;
		}

		document.onmousemove	= Drag.drag;
		document.onmouseup		= Drag.end;

		return false;
	},

	drag : function(e)
	{
		e=Drag.fixE(e);
		var o=Drag.obj;
    
    if(document.all){
    	if(window.opera){
    		 o.root.style.opacity=0.5;
    	}
    	else{
			   o.root.style.filter="alpha(opacity=50)";
		  }
		}
		else{
			o.root.style.opacity=0.5;
		}
    
		var ey	= e.clientY;
		var ex	= e.clientX;
		var y=parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
		var x=parseInt(o.hmode ? o.root.style.left : o.root.style.right );
		var nx, ny;

		if (o.minX != null) ex=o.hmode ? Math.max(ex, o.minMouseX) : Math.min(ex, o.maxMouseX);
		if (o.maxX != null) ex=o.hmode ? Math.min(ex, o.maxMouseX) : Math.max(ex, o.minMouseX);
		if (o.minY != null) ey=o.vmode ? Math.max(ey, o.minMouseY) : Math.min(ey, o.maxMouseY);
		if (o.maxY != null) ey=o.vmode ? Math.min(ey, o.maxMouseY) : Math.max(ey, o.minMouseY);

		nx=x + ((ex - o.lastMouseX) * (o.hmode ? 1 : -1));
		ny=y + ((ey - o.lastMouseY) * (o.vmode ? 1 : -1));

		if (o.xMapper)		nx=o.xMapper(y)
		else if (o.yMapper)	ny=o.yMapper(x)

		o.root.style[o.hmode ? "left" : "right"]=nx +"px";
		o.root.style[o.vmode ? "top" : "bottom"]=ny +"px";
		o.lastMouseX	= ex;
		o.lastMouseY	= ey;

		o.root.onDrag(nx, ny);
		return false;
	},

	end : function()
	{
		var o=Drag.obj; 
		document.onmousemove=document.onmouseup=function(){
		    if(document.all){
		    	   if(window.opera){
		             Drag.obj.root.style.opacity=1;
		         }
		         else{
		             Drag.obj.root.style.filter="";
		         }
	      }
	      else{
		         Drag.obj.root.style.opacity=1;
	      }
	      return null;
	  }
		o.root.onDragEnd(parseInt(o.root.style[o.hmode ? "left" : "right"]), parseInt(o.root.style[o.vmode ? "top" : "bottom"]));
		o=null;
	},

	fixE : function(e)
	{
		if (typeof e == 'undefined') e=window.event;
		if (typeof e.layerX == 'undefined') e.layerX=e.offsetX;
		if (typeof e.layerY == 'undefined') e.layerY=e.offsetY;
		return e;
	},
	
	fixIndex : function(elem){	  
		elem.root.style.zIndex=Drag.zIndex;
		Drag.zIndex++;	
	}
}
//-->