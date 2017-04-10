(function ($) {
	/*jslint undef: false, browser: true, devel: false, eqeqeq: false, bitwise: false, white: false, plusplus: false, regexp: false, nomen: false */ 
	/*global jQuery,setTimeout,location,setInterval,YT,clearInterval,clearTimeout,pixelentity,WebKitCSSMatrix, */
	
	$.pixelentity = $.pixelentity || {version: '1.0.0'};
	
	$.pixelentity.peVoloCaptions = {	
		conf: {
			slider: false,
			api: false
		} 
	};
	
	// 0-480, 481-767, 768-980, 980 - anything
	var cssT = $.support.csstransitions;
	var properties = "";
	
	if (cssT) {
		properties = "opacity, %0transform".format($.support.csstransitionsPrefix);
	}
		
	function PeVoloCaptions(target, conf) {
		var w,h;
		var slider;
		var slides;
		var active = {};
		var current = -1;
		//var captionsLayer;
		
		function resizeCaption(c) {
			if (!w || !h || w<20 || h<20) {
				setTimeout(resize,100);
				return;
			}
			c = $(c);
			var align = (c.attr("data-align") || "bottom,left").split(",");
			var scaler = $.pixelentity.Geom.getScaler("none",align[1],align[0],w,h,c.outerWidth(),c.outerHeight());
			var co = (c.attr("data-offset") || "-20,40").split(",");
			c.css({
				"margin-top": scaler.offset.h+parseInt(co[0],10),
				"margin-left": scaler.offset.w+parseInt(co[1],10)
			});
			
		}

		
		function resizeCaptions() {
			var i,j;
			for (i in active) {
				if (typeof i == "string" && active[i]) {
					for (j = 0; j<active[i].length;j++) {
						resizeCaption(active[i][j]);
					}
				}
			}
		}
		
		
		function resize() {
			w = target.width();
			h = target.height();
			resizeCaptions();				
		}
		
		function remove(el,idx) {
			slides[idx].append(active[idx]);
			delete active[idx];
		}
		
		function fadeIn(el) {
			//console.log(cssT+"Duration");
			var jel = $(el);
			var duration = parseFloat(jel.attr("data-duration") || 0.5);
			var delay = parseFloat(jel.attr("data-delay") || 0);
			
			if (cssT) {
				el.style[cssT+"Property"] = properties;
				el.style[cssT+"Duration"] = duration + "s";
				el.style[cssT+"Delay"] = delay + "s";
				jel.css("opacity",1);
				jel.transform(1,0,0,w,h);
			} else {
				jel.stop().delay(delay*1000).animate({opacity:1,left:0,top:0},duration*1000);
			}
		}
		
		function fadeOut(el) {
			var jel = $(el);
			if (cssT) {
				el.style[cssT+"Property"] = "opacity";
				el.style[cssT+"Delay"] = "0s";
				el.style[cssT+"Duration"] = "0.5s";	
				jel.css("opacity",0);	
			} else {
				jel.stop().animate({opacity:0},500);
			}
			//console.log(el);
		}

		function clean() {
			var i;
			for (i in active) {
				if (typeof i == "string" && i != current && active[i]) {
					slides[i].append(active[i]);
					active[i] = false;
				}
			}
		}
		
		function setTransition(el) {
			el = $(el);
			el.fadeTo(0,0);
			var left = 0, top = 0;
			switch (el.attr("data-transition")) {
			case "flyRight":
				left = 100;
				break;
			case "flyLeft":
				left = -100;
				break;
			case "flyTop":
				top = -100;
				break;
			case "flyBottom":
				top = 100;
				break;
			}
			if (cssT) {
				el.transform(1,left,top,w,h);
			} else {
				el.css({left:left,top:top});
			}

		}

		
		function change(e,data) {
			var i,j,idx = data.slideIdx-1;
			if (idx === current) {
				return;
			}
			var c = slides[idx].find(".peCaption");
			for (i = 0; i<c.length;i++) {
				setTransition(c[i]);
			}
			c.fadeTo(0,0);
			target.prepend(c);
			
			current = idx;
			active[idx] = c;
			
			resize();
			
			for (i in active) {
				if (typeof i == "string" && i != idx && active[i]) {
					for (j = 0; j<active[i].length;j++) {
						fadeOut(active[i][j]);
					}
				}
			}
			
			setTimeout(clean,500);
			
			for (i = 0; i<c.length;i++) {
				fadeIn(c[i]);
			}
			
			active[idx] = c;
		}
		
		function ready(e,data) {
			slides = data.markup;
		}
		
		// init function
		function start() {
			if (conf.slider) {
				link(conf.slider);
			}
		}
		
		function link(s) {
			slider = s;
			slider.bind("ready.pixelentity",ready);
			slider.bind("resize.pixelentity",resize);
			slider.bind("change.pixelentity",change);
		}
		
		$.extend(this, {
			// plublic API
			link: link,
			destroy: function() {
				target.data("peVoloCaptions", null);
				target = undefined;
			}
		});
		
		// initial0ize
		start();
	}
	
	// jQuery plugin implementation
	$.fn.peVoloCaptions = function(conf) {
		
		// return existing instance	
		var api = this.data("peVoloCaptions");
		
		if (api) { 
			return api; 
		}
		
		conf = $.extend(true, {}, $.pixelentity.peVoloCaptions.conf, conf);
		
		// install the plugin for each entry in jQuery object
		this.each(function() {
			var el = $(this);
			api = new PeVoloCaptions(el, conf);
			el.data("peVoloCaptions", api); 
		});
		
		return conf.api ? api: this;		 
	};
	
}(jQuery));
