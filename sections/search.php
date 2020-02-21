<?php
/**
 * BB Search @version 1.2
 * @author Chris Chatterton
 * @author Mark Parnell
 */
?>
<style>
/* START: <?php echo date("Y-m-d H:i:s"); ?> */
@media only screen {
	#row-search .cell {margin-bottom: 0.9375rem;}
	#row-search .cell > a {display: inline-block; position: relative; height: 100%;}

    #row-search ul#search_results {margin-left: -.9375rem;}

	#row-search .search-counts {margin-left: 0px; list-style: none; clear: both;}
	#row-search .search-counts > li {border-left: 1px solid rgba(0, 0, 0, 0.2); display: inline-block; height: 0.8rem; line-height: 0.7rem; margin-left: 0.5rem; padding-left: 0.5rem;}
	#row-search .search-counts > li:first-of-type{border-left: none; margin-left: 0px; padding-left: 0px;}

	/* search form */
	#row-search .search-form {border: 2px solid rgba(<?php echo spark_convert_colour(spark_get_theme_mod(ns_.'colour9')); ?>,1); clear: both; display: block; max-width: 500px; margin-top: 0.5rem;}
	#row-search .search-field {border: none; border-radius: 0; box-shadow: none; display: inline-block; float: left; height: 2.5rem; max-width: 75%;}
	#row-search .search-submit {background-color:<?php echo spark_get_theme_mod('colour5');?>; border: 0px solid #fff; border-radius: 0; color: #fff; display: inline-block; height: 2.5rem; width: 25%;}

    /*#row-search .search_wrapper {padding-bottom: 1rem;}
    #row-search .search_wrapper .search-form { border: 2px solid <?php echo spark_get_theme_mod('colour6');?>;}
    #row-search .search_wrapper .search-form input { margin-bottom:0; border-radius: 0;}
    #row-search .search_wrapper .search-form input[type=submit] {position: absolute;top: 0;right: 0;padding: 0.39rem 1rem;border:none; border-radius: 0; background-color: <?php echo spark_get_theme_mod('colour6');?>; color:<?php echo spark_get_theme_mod('colour1');?>;}
    #row-search .button {position: absolute;margin-top: -2.7rem;background-color: transparent;}
    #row-search .search_wrapper .search-form input {padding-left: 2rem;}*/

	#row-search .highlight {background-color: <?php echo spark_get_theme_mod('colour1'); ?>; padding: 1px 4px 1px 2px; color: <?php echo spark_get_theme_mod('colour9'); ?>; border-bottom: 1px solid rgba(<?php echo spark_convert_colour(spark_get_theme_mod(ns_.'colour2')); ?>, 0.1);}
	#row-search .count {background-color: rgba(<?php echo spark_convert_colour(spark_get_theme_mod(ns_.'colour3')); ?>,1); border-radius: 50%; bottom: 0.9375rem; right: 0.9375rem; color: #fff; display: block; font-size: 0.8rem; height: 1.5rem; line-height: 1.4rem; text-align: center; width: 1.5rem; margin-bottom: 0.5rem;}
	#row-search .post_type {top: 0.9375rem; right: 0.9375rem; color: #FFF; font-weight: 600; text-transform: uppercase; font-size: 0.8rem;}
	#row-search .filtered {background: rgba(<?php echo spark_convert_colour(spark_get_theme_mod(ns_.'colour3')); ?>,0.2);padding: 0.2rem 0.5rem;color:rgba(<?php echo spark_convert_colour(spark_get_theme_mod(ns_.'colour3')); ?>,1)}

	.background-image {background-size: cover; background-position: center center; background-color: rgba(0,0,0,0.1)}
	.absolute {position: absolute;}
	.relative {position: relative;}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
	#row-search .card > a.wrapper {min-height: 14rem;}
	#row-search .cell {margin-bottom: 1rem;}
	#row-search	.search-form {max-width: 60%;}
	body.search #row-inner-hero {height: 200px;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
	#row-search .cell {margin-bottom: 1.2rem;}
	#row-search	.search-form {max-width: 40%;}
	body.search #row-inner-hero {height: 300px;}
}

