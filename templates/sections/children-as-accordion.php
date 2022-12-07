<?php
// ------------------
// 1. Setup the data
// ------------------
/**
 * @var string $file Current file
 * @var array $meta Meta data for current post
 * @var array $ancestors List of ancestor IDs for current post
 * @var string $ancestor_string Underscore-separated list of ancestor IDs for current post
 * @var integer $archive_page ID of the archive page that corresponds to the current request
 * @var string $transient_suffix Unique transient suffix for current section and post
 **/
extract(spark_setup_data(__FILE__));

// -------------------
// 2. Generate output
// -------------------
global $post;
$t_args = array('name' => 'markup'.$transient_suffix, 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (false === ($ob = get_transient($transient)) || !Spark_Transients::use_transients($ob)) {
	ob_start();

	// section content - start
	echo '<!-- START: '.$file.' -->'."\n";

	// section content
?>
<div id="row-children-as-accordion" class="grid-container">
	<div id="row-inner-children-as-accordion" class="grid-x grid-margin-x">
		<div class="small-24 medium-15 large-17 cell">
			<h1><?php the_title(); ?></h1>
			<article <?php post_class() ?>>
				<?php echo apply_filters('the_content', $post->post_content); ?>
			</article>
			<div class="accordion" data-accordion data-allow-all-closed="true">
<?php
	$children = spark_get_children($post);
	$tmp_post = $post;
	foreach ($children as $post) {
		setup_postdata($post);
		$slug = get_the_slug($post->ID);
		$title = get_the_title($post);
		$content = apply_filters('the_content', $post->post_content);
?>
				<article id="<?php echo $slug; ?>" class="<?php post_class('child accordion-item', $post->ID); ?>" data-accordion-item>
					<a href="#" class="accordion-title"><span class="h2"><?php echo $title; ?></span></a>
					<div class="accordion-content" data-tab-content>
						<?php echo $content; ?>
					</div>
				</article>
<?php
	}
	$post = $tmp_post;
?>
			</div>
		</div>
		<aside class="small-24 medium-9 large-7 cell">
			<?php get_sidebar(); ?>
		</aside>
	</div>
</div>
<?php
	// section content - end
	echo '<!-- END:'.$file.' -->'."\n";

	$ob = ob_get_clean();
	if (Spark_Transients::use_transients($ob)) {
		Spark_Transients::set($transient, $ob);
	}
}
echo $ob;
unset($ob, $t_args, $transient);
