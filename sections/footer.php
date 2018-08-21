<?php
/**
 * @version F.1.0
 *
 */

// -------------------------------
// 1. setup the post and the data
// -------------------------------
global $post;
extract(Spark_Theme::setup_data(__FILE__));
$t_period = LONG_TERM;

// -------------------------------------------
// 2. setup local css transient for this file
// -------------------------------------------
$t_args = array('name' => 'css', 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (!Spark_Transients::use_transients()) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();
?>
<style>
/* START: <?php echo $file.' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {
    #row-footer {padding-top:1rem; border-top: 1px solid <?php echo spark_get_theme_mod('colour4'); ?>; background: <?php echo spark_get_theme_mod('colour3'); ?>;}
    #row-footer, #row-footer a {color: <?php echo spark_get_theme_mod('colour1'); ?>}
    #row-footer .logo {width: 100%; padding-bottom:1rem;}
/*     #row-footer .logo-registered {max-width: 80%; padding-bottom: 1rem;} */
    #row-footer .about {padding-top: 1rem; font-size: 1rem; font-weight: 400;}

    #row-footer .search_wrapper {padding-bottom: 1rem;}
    #row-footer .search_wrapper * {color: <?php echo spark_get_theme_mod('colour2'); ?>;}
    #row-footer .search_wrapper svg {margin-right: 0.5rem; padding: 0;}
    #row-footer .search_wrapper .search-form {border: 2px solid <?php echo spark_get_theme_mod('colour6');?>; position:relative;}
    #row-footer .search_wrapper .search-form input {margin-bottom:0; border-radius: 0; padding-left: 2rem;}
    #row-footer .search_wrapper .search-form input[type=submit] {position: absolute; top: 0; right: 0; padding: 0.65rem 1rem; border:none; border-radius: 0; background-color: <?php echo spark_get_theme_mod('colour6');?>; color:<?php echo spark_get_theme_mod('colour1');?>;}
    #row-footer .search_wrapper .search-form input {padding-left: 2rem;}
    #row-footer .search_wrapper .button {position: absolute; margin-top: -2.7rem; background-color: transparent;}

    #row-footer hr {margin-top:0; border: 2px solid <?php echo spark_get_theme_mod('colour6');?>;}
    #row-footer .h4 {font-size: 1.2rem; margin-bottom: 0.4rem;}

    #row-footer li {text-align: center; list-style: none; font-size: 1.2rem; padding-bottom: 0.4rem;}
    #row-footer li > a {color: <?php echo spark_get_theme_mod('colour2');?>; font-size: 1.2rem;}
    #row-footer ul {margin-left: 0;}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
    #row-footer li > a {font-size: 0.9rem;}
    #row-footer .h4 {margin-bottom: 0.5rem;}
    #row-footer li {text-align: left; padding-left: 0; padding-right: 1.5rem; padding-bottom: 0.2rem;}
    #row-footer .contact {padding-top: 0.2rem;}
    #row-footer .subscribe .gform_body {display: inline-block !important; width: 100% !important; max-width:100% !important;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
    #row-footer {padding-top:2rem;}
}
@media only screen and (min-width: <?php echo ROW_MAX_WIDTH; ?> ) {}
@media only screen and (min-width: <?php echo SITE_MAX_WIDTH; ?> ) {}
/* END: <?php echo $file; ?> */
</style>
<?php
    $ob = ob_get_clean();
    set_transient($transient, $ob, $t_period);
}
echo $ob;
unset($ob, $t_args, $transient);

// ----------------------------
// 4. setup output transient/s
// ----------------------------
$t_args = array('name' => 'markup'.$transient_suffix, 'file' => $file);
$transient = Spark_Transients::name($t_args);
if (!Spark_Transients::use_transients()) {
    delete_transient($transient);
}
if (false === ($ob = get_transient($transient))) {
    ob_start();

    // section content - start
    echo '<!-- START: '.$file.' -->'."\n";

    // section content
    $logo_footer = spark_get_theme_mod(ns_.'logo_footer');
    $footer_text = spark_get_theme_mod(ns_.'footer_text');
    $email = spark_get_theme_mod(ns_.'contact_email');
    $phone = spark_get_theme_mod(ns_.'contact_phone');
    $address = spark_get_theme_mod(ns_.'contact_address');
?>
<div class="small-24 medium-7 cell hide-for-print">
<?php
    if (!empty($logo_footer)) {
        echo '<img class="logo" src="'.$logo_footer.'" alt="">'."\n";
    }
    if (!empty($footer_text)) {
        echo '<p class="h3 about">'.$footer_text.'</p>'."\n";
    }
    echo '<div class="search_wrapper">'."\n";
    get_search_form();
    echo '  <a href="#" class="button button-search margin-zero radius-zero" onclick="jQuery(this).parent().find(\'form.search-form\').submit();">'."\n";
    echo '      <span class="show-for-sr">Search</span>'."\n";
    echo '      <span aria-hidden="true"><i class="fa fa-search margin-zero" aria-hidden="true"></i></span>'."\n";
    echo '  </a>'."\n";
    echo '</div>'."\n";
?>
</div>
<div class="show-for-medium medium-11 cell grid-x grid-margin-x hide-for-print">
    <ul class="no-bullet medium-12 cell">
        <?php spark_menu(array('menu' => 'footer-left', 'display_children' => true)); ?>
    </ul>
    <ul class="no-bullet medium-12 cell">
        <?php spark_menu(array('menu' => 'footer-right','display_children' => true)); ?>
    </ul>
</div>
<div class="show-for-small-only small-24 cell hide-for-print">
    <ul class="no-bullet grid-x grid-margin-x small-up-1">
        <?php spark_menu(array('menu' => 'footer-left', 'display_children' => false, 'li_class' => 'cell')); ?>
        <?php spark_menu(array('menu' => 'footer-right', 'display_children' => false, 'li_class' => 'cell')); ?>
    </ul>
</div>
<div class="small-24 medium-6 cell contact">
    <p class="h4">Contact Us</p>
    <hr class="menu-title">
    <div class="grid-x grid-margin-x">
        <div class="small-3 cell"><i class="fas fa-map-marker-alt fa-fw" aria-hidden="true"></i></div>
        <div class="small-21 cell"><?php echo apply_filters('the_content', $address); ?></div>
        <div class="small-3 cell"><i class="fas fa-envelope fa-fw" aria-hidden="true"></i></div>
        <div class="small-21 cell"><p><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p></div>
        <div class="small-3 cell"><i class="fas fa-phone fa-fw" aria-hidden="true"></i></div>
        <div class="small-21 cell"><p><a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a></p></div>
    </div>
</div>
<?php
    // section content - end
    echo '<!-- END:'.$file.' -->'."\n";

    $ob = ob_get_clean();
    set_transient($transient, $ob, $t_period);
}
echo $ob;
unset($ob, $t_args, $transient);
