<?php
$panel_meta = spark_get_post_meta($panel->ID);
$small_count = $panel_meta['num_per_row_small'];
$medium_count = $panel_meta['num_per_row_medium'];
$large_count = $panel_meta['num_per_row_large'];

$args = array(
        'posts_per_page' => $panel_meta['num_items'],
        'post__not_in' => array($post->ID),
);
$post_type = $panel_meta['post_type'];
if (!empty($post_type)) {
    $args['post_type'] = $post_type;
    if ($post_type == 'events') {
        $args['orderby'] = 'meta_value';
        $args['order'] = 'ASC';
        $args['meta_key'] = 'event_date_start';
        $args['meta_query'] = array(
                array(
                        'key' => 'event_date_start',
                        'value' => current_time('Ymd'),
                        'compare' => '>',
                ),
        );
    }
}
$category = $panel_meta['post_category'];
if (!empty($category) && $post_type == 'post') {
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
spark_panel_title($panel, $panel_meta["destination"]);
spark_panel_content($panel);
?>
    <div class="grid-x grid-margin-x small-up-<?php echo $small_count; ?> medium-up-<?php echo $medium_count; ?> large-up-<?php echo $large_count; ?>">
<?php
foreach ($recent_posts as $recent_post) {
?>
    	<div class="cell bg4">
            <a href="<?php echo get_the_permalink($recent_post); ?>">
                <div class="image relative" style="background-image: url(<?php echo spark_get_featured_image_url('medium', $recent_post, true); ?>);">
<?php
if (in_array(get_post_type($recent_post), array('post', 'events', 'stories'))) {
    $date = get_post_type($recent_post) == 'events' ? spark_get_post_meta($recent_post, 'event_date') : get_the_time(get_option('date_format'), $recent_post);
?>
                    <p class="subheader h5"><?php echo $date; ?></p>
<?php
}
?>
                </div>
                <div class="article-excerpt">
                    <p class="h5"><?php echo get_the_title($recent_post); ?></p>
                    <?php echo apply_filters('the_content', spark_post_extract($recent_post)); ?>
                </div>
                <p class="button">Read more</p>
            </a>
    	</div>
<?php
}
?>
    </div>
<?php
if (!empty($panel_meta["destination"]) && !empty($panel_meta["action_text"])) {
?>
    <p><a href="<?php echo $panel_meta["destination"]; ?>" class="button"><?php echo $panel_meta["action_text"]; ?></a></p>
<?php
}
?>
</div>
