(function($){
	$.fn.goTop = function(options){
        var opts = $.extend({}, $.fn.goTop.defualts, options); 
		_this = $(this);		
		$.fn.goTop.scrollEffects(_this,opts);		
		_this.click(function(){
			$("html,body").animate({scrollTop:0},"fast");
			});
		
	}

	$.fn.goTop.defualts ={
		right:20,
		bottom:30 
	}	
	$.fn.goTop.scrollEffects = function(_this,opts){
		_this.css({position:"absolute",right:opts.right});
		var scrollTop = $(window).scrollTop();
		if(scrollTop > 0){_this.show();}else{_this.hide();}
		$(window).scroll(function(){
			var topValue = opts.bottom; 
			var showHeight = $(window).height();
			var scrollTop = $(window).scrollTop();
			var thisHeight = _this.height();
			if(scrollTop > 0){_this.show();}else{_this.hide();}
			var topNum = (scrollTop+showHeight)-(topValue+thisHeight);
			//_this.stop(true,false).delay(300).animate({top:topNum},"slow");
			_this.stop(true,false).css({top:topNum});
		});
	}
})(jQuery);    