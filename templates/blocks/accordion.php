<?php
/**
 * Accordion Block Template
 *
 * @var $block array The block settings and attributes.
 * @var $content string The block inner HTML (empty).
 * @var $is_preview bool True during AJAX preview.
 * @var $post_id (int|string) The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'accordion-'.$block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" values.
$class = 'block-accordion accordion';
if (!empty($block['className'])) {
    $class .= ' '.$block['className'];
}

if (have_rows('items')) {
?>
<ul id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" data-accordion>
<?php
	while (have_rows('items')) {
		the_row();
		$title = get_sub_field('title');
		$text = get_sub_field('text');
?>
	<li class="accordion-item" data-accordion-item>
		<a class="accordion-title"><?php echo $title; ?></a>
		<div class="accordion-content" data-tab-content>
			<?php echo wpautop($text); ?>
		</div>
	</li>
<?php
	}
?>
</ul>
<?php
}
