<style>
@media only screen {
    #article-<?php echo $ID; ?> .featured-image {background-image: url(<?php echo spark_get_featured_image_url('medium', $ID);?>);}
}
</style>
<article id="article-<?php echo $ID; ?>" <?php post_class('card post-preview small-24 cell bg4'); ?>>
    <a href="<?php echo get_permalink($ID); ?>">
        <div class="featured-image">
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
            <p class="h5"><?php echo get_the_title($ID); ?></p>
            <?php echo apply_filters('the_content', spark_post_extract($ID)); ?>
        </div>
        <p class="button">Read more</p>
    </a>
</article>
