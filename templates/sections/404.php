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
		<article <?php post_class('small-24 cell'); ?>>
			<h1>Whoops!</h1>
			<p>Sorry, the page you tried to access does not exist. It may have been removed or you might have followed an invalid link.</p>
			<p>You can browse the site using the available menu options, or try searching for what you were looking for:</p>
			<?php get_search_form(); ?>
		</article>
	</div>
</div>
<?php
echo '<!-- END: '.$file.' -->'."\n";
