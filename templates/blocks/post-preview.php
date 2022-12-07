<?php
/**
 * Post Preview Block Template
 *
 * @param $block array The block settings and attributes.
 * @param $content string The block inner HTML (empty).
 * @param $is_preview bool True during AJAX preview.
 * @param $post_id (int|string) The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'post-preview-'.$block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" values.
$class = 'block-post-preview';
if (!empty($block['className'])) {
    $class .= ' '.$block['className'];
}

// Load values and assign defaults.
$post_id = get_field('post');
$image = get_field('image');
if (is_int($image)) {
	$img = wp_get_attachment_image_src($image, 'large');
	if (is_array($img)) {
		$image = $img[0];
	} else {
		$image = '';
	}
}
$title = get_field('title');
$blurb = get_field('blurb');
$hide_excerpt = (bool)get_field('hide_excerpt');
if ($post_id) {
	if (defined('DOING_AJAX') && isset($_POST['post_id'])) {
		$parent_post = $_POST['post_id'];
	} elseif (is_admin()) {
		$parent_post = $_GET['post'];
	} else {
		global $post;
		$parent_post = $post->ID;
	}
	if ($post_id == $parent_post) {
		if (current_user_can('edit_posts')) {
			echo '<p>You can\'t embed content in itself!</p>';
		}
		return;
	}
    echo '<div id="'.$id.'" class="'.$class.'">'."\n";
    echo get_card(array('card' => 'post-preview', 'ID' => $post_id, 'hide_excerpt' => $hide_excerpt, 'image' => $image, 'title' => $title, 'blurb' => $blurb));
    echo '</div>'."\n";
}