/* END */
</style>
<?php

// -------------------------------
// 1. setup the post and the data
// -------------------------------
global $post;
extract(Spark_Theme::setup_data(__FILE__));
$t_period = LONG_TERM;

$args = array(
        'public' => true,
        'exclude_from_search' => false,
);
$post_types = get_post_types($args);

// -------------------------------------
// 2. setup the custom search variables
// -------------------------------------
$search_args = array(
        'post_type' => $post_types,
        'msg' => array(
                '404' => 'We can\'t find what you are looking for. Try a different keyword:',
        ),
        'language' => array(
                'wp' => 'match',
                'bb' => 'relate to',
                'popular_search' => 'Can\'t find what you are looking for? Try one of these popular searches...',
        ),
);

if (is_user_logged_in()) {
    $additional_types = array(); // add private post types to include in search here
    foreach ($additional_types as $type) {
        array_push($search_args['post_type'], $type);
    }
}
if (isset($_GET['post_type']) && in_array($_GET['post_type'], $search_args['post_type'])) {
    $search_args['post_type'] = array($_GET['post_type']); // set this to filter to just 1 post type
}

// lets track what is being searched for
if (empty($_GET['msg'])) {
    // make sure our tracker is registered
    $spark_tracking = get_option('spark_tracking');
    if (empty($spark_tracking)) {
        $spark_tracking = array();
    }
    array_push($spark_tracking, 'spark_search_tracking_'.date("Ymd"));
    $spark_tracking = array_unique($spark_tracking);
    update_option('spark_tracking', $spark_tracking);

    // track the search string per day.
    $spark_search_tracking_date = get_option('spark_search_tracking_'.date("Ymd"));
    if (empty($spark_search_tracking_date)) {
        $spark_search_tracking_date = array();
    }
    $spark_search_tracking_date[$search_string][] = $_SERVER;
    update_option('spark_search_tracking_'.date("Ymd"), $spark_search_tracking_date);
}

