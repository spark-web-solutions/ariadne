<?php
/**
 * Children as paragraph card
 * @var $ID integer Post ID
 */
$child = get_post($ID);
if (!empty($child->post_excerpt)) {
	$content = apply_filters('the_content', $child->post_excerpt);
} else {
	$content = apply_filters('the_content', $child->post_content);
}
$read_more_link = !empty($child->post_excerpt) || spark_has_children($child->ID) ? '<p class="text-right read-more"><a class="button" href="'.get_the_slug($child->ID).'">'._('Read More', 'spark_theme').'</a></p>' : '';
?>
<div id="<?php echo $child->post_name; ?>" class="paragraph-card grid-x grid-margin-x">
	<p class="small-24 cell h2 show-for-medium"><?php echo $child->post_title; ?></p>
	<div class="small-24 cell content">
		<p class="h2 hide-for-medium"><?php echo $child->post_title; ?></p>
		<?php echo $content; ?>
		<?php echo $read_more_link; ?>
	</div>
</div>
