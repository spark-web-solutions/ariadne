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

echo '<!-- START: '.$file.' -->'."\n";
?>
<div id="row-breadcrumbs" class="grid-container">
	<div id="row-inner-breadcrumbs" class="grid-x grid-margin-x">
<?php
	if (function_exists('yoast_breadcrumb') && !is_front_page()) {
		echo '<div class="small-24 cell show-for-medium">'."\n";
		yoast_breadcrumb('<p id="breadcrumbs">','</p>');
		echo '</div>'."\n";
	}
?>
	</div>
</div>
<?php
echo '<!-- END:'.$file.' -->'."\n";
