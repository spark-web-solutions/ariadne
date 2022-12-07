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
<footer id="row-copyright" class="grid-container">
	<div id="row-inner-copyright" class="grid-x grid-margin-x">
		<div class="small-24 text-center medium-text-left cell hide-for-print">
			<?php echo spark_get_theme_mod('copyright').date(' Y'); ?> | <a href="/privacy-policy/">Privacy Policy</a> <p class="float-right"><a href="https://sparkweb.com.au/" target="_blank">Website design and development by Spark Web Solutions</a></p>
		</div>
	</div>
</footer>
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
