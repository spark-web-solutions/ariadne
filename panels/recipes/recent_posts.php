<?php
$panel_meta = spark_get_post_meta($panel->ID);
$small_count = $panel_meta['num_per_row_small'];
$medium_count = $panel_meta['num_per_row_medium'];
$large_count = $panel_meta['num_per_row_large'];

$args = array(
        'posts_per_page' => $panel_meta['num_items'],
        'post__not_in' => array($post->ID),
);
$category = $panel_meta['post_category'];
if (!empty($category)) {
    $args['tax_query'] = array(
            array(
                    'taxonomy' => 'category',
                    'terms' => $category,
                    'field' => 'term_id',
            ),
    );
}
$recent_posts = get_posts($args);
?>
<div class="cell">
<?php
spark_panel_title($panel);
spark_panel_content($panel);
?>
    <div class="grid-x grid-padding-x small-up-<?php echo $small_count; ?> medium-up-<?php echo $medium_count; ?> large-up-<?php echo $large_count; ?>">
<?php
foreach ($recent_posts as $recent_post) {
?>
    	<div class="cell">
    	    <div class="image" style="background-image: url(<?php echo spark_get_featured_image_url('medium', $recent_post); ?>);"></div>
            <h3 class="title"><?php echo $recent_post->post_title; ?></h3>
            <p class="content"><?php echo apply_filters('the_content', spark_extract($recent_post->post_content)); ?></p>
            <a class="button" href="<?php echo get_the_permalink($recent_post); ?>">Learn more</a>
    	</div>
<?php
}
?>
    </div>
</div>
