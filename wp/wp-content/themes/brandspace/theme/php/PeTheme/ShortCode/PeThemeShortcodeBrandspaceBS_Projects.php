<?php

class PeThemeShortcodeBrandspaceBS_Projects extends PeThemeShortcode {
	
	public $instances = 0;
	public $count;
	public $custom;

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "projects";
		$this->group = __pe("CONTENT");
		$this->name = __pe("Projects");
		$this->description = __pe("Projects");
		$this->fields = array(
							  "count" =>
							  array(
									"label" => __pe("Max Projects"),
									"type" => "RadioUI",
									"single" => true,
									"description" => __pe("Maximum number of projects to display."),
									"options" => array(3,6,9,12,15,18,21,24,27,30),
									"default" => 3
									),
							  "tag" =>
							  array(
									"label" => __pe("Project Tag"),
									"type" => "Select",
									"description" => __pe("Only display projects from a specific project tag."),
									"options" => array_merge(array(__pe("All")=>""),peTheme()->data->getTaxOptions("prj-category")),
									"default" => ""
									),
							  "title" =>
							  array(
									"label" => __pe("Text Block Title"),
									"type" => "Text",
									"default" => __pe("Title")
									),
							  "content" =>
							  array(
									"label" => __pe("Text Block Content"),
									"type" => "TextArea",
									"default" => sprintf(__pe('%sDescription%s'),"<br/>\n","<br/>\n")
									),
							  "label" =>
							  array(
									"label" => __pe("Link Label"),
									"type" => "Text",
									"default" => __pe("read more")
									),
							  "url" =>
							  array(
									"label" => __pe("Link Url"),
									"type" => "Text",
									"default" => __pe("#")
									)
							  );

		peTheme()->shortcode->blockLevel[] = $this->trigger;

	}

	
	public function output($atts,$content=null,$code="") {

		$defaults = apply_filters("pe_theme_shortcode_projects_defaults",array('count'=>3,'tag'=> '','content' => "","title" => "","label" => "","url" => ""),$atts);
		$conf = (object) shortcode_atts($defaults,$atts);

		if ($content) {
			$conf->content =& do_shortcode(apply_filters("the_content",$content));
		}

		if ($conf->tag) { 
			$conf->tag = array($conf->tag);
		}

		$t =& peTheme();
		$content = "";

		if ($loop =& $t->project->customLoop($conf->count,$conf->tag,false)) {

			ob_start();
			$t->template->data($conf,$loop);
			$t->get_template_part("shortcode","projects");
			$content =& ob_get_clean();
			$t->content->resetLoop();

		}

		return $content;

	}


}

?>
