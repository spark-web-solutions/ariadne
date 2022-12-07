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
	$children = spark_get_children($post);
?>
<div id="row-children-as-tabs" class="grid-container">
	<div id="row-inner-children-as-tabs" class="grid-x grid-margin-x">
		<div class="small-24 cell">
			<div class="children-as-tabs grid-x grid-margin-x" data-equalizer="tabs" data-equalize-on="medium">
				<aside class="medium-8 large-5 cell" data-equalizer-watch="tabs">
					<?php get_sidebar('children-as-tabs'); ?>
				</aside>
				<div class="small-24 medium-16 large-19 float-left cell">
					<h1><?php echo $post->post_title; ?></h1>
					<div class="tabs-content vertical" data-tabs-content="children-as-tabs">
<?php
	$is_active = false;
	if (!empty($post->post_content)) {
		echo '<div class="tabs-panel is-active" id="'. $post->post_name .'" data-equalizer-watch="tabs">'."\n";
		echo apply_filters('the_content', $post->post_content);
		echo '</div>'."\n";
		$is_active = true;
	}

	foreach ($children as $child) {
		if ($is_active == false) {
			$class = 'is-active';
			$is_active = true;
		} else {
			$class = '';
		}
		$slug = get_the_slug($child->ID);
		echo '<div class="tabs-panel '.$class.'" id="'. $slug .'" data-equalizer-watch="tabs">'."\n";
		echo '<h2>'.$child->post_title.'</h2>'."\n";
		echo apply_filters('the_content', $child->post_content);
		echo '</div>'."\n";
		if (spark_has_children($child->ID)) { // If child page has children of its own, add them too
			foreach (spark_get_children($child) as $grandchild) {
				echo '<div class="tabs-panel" id="'.get_the_slug($grandchild->ID).'" data-equalizer-watch>'."\n";
				echo '<h2>'.$grandchild->post_title.'</h2>'."\n";
				echo apply_filters('the_content', $grandchild->post_content);
				echo '</div>'."\n";
			}
		}
		unset($class);
	}
?>
					</div>
				</div>
			</div>
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
