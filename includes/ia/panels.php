<?php
new Spark_Theme\cptClass('Panel', 'Panels', array(
		'public' => false,
		'has_archive' => false,
		'query_var' => false,
		'show_ui' => true,
		'menu_icon' => 'dashicons-layout',
));

/**
 * Panel meta fields (uses ACF)
 */
add_action('admin_init', 'spark_panel_meta'); // Needs to run after init to get CPTs in post type list
function spark_panel_meta() {
	if (function_exists("register_field_group")) {
		register_field_group(array(
				'id' => 'acf_panel-settings',
				'title' => __('Panel Settings', 'spark_theme'),
				'fields' => array(
						array(
								'key' => 'spark_panels_field_panel_name',
								'label' => __('Panel Name', 'spark_theme'),
								'name' => 'panel_name',
								'type' => 'text',
								'instructions' => __('Class name (used for styling). Multiple classes can be separated with spaces.', 'spark_theme'),
								'formatting' => 'text',
						),
						array(
								'key' => 'spark_panels_field_children',
								'label' => __('Display Children As', 'spark_theme'),
								'name' => 'children',
								'type' => 'radio',
								'instructions' => __('If this panel has child panels, they can either be displayed as a slider or a series of tiles. Note that if this panel has children most of the following options are ignored.', 'spark_theme'),
								'choices' => array(
										'slider' => __('Slider', 'spark_theme'),
										'tiles' => __('Tiles', 'spark_theme'),
								),
								'other_choice' => 0,
								'save_other_choice' => 0,
								'default_value' => 'slider',
								'layout' => 'horizontal',
						),
						array(
								'key' => 'spark_panels_field_recipe',
								'label' => __('Recipe', 'spark_theme'),
								'name' => 'recipe',
								'type' => 'select',
								'required' => 1,
								'choices' => spark_panels_get_recipe_options(),
								'default_value' => 'default',
								'allow_null' => 0,
								'multiple' => 0,
						),
						array(
								'key' => 'spark_panels_field_hide_title',
								'label' => __('Hide Panel Title?', 'spark_theme'),
								'name' => 'hide_title',
								'type' => 'checkbox',
								'choices' => array(
										'true' => __('Hide Title', 'spark_theme'),
								),
								'default_value' => '',
								'layout' => 'horizontal',
						),
						array(
								'key' => 'spark_panels_field_flavour',
								'label' => __('Display Style', 'spark_theme'),
								'name' => 'flavour',
								'type' => 'radio',
								'instructions' => __('How do you want this panel displayed?', 'spark_theme'),
								'choices' => array(
										'full_bleed' => __('Full width', 'spark_theme'),
										'partial_bleed' => __('Full width background image, contained content', 'spark_theme'),
										'fully_contained' => __('Contained background image and content', 'spark_theme'),
								),
								'other_choice' => 0,
								'save_other_choice' => 0,
								'default_value' => 'partial_bleed',
								'layout' => 'horizontal',
						),
						array(
								'key' => 'spark_panels_bg_opacity',
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
								'key' => 'spark_panels_field_bg_colour',
								'label' => __('Background Colour', 'spark_theme'),
								'name' => 'bg_colour',
								'type' => 'select',
								'choices' => spark_panels_get_theme_palette(),
								'default_value' => 'transparent',
								'multiple' => 0,
						),
						array(
								'key' => 'spark_panels_field_bg_pos_x',
								'label' => __('Background Image Anchor (Horizontal)', 'spark_theme'),
								'name' => 'bg_pos_x',
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
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '!=',
														'value' => 'recent_posts',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '!=',
														'value' => 'tile_menu',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '!=',
														'value' => 'tiles',
												),
										),
										'allorany' => 'all',
								),
						),
						array(
								'key' => 'spark_panels_field_bg_pos_y',
								'label' => __('Background Image Anchor (Vertical)', 'spark_theme'),
								'name' => 'bg_pos_y',
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
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '!=',
														'value' => 'recent_posts',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '!=',
														'value' => 'recent_posts_carousel',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '!=',
														'value' => 'tile_menu',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '!=',
														'value' => 'tiles',
												),
										),
										'allorany' => 'all',
								),
						),
						// Recipe-specific options
						array(
								'key' => 'spark_panels_field_image',
								'label' => __('Additional Image', 'spark_theme'),
								'name' => 'image',
								'type' => 'image',
								'instructions' => __('Some recipes will display an additional image alongside the content', 'spark_theme'),
								'save_format' => 'url',
								'preview_size' => 'thumbnail',
								'library' => 'all',
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'half_image',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'with_image',
												),
										),
										'allorany' => 'any',
								),
						),
						array(
								'key' => 'spark_panels_field_image_pos',
								'label' => __('Image Position', 'spark_theme'),
								'name' => 'image_pos',
								'type' => 'radio',
								'instructions' => __('Position of the additional image', 'spark_theme'),
								'choices' => array(
										'left' => __('Left', 'spark_theme'),
										'right' => __('Right', 'spark_theme'),
								),
								'other_choice' => 0,
								'save_other_choice' => 0,
								'default_value' => 'left',
								'layout' => 'horizontal',
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'half_image',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'with_image',
												),
										),
										'allorany' => 'any',
								),
						),
						array(
								'key' => 'spark_panels_field_menu',
								'label' => __('Menu', 'spark_theme'),
								'name' => 'menu',
								'type' => 'select',
								'instructions' => __('Each menu item in the selected menu will become a tile, with the description being used as the URL for the background image.', 'spark_theme'),
								'choices' => spark_panels_get_menus(),
								'other_choice' => 0,
								'save_other_choice' => 0,
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'tile_menu',
												),
										),
										'allorany' => 'any',
								),
						),
						array(
								'key' => 'spark_panels_field_post_type',
								'label' => __('Post Type', 'spark_theme'),
								'name' => 'post_type',
								'type' => 'select',
								'choices' => spark_panels_get_post_types(),
								'default_value' => 'post',
								'multiple' => 0,
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'recent_posts',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'recent_posts_carousel',
												),
										),
										'allorany' => 'any',
								),
						),
						array(
								'key' => 'spark_panels_field_post_category',
								'label' => __('Category', 'spark_theme'),
								'name' => 'post_category',
								'type' => 'select',
								'choices' => spark_panels_get_post_categories(),
								'default_value' => '',
								'multiple' => 0,
								'conditional_logic' => array(
										array(
												array(
														'field' => 'spark_panels_field_post_type',
														'operator' => '==',
														'value' => 'post',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'recent_posts',
												),
										),
										array(
												array(
														'field' => 'spark_panels_field_post_type',
														'operator' => '==',
														'value' => 'post',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'recent_posts_carousel',
												),
										),
								),
						),
						array(
								'key' => 'spark_panels_field_num_items',
								'label' => __('Maximum Number of Items', 'spark_theme'),
								'name' => 'num_items',
								'type' => 'number',
								'default_value' => '6',
								'formatting' => 'text',
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'recent_posts',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'recent_posts_carousel',
												),
										),
										'allorany' => 'any',
								),
						),
						array(
								'key' => 'spark_panels_field_num_per_row_large',
								'label' => __('Items Per Row (Large Screen)', 'spark_theme'),
								'name' => 'num_per_row_large',
								'type' => 'number',
								'default_value' => '3',
								'formatting' => 'text',
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'recent_posts',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'tile_menu',
												),
												array(
														'field' => 'spark_panels_field_children',
														'operator' => '==',
														'value' => 'tiles',
												),
										),
										'allorany' => 'any',
								),
						),
						array(
								'key' => 'spark_panels_field_num_per_row_medium',
								'label' => __('Items Per Row (Medium Screen)', 'spark_theme'),
								'name' => 'num_per_row_medium',
								'type' => 'number',
								'default_value' => '3',
								'formatting' => 'text',
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'recent_posts',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'tile_menu',
												),
												array(
														'field' => 'spark_panels_field_children',
														'operator' => '==',
														'value' => 'tiles',
												),
										),
										'allorany' => 'any',
								),
						),
						array(
								'key' => 'spark_panels_field_num_per_row_small',
								'label' => __('Items Per Row (Small Screen)', 'spark_theme'),
								'name' => 'num_per_row_small',
								'type' => 'number',
								'default_value' => '1',
								'formatting' => 'text',
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'recent_posts',
												),
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'tile_menu',
												),
												array(
														'field' => 'spark_panels_field_children',
														'operator' => '==',
														'value' => 'tiles',
												),
										),
										'allorany' => 'any',
								),
						),
						array(
								'key' => 'spark_panels_field_video',
								'label' => __('Video URL', 'spark_theme'),
								'name' => 'video',
								'type' => 'text',
								'placeholder' => 'https://',
								'formatting' => 'text',
								'conditional_logic' => array(
										'status' => 1,
										'rules' => array(
												array(
														'field' => 'spark_panels_field_recipe',
														'operator' => '==',
														'value' => 'video',
												),
										),
										'allorany' => 'any',
								),
						),
				),
				'location' => array(
						array(
								array(
										'param' => 'post_type',
										'operator' => '==',
										'value' => 'panel',
										'order_no' => 0,
										'group_no' => 0,
								),
						),
				),
				'options' => array(
						'position' => 'normal',
						'layout' => 'default',
						'hide_on_screen' => array(
								0 => 'excerpt',
								1 => 'custom_fields',
								2 => 'discussion',
								3 => 'comments',
								4 => 'categories',
								5 => 'tags',
								6 => 'send-trackbacks',
						),
				),
				'menu_order' => 0,
		));

		register_field_group(array(
				'id' => 'acf_call-to-action',
				'title' => __('Call to Action', 'spark_theme'),
				'fields' => array(
						array(
								'key' => 'spark_panels_cta_field_action_text',
								'label' => __('Action Text', 'spark_theme'),
								'name' => 'action_text',
								'type' => 'text',
								'formatting' => 'html',
						),
						array(
								'key' => 'spark_panels_cta_field_destination',
								'label' => __('Destination URL', 'spark_theme'),
								'name' => 'destination',
								'type' => 'text',
								'placeholder' => 'https://',
								'formatting' => 'html',
						),
				),
				'location' => array(
						array(
								array(
										'param' => 'post_type',
										'operator' => '==',
										'value' => 'panel',
										'order_no' => 0,
										'group_no' => 0,
								),
						),
				),
				'options' => array(
						'position' => 'normal',
						'layout' => 'default',
						'hide_on_screen' => array(),
				),
				'menu_order' => 1,
		));

		register_field_group(array(
				'id' => 'acf_panel_post_types',
				'title' => __('Post Types', 'spark_theme'),
				'fields' => array(
						array(
								'key' => 'spark_panels_post_types',
								'label' => __('Post Types', 'spark_theme'),
								'description' => __('This panel will be displayed on all posts of the selected types', 'spark_theme'),
								'name' => 'post_types',
								'type' => 'checkbox',
								'choices' => spark_panels_get_post_types(),
								'default_value' => '',
								'layout' => 'vertical',
						),
				),
				'location' => array(
						array(
								array(
										'param' => 'post_type',
										'operator' => '==',
										'value' => 'panel',
										'order_no' => 0,
										'group_no' => 0,
								),
						),
				),
				'options' => array(
						'position' => 'side',
						'layout' => 'default',
						'hide_on_screen' => array(),
				),
				'menu_order' => 0,
		));
	}
}
