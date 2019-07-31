<style>
/* START: <?php echo $section_args['filename'].' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {
	#row-breadcrumbs #breadcrumbs .breadcrumb_last {display: none;}
	.bbconnect {background-color: <?php echo spark_get_theme_mod('colour9');?>; padding: 0.5rem 2rem; border-radius: 4px;}
	.bbconnect_wrapper {margin-bottom: 2rem;}
	.gform_wrapper ul.gfield_checkbox li label, .gform_wrapper ul.gfield_radio li label {vertical-align: text-top!important;}
	.gform_wrapper .gform_footer:not(.top_label) { margin-left: 0!important;}

}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */

}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
	.bbconnect {width: 60%;float: left;}
}
@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}
@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo $section_args['filename']; ?> */
</style>
<?php
/*
 * Template Name: Thank you
 */
get_header();
ob_start();
Spark_Theme::section('name=content&file=content.php');
$ob = ob_get_clean();
$entry = GFAPI::get_entry($_GET['e']);
switch ($_GET['f']) {
    case bb_cart_get_checkout_form():
        $ob = str_replace('%%FirstName%%', $entry["1.3"], $ob);
        $ob = str_replace('%%Amount%%', GFCommon::to_money($entry["payment_amount"], $entry['currency']), $ob);
        break;
}
echo $ob;
get_footer();
