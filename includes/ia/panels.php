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
				'title' => __('Panel Settings', SPARK_THEME_TEXTDOMAIN),
				'fields' => array(
						array(
								'key' => 'spark_panels_field_panel_name',
								'label' => __('Panel Name', SPARK_THEME_TEXTDOMAIN),
								'name' => 'panel_name',
								'type' => 'text',
								'instructions' => __('Class name (used for styling). Multiple classes can be separated with spaces.', SPARK_THEME_TEXTDOMAIN),
								'formatting' => 'text',
						),
						array(
								'key' => 'spark_panels_field_children',
								'label' => __('Display Children As', SPARK_THEME_TEXTDOMAIN),
								'name' => 'children',
								'type' => 'radio',
								'instructions' => __('If this panel has child panels, they can either be displayed as a slider or a series of tiles. Note that if this panel has children most of the following options are ignored.', SPARK_THEME_TEXTDOMAIN),
								'choices' => array(
										'slider' => __('Slider', SPARK_THEME_TEXTDOMAIN),
										'tiles' => __('Tiles', SPARK_THEME_TEXTDOMAIN),
								),
								'other_choice' => 0,
								'save_other_choice' => 0,
								'default_value' => 'slider',
								'layout' => 'horizontal',
						),
						array(
								'key' => 'spark_panels_field_recipe',
								'label' => __('Recipe', SPARK_THEME_TEXTDOMAIN),
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
								'label' => __('Hide Panel Title?', SPARK_THEME_TEXTDOMAIN),
								'name' => 'hide_title',
								'type' => 'checkbox',
								'choices' => array(
										'true' => __('Hide Title', SPARK_THEME_TEXTDOMAIN),
								),
								'default_value' => '',
								'layout' => 'horizontal',
						),
						array(
								'key' => 'spark_panels_field_flavour',
								'label' => __('Display Style', SPARK_THEME_TEXTDOMAIN),
								'name' => 'flavour',
								'type' => 'radio',
								'instructions' => __('How do you want this panel displayed?', SPARK_THEME_TEXTDOMAIN),
								'choices' => array(
										'full_bleed' => __('Full width', SPARK_THEME_TEXTDOMAIN),
										'partial_bleed' => __('Full width background image, contained content', SPARK_THEME_TEXTDOMAIN),
										'fully_contained' => __('Contained background image and content', SPARK_THEME_TEXTDOMAIN),
								),
								'other_choice' => 0,
								'save_other_choice' => 0,
								'default_value' => 'partial_bleed',
								'layout' => 'horizontal',
						),
						array(
								'key' => 'spark_panels_bg_opacity',
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
								'key' => 'spark_panels_field_bg_colour',
								'label' => __('Background Colour', SPARK_THEME_TEXTDOMAIN),
								'name' => 'bg_colour',
								'type' => 'select',
								'choices' => spark_panels_get_theme_palette(),
								'default_value' => 'transparent',
								'multiple' => 0,
						),
						array(
								'key' => 'spark_panels_field_bg_pos_x',
								'label' => __('Background Image Anchor (Horizontal)', SPARK_THEME_TEXTDOMAIN),
								'name' => 'bg_pos_x',
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
								'label' => __('Background Image Anchor (Vertical)', SPARK_THEME_TEXTDOMAIN),
								'name' => 'bg_pos_y',
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
								'label' => __('Additional Image', SPARK_THEME_TEXTDOMAIN),
								'name' => 'image',
								'type' => 'image',
								'instructions' => __('Some recipes will display an additional image alongside the content', SPARK_THEME_TEXTDOMAIN),
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
								'label' => __('Image Position', SPARK_THEME_TEXTDOMAIN),
								'name' => 'image_pos',
								'type' => 'radio',
								'instructions' => __('Position of the additional image', SPARK_THEME_TEXTDOMAIN),
								'choices' => array(
										'left' => __('Left', SPARK_THEME_TEXTDOMAIN),
										'right' => __('Right', SPARK_THEME_TEXTDOMAIN),
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
								'label' => __('Menu', SPARK_THEME_TEXTDOMAIN),
								'name' => 'menu',
								'type' => 'select',
								'instructions' => __('Each menu item in the selected menu will become a tile, with the description being used as the URL for the background image.', SPARK_THEME_TEXTDOMAIN),
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
								'label' => __('Post Type', SPARK_THEME_TEXTDOMAIN),
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
								'label' => __('Category', SPARK_THEME_TEXTDOMAIN),
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
								'label' => __('Maximum Number of Items', SPARK_THEME_TEXTDOMAIN),
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
								'label' => __('Items Per Row (Large Screen)', SPARK_THEME_TEXTDOMAIN),
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
								'label' => __('Items Per Row (Medium Screen)', SPARK_THEME_TEXTDOMAIN),
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
								'label' => __('Items Per Row (Small Screen)', SPARK_THEME_TEXTDOMAIN),
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
								'label' => __('Video URL', SPARK_THEME_TEXTDOMAIN),
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
				'title' => __('Call to Action', SPARK_THEME_TEXTDOMAIN),
				'fields' => array(
						array(
								'key' => 'spark_panels_cta_field_action_text',
								'label' => __('Action Text', SPARK_THEME_TEXTDOMAIN),
								'name' => 'action_text',
								'type' => 'text',
								'formatting' => 'html',
						),
						array(
								'key' => 'spark_panels_cta_field_destination',
								'label' => __('Destination URL', SPARK_THEME_TEXTDOMAIN),
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
				'title' => __('Post Types', SPARK_THEME_TEXTDOMAIN),
				'fields' => array(
						array(
								'key' => 'spark_panels_post_types',
								'label' => __('Post Types', SPARK_THEME_TEXTDOMAIN),
								'description' => __('This panel will be displayed on all posts of the selected types', SPARK_THEME_TEXTDOMAIN),
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
