<?php
add_filter('pre_get_posts','search_filter_pages');
function search_filter_pages($query) {
	if (!is_admin() && $query->is_search) {
		if (is_plugin_active('wordpress-seo/wp-seo.php')) {
			$query->set('meta_query', array(
					'relation' => 'OR',
					array(
							'key'     => '_yoast_wpseo_meta-robots-noindex',
							'compare' => 'NOT EXISTS',
					),
					array(
							'key'     => '_yoast_wpseo_meta-robots-noindex',
							'value'   => 1,
							'compare' => '!=',
					),
			));
		}
	}
	return $query;
}
