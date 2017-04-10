<?php

class PeThemeShortcodeBrandspaceBS_HandWritten extends PeThemeShortcode {

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "hw";
		$this->group = __pe("UI");
		$this->name = __pe("Hand written font");
		$this->description = __pe("Hand written font");
		$this->fields = array(
							  "content"=> 
							  array(
									"label" => __pe("Content"),
									"type" => "Text",
									"default" => __pe("your text")
									)
							  );
	}

	public function output($atts,$content=null,$code="") {
		$html = sprintf('<span class="hand-written">%s</span>',$content);
        return trim($html);
	}


}

?>
