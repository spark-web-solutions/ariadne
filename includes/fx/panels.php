<?php
if (!function_exists('spark_get_panels')) {
	/**
	 * Get panels for the current page
	 * @param array $params
	 * @return array
	 */
	function spark_get_panels() {
		if (in_array($GLOBALS['pagenow'], array('wp-signup.php', 'wp-activate.php'))) {
			return array();
		}
	    $panels = wp_cache_get('spark_panels');
	    if (false === $panels) {
	        $panels = array();
	        $args = array(
	                'posts_per_page' => -1,
	                'post_type' => 'panel',
	                'orderby' => 'menu_order',
	                'order' => 'ASC',
	                'post_parent' => 0,
	        );
	        if (is_single() || is_archive()) {
	        	$args['meta_query'] = array(
	        			array(
	        					'key' => 'post_types',
	        					'value' => '"'.get_post_type().'"', // Values are stored as a serialised array, so we look for the value surrounded by quotes to avoid false positives (e.g. posts and reposts)
	        					'compare' => 'LIKE',
	        			),
	        	);
	        	$panels = get_posts($args);
	        	wp_cache_set('spark_panels', $panels);
	        }
	    }
	    return $panels;
	}
}

add_shortcode('spark_panels', 'spark_show_panels');
if (!function_exists('spark_show_panels')) {
	/**
	 * Main loop for displaying panels
	 */
	function spark_show_panels($position = '') {
	    $panels = spark_get_panels();

	    foreach ($panels as $panel) {
	        if (empty($position) || ($position == 'top' && $panel->menu_order < 50) || ($position == 'bottom' && $panel->menu_order >= 50)) {
	            spark_show_panel($panel);
	        }
	    }
	}
}

if (!function_exists('spark_show_panel')) {
	/**
	 * Display a single panel
	 * @param WP_Post $panel
	 */
	function spark_show_panel(WP_Post $panel) {
	    $args = array(
	            'posts_per_page' => -1,
	            'post_type' => 'panel',
	            'orderby' => 'menu_order',
	            'order' => 'ASC',
	            'post_parent' => $panel->ID,
	    );
	    $children = get_posts($args);
	    if (count($children) > 0) {
	        $wrapper = $panel;
	        if (get_post_meta($panel->ID, 'children', true) == 'tiles') {
	        	locate_template('/templates/panels/tiles.php', true, false);
	        } else {
	        	locate_template('/templates/panels/slider.php', true, false);
	        }
	    } else {
	    	locate_template('/templates/panels/banner.php', true, false);
	    }
	}
}

if (!function_exists('spark_panels_get_recipes')) {
	/**
	 * Get list of available panel recipes
	 * @return array
	 */
	function spark_panels_get_recipes() {
	    $recipes = array();
	    $recipe_dirs = array(get_template_directory().'/templates/panels/recipes/');
	    if (is_child_theme()) {
	    	$recipe_dirs[] = get_stylesheet_directory().'/templates/panels/recipes/';
	    }
	    foreach ($recipe_dirs as $recipe_dir) {
	    	$dir = opendir($recipe_dir);
		    if ($dir) {
			    while (false !== ($filename = readdir($dir))) {
			        if (strpos($filename, '.php') !== false && 'index.php' !== $filename) {
			            $recipes[] = str_replace('.php', '', $filename);
			        }
			    }
		    }
	    }
	    $recipes = array_unique($recipes);
	    sort($recipes);
	    return $recipes;
	}
}

if (!function_exists('spark_panels_get_recipe_options')) {
	/**
	 * Convert list of recipes into associative array of value => label for us in select fields
	 * @return array
	 */
	function spark_panels_get_recipe_options() {
	    $recipes = spark_panels_get_recipes();
	    sort($recipes);
	    $recipe_options = array();
	    foreach ($recipes as $recipe) {
	        $recipe_options[$recipe] = ucwords(str_replace('_', ' ', $recipe));
	    }
	    return $recipe_options;
	}
}

