<?php
// Originally based on https://gist.github.com/veelen/408f09528d163008a1ef

if (!function_exists('spark_foundation_pagination')) {
	/**
	 * Auto-generate WP pagination links for post archives using Foundation markup
	 * @param int $p The number of page items to display before and after the current page
	 * @param bool $return Whether to return or echo the links
	 * @return null|string
	 */
	function spark_foundation_pagination($p = 2, $return = false) {
	    if (is_singular()) {
	        return null;
	    }
	    global $wp_query, $paged;
	    $max_page = $wp_query->max_num_pages;
	    $output = spark_generate_pagination_links($max_page, $p, $paged);
	    if ($return) {
	        return $output;
	    } else {
	        echo $output;
	    }
	}
}

if (!function_exists('spark_foundation_comments_pagination')) {
	/**
	 * Auto-generate WP comments pagination links for post archives using Foundation markup
	 * @param int $p The number of page items to display before and after the current page
	 * @param bool $return Whether to return or echo the links
	 * @return null|string
	 * @todo implement $p logic
	 */
	function spark_foundation_comments_pagination($p = 2, $return = false) {
		$links = paginate_comments_links(array('type' => 'array'));
		$output = '';
		foreach ($links as $link) {
			$output .= '<li>'.$link.'</li>';
		}
		if (!empty($output)) {
			$output = sprintf('<ul class="pagination" role="navigation" aria-label="%s">'.$output.'</ul>', _x('Pagination', 'ARIA label for comments pagination wrapper', 'spark_theme'));
		}
		if ($return) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('spark_generate_pagination_links')) {
	/**
	 * Generate WP pagination links using Foundation markup
	 * @param int $max_page Total number of pages
	 * @param int $p The number of page items to display before and after the current page
	 * @param int $paged Optional. Current page. Default 1.
	 * @param boolean $querystring Optional. Whether to generate links using querystring (?n=$i) instead of default /page/$i/ syntax. Default false.
	 * @return string Full HTML output of pagination links
	 */
	function spark_generate_pagination_links($max_page, $p = 2, $paged = null, $querystring = false) {
	    if ($max_page == 1) {
	        return null;
	    }
	    if (empty($paged)) {
	        $paged = 1;
	    }
	    if ($paged > 1) {
	        $output .= spark_p_link($paged - 1, $querystring, 'previous');
	    }
	    if ($paged > $p + 1) {
	        $output .= spark_p_link(1, $querystring, 'first');
	    }
	    if ($paged > $p + 2) {
	        $output .= '<li class="unavailable" aria-disabled="true"><a href="#">&hellip;</a></li>';
	    }
	    for ($i = $paged - $p; $i <= $paged + $p; $i++) { // Middle pages
	        if ($i == 1) {
	            $rel = 'rel="first"';
	        } elseif ($i == $max_page) {
	            $rel = 'rel="last"';
	        } else {
	            $rel = '';
	        }
	        if ($i > 0 && $i <= $max_page) {
	            $i == $paged ? $output .= "<li class='current' {$rel}><a href='#'>{$i}</a></li> " : $output .= spark_p_link($i, $querystring);
	        }
	    }
	    if ($paged < $max_page - $p - 1) {
	        $output .= '<li class="unavailable" aria-disabled="true"><a href="#">&hellip;</a></li>';
	    }
	    if ($paged < $max_page - $p) {
	        $output .= spark_p_link($max_page, $querystring, 'last');
	    }
	    if ($paged < $max_page) {
	        $output .= spark_p_link($paged + 1, $querystring, 'next');
	    }
	    if (!empty($output)) {
	        $output = sprintf('<ul class="pagination" role="navigation" aria-label="%s">'.$output.'</ul>', _x('Pagination', 'ARIA label for pagination wrapper', 'spark_theme'));
	    }
	    return $output;
	}
}

if (!function_exists('spark_p_link')) {
	/**
	 * Generate link to specific page for pagination
	 * @param int $i Page number to link to.
	 * @param boolean $querystring Optional. Whether to generate the links using querystring (true) or "pretty" links (false). Default false ("pretty" links).
	 * @param string $link_type Optional. Link type used to determine what title to display - accepts 'first', 'last', 'previous' or 'next'. Any other value (or if not set) will show page number.
	 * @return string Full pagination link including wrapping li
	 */
	function spark_p_link($i, $querystring = false, $link_type = '') {
	    global $wp_query;
	    $max_page = $wp_query->max_num_pages;
	    if ($i == 1 || $link_type == 'first') {
	        $rel = 'rel="first"';
	    } elseif ($link_type == 'last' || $i == $max_page) {
	        $rel = 'rel="last"';
	    } else {
	        $rel = '';
	    }
	    $linktext = $i;
	    switch ($link_type) {
	        case 'first':
	            $readabletitle = __('First', 'spark_theme');
	            break;
	        case 'last':
	        	$readabletitle = __('Last', 'spark_theme');
	            break;
	        case 'previous':
	        	$readabletitle = $linktext = __('‹ Previous', 'spark_theme');
	            $rel = 'rel="prev"';
	            break;
	        case 'next':
	        	$readabletitle = $linktext = __('Next ›', 'spark_theme');
	            $rel = 'rel="next"';
	            break;
	        default:
	        	/* translators: %d: the page number (e.g. 2) */
	        	$readabletitle = sprintf(__("Page %d", 'spark_theme'), $i);
	    }
	    if ($querystring) {
	        $link = add_query_arg('n', $i);
	    } else {
	        $link = esc_html(get_pagenum_link($i));
	    }
	    return '<li><a href="'.$link.'" '.$rel.' title="'.$readabletitle.'">'.$linktext.'</a></li>';
	}
}
