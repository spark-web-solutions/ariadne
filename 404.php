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

    $types = array('jpg','css','html','png');
    $trackas = '_';

    foreach ($types as $type) {
        if (strstr($_SERVER['REQUEST_URI'], $type)) {
            $spark_tracking = unserialize(get_option('spark_tracking'));
            if (empty($spark_tracking)) {
                $spark_tracking = array();
            }
            if (!in_array('spark_404_'.$type.'_tracking', $spark_tracking)) {
                array_push($spark_tracking, 'spark_404_'.$type.'_tracking');
                $spark_tracking = array_unique($spark_tracking);
                update_option('spark_tracking', serialize($spark_tracking));
            }
            $trackas = '_'.$type.'_';
        }
    }

    $spark_tracking = unserialize(get_option('spark_tracking'));
    if(empty($spark_tracking)) {
        $spark_tracking = array();
    }
    array_push($spark_tracking, 'spark_404_tracking');
    $spark_tracking = array_unique($spark_tracking);
    update_option('spark_tracking', serialize($spark_tracking));

    $spark_404_tracking = unserialize(get_option('spark_404'.$trackas.'tracking'));
    if(empty($spark_404_tracking)) {
        $spark_404_tracking = array();
    }
    $spark_404_tracking[trim($_SERVER['REQUEST_URI'])][] = $_SERVER;
    update_option('spark_404'.$trackas.'tracking', serialize($spark_404_tracking));

    // Redirect to search results
    wp_redirect($url);
    exit;
} else {
    get_header();
    Spark_Theme::section('name=content&file=404.php');
    get_footer();
}
