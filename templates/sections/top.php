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
<div id="row-top" class="grid-container full">
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
echo '<!-- END:'.$file.' -->'."\n";
