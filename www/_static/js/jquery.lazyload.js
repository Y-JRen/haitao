/**
* high.Lazyload plugin
*
* Copyright (c) 2011 shanhuhai (jquerycn.cn)
* Dual licensed under the MIT and GPL licenses:
* http://www.opensource.org/licenses/mit-license.php
* site:http://www.jquerycn.cn
* E-mail:jquerycn@qq.com
*/
(function($) {
	var settings = {
		original     : '_src',
		placeholder  : undefined,
		threshold    : 0,
		effect       : 'fadeIn',
		effectspeed  : '300'
	};

	$.fn.lazyload = function(options) {
		if(options) {
			$.extend(settings, options || {});
		}

		var elements = this;
		elements.each(function(){
			
			var self = this;
			if (settings.placeholder) {
				$(self).attr('src', settings.placeholder);
			}

			$(self).bind('appear', function(){
				$('<img />').bind('load', function(){
					$(self).hide().attr('src', $(self).attr(settings.original))[settings.effect](settings.effectspeed);
				}).attr("src",$(self).attr(settings.original) );
				self.loaded = true;
			})
			if(canload(this)){
				$(this).trigger("appear");
				this.loaded = true;
			}else{
				this.loaded = false;
			}

		})

		elements = filter(elements);
		window.onscroll = function(){
			elements.each(function(){
				if(canload(this))
				{
					$(this).trigger("appear");
				}
			});
			elements = filter(elements);
		}
	}

	function canload(img){ //检测图片是否在视野范围内
		var hold_x = $(window).width()+$(window).scrollLeft();
		var hold_y = $(window).height()+$(window).scrollTop();
		return hold_x >= $(img).offset().left - settings.threshold && hold_y >= $(img).offset().top - settings.threshold;
	}

	function filter(o){ //过滤已载入的图片
		return $(o).filter(function(){
			return !this.loaded;
		});
	}
})(jQuery);