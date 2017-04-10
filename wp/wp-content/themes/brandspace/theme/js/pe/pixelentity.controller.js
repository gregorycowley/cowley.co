(function ($) {
	/*jslint undef: false, browser: true, devel: false, eqeqeq: false, bitwise: false, white: false, plusplus: false, regexp: false, nomen: false */ 
	/*global jQuery,setTimeout,clearTimeout,projekktor,location,setInterval,YT,clearInterval,pixelentity,prettyPrint */
	
	function noop() {
		return false;
	}
	
	function imgfilter() {
		return this.href.match(/\.(jpg|jpeg|png|gif)$/i);
	}
	
	pixelentity.classes.Controller = function() {
		
		function autoFlare(idx,el) {
			el = $(el);
			el.attr("data-target","flare");
			var img = el.find("img:first");
			if (img.length === 1 && !(el.parent().hasClass("wp-caption") || img.hasClass("alignleft") || img.hasClass("alignleft"))) {
				el.addClass("peOverInfo");
			}
		}

		function quotation(e) {
			var el = $(e.currentTarget);
			if (el.is(":checked")) {
				if (el.val() !== "quotation") {
					$(".peThemeContactForm #project").slideUp("fast");
				} else {
					$(".peThemeContactForm #project").slideDown("fast");
				}
			}
		}
		
		function centerPagination() {
			var jthis = $(this);
			jthis.css("margin-left",-jthis.width()/2);
		}

		
		function start() {
			if ($.pixelentity.browser.mobile) {
				$("html").addClass("mobile");
			}
			
			$('a[data-target!="flare"]').filter(imgfilter).each(autoFlare);
			
			if ($("aside.sidebar").length > 0) {
				$("body").addClass("hasSidebar");
			}
			
			$.pixelentity.widgets.build($("body"),{});
			
			$(".widget_categories ul li").prepend('<span class="icon-right-open"></span>');
			
			// Responsive nav
			window.selectnav('navigation', {
				label: $("#dropdown-nav").attr("data-label"),
				autoselect: false,
				nested: true,
				indent: '–-'
			});
			$("#dropdown-nav").append($("#selectnav1"));
			
			$(".peThemeContactForm #contactType input[type='radio']").click(quotation).triggerHandler("click");
			
			$(".pagination ul").each(centerPagination);
			
			$("img.peLazyLoading").lazyload({
				effect       : "fadeIn",
				skipInvisible: false
			});

			
		}
		
		start();
	};
	
}(jQuery));
