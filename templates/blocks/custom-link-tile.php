<?php
/**
 * Custom Link Tile Block Template
 *
 * @var $block array The block settings and attributes.
 * @var $content string The block inner HTML (empty).
 * @var $is_preview bool True during AJAX preview.
 * @var $post_id (int|string) The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'custom-link-tile-'.$block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" values.
$class = 'block-custom-link-tile';
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
echo spark_get_card(array('card' => 'tile', 'image' => $image, 'link' => $link, 'title' => $title, 'blurb' => $blurb));
echo '</div>'."\n";
