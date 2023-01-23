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
<div id="row-children-as-accordion" class="grid-container">
	<div id="row-inner-children-as-accordion" class="grid-x grid-margin-x">
		<div class="small-24 medium-15 large-17 cell">
			<h1><?php the_title(); ?></h1>
			<article <?php post_class() ?>>
				<?php the_content(); ?>
			</article>
			<div class="accordion" data-accordion data-allow-all-closed="true">
<?php
	$children = spark_get_children($post);
	foreach ($children as $child) {
		$slug = get_the_slug($child->ID);
		$title = get_the_title($child);
		$content = apply_filters('the_content', $child->post_content);
?>
				<article id="<?php echo $slug; ?>" class="<?php post_class('child accordion-item', $child->ID); ?>" data-accordion-item>
					<a href="#" class="accordion-title"><span class="h2"><?php echo $title; ?></span></a>
					<div class="accordion-content" data-tab-content>
						<?php echo $content; ?>
					</div>
				</article>
<?php
	}
?>
			</div>
		</div>
		<aside class="small-24 medium-9 large-7 cell">
			<?php get_sidebar(); ?>
		</aside>
	</div>
</div>
<?php
echo '<!-- END:'.$file.' -->'."\n";
