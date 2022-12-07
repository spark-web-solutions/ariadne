<?php
$blocked = array();
if (defined('SPARK_SUPER_SEARCH') && SPARK_SUPER_SEARCH) {
    foreach ($blocked as $block) {
        if (strstr($_SERVER['HTTP_REFERER'], $block)) {
            die();
        }
    }

    // Build the redirect URL
    $string = strtolower(trim(str_replace(array('-', '/'), ' ', $_SERVER['REQUEST_URI'])));
    if (true == strstr($string, '?')) {
        $string = substr($string, 0, strpos($string, '?')-1);
    }
    $url = '/?s='.urlencode($string).'&msg=404'."\n";

    // Redirect to search results
    wp_redirect($url);
    exit;
} else {
	get_header();
	get_template_part('templates/sections/404');
    get_footer();
}
