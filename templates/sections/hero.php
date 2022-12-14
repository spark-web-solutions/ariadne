<?php
// ------------------
// 1. Setup the data
// ------------------
/**
 * @var string $file Current file
 * @var array $meta Meta data for current post
 * @var array $ancestors List of ancestor IDs for current post
 * @var string $ancestor_string Underscore-separated list of ancestor IDs for current post
 * @var integer $archive_page ID of the archive page that corresponds to the current request
 * @var string $transient_suffix Unique transient suffix for current section and post
 **/
extract(spark_setup_data(__FILE__));

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
// 2. setup local css transient for this post
// -------------------------------------------
$t_args = array('name' => 'css'.$transient_suffix, 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (false === ($ob = get_transient($transient)) || !Spark_Transients::use_transients($ob)) {
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
/* END: <?php echo $file; ?> */
</style>
<?php
    }
    $ob = ob_get_clean();
    if (Spark_Transients::use_transients($ob)) {
    	Spark_Transients::set($transient, $ob);
    }
}
echo $ob;
unset($ob, $t_args, $transient);

// -------------------
// 3. Generate output
// -------------------
$t_args = array('name' => 'markup'.$transient_suffix, 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (false === ($ob = get_transient($transient)) || !Spark_Transients::use_transients($ob)) {
	ob_start();

	// section content - start
	echo '<!-- START: '.$file.' -->'."\n";

	// section content
?>
<div id="row-hero" class="grid-container full">
    <div id="row-inner-hero" class="relative hero-height">
<?php
	if (!empty($video)) {
?>
		<div class="hero-video">
		    <video loop autoplay muted playsinline preload="auto"><source src="<?php echo $video; ?>" type="video/mp4"></video>
		</div>
<?php
    }
?>
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
	</div>
</div>
<?php
    // section content - end
    echo '<!-- END:'.$file.' -->'."\n";

	$ob = ob_get_clean();
	if (Spark_Transients::use_transients($ob)) {
		Spark_Transients::set($transient, $ob);
	}
}
echo $ob;
unset($ob, $t_args, $transient);
