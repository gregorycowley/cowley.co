<?php

class PeThemeWidgetBrandspaceContacts extends PeThemeWidget {

	public function __construct() {
		$this->name = __pe("Pixelentity - Contacts");
		$this->description = __pe("Statistical informations and links");
		$this->wclass = "widget_contact";

		$this->fields = array(
							  "title" => 
							  array(
									"label"=>__pe("Title"),
									"type"=>"Text",
									"description" => __pe("Widget title"),
									"default"=>"Contact Widget"
									),
							  "handwritten" => 
							  array(
									"label"=>__pe("Handwritten Text"),
									"type"=>"Text",
									"description" => __pe("Optional handwritten text, leave empty to hide"),
									"default"=> __pe("Quick Contacts")
									),							  
							  "content" => 
							  array(
									"label"=>__pe("Contact Info"),
									"type"=>"TextArea",
									"description" => __pe("Contact info."),
									"default"=>sprintf('Street Number & Name<br />
Building District<br />
Postal Code<br />
2034 BXU<br />
<br />
<span>+353 1 234 566 78</span>
<a href="#">info@emailaddress.com</a>',"\n","\n")
									)
							  
							  );

		parent::__construct();
	}

	public function &getContent(&$instance) {
		
		extract(shortcode_atts(array('title'=>'','handwritten'=>'','content' => ''),$instance));

		$html = "";

		if ($handwritten) {
			$html .= sprintf('<span class="label hand-written"><i class="icon-down-open"></i>%s</span>',$handwritten);
		}

		if ($title) {
			$html .= "<h3>$title</h3>";
		}

		if ($content) {
			$html .= sprintf('<p>%s</p>',do_shortcode($content));
		}

		return $html;
	}


}
?>
