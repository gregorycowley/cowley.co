<?php

class PeThemeVideo {

	protected $master;
	protected $options;
	protected $cache;

	public function __construct(&$master) {
		$this->master =& $master;
	}

	public function cpt() {
		$cpt =
			array(
				  'labels' => 
				  array(
						'name'              => __pe("Videos"),
						'singular_name'     => __pe("Video"),
						'add_new_item'      => __pe("Add New Video"),
						'search_items'      => __pe('Search Videos'),
						'popular_items' 	  => __pe('Popular Videos'),		
						'all_items' 		  => __pe('All Videos'),
						'parent_item' 	  => __pe('Parent Video'),
						'parent_item_colon' => __pe('Parent Video:'),
						'edit_item' 		  => __pe('Edit Video'), 
						'update_item' 	  => __pe('Update Video'),
						'add_new_item' 	  => __pe('Add New Video'),
						'new_item_name' 	  => __pe('New Video Name')
						),
				  'public' => true,
				  'has_archive' => false,
				  "supports" => array("title","thumbnail"),
				  "taxonomies" => array("")
				  );
		
		PeGlobal::$config["post_types"]["video"] =& $cpt;

		PeGlobal::$config["metaboxes-video"] = 
			array(
				  "video" => PeGlobal::$const->video->metabox
				  );

	}


	public function option() {
		$posts = get_posts(
						   array(
								 "post_type"=>"video",
								 "posts_per_page"=>-1
								 )
						   );
		if (count($posts) > 0) {
			$options = array();
			foreach($posts as $post) {
				$options[$post->post_title] = $post->ID;
			}
		} else {
			$options = array(__pe("No videos defined")=>-1);
		}
		return $options;
	}


	public function &get($id) {
		$post = false;
		if (!isset($id) || $id == "" ) return $post;
		if (isset($this->cache[$id])) return $this->cache[$id];
		$post =& get_post($id);
		if (!$post || $post->post_type != "video") {
			$post = false;
			return $post;
		}
		$meta =& $this->master->meta->get($id,$post->post_type);
		$post->meta = $meta;
		switch ($meta->video->type) {
		case "vimeo":
			preg_match("/https?:\/\/(vimeo\.com|www\.vimeo\.com)\/([\w|\-]+)/i",$meta->video->url,$matches);
			break;
		case "youtube":
			preg_match("/https?:\/\/(www.youtube.com\/watch\?v=|youtube.com\/watch\?v=|youtu.be\/)([\w|\-]+)/i",$meta->video->url,$matches);
			break;
		default:
			$matches = false;
		} 
		if ($matches && isset($matches[2])) $meta->video->id = $matches[2];
		if (!isset($meta->video->cover)) {
			$poster = wp_get_attachment_url(get_post_thumbnail_id($id));
			if ($poster) {
				$meta->video->poster = $poster;
			}
		}

		return $post;
	}

	public function exists($id) {
		return $this->get($id) !== false;		
	}

	public function getInfo($id) {
		$video = $this->get($id);
		return $video === false ? $video : $video->meta->video;		
	}

	public function inline($id) {
		$post = $this->get($id);
		if (!$post) return null;
		$video =& $post->meta->video;
		
		if ($video->fullscreen === "yes" ) {
			$template = '<a href="%s" data-target="flare" data-flare-videoformats="%s" data-poster="%s" data-flare-videoposter="%s" class="peVideo"></a>';
		} else {
			$template = '<a href="%s" data-formats="%s" data-poster="%s" class="peVideo"></a>';
		}

		return sprintf($template,
					   $video->url,
					   join(",",$video->formats),
					   $video->poster,
					   $video->poster
					   );
	}

	public function output($id = null) {
		if (!$id) {
			global $post;
			$id = $post->ID;
			$meta =& $this->master->content->meta();

			// if current post is not video custom post type
			if ($post->post_type != "video") {
				// get video post id from current post meta
				$vid = empty($meta->video->id) ? false : $meta->video->id;
				if ($vid) {
					// get video post
					$p = get_post($vid);
					$meta =& $this->master->meta->get($vid,$p->post_type);
				}
			}

		} else {
			$post = get_post($id);
			$meta =& $this->master->meta->get($id,$post->post_type);
		}

		if (!empty($meta->video)) {
			$conf =& $meta->video;
			$this->master->template->data($conf);
			get_template_part("video",$conf->type);
		}
	}

	public function show($id) {
		$inline = $this->inline($id);
		if ($inline) {
			echo $inline;
		}
		
	}


}

?>
