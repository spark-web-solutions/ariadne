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
<div id="row-children-as-paragraphs" class="grid-container">
	<div id="row-inner-children-as-paragraphs" class="grid-x grid-margin-x">
		<aside class="small-24 medium-8 large-5 cell side-wrapper">
			<?php get_sidebar('children-as-paragraphs'); ?>
		</aside>
		<div class="small-24 medium-16 large-19 cell">
			<article <?php post_class() ?>>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>
<?php
	$children = spark_get_children($post);
	foreach ($children as $child) {
		echo '<hr class="pre-paragraph">';
		echo spark_get_card(array('card' => 'paragraph', 'ID' => $child->ID));
	}
?>
		</div>
	</div>
</div>
<?php
echo '<!-- END:'.$file.' -->'."\n";
