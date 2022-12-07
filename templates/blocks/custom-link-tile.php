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
$image = get_field('image');
if (is_int($image)) {
	$image = array_shift(wp_get_attachment_image_src($image, 'large'));
}
$link = get_field('link');
$title = get_field('title');
$blurb = get_field('blurb');
echo '<div id="'.$id.'" class="'.$class.'">'."\n";
echo get_card(array('card' => 'tile', 'image' => $image, 'link' => $link, 'title' => $title, 'blurb' => $blurb));
echo '</div>'."\n";
