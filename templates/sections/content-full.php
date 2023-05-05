<?php
/**
 * @var string $file Current file
 * @var array $meta Meta data for current post
 * @var array $ancestors List of ancestor IDs for current post
 * @var string $ancestor_string Underscore-separated list of ancestor IDs for current post
 * @var integer $archive_page ID of the archive page that corresponds to the current request
 * @var string $transient_suffix Unique transient suffix for current section and post
 **/
extract(spark_setup_data(__FILE__));

echo '<!-- START: '.$file.' -->'."\n";

$class = 'small-24 cell no-sidebar';
?>
<div id="row-content" class="grid-container">
	<div id="row-inner-content" class="grid-x grid-margin-x">
<?php
if (!is_singular()) {
?>
		<div class="<?php echo $class; ?>">
<?php
	$class = '';
}
while (have_posts()) {
	the_post();
?>
			<article <?php post_class($class); ?>>
<?php
	if (!is_front_page() && !empty(spark_get_post_meta($post, 'hide_title'))) {
?>
				<h1><?php the_title(); ?></h1>
<?php
	}
	the_content();
	if (is_singular() && (comments_open() || get_comments_number())) {
 		comments_template();
	}
?>
			</article>
<?php
}
if (!is_singular()) {
?>
		</div>
<?php
}
?>
	</div>
</div>
<?php
echo '<!-- END:'.$file.' -->'."\n";
