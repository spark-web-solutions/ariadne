<?php
/**
 * Tabs Block Template
 *
 * @var $block array The block settings and attributes.
 * @var $content string The block inner HTML (empty).
 * @var $is_preview bool True during AJAX preview.
 * @var $post_id (int|string) The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'tabs-'.$block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" values.
$class = 'block-tabs tabs';
if (!empty($block['className'])) {
    $class .= ' '.$block['className'];
}

if (have_rows('items')) {
?>
<ul id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" data-tabs>
<?php
	$i = 1;
	while (have_rows('items')) {
		the_row();
		$title = get_sub_field('title');
?>
	<li class="tabs-title<?php if (1 == $i) {echo ' is-active';} ?>">
		<a data-tabs-target="<?php echo esc_attr($id.'-'.$i); ?>"<?php if (1 == $i) { echo ' aria-selected="true"';} ?>><?php echo $title; ?></a>
	</li>
<?php
		$i++;
	}
?>
</ul>
<div class="tabs-content" data-tabs-content="<?php echo esc_attr($id); ?>">
<?php
	$i = 1;
	while (have_rows('items')) {
		the_row();
		$text = get_sub_field('text');
?>
	<div class="tabs-panel<?php if (1 == $i) {echo ' is-active';} if (!empty($block['align'])) {echo ' align'.$block['align']; } ?>" id="<?php echo esc_attr($id.'-'.$i); ?>">
		<?php echo wpautop($text); ?>
	</div>
<?php
		$i++;
	}
?>
</div>
<?php
}
