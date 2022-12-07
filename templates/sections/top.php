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
<div id="row-top" class="grid-container">
	<div id="row-inner-top" class="grid-x grid-margin-x">
		<div class="cell">
			<nav class="top-bar">
				<section class="top-bar-left">
					<?php the_custom_logo(); ?>
				</section>
				<section class="top-bar-right">
					<a href="#" class="float-right hide-for-medium" type="button" data-open="offCanvas"><i class="la la-bars la-3x bg1 text3 hbg3 htext1" aria-hidden="true"></i></a>
<?php
$args = array(
		'theme_location' => 'main',
		'container_class' => 'header-nav main-nav show-for-medium',
);
spark_nav_menu($args, 'dropdown');
?>
				</section>
			</nav>
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
