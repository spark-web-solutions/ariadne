<?php
add_action('widgets_init', 'spark_register_widgets');
if (!function_exists('spark_register_widgets')) {
	function spark_register_widgets() {
		register_sidebar(array(
				'name' => __('Primary Sidebar', 'spark_theme'),
				'id' => 'primary_sidebar',
				'before_widget' => '',
				'after_widget' => '',
				'before_title' => '<p class="h2">',
				'after_title' => '</p>',
		));
		register_sidebar(array(
				'name' => __('Footer', 'spark_theme'),
				'id' => 'footer',
				'before_widget' => '',
				'after_widget' => '',
				'before_title' => '<p class="h2">',
				'after_title' => '</p>',
		));
	}
}
