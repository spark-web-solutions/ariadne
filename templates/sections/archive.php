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
<div id="row-content" class="grid-container">
	<div id="row-inner-content" class="grid-x grid-margin-x">
		<div class="small-24 cell no-sidebar">
<?php
if ($archive_page) {
	if (!empty(spark_get_post_meta($archive_page, 'hide_title'))) {
?>
			<h1><?php echo $archive_page->post_title; ?></h1>
<?php
	}
	echo apply_filters('the_content', $archive_page->post_content);
}
if (is_search()) {
	get_search_form();
}
?>
			<div class="grid-x grid-margin-x small-up-1 medium-up-2 large-up-3" data-equalizer data-equalize-by-row="true">
<?php
while (have_posts()) {
	the_post();
	echo spark_get_card('card=post-preview&ID='.get_the_id());
}
?>
			</div>
<?php
echo spark_foundation_pagination();
?>
		</div>
	</div>
</div>
<?php
echo '<!-- END:'.$file.' -->'."\n";
