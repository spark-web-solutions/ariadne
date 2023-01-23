<?php
add_action('acf/init', 'spark_hero_ia');
if (!function_exists('spark_hero_ia')) {
	function spark_hero_ia() {
		if (function_exists("acf_add_local_field_group")) {
			acf_add_local_field_group(array(
					'key' => 'acf_hero',
					'title' => __('Hero', 'spark_theme'),
					'fields' => array(
							array(
									'key' => 'spark_hero_tab_defaults',
									'label' => __('Defaults', 'spark_theme'),
									'name' => 'hero_tab_defaults',
									'type' => 'tab',
							),
							array(
									'key' => 'spark_hero_bgcolour',
									'label' => __('Background Colour', 'spark_theme'),
									'name' => 'hero_bgcolour',
									'type' => 'select',
									'choices' => spark_panels_get_theme_palette(),
									'default_value' => 'transparent',
							),
							array(
									'key' => 'spark_hero_hide_title',
									'label' => __('Hide Title?', 'spark_theme'),
									'name' => 'hide_title',
									'type' => 'checkbox',
									'choices' => array(
											'true' => __('Hide Title', 'spark_theme'),
									),
									'default_value' => '',
									'layout' => 'horizontal',
							),
							array(
									'key' => 'spark_hero_image',
									'label' => __('Hero Image', 'spark_theme'),
									'name' => 'hero_image',
									'type' => 'image',
									'instructions' => __('If no image is specified, the Featured Image will be used instead.', 'spark_theme'),
									'save_format' => 'url',
									'preview_size' => 'thumbnail',
									'library' => 'all',
							),
							array(
									'key' => 'spark_hero_video',
									'label' => __('Hero Video', 'spark_theme'),
									'name' => 'hero_video',
									'type' => 'text',
									'placeholder' => 'https://',
									'instructions' => __('If included, video will be auto-played without sound. As such it should be brief (<30 seconds) and silent in order to minimise file size.', 'spark_theme'),
							),
							array(
									'key' => 'spark_hero_bg_opacity',
									'label' => __('Background Image Opacity', 'spark_theme'),
									'instructions' => __('Enter a number between 0 (completely transparent) and 1 (completely opaque).', 'spark_theme'),
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
									'label' => __('Background Image Anchor (Vertical)', 'spark_theme'),
									'name' => 'hero_bgpos_y',
									'type' => 'radio',
									'choices' => array(
											'top' => __('Top', 'spark_theme'),
											'25%' => __('25%', 'spark_theme'),
											'center' => __('Centre', 'spark_theme'),
											'75%' => __('75%', 'spark_theme'),
											'bottom' => __('Bottom', 'spark_theme'),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => 'center',
									'layout' => 'horizontal',
							),
							array(
									'key' => 'spark_hero_bgpos_x',
									'label' => __('Background Image Anchor (Horizontal)', 'spark_theme'),
									'name' => 'hero_bgpos_x',
									'type' => 'radio',
									'choices' => array(
											'left' => __('Left', 'spark_theme'),
											'25%' => __('25%', 'spark_theme'),
											'center' => __('Centre', 'spark_theme'),
											'75%' => __('75%', 'spark_theme'),
											'right' => __('Right', 'spark_theme'),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => 'center',
									'layout' => 'horizontal',
							),
							array(
									'key' => 'spark_hero_title',
									'label' => __('Hero Title', 'spark_theme'),
									'name' => 'hero_title',
									'type' => 'text',
							),
							array(
									'key' => 'spark_hero_tagline_desc',
									'label' => __('Hero Tagline Description', 'spark_theme'),
									'name' => 'hero_tagline_desc',
									'type' => 'textarea',
							),
							array(
									'key' => 'spark_hero_cta_field_action_text',
									'label' => __('Action Text', 'spark_theme'),
									'name' => 'hero_action_text',
									'type' => 'text',
							),
							array(
									'key' => 'spark_hero_cta_field_destination',
									'label' => __('Destination URL', 'spark_theme'),
									'name' => 'hero_destination',
									'type' => 'text',
									'placeholder' => 'https://',
							),
							array(
									'key' => 'spark_hero_tab_medium',
									'label' => __('Medium Screens', 'spark_theme'),
									'name' => 'hero_tab_medium',
									'type' => 'tab',
							),
							array(
									'key' => 'spark_hero_image_medium',
									'label' => __('Hero Image', 'spark_theme'),
									'name' => 'hero_image_medium',
									'type' => 'image',
									'save_format' => 'url',
									'preview_size' => 'thumbnail',
									'library' => 'all',
							),
							array(
									'key' => 'spark_hero_bgpos_y_medium',
									'label' => __('Vertical Image Position', 'spark_theme'),
									'name' => 'hero_bgpos_y_medium',
									'type' => 'radio',
									'choices' => array(
											'' => __('Use Default', 'spark_theme'),
											'top' => __('Top', 'spark_theme'),
											'25%' => __('25%', 'spark_theme'),
											'center' => __('Centre', 'spark_theme'),
											'75%' => __('75%', 'spark_theme'),
											'bottom' => __('Bottom', 'spark_theme'),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => '',
									'layout' => 'vertical',
							),
							array(
									'key' => 'spark_hero_bgpos_x_medium',
									'label' => __('Horizontal Image Position', 'spark_theme'),
									'name' => 'hero_bgpos_x_medium',
									'type' => 'radio',
									'choices' => array(
											'' => __('Use Default', 'spark_theme'),
											'left' => __('Left', 'spark_theme'),
											'25%' => __('25%', 'spark_theme'),
											'center' => __('Centre', 'spark_theme'),
											'75%' => __('75%', 'spark_theme'),
											'right' => __('Right', 'spark_theme'),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => '',
									'layout' => 'vertical',
							),
							array(
									'key' => 'spark_hero_tab_small',
									'label' => __('Small Screens', 'spark_theme'),
									'name' => 'hero_tab_small',
									'type' => 'tab',
							),
							array(
									'key' => 'spark_hero_image_small',
									'label' => __('Hero Image', 'spark_theme'),
									'name' => 'hero_image_small',
									'type' => 'image',
									'save_format' => 'url',
									'preview_size' => 'thumbnail',
									'library' => 'all',
							),
							array(
									'key' => 'spark_hero_bgpos_y_small',
									'label' => __('Vertical Image Position', 'spark_theme'),
									'name' => 'hero_bgpos_y_small',
									'type' => 'radio',
									'choices' => array(
											'' => __('Use Default', 'spark_theme'),
											'top' => __('Top', 'spark_theme'),
											'25%' => __('25%', 'spark_theme'),
											'center' => __('Centre', 'spark_theme'),
											'75%' => __('75%', 'spark_theme'),
											'bottom' => __('Bottom', 'spark_theme'),
									),
									'other_choice' => 0,
									'save_other_choice' => 0,
									'default_value' => '',
									'layout' => 'vertical',
							),
							array(
									'key' => 'spark_hero_bgpos_x_small',
									'label' => __('Horizontal Image Position', 'spark_theme'),
									'name' => 'hero_bgpos_x_small',
									'type' => 'radio',
									'choices' => array(
											'' => __('Use Default', 'spark_theme'),
											'left' => __('Left', 'spark_theme'),
											'25%' => __('25%', 'spark_theme'),
											'center' => __('Centre', 'spark_theme'),
											'75%' => __('75%', 'spark_theme'),
											'right' => __('Right', 'spark_theme'),
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
