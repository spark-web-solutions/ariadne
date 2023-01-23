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
<div class="off-canvas position-right" id="offCanvas" data-off-canvas>
	<button class="close-button" aria-label="Close menu" type="button" data-close>
		<i class="la la-times" aria-hidden="true"></i>
	</button>
<?php
$args = array(
		'container' => 'nav',
		'theme_location' => 'main',
		'menu_class' => 'vertical',
);
spark_nav_menu($args, 'accordion');
?>
</div>
<?php
echo '<!-- END: '.$file.' -->'."\n";
