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
$t_args = array('name' => 'markup'.$transient_suffix, 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (false === ($ob = get_transient($transient)) || !Spark_Transients::use_transients($ob)) {
	ob_start();

	// section content - start
	echo '<!-- START: '.$file.' -->'."\n";

	// section content
?>
<div id="row-content" class="grid-container">
	<div id="row-inner-content" class="grid-x grid-margin-x">
		<div class="small-24 cell no-sidebar">
<?php
    if ($archive_page) {
        echo '    <h1>'.$archive_page->post_title.'</h1>'."\n";
        echo apply_filters('the_content', $archive_page->post_content);
    }
    echo '<div class="grid-x grid-margin-x small-up-1 medium-up-2 large-up-3">'."\n";
    while (have_posts()) {
        the_post();
        echo spark_get_card('card=post-preview&ID='.get_the_id());
    }
    echo '</div>'."\n";
    echo spark_foundation_pagination();
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
