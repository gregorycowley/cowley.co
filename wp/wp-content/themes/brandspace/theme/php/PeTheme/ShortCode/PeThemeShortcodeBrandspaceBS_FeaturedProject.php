<?php

class PeThemeShortcodeBrandspaceBS_FeaturedProject extends PeThemeShortcode {
	
	public $instances = 0;
	public $count;
	public $custom;

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "featured";
		$this->group = __pe("CONTENT");
		$this->name = __pe("Featured Project");
		$this->description = __pe("Featured Project");
		$this->fields = array(
							  "id" => 
							  array(
									"label"=>__pe("Project"),
									"type"=>"Select",
									"description" => __pe("Select the project you wish to be featured."),
									"options" => peTheme()->project->option()
									)
							  );

		peTheme()->shortcode->blockLevel[] = $this->trigger;

	}

	
	public function output($atts,$content=null,$code="") {

		$defaults = apply_filters("pe_theme_shortcode_featured_project_defaults",array('id'=>""),$atts);
		extract(shortcode_atts($defaults,$atts));

		if (!$id) return "";

		$t =& peTheme();
		$content = "";

		if ($loop =& $t->content->customLoop("project",1,null,array("post__in" => array($id), "orderby" => "post__in"),false)) {

			ob_start();
			$t->template->data($loop);
			$t->get_template_part("shortcode","featured");
			$content =& ob_get_clean();
			$t->content->resetLoop();

		}

		return $content;

	}


}

?>
