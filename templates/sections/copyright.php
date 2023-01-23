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
<footer id="row-copyright" class="grid-container full">
	<div id="row-inner-copyright" class="grid-x grid-margin-x">
		<div class="small-24 text-center medium-text-left cell hide-for-print">
			<?php echo spark_get_theme_mod('copyright').date(' Y'); ?> | <a href="/privacy-policy/"><?php _e('Privacy Policy', 'spark_theme'); ?></a> <p class="float-right"><a href="https://sparkweb.com.au/" target="_blank"><?php _e('Website design and development by Spark Web Solutions', 'spark_theme'); ?></a></p>
		</div>
	</div>
</footer>
<?php
echo '<!-- END:'.$file.' -->'."\n";