if (!function_exists('spark_panel_cook_recipe')) {
	/**
	 * Build a panel using the relevant recipe
	 * @param WP_Post $panel
	 */
	function spark_panel_cook_recipe(WP_Post $panel) {
	    locate_template('/templates/panels/recipes/'.get_post_meta($panel->ID, 'recipe', true).'.php');
	}
}

if (!function_exists('spark_panel_show_title')) {
	/**
	 * Determine whether the panel title should be displayed or not
	 * @param WP_Post $panel
	 * @return boolean
	 */
	function spark_panel_show_title(WP_Post $panel) {
	    $hide_title = get_post_meta($panel->ID, 'hide_title', true);
	    return empty($hide_title);
	}
}

if (!function_exists('spark_panel_title')) {
	/**
	 * Display the panel title (unless configured to hide title)
	 * @param WP_Post $panel
	 */
	function spark_panel_title(WP_Post $panel, $url = '') {
	    if (spark_panel_show_title($panel)) {
	        echo '<p class="h2">';
	        if (!empty($url)) {
	            echo '<a href="'.$url.'">';
	        }
	        echo $panel->post_title;
	        if (!empty($url)) {
	            echo '</a>';
	        }
	        echo '</p>'."\n";
	    }
	}
}

if (!function_exists('spark_panel_content')) {
	/**
	 * Display the panel content
	 * @param WP_Post $panel
	 */
	function spark_panel_content(WP_Post $panel) {
		echo do_shortcode(do_blocks($panel->post_content)); // @todo surely there's a better way of parsing this content?
	}
}

if (!function_exists('spark_panels_get_menus')) {
	function spark_panels_get_menus() {
	    $menus = get_terms('nav_menu', array('hide_empty' => false));
	    $menu_choices = array();

	    foreach ($menus as $menu) {
	        $menu_choices[$menu->name] = $menu->name;
	    }
	    return $menu_choices;
	}
}

if (!function_exists('spark_panels_get_theme_palette')) {
	function spark_panels_get_theme_palette() {
	    $colours = spark_get_theme_mod(ns_.'colours', SPARK_DEFAULT_COLOUR_COUNT);
	    $palette_options = array(
	            'transparent' => 'None (i.e. transparent)',
	    );
	    for ($i = 1; $i <= $colours; $i++) {
	        $palette_options[$i] = 'Colour '.$i.' ('.spark_get_theme_mod(ns_.'colour'.$i).')';
	    }
	    return $palette_options;
	}
}

if (!function_exists('spark_panels_get_post_categories')) {
	function spark_panels_get_post_categories($taxonomy = 'category') {
	    $args = array(
	            'hide_empty' => false,
	    );
	    $terms = get_terms($taxonomy, $args);
	    $categories = array(
	            '' => 'All',
	    );
	    foreach ($terms as $term) {
	        $categories[$term->term_id] = $term->name;
	    }
	    return $categories;
	}
}

if (!function_exists('spark_panels_get_post_types')) {
	function spark_panels_get_post_types() {
	    $args = array(
	            'public' => true,
	            '_builtin' => false,
	    );
	    $post_types = get_post_types($args, 'objects');
	    $types = array(
	            'post' => 'Posts',
	    );
	    $ignore = apply_filters('spark_panels_ignore_post_types', array());
	    foreach ($post_types as $post_type) {
	        if (!in_array($post_type->name, $ignore)) {
	            $types[$post_type->name] = $post_type->label;
	        }
	    }
	    return $types;
	}
}

add_shortcode('spark_panel', 'spark_panel_shortcode');
if (!function_exists('spark_panel_shortcode')) {
	function spark_panel_shortcode($atts) {
		$atts = shortcode_atts(array(
				'id' => null,
		), $atts, 'panel');
		if (empty($atts['id'])) {
			return;
		}

		$panel = get_post($atts['id']);
		if (!$panel instanceof WP_Post || 'panel' != get_post_type($panel)) {
			return;
		}

		ob_start();
		spark_show_panel($panel);
		$html = ob_get_clean();
		return $html;
	}
}
