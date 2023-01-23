<?php
/**
 * @var WP_Post $panel
 */
$panel_meta = spark_get_post_meta($panel->ID);

global $post;
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
	<div class="grid-x grid-margin-x">
		<div class="small-24 medium-12 cell">
<?php
spark_panel_title($panel, $panel_meta["destination"]);
?>
		</div>
		<div class="small-24 medium-12 cell medium-text-right">
<?php
if (!empty($panel_meta["destination"]) && !empty($panel_meta["action_text"])) {
?>
			<p><a href="<?php echo $panel_meta["destination"]; ?>" class="button"><?php echo $panel_meta["action_text"]; ?> <i class="las la-arrow-right"></i></a></p>
<?php
}
?>
		</div>
	</div>
<?php
spark_panel_content($panel);
?>
	<div class="carousel alignfull">
<?php
foreach ($recent_posts as $recent_post) {
?>
		<div class="recent-post slide-wrapper">
		<article class="card carousel-item small-24 cell">
			<a href="<?php echo get_the_permalink($recent_post); ?>">
				<div class="featured-image"><img src="<?php echo spark_get_featured_image_url('medium', $recent_post, true); ?>" alt=""></div>
				<div class="article-excerpt">
					<p class="h5"><?php echo get_the_title($recent_post); ?></p>
<?php
if (in_array(get_post_type($recent_post), array('events'))) {
	$date = get_post_type($recent_post) == 'events' ? spark_get_post_meta($recent_post, 'event_date') : get_the_time(get_option('date_format'), $recent_post);
?>
					<p class="subheader"><?php echo $date; ?></p>
<?php
}
?>
					<?php echo apply_filters('the_content', spark_post_extract($recent_post, 120)); ?>
				</div>
			</a>
			</article>
		</div>
<?php
}
?>
	</div>
</div>
