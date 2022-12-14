<?php
/**
 * Post Preview Block Template
 *
 * @var $block array The block settings and attributes.
 * @var $content string The block inner HTML (empty).
 * @var $is_preview bool True during AJAX preview.
 * @var $post_id (int|string) The post ID this block is saved to.
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
$embed_id = get_field('post');
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
if ($embed_id) {
	if ($embed_id == $post_id) {
		if (current_user_can('edit_posts')) {
			echo '<p>You can\'t embed content in itself!</p>';
		}
		return;
	}
    echo '<div id="'.$id.'" class="'.$class.'">'."\n";
    echo spark_get_card(array('card' => 'post-preview', 'ID' => $embed_id, 'hide_excerpt' => $hide_excerpt, 'image' => $image, 'title' => $title, 'blurb' => $blurb));
    echo '</div>'."\n";
}
