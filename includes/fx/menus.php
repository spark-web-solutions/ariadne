<?php
function spark_nav_menu(array $args = array(), $type = '') {
	if (!isset($args['menu_class'])) {
		$args['menu_class'] = 'menu';
	} elseif (!in_array('menu', explode(' ', $args['menu_class']))) {
		$args['menu_class'] .= ' menu';
	}
	switch ($type) {
		case 'dropdown':
			$args['items_wrap'] = '<ul id="%1$s" class="%2$s" data-dropdown-menu>%3$s</ul>';
			$args['menu_class'] .= ' dropdown';
			add_filter('nav_menu_css_class', function($classes, $item, $args, $depth) {
				if ($item->has_children) {
					$classes[] = 'is-dropdown-submenu-parent';
				}
				return $classes;
			}, 10, 4);
			break;
		case 'drilldown':
			$args['items_wrap'] = '<ul id="%1$s" class="%2$s" data-drilldown>%3$s</ul>';
			$args['menu_class'] .= ' vertical drilldown';
			break;
		case 'accordion':
			$args['items_wrap'] = '<ul id="%1$s" class="%2$s" data-accordion-menu data-submenu-toggle="true">%3$s</ul>';
			$args['menu_class'] .= ' vertical accordion-menu';
			break;
		default:
			break;
	}
	wp_nav_menu($args);
}

add_filter('nav_menu_css_class', 'spark_nav_menu_css_class', 10, 4);
function spark_nav_menu_css_class($classes, $item, $args, $depth) {
	if ($item->current || $item->current_item_ancestor) {
		$classes[] = 'is-active';
	}
	if (empty($item->url) || '#' == $item->url) {
		$classes[] = 'menu-text';
	}
	return $classes;
}

add_filter('nav_menu_submenu_css_class', 'spark_nav_menu_submenu_css_class', 10, 3);
function spark_nav_menu_submenu_css_class($classes, $args, $depth) {
	$classes[] = 'nested';
	$classes[] = 'menu';
	return $classes;
}
