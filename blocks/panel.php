<?php
/**
 * Panel Block Template
 *
 * @param $block array The block settings and attributes.
 * @param $content string The block inner HTML (empty).
 * @param $is_preview bool True during AJAX preview.
 * @param $post_id (int|string) The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'panel-'.$block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" values.
$class = 'block-panel';
if (!empty($block['className'])) {
    $class .= ' '.$block['className'];
}

// Load values and assing defaults.
$panel_id = get_field('panel');
if ($panel_id) {
    $panel = get_post($panel_id);
    if (get_post_type($panel) == 'panel') {
        echo '<div id="'.$id.'" class="'.$class.'">'."\n";
        spark_show_panel($panel);
        echo '</div>'."\n";
    }
}
