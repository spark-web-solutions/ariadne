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
<div id="row-children-as-paragraphs" class="grid-container">
	<div id="row-inner-children-as-paragraphs" class="grid-x grid-margin-x">
		<aside class="small-24 medium-8 large-5 cell side-wrapper">
			<?php get_sidebar('children-as-paragraphs'); ?>
		</aside>
		<div class="small-24 medium-16 large-19 cell">
			<article <?php post_class() ?>>
				<h1><?php echo apply_filters('the_title', $post->post_title); ?></h1>
				<?php echo apply_filters('the_content', $post->post_content); ?>
			</article>
<?php
	$children = spark_get_children($post);
	foreach ($children as $child) {
		echo '<hr class="pre-paragraph">';
		echo get_card(array('card' => 'paragraph', 'ID' => $child->ID));
	}
?>
		</div>
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
