<?php
define('ns_', 'spark_');
define('SPARK_THEME_TEXTDOMAIN', ns_.'theme');

// Core WP integrations
require_once(trailingslashit(get_template_directory()).'includes/customizer.php'); // Our customizer fields & settings
require_once(trailingslashit(get_template_directory()).'includes/scripts.php'); // Enqueues our styles and scripts
require_once(trailingslashit(get_template_directory()).'includes/widgets.php'); // Registers our widget areas

// Helper classes
require_once(trailingslashit(get_template_directory()).'includes/classes/cookies.php'); // Spark_Cookie() - handy cookie management
require_once(trailingslashit(get_template_directory()).'includes/classes/random.php'); // Spark_Random() - funky logic for displaying random items, with caching if desired
require_once(trailingslashit(get_template_directory()).'includes/classes/transients.php'); // Spark_Transients() - transient management

// Misc functions
require_once(trailingslashit(get_template_directory()).'includes/fx/cards.php'); // Display cards
require_once(trailingslashit(get_template_directory()).'includes/fx/carousel.php'); // Gemerate carousel
require_once(trailingslashit(get_template_directory()).'includes/fx/classes.php'); // Generate custom CSS classes based on content
require_once(trailingslashit(get_template_directory()).'includes/fx/colours.php'); // Functions for adjusting colours
require_once(trailingslashit(get_template_directory()).'includes/fx/columns.php'); // Rearrange arrays into columns
require_once(trailingslashit(get_template_directory()).'includes/fx/embed.php'); // Wraps oEmbed content in Foundation responsive embed classes
require_once(trailingslashit(get_template_directory()).'includes/fx/extract.php'); // Generate an extract for your content
require_once(trailingslashit(get_template_directory()).'includes/fx/featured-image.php'); // Hierarchy-aware featured image logic
require_once(trailingslashit(get_template_directory()).'includes/fx/hero.php'); // Hero image logic
require_once(trailingslashit(get_template_directory()).'includes/fx/hierarchy.php'); // Functions for working with hierarchical content
require_once(trailingslashit(get_template_directory()).'includes/fx/login.php'); // Various customisations relating to logging in and the login page
require_once(trailingslashit(get_template_directory()).'includes/fx/map.php'); // Generate a Google map
require_once(trailingslashit(get_template_directory()).'includes/fx/meta.php'); // Meta handling utility functions
require_once(trailingslashit(get_template_directory()).'includes/fx/menus.php'); // Menu functions
require_once(trailingslashit(get_template_directory()).'includes/fx/panels.php'); // Panel functions
require_once(trailingslashit(get_template_directory()).'includes/fx/pagination.php'); // Generate pagination links using Foundation markup
require_once(trailingslashit(get_template_directory()).'includes/fx/responsive.php'); // Functions for generating responsive content
require_once(trailingslashit(get_template_directory()).'includes/fx/slug.php'); // Utility functions for accessing slug for current post

// Information Architecture
require_once(trailingslashit(get_template_directory()).'includes/ia/cpt_.php'); // Register custom CPTs
require_once(trailingslashit(get_template_directory()).'includes/ia/tax_.php'); // Register custom Taxonomies
require_once(trailingslashit(get_template_directory()).'includes/ia/cpt_tax_.php'); // Register custom CPTs which are also available as a taxonomy
require_once(trailingslashit(get_template_directory()).'includes/ia/blocks.php'); // Register our custom blocks
require_once(trailingslashit(get_template_directory()).'includes/ia/hero.php'); // Meta for configuring hero
require_once(trailingslashit(get_template_directory()).'includes/ia/panels.php'); // Meta for configuring panels

// Gravity Forms integrations
require_once(trailingslashit(get_template_directory()).'includes/gf/address.php'); // Adds Australia address type
require_once(trailingslashit(get_template_directory()).'includes/gf/columns.php'); // Adds support for multi-column Gravity Forms

if (!function_exists('spark_setup_data')) {
	function spark_setup_data($file, $transient_term = SPARK_MEDIUM_TERM) {
		global $post;
		$current_id = $post->ID;
		$archive_page = $current_page = null;
		$t_suffix = '';
		if (is_archive()) {
			$current_page = get_query_var('paged') ?: 1;
			$term = $taxonomy = null;
			if (is_category()) {
				$term = get_category(get_query_var('cat'));
				$taxonomy = 'category';
			} elseif (is_tag() || is_tax()) {
				$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
				$taxonomy = get_query_var('taxonomy');
			}
			if ($term instanceof WP_Term) {
				$current_id = 'term_'.$term->term_id;
				$t_suffix .= '_'.$taxonomy.'_'.$current_id.'_'.$current_page;
			} else {
				$current_id = null;
				$archive_page = get_page_by_path(get_query_var('post_type'));
				if ($archive_page instanceof WP_Post) {
					$current_id = $archive_page->ID;
				}
				$t_suffix .= '_'.get_query_var('post_type').'_'.$current_page;
			}
		} elseif (is_home() && !is_front_page()) {
			$current_page = get_query_var('paged') ?: 1;
			$current_id = get_option('page_for_posts', true);
			$archive_page = get_post($current_id);
			$t_suffix .= '_post_'.$current_page;
		}

		$filename = str_replace(get_stylesheet_directory(), '', $file);
		$t_args = array('name' => 'var_'.$current_id.$t_suffix, 'file' => $filename);
		$transient_name = Spark_Transients::name($t_args);
		if (false === ($var = get_transient($transient_name)) || !Spark_Transients::use_transients()) {
			$var = array(
					'file' => $filename,
					'meta' => array(),
					'ancestors' => array(),
					'ancestor_string' => '',
					'archive_page' => $archive_page,
					'transient_suffix' => '',
			);

			if (is_search()) {
				$var['transient_suffix'] .= '_search';
				$var['search_string'] = $_GET['s'];
			}

			if (is_404()) {
				$var['transient_suffix'] .= '_404';
			}

			if (is_singular()) {
				$var['meta'] = spark_get_post_meta($post->ID);
				$var['ancestors'] = get_ancestors($post->ID, get_post_type($post));
				if (!empty($var['ancestors'])) {
					$var['ancestor_string'] .= '_'.implode('_', $var['ancestors']);
				}
				$var['transient_suffix'] .= $var['ancestor_string'].'_'.$post->ID;
			}

			if (is_archive()) {
				$var['transient_suffix'] .= $t_suffix;
				if (is_tax() && $term instanceof WP_Term) {
					$var['term'] = $term;
					$var['meta'] = spark_get_term_meta($var['term']->term_id);
				} elseif ($var['archive_page'] instanceof WP_Post) {
					$var['meta'] = spark_get_post_meta($var['archive_page']->ID);
				}
			} elseif (is_home() && !is_front_page()) {
				$var['transient_suffix'] .= $t_suffix;
				$var['meta'] = spark_get_post_meta($var['archive_page']);
			}

			Spark_Transients::set($transient_name, $var, $transient_term);
		}

		return apply_filters('spark_setup_data', $var);
	}
}
