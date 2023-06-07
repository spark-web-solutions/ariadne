<?php
if (!function_exists('spark_body_classes')) {
	/**
	 * Generate series of helper CSS classes for the page wrapper based on the page content
	 * @param string $classes Custom classes to include
	 * @param boolean $post_atts Whether to include post slug and ID classes
	 * @return string
	 */
	function spark_body_classes($classes = '', $post_atts = true) {
		global $post;
		$class = array();

		$class[] = $classes;
		$class[] = (is_archive())       ? '' : 'not-archive';
		$class[] = (is_attachment())    ? 'attachment' : 'not-attachment';
		$class[] = (is_front_page())    ? '' : 'not-home';
		$class[] = (is_home())          ? '' : 'not-blog';
		$class[] = (is_page())          ? '' : 'not-page';
		$class[] = (is_search())        ? '' : 'not-search';
		$class[] = (is_single())        ? '' : 'not-single';
		$class[] = (is_sticky())        ? 'sticky' : 'not-sticky';
		$class[] = (is_tax())           ? 'tax' : 'not-tax';
		if ($post_atts == true && $post instanceof WP_Post) {
			$class[] = $post->post_type.'-'.$post->post_name;
		}

		$class = implode(' ', $class);
		return $class;
	}
}
