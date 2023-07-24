<?php
/**
 * Map Block Template
 *
 * @var $block array The block settings and attributes.
 * @var $content string The block inner HTML (empty).
 * @var $is_preview bool True during AJAX preview.
 * @var $post_id (int|string) The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'map-'.$block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" values.
$class = 'block-map';
if (!empty($block['className'])) {
    $class .= ' '.$block['className'];
}

// Load values and assign defaults.
$location = get_field('location');
$width = get_field('width').'px';
$height = get_field('height').'px';
$zoom = get_field('zoom');
spark_map(str_replace('-', '_', $id), $location['lat'], $location['lng'], array(), $width, $height, $class, $zoom);