// do we already have the results for this search string?
$t_args = array('name' => $search_string.'_markup_'.implode('_', $search_args['post_type']), 'file' => $file);
$markup_transient = Spark_Transients::name($t_args);
if (!Spark_Transients::use_transients()) {
    delete_transient($markup_transient);
}
if (false === ($markup = get_transient($markup_transient))) {
    $results = array();
    $markup = array();
    $tracking = array();

    // get the BB Super Search results
    if (defined('SPARK_SUPER_SEARCH') && SPARK_SUPER_SEARCH) {
        $t_args = array('name' => $search_string.'_spark_'.implode('_', $search_args['post_type']), 'file' => $file);
        $transient = Spark_Transients::name($t_args);
        if (!Spark_Transients::use_transients()) {
            delete_transient($transient);
        }
        if (false === ($results['bb'] = get_transient($transient))) {
            $args = array(
                    'posts_per_page' => -1,
                    'post_type' => $search_args['post_type'],
                    'meta_query' => array(
                            array(
                                    'key'     => 'keywords',
                                    'value'   => $search_string,
                                    'compare' => 'LIKE',
                            ),
                    ),
            );
            $results['bb'] = new WP_Query( $args );
            set_transient( $transient, $results['bb'], $t_period);
            unset($transient);
        }
    }

    // get the WP search results
    $t_args = array('name' => $search_string.'_wp_'.implode('_', $search_args['post_type']), 'file' => $file);
    $transient = Spark_Transients::name($t_args);
    if (!Spark_Transients::use_transients()) {
        delete_transient($transient);
    }
    if (false === ($results['wp'] = get_transient($transient))) {
        $args = array(
                's' => $search_string,
                'posts_per_page' => -1,
                'post_type' => $search_args['post_type'],
        		'orderby' => 'relevance',
        );
        $results['wp'] = new WP_Query( $args );
        set_transient( $transient, $results['wp'], $t_period);
        unset($transient);
    }

    // process our results & buid some markup
    foreach($results as $key => $result) {
        $markup[$key]['count'] = count($results[$key]->posts);
        if ($markup[$key]['count'] > 0) {
            // setup the intro
            $markup[$key]['intro'] .= '<div class="small-24 medium-24 large-24 cell">'."\n";
            $markup[$key]['intro'] .= '    <span class="h2">We found '.$markup[$key]['count'].' page/s that '.$search_args['language'][$key].' your search request</span>'."\n";
            $markup[$key]['intro'] .= '</div>'."\n";

            // track what we process
            if (!is_array($tracking[$key]['post_types'])) {
                $tracking[$key]['post_types'] = array();
            }
            if (!is_array($tracking['shown'])) {
                $tracking['shown'] = array();
            }

            // build the search cards
            $markup[$key]['cards'] .= '<div class="small-24 medium-24 large-24 cell">'."\n";
            $markup[$key]['cards'] .= '    <ul id="search_results" class="grid-x grid-margin-x small-up-1 medium-up-2 large-up-3 cards no-bullet">'."\n";
            foreach ($results[$key]->posts as $card) {
                if(!in_array($card->ID, $tracking['shown'])) {
                    $tracking[$key]['post_types'][$card->post_type]++;
                    $tracking['shown'][] = $card->ID;
                    $markup[$key]['cards'] .= get_card('ID='.$card->ID.'&card=search&string='.$search_string);
                }
            }
            $markup[$key]['cards'] .= '    </ul>'."\n";
            $markup[$key]['cards'] .= '</div>'."\n";

            // setup post_type filters
            if (count($tracking[$key]['post_types']) > 0) {
                $markup[$key]['post_types'] .= '<div class="small-24 medium-24 large-24 cell">'."\n";
                $markup[$key]['post_types'] .= '<ul class="search-counts">';
                if(count($tracking[$key]['post_types']) > 1) {
                    $markup[$key]['post_types'] .= '<li><a href="/?s='.$search_string.'">All</a> ('.count( $results[$key]->posts ).')</li>';
                    foreach ($tracking[$key]['post_types'] as $post_type => $count) {
                        if( $post_type !== 'page' ) {
                            $markup[$key]['post_types'] .= '<li><a href="/?s='.$search_string.'&post_type='.$post_type.'">'.ucfirst( $post_type ).'</a> ('.$count.')</li>';
                        }
                    }
                } elseif (isset($_GET['post_type']) && count($tracking[$key]['post_types']) == 1){
                    $markup[$key]['post_types'] .= '<li>Showing: <a class="filtered" href="/?s='.$search_string.'">'.ucfirst( $post_type ).' X</a></li>';
                }
                $markup[$key]['post_types'] .= '</ul>';
                $markup[$key]['post_types'] .= '</div>'."\n";
            }
            $markup[$key]['post_types'];
        }
    }
    set_transient($markup_transient, $markup, $t_period);
}

// -----------------------------
// build our page by search rows
// -----------------------------

// Any messsages?
if (isset($search_args['msg'][$_GET['msg']])) {
    echo '<div class="small-24 medium-24 large-24 cell">'."\n";
    echo '<span class="h1 msg">'.$search_args['msg'][$_GET['msg']].'</span>'."\n";
    echo '</div>'."\n";
}

// search box
echo '<div class="small-24 medium-24 large-24 cell">'."\n";
get_search_form();
echo '</div>'."\n";

// and lets echo our markup :)
foreach($markup as $key => $result) {
    if ($markup[$key]['count'] > 0){
        echo $markup[$key]['intro'];
        echo $markup[$key]['post_types'];
        echo $markup[$key]['cards'];
    }
}
