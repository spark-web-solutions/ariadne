<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<?php
$favicon = spark_get_theme_mod(ns_.'favicon');
if ($favicon) {
    echo '        <link rel="icon" href="'.$favicon.'" type="image/png">'."\n";
}
if (!empty(spark_get_theme_mod('gf'))) { // Add preconnect for Google Fonts to improve performance
?>
        <link href="//fonts.gstatic.com" rel="preconnect" crossorigin>
<?php
}
wp_head();
?>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

//     ga('create', 'UA-67704837-1', 'auto'); @todo
    ga('send', 'pageview');
</script>
<style>
/* START: <?php echo basename(__FILE__).' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */ }
@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}
@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo basename(__FILE__); ?> */
</style>
    </head>
    <body <?php body_class(Spark_Theme::classes()); ?>>
    <!-- start everything -->
    <div class="everything">
<?php locate_template(array('sections/offcanvas.php'), true); ?>
        <div class="off-canvas-content" data-off-canvas-content>
            <header class="hide-for-print clearfix">
<?php
Spark_Theme::section('name=hero&file=hero.php&class=full&inner_class=relative hero-height&grid_type=');
?>
            </header>
            <section class="main-section">
<?php
if (!is_front_page()) {
    Spark_Theme::section('name=breadcrumbs&file=breadcrumbs.php');
}
spark_show_panels('top');
