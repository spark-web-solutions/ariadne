<?php
/**
 * @version F.1.0
 *
 */

// -------------------------------
// 1. setup the post and the data
// -------------------------------
global $post;
extract(Spark_Theme::setup_data(__FILE__));
$t_period = LONG_TERM;
if (is_post_type_archive() || (is_home() && !is_front_page())) {
    $title = $archive_page->post_title;
    $images = spark_get_hero_images($archive_page);
} elseif (is_archive()) {
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    if (is_tax() && $term instanceof WP_Term) {
        $title = $term->name;
        $images = spark_get_hero_images($term);
    } else {
        $title = $archive_page->post_title;
        $images = spark_get_hero_images($archive_page);
    }
} elseif (is_search()) {
    $title = 'Search Results';
    $default_featured_image = spark_get_theme_mod('default_featured_image');
    $images = array(
            'large' => $default_featured_image,
            'medium' => $default_featured_image,
            'small' => $default_featured_image,
    );
} else {
    $title = get_the_title();
    $images = spark_get_hero_images();
}

if (!empty($meta['hero_title'])) {
    $title = $meta['hero_title'];
}
if (!empty($meta['hero_video'])) {
    $video = $meta['hero_video'];
}

// -------------------------------------------
// 2. setup local css transient for this file
// -------------------------------------------
$t_args = array('name' => 'css', 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (!Spark_Transients::use_transients()) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();
?>
<style>
/* START: <?php echo $file.' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {
    #row-hero {text-shadow: 0.05rem 0.05rem 0.05rem rgba(86, 86, 86, 0.4);}
    #row-hero .hero-content {bottom: 1rem; left: 0; position: absolute; margin: 0 0.9375rem;}
    #row-hero h1 {z-index: 99; position: relative;}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
    #row-hero .navigation {position:relative; z-index:99;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */ }
@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}
@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo $file; ?> */
</style>
<?php
    $ob = ob_get_clean();
    set_transient($transient, $ob, $t_period);
}
echo $ob;
unset($ob, $t_args, $transient);

// -------------------------------------------
// 3. setup local css transient for this post
// -------------------------------------------
$t_args = array('name' => 'css'.$transient_suffix, 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (!Spark_Transients::use_transients()) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();
    if (!empty($images['large'])) {
        $bgpos_x_large = $meta['hero_bgpos_x'];
        $bgpos_y_large = $meta['hero_bgpos_y'];
        $bgpos_x_medium = !empty($meta['hero_bgpos_x_medium']) ? $meta['hero_bgpos_x_medium'] : $bgpos_x_large;
        $bgpos_y_medium = !empty($meta['hero_bgpos_y_medium']) ? $meta['hero_bgpos_y_medium'] : $bgpos_y_large;
        $bgpos_x_small = !empty($meta['hero_bgpos_x_small']) ? $meta['hero_bgpos_x_small'] : $bgpos_x_large;
        $bgpos_y_small = !empty($meta['hero_bgpos_y_small']) ? $meta['hero_bgpos_y_small'] : $bgpos_y_large;
?>
<style>
/* START: <?php echo $file.' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {
    #row-hero {background-color: <?php echo spark_get_theme_mod('colour'.$meta['hero_bgcolour']); ?>;}
    #row-hero:before {background-image: url(<?php echo $images['small']; ?>); background-position: <?php echo $bgpos_x_small.' '.$bgpos_y_small; ?>; opacity: <?php echo $meta['bg_opacity']; ?>;}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
    #row-hero:before {background-image: url(<?php echo $images['medium']; ?>); background-position: <?php echo $bgpos_x_medium.' '.$bgpos_y_medium; ?>;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
    #row-hero:before {background-image: url(<?php echo $images['large']; ?>); background-position: <?php echo $bgpos_x_large.' '.$bgpos_y_large; ?>;}
}
@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}
@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo $file; ?> */
</style>
<?php
    }
    $ob = ob_get_clean();
    set_transient($transient, $ob, $t_period);
}
echo $ob;
unset($ob, $t_args, $transient);

// ----------------------------
// 4. setup output transient/s
// ----------------------------
$t_args = array('name' => 'markup'.$transient_suffix, 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (!Spark_Transients::use_transients()) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();

    // section content - start
    echo '<!-- START: '.$file.' -->'."\n";

    // section content
    if (!empty($video)) {
?>
<div class="hero-video">
    <video loop autoplay muted playsinline preload="auto"><source src="<?php echo $video; ?>" type="video/mp4"></video>
</div>
<?php
    }
?>
<div class="navigation show-for-medium cell">
    <ul class="menu align-right">
        <?php spark_menu('main'); ?>
    </ul>
</div>
<div class="hero-content">
<?php
    if (empty($meta['hide_title'])) {
        echo '<h1>'.$title.'</h1>'."\n";
    }
    if (!empty($meta['hero_tagline_desc'])) {
        echo '<p class="tagline">'.$meta['hero_tagline_desc'].'</p>'."\n";
    }
    if (!empty($meta['hero_destination']) && !empty($meta['hero_action_text'])) {
        echo '<a class="button cta" href="'.$meta['hero_destination'].'">'.$meta['hero_action_text'].'</a>'."\n";
    }
?>
</div>
<?php
    // section content - end
    echo '<!-- END:'.$file.' -->'."\n";

    $ob = ob_get_clean();
    set_transient($transient, $ob, $t_period);
}
echo $ob;
unset($ob, $t_args, $transient);
