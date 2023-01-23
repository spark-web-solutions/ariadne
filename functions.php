<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

require_once(get_template_directory().'/includes/core.php');

/** Core Theme Setup **/
add_action('after_setup_theme', 'spark_theme_setup');
function spark_theme_setup() {
	// Register theme support
	add_theme_support('post-thumbnails'); // Featured images
	add_theme_support('automatic-feed-links'); // RSS support
	add_theme_support('title-tag');
	add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));
	add_theme_support('align-wide');
	add_theme_support('editor-styles');
	add_theme_support('editor-color-palette', spark_get_theme_palette());
	add_theme_support('disable-custom-font-sizes');
	add_theme_support('disable-custom-colors');
	add_theme_support('disable-custom-gradients');
	add_theme_support('responsive-embeds');
	add_theme_support('editor-font-sizes', array(
			array(
					'name' => esc_attr__('Small', 'spark_theme'),
					'size' => '0.833rem',
					'slug' => 'small',
			),
			array(
					'name' => esc_attr__('Normal', 'spark_theme'),
					'size' => '1rem',
					'slug' => 'normal',
			),
			array(
					'name' => esc_attr__('Large', 'spark_theme'),
					'size' => '1.728rem',
					'slug' => 'large',
			),
			array(
					'name' => esc_attr__('Huge', 'spark_theme'),
					'size' => '2.488rem',
					'slug' => 'huge',
			),
	));

	// @todo review and activate/remove these
	// add_theme_support('customize-selective-refresh-widgets');

	add_filter('should_load_separate_core_block_assets', '__return_true');

	// Enable custom logo support
	// @todo configure settings
	$logo_config = array(
			'height'      => 100,
			'width'       => 300,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array('site-title', 'site-description'),
	);
	add_theme_support('custom-logo', $logo_config);

	// Register menus
	register_nav_menus(array(
			'main' => 'Main',
			'footer' => 'Footer',
	));

	// Trigger refresh process if needed
	add_action('init', function () {
		if ((current_user_can('manage_options') && isset($_GET['spark']) && $_GET['spark'] == 'refresh')) {
			$transients = new Spark_Transients();
			$transients->delete();
			wp_redirect(remove_query_arg('spark'));
			exit;
		}
	});

	// Show template name in admin bar
	add_filter('template_include', function ($t) {
		if (current_user_can('manage_options')) {
			$template_name = get_page_template_slug(get_queried_object_id());
			if (empty($template_name)) {
				$template_name = '(default)';
			}
			$template_name = basename($t).' > '.$template_name;
			add_action('admin_bar_menu', function ($wp_admin_bar) use ($template_name) {
				$args = array(
						'id' => 'spark-template',
						'title' => $template_name,
						'meta' => array(
								'class' => 'spark temp',
						),
				);
				$wp_admin_bar->add_node($args);
			}, PHP_INT_MAX);
		}
		return $t;
	}, PHP_INT_MAX);

	// Add our custom admin bar nodes
	add_action('admin_bar_menu', function ($wp_admin_bar) {
		// Environment indicator
		switch (wp_get_environment_type()) {
			case 'development':
			case 'local':
				$class = 'dev';
				break;
			case 'staging':
				$class = 'stage';
				break;
			case 'production':
			default:
				$class = 'prod';
				break;
		}

		$args = array(
				'id' => 'spark-env',
				'title' => strtoupper(wp_get_environment_type()),
				'meta' => array(
						'class' => 'spark '.$class,
				),
		);
		$wp_admin_bar->add_node($args);

		$refresh_link = is_admin() ? '/?spark=refresh' : '?spark=refresh';
		$args = array(
				'id' => 'spark-refresh',
				'title' => 'Refresh',
				'href' => $refresh_link,
				'meta' => array(
						'class' => 'spark refresh',
				),
		);
		$wp_admin_bar->add_node($args);
	}, PHP_INT_MAX);
}

add_action('admin_init', function() {
	new Spark_Theme_Updates('spark-web-solutions', 'ariadne');
});

/** Third Party Integrations **/

// Hide ACF field group menu item except on dev
add_filter('acf/settings/show_admin', function() {return 'development' == wp_get_environment_type();});

// Scroll to form on page load after submission
add_filter('gform_confirmation_anchor', '__return_true');
