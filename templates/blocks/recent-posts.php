<?php
/**
 * Recent Posts Block Template
 *
 * @var $block array The block settings and attributes.
 * @var $content string The block inner HTML (empty).
 * @var $is_preview bool True during AJAX preview.
 * @var $post_id (int|string) The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'recent-posts-'.$block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" values.
$class = 'block-recent-posts';
if (!empty($block['className'])) {
    $class .= ' '.$block['className'];
}

// Load values and assign defaults.
$post_type = get_field('post_type');
$hide_excerpt = (bool)get_field('hide_excerpt');
$num_posts = (int)get_field('num_posts');
$per_row_large = (int)get_field('per_row_large');
$per_row_medium = (int)get_field('per_row_medium');
$per_row_small = (int)get_field('per_row_small');
if ($post_type) {
	$args = array(
			'posts_per_page' => $num_posts,
			'post_type' => $post_type,
			'post__not_in' => array($post_id),
	);
	$latest = get_posts($args);
	if (count($latest) > 0) {
	    echo '<div id="'.$id.'" class="grid-container '.$class.'">'."\n";
	    echo '<div class="grid-x grid-margin-x small-up-'.$per_row_small.' medium-up-'.$per_row_medium.' large-up-'.$per_row_large.'" data-equalizer>'."\n";
		foreach ($latest as $recent_post) {
			$post_id = $recent_post->ID;
		    echo spark_get_card(array('card' => 'post-preview', 'ID' => $post_id, 'hide_excerpt' => $hide_excerpt));
		}
	    echo '</div>'."\n";
	    echo '</div>'."\n";
	}
}
