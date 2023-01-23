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

global $post;
echo '<!-- START: '.$file.' -->'."\n";
?>
<div id="row-children-as-tiles" class="grid-container">
	<div id="row-inner-children-as-tiles" class="grid-x grid-margin-x">
		<div class="small-24 medium-15 large-17 cell">
			<h1><?php the_title(); ?></h1>
			<article <?php post_class() ?>>
				<?php echo apply_filters('the_content', $post->post_content); ?>
			</article>
			<div class="grid-x grid-margin-x small-up-1 medium-up-2 large-up-3 child-tiles text-center">
<?php
$children = spark_get_children();
foreach ($children as $child) {
	$image = get_value_from_hierarchy('featured_image', $child->ID);
	if (!empty($image)) {
		$args = array(
				'ID'	=> $child->ID,
				'card'  => 'tile',
				'image' => $image,
		);
		echo spark_get_card($args);
	}
}
?>
			</div>
		</div>
		<aside class="small-24 medium-9 large-7 column">
			<?php get_sidebar(); ?>
		</aside>
	</div>
</div>
<?php
echo '<!-- END:'.$file.' -->'."\n";
