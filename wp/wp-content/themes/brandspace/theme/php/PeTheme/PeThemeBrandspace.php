<?php

class PeThemeBrandspace extends PeThemeController {

	public $preview = array();

	public function __construct() {
		// custom post types
		add_action("pe_theme_custom_post_type",array(&$this,"pe_theme_custom_post_type"));

		// google fonts
		add_filter("pe_theme_font_variants",array(&$this,"pe_theme_font_variants_filter"),10,2);

		// menu
		add_filter("pe_theme_menu_items_wrap_default",array(&$this,"pe_theme_menu_items_wrap_default_filter"));
		add_filter("pe_theme_menu_top_level_icon",array(&$this,"pe_theme_menu_top_level_icon_filter"));
		add_filter("pe_theme_menu_nth_level_icon",array(&$this,"pe_theme_menu_top_level_icon_filter"));
		add_filter("pe_theme_menu_dropdown_menu_item_class",array(&$this,"pe_theme_menu_dropdown_menu_item_class_filter"));
		add_filter("pe_theme_menu_dropdown_menu_class",array(&$this,"pe_theme_menu_dropdown_menu_class_filter"));
		add_filter("pe_theme_menu_item_classes",array(&$this,"pe_theme_menu_item_classes_filter"),10,3);

		// social links
		add_filter("pe_theme_content_get_social_link",array(&$this,"pe_theme_content_get_social_link_filter"),10,4);

		// use prio 30 so gets executed after standard theme filter
		add_filter("the_content_more_link",array(&$this,"the_content_more_link_filter"),30);

		// gallery fields
		add_filter("pe_theme_gallery_image_fields",array(&$this,"pe_theme_gallery_image_fields_filter"));

		// slider type
		add_filter("pe_theme_slider_plugin",array(&$this,"pe_theme_slider_plugin_filter"));

		// footer
		add_filter("pe_theme_footer_layouts",array(&$this,"pe_theme_footer_layouts_filter"));

		// metaboxes
		add_filter("pe_theme_metabox_gallery",array(&$this,"pe_theme_metabox_gallery_filter"));
		add_filter("pe_theme_portfolio_layouts",array(&$this,"pe_theme_portfolio_layouts_filter"));
		add_filter("pe_theme_portfolio_default_layout",array(&$this,"pe_theme_portfolio_default_layout_filter"));

		// shortcodes default values
		add_filter("pe_theme_shortcode_gallery_defaults",array(&$this,"pe_theme_shortcode_gallery_defaults_filter"),10,2);
		
	}

	public function pe_theme_resized_img_filter($markup,$url,$w,$h) {
		// no lazy loading inside sliders
		if ($this->template->ancestor("slider")) {
			return $markup;
		}

		return sprintf('<img class="peLazyLoading" src="%s" data-original="%s" width="%s" height="%s" />',
					   $this->image->blank($w,$h),
					   $url,
					   $w,
					   $h
					   );
	}


	public function pe_theme_portfolio_layouts_filter($layouts) {
		return array(
					 __pe("2 Columns")=>2,
					 __pe("3 Columns")=>3,
					 __pe("4 Columns")=>4,
					 );
	}

	public function pe_theme_portfolio_default_layout_filter($def) {
		return 4;
	}


	public function pe_theme_footer_layouts_filter($layouts) {
		return array("default" => array("span6","span6"));
	}

	public function pe_theme_slider_plugin_filter($plugin) {
		return "peVolo";
		return ($this->template->ancestor("gallery") ? "peRefineSlide" : $plugin); 
	}

	
	public function pe_theme_font_variants_filter($variants,$font) {
		if ($font === "Open Sans") {
			$variants = "Open Sans:400italic,400,300,700";
		}
		return $variants;
	}

	public function pe_theme_menu_items_wrap_default_filter($wrapper) {
		return '<ul id="navigation" class="main-nav">%3$s</ul>';
	}

	public function pe_theme_menu_top_level_icon_filter($wrapper) {
		return '';
	}

	public function pe_theme_menu_dropdown_menu_item_class_filter($cl) {
		return '';
	}

	public function pe_theme_menu_dropdown_menu_class_filter($cl) {
		return 'subMenu';
	}

	public function pe_theme_menu_item_classes_filter($classes,$item,$depth) {
		if ($depth === 0) {
			$classes[] = "menu span3";
		}
		return $classes;
	}

	public function pe_theme_content_get_social_link_filter($html,$link,$domain,$icon) {
		return sprintf('<li><a class="sm-icon sm-icon-%s" href="%s"></a></li>',$icon,$link);
	}

	public function the_content_more_link_filter($link) {
		return sprintf('<a class="hand-written read-more" href="%s#more-%s">%s</a>',get_permalink(),$GLOBALS["post"]->ID,__pe("read more"));
	}

