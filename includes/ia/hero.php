<?php
add_action('acf/init', 'spark_hero_ia');
if (!function_exists('spark_hero_ia')) {
	function spark_hero_ia() {
		if (function_exists("acf_add_local_field_group")) {
			acf_add_local_field_group(array(
					'key' => 'acf_hero',
					'title' => __('Hero', SPARK_THEME_TEXTDOMAIN),
					'fields' => array(
							array(
									'key' => 'spark_hero_tab_defaults',
									'label' => __('Defaults', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_tab_defaults',
									'type' => 'tab',
							),
							array(
									'key' => 'spark_hero_bgcolour',
									'label' => __('Background Colour', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_bgcolour',
									'type' => 'select',
									'choices' => spark_panels_get_theme_palette(),
									'default_value' => 'transparent',
							),
							array(
									'key' => 'spark_hero_hide_title',
									'label' => __('Hide Title?', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hide_title',
									'type' => 'checkbox',
									'choices' => array(
											'true' => __('Hide Title', SPARK_THEME_TEXTDOMAIN),
									),
									'default_value' => '',
									'layout' => 'horizontal',
							),
							array(
									'key' => 'spark_hero_image',
									'label' => __('Hero Image', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_image',
									'type' => 'image',
									'instructions' => __('If no image is specified, the Featured Image will be used instead.', SPARK_THEME_TEXTDOMAIN),
									'save_format' => 'url',
									'preview_size' => 'thumbnail',
									'library' => 'all',
							),
							array(
									'key' => 'spark_hero_video',
									'label' => __('Hero Video', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_video',
									'type' => 'text',
									'placeholder' => 'https://',
									'instructions' => __('If included, video will be auto-played without sound. As such it should be brief (<30 seconds) and silent in order to minimise file size.', SPARK_THEME_TEXTDOMAIN),
							),
							array(
									'key' => 'spark_hero_bg_opacity',
									'label' => __('Background Image Opacity', SPARK_THEME_TEXTDOMAIN),
									'instructions' => __('Enter a number between 0 (completely transparent) and 1 (completely opaque).', SPARK_THEME_TEXTDOMAIN),
									'name' => 'bg_opacity',
									'type' => 'number',
									'default_value' => '1',
									'formatting' => 'text',
									'min' => 0,
									'max' => 1,
									'step' => 0.01,
							),
							array(
									'key' => 'spark_hero_bgpos_y',
									'label' => __('Background Image Anchor (Vertical)', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_bgpos_y',
									'type' => 'radio',
									'choices' => array(
											'top' => __('Top', SPARK_THEME_TEXTDOMAIN),
											'25%' => __('25%', SPARK_THEME_TEXTDOMAIN),
											'center' => __('Centre', SPARK_THEME_TEXTDOMAIN),
											'75%' => __('75%', SPARK_THEME_TEXTDOMAIN),
											'bottom' => __('Bottom', SPARK_THEME_TEXTDOMAIN),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => 'center',
									'layout' => 'horizontal',
							),
							array(
									'key' => 'spark_hero_bgpos_x',
									'label' => __('Background Image Anchor (Horizontal)', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_bgpos_x',
									'type' => 'radio',
									'choices' => array(
											'left' => __('Left', SPARK_THEME_TEXTDOMAIN),
											'25%' => __('25%', SPARK_THEME_TEXTDOMAIN),
											'center' => __('Centre', SPARK_THEME_TEXTDOMAIN),
											'75%' => __('75%', SPARK_THEME_TEXTDOMAIN),
											'right' => __('Right', SPARK_THEME_TEXTDOMAIN),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => 'center',
									'layout' => 'horizontal',
							),
							array(
									'key' => 'spark_hero_title',
									'label' => __('Hero Title', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_title',
									'type' => 'text',
							),
							array(
									'key' => 'spark_hero_tagline_desc',
									'label' => __('Hero Tagline Description', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_tagline_desc',
									'type' => 'textarea',
							),
							array(
									'key' => 'spark_hero_cta_field_action_text',
									'label' => __('Action Text', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_action_text',
									'type' => 'text',
							),
							array(
									'key' => 'spark_hero_cta_field_destination',
									'label' => __('Destination URL', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_destination',
									'type' => 'text',
									'placeholder' => 'https://',
							),
							array(
									'key' => 'spark_hero_tab_medium',
									'label' => __('Medium Screens', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_tab_medium',
									'type' => 'tab',
							),
							array(
									'key' => 'spark_hero_image_medium',
									'label' => __('Hero Image', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_image_medium',
									'type' => 'image',
									'save_format' => 'url',
									'preview_size' => 'thumbnail',
									'library' => 'all',
							),
							array(
									'key' => 'spark_hero_bgpos_y_medium',
									'label' => __('Vertical Image Position', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_bgpos_y_medium',
									'type' => 'radio',
									'choices' => array(
											'' => __('Use Default', SPARK_THEME_TEXTDOMAIN),
											'top' => __('Top', SPARK_THEME_TEXTDOMAIN),
											'25%' => __('25%', SPARK_THEME_TEXTDOMAIN),
											'center' => __('Centre', SPARK_THEME_TEXTDOMAIN),
											'75%' => __('75%', SPARK_THEME_TEXTDOMAIN),
											'bottom' => __('Bottom', SPARK_THEME_TEXTDOMAIN),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => '',
									'layout' => 'vertical',
							),
							array(
									'key' => 'spark_hero_bgpos_x_medium',
									'label' => __('Horizontal Image Position', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_bgpos_x_medium',
									'type' => 'radio',
									'choices' => array(
											'' => __('Use Default', SPARK_THEME_TEXTDOMAIN),
											'left' => __('Left', SPARK_THEME_TEXTDOMAIN),
											'25%' => __('25%', SPARK_THEME_TEXTDOMAIN),
											'center' => __('Centre', SPARK_THEME_TEXTDOMAIN),
											'75%' => __('75%', SPARK_THEME_TEXTDOMAIN),
											'right' => __('Right', SPARK_THEME_TEXTDOMAIN),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => '',
									'layout' => 'vertical',
							),
							array(
									'key' => 'spark_hero_tab_small',
									'label' => __('Small Screens', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_tab_small',
									'type' => 'tab',
							),
							array(
									'key' => 'spark_hero_image_small',
									'label' => __('Hero Image', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_image_small',
									'type' => 'image',
									'save_format' => 'url',
									'preview_size' => 'thumbnail',
									'library' => 'all',
							),
							array(
									'key' => 'spark_hero_bgpos_y_small',
									'label' => __('Vertical Image Position', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_bgpos_y_small',
									'type' => 'radio',
									'choices' => array(
											'' => __('Use Default', SPARK_THEME_TEXTDOMAIN),
											'top' => __('Top', SPARK_THEME_TEXTDOMAIN),
											'25%' => __('25%', SPARK_THEME_TEXTDOMAIN),
											'center' => __('Centre', SPARK_THEME_TEXTDOMAIN),
											'75%' => __('75%', SPARK_THEME_TEXTDOMAIN),
											'bottom' => __('Bottom', SPARK_THEME_TEXTDOMAIN),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => '',
									'layout' => 'vertical',
							),
							array(
									'key' => 'spark_hero_bgpos_x_small',
									'label' => __('Horizontal Image Position', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hero_bgpos_x_small',
									'type' => 'radio',
									'choices' => array(
											'' => __('Use Default', SPARK_THEME_TEXTDOMAIN),
											'left' => __('Left', SPARK_THEME_TEXTDOMAIN),
											'25%' => __('25%', SPARK_THEME_TEXTDOMAIN),
											'center' => __('Centre', SPARK_THEME_TEXTDOMAIN),
											'75%' => __('75%', SPARK_THEME_TEXTDOMAIN),
											'right' => __('Right', SPARK_THEME_TEXTDOMAIN),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => '',
									'layout' => 'vertical',
							),
					),
					'location' => spark_hero_ia_locations(),
					'position' => 'normal',
					'style' => 'default',
					'menu_order' => 0,
			));
		}
	}
}

function spark_hero_ia_locations() {
	$post_types = array('page');
	$taxonomies = array('category');
	$post_types = apply_filters('spark_theme_hero_post_types', $post_types);
	$taxonomies = apply_filters('spark_theme_hero_taxonomies', $taxonomies);
	$locations = array();
	foreach ($post_types as $post_type) {
		$locations[] = array(
				array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => $post_type,
				),
		);
	}
	foreach ($taxonomies as $taxonomy) {
		$locations[] = array(
				array(
						'param' => 'taxonomy',
						'operator' => '==',
						'value' => $taxonomy,
				),
		);
	}
	return $locations;
}
