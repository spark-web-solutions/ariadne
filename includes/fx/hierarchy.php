<?php
if (!function_exists('spark_get_value_from_hierarchy')) {
	/**
	 * Works its way up the post hierarchy to find a meta value
	 * @param string $field Key to look for
	 * @param integer|WP_Post $post (optional) Post to start from
	 * @param integer $last_id (optional) Variable to hold the ID of the post the value was found on (or the top-level ancestor if no value found)
	 * @param string $type Type of data to look for. Accepts either "meta" (default) or "taxonomy".
	 * @return mixed
	 */
	function spark_get_value_from_hierarchy($field, $post = null, &$last_id = null, $type = "meta") {
		$post = get_post($post);

		if ($post instanceof WP_Post) {
			$id = $post->ID;
			$ancestors = get_ancestors($id, get_post_type($id));
			do {
				$last_id = $id;
				if ($type == 'taxonomy') {
					$value = wp_get_object_terms($id, $field);
				} else {
					if ($field == 'featured_image') {
						$value = spark_get_featured_image_url('full', $id);
					} else {
						$value = get_post_meta($id, $field, true);
					}
				}
				$id = array_shift($ancestors);
			} while ($id > 0 && empty($value));
		}

		// Check for fallback default from customizer
		if (empty($value)) {
			$value = spark_get_theme_mod('default_'.$field);
			if (!empty($value)) {
				$last_id = 0;
			}
		}

		return $value;
	}
}

if (!function_exists('spark_get_children')) {
	/**
	 * Get the children of the specified post
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Will use global $post if not specified.
	 * @return array|boolean List of posts or false on failure
	 */
	function spark_get_children($post = null) {
	    $post = get_post($post);
	    if (!$post instanceof WP_Post) {
	    	return false;
	    }

	    $transient = ns_.'children_'.$post->ID.'_';
	    if (false === ($children = get_transient($transient)) || !Spark_Transients::use_transients()) {
	        $args = array(
	                'posts_per_page' => -1,
	                'orderby' => array('menu_order' => 'ASC', 'title' => 'ASC'),
	                'post_type' => get_post_type($post),
	                'post_parent' => $post->ID,
	        );
	        $children = get_posts($args);

	        if (Spark_Transients::use_transients()) {
	            set_transient($transient, $children, LONG_TERM);
	        }
	    }

	    return $children;
	}
}

if (!function_exists('spark_has_children')) {
	/**
	 * Check if the post has children
	 *
	 * @param int $post_id
	 * @return bool
	 */
	function spark_has_children($post_id = null) {
		$post = get_post($post_id);
		if (!$post instanceof WP_Post) {
			return false;
		}
	    $query = new WP_Query(array('post_parent' => $post->ID, 'post_type' => get_post_type($post)));
	    return $query->have_posts();
	}
}

/**
 * Add excerpts to pages
 */
add_action('init', 'spark_add_excerpts_to_pages');
function spark_add_excerpts_to_pages() {
     add_post_type_support('page', 'excerpt');
}
