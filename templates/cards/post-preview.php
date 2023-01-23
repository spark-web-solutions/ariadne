<?php
/**
 * Post Preview card
 * @var $ID integer Post ID
 * @var $image integer Optional. Image URL
 * @var $title string Optional. Card title
 * @var $blurb string Optional. Card blurb
 * @var $hide_excerpt bool Optional. Whether to hide the blurb
 */
$image = $image ?: spark_get_featured_image_url('large', $ID);
$title = $title ?: get_the_title($ID);
$blurb = $blurb ?: spark_post_extract($ID);
?>
<article id="article-<?php echo $ID; ?>" <?php post_class('card post-preview small-24 cell'); ?>>
    <a href="<?php echo get_permalink($ID); ?>">
        <div class="featured-image" style="background-image: url(<?php echo $image; ?>);">
<?php
if (in_array(get_post_type($ID), array('post', 'events', 'stories'))) {
    $date = get_post_type($ID) == 'events' ? spark_get_post_meta($ID, 'event_date') : get_the_time(get_option('date_format'), $ID);
?>
            <p class="subheader"><?php echo $date; ?></p>
<?php
}
?>
        </div>
        <div class="article-excerpt">
            <p class="h5"><?php echo $title; ?></p>
<?php
if (!isset($hide_excerpt) || !$hide_excerpt) {
	echo wpautop($blurb);
}
?>
        </div>
        <p class="text-right read-more"><?php _e('Read More', 'spark_theme'); ?></p>
    </a>
</article>
