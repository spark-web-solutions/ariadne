<?php
// Register custom block types (requires ACF Pro!)
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'spark_register_block_types');
	function spark_register_block_types() {
		acf_register_block_type(array(
				'name'              => 'panel',
				'title'             => __('Panel', SPARK_THEME_TEXTDOMAIN),
				'description'       => __('Insert a panel', SPARK_THEME_TEXTDOMAIN),
				'render_template'   => trailingslashit(get_template_directory()).'templates/blocks/panel.php',
				'category'          => 'formatting',
				'icon'              => 'layout',
				'keywords'          => array('panel'),
				'mode'              => 'auto',
				'align'             => 'full',
				'supports'          => array(
						'align' => array('center', 'full'),
				),
		));

		acf_register_block_type(array(
				'name'              => 'slider',
				'title'             => __('Slider', SPARK_THEME_TEXTDOMAIN),
				'description'       => __('Create a slider', SPARK_THEME_TEXTDOMAIN),
				'render_template'   => trailingslashit(get_template_directory()).'templates/blocks/slider.php',
				'category'          => 'formatting',
				'icon'              => 'align-wide',
				'keywords'          => array('slider', 'orbit', 'carousel'),
				'mode'              => 'auto',
				'align'             => 'full',
				'supports'          => array(
						'align' => false,
				),
		));

		acf_register_block_type(array(
				'name'			    => 'post-preview',
				'title'			    => __('Page/Post Preview', SPARK_THEME_TEXTDOMAIN),
				'description'	    => __('Insert a tile preview of a page or post', SPARK_THEME_TEXTDOMAIN),
				'render_template'   => trailingslashit(get_template_directory()).'templates/blocks/post-preview.php',
				'category'		    => 'theme',
				'icon'			    => 'format-aside',
				'keywords'		    => array('page', 'post', 'tile', 'card'),
				'mode'			    => 'auto',
				'align'			    => 'full',
				'supports'		    => array(
						'align' => false,
				),
		));

		acf_register_block_type(array(
				'name'			    => 'latest-post',
				'title'			    => __('Latest Post', SPARK_THEME_TEXTDOMAIN),
				'description'	    => __('Insert a tile preview of your most recent post', SPARK_THEME_TEXTDOMAIN),
				'render_template'   => trailingslashit(get_template_directory()).'templates/blocks/latest-post.php',
				'category'		    => 'theme',
				'icon'			    => 'format-aside',
				'keywords'		    => array('page', 'post', 'tile', 'card', 'latest'),
				'mode'			    => 'auto',
				'align'			    => 'full',
				'supports'		    => array(
						'align' => false,
				),
		));

		acf_register_block_type(array(
				'name'			    => 'custom-link-tile',
				'title'			    => __('Custom Link Tile', SPARK_THEME_TEXTDOMAIN),
				'description'	    => __('Insert a tile linking to a custom URL', SPARK_THEME_TEXTDOMAIN),
				'render_template'   => trailingslashit(get_template_directory()).'templates/blocks/custom-link-tile.php',
				'category'		    => 'theme',
				'icon'			    => 'admin-links',
				'keywords'		    => array('custom', 'link', 'url', 'tile', 'card'),
				'mode'			    => 'auto',
				'align'			    => 'full',
				'supports'		    => array(
						'align' => false,
				),
		));
	}

	add_action('admin_init', 'spark_block_meta'); // Has to be registered after init so that CPTs are registered
	function spark_block_meta() {
		if (function_exists("register_field_group")) {
			register_field_group(array(
					'id' => 'acf_block_panel_settings',
					'title' => __('Block: Panel', SPARK_THEME_TEXTDOMAIN),
					'fields' => array(
							array(
									'key' => 'spark_block_panel_field_panel',
									'label' => __('Panel', SPARK_THEME_TEXTDOMAIN),
									'name' => 'panel',
									'type' => 'post_object',
									'post_type' => array('panel'),
									'return_format' => 'id',
							),
					),
					'location' => array(
							array(
									array(
											'param' => 'block',
											'operator' => '==',
											'value' => 'acf/panel',
											'order_no' => 0,
											'group_no' => 0,
									),
							),
					),
			));

			register_field_group(array(
					'id' => 'acf_block_slider_settings',
					'title' => __('Block: Slider', SPARK_THEME_TEXTDOMAIN),
					'fields' => array(
							array(
									'key' => 'spark_block_slider_field_slider_height',
									'label' => 'Slider Height',
									'name' => 'slider_height',
									'type' => 'number',
									'append' => 'px',
									'default_value' => 250,
									'required' => true,
							),
							array(
									'key' => 'spark_block_slider_field_slides',
									'label' => __('Slides', SPARK_THEME_TEXTDOMAIN),
									'name' => 'slides',
									'type' => 'repeater',
									'layout' => 'row',
									'button_label' => __('Add Slide', SPARK_THEME_TEXTDOMAIN),
									'collapsed' => 'spark_block_slider_field_image',
									'sub_fields' => array(
											array(
													'key' => 'spark_block_slider_field_image',
													'label' => __('Image', SPARK_THEME_TEXTDOMAIN),
													'name' => 'image',
													'type' => 'image',
													'save_format' => 'array',
													'preview_size' => 'large',
													'library' => 'all',
											),
											array(
													'key' => 'spark_block_slider_field_heading',
													'label' => __('Heading', SPARK_THEME_TEXTDOMAIN),
													'name' => 'heading',
													'type' => 'text',
											),
											array(
													'key' => 'spark_block_slider_field_text',
													'label' => __('Text', SPARK_THEME_TEXTDOMAIN),
													'name' => 'text',
													'type' => 'textarea',
											),
											array(
													'key' => 'spark_block_slider_field_button_url',
													'label' => __('Button Link', SPARK_THEME_TEXTDOMAIN),
													'name' => 'button_url',
													'type' => 'url',
											),
											array(
													'key' => 'spark_block_slider_field_button_label',
													'label' => __('Button Label', SPARK_THEME_TEXTDOMAIN),
													'name' => 'button_label',
													'type' => 'text',
											),
									),
							),
					),
					'location' => array(
							array(
									array(
											'param' => 'block',
											'operator' => '==',
											'value' => 'acf/slider',
											'order_no' => 0,
											'group_no' => 0,
									),
							),
					),
			));

			register_field_group(array(
					'id' => 'acf_block_post_preview_settings',
					'title' => __('Block: Page/Post Preview', SPARK_THEME_TEXTDOMAIN),
					'fields' => array(
							array(
									'key' => 'spark_block_post_preview_field_post',
									'label' => __('Page/Post', SPARK_THEME_TEXTDOMAIN),
									'name' => 'post',
									'type' => 'post_object',
									'post_type' => array('page', 'post'),
									'return_format' => 'id',
							),
							array(
									'key' => 'spark_block_post_preview_field_image',
									'label' => __('Custom Image', SPARK_THEME_TEXTDOMAIN),
									'instructions' => __('If no image selected the selected page/post featured image will be used', SPARK_THEME_TEXTDOMAIN),
									'name' => 'image',
									'type' => 'image',
									'return_format' => 'url',
									'required' => false,
							),
							array(
									'key' => 'spark_block_post_preview_field_title',
									'label' => __('Custom Title', SPARK_THEME_TEXTDOMAIN),
											'instructions' => __('If left blank the selected page/post title will be used', SPARK_THEME_TEXTDOMAIN),
									'name' => 'title',
									'type' => 'text',
									'required' => false,
							),
							array(
									'key' => 'spark_block_post_preview_field_hide_excerpt',
									'label' => __('Hide Text Excerpt?', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hide_excerpt',
									'type' => 'true_false',
							),
							array(
									'key' => 'spark_block_post_preview_field_blurb',
									'label' => __('Custom Blurb', SPARK_THEME_TEXTDOMAIN),
									'instructions' => __('If left blank the selected page/post excerpt will be used', SPARK_THEME_TEXTDOMAIN),
									'name' => 'blurb',
									'type' => 'textarea',
									'required' => false,
									'conditional_logic' => array(
											'status' => 1,
											'rules' => array(
													array(
															'field' => 'spark_block_post_preview_field_hide_excerpt',
															'operator' => '==',
															'value' => 0,
													),
											),
											'allorany' => 'all',
									),
							),
					),
					'location' => array(
							array(
									array(
											'param' => 'block',
											'operator' => '==',
											'value' => 'acf/post-preview',
											'order_no' => 0,
											'group_no' => 0,
									),
							),
					),
			));

			register_field_group(array(
					'id' => 'acf_block_latest_post_settings',
					'title' => __('Block: Latest Post', SPARK_THEME_TEXTDOMAIN),
					'fields' => array(
							array(
									'key' => 'spark_block_latest_post_field_post_type',
									'label' => __('Post Type', SPARK_THEME_TEXTDOMAIN),
									'name' => 'post_type',
									'type' => 'select',
									'choices' => spark_blocks_get_post_types(),
							),
							array(
									'key' => 'spark_block_latest_post_field_hide_excerpt',
									'label' => __('Hide Text Excerpt?', SPARK_THEME_TEXTDOMAIN),
									'name' => 'hide_excerpt',
									'type' => 'true_false',
							),
					),
					'location' => array(
							array(
									array(
											'param' => 'block',
											'operator' => '==',
											'value' => 'acf/latest-post',
											'order_no' => 0,
											'group_no' => 0,
									),
							),
					),
			));

			register_field_group(array(
					'id' => 'acf_block_custom_link_tile_settings',
					'title' => __('Block: Custom Link Tile', SPARK_THEME_TEXTDOMAIN),
					'fields' => array(
							array(
									'key' => 'spark_block_custom_link_tile_field_image',
									'label' => __('Image', SPARK_THEME_TEXTDOMAIN),
									'name' => 'image',
									'type' => 'image',
									'return_format' => 'url',
									'required' => true,
							),
							array(
									'key' => 'spark_block_custom_link_tile_field_link',
									'label' => __('Link', SPARK_THEME_TEXTDOMAIN),
									'name' => 'link',
									'type' => 'url',
									'required' => true,
							),
							array(
									'key' => 'spark_block_custom_link_tile_field_title',
									'label' => __('Title', SPARK_THEME_TEXTDOMAIN),
									'name' => 'title',
									'type' => 'text',
									'required' => true,
							),
							array(
									'key' => 'spark_block_custom_link_tile_field_blurb',
									'label' => __('Blurb', SPARK_THEME_TEXTDOMAIN),
									'name' => 'blurb',
									'type' => 'textarea',
							),
					),
					'location' => array(
							array(
									array(
											'param' => 'block',
											'operator' => '==',
											'value' => 'acf/custom-link-tile',
											'order_no' => 0,
											'group_no' => 0,
									),
							),
					),
			));
		}
	}
}

function spark_blocks_get_post_types() {
	$args = array(
			'public' => true,
			'hierarchical' => false,
			'show_in_nav_menus' => true,
	);
	$post_types = get_post_types($args, 'objects');
	$choices = array(
			'post' => _x('Posts', 'Default post type label (plural)', SPARK_THEME_TEXTDOMAIN),
	);
	foreach ($post_types as $post_type) {
		$choices[$post_type->name] = $post_type->label;
	}
	return $choices;
}