	public function pe_theme_gallery_image_fields_filter($fields) {
		$save = $fields["save"];
		unset($fields["save"]);
		unset($fields["video"]);
		unset($fields["ititle"]);
		$fields["link"] = 
			array(
				  "label"=>__pe("Link"),
				  "type"=>"Text",
				  "section"=>"main",
				  "description" => __pe("Optional image link."),
				  "default"=> ""
				  );
		$fields["save"] = $save;
		return $fields;
	}

	public function pe_theme_metabox_gallery_filter($mboxes) {
		$type =& $mboxes["settings"]["content"]["type"];
		$type["options"][__pe("Thumbnails + Slider")] = "slider";
		$type["default"] = "slider";
		return $mboxes;
	}


	public function pe_theme_shortcode_gallery_defaults_filter($defaults,$atts) {
		extract(shortcode_atts(array("id" => false),$atts));

		switch ($this->gallery->type($id)) {
		case "slider":
			$defaults["size"] = "560x353";
			break;
		}

		return $defaults;
	}


	public function init() {
		parent::init();

		if ($this->options->get("lazyImages") === "yes") {
			add_filter("pe_theme_resized_img",array(&$this,"pe_theme_resized_img_filter"),10,4);
		}
	}


	public function pe_theme_custom_post_type() {
		$this->gallery->cpt();
		$this->video->cpt();
		$this->project->cpt();
		$this->testimonial->cpt();
	}


