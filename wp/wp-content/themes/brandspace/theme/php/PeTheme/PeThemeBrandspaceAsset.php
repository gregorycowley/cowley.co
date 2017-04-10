<?php

class PeThemeBrandspaceAsset extends PeThemeAsset  {

	public function __construct(&$master) {
		$this->minifiedJS = "theme/compressed/theme.min.js";
		$this->minifiedCSS = "theme/compressed/theme.min.css";
		parent::__construct($master);
	}

	public function registerAssets() {

		add_filter("pe_theme_minified_js_deps",array(&$this,"pe_theme_minified_js_deps_filter"));
		add_filter("pe_theme_video_js_deps",array(&$this,"pe_theme_video_js_deps_filter"));
		add_filter("pe_theme_flare_css_deps",array(&$this,"pe_theme_flare_css_deps_filter"));
		add_filter("pe_theme_bootstrap_js",array(&$this,"pe_theme_bootstrap_js_filter"));

		parent::registerAssets();

		$options =& $this->master->options->all();		

		// override projekktor skin
		wp_deregister_style("pe_theme_projekktor");
		$this->addStyle("framework/js/pe.flare/video/theme/style.css",array(),"pe_theme_projekktor");

		$this->addStyle("css/dark_skin.css",array(),"pe_theme_brandspace_dark_skin");		

		if ($this->minifyCSS) {
			$deps = 
				array(
					  "pe_theme_compressed"
					  );
		} else {

			// theme styles
			$this->addStyle("css/reset.css",array(),"pe_theme_brandspace_reset");
			$this->addStyle("css/bootstrap/css/bootstrap.min.css",array(),"pe_theme_brandspace_bootstrap");
			$this->addStyle("css/bootstrap/css/bootstrap-responsive.min.css",array(),"pe_theme_brandspace_bootstrap_responsive");
			$this->addStyle("css/slider_captions.css",array(),"pe_theme_brandspace_slider_captions");
			$this->addStyle("css/slider_captions_style.css",array(),"pe_theme_brandspace_slider_captions_style");
			$this->addStyle("css/slider_ui.css",array(),"pe_theme_brandspace_slider_ui");
			$this->addStyle("css/style.css",array(),"pe_theme_brandspace_style");
			$this->addStyle("css/entypo-icon-font.css",array(),"pe_theme_brandspace_icon_font");

			$deps = 
				array(
					  "pe_theme_brandspace_reset",
					  "pe_theme_brandspace_bootstrap",
					  "pe_theme_brandspace_bootstrap_responsive",
					  "pe_theme_brandspace_icon_font",					  
					  "pe_theme_video",
					  "pe_theme_refineslide",
					  "pe_theme_volo",
					  "pe_theme_flare",
					  "pe_theme_prettify",
					  "pe_theme_brandspace_slider_ui",
					  "pe_theme_brandspace_slider_captions",
					  "pe_theme_brandspace_slider_captions_style",
					  "pe_theme_brandspace_style"
					  );
		}

		if ($options->skin == "dark") {
			$deps[] = "pe_theme_brandspace_dark_skin";
		}

		$this->addStyle("style.css",$deps,"pe_theme_init");

		//$this->addScript("css/bootstrap/js/bootstrap.min.js",array("jquery"),"pe_theme_brandspace_bootstrap");

		$this->addScript("theme/js/pe/pixelentity.controller.js",
						 array(
							   "pe_theme_selectnav",
							   "pe_theme_lazyload",
							   "pe_theme_effects_info",
							   "pe_theme_effects_bw",
							   "pe_theme_flare",

							   "pe_theme_widgets_bslinks",
							   "pe_theme_widgets_contact",
							   "pe_theme_widgets_bootstrap",
							   "pe_theme_widgets_galleryslider",
							   "pe_theme_widgets_twitter",
							   "pe_theme_widgets_flickr",
							   "pe_theme_widgets_newsletter",
							   "pe_theme_widgets_gmap",
							   "pe_theme_widgets_social_facebook",
							   "pe_theme_widgets_social_twitter",
							   "pe_theme_widgets_social_google"

							   ),"pe_theme_controller");

		/*
		wp_localize_script("pe_theme_init", 'peThemeOptions',
						   array(
								 "backgroundMinWidth" => absint($options->backgroundMinWidth)
								 ));
		*/

	}
	
	public function pe_theme_bootstrap_js_filter($js) {
		return "css/bootstrap/js/bootstrap.min.js";
	}


	public function pe_theme_minified_js_deps_filter($deps) {
		return array("jquery");
	}

	public function pe_theme_video_js_deps_filter($deps) {
		return array(
					 "pe_theme_utils_youtube",
					 "pe_theme_utils_vimeo",
					 );
	}

	public function pe_theme_flare_css_deps_filter($deps) {
		return 	array(
					  "pe_theme_flare_common"
					  );
	}
	

	public function style() {
		bloginfo("stylesheet_url"); 
	}

	public function enqueueAssets() {
		$this->registerAssets();
		
		if ($this->minifyJS && file_exists(PE_THEME_PATH."/preview/init.js")) {
			$this->addScript("preview/init.js",array("jquery"),"pe_theme_preview_init");
			wp_localize_script("pe_theme_preview_init", 'o',
							   array(
									 "dark" => PE_THEME_URL."/css/dark_skin.css",
									 "css" => $this->master->color->customCSS(true,"color1")
									 ));
			wp_enqueue_script("pe_theme_preview_init");
		}	
		
		wp_enqueue_style("pe_theme_init");
		wp_enqueue_script("pe_theme_init");

		if ($this->minifyJS && file_exists(PE_THEME_PATH."/preview/preview.js")) {
			$this->addScript("preview/preview.js",array("pe_theme_init"),"pe_theme_skin_chooser");
			wp_localize_script("pe_theme_skin_chooser","pe_skin_chooser",array("url"=>urlencode(PE_THEME_URL."/")));
			wp_enqueue_script("pe_theme_skin_chooser");
		}
	}


}

?>