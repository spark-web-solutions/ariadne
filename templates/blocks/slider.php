<?php
/**
 * Slider Block Template.
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during AJAX preview.
 * @var (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'slider-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'block-slider';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}
if ($is_preview) {
	$className .= ' is-admin';
}
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php
if (have_rows('slides')) {
	$slides = array();
?>
	<div class="slides">
<?php
	$slide_count = 0;
	while (have_rows('slides')) {
		the_row();
		$image = get_sub_field('image');
		$heading = get_sub_field('heading');
		$text = get_sub_field('text');
		$button_url = get_sub_field('button_url');
		$button_label = get_sub_field('button_label');

		$slide = <<<EOH
<div class="slide text-center">
	%s
	<h2>%s</h2>
	%s
	%s
</div>
EOH;
		$button = '';
		if (!empty($button_url) && !empty($button_label)) {
			$button = '<a href="'.$button_url.'" class="button">'.$button_label.'</a>';
		}
		$slides[] = sprintf($slide, wp_get_attachment_image($image['id'], 'full'), $heading, wpautop($text), $button);

		$slide_count++;
		if ($is_preview) {
			break;
		}
	}
	spark_generate_carousel($slides);
?>
	</div>
<?php
if ($is_preview) {
?>
	<style>
		#<?php echo esc_attr($id); ?> {max-width: 100%; margin-left: 0;}
		#<?php echo esc_attr($id); ?> .slide {float: left; width: 100%);}
	</style>
<?php
	}
} elseif (current_user_can('edit_post', $post_id)) {
?>
	<p><?php _e('Please add some slides.', 'spark_theme'); ?></p>
<?php
}
?>
</div>
