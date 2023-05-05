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

$logo_footer = spark_get_theme_mod(ns_.'logo_footer');
$footer_text = spark_get_theme_mod(ns_.'footer_text');
$email = spark_get_theme_mod(ns_.'contact_email');
$phone = spark_get_theme_mod(ns_.'contact_phone');
$address = spark_get_theme_mod(ns_.'contact_address');
?>
<footer id="row-footer" class="grid-container full">
	<div id="row-inner-footer" class="grid-x grid-margin-x">
		<div class="small-24 medium-8 cell">
<?php
if (!empty($logo_footer)) {
	echo '<img class="logo" src="'.$logo_footer.'" alt="">'."\n";
}
if (!empty($footer_text)) {
	echo '<p class="about">'.$footer_text.'</p>'."\n";
}
echo '<div class="search_wrapper">'."\n";
get_search_form();
echo '	<a class="button button-search margin-zero radius-zero" onclick="jQuery(this).parent().find(\'form.search-form\').submit();">'."\n";
echo '		<span class="show-for-sr">Search</span>'."\n";
echo '		<span aria-hidden="true"><i class="la la-search margin-zero" aria-hidden="true"></i></span>'."\n";
echo '	</a>'."\n";
echo '</div>'."\n";
?>
		</div>
		<div class="small-24 medium-8 cell">
<?php
$args = array(
		'theme_location' => 'footer',
		'container' => 'nav',
		'container_class' => 'footer-nav',
		'menu_class' => 'vertical',
);
spark_nav_menu($args);
?>
		</div>
		<div class="small-24 medium-6 cell contact">
			<p class="h4">Contact Us</p>
			<hr class="menu-title">
			<div class="grid-x grid-margin-x">
<?php
if (!empty($address)) {
?>
				<div class="small-3 cell"><i class="la la-map-marker" aria-hidden="true"></i></div>
				<div class="small-21 cell"><?php echo nl2br($address); ?></div>
<?php
}
if (!empty($email)) {
?>
				<div class="small-3 cell"><i class="la la-envelope" aria-hidden="true"></i></div>
				<div class="small-21 cell"><p><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p></div>
<?php
}
if (!empty($phone)) {
?>
				<div class="small-3 cell"><i class="la la-phone" aria-hidden="true"></i></div>
				<div class="small-21 cell"><p><a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a></p></div>
			</div>
<?php
}
?>
		</div>
	</div>
</footer>
<?php
echo '<!-- END:'.$file.' -->'."\n";
