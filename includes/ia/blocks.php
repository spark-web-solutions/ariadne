<?php
// Register custom block types (requires ACF Pro!)
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'spark_register_block_types');
	function spark_register_block_types() {
		acf_register_block_type(array(
				'name'              => 'panel',
				'title'             => __('Panel', 'spark_theme'),
				'description'       => __('Insert a panel', 'spark_theme'),
				'render_template'   => locate_template('templates/blocks/panel.php'),
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
				'name'              => 'accordion',
				'title'             => __('Accordion', 'spark_theme'),
				'description'       => __('Build content as an accordion', 'spark_theme'),
				'render_template'   => locate_template('templates/blocks/accordion.php'),
				'category'          => 'layout',
				'icon'              => 'arrow-down',
				'keywords'          => array('accordion'),
				'mode'              => 'auto',
				'align'             => 'center',
				'supports'          => array(
						'align' => false,
				),
		));

		acf_register_block_type(array(
				'name'              => 'tabs',
				'title'             => __('Tabs', 'spark_theme'),
				'description'       => __('Build tabbed content', 'spark_theme'),
				'render_template'   => locate_template('templates/blocks/tabs.php'),
				'category'          => 'layout',
				'icon'              => 'category',
				'keywords'          => array('tab, tabs, tabbed'),
				'mode'              => 'auto',
				'align'             => 'center',
				'supports'          => array(
						'align' => array('center', 'full'),
				),
		));

		acf_register_block_type(array(
				'name'              => 'slider',
				'title'             => __('Slider', 'spark_theme'),
				'description'       => __('Create a slider', 'spark_theme'),
				'render_template'   => locate_template('templates/blocks/slider.php'),
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
				'title'			    => __('Page/Post Preview', 'spark_theme'),
				'description'	    => __('Insert a tile preview of a page or post', 'spark_theme'),
				'render_template'   => locate_template('templates/blocks/post-preview.php'),
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
				'name'			    => 'recent-posts',
				'title'			    => __('Recent Posts', 'spark_theme'),
				'description'	    => __('Insert a tile preview of your most recent posts', 'spark_theme'),
				'render_template'   => locate_template('templates/blocks/recent-posts.php'),
				'category'		    => 'theme',
				'icon'			    => 'format-aside',
				'keywords'		    => array('page', 'post', 'tile', 'card', 'latest', 'recent'),
				'mode'			    => 'auto',
				'align'			    => 'full',
				'supports'		    => array(
						'align' => false,
				),
		));

		acf_register_block_type(array(
				'name'			    => 'random-posts',
				'title'			    => __('Random Posts', 'spark_theme'),
				'description'	    => __('Insert a tile preview of random posts', 'spark_theme'),
				'render_template'   => locate_template('templates/blocks/random-posts.php'),
				'category'		    => 'theme',
				'icon'			    => 'format-aside',
				'keywords'		    => array('page', 'post', 'tile', 'card', 'random'),
				'mode'			    => 'auto',
				'align'			    => 'full',
				'supports'		    => array(
						'align' => false,
				),
		));

		acf_register_block_type(array(
				'name'			    => 'custom-link-tile',
				'title'			    => __('Custom Link Tile', 'spark_theme'),
				'description'	    => __('Insert a tile linking to a custom URL', 'spark_theme'),
				'render_template'   => locate_template('templates/blocks/custom-link-tile.php'),
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

	// Has to be registered after init so that CPTs are registered
	// We used to use admin_init, but repeater fields in blocks don't work unless the meta is registered on the front end as well
	add_action('wp_loaded', 'spark_block_meta');
	function spark_block_meta() {
		if (function_exists("register_field_group")) {
			register_field_group(array(
					'id' => 'acf_block_panel_settings',
					'title' => __('Block: Panel', 'spark_theme'),
					'fields' => array(
							array(
									'key' => 'spark_block_panel_field_panel',
									'label' => __('Panel', 'spark_theme'),
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
					'id' => 'acf_block_accordion_settings',
					'title' => __('Block: Accordion', 'spark_theme'),
					'fields' => array(
							array(
									'key' => 'spark_block_accordion_field_items',
									'label' => __('Items', 'spark_theme'),
									'name' => 'items',
									'type' => 'repeater',
									'layout' => 'row',
									'button_label' => __('Add Item', 'spark_theme'),
									'collapsed' => 'spark_block_accordion_field_title',
									'sub_fields' => array(
											array(
													'key' => 'spark_block_accordion_field_title',
													'label' => __('Title', 'spark_theme'),
													'name' => 'title',
													'type' => 'text',
													'required' => true,
											),
											array(
													'key' => 'spark_block_accordion_field_content',
													'label' => __('Text', 'spark_theme'),
													'name' => 'text',
													'type' => 'wysiwyg',
													'required' => true,
											)
									)
							)
					),
					'location' => array(
							array(
									array(
											'param' => 'block',
											'operator' => '==',
											'value' => 'acf/accordion',
											'order_no' => 0,
											'group_no' => 0
									)
							)
					)
			));

			register_field_group(array(
					'id' => 'acf_block_tabs_settings',
					'title' => __('Block: Tabs', 'spark_theme'),
					'fields' => array(
							array(
									'key' => 'spark_block_tabs_field_items',
									'label' => __('Items', 'spark_theme'),
									'name' => 'items',
									'type' => 'repeater',
									'layout' => 'row',
									'button_label' => __('Add Item', 'spark_theme'),
									'collapsed' => 'spark_block_tabs_field_title',
									'sub_fields' => array(
											array(
													'key' => 'spark_block_tabs_field_title',
													'label' => __('Title', 'spark_theme'),
													'name' => 'title',
													'type' => 'text',
													'required' => true,
											),
											array(
													'key' => 'spark_block_tabs_field_content',
													'label' => __('Text', 'spark_theme'),
													'name' => 'text',
													'type' => 'wysiwyg',
													'required' => true,
											)
									)
							)
					),
					'location' => array(
							array(
									array(
											'param' => 'block',
											'operator' => '==',
											'value' => 'acf/tabs',
											'order_no' => 0,
											'group_no' => 0
									)
							)
					)
			));

			register_field_group(array(
					'id' => 'acf_block_slider_settings',
					'title' => __('Block: Slider', 'spark_theme'),
					'fields' => array(
							array(
									'key' => 'spark_block_slider_field_slider_height',
									'label' => __('Slider Height', 'spark_theme'),
									'name' => 'slider_height',
									'type' => 'number',
									'append' => 'px',
									'default_value' => 250,
									'required' => true,
							),
							array(
									'key' => 'spark_block_slider_field_slides',
									'label' => __('Slides', 'spark_theme'),
									'name' => 'slides',
									'type' => 'repeater',
									'layout' => 'row',
									'button_label' => __('Add Slide', 'spark_theme'),
									'collapsed' => 'spark_block_slider_field_image',
									'sub_fields' => array(
											array(
													'key' => 'spark_block_slider_field_image',
													'label' => __('Image', 'spark_theme'),
													'name' => 'image',
													'type' => 'image',
													'save_format' => 'array',
													'preview_size' => 'large',
													'library' => 'all',
											),
											array(
													'key' => 'spark_block_slider_field_heading',
													'label' => __('Heading', 'spark_theme'),
													'name' => 'heading',
													'type' => 'text',
											),
											array(
													'key' => 'spark_block_slider_field_text',
													'label' => __('Text', 'spark_theme'),
													'name' => 'text',
													'type' => 'textarea',
											),
											array(
													'key' => 'spark_block_slider_field_button_url',
													'label' => __('Button Link', 'spark_theme'),
													'name' => 'button_url',
													'type' => 'url',
											),
											array(
													'key' => 'spark_block_slider_field_button_label',
													'label' => __('Button Label', 'spark_theme'),
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
					'title' => __('Block: Page/Post Preview', 'spark_theme'),
					'fields' => array(
							array(
									'key' => 'spark_block_post_preview_field_post',
									'label' => __('Page/Post', 'spark_theme'),
									'name' => 'post',
									'type' => 'post_object',
									'post_type' => array('page', 'post'),
									'return_format' => 'id',
							),
							array(
									'key' => 'spark_block_post_preview_field_image',
									'label' => __('Custom Image', 'spark_theme'),
									'instructions' => __('If no image selected the selected page/post featured image will be used', 'spark_theme'),
									'name' => 'image',
									'type' => 'image',
									'return_format' => 'url',
									'required' => false,
							),
							array(
									'key' => 'spark_block_post_preview_field_title',
									'label' => __('Custom Title', 'spark_theme'),
											'instructions' => __('If left blank the selected page/post title will be used', 'spark_theme'),
									'name' => 'title',
									'type' => 'text',
									'required' => false,
							),
							array(
									'key' => 'spark_block_post_preview_field_hide_excerpt',
									'label' => __('Hide Text Excerpt?', 'spark_theme'),
									'name' => 'hide_excerpt',
									'type' => 'true_false',
							),
							array(
									'key' => 'spark_block_post_preview_field_blurb',
									'label' => __('Custom Blurb', 'spark_theme'),
									'instructions' => __('If left blank the selected page/post excerpt will be used', 'spark_theme'),
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
					'id' => 'acf_block_recent_posts_settings',
					'title' => __('Block: Recent Posts', 'spark_theme'),
					'fields' => array(
							array(
									'key' => 'spark_block_recent_posts_field_post_type',
									'label' => __('Post Type', 'spark_theme'),
									'name' => 'post_type',
									'type' => 'select',
									'choices' => spark_blocks_get_post_types(),
							),
							array(
									'key' => 'spark_block_recent_posts_field_hide_excerpt',
									'label' => __('Hide Text Excerpt?', 'spark_theme'),
									'name' => 'hide_excerpt',
									'type' => 'true_false',
							),
							array(
									'key' => 'spark_block_recent_posts_field_num_posts',
									'label' => 'Number of Posts',
									'name' => 'num_posts',
									'type' => 'number',
									'default_value' => 3,
									'required' => true,
							),
							array(
									'key' => 'spark_block_recent_posts_field_per_row_large',
									'label' => 'Posts Per Row (Large Screens)',
									'name' => 'per_row_large',
									'type' => 'number',
									'default_value' => 3,
									'required' => true,
							),
							array(
									'key' => 'spark_block_recent_posts_field_per_row_medium',
									'label' => 'Posts Per Row (Medium Screens)',
									'name' => 'per_row_medium',
									'type' => 'number',
									'default_value' => 2,
									'required' => true,
							),
							array(
									'key' => 'spark_block_recent_posts_field_per_row_small',
									'label' => 'Posts Per Row (Small Screens)',
									'name' => 'per_row_small',
									'type' => 'number',
									'default_value' => 1,
									'required' => true,
							),
					),
					'location' => array(
							array(
									array(
											'param' => 'block',
											'operator' => '==',
											'value' => 'acf/recent-posts',
											'order_no' => 0,
											'group_no' => 0,
									),
							),
							array(
									array(
											'param' => 'block',
											'operator' => '==',
											'value' => 'acf/random-posts',
											'order_no' => 0,
											'group_no' => 0,
									),
							),
					),
			));

			register_field_group(array(
					'id' => 'acf_block_custom_link_tile_settings',
					'title' => __('Block: Custom Link Tile', 'spark_theme'),
					'fields' => array(
							array(
									'key' => 'spark_block_custom_link_tile_field_image',
									'label' => __('Image', 'spark_theme'),
									'name' => 'image',
									'type' => 'image',
									'return_format' => 'url',
									'required' => true,
							),
							array(
									'key' => 'spark_block_custom_link_tile_field_link',
									'label' => __('Link', 'spark_theme'),
									'name' => 'link',
									'type' => 'url',
									'required' => true,
							),
							array(
									'key' => 'spark_block_custom_link_tile_field_title',
									'label' => __('Title', 'spark_theme'),
									'name' => 'title',
									'type' => 'text',
									'required' => true,
							),
							array(
									'key' => 'spark_block_custom_link_tile_field_blurb',
									'label' => __('Blurb', 'spark_theme'),
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
			'show_in_nav_menus' => true,
	);
	$post_types = get_post_types($args, 'objects');
	$choices = array();
	foreach ($post_types as $post_type) {
		$choices[$post_type->name] = $post_type->label;
	}
	return $choices;
}
