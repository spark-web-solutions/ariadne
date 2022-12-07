<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php
// $favicon = spark_get_theme_mod(ns_.'favicon');
// if ($favicon) {
// 	echo '		<link rel="icon" href="'.$favicon.'" type="image/png">'."\n";
// }
if (!empty(spark_get_theme_mod('gf'))) { // Add preconnect for Google Fonts to improve performance
?>
	<link href="//fonts.gstatic.com" rel="preconnect" crossorigin>
<?php
}
wp_head();
?>
</head>
<body <?php body_class(spark_body_classes()); ?>>
	<?php wp_body_open(); ?>
	<!-- start everything -->
	<div id="everything">
		<a class="skip-link show-for-sr" href="#content"><?php esc_html_e('Skip to content', ns_); ?></a>
<?php get_template_part('templates/sections/offcanvas'); ?>
		<div class="off-canvas-content" data-off-canvas-content>
			<header class="hide-for-print clearfix">
<?php
get_template_part('templates/sections/top');
get_template_part('templates/sections/hero');
?>
			</header>
			<div id="content">
<?php
spark_show_panels('top');
if (!is_front_page()) {
	get_template_part('templates/sections/breadcrumbs');
}
?>
				<main id="main" role="main">
