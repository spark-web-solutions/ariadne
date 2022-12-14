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
    // section content - end
    echo '<!-- END:'.$file.' -->'."\n";

	$ob = ob_get_clean();
	if (Spark_Transients::use_transients($ob)) {
		Spark_Transients::set($transient, $ob);
	}
}
echo $ob;
unset($ob, $t_args, $transient);