	public function boot() {
		parent::boot();

		PeGlobal::$config["content-width"] = 940;
		PeGlobal::$config["post-formats"] = array("video","gallery");
		PeGlobal::$config["post-formats-project"] = array("video","gallery");

		PeGlobal::$config["image-sizes"]["thumbnail"] = array(120,90,true);
		PeGlobal::$config["image-sizes"]["medium"] = array(480,396,true);
		PeGlobal::$config["image-sizes"]["large"] = array(680,224,true);
		PeGlobal::$config["image-sizes"]["post-thumbnail"] = PeGlobal::$config["image-sizes"]["medium"];
		

		PeGlobal::$config["nav-menus"]["footer"] = __pe("Footer menu");

		// blog layouts
		PeGlobal::$config["blog"] =
			array(
				  __pe("Default") => "",
				  );

		PeGlobal::$config["widgets"] = 
			array(
				  "Project",
				  "BrandspaceContacts",
				  "Twitter",
				  "RecentPosts",
				  );

		PeGlobal::$config["shortcodes"] = 
			array(
				  "BrandspaceBS_HandWritten",
				  "BS_Tooltip",
				  "BS_Hero",
				  "BS_ContentBox",
				  "BS_Badge",
				  "BS_Label",
				  "BS_Button",
				  "BS_Alert",
				  "BS_Faq",
				  "BS_Portfolio",
				  "BrandspaceBS_FeaturedProject",
				  "BrandspaceBS_Projects",
				  "BS_Gallery",
				  "BS_Slider",
				  "BS_Video",
				  "BS_Blog",
				  "BS_Accordion",
				  "BS_Tabs",
				  "BS_Columns"
				  );

		PeGlobal::$config["sidebars"] =
			array(
				  "footer" => __pe("Footer Widgets"),
				  "default" => __pe("Default post/page")
				  );

		PeGlobal::$config["colors"] = 
			array(
				  "color1" => 
				  array(
						"label" => __pe("Primary Color"),
						"selectors" => 
						array(
							  "p.wp-caption-text.hand-written" => "color",
							  ".hand-written" => "color",
							  "a" => "color",
							  ".accent" => "color",
							  ".subMenu a:hover" => "color",
							  ".subMenu a.selected" => "color",
							  ".feat-left .caption span" => "color",
							  ".feat-right .caption span" => "color",
							  ".widget_twitter .followBtn:hover .label" => "color",
							  ".widget_tag_cloud a:hover" => "background-color",
							  ".widget.widget_tag_cloud a:hover" => "border-color",
							  ".note-marker" => "color",
							  ".sidebar li i" => "color",
							  ".sidebar li a:hover" => "color",
							  ".widget_featured .caption i" => "color",
							  ".widget_featured .caption:hover" => "color",
							  ".post .category a:hover" => "color",
							  ".post .comments a:hover" => "color",
							  ".contentBox" => "background-color",
							  ".bypostauthor > .comment-body > .comment-author img" => "border-color",
							  ".bypostauthor > .comment-body cite" => "color",
							  ".bypostauthor #comments .fn a" => "color",
							  "#comments .reply .label:hover" => "background-color",
							  "#commentform .btn" => "background-color",
							  ".widget_contact a" => "color",
							  ".widget_contact a:hover" => "color",
							  ".feat-project-viewer .image-browser a:hover" => "outline-color",
							  ".category-thumbs h3 a:hover" => "color",
							  ".peSlider > div.peCaption h3" => "color",
							  ".widget_info a" => "color",
							  ".newsletter button.btn" => "background-color",
							  ".peThemeContactForm button.btn" => "background-color",
							  ".subMenu .current-menu-item a" => "color"
							  ),
						"default" => "#00a6d5"
						),
				  "color2" => 
				  array(
						"label" => __pe("Dark Grey"),
						"selectors" => 
						array(
							  "h1" => "color",
							  "h2" => "color",
							  "aside h3" => "color",
							  "footer h3" => "color",
							  ".sidebar h3" => "color",
							  ".tagline h4" => "color",
							  ".post-title a" => "color",
							  ".post .post-meta i" => "color",
							  "#contactForm label" => "color",
							  ".faq li" => "color",
							  ".cat-info .tags a:hover" => "color",
							  ".project .project-meta .tags a:hover" => "color",
							  ".project-nav a i" => "color"
							  ),
						"default" => "#444444"
						),
				  "color3" => 
				  array(
						"label" => __pe("Medium Grey"),
						"selectors" => 
						array(
							  "body" => "color",
							  "h3" => "color",
							  "h4" => "color",
							  "p" => "color",
							  "li" => "color",
							  ".feat-left .caption" => "color",
							  ".feat-right .caption" => "color",
							  ".widget_info .phone" => "color",
							  ".widget_calendar caption" => "color",
							  ".widget_calendar tfoot td a" => "color",
							  ".widget_nav_menu a:hover" => "color",
							  ".testimonials p" => "color",
							  ".sidebar h3" => "color",
							  ".sidebar li a" => "color",
							  ".widget_featured .caption" => "color",
							  ".compact .post-meta i" => "color",
							  "#comments-title span" => "color",
							  ".bypostauthor > .comment-body p" => "color",
							  "#comments .fn a" => "color",
							  "#comments .comment-meta a:hover" => "color",
							  "#comments .pagination .active a" => "border-color",
							  "#comments .pagination .active a" => "background-color",
							  "#commentform label" => "color",
							  ".faq-heading:hover > div" => "color",
							  "h.category-thumbs h3 a" => "color",
							  ".project .project-meta i" => "color",
							  ".project-nav a" => "color"
							  ),
						"default" => "#666666"
						),
				  "color4" => 
				  array(
						"label" => __pe("Light Grey"),
						"selectors" => 
						array(
							  "h6" => "color",
							  "small" => "color",
							  ".post li" => "color",
							  ".stat-sidebar h3" => "color",
							  ".newsletter input[type=text]" => "color",
							  ".widget_info p" => "color",
							  ".widget_info span" => "color",
							  "footer .widget_twitter h3" => "color",
							  "footer .widget_recent_entries h3" => "color",
							  ".comments-num" => "color",
							  ".widget_archive li" => "color",
							  ".widget_links li a" => "color",
							  ".widget_pages li a" => "color",
							  ".widget_meta li a" => "color",
							  ".widget_nav_menu li a" => "color",
							  ".widget_recent_entries li a" => "color",
							  ".widget_recent_comments li a" => "color",
							  ".widget_calendar #wp-calendar" => "color",
							  ".widget_tag_cloud a" => "color",
							  ".post .user" => "color",
							  ".post .date" => "color",
							  ".post.comments" => "color",
							  ".post .category" => "color",
							  ".post .category a" => "color",
							  ".post .comments a" => "color",
							  ".compact .user" => "color",
							  ".compact .date" => "color",
							  ".compact .comments" => "color",
							  ".compact .category" => "color",
							  ".post .tags a" => "color",
							  ".widget_contact p" => "color",
							  "span.help-inline" => "color"
							  ),
						"default" => "#999999"
						),
				  "color5" => 
				  array(
						"label" => __pe("Linework"),
						"selectors" => 
						array(
							  ".header-content" => "border-color",
							  ".logo" => "border-color",
							  ".tagline.border-bottom" => "border-color",
							  ".upper" => "border-color",
							  ".connect" => "border-color",
							  "footer" => "border-color",
							  ".widget_twitter span" => "color",
							  ".widget_recent_entries span" => "color",
							  ".widget_nav_menu a" => "border-color",
							  ".sidebar li span" => "color",
							  ".widget_categories ul" => "border-color",
							  ".widget_categories li" => "color",
							  ".post.compact" => "border-color",
							  "#comments .reply .label" => "background-color",
							  ".feat-info" => "border-color",
							  ".cat-wrap" => "border-color",
							  ".cat-info .tags a" => "color",
							  ".project .project-meta .tags" => "color",
							  ".project .project-meta .tags a" => "color",
							  ".project-nav a.disabled" => "color",
							  ".project-nav a.disabled i" => "color",
							  "input:-moz-placeholder" => "color",
							  "textarea:-moz-placeholder" => "color",
							  "input::-webkit-input-placeholder" => "color",
							  "textarea::-webkit-input-placeholder" => "color",
							  "input:-ms-input-placeholder" => "color",
							  "textarea:-ms-input-placeholder" => "color",
							  ".post.compact" => "border-color"
							  ),
						"default" => "#cccccc"
						),
				  "color6" => 
				  array(
						"label" => __pe("Light Backgrounds"),
						"selectors" => 
						array(
							  ".stat-sidebar" => "background-color",
							  ".widget_info" => "background-color",
							  "footer .widget_twitter h3" => "background-color",
							  "footer .widget_recent_entries h3" => "background-color",
							  ".widget_calendar th" => "background-color",
							  ".widget_calendar tbody td a" => "background-color",
							  ".widget_calendar tfoot td a:hover" => "background-color",
							  ".sidebar" => "background-color",
							  ".widget_search input[type=text]" => "border-color",
							  ".faq-heading" => "background-color",
							  ".nav-tabs > li > a:hover" => "background-color",
							  ".accordion-heading" => "background-color",
							  ".project-nav a" => "background-color",
							  ".project-nav a.disabled:hover" => "background-color"
							  ),
						"default" => "#f6f6f6"
						)
				  );
		

		PeGlobal::$config["fonts"] = 
			array(
				  "fontHeading" => 
				  array(
						"label" => __pe("Heading Text"),
						"selectors" => 
						array(
							  "h1",
							  "h2",
							  "h3",
							  "h4",
							  "h5",
							  "h6"
							  ),
						"default" => "Open Sans"
						),
				  "fontBody" => 
				  array(
						"label" => __pe("Body, Paragraph Text"),
						"selectors" => 
						array(
							  "body",
							  "p",
							  "input",
							  "select",
							  "textarea"
							  ),
						"default" => "Open Sans"
						),
				  "fontHW" => 
				  array(
						"label" => __pe("Hand Written"),
						"selectors" => 
						array(
							  ".hand-written"
							  ),
						"default" => "Covered By Your Grace"
						)
				  );

		

		$options = array();

		if (!defined('PE_HIDE_IMPORT_DEMO') || !PE_HIDE_IMPORT_DEMO) {
			$options["import_demo"] = $this->defaultOptions["import_demo"];
		}

		$options = array_merge($options,
			array(
				  "skin" => 
				  array(
						"label"=>__pe("Skin"),
						"type"=>"RadioUI",
						"section"=>__pe("General"),
						"description" => __pe("Select Theme Skin"),
						"options" => array("light","dark"),
						"single" => true,
						"default"=>"light"
						),
				  "logo" => 
				  array(
						"label"=>__pe("Logo"),
						"type"=>"Upload",
						"section"=>__pe("General"),
						"description" => __pe("This is the main site logo image. The image should be a .png file."),
						"default"=> PE_THEME_URL."/img/skin/logo1.png"
						),
				  "favicon" => 
				  array(
						"label"=>__pe("Favicon"),
						"type"=>"Upload",
						"section"=>__pe("General"),
						"description" => __pe("This is the favicon for your site. The image can be a .jpg, .ico or .png with dimensions of 16x16px "),
						"default"=> PE_THEME_URL."/favicon.jpg"
						),
				  "customCSS" => $this->defaultOptions["customCSS"],
				  "customJS" => $this->defaultOptions["customJS"],
				  "taglineShow" =>				
				  array(
						"label"=>__pe("Show"),
						"type"=>"RadioUI",
						"section" => __pe("Tagline"),
						"description"=>__pe('Whether to show the tagline area or not.'),
						"options" => Array(__pe("Yes")=>"yes",__pe("No")=>"no"),
						"default"=>"yes"
						),
				  "taglineContent" => 
				  array(
						"label"=>__pe("Content"),
						"type"=>"TextArea",
						"section"=>__pe("Tagline"),
						"description" => __pe("Default content of tagline area."),
						"wpml" => true,
						"default"=> __pe('<h4>Hi there and welcome to <span class="accent">Brandspace</span>, a new minimal multi-purpose WordPress Theme from <a href="#">Pixelentity</a>. As with all of our themes, Brandspace is speedy, lightweight and bursting with the latest tech.</h4>')
						),
				  "customColors" => 
				  array(
						"label"=>__pe("Custom Colors"),
						"type"=>"Help",
						"section"=>__pe("Colors"),
						"description" => __pe("In this page you can set alternative colors for the main colored elements in this theme. Four color options have been provided. A primary color, a secondary or complimentary color, a primary or dark grey and a secondary or light grey. To change the colors used on these elements simply write a new hex color reference number into the fields below or use the color picker which appears when each field obtains focus. Once you have selected your desired colors make sure to save them by clicking the <b>Save All Changes</b> button at the bottom of the page. Then just refresh your page to see the changes.<br/><br/><b>Please Note:</b> Some of the elements in this theme are made from images (Eg. Icons) and these items may have a color. It is not possible to change these elements via this page, instead such elements will need to be changed manually by opening the images/icons in an image editing program and manually changing their colors to match your theme's custom color scheme. <br/><br/>To return all colors to their default values at any time just hit the <b>Restore Default</b> link beneath each field."),
						),
				  "googleFonts" =>
				  array(
						"label"=>__pe("Custom Fonts"),
						"type"=>"Help",
						"section"=>__pe("Fonts"),
						"description" => __pe("In this page you can set the typefaces to be used throughout the theme. For each elements listed below you can choose any front from the Google Web Font library. Once you have chosen a font from the list, you will see a preview of this font immediately beneath the list box. The icons on the right hand side of the font preview, indicate what weights are available for that typeface.<br/><br/><strong>R</strong> -- Regular,<br/><strong>B</strong> -- Bold,<br/><strong>I</strong> -- Italics,<br/><strong>BI</strong> -- Bold Italics<br/><br/>When decideing what font to use, ensure that the chosen font contains the font weight required by the element. For example, main headings are bold, so you need to select a new font for these elements which supports a bold font weight. If you select a font which does not have a bold icon, the font will not be applied. <br/><br/>Browse the online <a href='http://www.google.com/webfonts'>Google Font Library</a><br/><br/><b>Custom fonts</b> (Advanced Users):<br/> Other then those available from Google fonts, custom fonts may also be applied to the elements listed below. To do this an additional field is provided below the google fonts list. Here you may enter the details of a font family, size, line-height etc. for a custom font. This information is entered in the form of the shorthand 'font:' CSS declaration, for example:<br/><br/><b>bold italic small-caps 1em/1.5em arial,sans-serif</b><br/><br/>If a font is specified in this field then the font listed in the Google font drop menu above will not be applied to the element in question. If you wish to use the Google font specified in the drop down list and just specify a new font size or line height, you can do so in this field also, however the name of the Google font <b>MUST</b> also be entered into this field. You may need to visit the Google fonts web page to find the exact CSS name for the font you have chosen." )
						),
				  "footerExtra" =>				
				  array(
						"label"=>__pe("Show Extra Section"),
						"type"=>"RadioUI",
						"section" => __pe("Footer"),
						"description"=>__pe('Whether to show the extra footer section (social links/newsletter) or not.'),
						"options" => Array(__pe("Yes")=>"yes",__pe("No")=>"no"),
						"default"=>"yes"
						),
				  "newsletter" => 
				  array(
						"label"=>__pe("Newsletter"),
						"type"=>"Text",
						"section"=>__pe("Footer"),
						"description" => __pe("Newsletter subscribe mail address, leave empty to disable newsletter block."),
						"default"=>""
						),
				  "footerSocialLinks" => 
				  array(
						"label"=>__pe("Social Profile Links"),
						"type"=>"Links",
						"section"=>__pe("Footer"),
						"description" => __pe("Add the link to your common social media profiles. Paste links one at a time and click the 'Add New' button. The links will appear in a table below and an icons will be inserted automatically based on the domain in the url."),
						"sortable" => true,
						"default"=>""
						),
				  "footerLogo" => 
				  array(
						"label"=>__pe("Logo"),
						"type"=>"Upload",
						"section"=>__pe("Footer"),
						"description" => __pe("This is the site footer logo image."),
						"default"=> PE_THEME_URL."/img/skin/logo_foot1.png"
						),
				  "footerInfo" => 
				  array(
						"label"=>__pe("Info"),
						"type"=>"Textarea",
						"section"=>__pe("Footer"),
						"description" => __pe("Footer info block."),
						"wpml" => true,
						"default"=> sprintf('<p>Lorem ipsum dolor sit amet.</p>%s<p>Quick contact <a href="#">hello@emailaddress.com</a></p>%s<span class="phone">+353 (0) 123 456 78</span>%s<span>Brandspace &copy; by</span>%s<a class="hand-written" href="http://themeforest.net/user/pixelentity">pixelentity</a>',"\n","\n","\n","\n")
						),
				  "contactEmail" => $this->defaultOptions["contactEmail"],
				  "contactSubject" => $this->defaultOptions["contactSubject"],
				  "sidebars" => 
				  array(
						"label"=>__pe("Widget Areas"),
						"type"=>"Sidebars",
						"section"=>__pe("Widget Areas"),
						"description" => __pe("Create new widget areas by entering the area name and clicking the add button. The new widget area will appear in the table below. Once a widget area has been created, widgets may be added via the widgets page."),
						"default"=>""
						),
				  "404content" => 
				  array(
						"label"=>__pe("Content"),
						"type"=>"TextArea",
						"section"=>__pe("Custom 404"),
						"description" => __pe("Content of 404 (not found) page"),
						"wpml" => true,
						"default"=> '<strong>
The Page You Are Looking For Cannot Be Found
</strong>
<p>
You may want to check the following links:
</p>
<a href="#" class="btn btn-danger">
Home
</a>
<a href="#" class="btn btn-danger">
Contact
</a>
'
						)

				  ));

		$options = array_merge($options,$this->color->options());
		$options = array_merge($options,$this->font->options());			

		$options["lazyImages"] =& $this->defaultOptions["lazyImages"];
		$options["minifyJS"] =& $this->defaultOptions["minifyJS"];
		$options["minifyCSS"] =& $this->defaultOptions["minifyCSS"];

		$options["adminThumbs"] =& $this->defaultOptions["adminThumbs"];
		$options["mediaQuick"] =& $this->defaultOptions["mediaQuick"];
		$options["mediaQuickDefault"] =& $this->defaultOptions["mediaQuickDefault"];

		$options["updateCheck"] =& $this->defaultOptions["updateCheck"];
		$options["updateUsername"] =& $this->defaultOptions["updateUsername"];
		$options["updateAPIKey"] =& $this->defaultOptions["updateAPIKey"];

		$options["adminLogo"] =& $this->defaultOptions["adminLogo"];
		$options["adminUrl"] =& $this->defaultOptions["adminUrl"];
		
		PeGlobal::$config["options"] =& apply_filters("pe_theme_options",$options);

		$subtitleMbox = 
			array(
				  "type" =>"",
				  "title" =>__pe("Subtitle"),
				  "priority" => "core",
				  "where" => 
				  array(
						"post" => "all",
						),
				  "content"=>
				  array(
						"content" =>    
						array(
							  "label"=>__pe("Subtitle"),
							  "type"=>"Text",
							  "description" => __pe("Optional subtitle, leave empty to hide."),
							  "default"=>""
							  )
						)
				  );

		$taglineMbox = 
			array(
				  "type" =>"",
				  "title" =>__pe("Tagline"),
				  "priority" => "core",
				  "where" => 
				  array(
						"post" => "all",
						),
				  "content"=>
				  array(
						"show" =>				
						array(
							  "label"=>__pe("Show"),
							  "type"=>"RadioUI",
							  "description"=>__pe('Whether to show the tagline area or not.'),
							  "options" => Array(__pe("Yes")=>"yes",__pe("No")=>"no"),
							  "default"=>"yes"
							  ),
						"tagline" =>    
						array(
							  "label"=>__pe("Tagline"),
							  "type"=>"TextArea",
							  "description" => __pe("Optional custom tagline message, leave empty to use default."),
							  "default"=>""
							  ),
						"border" =>				
						array(
							  "label"=>__pe("Bottom Border"),
							  "type"=>"RadioUI",
							  "description"=>__pe('Whether to add a bottom border to the tagline area or not.'),
							  "options" => Array(__pe("Yes")=>"yes",__pe("No")=>"no"),
							  "default"=>"no"
							  )
						
						)
				  );
		
		$galleryMbox = 
			array(
				  "title" => __pe("Slider Options"),
				  "type" => "GalleryPost",
				  "priority" => "core",
				  "where" =>
				  array(
						"post" => "gallery"
						),
				  "content" =>
				  array(
						"id" => PeGlobal::$const->gallery->id,
						"width" =>
						array(
							  "label"=>__pe("Width"),
							  "type"=>"Text",
							  "description" => __pe("Leave empty to use default width."),
							  "default"=> ""
							  ),
						"height" =>
						array(
							  "label"=>__pe("Height"),
							  "type"=>"Text",
							  "description" => __pe("Leave empty to avoid image cropping. In this case, all your (original) images must have the same size for the slider to work correctly"),
							  "default"=> "350"
							  ),
						)
				  );

		$projectMbox = 
			array(
				  "type" =>"",
				  "title" =>__pe("Project"),
				  "priority" => "core",
				  "where" => 
				  array(
						"post" => "all",
						),
				  "content"=>
				  array(
						"client" =>    
						array(
							  "label"=>__pe("Client"),
							  "type"=>"Text",
							  "description" => __pe("Optional client name."),
							  "default"=> __pe("Client Name")
							  ),
						"date" =>    
						array(
							  "label"=>__pe("Date"),
							  "type"=>"Text",
							  "description" => __pe("Optional date/period."),
							  "default"=> __pe("ongoing - Jun 2011")
							  ),
						"label" => 	
						array(
							  "label"=>__pe("Link Label"),
							  "type"=>"Text",
							  "description" => __pe("Optional link label, leave empty to hide."),
							  "default"=>__pe("Visit Site")
							  ),
						"link" => 	
						array(
							  "label"=>__pe("Link Url"),
							  "type"=>"Text",
							  "description" => __pe("Optional link url, leave empty to hide."),
							  "default"=>"#"
							  ),
						)
				  );

		$testimonialsMbox =
			array(
				  "type" =>"",
				  "title" =>__pe("Testimonials"),
				  "priority" => "core",
				  "where" => 
				  array(
						"page" => "page-testimonials",
						),
				  "content"=>
				  array(
						"items" => 
						array(
							  "label"=>__pe("Testimonials"),
							  "type"=>"Links",
							  "description" => __pe("Add one or more testimonial."),
							  "sortable" => true,
							  "options"=> $this->testimonial->option()
							  )
						)
				  
				  );

		$logosMbox = 
			array(
				  "type" =>"",
				  "title" =>__pe("Sidebar Logos"),
				  "priority" => "core",
				  "where" => 
				  array(
						"page" => "page-home",
						),
				  "content"=>
				  array(
						"title" => 	
						array(
							  "label"=>__pe("Title"),
							  "type"=>"Text",
							  "default"=>__pe("Why Choose Us ?")
							  ),
						"logo1" => 
						array(
							  "label"=>__pe("Logo 1"),
							  "type"=>"Upload",
							  "default"=> PE_THEME_URL."/img/home/stat_01.png"
							  ),
						"link1" => 
						array(
							  "label"=>__pe("Link 1"),
							  "type"=>"Text",
							  "default"=> "#"
							  ),
						"text1" => 
						array(
							  "label"=>__pe("Text 1"),
							  "type"=>"Text",
							  "default"=> "The number of happy clients world wide since we began in 2006"
							  ),
						"logo2" => 
						array(
							  "label"=>__pe("Logo 2"),
							  "type"=>"Upload",
							  "default"=> PE_THEME_URL."/img/home/stat_02.png"
							  ),
						"link2" => 
						array(
							  "label"=>__pe("Link 2"),
							  "type"=>"Text",
							  "default"=> "#"
							  ),
						"text2" => 
						array(
							  "label"=>__pe("Text 2"),
							  "type"=>"Text",
							  "default"=> "The number of awards won by our studio in 2010"
							  ),
						"logo3" => 
						array(
							  "label"=>__pe("Logo 3"),
							  "type"=>"Upload",
							  "default"=> PE_THEME_URL."/img/home/stat_03.png"
							  ),
						"link3" => 
						array(
							  "label"=>__pe("Link 3"),
							  "type"=>"Text",
							  "default"=> "#"
							  ),
						"text3" => 
						array(
							  "label"=>__pe("Text 3"),
							  "type"=>"Text",
							  "default"=> "A small team means lower costs for us and higher value for you"
							  ),
						"logo4" => 
						array(
							  "label"=>__pe("Logo 4"),
							  "type"=>"Upload",
							  "default"=> PE_THEME_URL."/img/home/stat_04.png"
							  ),
						"link4" => 
						array(
							  "label"=>__pe("Link 4"),
							  "type"=>"Text",
							  "default"=> "#"
							  ),
						"text4" => 
						array(
							  "label"=>__pe("Text 4"),
							  "type"=>"Text",
							  "default"=> "Client satisfaction guaranteed"
							  )
						)
				  );


		$homeProjectsMbox =
			array(
				  "type" =>"",
				  "title" =>__pe("Latest Work"),
				  "priority" => "core",
				  "where" => 
				  array(
						"page" => "page-home",
						),
				  "content"=>
				  array(
						"title" => 	
						array(
							  "label"=>__pe("Title"),
							  "type"=>"Text",
							  "default"=>__pe("Latest Work")
							  ),
						"label" => 	
						array(
							  "label"=>__pe("Link Label"),
							  "type"=>"Text",
							  "description" => __pe("Optional link label, leave empty to hide."),
							  "default"=>__pe("Check It Out")
							  ),
						"link" => 	
						array(
							  "label"=>__pe("Link Url"),
							  "type"=>"Text",
							  "description" => __pe("Optional link url, leave empty to hide."),
							  "default"=>"#"
							  ),
						"items" => 
						array(
							  "label"=>__pe("Projects"),
							  "type"=>"Links",
							  "description" => __pe("Add one or more projects."),
							  "sortable" => true,
							  "options"=> $this->project->option()
							  )
						)
				  
				  );

		$notesMbox =
			array(
				  "type" =>"",
				  "title" =>__pe("Sidebar Notes"),
				  "priority" => "core",
				  "where" => 
				  array(
						"page" => "page-notes",
						),
				  "content"=>
				  array(
						"items" => 
						array(
							  "label"=>__pe("Notes"),
							  "type"=>"Links",
							  "description" => __pe("Add one or notes."),
							  "sortable" => true,
							  "unique" => false
							  )
						)
				  
				  );


		$contactMbox = 
			array(
				  "type" =>"",
				  "title" =>__pe("Contact Options"),
				  "priority" => "core",
				  "where" => 
				  array(
						"page" => "page-contact",
						),
				  "content"=>
				  array(
						"caption" => 
						array(
							  "label"=>__pe("Image Caption"),
							  "type"=>"TextArea",
							  "description" => __pe("Optional image caption, will be shown on top of the featured image. If no featured image, the whole block will be hidden."),
							  "default"=> '<span>#07</span>
<h3>Part Avenue, <em>New York Office</em></h3>'
							  ),
						"quote" =>				
						array(
							  "label"=>__pe("Quotation Request"),
							  "type"=>"RadioUI",
							  "description"=>__pe('Whether to show the quotation request area or not.'),
							  "options" => Array(__pe("Yes")=>"yes",__pe("No")=>"no"),
							  "default"=>"yes"
							  ),
						"quoteSelectBox" => 
						array(
							  "label"=>__pe("Quotation Select Box"),
							  "type"=>"TextArea",
							  "description" => __pe("Text content of the quotation select box."),
							  "default"=> '<h1>
The Nature of Your Enquiry? 
<span class="hand-written sub-heading">general or quotation</span>
</h1>
'
							  ),
						"personalBox" => 
						array(
							  "label"=>__pe("Personal Box"),
							  "type"=>"TextArea",
							  "description" => __pe("Text content of the personal box."),
							  "default"=> '<h1>
Your Personal Details
<span class="hand-written sub-heading">tell us about youself</span>
</h1>
'
							  ),
						"quoteBox" => 
						array(
							  "label"=>__pe("Quotation Box"),
							  "type"=>"TextArea",
							  "description" => __pe("Text content of the quotation box."),
							  "default"=> '<h1>
Quotation Request Details
<span class="hand-written sub-heading">tell us what you need</span>
</h1>
'
							  ),
						"services" => 
						array(
							  "label"=>__pe("Service Options"),
							  "type"=>"Links",
							  "description" => __pe("Add one or more service option."),
							  "sortable" => true,
							  "unique" => false,
							  "default" => array(
												 "Template Customisation",
												 "PSD to HTML",
												 "Wordpress Theme",
												 "Wordpress Installation",
												 "Flash Website",
												 "PSD Website Layout Design",
												 "CSS and HTML Website Development"
												 )
							  ),
						"budgets" => 
						array(
							  "label"=>__pe("Budget Options"),
							  "type"=>"Links",
							  "description" => __pe("Add one or more budget option."),
							  "sortable" => true,
							  "unique" => false,
							  "default" => array(
												 "< $500",
												 "< $2000",
												 "> $2000",
												 "Unsure"
												 )
							  ),
						"messageBox" => 
						array(
							  "label"=>__pe("Message Box"),
							  "type"=>"TextArea",
							  "description" => __pe("Text content of the message box."),
							  "default"=> '<h1>
Message Details
<span class="hand-written sub-heading">give us the low down</span>
</h1>
'
							  ),
						"msgOK" => 	
						array(
							  "label"=>__pe("Mail Sent Message"),
							  "type"=>"TextArea",
							  "description" => __pe("Message shown when form message has been sent without errors"),
							  "default"=>'<strong>Your Message Has Been Sent!</strong> Thank you for contacting us.'
							  ),
						"msgKO" => 	
						array(
							  "label"=>__pe("Form Error Message"),
							  "type"=>"TextArea",
							  "description" => __pe("Message shown when form message encountered errors"),
							  "default"=>'<strong>Oops, An error has occured!</strong> See the marked fields above to fix the errors.'
							  )
						)
				  );

		$relatedProjectsMbox =
			array(
				  "type" =>"",
				  "title" =>__pe("Related Projects"),
				  "priority" => "core",
				  "where" => 
				  array(
						"post" => "all",
						),
				  "content"=>
				  array(
						"items" => 
						array(
							  "label"=>__pe("Projects"),
							  "type"=>"Links",
							  "description" => __pe("Add one or more related projects."),
							  "sortable" => true,
							  "options"=> $this->project->option()
							  )
						)
				  
				  );


		$portfolioMbox =& PeGlobal::$const->portfolio->metabox;
		unset($portfolioMbox["content"]["filterable"]);

		PeGlobal::$config["metaboxes-post"] = 
			array(
				  "tagline" => $taglineMbox,
				  "subtitle" => $subtitleMbox,
				  "video" => PeGlobal::$const->video->metaboxPost,
				  "sidebar" => PeGlobal::$const->sidebar->metabox,
				  "footer" => PeGlobal::$const->sidebar->metaboxFooter,
				  "gallery" => $galleryMbox
				  );

		PeGlobal::$config["metaboxes-page"] = 
			array(
				  "tagline" => $taglineMbox,
				  "gallery" => array_merge($galleryMbox,array("where"=>array("post"=>"page-home, page-slider"))),
				  "sidebar" => array_merge(PeGlobal::$const->sidebar->metabox,array("where"=>array("post"=>"default, page-blog, page-right, page-contact"))),
				  "blog" => array_merge(PeGlobal::$const->blog->metabox,array("where"=>array("post"=>"page-blog"))),
				  "footer" => PeGlobal::$const->sidebar->metaboxFooter,
				  "logos" => $logosMbox,
				  "portfolio" => $portfolioMbox,
				  "testimonials" => $testimonialsMbox,
				  "projects" => $homeProjectsMbox,
				  "notes" => $notesMbox,
				  "contact" => $contactMbox,
				  "gmap" => PeGlobal::$const->gmap->metabox,
				  );


		PeGlobal::$config["metaboxes-project"] = 
			array(
				  "tagline" => $taglineMbox,
				  "subtitle" => $subtitleMbox,
				  "project" => $projectMbox,
				  "footer" => PeGlobal::$const->sidebar->metaboxFooter,
				  "gallery" => $galleryMbox,
				  "video" => PeGlobal::$const->video->metaboxPost
				  //"related" => $relatedProjectsMbox
				  );

	}

	public function pre_get_posts_filter($query) {
		if ($query->is_search) {
			$query->set('post_type',array('post'));
		}

		if (is_tax("prj-category") && !empty($query->query_vars["prj-category"])) {
			$query->set('posts_per_page',16);
		}

		return $query;
	}

	protected function init_asset() {
		return new PeThemeBrandspaceAsset($this);
	}


}

?>
